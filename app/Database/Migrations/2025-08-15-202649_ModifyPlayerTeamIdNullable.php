<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPlayerTeamIdNullable extends Migration
{
    public function up()
    {
        // Modifier la colonne team_id pour permettre NULL
        $this->forge->modifyColumn('player', [
            'team_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ]
        ]);
        
        // Mettre à jour les joueurs avec 'default_team' vers NULL
        $this->db->query("UPDATE player SET team_id = NULL WHERE team_id = 'default_team'");
    }

    public function down()
    {
        // Revenir à NOT NULL (attention: cela peut échouer s'il y a des NULL)
        $this->forge->modifyColumn('player', [
            'team_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ]
        ]);
    }
}
