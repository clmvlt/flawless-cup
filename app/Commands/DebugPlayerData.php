<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\RiotApiService;

class DebugPlayerData extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Debug';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'debug:players';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Debug player data with riot info';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'debug:players';

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
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $riotService = new RiotApiService();
        
        $players = $db->table('player')->select('pseudo, riot_id, mmr, tier_id, rr')->get()->getResultArray();
        
        CLI::write('🔍 Données des joueurs en base :', 'cyan');
        CLI::newLine();
        
        foreach($players as $p) {
            CLI::write("👤 {$p['pseudo']} ({$p['riot_id']})", 'green');
            CLI::write("   MMR: {$p['mmr']} | Tier ID: {$p['tier_id']} | RR: {$p['rr']}");
            
            if ($p['tier_id']) {
                $tierInfo = $riotService->getTierInfo($p['tier_id']);
                CLI::write("   → Rang calculé: {$tierInfo['name']}", 'yellow');
            } else {
                CLI::write("   → Pas de tier_id (conversion MMR utilisée)", 'red');
            }
            
            CLI::newLine();
        }
    }
}
