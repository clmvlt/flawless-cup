<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConnectionLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'log_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => false,
                'comment'    => 'Adresse IP du visiteur'
            ],
            'user_agent' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'User Agent du navigateur'
            ],
            'requested_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => false,
                'comment'    => 'URL demandée'
            ],
            'http_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false,
                'default'    => 'GET',
                'comment'    => 'Méthode HTTP (GET, POST, etc.)'
            ],
            'referer' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
                'comment'    => 'Page de provenance'
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'       => true,
                'comment'    => 'ID de session'
            ],
            'user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'ID utilisateur si connecté'
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Pays du visiteur (géolocalisation)'
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Ville du visiteur (géolocalisation)'
            ],
            'browser' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Navigateur détecté'
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'Système d\'exploitation'
            ],
            'device_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Desktop', 'Mobile', 'Tablet'],
                'default'    => 'Desktop',
                'comment'    => 'Type d\'appareil'
            ],
            'is_bot' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'comment'    => 'Indique si c\'est un bot/crawler'
            ],
            'response_time' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
                'comment'    => 'Temps de réponse en secondes'
            ],
            'status_code' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
                'comment'    => 'Code de réponse HTTP'
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'comment' => 'Date et heure de la connexion'
            ],
        ]);

        // Clé primaire
        $this->forge->addKey('log_id', true);

        // Index pour optimiser les requêtes
        $this->forge->addKey('ip_address');
        $this->forge->addKey('created_at');
        $this->forge->addKey('user_id');
        $this->forge->addKey('is_bot');
        $this->forge->addKey(['ip_address', 'created_at'], false, 'idx_ip_date');

        // Créer la table
        $this->forge->createTable('connection_logs');

        // Ajouter des commentaires sur la table
        $this->db->query('ALTER TABLE `connection_logs` COMMENT = "Logs des connexions et visites sur le site"');
    }

    public function down()
    {
        $this->forge->dropTable('connection_logs');
    }
}