<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRememberTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => false
            ],
            'player_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45, // Support IPv6
                'null' => false
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Clé primaire
        $this->forge->addPrimaryKey('token');
        
        // Index pour optimiser les recherches
        $this->forge->addKey('player_id');
        $this->forge->addKey('expires_at');
        $this->forge->addKey(['player_id', 'ip_address']);

        // Clé étrangère vers la table player
        $this->forge->addForeignKey('player_id', 'player', 'discord_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('remember_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('remember_tokens');
    }
}
