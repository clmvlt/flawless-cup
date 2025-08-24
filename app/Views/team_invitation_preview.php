<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="futuristic-card">
                <div class="text-center mb-4">
                    <h2 class="text-glow">
                        <i class="fas fa-envelope-open me-3 text-blue"></i>Invitation à rejoindre une équipe
                    </h2>
                    <p class="text-secondary">Vous avez été invité à rejoindre cette équipe</p>
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
                                <i class="fas fa-info-circle me-2"></i>Informations de l'équipe
                            </h5>
                            <div class="mb-3">
                                <strong class="text-primary">Nom :</strong>
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
                            </div>
                            <div class="mb-2">
                                <strong class="text-primary">Statut :</strong>
                                <?php if ($isTeamFull): ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-lock me-1"></i>Équipe complète
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-unlock me-1"></i>Places disponibles
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="futuristic-card bg-primary-subtle h-100">
                            <h5 class="text-blue mb-3">
                                <i class="fas fa-users me-2"></i>Membres actuels
                            </h5>
                            <?php if (!empty($members)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($members as $member): ?>
                                        <div class="list-group-item bg-transparent border-0 p-2">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= esc($member['avatar']) ?>" alt="Avatar" class="rounded-circle me-3" width="32" height="32">
                                                <div>
                                                    <div class="text-cyan fw-medium">
                                                        <?= esc($member['pseudo']) ?>
                                                        <?php if ($member['is_leader']): ?>
                                                            <span class="badge bg-warning text-dark ms-1">
                                                                <i class="fas fa-crown"></i> Leader
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if (isset($member['tier_id']) && $member['tier_id'] && isset($member['rr'])): ?>
                                                            <span class="badge bg-info d-flex align-items-center ms-2">
                                                                <img src="<?= getRankIcon($member['tier_id']) ?>" 
                                                                     alt="Rank" style="width: 12px; height: 12px;" class="me-1">
                                                                <?= esc($member['rr']) ?> RR
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <small class="text-secondary"><?= esc($member['tag']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucun membre trouvé</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Informations sur l'invitation -->
                <div class="futuristic-card bg-warning-subtle mb-4">
                    <h5 class="text-warning mb-3">
                        <i class="fas fa-clock me-2"></i>Détails de l'invitation
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-primary">Expire le :</strong>
                            <div class="text-secondary"><?= date('d/m/Y à H:i', strtotime($invitation['expires_at'])) ?></div>
                        </div>
                        <?php if ($invitation['max_uses']): ?>
                            <div class="col-md-6">
                                <strong class="text-primary">Utilisations :</strong>
                                <div class="text-secondary"><?= $invitation['current_uses'] ?>/<?= $invitation['max_uses'] ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-3 justify-content-center">
                    <?php if ($hasTeam): ?>
                        <div class="alert alert-warning-futuristic w-100 text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Vous êtes déjà membre d'une équipe. Vous devez la quitter avant de rejoindre cette équipe.
                        </div>
                    <?php elseif ($isTeamFull): ?>
                        <div class="alert alert-danger-futuristic w-100 text-center">
                            <i class="fas fa-lock me-2"></i>
                            Cette équipe est complète et n'accepte plus de nouveaux membres.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="/team/accept-invitation/<?= esc($invitation['invitation_id']) ?>" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary-futuristic btn-lg">
                                <i class="fas fa-check me-2"></i>Accepter l'invitation
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <a href="/dashboard" class="btn btn-secondary-futuristic btn-lg">
                        <i class="fas fa-times me-2"></i>Refuser
                    </a>
                </div>

                <?php if (!$hasTeam && !$isTeamFull): ?>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            En acceptant cette invitation, vous deviendrez membre de l'équipe "<?= esc($team['name']) ?>".
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>