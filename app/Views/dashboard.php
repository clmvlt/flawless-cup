<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <!-- Section d'accueil -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card text-center">
                <h1 class="hero-title mb-3" style="font-size: 2.5rem;">
                    <i class="fas fa-tachometer-alt me-3 text-glow"></i>
                    Dashboard
                </h1>
                <p class="hero-subtitle mb-0">
                    Bienvenue, <span class="text-cyan fw-bold"><?= esc($player['pseudo']) ?></span> !
                </p>
            </div>
        </div>
    </div>

    <!-- Section profil utilisateur -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-user-circle me-3 text-blue" style="font-size: 2rem;"></i>
                    <h3 class="text-glow mb-0">Profil Discord</h3>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-dark rounded-3 border border-primary border-opacity-25">
                            <?php if ($player['avatar']): ?>
                                <img src="<?= esc($player['avatar']) ?>" alt="Avatar" class="rounded-circle me-3" 
                                     style="width: 48px; height: 48px; border: 2px solid var(--primary-blue);">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 48px; height: 48px; border: 2px solid var(--primary-blue);">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h5 class="text-primary mb-1"><?= esc($player['pseudo']) ?></h5>
                                <small class="text-secondary"><?= esc($player['tag']) ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-3 bg-dark rounded-3 border border-primary border-opacity-25">
                            <div class="d-flex align-items-center">
                                <i class="fab fa-discord me-2 text-primary" style="font-size: 1.5rem;"></i>
                                <div>
                                    <small class="text-secondary d-block">Discord ID</small>
                                    <code class="text-cyan"><?= esc($player['discord_id']) ?></code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section équipe et rang -->
    <div class="row mb-4">
        <!-- Équipe -->
        <div class="col-lg-6 mb-4">
            <div class="futuristic-card h-100">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-users me-3 text-blue" style="font-size: 2rem;"></i>
                    <h3 class="text-glow mb-0">Équipe</h3>
                </div>
                
                <div class="text-center">
                    <?php if ($player['team_id'] && $player['team_id'] !== 'default_team'): ?>
                        <div class="mb-3">
                            <h4 class="text-cyan"><?= esc($player['team_name']) ?></h4>
                            <?php if ($player['is_leader']): ?>
                                <span class="badge-futuristic">
                                    <i class="fas fa-crown me-1"></i>Leader d'équipe
                                </span>
                            <?php else: ?>
                                <span class="badge-futuristic">
                                    <i class="fas fa-user me-1"></i>Membre
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash text-secondary mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                            <h5 class="text-secondary">Aucune équipe</h5>
                            <p class="text-muted">Vous n'êtes actuellement dans aucune équipe</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Rang Valorant -->
        <div class="col-lg-6 mb-4">
            <div class="futuristic-card h-100">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-medal me-3 text-blue" style="font-size: 2rem;"></i>
                    <h3 class="text-glow mb-0">Rang Valorant</h3>
                </div>
                
                <div class="text-center">
                    <?php if (isset($player['riot_id']) && $player['riot_id']): ?>
                        <div class="mb-3">
                            <h5 class="text-primary mb-2"><?= esc($player['riot_id']) ?></h5>
                            <?php if (isset($player['mmr']) && $player['mmr']): ?>
                                <?php if (isset($rank_info) && $rank_info): ?>
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <img src="<?= esc($rank_info['icon']) ?>" alt="<?= esc($rank_info['name']) ?>" 
                                             class="me-3" style="width: 48px; height: 48px;">
                                        <div class="text-start">
                                            <strong style="color: <?= esc($rank_info['color']) ?>; font-size: 1.2rem;">
                                                <?= esc($rank_info['name']) ?>
                                            </strong>
                                            <div>
                                                <span class="badge bg-light text-dark"><?= esc($rank_info['rr']) ?> RR</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-cyan">Rang en cours de récupération</p>
                                <?php endif; ?>
                                <small class="text-secondary">Mis à jour automatiquement</small>
                            <?php else: ?>
                                <small class="text-secondary">En attente de synchronisation</small>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-gamepad text-secondary mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                            <h5 class="text-secondary">Profil non configuré</h5>
                            <p class="text-muted">Configurez votre Riot ID pour voir votre rang</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <?php if (!$player['team_id'] || $player['team_id'] === 'default_team'): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-triangle me-3 text-warning" style="font-size: 2rem;"></i>
                    <h3 class="text-glow mb-0">Action requise</h3>
                </div>
                <div class="alert alert-warning-futuristic mb-3">
                    <h5 class="mb-2">Vous n'avez pas encore d'équipe</h5>
                    <p class="mb-0">Créez votre propre équipe pour participer au tournoi ou rejoignez une équipe existante.</p>
                </div>
                <div class="text-center">
                    <a href="/team/create" class="btn btn-futuristic">
                        <i class="fas fa-plus me-2"></i>Créer une équipe
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Configuration et actions -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="futuristic-card h-100">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-cog me-3 text-blue" style="font-size: 2rem;"></i>
                    <h3 class="text-glow mb-0">Configuration</h3>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-gamepad me-2"></i>Profil Valorant
                    </h5>
                    <?php if (isset($player['riot_id']) && $player['riot_id'] && $player['riot_id'] !== ''): ?>
                        <div class="alert alert-success-futuristic">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Profil configuré</strong>
                            <div class="mt-2">
                                <small class="text-secondary d-block">Riot ID: <?= esc($player['riot_id']) ?></small>
                                <small class="text-secondary">
                                    <i class="fas fa-lock me-1"></i>
                                    Ne peut pas être modifié pour des raisons de sécurité
                                </small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning-futuristic mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Profil non configuré</strong>
                            <p class="mb-0 mt-2">Configurez votre Riot ID pour participer aux tournois</p>
                        </div>
                        <a href="/riot/setup" class="btn btn-futuristic">
                            <i class="fas fa-plus me-2"></i>Configurer le profil Riot
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Section Gestion d'équipe pour les joueurs qui ont une équipe -->
        <?php if ($player['team_id'] && $player['team_id'] !== 'default_team'): ?>
        <div class="col-md-6 mb-4">
            <div class="futuristic-card h-100">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-tools me-3 text-blue" style="font-size: 2rem;"></i>
                    <h3 class="text-glow mb-0">Gestion d'équipe</h3>
                </div>
                
                <div class="mb-4">
                    <div class="alert alert-info-futuristic">
                        <strong><?= esc($player['team_name']) ?></strong>
                        <br>
                        <small>
                            <?php if ($player['is_leader']): ?>
                                <i class="fas fa-crown text-warning me-1"></i>Vous êtes le leader
                            <?php else: ?>
                                <i class="fas fa-user text-primary me-1"></i>Vous êtes membre
                            <?php endif; ?>
                        </small>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <?php if ($player['is_leader']): ?>
                        <a href="/team/invitations" class="btn btn-futuristic">
                            <i class="fas fa-user-plus me-2"></i>Gérer les invitations
                        </a>
                        <a href="/team/delete" class="btn btn-danger-futuristic" 
                           onclick="return confirm('⚠️ ATTENTION : Cette action supprimera définitivement l\'équipe et tous ses membres seront retirés. Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?')">
                            <i class="fas fa-trash me-2"></i>Supprimer l'équipe
                        </a>
                    <?php else: ?>
                        <a href="/team/leave" class="btn btn-warning-futuristic"
                           onclick="return confirm('Êtes-vous sûr de vouloir quitter cette équipe ?')">
                            <i class="fas fa-sign-out-alt me-2"></i>Quitter l'équipe
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Membres de l'équipe (si dans une équipe) -->
    <?php if ($player['team_id'] && $player['team_id'] !== 'default_team'): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users me-3 text-blue" style="font-size: 2rem;"></i>
                        <h3 class="text-glow mb-0">Membres de l'équipe</h3>
                    </div>
                    <?php if (isset($team_members) && !empty($team_members)): ?>
                        <span class="badge badge-futuristic">
                            <i class="fas fa-users me-1"></i>
                            <?= count($team_members) ?>/5 membres
                        </span>
                    <?php endif; ?>
                </div>
                
                <?php if (isset($team_members) && !empty($team_members)): ?>
                    <div class="row g-3">
                        <?php foreach ($team_members as $member): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-dark rounded-3 border border-primary border-opacity-25">
                                    <div class="d-flex align-items-center mb-2">
                                        <?php if (isset($member['avatar']) && $member['avatar']): ?>
                                            <img src="<?= esc($member['avatar']) ?>" alt="Avatar" class="rounded-circle me-3" 
                                                 style="width: 40px; height: 40px; border: 2px solid var(--primary-blue);">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px; border: 2px solid var(--primary-blue);">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <h6 class="text-primary mb-0"><?= esc($member['pseudo']) ?></h6>
                                            <?php if ($member['riot_id']): ?>
                                                <small class="text-secondary"><?= esc($member['riot_id']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php if ($member['is_leader']): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-crown me-1"></i>Leader
                                            </span>
                                        <?php endif; ?>
                                        <?php if (isset($member['tier_id']) && $member['tier_id'] && isset($member['rr'])): ?>
                                            <span class="badge bg-info d-flex align-items-center">
                                                <img src="<?= getRankIcon($member['tier_id']) ?>" 
                                                     alt="Rank" style="width: 16px; height: 16px;" class="me-1">
                                                <?= esc($member['rr']) ?> RR
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                        <h5 class="text-secondary">Impossible de charger les membres</h5>
                        <p class="text-muted">Une erreur est survenue lors du chargement des membres de l'équipe</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>