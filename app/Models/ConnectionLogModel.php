<?php

namespace App\Models;

use CodeIgniter\Model;

class ConnectionLogModel extends Model
{
    protected $table = 'connection_logs';
    protected $primaryKey = 'log_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ip_address',
        'user_agent',
        'requested_url',
        'http_method',
        'referer',
        'session_id',
        'user_id',
        'country',
        'city',
        'browser',
        'platform',
        'device_type',
        'is_bot',
        'response_time',
        'status_code',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'log_id' => 'integer',
        'user_id' => '?integer',
        'is_bot' => 'boolean',
        'response_time' => '?float',
        'status_code' => '?integer',
        'created_at' => 'datetime'
    ];

    protected array $validationRules = [
        'ip_address' => 'required|valid_ip',
        'requested_url' => 'required|string|max_length[500]',
        'http_method' => 'required|in_list[GET,POST,PUT,DELETE,PATCH,OPTIONS,HEAD]'
    ];

    protected array $validationMessages = [
        'ip_address' => [
            'required' => 'L\'adresse IP est requise',
            'valid_ip' => 'L\'adresse IP doit être valide'
        ],
        'requested_url' => [
            'required' => 'L\'URL demandée est requise',
            'max_length' => 'L\'URL ne peut pas dépasser 500 caractères'
        ]
    ];

    protected array $skipValidation = false;
    protected array $cleanValidationRules = true;

    protected bool $allowCallbacks = true;
    protected bool $beforeInsert = ['setCreatedAt'];
    protected bool $afterInsert = [];
    protected bool $beforeUpdate = [];
    protected bool $afterUpdate = [];
    protected bool $beforeFind = [];
    protected bool $afterFind = [];
    protected bool $beforeDelete = [];
    protected bool $afterDelete = [];

    /**
     * Définit automatiquement created_at lors de l'insertion
     */
    protected function setCreatedAt(array $data): array
    {
        if (!isset($data['data']['created_at'])) {
            $data['data']['created_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Enregistre une connexion
     */
    public function logConnection(array $data): bool
    {
        // Parser l'User-Agent pour extraire des informations
        if (isset($data['user_agent'])) {
            $parsed = $this->parseUserAgent($data['user_agent']);
            $data = array_merge($data, $parsed);
        }

        // Détecter si c'est un bot
        $data['is_bot'] = $this->detectBot($data['user_agent'] ?? '');

        return $this->insert($data) !== false;
    }

    /**
     * Parse l'User-Agent pour extraire navigateur, plateforme, etc.
     */
    private function parseUserAgent(string $userAgent): array
    {
        $result = [
            'browser' => 'Unknown',
            'platform' => 'Unknown',
            'device_type' => 'Desktop'
        ];

        // Détecter les navigateurs
        if (preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Firefox ' . $matches[1];
        } elseif (preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Chrome ' . $matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Safari ' . $matches[1];
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent, $matches)) {
            $result['browser'] = 'Edge ' . $matches[1];
        }

        // Détecter les plateformes
        if (strpos($userAgent, 'Windows NT') !== false) {
            $result['platform'] = 'Windows';
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            $result['platform'] = 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $result['platform'] = 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $result['platform'] = 'Android';
            $result['device_type'] = 'Mobile';
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            $result['platform'] = 'iOS';
            $result['device_type'] = strpos($userAgent, 'iPad') !== false ? 'Tablet' : 'Mobile';
        }

        return $result;
    }

    /**
     * Détecte si l'User-Agent correspond à un bot
     */
    private function detectBot(string $userAgent): bool
    {
        $botPatterns = [
            'Googlebot',
            'Bingbot',
            'Slurp',
            'DuckDuckBot',
            'Baiduspider',
            'YandexBot',
            'facebookexternalhit',
            'Twitterbot',
            'rogerbot',
            'linkedinbot',
            'embedly',
            'quora link preview',
            'showyoubot',
            'outbrain',
            'pinterest',
            'developers.google.com/+/web/snippet'
        ];

        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Récupère les statistiques de connexion
     */
    public function getConnectionStats(int $days = 30): array
    {
        $startDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return [
            'total_connections' => $this->where('created_at >=', $startDate)->countAllResults(false),
            'unique_ips' => $this->select('COUNT(DISTINCT ip_address) as count')
                                ->where('created_at >=', $startDate)
                                ->get()
                                ->getRow()
                                ->count,
            'bot_connections' => $this->where('created_at >=', $startDate)
                                      ->where('is_bot', true)
                                      ->countAllResults(false),
            'top_pages' => $this->select('requested_url, COUNT(*) as visits')
                                ->where('created_at >=', $startDate)
                                ->groupBy('requested_url')
                                ->orderBy('visits', 'DESC')
                                ->limit(10)
                                ->findAll(),
            'top_ips' => $this->select('ip_address, COUNT(*) as visits')
                              ->where('created_at >=', $startDate)
                              ->groupBy('ip_address')
                              ->orderBy('visits', 'DESC')
                              ->limit(10)
                              ->findAll(),
            'browsers' => $this->select('browser, COUNT(*) as count')
                               ->where('created_at >=', $startDate)
                               ->groupBy('browser')
                               ->orderBy('count', 'DESC')
                               ->findAll(),
            'platforms' => $this->select('platform, COUNT(*) as count')
                                ->where('created_at >=', $startDate)
                                ->groupBy('platform')
                                ->orderBy('count', 'DESC')
                                ->findAll()
        ];
    }

    /**
     * Nettoie les anciens logs (plus de X jours)
     */
    public function cleanOldLogs(int $daysToKeep = 90): int
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysToKeep} days"));
        
        return $this->where('created_at <', $cutoffDate)->delete();
    }

    /**
     * Récupère les connexions récentes
     */
    public function getRecentConnections(int $limit = 50): array
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Recherche des connexions suspectes (trop de requêtes d'une même IP)
     */
    public function getSuspiciousIPs(int $maxRequestsPerHour = 100): array
    {
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
        
        return $this->select('ip_address, COUNT(*) as request_count')
                    ->where('created_at >=', $oneHourAgo)
                    ->groupBy('ip_address')
                    ->having('request_count >', $maxRequestsPerHour)
                    ->orderBy('request_count', 'DESC')
                    ->findAll();
    }
}