<?php

namespace App\Controllers;

use App\Libraries\ConnectionLogger;

class AdminLogs extends BaseController
{
    private ConnectionLogger $logger;

    public function __construct()
    {
        $this->logger = new ConnectionLogger();
    }

    /**
     * Page principale des logs
     */
    public function index()
    {
        // Récupérer les statistiques
        $stats = $this->logger->getStats(30);
        
        // Récupérer les connexions récentes
        $recentConnections = $this->logger->getRecentConnections(100);
        
        // Récupérer les IPs suspectes
        $suspiciousIPs = $this->logger->getSuspiciousIPs(50);

        $data = [
            'title' => 'Logs de Connexion - Administration',
            'stats' => $stats,
            'recent_connections' => $recentConnections,
            'suspicious_ips' => $suspiciousIPs
        ];

        return view('admin/logs', $data);
    }

    /**
     * API pour récupérer les données des logs (AJAX)
     */
    public function getLogsData()
    {
        $request = $this->request;
        
        // Paramètres de pagination et filtrage
        $draw = $request->getPost('draw');
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 50;
        $searchValue = $request->getPost('search')['value'] ?? '';
        
        // Filters
        $dateFrom = $request->getPost('date_from');
        $dateTo = $request->getPost('date_to');
        $ipFilter = $request->getPost('ip_filter');
        $botFilter = $request->getPost('bot_filter');

        try {
            $model = new \App\Models\ConnectionLogModel();
            
            // Construire la requête
            $builder = $model->orderBy('created_at', 'DESC');
            
            // Appliquer les filtres
            if ($searchValue) {
                $builder->groupStart()
                       ->like('ip_address', $searchValue)
                       ->orLike('requested_url', $searchValue)
                       ->orLike('user_agent', $searchValue)
                       ->groupEnd();
            }
            
            if ($dateFrom) {
                $builder->where('created_at >=', $dateFrom);
            }
            
            if ($dateTo) {
                $builder->where('created_at <=', $dateTo);
            }
            
            if ($ipFilter) {
                $builder->like('ip_address', $ipFilter);
            }
            
            if ($botFilter !== null) {
                $builder->where('is_bot', $botFilter == '1');
            }

            // Compter le total
            $totalRecords = $model->countAllResults(false);
            
            // Récupérer les données paginées
            $logs = $builder->limit($length, $start)->findAll();
            
            // Formater les données pour DataTables
            $data = [];
            foreach ($logs as $log) {
                $data[] = [
                    'id' => $log['log_id'],
                    'ip' => $log['ip_address'],
                    'url' => $log['requested_url'],
                    'method' => $log['http_method'],
                    'browser' => $log['browser'] ?? 'N/A',
                    'platform' => $log['platform'] ?? 'N/A',
                    'device' => $log['device_type'],
                    'is_bot' => $log['is_bot'] ? 'Oui' : 'Non',
                    'response_time' => $log['response_time'] ? number_format($log['response_time'], 3) . 's' : 'N/A',
                    'status' => $log['status_code'] ?? 'N/A',
                    'created_at' => date('d/m/Y H:i:s', strtotime($log['created_at'])),
                    'user_agent' => substr($log['user_agent'] ?? '', 0, 100) . '...'
                ];
            }

            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Erreur lors de la récupération des logs: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Nettoie les anciens logs
     */
    public function cleanLogs()
    {
        try {
            $days = $this->request->getPost('days') ?? 90;
            $deletedRows = $this->logger->cleanOldLogs($days);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "{$deletedRows} entrées supprimées",
                'deleted_rows' => $deletedRows
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Erreur lors du nettoyage: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exporte les logs au format CSV
     */
    public function exportLogs()
    {
        try {
            $model = new \App\Models\ConnectionLogModel();
            
            // Paramètres d'export
            $dateFrom = $this->request->getGet('date_from');
            $dateTo = $this->request->getGet('date_to');
            $limit = $this->request->getGet('limit') ?? 1000;
            
            $builder = $model->orderBy('created_at', 'DESC')->limit($limit);
            
            if ($dateFrom) {
                $builder->where('created_at >=', $dateFrom);
            }
            
            if ($dateTo) {
                $builder->where('created_at <=', $dateTo);
            }
            
            $logs = $builder->findAll();
            
            // Générer le CSV
            $filename = 'connection_logs_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $output = fopen('php://output', 'w');
            
            // En-têtes CSV
            $headers = [
                'ID', 'IP', 'URL', 'Méthode', 'Navigateur', 'Plateforme', 
                'Appareil', 'Bot', 'Temps réponse', 'Code statut', 'Date/Heure'
            ];
            fputcsv($output, $headers);
            
            // Données
            foreach ($logs as $log) {
                $row = [
                    $log['log_id'],
                    $log['ip_address'],
                    $log['requested_url'],
                    $log['http_method'],
                    $log['browser'] ?? 'N/A',
                    $log['platform'] ?? 'N/A',
                    $log['device_type'],
                    $log['is_bot'] ? 'Oui' : 'Non',
                    $log['response_time'] ?? 'N/A',
                    $log['status_code'] ?? 'N/A',
                    $log['created_at']
                ];
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            show_error('Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'une IP
     */
    public function ipDetails($ip)
    {
        try {
            $model = new \App\Models\ConnectionLogModel();
            
            // Statistiques pour cette IP
            $stats = $model->select('
                COUNT(*) as total_visits,
                COUNT(DISTINCT DATE(created_at)) as days_active,
                MIN(created_at) as first_visit,
                MAX(created_at) as last_visit,
                COUNT(DISTINCT requested_url) as unique_pages
            ')
            ->where('ip_address', $ip)
            ->get()
            ->getRowArray();
            
            // Visites récentes
            $recentVisits = $model->where('ip_address', $ip)
                                 ->orderBy('created_at', 'DESC')
                                 ->limit(50)
                                 ->findAll();
            
            // Pages les plus visitées par cette IP
            $topPages = $model->select('requested_url, COUNT(*) as visits')
                             ->where('ip_address', $ip)
                             ->groupBy('requested_url')
                             ->orderBy('visits', 'DESC')
                             ->limit(10)
                             ->findAll();

            $data = [
                'title' => "Détails IP: {$ip}",
                'ip' => $ip,
                'stats' => $stats,
                'recent_visits' => $recentVisits,
                'top_pages' => $topPages
            ];

            return view('admin/ip_details', $data);
            
        } catch (\Exception $e) {
            show_error('Erreur lors de la récupération des détails: ' . $e->getMessage());
        }
    }
}