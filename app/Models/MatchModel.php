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
    protected $allowedFields = ['match_id', 'match_date', 'is_tournament'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'match_date' => 'datetime',
        'is_tournament' => 'boolean'
    ];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'match_id' => 'required|max_length[50]|is_unique[matchs.match_id]',
        'match_date' => 'permit_empty|valid_date',
        'is_tournament' => 'permit_empty|in_list[0,1]'
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
}