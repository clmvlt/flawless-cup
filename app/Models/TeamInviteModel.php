<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamInviteModel extends Model
{
    protected $table = 'team_invite';
    protected $primaryKey = 'token';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['token', 'team_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'token' => 'required|max_length[50]|is_unique[team_invite.token]',
        'team_id' => 'required|max_length[50]'
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

    public function getInviteWithTeam($token)
    {
        return $this->select('team_invite.*, team.name as team_name')
                    ->join('team', 'team.team_id = team_invite.team_id')
                    ->where('team_invite.token', $token)
                    ->first();
    }

    public function getInvitesByTeam($teamId)
    {
        return $this->where('team_id', $teamId)->findAll();
    }
}