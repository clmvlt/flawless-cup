<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupTokens extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'cleanup:tokens';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Nettoie les tokens de connexion persistants expirés';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'cleanup:tokens';

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
        $rememberTokenModel = new \App\Models\RememberTokenModel();
        
        CLI::write('Nettoyage des tokens de connexion expirés...', 'yellow');
        
        $deletedCount = $rememberTokenModel->cleanupExpiredTokens();
        
        CLI::write("✓ Nettoyage terminé. {$deletedCount} tokens expirés supprimés.", 'green');
    }
}
