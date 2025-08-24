<?php

namespace App\Controllers;

use App\Services\RiotApiService;

class Overlay extends BaseController
{
    private $riotService;

    public function __construct()
    {
        $this->riotService = new RiotApiService();
    }

    public function index()
    {
        // Récupérer les données pour uri#zeub
        $playerName = 'uri';
        $tag = 'zeub';
        
        // Appeler l'API Valorant
        $riotData = $this->riotService->getValorantMMR($playerName, $tag);
        $rankData = $this->riotService->formatRankData($riotData);
        
        if ($rankData) {
            // Obtenir les informations détaillées du rang
            $detailedRank = $this->riotService->getDetailedRank(
                $rankData['current_rank']['elo'],
                $rankData['current_rank']['rr'],
                $rankData['current_rank']['tier_id']
            );
            
            $data = [
                'account' => $rankData['account'],
                'rank' => $detailedRank,
                'rr' => $rankData['current_rank']['rr'],
                'last_change' => $rankData['current_rank']['last_change'],
                'peak_rank' => $rankData['peak_rank'],
                'success' => true
            ];
        } else {
            // Données par défaut en cas d'erreur
            $data = [
                'account' => [
                    'name' => $playerName,
                    'tag' => $tag
                ],
                'rank' => [
                    'name' => 'Non classé',
                    'color' => '#8B8B8B',
                    'icon' => $this->riotService->getRankIcon(0)
                ],
                'rr' => 0,
                'last_change' => 0,
                'success' => false,
                'error' => 'Impossible de récupérer les données Valorant'
            ];
        }
        
        return view('overlay', $data);
    }
}