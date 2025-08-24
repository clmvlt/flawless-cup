<?php
require_once 'vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$players = $db->table('player')->select('pseudo, riot_id, mmr, tier_id, rr')->get()->getResultArray();

echo "🔍 Données des joueurs en base :\n\n";

foreach($players as $p) {
    echo "👤 {$p['pseudo']} ({$p['riot_id']})\n";
    echo "   MMR: {$p['mmr']} | Tier ID: {$p['tier_id']} | RR: {$p['rr']}\n\n";
}