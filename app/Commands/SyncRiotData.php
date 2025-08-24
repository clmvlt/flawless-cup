<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PlayerModel;
use App\Services\RiotApiService;

class SyncRiotData extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Riot';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'sync:riot';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Synchronise les données Riot (tier_id, rr) pour tous les joueurs existants';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'sync:riot [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--force' => 'Force la resynchronisation même si tier_id existe déjà'
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $playerModel = new PlayerModel();
        $riotService = new RiotApiService();
        
        $force = CLI::getOption('force');
        
        CLI::write('🔄 Synchronisation des données Riot...', 'green');
        
        // Récupérer tous les joueurs avec un riot_id
        $whereClause = "riot_id IS NOT NULL AND riot_id != ''";
        if (!$force) {
            $whereClause .= " AND (tier_id IS NULL OR rr IS NULL)";
        }
        
        $players = $playerModel->where($whereClause)->findAll();
        
        if (empty($players)) {
            CLI::write('✅ Aucun joueur à synchroniser.', 'yellow');
            return;
        }
        
        CLI::write("📊 " . count($players) . " joueur(s) à synchroniser...");
        
        $updated = 0;
        $errors = 0;
        
        foreach ($players as $player) {
            $riotIdParts = explode('#', $player['riot_id']);
            if (count($riotIdParts) !== 2) {
                CLI::write("❌ Format riot_id invalide pour {$player['pseudo']}: {$player['riot_id']}", 'red');
                $errors++;
                continue;
            }
            
            $playerName = $riotIdParts[0];
            $tag = $riotIdParts[1];
            
            CLI::write("🔍 Synchronisation de {$player['pseudo']} ({$playerName}#{$tag})...");
            
            try {
                // Récupérer les données de l'API
                $riotData = $riotService->getValorantMMR($playerName, $tag);
                
                if (!$riotData) {
                    CLI::write("⚠️  Impossible de récupérer les données pour {$player['pseudo']}", 'yellow');
                    $errors++;
                    continue;
                }
                
                $formattedData = $riotService->formatRankData($riotData);
                
                // Mettre à jour le joueur
                $updateData = [
                    'mmr' => $formattedData['current_rank']['elo'],
                    'tier_id' => $formattedData['current_rank']['tier_id'],
                    'rr' => $formattedData['current_rank']['rr']
                ];
                
                $playerModel->update($player['discord_id'], $updateData);
                
                $tierInfo = $riotService->getTierInfo($formattedData['current_rank']['tier_id']);
                CLI::write("✅ {$player['pseudo']}: {$tierInfo['name']} - {$formattedData['current_rank']['rr']} RR", 'green');
                
                $updated++;
                
                // Pause pour éviter de surcharger l'API
                sleep(1);
                
            } catch (\Exception $e) {
                CLI::write("❌ Erreur pour {$player['pseudo']}: " . $e->getMessage(), 'red');
                $errors++;
            }
        }
        
        CLI::newLine();
        CLI::write("🎯 Synchronisation terminée:", 'cyan');
        CLI::write("   ✅ {$updated} joueur(s) mis à jour");
        CLI::write("   ❌ {$errors} erreur(s)");
        
        if ($updated > 0) {
            CLI::write("🚀 Les rangs sont maintenant synchronisés avec l'API Riot!", 'green');
        }
    }
}
