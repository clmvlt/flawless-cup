<?php

namespace App\Controllers;

class Twitch extends BaseController
{
    public function getStatus()
    {
        try {
            $username = 'urikaynee';
            $client = \Config\Services::curlrequest();
            
            // Double vérification avec deux APIs différentes
            $isLive = false;
            $viewers = 0;
            $game = null;
            $title = null;
            $debugInfo = [];
            
            // API 1: StreamElements API (plus fiable)
            try {
                $url1 = "https://api.streamelements.com/kappa/v2/chatstats/{$username}";
                $response1 = $client->get($url1, [
                    'timeout' => 8,
                    'headers' => ['User-Agent' => 'Mozilla/5.0 (compatible; FlawlessCup/1.0)']
                ]);
                
                if ($response1->getStatusCode() === 200) {
                    $streamData = json_decode($response1->getBody(), true);
                    
                    // Vérifier si le dernier message est récent (moins de 5 minutes)
                    $lastMessage = isset($streamData['lastMessage']) ? $streamData['lastMessage'] : null;
                    $isRecentActivity = false;
                    
                    if ($lastMessage) {
                        $lastMessageTime = strtotime($lastMessage);
                        $currentTime = time();
                        $timeDiff = $currentTime - $lastMessageTime;
                        $isRecentActivity = $timeDiff < 300; // 5 minutes en secondes
                    }
                    
                    $api1Live = $isRecentActivity;
                    $debugInfo['api1_response'] = 'Last message: ' . ($lastMessage ?? 'none') . ' (' . ($isRecentActivity ? 'recent' : 'old') . ')';
                    $debugInfo['api1_live'] = $api1Live;
                } else if ($response1->getStatusCode() === 404) {
                    // 404 = pas de stream actif
                    $api1Live = false;
                    $debugInfo['api1_response'] = 'Stream not found (404)';
                    $debugInfo['api1_live'] = $api1Live;
                } else {
                    $debugInfo['api1_error'] = 'HTTP ' . $response1->getStatusCode();
                }
            } catch (\Exception $e) {
                $debugInfo['api1_error'] = $e->getMessage();
            }
            
            // API 2: decapi.me uptime (si uptime existe = live)
            try {
                $url2 = "https://decapi.me/twitch/uptime/{$username}";
                $response2 = $client->get($url2, [
                    'timeout' => 8,
                    'headers' => ['User-Agent' => 'Mozilla/5.0 (compatible; FlawlessCup/1.0)']
                ]);
                
                if ($response2->getStatusCode() === 200) {
                    $uptime = trim($response2->getBody());
                    $api2Live = $uptime !== $username . ' is not streaming' && 
                               $uptime !== 'Channel offline' && 
                               $uptime !== $username . ' is offline' &&
                               !str_contains(strtolower($uptime), 'offline');
                    $debugInfo['api2_response'] = $uptime;
                    $debugInfo['api2_live'] = $api2Live;
                } else {
                    $debugInfo['api2_error'] = 'HTTP ' . $response2->getStatusCode();
                }
            } catch (\Exception $e) {
                $debugInfo['api2_error'] = $e->getMessage();
            }
            
            // Décision finale : LIVE si AU MOINS une des deux APIs confirme (plus souple)
            $isLive = (isset($debugInfo['api1_live']) && $debugInfo['api1_live']) || 
                      (isset($debugInfo['api2_live']) && $debugInfo['api2_live']);
                      
            $debugInfo['final_decision'] = $isLive;
                
                // Si en live, récupérer plus d'infos
                if ($isLive) {
                    try {
                        $gameResponse = $client->get("https://decapi.me/twitch/game/{$username}", ['timeout' => 5]);
                        if ($gameResponse->getStatusCode() === 200) {
                            $game = trim($gameResponse->getBody());
                        }
                        
                        $titleResponse = $client->get("https://decapi.me/twitch/title/{$username}", ['timeout' => 5]);
                        if ($titleResponse->getStatusCode() === 200) {
                            $title = trim($titleResponse->getBody());
                        }
                    } catch (\Exception $e) {
                        $debugInfo['info_error'] = $e->getMessage();
                    }
                }
                
                return $this->response->setJSON([
                    'isLive' => $isLive,
                    'channel' => $username,
                    'game' => $game,
                    'title' => $title,
                    'viewers' => $viewers,
                    'debug' => $debugInfo
                ]);
            
            // Fallback: TwitchTracker si decapi échoue
            $fallbackUrl = "https://twitchtracker.com/api/channels/summary/{$username}";
            $fallbackResponse = $client->get($fallbackUrl, [
                'timeout' => 5,
                'headers' => ['User-Agent' => 'Mozilla/5.0']
            ]);
            
            if ($fallbackResponse->getStatusCode() === 200) {
                $data = json_decode($fallbackResponse->getBody(), true);
                $isLive = isset($data['is_live']) && $data['is_live'] === true;
                
                return $this->response->setJSON([
                    'isLive' => $isLive,
                    'channel' => $username,
                    'game' => $data['game'] ?? null,
                    'title' => $data['title'] ?? null,
                    'viewers' => $data['viewers'] ?? 0,
                    'debug' => $data
                ]);
            }
            
            // Dernier fallback: retourner hors ligne si aucune API ne répond
            return $this->response->setJSON([
                'isLive' => false,
                'channel' => $username,
                'error' => 'All APIs unavailable'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Twitch API error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'isLive' => false,
                'channel' => 'urikaynee',
                'error' => 'Service error: ' . $e->getMessage()
            ]);
        }
    }
}