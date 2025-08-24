<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\Auth;

class RememberTokenFilter implements FilterInterface
{
    /**
     * Vérifie si l'utilisateur peut être reconnecté automatiquement via un remember token
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ne pas vérifier pour les routes d'authentification pour éviter les boucles
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Exclure les routes d'auth et les assets
        $excludedPaths = [
            '/register',
            '/logout',
            '/css/',
            '/js/',
            '/fonts/',
            '/images/',
            '/favicon.ico'
        ];
        
        foreach ($excludedPaths as $excludedPath) {
            if (strpos($path, $excludedPath) !== false) {
                return;
            }
        }

        // Si l'utilisateur n'est pas connecté, vérifier le remember token
        if (!session()->has('player_id')) {
            $auth = new Auth();
            $reconnected = $auth->checkRememberToken($request);
            
            if ($reconnected) {
                // Optionnel: rediriger vers la page demandée avec un message
                // return redirect()->to($path)->with('info', 'Reconnexion automatique effectuée');
            }
        }
    }

    /**
     * Après la réponse (optionnel pour le nettoyage)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la réponse pour ce filtre
    }
}