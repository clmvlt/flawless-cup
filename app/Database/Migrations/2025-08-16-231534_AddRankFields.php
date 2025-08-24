<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRankFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('player', [
            'tier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Valorant tier ID from Riot API'
            ],
            'rr' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Ranked Rating points'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('player', ['tier_id', 'rr']);
    }
}
