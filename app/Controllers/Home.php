<?php

namespace App\Controllers;

use App\Models\MatchModel;
use App\Models\GameModel;

class Home extends BaseController
{
    protected $matchModel;
    protected $gameModel;

    public function __construct()
    {
        helper('auth');
        $this->matchModel = new MatchModel();
        $this->gameModel = new GameModel();
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
        
        // Récupérer les 2 derniers matchs joués et les 2 prochains
        $data['recent_matches'] = $this->getRecentMatches();
        $data['upcoming_matches'] = $this->getUpcomingMatches();
        
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

    /**
     * Récupère les 2 derniers matchs joués (avec au moins un game terminé)
     */
    private function getRecentMatches()
    {
        try {
            $matches = $this->matchModel->select('matchs.*, 
                                              team_a.name as team_a_name, 
                                              team_b.name as team_b_name')
                                   ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                                   ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                                   ->where('matchs.match_date <=', date('Y-m-d H:i:s'))
                                   ->orderBy('matchs.match_date', 'DESC')
                                   ->findAll();

            $recentMatches = [];
            foreach ($matches as $match) {
                $games = $this->gameModel->getGamesByMatch($match['match_id']);
                
                // Vérifier si au moins un game a des scores (match joué)
                $hasCompletedGames = false;
                foreach ($games as $game) {
                    if ($game['a_score'] !== null && $game['b_score'] !== null) {
                        $hasCompletedGames = true;
                        break;
                    }
                }
                
                if ($hasCompletedGames) {
                    $match['games'] = $games;
                    $match['winner'] = $this->determineMatchWinner($games, $match['is_tournament']);
                    $recentMatches[] = $match;
                    
                    if (count($recentMatches) >= 2) {
                        break;
                    }
                }
            }
            
            return $recentMatches;
        } catch (\Exception $e) {
            log_message('error', 'Home - Erreur lors de la récupération des matchs récents: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les 2 prochains matchs à venir
     */
    private function getUpcomingMatches()
    {
        try {
            $matches = $this->matchModel->select('matchs.*, 
                                              team_a.name as team_a_name, 
                                              team_b.name as team_b_name')
                                   ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                                   ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                                   ->where('matchs.match_date >', date('Y-m-d H:i:s'))
                                   ->orderBy('matchs.match_date', 'ASC')
                                   ->limit(2)
                                   ->findAll();

            foreach ($matches as &$match) {
                $match['games'] = $this->gameModel->getGamesByMatch($match['match_id']);
            }
            
            return $matches;
        } catch (\Exception $e) {
            log_message('error', 'Home - Erreur lors de la récupération des matchs à venir: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Détermine le gagnant d'un match basé sur ses games
     */
    private function determineMatchWinner($games, $isTournament)
    {
        $teamAWins = 0;
        $teamBWins = 0;
        
        foreach ($games as $game) {
            if ($game['a_score'] !== null && $game['b_score'] !== null) {
                if ($game['a_score'] > $game['b_score']) {
                    $teamAWins++;
                } elseif ($game['b_score'] > $game['a_score']) {
                    $teamBWins++;
                }
            }
        }
        
        if ($isTournament) {
            // BO3 : il faut 2 victoires
            if ($teamAWins >= 2) return 'team_a';
            if ($teamBWins >= 2) return 'team_b';
        } else {
            // Poule : 1 seul game
            if ($teamAWins > $teamBWins) return 'team_a';
            if ($teamBWins > $teamAWins) return 'team_b';
        }
        
        return null; // Match nul ou non déterminé
    }
}
