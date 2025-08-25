<?php

namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\PlayerModel;
use App\Models\MatchModel;
use App\Models\GameModel;

class Poules extends BaseController
{
    protected $teamModel;
    protected $playerModel;
    protected $matchModel;
    protected $gameModel;

    public function __construct()
    {
        helper('auth');
        $this->teamModel = new TeamModel();
        $this->playerModel = new PlayerModel();
        $this->matchModel = new MatchModel();
        $this->gameModel = new GameModel();
    }

    public function index()
    {
        // NE PAS synchroniser automatiquement - laisser les admins gérer les poules
        
        // Récupérer toutes les équipes avec le nombre de membres et leurs joueurs
        $realTeams = $this->teamModel->getTeamsWithMembers();
        
        // Filtrer les équipes qui ont au moins 1 membre OU sont des équipes de test
        $validTeams = array_filter($realTeams, function($team) {
            return $team['member_count'] > 0 || strpos($team['team_id'], 'TEST_TEAM_') === 0;
        });

        // Convertir au format attendu avec gestion des rangs Valorant
        $formattedTeams = [];
        foreach ($validTeams as $team) {
            // Enrichir les données des joueurs avec les rangs Valorant
            $enrichedPlayers = $this->enrichPlayersWithRanks($team['players']);
            
            $formattedTeams[] = [
                'id' => $team['team_id'],
                'nom' => $team['name'],
                'membres' => $team['member_count'],
                'players' => $enrichedPlayers,
                'poule_id' => $team['poule_id'] ?? null,
                'resultats' => $this->getTeamResults($team['team_id']), // Récupérer les vrais résultats
                'matches' => $this->getTeamMatches($team['team_id']) // Récupérer les détails des matchs
            ];
        }

        // Si aucune équipe réelle, utiliser des équipes de test pour la démo
        if (empty($formattedTeams)) {
            $formattedTeams = $this->generateTestTeams();
        }

        // Séparer les équipes par poule et récupérer celles sans poule
        list($pouleA, $pouleB, $teamsWithoutPoule) = $this->separateTeamsByPoule($formattedTeams);

        // Trier les équipes par performance
        $pouleA = $this->sortTeamsByPerformance($pouleA);
        $pouleB = $this->sortTeamsByPerformance($pouleB);
        $teamsWithoutPoule = $this->sortTeamsByName($teamsWithoutPoule);

        // Vérifier si tous les résultats sont complétés
        $allResultsComplete = $this->areAllResultsComplete(array_merge($pouleA, $pouleB));

        // Récupérer les matchs de tournoi pour le bracket
        $tournamentMatches = $this->getTournamentMatches();

        $data = array_merge(getAuthData(), [
            'title' => 'Poules - Flawless Cup',
            'pouleA' => $pouleA,
            'pouleB' => $pouleB,
            'teamsWithoutPoule' => $teamsWithoutPoule,
            'totalTeams' => count($formattedTeams),
            'allResultsComplete' => $allResultsComplete,
            'tournamentMatches' => $tournamentMatches
        ]);

        return view('poules', $data);
    }

    /**
     * Calcule les points d'une équipe basé sur ses résultats
     * V = 3 points, D = 0 points
     */
    private function calculatePoints($resultats)
    {
        $points = 0;
        $victoires = 0;
        $defaites = 0;
        
        foreach ($resultats as $resultat) {
            if ($resultat === 'V') {
                $points += 3;
                $victoires++;
            } elseif ($resultat === 'D') {
                $defaites++;
            }
        }
        
        return [
            'points' => $points,
            'victoires' => $victoires,
            'defaites' => $defaites,
            'matchs_joues' => $victoires + $defaites
        ];
    }

    /**
     * Trie les équipes par performance (points, puis victoires, puis nom)
     */
    private function sortTeamsByPerformance($teams)
    {
        // Ajouter les statistiques calculées à chaque équipe
        foreach ($teams as &$team) {
            $stats = $this->calculatePoints($team['resultats']);
            $team['points'] = $stats['points'];
            $team['victoires'] = $stats['victoires'];
            $team['defaites'] = $stats['defaites'];
            $team['matchs_joues'] = $stats['matchs_joues'];
        }

        // Trier par points (desc), puis victoires (desc), puis nom (asc)
        usort($teams, function($a, $b) {
            if ($a['points'] !== $b['points']) {
                return $b['points'] - $a['points']; // Plus de points = meilleur
            }
            if ($a['victoires'] !== $b['victoires']) {
                return $b['victoires'] - $a['victoires']; // Plus de victoires = meilleur
            }
            return strcmp($a['nom'], $b['nom']); // Ordre alphabétique si égalité
        });

        return $teams;
    }

    /**
     * Vérifie si tous les résultats sont complétés (aucun tiret '-' restant)
     */
    private function areAllResultsComplete($teams)
    {
        foreach ($teams as $team) {
            foreach ($team['resultats'] as $resultat) {
                if ($resultat === '-') {
                    return false; // Encore des matchs à jouer
                }
            }
        }
        return true; // Tous les matchs sont joués
    }

    /**
     * Méthode pour mettre à jour les résultats d'une équipe (utilisée par l'admin)
     */
    public function updateTeamResults($teamId, $resultats)
    {
        // Cette méthode sera appelée quand vous me demanderez de mettre à jour
        // Pour l'instant, elle simule la mise à jour en mémoire
        // Dans une vraie app, ça sauvegarderait en base de données
        
        $updatedData = [
            'team_id' => $teamId,
            'resultats' => $resultats,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $updatedData;
    }

    /**
     * Répartit automatiquement les équipes en 2 poules équilibrées
     */
    private function distributeTeamsInPoules($teams)
    {
        $totalTeams = count($teams);
        
        // Si moins de 2 équipes, tout va en poule A
        if ($totalTeams < 2) {
            return [$teams, []];
        }
        
        // Si exactement 2 équipes, 1 par poule
        if ($totalTeams == 2) {
            return [[$teams[0]], [$teams[1]]];
        }
        
        // Mélanger les équipes pour une répartition aléatoire équitable
        $shuffledTeams = $teams;
        shuffle($shuffledTeams);
        
        $pouleA = [];
        $pouleB = [];
        
        // Répartition en alternant : A, B, A, B, etc.
        for ($i = 0; $i < $totalTeams; $i++) {
            if ($i % 2 == 0) {
                $pouleA[] = $shuffledTeams[$i];
            } else {
                $pouleB[] = $shuffledTeams[$i];
            }
        }
        
        return [$pouleA, $pouleB];
    }

    /**
     * Génère des équipes de test si aucune équipe réelle n'existe
     */
    private function generateTestTeams($count = 8)
    {
        $testNames = [
            'Team Alpha', 'Team Beta', 'Team Gamma', 'Team Delta',
            'Team Epsilon', 'Team Zeta', 'Team Eta', 'Team Theta'
        ];
        
        $teams = [];
        for ($i = 0; $i < min($count, count($testNames)); $i++) {
            $memberCount = rand(3, 5);
            $players = [];
            
            // Générer des joueurs fictifs avec tier_id et rr aléatoires
            for ($j = 0; $j < $memberCount; $j++) {
                $randomTierId = rand(1, 27); // Tiers de 1 (Iron 1) à 27 (Radiant)
                $randomRR = rand(0, 99); // RR de 0 à 99
                
                $players[] = [
                    'pseudo' => 'Player' . ($j + 1) . '_' . $testNames[$i],
                    'discord_username' => 'player' . ($j + 1) . '_team' . ($i + 1) . '#' . rand(1000, 9999),
                    'tier_id' => $randomTierId,
                    'rr' => $randomRR
                ];
            }
            
            $teams[] = [
                'id' => 'test_team_' . ($i + 1),
                'nom' => $testNames[$i],
                'membres' => $memberCount,
                'players' => $players,
                'poule_id' => ($i < 4) ? 'A' : 'B', // Alterner entre A et B
                'resultats' => ['-', '-', '-']
            ];
        }
        
        return $teams;
    }

    /**
     * Synchronise automatiquement les équipes avec les poules
     */
    private function autoAssignTeamsToPoules()
    {
        // Créer les poules A et B si elles n'existent pas
        $pouleModel = new \App\Models\PouleModel();
        
        $pouleA = $pouleModel->find('A');
        if (!$pouleA) {
            $pouleModel->insert(['poule_id' => 'A']);
        }
        
        $pouleB = $pouleModel->find('B');
        if (!$pouleB) {
            $pouleModel->insert(['poule_id' => 'B']);
        }

        // Récupérer les équipes sans poule assignée
        $teamsWithoutPoule = $this->teamModel->where('poule_id IS NULL')
                                          ->orWhere('poule_id', '')
                                          ->findAll();

        if (!empty($teamsWithoutPoule)) {
            // Compter les équipes dans chaque poule
            $countA = $this->teamModel->where('poule_id', 'A')->countAllResults();
            $countB = $this->teamModel->where('poule_id', 'B')->countAllResults();

            foreach ($teamsWithoutPoule as $team) {
                // Assigner à la poule avec le moins d'équipes
                $assignToPoule = ($countA <= $countB) ? 'A' : 'B';
                
                $this->teamModel->update($team['team_id'], ['poule_id' => $assignToPoule]);
                
                if ($assignToPoule === 'A') {
                    $countA++;
                } else {
                    $countB++;
                }
            }
        }
    }

    /**
     * Sépare les équipes par poule et récupère celles sans poule
     */
    private function separateTeamsByPoule($teams)
    {
        $pouleA = [];
        $pouleB = [];
        $teamsWithoutPoule = [];

        foreach ($teams as $team) {
            $pouleId = $team['poule_id'] ?? null;
            
            if ($pouleId === 'A') {
                $pouleA[] = $team;
            } elseif ($pouleId === 'B') {
                $pouleB[] = $team;
            } else {
                // Équipes sans poule assignée
                $teamsWithoutPoule[] = $team;
            }
        }

        return [$pouleA, $pouleB, $teamsWithoutPoule];
    }

    /**
     * Trie les équipes par nom (pour les équipes sans poule)
     */
    private function sortTeamsByName($teams)
    {
        usort($teams, function($a, $b) {
            return strcmp($a['nom'], $b['nom']);
        });
        return $teams;
    }

    /**
     * Enrichit les données des joueurs avec les rangs Valorant basés sur tier_id et rr
     */
    private function enrichPlayersWithRanks($players)
    {
        $enrichedPlayers = [];
        
        foreach ($players as $player) {
            // Utiliser tier_id et rr directement au lieu de convertir le MMR
            $player['tier_id'] = $player['tier_id'] ?? 0;
            $player['rr'] = $player['rr'] ?? 0;
            $player['discord_username'] = $player['tag'] ?? 'Non renseigné';
            $enrichedPlayers[] = $player;
        }
        
        return $enrichedPlayers;
    }

    /**
     * Convertit le MMR en rang Valorant
     */
    private function convertMMRToRank($mmr)
    {
        if ($mmr >= 2100) return 'Radiant';
        if ($mmr >= 1900) return 'Immortel';
        if ($mmr >= 1600) return 'Ascendant';
        if ($mmr >= 1300) return 'Diamant';
        if ($mmr >= 1000) return 'Platine';
        if ($mmr >= 700) return 'Or';
        if ($mmr >= 400) return 'Argent';
        if ($mmr >= 100) return 'Bronze';
        if ($mmr > 0) return 'Fer';
        return 'Non classé';
    }

    /**
     * Récupère les résultats d'une équipe depuis la base de données
     */
    private function getTeamResults($teamId)
    {
        try {
            // Récupérer tous les matchs où cette équipe participe
            $matches = $this->matchModel->select('matchs.*, 
                                                team_a.name as team_a_name, 
                                                team_b.name as team_b_name')
                                       ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                                       ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                                       ->groupStart()
                                           ->where('matchs.team_id_a', $teamId)
                                           ->orWhere('matchs.team_id_b', $teamId)
                                       ->groupEnd()
                                       ->where('matchs.is_tournament', false) // Seulement les matchs de poule
                                       ->findAll();
            
            $results = [];
            foreach ($matches as $match) {
                // Pour chaque match, calculer le résultat basé sur tous ses games
                $games = $this->gameModel->getGamesByMatch($match['match_id']);
                
                if (empty($games)) {
                    $results[] = '-'; // Aucun game joué
                    continue;
                }
                
                $teamAWins = 0;
                $teamBWins = 0;
                $allGamesHaveScores = true;
                
                foreach ($games as $game) {
                    if ($game['a_score'] === null || $game['b_score'] === null) {
                        $allGamesHaveScores = false;
                        break;
                    }
                    
                    if ($game['a_score'] > $game['b_score']) {
                        $teamAWins++;
                    } elseif ($game['b_score'] > $game['a_score']) {
                        $teamBWins++;
                    }
                }
                
                if (!$allGamesHaveScores) {
                    $results[] = '-'; // Certains games n'ont pas de score
                    continue;
                }
                
                // Déterminer le gagnant du match
                $matchWinner = null;
                if ($match['is_tournament']) {
                    // BO3 : il faut 2 victoires
                    if ($teamAWins >= 2) {
                        $matchWinner = $match['team_id_a'];
                    } elseif ($teamBWins >= 2) {
                        $matchWinner = $match['team_id_b'];
                    }
                } else {
                    // Poule : 1 seul game
                    if ($teamAWins > $teamBWins) {
                        $matchWinner = $match['team_id_a'];
                    } elseif ($teamBWins > $teamAWins) {
                        $matchWinner = $match['team_id_b'];
                    }
                }
                
                // Ajouter le résultat pour cette équipe
                if ($matchWinner === $teamId) {
                    $results[] = 'V';
                } elseif ($matchWinner !== null) {
                    $results[] = 'D';
                } else {
                    $results[] = '-'; // Match nul ou non déterminé
                }
            }
            
            // Compléter avec des tirets si moins de 3 matchs
            while (count($results) < 3) {
                $results[] = '-';
            }
            
            return array_slice($results, 0, 3); // Max 3 résultats
            
        } catch (\Exception $e) {
            log_message('error', 'Poules - Erreur lors de la récupération des résultats: ' . $e->getMessage());
            return ['-', '-', '-'];
        }
    }

    /**
     * Récupère les détails des matchs d'une équipe avec leurs résultats
     */
    private function getTeamMatches($teamId)
    {
        try {
            $matches = $this->matchModel->select('matchs.*, 
                                                team_a.name as team_a_name, 
                                                team_b.name as team_b_name')
                                       ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                                       ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                                       ->groupStart()
                                           ->where('matchs.team_id_a', $teamId)
                                           ->orWhere('matchs.team_id_b', $teamId)
                                       ->groupEnd()
                                       ->orderBy('matchs.match_date', 'ASC')
                                       ->findAll();
            
            $matchDetails = [];
            foreach ($matches as $match) {
                $games = $this->gameModel->getGamesByMatch($match['match_id']);
                
                $match['games'] = $games;
                $match['team_perspective'] = $teamId; // Pour savoir quelle équipe regarde
                $matchDetails[] = $match;
            }
            
            return $matchDetails;
            
        } catch (\Exception $e) {
            log_message('error', 'Poules - Erreur lors de la récupération des matchs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les matchs de tournoi (demi-finales et finale) avec leurs détails
     */
    private function getTournamentMatches()
    {
        try {
            $matches = $this->matchModel->select('matchs.*, 
                                              team_a.name as team_a_name, 
                                              team_b.name as team_b_name')
                                   ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                                   ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                                   ->where('matchs.is_tournament', true)
                                   ->orderBy('matchs.match_date', 'ASC')
                                   ->findAll();

            $tournamentData = [
                'semifinals' => [],
                'final' => null,
                'third_place' => null
            ];

            foreach ($matches as $match) {
                $games = $this->gameModel->getGamesByMatch($match['match_id']);
                $match['games'] = $games;
                $match['winner'] = $this->determineMatchWinner($games, true);
                $match['is_completed'] = $this->isMatchCompleted($games, true);
                
                // Les 2 premiers matchs sont les demi-finales
                if (count($tournamentData['semifinals']) < 2) {
                    $tournamentData['semifinals'][] = $match;
                } elseif ($tournamentData['third_place'] === null) {
                    // Le 3ème match est le match de 3ème place (se joue avant la finale)
                    $tournamentData['third_place'] = $match;
                } else {
                    // Le 4ème match est la finale (se joue après le match de 3ème place)
                    $tournamentData['final'] = $match;
                }
            }

            return $tournamentData;
        } catch (\Exception $e) {
            log_message('error', 'Poules - Erreur lors de la récupération des matchs de tournoi: ' . $e->getMessage());
            return ['semifinals' => [], 'final' => null, 'third_place' => null];
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
        
        return null;
    }

    /**
     * Vérifie si un match est terminé
     */
    private function isMatchCompleted($games, $isTournament)
    {
        if (empty($games)) {
            return false;
        }

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
            // BO3 : match terminé quand une équipe a 2 victoires
            return ($teamAWins >= 2 || $teamBWins >= 2);
        } else {
            // Poule : match terminé quand il y a au moins 1 game avec des scores
            return $teamAWins > 0 || $teamBWins > 0;
        }
    }

}