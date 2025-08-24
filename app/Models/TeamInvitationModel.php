<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamInvitationModel extends Model
{
    protected $table            = 'team_invitation';
    protected $primaryKey       = 'invitation_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invitation_id',
        'team_id',
        'created_by',
        'expires_at',
        'max_uses',
        'current_uses',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
        'max_uses' => '?integer',
        'current_uses' => 'integer'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getActiveInvitationsForTeam($teamId)
    {
        return $this->where('team_id', $teamId)
                   ->where('is_active', true)
                   ->where('expires_at >', date('Y-m-d H:i:s'))
                   ->findAll();
    }

    public function getValidInvitation($invitationId)
    {
        return $this->where('invitation_id', $invitationId)
                   ->where('is_active', true)
                   ->where('expires_at >', date('Y-m-d H:i:s'))
                   ->first();
    }

    public function incrementUse($invitationId)
    {
        $invitation = $this->find($invitationId);
        if (!$invitation) {
            return false;
        }

        $newUses = $invitation['current_uses'] + 1;
        $updateData = ['current_uses' => $newUses];

        // Désactiver si limite atteinte
        if ($invitation['max_uses'] && $newUses >= $invitation['max_uses']) {
            $updateData['is_active'] = false;
        }

        return $this->update($invitationId, $updateData);
    }
}
