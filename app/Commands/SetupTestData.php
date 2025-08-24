<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\TeamModel;
use App\Models\PlayerModel;

class SetupTestData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'setup:testdata';
    protected $description = 'Configure des données de test pour les équipes et joueurs';

    public function run(array $params)
    {
        $teamModel = new TeamModel();
        $playerModel = new PlayerModel();

        CLI::write('🚀 Configuration des données de test...', 'yellow');

        // 1. Corriger l'équipe existante
        $this->fixExistingTeam($teamModel);

        // 2. Créer une seconde équipe
        $this->createSecondTeam($teamModel);

        // 3. Assigner les joueurs aux équipes
        $this->assignPlayersToTeams($playerModel, $teamModel);

        // 4. Mettre à jour les MMR pour des rangs variés
        $this->updatePlayerMMR($playerModel);

        CLI::write('✅ Configuration terminée !', 'green');
        
        // Afficher le résultat
        $this->displayResult($teamModel, $playerModel);
    }

    private function fixExistingTeam($teamModel)
    {
        CLI::write('🔧 Correction de l\'équipe existante...', 'blue');
        
        $team = $teamModel->where('poule_id', 'default_poule')->first();
        if ($team) {
            $teamModel->update($team['team_id'], ['poule_id' => 'A']);
            CLI::write("  ✓ Équipe '{$team['name']}' assignée à la Poule A", 'green');
        }
    }

    private function createSecondTeam($teamModel)
    {
        CLI::write('🏆 Création d\'une seconde équipe...', 'blue');
        
        $secondTeamId = 'team_' . uniqid();
        $secondTeam = [
            'team_id' => $secondTeamId,
            'name' => 'FlawlessPro',
            'description' => 'Équipe de test pour la démo',
            'poule_id' => 'B'
        ];
        
        $teamModel->insert($secondTeam);
        CLI::write("  ✓ Équipe 'FlawlessPro' créée et assignée à la Poule B", 'green');
        
        return $secondTeamId;
    }

    private function assignPlayersToTeams($playerModel, $teamModel)
    {
        CLI::write('👥 Assignment des joueurs aux équipes...', 'blue');
        
        $teams = $teamModel->findAll();
        $players = $playerModel->where('team_id IS NULL')->orWhere('team_id', '')->findAll();
        
        if (count($teams) >= 2 && count($players) >= 2) {
            // Assigner les 2 premiers joueurs à la première équipe
            for ($i = 0; $i < min(2, count($players)); $i++) {
                $playerModel->update($players[$i]['discord_id'], ['team_id' => $teams[0]['team_id']]);
                CLI::write("  ✓ {$players[$i]['pseudo']} ajouté à {$teams[0]['name']}", 'green');
            }
            
            // Assigner les joueurs restants à la seconde équipe
            for ($i = 2; $i < count($players); $i++) {
                $playerModel->update($players[$i]['discord_id'], ['team_id' => $teams[1]['team_id']]);
                CLI::write("  ✓ {$players[$i]['pseudo']} ajouté à {$teams[1]['name']}", 'green');
            }
        }
    }

    private function updatePlayerMMR($playerModel)
    {
        CLI::write('📊 Mise à jour des MMR pour des rangs variés...', 'blue');
        
        $players = $playerModel->findAll();
        $mmrValues = [1500, 800, 1200, 450]; // Diamant, Or, Platine, Argent
        
        foreach ($players as $index => $player) {
            if (isset($mmrValues[$index])) {
                $playerModel->update($player['discord_id'], ['mmr' => $mmrValues[$index]]);
                $rank = $this->convertMMRToRank($mmrValues[$index]);
                CLI::write("  ✓ {$player['pseudo']}: MMR {$mmrValues[$index]} ({$rank})", 'green');
            }
        }
    }

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

    private function displayResult($teamModel, $playerModel)
    {
        CLI::write("\n📋 Résultat final :", 'yellow');
        
        $teams = $teamModel->getTeamsWithMembers();
        foreach ($teams as $team) {
            $poule = $team['poule_id'] ?? 'Aucune';
            CLI::write("  🏆 {$team['name']} (Poule {$poule}) - {$team['member_count']} membres :", 'cyan');
            
            foreach ($team['players'] as $player) {
                $rank = $this->convertMMRToRank($player['mmr'] ?? 0);
                CLI::write("    👤 {$player['pseudo']} - {$rank} (MMR: {$player['mmr']})", 'white');
            }
        }
    }
}