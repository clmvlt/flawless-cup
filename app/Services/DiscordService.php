<?php

namespace App\Services;

use CodeIgniter\HTTP\CURLRequest;

class DiscordService
{
    private $botToken;
    private $guildId;
    private $client;

    public function __construct()
    {
        $this->botToken = 'YOUR_BOT_TOKEN';
        $this->guildId = '1405943036953292810';
        $this->client = \Config\Services::curlrequest();
    }

    /**
     * Vérifie si un utilisateur est membre du serveur Discord spécifié
     * 
     * @param string $discordUserId ID Discord de l'utilisateur
     * @return bool True si l'utilisateur est membre du serveur
     */
    public function isUserInGuild($discordUserId)
    {
        try {
            // Utilise l'API custom pour vérifier l'appartenance au serveur Discord
            $url = "http://192.168.1.152:6050/api/checkuser/{$discordUserId}";
            
            $response = $this->client->get($url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 10 // Timeout de 10 secondes
            ]);

            $statusCode = $response->getStatusCode();
            
            if ($statusCode === 200) {
                $responseBody = $response->getBody();
                $data = json_decode($responseBody, true);
                
                if ($data && isset($data['exists'])) {
                    if ($data['exists'] === true) {
                        // L'utilisateur est membre du serveur
                        log_message('info', 'User ' . $discordUserId . ' is member of Discord server (via custom API)');
                        return true;
                    } else {
                        // L'utilisateur n'est pas membre du serveur
                        $errorMsg = isset($data['error']) ? $data['error'] : 'User not found';
                        log_message('info', 'User ' . $discordUserId . ' is NOT member of Discord server: ' . $errorMsg);
                        return false;
                    }
                } else {
                    log_message('error', 'Invalid response format from Discord check API: ' . $responseBody);
                    return false;
                }
            } else {
                // Erreur API
                log_message('error', 'Discord check API error: ' . $statusCode . ' - ' . $response->getBody());
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', 'Discord guild membership check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les informations d'un membre du serveur
     * 
     * @param string $discordUserId ID Discord de l'utilisateur
     * @return array|false Données du membre ou false si pas membre
     */
    public function getGuildMember($discordUserId)
    {
        try {
            $url = "https://discord.com/api/v10/guilds/{$this->guildId}/members/{$discordUserId}";
            
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => 'Bot ' . $this->botToken,
                    'Content-Type' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

            return false;

        } catch (\Exception $e) {
            log_message('error', 'Discord member info fetch failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un utilisateur a un rôle spécifique dans le serveur
     * 
     * @param string $discordUserId ID Discord de l'utilisateur
     * @param string $roleId ID du rôle à vérifier
     * @return bool True si l'utilisateur a le rôle
     */
    public function userHasRole($discordUserId, $roleId)
    {
        $memberData = $this->getGuildMember($discordUserId);
        
        if (!$memberData || !isset($memberData['roles'])) {
            return false;
        }

        return in_array($roleId, $memberData['roles']);
    }

    /**
     * Récupère les informations du serveur Discord
     * 
     * @return array|false Informations du serveur ou false en cas d'erreur
     */
    public function getGuildInfo()
    {
        try {
            $url = "https://discord.com/api/v10/guilds/{$this->guildId}";
            
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => 'Bot ' . $this->botToken,
                    'Content-Type' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

            return false;

        } catch (\Exception $e) {
            log_message('error', 'Discord guild info fetch failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le lien d'invitation permanent du serveur Discord
     * 
     * @return string URL d'invitation du serveur
     */
    public function getGuildInviteUrl()
    {
        // Vous pouvez retourner votre lien d'invitation permanent ici
        return 'https://discord.gg/mkA7tHejGN'; // Remplacez par votre lien
    }

    /**
     * Configuration du service - à appeler pour définir le token du bot
     * 
     * @param string $botToken Token du bot Discord
     * @param string $guildId ID du serveur Discord
     */
    public function configure($botToken, $guildId = null)
    {
        $this->botToken = $botToken;
        if ($guildId) {
            $this->guildId = $guildId;
        }
    }
}