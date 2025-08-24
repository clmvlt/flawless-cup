<?php

namespace App\Models;

use CodeIgniter\Model;

class PlayerModel extends Model
{
    protected $table = 'player';
    protected $primaryKey = 'discord_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['discord_id', 'tag', 'pseudo', 'avatar', 'is_leader', 'riot_id', 'mmr', 'tier_id', 'rr', 'team_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_leader' => 'boolean',
        'mmr' => 'integer',
        'tier_id' => '?integer',
        'rr' => '?integer'
    ];
    protected array $castHandlers = [];

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules = [
        'discord_id' => 'required|max_length[50]|is_unique[player.discord_id]',
        'tag' => 'permit_empty|max_length[50]',
        'pseudo' => 'permit_empty|max_length[50]',
        'avatar' => 'permit_empty|max_length[255]',
        'is_leader' => 'permit_empty|in_list[0,1]',
        'riot_id' => 'permit_empty|max_length[255]',
        'mmr' => 'permit_empty|integer',
        'tier_id' => 'permit_empty|integer',
        'rr' => 'permit_empty|integer',
        'team_id' => 'permit_empty|max_length[50]'
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

    public function getPlayerWithTeam($discordId)
    {
        try {
            return $this->select('player.*, team.name as team_name, team.poule_id')
                        ->join('team', 'team.team_id = player.team_id', 'left')
                        ->where('player.discord_id', $discordId)
                        ->first();
        } catch (\Exception $e) {
            // Si la table team n'existe pas, retourner seulement les données du joueur
            log_message('warning', 'Team table not found, returning player data only: ' . $e->getMessage());
            return $this->where('discord_id', $discordId)->first();
        }
    }

    public function getPlayersByTeam($teamId)
    {
        return $this->where('team_id', $teamId)->findAll();
    }

    public function getTeamLeader($teamId)
    {
        return $this->where('team_id', $teamId)
                    ->where('is_leader', true)
                    ->first();
    }

    public function getPlayersByPoule($pouleId)
    {
        return $this->select('player.*, team.name as team_name')
                    ->join('team', 'team.team_id = player.team_id')
                    ->where('team.poule_id', $pouleId)
                    ->findAll();
    }

    public function getTopPlayersByMMR($limit = 10)
    {
        return $this->select('player.*, team.name as team_name')
                    ->join('team', 'team.team_id = player.team_id', 'left')
                    ->where('player.mmr IS NOT NULL')
                    ->orderBy('player.mmr', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Met à jour l'équipe d'un joueur et synchronise automatiquement
     */
    public function updatePlayerTeam($discordId, $newTeamId)
    {
        $player = $this->find($discordId);
        $oldTeamId = $player['team_id'] ?? null;
        
        // Mettre à jour l'équipe du joueur
        $result = $this->update($discordId, ['team_id' => $newTeamId]);
        
        if ($result) {
            // Synchroniser le nombre de membres des équipes affectées
            $teamModel = new \App\Models\TeamModel();
            
            if ($oldTeamId) {
                $teamModel->updateMemberCount($oldTeamId);
            }
            
            if ($newTeamId) {
                $teamModel->updateMemberCount($newTeamId);
            }
        }
        
        return $result;
    }

    /**
     * Supprime un joueur et met à jour automatiquement son équipe
     */
    public function removePlayerFromTeam($discordId)
    {
        $player = $this->find($discordId);
        $teamId = $player['team_id'] ?? null;
        
        $result = $this->update($discordId, ['team_id' => null]);
        
        if ($result && $teamId) {
            $teamModel = new \App\Models\TeamModel();
            $teamModel->updateMemberCount($teamId);
        }
        
        return $result;
    }

    /**
     * Ajoute un joueur à une équipe avec synchronisation automatique
     */
    public function addPlayerToTeam($discordId, $teamId)
    {
        return $this->updatePlayerTeam($discordId, $teamId);
    }
}