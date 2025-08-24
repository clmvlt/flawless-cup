<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="futuristic-card">
                <div class="text-center mb-4">
                    <h2 class="text-glow">
                        <i class="fas fa-users me-3 text-blue"></i><?= esc($team['name']) ?>
                    </h2>
                    <p class="text-secondary">Informations de l'équipe</p>
                </div>

                <!-- Messages d'erreur et de succès -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger-futuristic" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success-futuristic" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <!-- Informations de l'équipe -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="futuristic-card bg-info-subtle h-100">
                            <h5 class="text-blue mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informations
                            </h5>
                            <div class="mb-3">
                                <strong class="text-primary">Nom de l'équipe :</strong>
                                <div class="text-cyan fs-5"><?= esc($team['name']) ?></div>
                            </div>
                            <?php if ($team['description']): ?>
                                <div class="mb-3">
                                    <strong class="text-primary">Description :</strong>
                                    <div class="text-secondary"><?= esc($team['description']) ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="mb-2">
                                <strong class="text-primary">Membres :</strong>
                                <span class="badge badge-futuristic">
                                    <i class="fas fa-users me-1"></i><?= $memberCount ?>/5
                                </span>
                                <?php if ($isTeamFull): ?>
                                    <span class="badge bg-danger ms-2">
                                        <i class="fas fa-ban me-1"></i>Équipe complète
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="futuristic-card bg-warning-subtle h-100">
                            <h5 class="text-blue mb-3">
                                <i class="fas fa-shield-alt me-2"></i>Statut
                            </h5>
                            <?php if ($isInThisTeam): ?>
                                <div class="alert alert-success-futuristic">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Vous êtes déjà membre de cette équipe</strong>
                                </div>
                            <?php elseif ($hasTeam): ?>
                                <div class="alert alert-warning-futuristic">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Vous êtes déjà dans une équipe</strong>
                                    <p class="mb-0 mt-2">Vous devez quitter votre équipe actuelle avant de pouvoir rejoindre celle-ci.</p>
                                </div>
                            <?php elseif ($isTeamFull): ?>
                                <div class="alert alert-danger-futuristic">
                                    <i class="fas fa-ban me-2"></i>
                                    <strong>Équipe complète</strong>
                                    <p class="mb-0 mt-2">Cette équipe a déjà atteint la limite de 5 membres.</p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success-futuristic">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Vous pouvez rejoindre cette équipe</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Liste des membres -->
                <div class="futuristic-card mb-4">
                    <h5 class="text-blue mb-3">
                        <i class="fas fa-users me-2"></i>Membres de l'équipe (<?= $memberCount ?>/5)
                    </h5>
                    
                    <?php if (!empty($members)): ?>
                        <div class="row g-3">
                            <?php foreach ($members as $member): ?>
                                <div class="col-md-6">
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
                            <i class="fas fa-user-slash text-secondary mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                            <h5 class="text-secondary">Aucun membre trouvé</h5>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-3">
                    <?php if ($isInThisTeam): ?>
                        <a href="/dashboard" class="btn btn-futuristic btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>Retour au Dashboard
                        </a>
                    <?php elseif (!$hasTeam && !$isTeamFull): ?>
                        <form action="/team/join-team/<?= esc($team['team_id']) ?>" method="post" id="joinTeamForm">
                            <button type="submit" class="btn btn-futuristic btn-lg w-100" id="joinBtn">
                                <i class="fas fa-user-plus me-2"></i>Rejoindre l'équipe
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <a href="/dashboard" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                    </a>
                </div>

                <?php if (!$hasTeam && !$isTeamFull && !$isInThisTeam): ?>
                    <div class="mt-4 text-center">
                        <small class="text-secondary">
                            <i class="fas fa-info-circle me-1"></i>
                            En rejoignant cette équipe, vous pourrez participer aux tournois ensemble.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
<?php if (!$hasTeam && !$isTeamFull && !$isInThisTeam): ?>
document.getElementById('joinTeamForm').addEventListener('submit', function(e) {
    const confirmation = confirm('Êtes-vous sûr de vouloir rejoindre l\'équipe "<?= esc($team['name']) ?>" ?');
    if (!confirmation) {
        e.preventDefault();
        return false;
    }
    
    const joinBtn = document.getElementById('joinBtn');
    joinBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adhésion en cours...';
    joinBtn.disabled = true;
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>