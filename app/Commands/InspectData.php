<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\TeamModel;
use App\Models\PlayerModel;

class InspectData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'inspect:data';
    protected $description = 'Inspecte les données des équipes et joueurs';

    public function run(array $params)
    {
        $teamModel = new TeamModel();
        $playerModel = new PlayerModel();

        CLI::write('🔍 Inspection des données...', 'yellow');

        // Afficher toutes les équipes
        $teams = $teamModel->findAll();
        CLI::write("\n🏆 Équipes dans la base (" . count($teams) . ") :", 'cyan');
        
        foreach ($teams as $team) {
            $memberCount = $playerModel->where('team_id', $team['team_id'])->countAllResults();
            $poule = $team['poule_id'] ?? 'Non assignée';
            CLI::write("  - {$team['name']} (ID: {$team['team_id']}) | Poule: {$poule} | Membres: {$memberCount}", 'white');
        }

        // Afficher tous les joueurs
        $players = $playerModel->findAll();
        CLI::write("\n👥 Joueurs dans la base (" . count($players) . ") :", 'cyan');
        
        foreach ($players as $player) {
            $teamName = $player['team_id'] ? 
                $teamModel->find($player['team_id'])['name'] ?? 'Équipe introuvable' : 
                'Aucune équipe';
            $pseudo = $player['pseudo'] ?? 'Pas de pseudo';
            $mmr = $player['mmr'] ?? 'Pas de MMR';
            CLI::write("  - {$pseudo} (Discord: {$player['discord_id']}) | Équipe: {$teamName} | MMR: {$mmr}", 'white');
        }

        // Joueurs sans équipe
        $playersWithoutTeam = $playerModel->where('team_id IS NULL')->orWhere('team_id', '')->findAll();
        if (!empty($playersWithoutTeam)) {
            CLI::write("\n⚠️  Joueurs sans équipe (" . count($playersWithoutTeam) . ") :", 'yellow');
            foreach ($playersWithoutTeam as $player) {
                $pseudo = $player['pseudo'] ?? 'Pas de pseudo';
                CLI::write("  - {$pseudo} (Discord: {$player['discord_id']})", 'red');
            }
        }

        CLI::write("\n✅ Inspection terminée !", 'green');
    }
}