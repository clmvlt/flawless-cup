<?php

namespace App\Controllers;

use App\Models\PlayerModel;
use App\Models\RememberTokenModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $playerModel;
    protected $rememberTokenModel;

    public function __construct()
    {
        $this->playerModel = new PlayerModel();
        $this->rememberTokenModel = new RememberTokenModel();
    }

    public function register()
    {
        $code = $this->request->getGet('code');
        $error = $this->request->getGet('error');

        if ($error) {
            return redirect()->to('/')->with('error', 'Authentification annulée');
        }

        if (!$code) {
            return redirect()->to('/')->with('error', 'Code d\'autorisation manquant');
        }

        

        try {
            $discordToken = $this->getDiscordAccessToken($code);
            if (!$discordToken) {
                return redirect()->to('/')->with('error', 'Erreur lors de l\'authentification Discord');
            }

            $discordUser = $this->getDiscordUserInfo($discordToken);
            if (!$discordUser) {
                return redirect()->to('/')->with('error', 'Impossible de récupérer les informations utilisateur');
            }

            session()->set('discord_raw_data', $discordUser);

            $player = $this->createOrUpdatePlayer($discordUser);

            if (!$player) {
                return redirect()->to('/')->with('error', 'Erreur lors de la création du profil joueur');
            }

            session()->set('player_id', $player['discord_id']);
            session()->set('player_data', $player);

            // Créer un token de connexion persistant
            $this->createRememberToken($player['discord_id']);

            // Vérifier si c'est un nouvel utilisateur (pas de riot_id configuré)
            $isNewUser = !isset($player['riot_id']) || empty($player['riot_id']) || $player['riot_id'] === '';
            
            if ($isNewUser) {
                return redirect()->to('/riot/setup')->with('success', 'Bienvenue ! Configurons votre profil Valorant pour participer au tournoi.');
            } else {
                // Actualiser le MMR pour les utilisateurs existants
                $this->updatePlayerMMR($player['discord_id']);
                return redirect()->to('/dashboard')->with('success', 'Connexion réussie ! Votre MMR a été actualisé.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Discord OAuth Error: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'Une erreur est survenue lors de l\'authentification');
        }
    }

    private function getDiscordAccessToken($code)
    {
        $client = \Config\Services::curlrequest();

        $data = [
            'client_id' => '1405941959792791602',
            'client_secret' => 'oTLfn6zzYTicxrfBEm_Ce1NBg9tEg4Dj',
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'https://flawless-cup.fr/register'
        ];

        try {
            $response = $client->post('https://discord.com/api/oauth2/token', [
                'form_params' => $data,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            if (isset($body['access_token'])) {
                return $body['access_token'];
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Discord token request failed: ' . $e->getMessage());
            return false;
        }
    }

    private function getDiscordUserInfo($accessToken)
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://discord.com/api/users/@me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken
                ]
            ]);

            $userData = json_decode($response->getBody(), true);

            if (isset($userData['id'])) {
                return $userData;
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Discord user info request failed: ' . $e->getMessage());
            return false;
        }
    }

    private function createOrUpdatePlayer($discordUser)
    {
        log_message('info', 'Discord user data received: ' . json_encode($discordUser));

        $discordId = $discordUser['id'];
        $username = $discordUser['username'];
        $discriminator = $discordUser['discriminator'] ?? null;
        $avatar = $discordUser['avatar'] ?? null;

        $tag = $discriminator ? $username . '#' . $discriminator : $username;
        $avatarUrl = $avatar ? 'https://cdn.discordapp.com/avatars/' . $discordId . '/' . $avatar . '.png?size=256' : null;

        $existingPlayer = $this->playerModel->find($discordId);

        $playerData = [
            'discord_id' => $discordId,
            'tag' => $tag,
            'pseudo' => $username,
            'avatar' => $avatarUrl,
            'is_leader' => $existingPlayer ? $existingPlayer['is_leader'] : false,
            'team_id' => null,
            'mmr' => 0
        ];

        try {
            if ($existingPlayer) {
                $updateData = [
                    'tag' => $tag,
                    'pseudo' => $username,
                    'avatar' => $avatarUrl,
                    'mmr' => 0,
                ];
                
                // Tenter la mise à jour avec gestion d'erreur de casting
                try {
                    $this->playerModel->update($discordId, $updateData);
                } catch (\Exception $updateException) {
                    log_message('warning', 'Player update failed with casting error, trying without timestamps: ' . $updateException->getMessage());
                    // En cas d'erreur de casting, essayer une requête directe
                    $db = \Config\Database::connect();
                    $db->table('player')
                       ->where('discord_id', $discordId)
                       ->update($updateData);
                }
                
                $updatedPlayer = $this->playerModel->find($discordId);
                log_message('info', 'Player updated: ' . $discordId . ' - ' . $username);
                return $updatedPlayer;
            } else {
                $this->playerModel->insert($playerData);
                log_message('info', 'New player created: ' . $discordId . ' - ' . $username);
                return $playerData;
            }
        } catch (\Exception $e) {
            log_message('error', 'Player creation/update failed: ' . $e->getMessage());
            log_message('error', 'Player data: ' . json_encode($playerData));
            
            // En cas d'échec complet, essayer de retourner les données existantes
            if ($existingPlayer) {
                log_message('info', 'Returning existing player data due to update failure');
                return $existingPlayer;
            }
            return false;
        }
    }

    private function updatePlayerMMR($playerId)
    {
        try {
            // Charger le service Riot en arrière-plan
            $riotController = new \App\Controllers\Riot();
            $riotController->updateMMR($playerId);
        } catch (\Exception $e) {
            // Ne pas interrompre le processus de connexion si la mise à jour MMR échoue
            log_message('warning', 'MMR update failed during login for player: ' . $playerId . ' - ' . $e->getMessage());
        }
    }

    public function logout()
    {
        // Supprimer le token de connexion persistant
        $rememberToken = $this->request->getCookie('remember_token');
        if ($rememberToken) {
            $this->rememberTokenModel->revokeToken($rememberToken);
            $this->response->deleteCookie('remember_token');
        }

        session()->destroy();
        return redirect()->to('/')->with('success', 'Déconnexion réussie');
    }

    /**
     * Crée un token de connexion persistant et le stocke dans un cookie
     */
    private function createRememberToken($playerId)
    {
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();

        $token = $this->rememberTokenModel->createRememberToken($playerId, $ipAddress, $userAgent, 30); // 30 jours

        if ($token) {
            // Créer un cookie sécurisé qui expire dans 30 jours
            try {
                $this->response->setCookie([
                    'name' => 'remember_token',
                    'value' => $token,
                    'expire' => 30 * 24 * 60 * 60, // 30 jours en secondes
                    'path' => '/',
                    'domain' => '',
                    'secure' => true, // HTTPS uniquement
                    'httponly' => true, // Pas accessible via JavaScript
                    'samesite' => 'Lax'
                ]);
                log_message('info', 'Remember token created for player: ' . $playerId);
            } catch (\Exception $e) {
                log_message('warning', 'Failed to create remember token cookie: ' . $e->getMessage());
            }
        }
    }

    /**
     * Vérifie et utilise un token de connexion persistant pour reconnecter automatiquement
     */
    public function checkRememberToken($request = null)
    {
        // Si l'utilisateur est déjà connecté, ne rien faire
        if (session()->has('player_id')) {
            return false;
        }

        // Utiliser la request passée en paramètre ou celle du contrôleur
        $requestObj = $request ?: $this->request;
        if (!$requestObj) {
            // Fallback vers le service request global
            $requestObj = service('request');
        }

        $rememberToken = $requestObj->getCookie('remember_token');
        if (!$rememberToken) {
            return false;
        }

        $ipAddress = $requestObj->getIPAddress();
        $userAgent = $requestObj->getUserAgent()->getAgentString();

        $tokenData = $this->rememberTokenModel->validateToken($rememberToken, $ipAddress, $userAgent);

        if ($tokenData) {
            // Token valide, reconnecter l'utilisateur
            $player = $this->playerModel->find($tokenData['player_id']);
            
            if ($player) {
                session()->set('player_id', $player['discord_id']);
                session()->set('player_data', $player);
                
                log_message('info', 'User auto-logged in via remember token: ' . $player['discord_id']);
                return true;
            }
        } else {
            // Token invalide, supprimer le cookie
            $this->response->deleteCookie('remember_token');
        }

        return false;
    }
}