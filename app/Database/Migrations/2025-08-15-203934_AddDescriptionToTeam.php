<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToTeam extends Migration
{
    public function up()
    {
        // Ajouter le champ description à la table team
        $this->forge->addColumn('team', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'name'
            ]
        ]);
    }

    public function down()
    {
        // Supprimer le champ description
        $this->forge->dropColumn('team', 'description');
    }
}
