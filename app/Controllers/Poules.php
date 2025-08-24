<?php

namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\PlayerModel;

class Poules extends BaseController
{
    protected $teamModel;
    protected $playerModel;

    public function __construct()
    {
        helper('auth');
        $this->teamModel = new TeamModel();
        $this->playerModel = new PlayerModel();
    }

    public function index()
    {
        // NE PAS synchroniser automatiquement - laisser les admins gérer les poules
        
        // Récupérer toutes les équipes avec le nombre de membres et leurs joueurs
        $realTeams = $this->teamModel->getTeamsWithMembers();
        
        // Filtrer les équipes qui ont au moins 1 membre
        $validTeams = array_filter($realTeams, function($team) {
            return $team['member_count'] > 0;
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
                'resultats' => $this->getTeamResults($team['team_id']) // Récupérer les vrais résultats
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

        $data = array_merge(getAuthData(), [
            'title' => 'Poules - Flawless Cup',
            'pouleA' => $pouleA,
            'pouleB' => $pouleB,
            'teamsWithoutPoule' => $teamsWithoutPoule,
            'totalTeams' => count($formattedTeams),
            'allResultsComplete' => $allResultsComplete
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
        // Pour l'instant, retourner des résultats par défaut
        // À terme, ceci devrait récupérer les vrais résultats depuis une table matches
        $matchModel = new \App\Models\MatchModel();
        
        try {
            $matches = $matchModel->where('team_a_id', $teamId)
                                ->orWhere('team_b_id', $teamId)
                                ->findAll();
            
            $results = [];
            foreach ($matches as $match) {
                if (isset($match['winner_team_id'])) {
                    if ($match['winner_team_id'] === $teamId) {
                        $results[] = 'V';
                    } else {
                        $results[] = 'D';
                    }
                } else {
                    $results[] = '-'; // Match pas encore joué
                }
            }
            
            // Compléter avec des tirets si moins de 3 matchs
            while (count($results) < 3) {
                $results[] = '-';
            }
            
            return array_slice($results, 0, 3); // Max 3 résultats
            
        } catch (\Exception $e) {
            // Si la table matches n'existe pas encore, utiliser des résultats par défaut
            return ['-', '-', '-'];
        }
    }
}