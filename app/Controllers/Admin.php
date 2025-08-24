<?php

namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\PlayerModel;
use App\Models\PouleModel;

class Admin extends BaseController
{
    protected $teamModel;
    protected $playerModel;
    protected $pouleModel;
    
    private $authorizedDiscordIds = [
        '432475928401149952',
        '602186937482215434',
        '221692373858648075'
    ];

    public function __construct()
    {
        $this->teamModel = new TeamModel();
        $this->playerModel = new PlayerModel();
        $this->pouleModel = new PouleModel();
    }

    public function index()
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setBody('Accès refusé');
        }

        // Récupérer toutes les équipes avec leurs joueurs
        $teams = $this->teamModel->getTeamsWithPlayers();
        
        // Récupérer toutes les poules disponibles
        $poules = $this->pouleModel->findAll();

        return view('admin/dashboard', [
            'teams' => $teams,
            'poules' => $poules,
            'title' => 'Administration - Liste des équipes',
            'isLoggedIn' => session()->has('player_id'),
            'player' => session()->get('player_data')
        ]);
    }

    public function getTeamMembers($teamId)
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        try {
            // Récupérer les informations de l'équipe
            $team = $this->teamModel->find($teamId);
            if (!$team) {
                return $this->response->setJSON(['success' => false, 'message' => 'Équipe introuvable']);
            }

            // Récupérer les membres de l'équipe
            $members = $this->playerModel->where('team_id', $teamId)->findAll();

            return $this->response->setJSON([
                'success' => true,
                'team_name' => $team['name'],
                'members' => $members
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la récupération des membres: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    private function checkAdminAccess()
    {
        // Vérifier si l'utilisateur est connecté
        $playerId = session()->get('player_id');
        if (!$playerId) {
            return false;
        }

        // Vérifier si l'ID Discord est autorisé
        return in_array($playerId, $this->authorizedDiscordIds);
    }

    /**
     * Fonction publique pour vérifier si l'utilisateur actuel a les droits admin
     * Utilisée dans les vues pour afficher conditionnellement les éléments admin
     */
    public static function hasAdminAccess()
    {
        $authorizedDiscordIds = [
            '432475928401149952',
            '602186937482215434',
            '221692373858648075'
        ];

        $playerId = session()->get('player_id');
        if (!$playerId) {
            return false;
        }

        return in_array($playerId, $authorizedDiscordIds);
    }

    public function updateTeamPoule()
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $teamId = $this->request->getPost('team_id');
        $pouleId = $this->request->getPost('poule_id');

        // Si pouleId est vide ou "none", on assigne null
        if (empty($pouleId) || $pouleId === 'none') {
            $pouleId = null;
        }

        try {
            // Vérifier que l'équipe existe
            $team = $this->teamModel->find($teamId);
            if (!$team) {
                return $this->response->setJSON(['success' => false, 'message' => 'Équipe introuvable']);
            }

            // Si une poule est spécifiée, vérifier qu'elle existe
            if ($pouleId !== null) {
                $poule = $this->pouleModel->find($pouleId);
                if (!$poule) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Poule introuvable']);
                }
            }

            // Mettre à jour la poule de l'équipe
            $result = $this->teamModel->update($teamId, ['poule_id' => $pouleId]);

            if ($result) {
                $pouleNameForResponse = $pouleId ? $pouleId : 'Aucune poule';
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Poule mise à jour avec succès',
                    'poule_name' => $pouleNameForResponse
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la mise à jour de la poule: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }
}