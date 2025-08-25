<?php

namespace App\Models;

use CodeIgniter\Model;

class MatchModel extends Model
{
    protected $table = 'matchs';
    protected $primaryKey = 'match_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['match_id', 'match_date', 'is_tournament', 'team_id_a', 'team_id_b'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_tournament' => 'boolean'
    ];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'match_id' => 'required|max_length[50]|is_unique[matchs.match_id]',
        'match_date' => 'permit_empty|valid_date',
        'is_tournament' => 'permit_empty',
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

    public function getMatchWithGames($matchId)
    {
        $match = $this->select('matchs.*, 
                              team_a.name as team_a_name, 
                              team_b.name as team_b_name')
                     ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                     ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                     ->where('matchs.match_id', $matchId)
                     ->first();

        if (!$match) {
            return null;
        }

        $gameModel = new GameModel();
        $games = $gameModel->getGamesByMatch($matchId);
        $match['games'] = $games;

        return $match;
    }

    public function getAllMatchesWithGames()
    {
        $matches = $this->select('matchs.*, 
                                team_a.name as team_a_name, 
                                team_b.name as team_b_name')
                       ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                       ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                       ->orderBy('matchs.match_date', 'DESC')
                       ->findAll();

        $gameModel = new GameModel();
        foreach ($matches as &$match) {
            $match['games'] = $gameModel->getGamesByMatch($match['match_id']);
        }

        return $matches;
    }

    public function getMatchesByTeam($teamId)
    {
        return $this->select('matchs.*, 
                            team_a.name as team_a_name, 
                            team_b.name as team_b_name')
                   ->join('team as team_a', 'team_a.team_id = matchs.team_id_a', 'left')
                   ->join('team as team_b', 'team_b.team_id = matchs.team_id_b', 'left')
                   ->groupStart()
                       ->where('matchs.team_id_a', $teamId)
                       ->orWhere('matchs.team_id_b', $teamId)
                   ->groupEnd()
                   ->orderBy('matchs.match_date', 'DESC')
                   ->findAll();
    }

    public function generateMatchId()
    {
        return 'MATCH_' . date('Y') . '_' . uniqid();
    }
}