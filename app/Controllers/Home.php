<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct()
    {
        helper('auth');
    }

    public function index(): string
    {
        $data = getAuthData();
        
        // Récupérer le nombre d'équipes pour l'affichage sur l'accueil
        $data['team_count'] = 0;
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT COUNT(*) as team_count FROM team WHERE name != 'Default Team'");
            $result = $query->getRow();
            $data['team_count'] = $result ? $result->team_count : 0;
        } catch (\Exception $e) {
            // Si la table n'existe pas ou la requête échoue, garder 0 par défaut
            $data['team_count'] = 0;
        }
        
        return view('home', $data);
    }

    /**
     * API endpoint pour récupérer le nombre d'équipes en AJAX
     */
    public function getTeamCount()
    {
        $teamCount = 0;
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT COUNT(*) as team_count FROM team WHERE name != 'Default Team'");
            $result = $query->getRow();
            $teamCount = $result ? $result->team_count : 0;
        } catch (\Exception $e) {
            // Si la table n'existe pas ou la requête échoue, garder 0 par défaut
            $teamCount = 0;
        }
        
        return $this->response->setJSON([
            'success' => true,
            'team_count' => $teamCount
        ]);
    }
}
