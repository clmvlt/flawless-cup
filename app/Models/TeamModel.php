<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamModel extends Model
{
    protected $table = 'team';
    protected $primaryKey = 'team_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['team_id', 'name', 'description', 'poule_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'team_id' => 'required|max_length[50]|is_unique[team.team_id]',
        'name' => 'required|max_length[50]',
        'description' => 'permit_empty|max_length[255]',
        'poule_id' => 'permit_empty|max_length[50]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getTeamWithPoule($teamId)
    {
        return $this->select('team.*, poule.poule_id as poule_name')
                    ->join('poule', 'poule.poule_id = team.poule_id', 'left')
                    ->where('team.team_id', $teamId)
                    ->first();
    }

    public function getTeamsByPoule($pouleId)
    {
        return $this->where('poule_id', $pouleId)->findAll();
    }

    public function getTeamsWithMemberCount()
    {
        return $this->select('team.*, COUNT(player.discord_id) as member_count')
                    ->join('player', 'player.team_id = team.team_id', 'left')
                    ->groupBy('team.team_id')
                    ->orderBy('team.name', 'ASC')
                    ->findAll();
    }

    public function getTeamsWithMembers()
    {
        $teams = $this->getTeamsWithMemberCount();
        
        foreach ($teams as &$team) {
            // Récupérer les joueurs de cette équipe avec toutes leurs informations
            $playerModel = new \App\Models\PlayerModel();
            $players = $playerModel->where('team_id', $team['team_id'])->findAll();
            
            // Enrichir les données des joueurs
            foreach ($players as &$player) {
                // Utiliser tag comme discord_username si disponible
                if (empty($player['discord_username']) && !empty($player['tag'])) {
                    $player['discord_username'] = $player['tag'];
                }
                
                // Utiliser pseudo si disponible, sinon utiliser une partie du discord_id
                if (empty($player['pseudo'])) {
                    $player['pseudo'] = !empty($player['tag']) ? 
                        explode('#', $player['tag'])[0] : 
                        'Joueur_' . substr($player['discord_id'], -4);
                }
            }
            
            $team['players'] = $players;
        }
        
        return $teams;
    }

    /**
     * Récupère les équipes par poule avec leurs membres
     */
    public function getTeamsByPouleWithMembers($pouleId)
    {
        $teams = $this->where('poule_id', $pouleId)
                      ->select('team.*, COUNT(player.discord_id) as member_count')
                      ->join('player', 'player.team_id = team.team_id', 'left')
                      ->groupBy('team.team_id')
                      ->orderBy('team.name', 'ASC')
                      ->findAll();

        foreach ($teams as &$team) {
            $playerModel = new \App\Models\PlayerModel();
            $team['players'] = $playerModel->where('team_id', $team['team_id'])->findAll();
        }

        return $teams;
    }

    /**
     * Met à jour automatiquement le nombre de membres d'une équipe
     */
    public function updateMemberCount($teamId)
    {
        $playerModel = new \App\Models\PlayerModel();
        $memberCount = $playerModel->where('team_id', $teamId)->countAllResults();
        
        // Optionnel: Si vous avez une colonne member_count dans la table team
        // $this->update($teamId, ['member_count' => $memberCount]);
        
        return $memberCount;
    }

    /**
     * Récupère toutes les équipes avec leurs joueurs pour l'admin
     */
    public function getTeamsWithPlayers()
    {
        return $this->select('team.*, poule.poule_id as poule_name')
                    ->join('poule', 'poule.poule_id = team.poule_id', 'left')
                    ->groupStart()
                        ->where('team.poule_id IS NULL')
                        ->orWhere('team.poule_id !=', 'default_poule')
                    ->groupEnd()
                    ->orderBy('team.name', 'ASC')
                    ->findAll();
    }
}