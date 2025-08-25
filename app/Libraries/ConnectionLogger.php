<?php

namespace App\Libraries;

use App\Models\ConnectionLogModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ConnectionLogger
{
    private ConnectionLogModel $logModel;
    private RequestInterface $request;
    private float $startTime;

    public function __construct()
    {
        $this->logModel = new ConnectionLogModel();
        $this->request = service('request');
        $this->startTime = microtime(true);
    }

    /**
     * Enregistre une connexion automatiquement
     */
    public function logCurrentConnection(ResponseInterface $response = null): bool
    {
        try {
            $data = [
                'ip_address' => $this->getClientIP(),
                'user_agent' => $this->request->getUserAgent() ? $this->request->getUserAgent()->__toString() : null,
                'requested_url' => $this->request->getUri()->getPath(),
                'http_method' => $this->request->getMethod(),
                'referer' => $this->request->getHeaderLine('Referer') ?: null,
                'session_id' => session_id() ?: null,
                'user_id' => $this->getCurrentUserId(),
                'response_time' => $this->calculateResponseTime(),
                'status_code' => $response ? $response->getStatusCode() : null
            ];

            // Ajouter la géolocalisation si disponible
            $geoData = $this->getGeoLocation($data['ip_address']);
            if ($geoData) {
                $data = array_merge($data, $geoData);
            }

            return $this->logModel->logConnection($data);

        } catch (\Exception $e) {
            // Logger l'erreur sans interrompre l'exécution
            log_message('error', 'Erreur lors de l\'enregistrement du log de connexion: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enregistre une connexion avec des données personnalisées
     */
    public function logConnection(array $customData = []): bool
    {
        try {
            $defaultData = [
                'ip_address' => $this->getClientIP(),
                'user_agent' => $this->request->getUserAgent() ? $this->request->getUserAgent()->__toString() : null,
                'requested_url' => $this->request->getUri()->getPath(),
                'http_method' => $this->request->getMethod(),
                'referer' => $this->request->getHeaderLine('Referer') ?: null,
                'session_id' => session_id() ?: null,
                'user_id' => $this->getCurrentUserId(),
                'response_time' => $this->calculateResponseTime()
            ];

            $data = array_merge($defaultData, $customData);

            return $this->logModel->logConnection($data);

        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de l\'enregistrement du log personnalisé: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère l'IP réelle du client (même derrière un proxy)
     */
    private function getClientIP(): string
    {
        // Headers à vérifier pour l'IP réelle
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        ];

        foreach ($headers as $header) {
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);

                // Vérifier que c'est une IP valide et publique
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Fallback vers REMOTE_ADDR
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Récupère l'ID de l'utilisateur connecté (à adapter selon votre système)
     */
    private function getCurrentUserId(): ?string
    {
        $session = session();
        
        // Vérifier différentes clés de session possibles
        $possibleKeys = ['user_id', 'id', 'discord_id', 'player_id'];
        
        foreach ($possibleKeys as $key) {
            if ($session->has($key)) {
                return (string) $session->get($key);
            }
        }

        return null;
    }

    /**
     * Calcule le temps de réponse
     */
    private function calculateResponseTime(): float
    {
        return round(microtime(true) - $this->startTime, 4);
    }

    /**
     * Géolocalisation basique (peut être étendue avec une API externe)
     */
    private function getGeoLocation(string $ip): ?array
    {
        // Pour l'instant, retourne null
        // Vous pouvez intégrer une API comme GeoIP ou MaxMind ici
        
        // Exemple basique pour les IP locales
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return [
                'country' => 'Local/Private',
                'city' => 'Local Network'
            ];
        }

        return null;
    }

    /**
     * Vérifie si une IP doit être ignorée (IP locale, bots connus, etc.)
     */
    public function shouldIgnoreIP(string $ip): bool
    {
        // IPs locales à ignorer
        $ignoredIPs = [
            '127.0.0.1',
            '::1',
            'localhost'
        ];

        if (in_array($ip, $ignoredIPs)) {
            return true;
        }

        // Ignorer les réseaux privés si configuré
        if (env('LOG_IGNORE_PRIVATE_IPS', false)) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Récupère les statistiques
     */
    public function getStats(int $days = 30): array
    {
        return $this->logModel->getConnectionStats($days);
    }

    /**
     * Récupère les connexions récentes
     */
    public function getRecentConnections(int $limit = 50): array
    {
        return $this->logModel->getRecentConnections($limit);
    }

    /**
     * Récupère les IPs suspectes
     */
    public function getSuspiciousIPs(int $maxRequestsPerHour = 100): array
    {
        return $this->logModel->getSuspiciousIPs($maxRequestsPerHour);
    }

    /**
     * Nettoie les anciens logs
     */
    public function cleanOldLogs(int $daysToKeep = 90): int
    {
        return $this->logModel->cleanOldLogs($daysToKeep);
    }

    /**
     * Active/désactive le logging selon la configuration
     */
    public function isLoggingEnabled(): bool
    {
        return env('CONNECTION_LOGGING_ENABLED', true);
    }

    /**
     * Filtre pour ignorer certaines URLs
     */
    public function shouldIgnoreUrl(string $url): bool
    {
        $ignoredPatterns = [
            '/favicon.ico',
            '/robots.txt',
            '/.well-known/',
            '/assets/',
            '/css/',
            '/js/',
            '/images/',
            '/fonts/',
        ];

        foreach ($ignoredPatterns as $pattern) {
            if (strpos($url, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}