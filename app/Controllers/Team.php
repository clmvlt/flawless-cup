<?php

namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\PlayerModel;
use App\Models\PouleModel;
use App\Models\TeamInvitationModel;
use App\Services\DiscordService;

class Team extends BaseController
{
    protected $teamModel;
    protected $playerModel;
    protected $pouleModel;
    protected $invitationModel;
    protected $discordService;

    public function __construct()
    {
        helper('auth');
        $this->teamModel = new TeamModel();
        $this->playerModel = new PlayerModel();
        $this->pouleModel = new PouleModel();
        $this->invitationModel = new TeamInvitationModel();
        $this->discordService = new DiscordService();
    }

    public function showCreate()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour créer une équipe');
        }

        $player = $this->playerModel->find(session()->get('player_id'));
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a déjà une équipe
        if ($player['team_id'] && $player['team_id'] !== 'default_team') {
            return redirect()->to('/dashboard')->with('error', 'Vous êtes déjà membre d\'une équipe');
        }

        // Vérifier si le joueur est membre du serveur Discord
        if (!$this->discordService->isUserInGuild($player['discord_id'])) {
            return $this->showDiscordRequired('créer une équipe');
        }

        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Créer une équipe - Flawless Cup'
        ]);

        return view('team_create', $data);
    }

    public function create()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour créer une équipe');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a déjà une équipe
        if ($player['team_id'] && $player['team_id'] !== 'default_team') {
            return redirect()->to('/dashboard')->with('error', 'Vous êtes déjà membre d\'une équipe');
        }

        // Vérifier si le joueur est membre du serveur Discord
        if (!$this->discordService->isUserInGuild($player['discord_id'])) {
            return $this->showDiscordRequired('créer une équipe');
        }

        $teamName = $this->request->getPost('team_name');
        $description = $this->request->getPost('description');

        // Validation
        if (!$teamName || strlen(trim($teamName)) < 3) {
            return redirect()->back()->with('error', 'Le nom de l\'équipe doit contenir au moins 3 caractères');
        }

        if (strlen(trim($teamName)) > 50) {
            return redirect()->back()->with('error', 'Le nom de l\'équipe ne peut pas dépasser 50 caractères');
        }

        // Vérifier si le nom d'équipe est déjà pris
        $existingTeam = $this->teamModel->where('name', trim($teamName))->first();
        if ($existingTeam) {
            return redirect()->back()->with('error', 'Ce nom d\'équipe est déjà pris');
        }

        try {
            // Commencer une transaction pour s'assurer que tout fonctionne ensemble
            $db = \Config\Database::connect();
            $db->transStart();

            // Générer un ID unique pour l'équipe
            $teamId = 'team_' . uniqid();

            // Créer l'équipe sans poule assignée
            $teamData = [
                'team_id' => $teamId,
                'name' => trim($teamName),
                'description' => $description ? trim($description) : null,
                'poule_id' => null
            ];

            $teamInsertResult = $this->teamModel->insert($teamData);
            log_message('info', 'Team insert result: ' . ($teamInsertResult ? 'SUCCESS' : 'FAILED'));

            if (!$teamInsertResult) {
                throw new \Exception('Failed to insert team');
            }

            // Mettre à jour le joueur pour qu'il soit le leader de cette équipe
            // Utiliser une requête SQL directe pour éviter les problèmes de validation
            $updateQuery = "UPDATE player SET team_id = ?, is_leader = ? WHERE discord_id = ?";
            $updateResult = $db->query($updateQuery, [$teamId, true, $playerId]);

            log_message('info', 'Player update result: ' . ($updateResult ? 'SUCCESS' : 'FAILED'));
            
            if (!$updateResult) {
                throw new \Exception('Failed to update player with team');
            }
            
            // Vérifier aussi que l'affectation a bien eu lieu
            $affectedRows = $db->affectedRows();
            log_message('info', 'Affected rows: ' . $affectedRows);
            
            if ($affectedRows === 0) {
                throw new \Exception('No rows updated - player might not exist');
            }
            
            // Vérifier que la mise à jour a bien fonctionné
            $updatedPlayer = $this->playerModel->find($playerId);
            log_message('info', 'Player after update - team_id: ' . ($updatedPlayer['team_id'] ?? 'NULL') . ', is_leader: ' . (($updatedPlayer['is_leader'] ?? false) ? 'true' : 'false'));

            // Compléter la transaction
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            log_message('info', 'Team created successfully: ' . $teamId . ' by player: ' . $playerId);

            return redirect()->to('/dashboard')->with('success', 'Équipe "' . $teamName . '" créée avec succès ! Vous êtes maintenant le leader de cette équipe.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            if (isset($db)) {
                $db->transRollback();
            }
            log_message('error', 'Team creation error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'équipe: ' . $e->getMessage());
        }
    }

    public function manageInvitations()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour gérer les invitations');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->getPlayerWithTeam($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Gérer les invitations - Flawless Cup'
        ]);

        // Si le joueur a une équipe et est leader, récupérer les invitations
        if ($player['team_id'] && $player['is_leader']) {
            $invitations = $this->invitationModel->getActiveInvitationsForTeam($player['team_id']);
            $data['invitations'] = $invitations;
            $data['team'] = $player;
        }

        return view('team_invitations', $data);
    }

    public function createInvitation()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player || !$player['team_id'] || !$player['is_leader']) {
            return redirect()->to('/dashboard')->with('error', 'Seul le leader d\'une équipe peut créer des invitations');
        }

        $maxUses = $this->request->getPost('max_uses');
        $expirationHours = $this->request->getPost('expiration_hours') ?: 24;

        try {
            $invitationId = 'inv_' . uniqid();
            $expiresAt = date('Y-m-d H:i:s', strtotime('+' . $expirationHours . ' hours'));

            $invitationData = [
                'invitation_id' => $invitationId,
                'team_id' => $player['team_id'],
                'created_by' => $playerId,
                'expires_at' => $expiresAt,
                'max_uses' => $maxUses ?: null,
                'current_uses' => 0,
                'is_active' => true
            ];

            $this->invitationModel->insert($invitationData);

            log_message('info', 'Invitation created: ' . $invitationId . ' by player: ' . $playerId);

            return redirect()->to('/team/invitations')->with('success', 'Invitation créée avec succès !');

        } catch (\Exception $e) {
            log_message('error', 'Invitation creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'invitation');
        }
    }

    public function joinByInvite($invitationId)
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour rejoindre une équipe');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur est membre du serveur Discord
        if (!$this->discordService->isUserInGuild($player['discord_id'])) {
            return $this->showDiscordRequired('rejoindre une équipe');
        }

        // Vérifier l'invitation
        $invitation = $this->invitationModel->getValidInvitation($invitationId);
        if (!$invitation) {
            return redirect()->to('/dashboard')->with('error', 'Invitation invalide ou expirée');
        }

        // Vérifier si l'équipe existe
        $team = $this->teamModel->find($invitation['team_id']);
        if (!$team) {
            return redirect()->to('/dashboard')->with('error', 'Équipe introuvable');
        }

        // Récupérer les membres de l'équipe
        $members = $this->playerModel->getPlayersByTeam($invitation['team_id']);
        $memberCount = count($members);

        // Vérifier si le joueur a déjà une équipe
        $hasTeam = $player['team_id'] && $player['team_id'] !== 'default_team';

        // Vérifier si l'équipe est pleine
        $isTeamFull = $memberCount >= 5;

        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Invitation à rejoindre ' . $team['name'] . ' - Flawless Cup',
            'team' => $team,
            'members' => $members,
            'memberCount' => $memberCount,
            'invitation' => $invitation,
            'hasTeam' => $hasTeam,
            'isTeamFull' => $isTeamFull,
            'currentPlayer' => $player
        ]);

        return view('team_invitation_preview', $data);
    }

    public function acceptInvitation($invitationId)
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour rejoindre une équipe');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a déjà une équipe
        if ($player['team_id'] && $player['team_id'] !== 'default_team') {
            return redirect()->to('/team/join/' . $invitationId)->with('error', 'Vous êtes déjà membre d\'une équipe');
        }

        // Vérifier si le joueur est membre du serveur Discord
        if (!$this->discordService->isUserInGuild($player['discord_id'])) {
            return $this->showDiscordRequired('rejoindre une équipe');
        }

        // Vérifier l'invitation
        $invitation = $this->invitationModel->getValidInvitation($invitationId);
        if (!$invitation) {
            return redirect()->to('/dashboard')->with('error', 'Invitation invalide ou expirée');
        }

        // Vérifier si l'équipe existe
        $team = $this->teamModel->find($invitation['team_id']);
        if (!$team) {
            return redirect()->to('/dashboard')->with('error', 'Équipe introuvable');
        }

        // Vérifier si l'équipe n'est pas pleine (max 5 joueurs)
        $memberCount = $this->playerModel->where('team_id', $invitation['team_id'])->countAllResults();
        if ($memberCount >= 5) {
            return redirect()->to('/team/join/' . $invitationId)->with('error', 'Cette équipe est complète (5/5 joueurs)');
        }

        try {
            // Ajouter le joueur à l'équipe
            $this->playerModel->update($playerId, [
                'team_id' => $invitation['team_id'],
                'is_leader' => false
            ]);

            // Incrémenter l'utilisation de l'invitation
            $this->invitationModel->incrementUse($invitationId);

            log_message('info', 'Player ' . $playerId . ' joined team via invitation: ' . $invitationId);

            return redirect()->to('/dashboard')->with('success', 'Vous avez rejoint l\'équipe "' . $team['name'] . '" avec succès !');

        } catch (\Exception $e) {
            log_message('error', 'Team join via invitation error: ' . $e->getMessage());
            return redirect()->to('/team/join/' . $invitationId)->with('error', 'Erreur lors de l\'adhésion à l\'équipe');
        }
    }

    public function leave()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a une équipe
        if (!$player['team_id']) {
            return redirect()->to('/dashboard')->with('error', 'Vous n\'êtes membre d\'aucune équipe');
        }

        try {
            // Si le joueur est leader, vérifier s'il y a d'autres membres
            if ($player['is_leader']) {
                $memberCount = $this->playerModel->where('team_id', $player['team_id'])->countAllResults();
                
                if ($memberCount > 1) {
                    // Transférer le leadership au premier membre trouvé
                    $otherMember = $this->playerModel->where('team_id', $player['team_id'])
                                                    ->where('discord_id !=', $playerId)
                                                    ->first();
                    
                    if ($otherMember) {
                        $this->playerModel->update($otherMember['discord_id'], ['is_leader' => true]);
                        log_message('info', 'Leadership transferred from ' . $playerId . ' to ' . $otherMember['discord_id']);
                    }
                }
            }

            // Retirer le joueur de l'équipe
            $this->playerModel->update($playerId, [
                'team_id' => null,
                'is_leader' => false
            ]);

            log_message('info', 'Player ' . $playerId . ' left team: ' . $player['team_id']);

            return redirect()->to('/dashboard')->with('success', 'Vous avez quitté votre équipe avec succès');

        } catch (\Exception $e) {
            log_message('error', 'Team leave error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la sortie de l\'équipe');
        }
    }

    public function showDelete()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->getPlayerWithTeam($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a une équipe
        if (!$player['team_id']) {
            return redirect()->to('/dashboard')->with('error', 'Vous n\'êtes membre d\'aucune équipe');
        }

        // Vérifier si le joueur est leader
        if (!$player['is_leader']) {
            return redirect()->to('/dashboard')->with('error', 'Seul le leader peut supprimer l\'équipe');
        }

        // Récupérer les membres de l'équipe
        $members = $this->playerModel->where('team_id', $player['team_id'])->findAll();

        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Supprimer l\'équipe - Flawless Cup',
            'team' => $player,
            'members' => $members
        ]);

        return view('team_delete', $data);
    }

    public function delete()
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a une équipe
        if (!$player['team_id']) {
            return redirect()->to('/dashboard')->with('error', 'Vous n\'êtes membre d\'aucune équipe');
        }

        // Vérifier si le joueur est leader
        if (!$player['is_leader']) {
            return redirect()->to('/dashboard')->with('error', 'Seul le leader peut supprimer l\'équipe');
        }

        $teamId = $player['team_id'];
        $team = $this->teamModel->find($teamId);

        if (!$team) {
            return redirect()->to('/dashboard')->with('error', 'Équipe introuvable');
        }

        // Vérifier la confirmation du nom d'équipe
        $confirmation = $this->request->getPost('confirmation');
        if ($confirmation !== $team['name']) {
            return redirect()->back()->with('error', 'Le nom saisi ne correspond pas au nom de l\'équipe');
        }

        try {
            // Retirer tous les joueurs de l'équipe
            $this->playerModel->where('team_id', $teamId)->set([
                'team_id' => null,
                'is_leader' => false
            ])->update();

            // Supprimer l'équipe
            $this->teamModel->delete($teamId);

            log_message('info', 'Team deleted: ' . $teamId . ' by player: ' . $playerId);

            return redirect()->to('/dashboard')->with('success', 'Équipe "' . $team['name'] . '" supprimée avec succès');

        } catch (\Exception $e) {
            log_message('error', 'Team deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'équipe');
        }
    }

    public function showTeam($teamId)
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour voir une équipe');
        }

        $playerId = session()->get('player_id');
        $currentPlayer = $this->playerModel->find($playerId);
        
        if (!$currentPlayer) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Récupérer les informations de l'équipe
        $team = $this->teamModel->find($teamId);
        if (!$team) {
            return redirect()->to('/dashboard')->with('error', 'Équipe introuvable');
        }

        // Récupérer les membres de l'équipe
        $members = $this->playerModel->getPlayersByTeam($teamId);
        $memberCount = count($members);

        // Vérifier si le joueur est déjà dans une équipe
        $hasTeam = $currentPlayer['team_id'] && $currentPlayer['team_id'] !== 'default_team';
        $isInThisTeam = $currentPlayer['team_id'] === $teamId;

        // Vérifier si l'équipe est pleine
        $isTeamFull = $memberCount >= 5;

        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Équipe ' . $team['name'] . ' - Flawless Cup',
            'team' => $team,
            'members' => $members,
            'memberCount' => $memberCount,
            'hasTeam' => $hasTeam,
            'isInThisTeam' => $isInThisTeam,
            'isTeamFull' => $isTeamFull,
            'currentPlayer' => $currentPlayer
        ]);

        return view('team_join', $data);
    }

    public function joinTeam($teamId)
    {
        if (!session()->has('player_id')) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour rejoindre une équipe');
        }

        $playerId = session()->get('player_id');
        $player = $this->playerModel->find($playerId);
        
        if (!$player) {
            return redirect()->to('/')->with('error', 'Profil joueur introuvable');
        }

        // Vérifier si le joueur a déjà une équipe
        if ($player['team_id'] && $player['team_id'] !== 'default_team') {
            return redirect()->to('/team/show/' . $teamId)->with('error', 'Vous êtes déjà membre d\'une équipe');
        }

        // Vérifier si le joueur est membre du serveur Discord
        if (!$this->discordService->isUserInGuild($player['discord_id'])) {
            return $this->showDiscordRequired('rejoindre une équipe');
        }

        // Vérifier si l'équipe existe
        $team = $this->teamModel->find($teamId);
        if (!$team) {
            return redirect()->to('/dashboard')->with('error', 'Équipe introuvable');
        }

        // Vérifier si l'équipe n'est pas pleine (max 5 joueurs)
        $memberCount = $this->playerModel->where('team_id', $teamId)->countAllResults();
        if ($memberCount >= 5) {
            return redirect()->to('/team/show/' . $teamId)->with('error', 'Cette équipe est complète (5/5 joueurs)');
        }

        try {
            // Ajouter le joueur à l'équipe
            $this->playerModel->update($playerId, [
                'team_id' => $teamId,
                'is_leader' => false
            ]);

            log_message('info', 'Player ' . $playerId . ' joined team: ' . $teamId);

            return redirect()->to('/dashboard')->with('success', 'Vous avez rejoint l\'équipe "' . $team['name'] . '" avec succès !');

        } catch (\Exception $e) {
            log_message('error', 'Team join error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'adhésion à l\'équipe');
        }
    }

    /**
     * Affiche la page d'exigence Discord avec instructions
     */
    private function showDiscordRequired($action)
    {
        $authData = getAuthData();
        $data = array_merge($authData, [
            'title' => 'Discord Requis - Flawless Cup',
            'action' => $action,
            'inviteUrl' => $this->discordService->getGuildInviteUrl()
        ]);

        return view('discord_required', $data);
    }

}