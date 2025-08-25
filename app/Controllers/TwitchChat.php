<?php

namespace App\Controllers;

class TwitchChat extends BaseController
{
    public function index()
    {
        // Récupérer le nom du canal depuis l'URL (paramètre GET)
        $channel = $this->request->getGet('channel') ?: 'flawless_cup'; // Valeur par défaut
        
        return view('twitch_chat', [
            'channel' => $channel
        ]);
    }
    
    public function getToken()
    {
        // Token anonyme pour lire le chat (pas besoin d'OAuth pour lire)
        return $this->response->setJSON([
            'token' => 'anonymous',
            'channel' => $this->request->getGet('channel') ?: 'flawless_cup'
        ]);
    }
}