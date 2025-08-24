<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\TeamModel;
use App\Models\PlayerModel;
use App\Models\PouleModel;

class SyncTeams extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'sync:teams';
    protected $description = 'Synchronise les équipes et les joueurs avec les poules';

    public function run(array $params)
    {
        $teamModel = new TeamModel();
        $playerModel = new PlayerModel();
        $pouleModel = new PouleModel();

        CLI::write('🔄 Synchronisation des équipes et joueurs...', 'yellow');

        // Créer les poules A et B si elles n'existent pas
        $this->ensurePoulesExist($pouleModel);

        // Synchroniser les équipes
        $this->syncTeamsToPoules($teamModel);

        // Afficher les statistiques
        $this->displayStats($teamModel, $playerModel);

        CLI::write('✅ Synchronisation terminée !', 'green');
    }

    private function ensurePoulesExist($pouleModel)
    {
        CLI::write('📋 Vérification des poules...', 'blue');

        $pouleA = $pouleModel->find('A');
        if (!$pouleA) {
            $pouleModel->insert(['poule_id' => 'A']);
            CLI::write('  ✓ Poule A créée', 'green');
        }

        $pouleB = $pouleModel->find('B');
        if (!$pouleB) {
            $pouleModel->insert(['poule_id' => 'B']);
            CLI::write('  ✓ Poule B créée', 'green');
        }
    }

    private function syncTeamsToPoules($teamModel)
    {
        CLI::write('🏆 Synchronisation des équipes...', 'blue');

        // Récupérer les équipes sans poule assignée
        $teamsWithoutPoule = $teamModel->where('poule_id IS NULL')
                                      ->orWhere('poule_id', '')
                                      ->findAll();

        if (empty($teamsWithoutPoule)) {
            CLI::write('  ✓ Toutes les équipes sont déjà assignées à une poule', 'green');
            return;
        }

        // Compter les équipes dans chaque poule
        $countA = $teamModel->where('poule_id', 'A')->countAllResults();
        $countB = $teamModel->where('poule_id', 'B')->countAllResults();

        foreach ($teamsWithoutPoule as $team) {
            // Assigner à la poule avec le moins d'équipes
            $assignToPoule = ($countA <= $countB) ? 'A' : 'B';
            
            $teamModel->update($team['team_id'], ['poule_id' => $assignToPoule]);
            
            CLI::write("  ✓ Équipe '{$team['name']}' assignée à la Poule {$assignToPoule}", 'green');
            
            if ($assignToPoule === 'A') {
                $countA++;
            } else {
                $countB++;
            }
        }
    }

    private function displayStats($teamModel, $playerModel)
    {
        CLI::write('📊 Statistiques des équipes :', 'yellow');

        // Statistiques par poule
        $poules = ['A', 'B'];
        foreach ($poules as $poule) {
            $teams = $teamModel->getTeamsByPouleWithMembers($poule);
            $totalPlayers = 0;
            
            foreach ($teams as $team) {
                $totalPlayers += count($team['players']);
            }

            CLI::write("  📋 Poule {$poule}: " . count($teams) . " équipes, {$totalPlayers} joueurs", 'cyan');
            
            foreach ($teams as $team) {
                $memberCount = count($team['players']);
                CLI::write("    🏆 {$team['name']}: {$memberCount} membres", 'white');
            }
        }

        // Statistiques globales
        $totalTeams = $teamModel->countAll();
        $totalPlayers = $playerModel->where('team_id IS NOT NULL')->countAllResults();
        
        CLI::write("\n📈 Total général :", 'yellow');
        CLI::write("  🏆 Équipes: {$totalTeams}", 'cyan');
        CLI::write("  👥 Joueurs dans des équipes: {$totalPlayers}", 'cyan');
    }
}