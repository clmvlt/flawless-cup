<?php

use App\Models\PlayerModel;

if (!function_exists('getAuthData')) {
    function getAuthData()
    {
        $data = [
            'isLoggedIn' => false,
            'player' => null
        ];

        // Vérifier si l'utilisateur est connecté
        if (session()->has('player_id')) {
            try {
                $playerModel = new PlayerModel();
                $player = $playerModel->getPlayerWithTeam(session()->get('player_id'));
                
                if ($player) {
                    $data['isLoggedIn'] = true;
                    $data['player'] = $player;
                }
            } catch (\Exception $e) {
                log_message('warning', 'Error fetching player data in auth helper: ' . $e->getMessage());
                // En cas d'erreur de base de données, garder les données basiques de session
                if (session()->has('player_data')) {
                    $data['isLoggedIn'] = true;
                    $data['player'] = session()->get('player_data');
                }
            }
        }

        return $data;
    }
}

if (!function_exists('isLoggedIn')) {
    /**
     * Vérifie si un utilisateur est connecté
     * 
     * @return bool
     */
    function isLoggedIn(): bool
    {
        return session()->has('player_id');
    }
}

if (!function_exists('getCurrentPlayer')) {
    /**
     * Retourne les données du joueur actuellement connecté
     * 
     * @return array|null
     */
    function getCurrentPlayer(): ?array
    {
        return session()->has('player_id') ? session()->get('player_data') : null;
    }
}

if (!function_exists('getPlayerName')) {
    /**
     * Retourne le nom du joueur connecté
     * 
     * @return string
     */
    function getPlayerName(): string
    {
        $player = getCurrentPlayer();
        return $player ? ($player['pseudo'] ?? 'Utilisateur') : 'Invité';
    }
}

if (!function_exists('requireAuth')) {
    /**
     * Force la redirection vers la page d'accueil si l'utilisateur n'est pas connecté
     * À utiliser dans les contrôleurs
     * 
     * @param string $message Message d'erreur personnalisé
     * @return \CodeIgniter\HTTP\RedirectResponse|null
     */
    function requireAuth(string $message = 'Vous devez être connecté pour accéder à cette page')
    {
        if (!isLoggedIn()) {
            return redirect()->to('/')->with('error', $message);
        }
        return null;
    }
}

if (!function_exists('getRankIcon')) {
    /**
     * Retourne l'URL de l'icône du rang basée sur le tier_id
     * 
     * @param int $tierId L'ID du tier
     * @return string L'URL de l'icône
     */
    function getRankIcon($tierId): string
    {
        $rankImages = [
            0 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/0/smallicon.png', // Unranked
            1 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/3/smallicon.png', // Iron 1
            2 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/4/smallicon.png', // Iron 2
            3 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/5/smallicon.png', // Iron 3
            4 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/6/smallicon.png', // Bronze 1
            5 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/7/smallicon.png', // Bronze 2
            6 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/8/smallicon.png', // Bronze 3
            7 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/7/smallicon.png', // Bronze 2
            8 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/8/smallicon.png', // Bronze 3
            9 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/9/smallicon.png', // Silver 1
            10 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/10/smallicon.png', // Silver 2
            11 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/11/smallicon.png', // Silver 3
            12 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/12/smallicon.png', // Gold 1
            13 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/13/smallicon.png', // Gold 2
            14 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/14/smallicon.png', // Gold 3
            15 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/15/smallicon.png', // Platinum 1
            16 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/16/smallicon.png', // Platinum 2
            17 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/17/smallicon.png', // Platinum 3
            18 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/18/smallicon.png', // Diamond 1
            19 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/19/smallicon.png', // Diamond 2
            20 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/20/smallicon.png', // Diamond 3
            21 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/21/smallicon.png', // Ascendant 1
            22 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/22/smallicon.png', // Ascendant 2
            23 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/23/smallicon.png', // Ascendant 3
            24 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/24/smallicon.png', // Immortal 1
            25 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/25/smallicon.png', // Immortal 2
            26 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/26/smallicon.png', // Immortal 3
            27 => 'https://media.valorant-api.com/competitivetiers/03621f52-342b-cf4e-4f86-9350a49c6d04/27/smallicon.png'  // Radiant
        ];

        return $rankImages[$tierId] ?? $rankImages[0];
    }
}