<?php

namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\PlayerModel;
use App\Models\PouleModel;
use App\Models\MatchModel;
use App\Models\GameModel;
use App\Models\ValorantMapModel;

class Admin extends BaseController
{
    protected $teamModel;
    protected $playerModel;
    protected $pouleModel;
    protected $matchModel;
    protected $gameModel;
    protected $valorantMapModel;
    
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
        $this->matchModel = new MatchModel();
        $this->gameModel = new GameModel();
        $this->valorantMapModel = new ValorantMapModel();
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

        // Récupérer tous les matchs avec leurs games
        $matches = $this->matchModel->getAllMatchesWithGames();

        // Récupérer les maps Valorant disponibles
        $valorantMaps = $this->valorantMapModel->getAllMapsOrdered();

        return view('admin/dashboard', [
            'teams' => $teams,
            'poules' => $poules,
            'matches' => $matches,
            'valorantMaps' => $valorantMaps,
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

    // ===========================
    // GESTION DES MATCHS
    // ===========================

    public function createMatch()
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $matchDate = $this->request->getPost('match_date');
        $isTournamentValue = $this->request->getPost('is_tournament');
        $isTournament = ($isTournamentValue === '1' || $isTournamentValue === 1 || $isTournamentValue === true);
        $teamIdA = $this->request->getPost('team_id_a');
        $teamIdB = $this->request->getPost('team_id_b');

        // Debug logging
        log_message('info', 'Admin - CreateMatch: is_tournament raw value: ' . var_export($isTournamentValue, true));
        log_message('info', 'Admin - CreateMatch: is_tournament processed: ' . var_export($isTournament, true));

        try {
            // Valider les équipes
            if (!$teamIdA || !$teamIdB) {
                return $this->response->setJSON(['success' => false, 'message' => 'Veuillez sélectionner les deux équipes']);
            }

            if ($teamIdA === $teamIdB) {
                return $this->response->setJSON(['success' => false, 'message' => 'Une équipe ne peut pas jouer contre elle-même']);
            }

            // Vérifier que les équipes existent
            $teamA = $this->teamModel->find($teamIdA);
            $teamB = $this->teamModel->find($teamIdB);
            if (!$teamA || !$teamB) {
                return $this->response->setJSON(['success' => false, 'message' => 'Une ou plusieurs équipes sont introuvables']);
            }

            // Générer un ID unique pour le match
            $matchId = $this->matchModel->generateMatchId();

            // Formater la date pour la base de données
            $formattedDate = $matchDate ? date('Y-m-d H:i:s', strtotime($matchDate)) : date('Y-m-d H:i:s');

            // Créer le match
            $matchData = [
                'match_id' => $matchId,
                'match_date' => $formattedDate,
                'is_tournament' => $isTournament,
                'team_id_a' => $teamIdA,
                'team_id_b' => $teamIdB
            ];

            log_message('info', 'Admin - CreateMatch: Inserting match data: ' . json_encode($matchData));
            $result = $this->matchModel->insert($matchData);
            log_message('info', 'Admin - CreateMatch: Insert result: ' . var_export($result, true));

            if ($result) {
                log_message('info', 'Admin - CreateMatch: Success - Match created with ID: ' . $matchId);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Match créé avec succès',
                    'match_id' => $matchId
                ]);
            } else {
                // Récupérer les erreurs de validation
                $validationErrors = $this->matchModel->errors();
                log_message('error', 'Admin - CreateMatch: Failed to insert match data. Validation errors: ' . json_encode($validationErrors));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création du match: ' . (empty($validationErrors) ? 'Erreur inconnue' : implode(', ', $validationErrors))
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la création du match: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function addGameToMatch()
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $matchId = $this->request->getPost('match_id');
        $scoreA = $this->request->getPost('a_score');
        $scoreB = $this->request->getPost('b_score');
        $map = $this->request->getPost('map');

        try {
            // Vérifier que le match existe et récupérer ses informations
            $match = $this->matchModel->find($matchId);
            if (!$match) {
                return $this->response->setJSON(['success' => false, 'message' => 'Match introuvable']);
            }

            // Vérifier que le match a bien des équipes assignées
            if (!$match['team_id_a'] || !$match['team_id_b']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le match doit avoir deux équipes assignées']);
            }

            // Générer un ID unique pour le game
            $gameId = $this->gameModel->generateGameId();

            // Créer le game (les équipes sont héritées du match)
            $gameData = [
                'game_id' => $gameId,
                'match_id' => $matchId,
                'a_score' => ($scoreA !== '' && is_numeric($scoreA)) ? (int)$scoreA : null,
                'b_score' => ($scoreB !== '' && is_numeric($scoreB)) ? (int)$scoreB : null,
                'map' => !empty($map) ? trim($map) : null
            ];

            $result = $this->gameModel->insert($gameData);

            if ($result) {
                // Récupérer les informations complètes du game créé
                $gameWithDetails = $this->gameModel->getGameWithTeams($gameId);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Game ajouté au match avec succès',
                    'game' => $gameWithDetails
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la création du game'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la création du game: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function updateGameScore()
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $gameId = $this->request->getPost('game_id');
        $scoreA = $this->request->getPost('a_score');
        $scoreB = $this->request->getPost('b_score');
        $map = $this->request->getPost('map');

        try {
            // Vérifier que le game existe
            $game = $this->gameModel->find($gameId);
            if (!$game) {
                return $this->response->setJSON(['success' => false, 'message' => 'Game introuvable']);
            }

            // Mettre à jour les scores et la map
            $updateData = [
                'a_score' => ($scoreA !== '' && is_numeric($scoreA)) ? (int)$scoreA : null,
                'b_score' => ($scoreB !== '' && is_numeric($scoreB)) ? (int)$scoreB : null,
                'map' => !empty($map) ? trim($map) : null
            ];

            $result = $this->gameModel->update($gameId, $updateData);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Score mis à jour avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du score'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la mise à jour du score: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function deleteMatch($matchId)
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        try {
            // Vérifier que le match existe
            $match = $this->matchModel->find($matchId);
            if (!$match) {
                return $this->response->setJSON(['success' => false, 'message' => 'Match introuvable']);
            }

            // Supprimer d'abord tous les games associés
            $games = $this->gameModel->where('match_id', $matchId)->findAll();
            foreach ($games as $game) {
                $this->gameModel->delete($game['game_id']);
            }

            // Supprimer le match
            $result = $this->matchModel->delete($matchId);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Match supprimé avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du match'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la suppression du match: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function deleteGame($gameId)
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        try {
            // Vérifier que le game existe
            $game = $this->gameModel->find($gameId);
            if (!$game) {
                return $this->response->setJSON(['success' => false, 'message' => 'Game introuvable']);
            }

            // Supprimer le game
            $result = $this->gameModel->delete($gameId);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Game supprimé avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du game'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la suppression du game: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    public function updateMatchDate()
    {
        // Vérifier l'accès admin
        if (!$this->checkAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        $matchId = $this->request->getPost('match_id');
        $newDate = $this->request->getPost('match_date');

        try {
            // Vérifier que le match existe
            $match = $this->matchModel->find($matchId);
            if (!$match) {
                return $this->response->setJSON(['success' => false, 'message' => 'Match introuvable']);
            }

            // Validation de la date
            if (!$newDate) {
                return $this->response->setJSON(['success' => false, 'message' => 'Date requise']);
            }

            // Formater la date pour la base de données
            $formattedDate = date('Y-m-d H:i:s', strtotime($newDate));

            // Mettre à jour la date du match
            $result = $this->matchModel->update($matchId, ['match_date' => $formattedDate]);

            if ($result) {
                log_message('info', 'Admin - Match date updated: ' . $matchId . ' to ' . $formattedDate);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Date du match mise à jour avec succès'
                ]);
            } else {
                // Récupérer les erreurs de validation
                $validationErrors = $this->matchModel->errors();
                log_message('error', 'Admin - Failed to update match date. Validation errors: ' . json_encode($validationErrors));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour: ' . (empty($validationErrors) ? 'Erreur inconnue' : implode(', ', $validationErrors))
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin - Erreur lors de la mise à jour de la date du match: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur serveur']);
        }
    }
}