<?php

namespace App\Controllers;

use App\Libraries\ConnectionLogger;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Contrôleur de base avec logging automatique des connexions
 */
abstract class LoggedBaseController extends Controller
{
    /**
     * Instance du logger de connexions
     */
    protected ConnectionLogger $connectionLogger;

    /**
     * Indique si le logging est activé pour ce contrôleur
     */
    protected bool $loggingEnabled = true;

    /**
     * Liste des actions à exclure du logging
     */
    protected array $excludeFromLogging = [];

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     */
    protected array $helpers = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Initialiser le logger de connexions
        if ($this->loggingEnabled && !$request instanceof CLIRequest) {
            $this->connectionLogger = new ConnectionLogger();
        }
    }

    /**
     * Hook exécuté après chaque action
     */
    protected function _remap($method, ...$params)
    {
        // Vérifier si la méthode existe
        if (!method_exists($this, $method)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Method '{$method}' not found");
        }

        // Exécuter la méthode
        $result = $this->$method(...$params);

        // Logger la connexion si activé
        if ($this->shouldLogThisRequest($method)) {
            $this->logConnection();
        }

        return $result;
    }

    /**
     * Détermine si cette requête doit être loggée
     */
    protected function shouldLogThisRequest(string $method): bool
    {
        // Vérifier si le logging est activé
        if (!$this->loggingEnabled || !isset($this->connectionLogger)) {
            return false;
        }

        // Vérifier si le logging est activé globalement
        if (!$this->connectionLogger->isLoggingEnabled()) {
            return false;
        }

        // Vérifier si cette action est exclue
        if (in_array($method, $this->excludeFromLogging)) {
            return false;
        }

        // Vérifier si cette URL doit être ignorée
        $currentUrl = $this->request->getUri()->getPath();
        if ($this->connectionLogger->shouldIgnoreUrl($currentUrl)) {
            return false;
        }

        // Vérifier si cette IP doit être ignorée
        $ip = $this->request->getIPAddress();
        if ($this->connectionLogger->shouldIgnoreIP($ip)) {
            return false;
        }

        return true;
    }

    /**
     * Enregistre la connexion actuelle
     */
    protected function logConnection(array $additionalData = []): bool
    {
        if (!isset($this->connectionLogger)) {
            return false;
        }

        try {
            return $this->connectionLogger->logCurrentConnection($this->response);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors du logging de connexion: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enregistre une action spécifique
     */
    protected function logAction(string $action, array $data = []): bool
    {
        if (!isset($this->connectionLogger)) {
            return false;
        }

        $logData = array_merge([
            'requested_url' => $this->request->getUri()->getPath() . " [{$action}]"
        ], $data);

        return $this->connectionLogger->logConnection($logData);
    }

    /**
     * Active le logging pour ce contrôleur
     */
    protected function enableLogging(): void
    {
        $this->loggingEnabled = true;
    }

    /**
     * Désactive le logging pour ce contrôleur
     */
    protected function disableLogging(): void
    {
        $this->loggingEnabled = false;
    }

    /**
     * Ajoute une action à la liste d'exclusion du logging
     */
    protected function excludeFromLogging(string $method): void
    {
        if (!in_array($method, $this->excludeFromLogging)) {
            $this->excludeFromLogging[] = $method;
        }
    }
}