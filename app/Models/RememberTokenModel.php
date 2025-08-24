<?php

namespace App\Models;

use CodeIgniter\Model;

class RememberTokenModel extends Model
{
    protected $table = 'remember_tokens';
    protected $primaryKey = 'token';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'token', 
        'player_id', 
        'ip_address', 
        'user_agent', 
        'expires_at', 
        'created_at',
        'last_used_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Génère un nouveau token de connexion persistant
     */
    public function createRememberToken($playerId, $ipAddress, $userAgent, $expiryDays = 30)
    {
        // Générer un token sécurisé
        $token = bin2hex(random_bytes(32));
        
        // Date d'expiration
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . $expiryDays . ' days'));
        
        $tokenData = [
            'token' => $token,
            'player_id' => $playerId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
            'last_used_at' => date('Y-m-d H:i:s')
        ];

        // Nettoyer les anciens tokens de ce joueur pour éviter l'accumulation
        $this->cleanupOldTokens($playerId);

        // Insérer le nouveau token
        if ($this->insert($tokenData)) {
            return $token;
        }

        return false;
    }

    /**
     * Vérifie si un token est valide
     */
    public function validateToken($token, $ipAddress, $userAgent)
    {
        $tokenData = $this->where('token', $token)
                          ->where('expires_at >', date('Y-m-d H:i:s'))
                          ->first();

        if (!$tokenData) {
            return false;
        }

        // Vérifier l'IP et le User-Agent pour la sécurité
        if ($tokenData['ip_address'] !== $ipAddress) {
            // IP différente - token compromis, le supprimer
            $this->delete($token);
            log_message('warning', 'Remember token revoked due to IP change. Player: ' . $tokenData['player_id']);
            return false;
        }

        // Optionnel: vérifier aussi le User-Agent pour plus de sécurité
        // if ($tokenData['user_agent'] !== $userAgent) {
        //     $this->delete($token);
        //     return false;
        // }

        // Mettre à jour la dernière utilisation
        $this->update($token, ['last_used_at' => date('Y-m-d H:i:s')]);

        return $tokenData;
    }

    /**
     * Supprime un token spécifique
     */
    public function revokeToken($token)
    {
        return $this->delete($token);
    }

    /**
     * Supprime tous les tokens d'un joueur
     */
    public function revokeAllPlayerTokens($playerId)
    {
        return $this->where('player_id', $playerId)->delete();
    }

    /**
     * Nettoie les anciens tokens expirés et limite le nombre de tokens par joueur
     */
    public function cleanupOldTokens($playerId = null)
    {
        // Supprimer tous les tokens expirés
        $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();

        if ($playerId) {
            // Garder seulement les 3 derniers tokens les plus récents pour ce joueur
            $existingTokens = $this->where('player_id', $playerId)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll();

            if (count($existingTokens) >= 3) {
                $tokensToDelete = array_slice($existingTokens, 2); // Garder les 2 plus récents
                foreach ($tokensToDelete as $tokenToDelete) {
                    $this->delete($tokenToDelete['token']);
                }
            }
        }
    }

    /**
     * Nettoie périodiquement tous les tokens expirés
     */
    public function cleanupExpiredTokens()
    {
        $deletedCount = $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
        log_message('info', 'Cleaned up ' . $deletedCount . ' expired remember tokens');
        return $deletedCount;
    }
}