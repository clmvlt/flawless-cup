<?php

namespace App\Models;

use CodeIgniter\Model;

class GameModel extends Model
{
    protected $table = 'game';
    protected $primaryKey = 'game_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['game_id', 'a_score', 'b_score', 'match_id', 'team_id_a', 'team_id_b'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'a_score' => 'integer',
        'b_score' => 'integer'
    ];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'game_id' => 'required|max_length[50]|is_unique[game.game_id]',
        'a_score' => 'permit_empty|integer',
        'b_score' => 'permit_empty|integer',
        'match_id' => 'required|max_length[50]',
        'team_id_a' => 'required|max_length[50]',
        'team_id_b' => 'required|max_length[50]'
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

    public function getGameWithTeams($gameId)
    {
        return $this->select('game.*, 
                            team_a.name as team_a_name, 
                            team_b.name as team_b_name,
                            matchs.match_date, 
                            matchs.is_tournament')
                    ->join('team as team_a', 'team_a.team_id = game.team_id_a')
                    ->join('team as team_b', 'team_b.team_id = game.team_id_b')
                    ->join('matchs', 'matchs.match_id = game.match_id')
                    ->where('game.game_id', $gameId)
                    ->first();
    }

    public function getGamesByMatch($matchId)
    {
        return $this->select('game.*, 
                            team_a.name as team_a_name, 
                            team_b.name as team_b_name')
                    ->join('team as team_a', 'team_a.team_id = game.team_id_a')
                    ->join('team as team_b', 'team_b.team_id = game.team_id_b')
                    ->where('game.match_id', $matchId)
                    ->findAll();
    }

    public function getGamesByTeam($teamId)
    {
        return $this->select('game.*, 
                            team_a.name as team_a_name, 
                            team_b.name as team_b_name,
                            matchs.match_date')
                    ->join('team as team_a', 'team_a.team_id = game.team_id_a')
                    ->join('team as team_b', 'team_b.team_id = game.team_id_b')
                    ->join('matchs', 'matchs.match_id = game.match_id')
                    ->groupStart()
                        ->where('game.team_id_a', $teamId)
                        ->orWhere('game.team_id_b', $teamId)
                    ->groupEnd()
                    ->findAll();
    }
}