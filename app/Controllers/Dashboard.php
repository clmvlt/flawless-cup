<?php

namespace App\Controllers;

use App\Models\PlayerModel;
use App\Services\RiotApiService;

class Dashboard extends BaseController
{
    protected $playerModel;
    protected $riotService;

    public function __construct()
    {
        helper('auth');
        $this->playerModel = new PlayerModel();
        $this->riotService = new RiotApiService();
    }

    public function index()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour accéder à cette page');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->getPlayerWithTeam($playerId);

        if (!$player) {
            session()->destroy();
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a configuré son compte Riot
        if (!$player['riot_id'] || empty($player['riot_id'])) {
            return redirect()->to('/riot/setup')->with('error', 'Vous devez d\'abord configurer votre compte Valorant pour accéder au dashboard');
        }

        // Calculer les informations de rang si MMR disponible
        $rankInfo = null;
        if (isset($player['mmr']) && $player['mmr']) {
            $tierId = $player['tier_id'] ?? null;
            $rr = $player['rr'] ?? 0;
            $rankInfo = $this->riotService->getDetailedRank($player['mmr'], $rr, $tierId);
        }

        // Récupérer les membres de l'équipe si le joueur en a une
        $teamMembers = null;
        if (isset($player['team_id']) && $player['team_id'] && $player['team_id'] !== 'default_team') {
            try {
                $teamMembers = $this->playerModel->where('team_id', $player['team_id'])
                                               ->orderBy('is_leader', 'DESC')
                                               ->orderBy('pseudo', 'ASC')
                                               ->findAll();
            } catch (\Exception $e) {
                log_message('warning', 'Unable to fetch team members: ' . $e->getMessage());
                $teamMembers = null;
            }
        }

        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Dashboard - Flawless Cup',
            'discord_raw_data' => session()->get('discord_raw_data'),
            'rank_info' => $rankInfo,
            'team_members' => $teamMembers
        ]);

        return view('dashboard', $data);
    }
}