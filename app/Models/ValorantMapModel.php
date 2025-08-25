<?php

namespace App\Models;

use CodeIgniter\Model;

class ValorantMapModel extends Model
{
    protected $table = 'valorant_maps';
    protected $primaryKey = 'map_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['map_name', 'display_order'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'display_order' => 'integer'
    ];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'map_name' => 'required|max_length[50]',
        'display_order' => 'required|integer'
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

    /**
     * Récupère toutes les maps triées par ordre d'affichage
     */
    public function getAllMapsOrdered()
    {
        return $this->orderBy('display_order', 'ASC')->findAll();
    }

    /**
     * Récupère une map par son nom
     */
    public function getMapByName($mapName)
    {
        return $this->where('map_name', $mapName)->first();
    }

    /**
     * Récupère les maps disponibles (non bannies/pickées) pour une session
     */
    public function getAvailableMapsForSession($sessionId)
    {
        $actionModel = new PickBanActionModel();
        
        // Récupérer les IDs des maps déjà utilisées
        $usedMapIds = $actionModel->where('session_id', $sessionId)
                                 ->whereIn('action_type', ['ban', 'pick'])
                                 ->findColumn('map_id');

        // Récupérer toutes les maps non utilisées
        $query = $this->orderBy('display_order', 'ASC');
        
        if (!empty($usedMapIds)) {
            $query->whereNotIn('map_id', $usedMapIds);
        }
        
        return $query->findAll();
    }

    /**
     * Récupère une map aléatoire parmi celles disponibles
     */
    public function getRandomAvailableMap($sessionId)
    {
        $availableMaps = $this->getAvailableMapsForSession($sessionId);
        
        if (empty($availableMaps)) {
            return null;
        }
        
        $randomIndex = array_rand($availableMaps);
        return $availableMaps[$randomIndex];
    }

    /**
     * Récupère le nombre total de maps
     */
    public function getTotalMapsCount()
    {
        return $this->countAllResults();
    }

    /**
     * Vérifie si une map existe par son ID
     */
    public function mapExists($mapId)
    {
        return $this->find($mapId) !== null;
    }
}