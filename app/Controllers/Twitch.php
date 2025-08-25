<?php

namespace App\Controllers;

class Twitch extends BaseController
{
    public function getStatus()
    {
        try {
            $username = 'urikaynee';
            $client = \Config\Services::curlrequest();
            
            $isLive = false;
            $viewers = 0;
            $game = null;
            $title = null;
            $debugInfo = [];
            
            // API Principale : decapi.me uptime (plus précise pour le statut live)
            try {
                $url = "https://decapi.me/twitch/uptime/{$username}";
                $response = $client->get($url, [
                    'timeout' => 10,
                    'headers' => ['User-Agent' => 'Mozilla/5.0 (compatible; FlawlessCup/1.0)']
                ]);
                
                if ($response->getStatusCode() === 200) {
                    $uptime = trim($response->getBody());
                    
                    // Vérifications strictes pour déterminer si offline
                    $offlineKeywords = [
                        $username . ' is not streaming',
                        'Channel offline',
                        $username . ' is offline',
                        'offline',
                        'not streaming',
                        'channel does not exist'
                    ];
                    
                    $isOffline = false;
                    foreach ($offlineKeywords as $keyword) {
                        if (str_contains(strtolower($uptime), strtolower($keyword))) {
                            $isOffline = true;
                            break;
                        }
                    }
                    
                    // Si on a un uptime valide (format durée), alors c'est live
                    $isValidUptime = preg_match('/^\d+[dhms]/', $uptime) || preg_match('/^\d+:\d+/', $uptime);
                    
                    $isLive = !$isOffline && $isValidUptime;
                    
                    $debugInfo['uptime_response'] = $uptime;
                    $debugInfo['is_offline_detected'] = $isOffline;
                    $debugInfo['is_valid_uptime'] = $isValidUptime;
                    $debugInfo['primary_live'] = $isLive;
                } else {
                    $debugInfo['uptime_error'] = 'HTTP ' . $response->getStatusCode();
                }
            } catch (\Exception $e) {
                $debugInfo['uptime_error'] = $e->getMessage();
            }
            
            // API de vérification : Simple ping vers la chaîne
            try {
                $pingUrl = "https://decapi.me/twitch/followage/{$username}/{$username}";
                $pingResponse = $client->get($pingUrl, [
                    'timeout' => 5,
                    'headers' => ['User-Agent' => 'Mozilla/5.0 (compatible; FlawlessCup/1.0)']
                ]);
                
                $debugInfo['ping_status'] = $pingResponse->getStatusCode();
                $debugInfo['channel_exists'] = $pingResponse->getStatusCode() === 200;
            } catch (\Exception $e) {
                $debugInfo['ping_error'] = $e->getMessage();
            }
            
            $debugInfo['final_decision'] = $isLive;
                
            // Si en live, récupérer plus d'infos
            if ($isLive) {
                try {
                    $gameResponse = $client->get("https://decapi.me/twitch/game/{$username}", ['timeout' => 5]);
                    if ($gameResponse->getStatusCode() === 200) {
                        $game = trim($gameResponse->getBody());
                        if ($game === 'Not playing anything' || $game === '') {
                            $game = null;
                        }
                    }
                    
                    $titleResponse = $client->get("https://decapi.me/twitch/title/{$username}", ['timeout' => 5]);
                    if ($titleResponse->getStatusCode() === 200) {
                        $title = trim($titleResponse->getBody());
                        if ($title === 'No title set' || $title === '') {
                            $title = null;
                        }
                    }
                    
                    // Tentative de récupération du nombre de viewers
                    $viewersResponse = $client->get("https://decapi.me/twitch/viewercount/{$username}", ['timeout' => 5]);
                    if ($viewersResponse->getStatusCode() === 200) {
                        $viewerCount = trim($viewersResponse->getBody());
                        if (is_numeric($viewerCount)) {
                            $viewers = (int)$viewerCount;
                        }
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