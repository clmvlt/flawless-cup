<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeamInvitationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'invitation_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'team_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'max_uses' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'current_uses' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('invitation_id', true);
        $this->forge->addForeignKey('team_id', 'team', 'team_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'player', 'discord_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('team_invitation');
    }

    public function down()
    {
        $this->forge->dropTable('team_invitation');
    }
}
