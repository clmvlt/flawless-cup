<?php

namespace App\Controllers;

use App\Models\PlayerModel;
use App\Services\RiotApiService;

class Riot extends BaseController
{
    protected $playerModel;
    protected $riotService;

    public function __construct()
    {
        helper('auth');
        $this->playerModel = new PlayerModel();
        $this->riotService = new RiotApiService();
    }

    public function syncProfile()
    {
        // Désactivé : Le Riot ID ne peut plus être modifié une fois configuré
        return redirect()->to('/dashboard')->with('error', 'Le Riot ID ne peut pas être modifié une fois configuré.');
    }


    public function showSetup()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté');
        }

        $authData = getAuthData();
        return view('setup_riot', $authData);
    }

    public function setup()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté');
        }

        $playerName = $this->request->getPost('player_name');
        $tag = $this->request->getPost('tag');

        if (!$playerName || !$tag) {
            return redirect()->back()->with('error', 'Nom de joueur et tag requis');
        }

        // Validation
        if (!$this->riotService->validateRiotID($playerName, $tag)) {
            return redirect()->back()->with('error', 'Format du Riot ID invalide');
        }

        try {
            // Vérifier que le joueur existe dans Valorant
            $riotData = $this->riotService->getValorantMMR($playerName, $tag);
            
            if (!$riotData) {
                return redirect()->back()->with('error', 'Joueur Valorant introuvable. Vérifiez votre nom et tag.');
            }

            // Formater les données
            $formattedData = $this->riotService->formatRankData($riotData);
            
            // Mettre à jour le joueur
            $playerId = session()->get('player_id');
            $updateData = [
                'riot_id' => $formattedData['account']['name'] . '#' . $formattedData['account']['tag'],
                'mmr' => $formattedData['current_rank']['elo'],
                'tier_id' => $formattedData['current_rank']['tier_id'],
                'rr' => $formattedData['current_rank']['rr']
            ];
            
            // Si on a des informations de tier, les stocker aussi (à adapter selon la structure de la base)
            // Note: Pour l'instant on stocke juste le MMR, mais on pourrait ajouter tier_id et rr si besoin

            $this->playerModel->update($playerId, $updateData);

            log_message('info', 'Riot profile configured for player: ' . $playerId . ' - ' . $updateData['riot_id']);

            return redirect()->to('/dashboard')->with('success', 'Profil Valorant configuré avec succès ! Bienvenue dans Flawless Cup !');

        } catch (\Exception $e) {
            log_message('error', 'Riot setup error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la vérification du profil Valorant');
        }
    }

    public function updateMMR($playerId)
    {
        try {
            $player = $this->playerModel->find($playerId);
            
            if (!$player || !$player['riot_id']) {
                return false;
            }

            // Parse le riot_id (format: "Nom#Tag")
            $riotIdParts = explode('#', $player['riot_id']);
            if (count($riotIdParts) !== 2) {
                return false;
            }

            $playerName = $riotIdParts[0];
            $tag = $riotIdParts[1];

            // Récupérer les nouvelles données
            $riotData = $this->riotService->getValorantMMR($playerName, $tag);
            
            if (!$riotData) {
                log_message('warning', 'Could not update MMR for player: ' . $playerId);
                return false;
            }

            $formattedData = $this->riotService->formatRankData($riotData);
            
            // Mettre à jour le MMR, tier_id et RR
            $this->playerModel->update($playerId, [
                'mmr' => $formattedData['current_rank']['elo'],
                'tier_id' => $formattedData['current_rank']['tier_id'],
                'rr' => $formattedData['current_rank']['rr']
            ]);

            log_message('info', 'MMR updated for player: ' . $playerId . ' - New MMR: ' . $formattedData['current_rank']['elo']);
            return true;

        } catch (\Exception $e) {
            log_message('error', 'MMR update error for player ' . $playerId . ': ' . $e->getMessage());
            return false;
        }
    }

}