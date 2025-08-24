<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="futuristic-card text-center">
                <div class="mb-4">
                    <i class="fab fa-discord" style="font-size: 4rem; color: #5865F2;"></i>
                </div>
                
                <h1 class="text-glow mb-4">
                    Discord Requis
                </h1>
                
                <div class="alert alert-warning-futuristic mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Accès Restreint :</strong> Vous devez être membre de notre serveur Discord pour <?= $action ?? 'participer' ?>.
                </div>

                <div class="mb-4">
                    <h3 class="text-cyan mb-3">Pourquoi Discord est-il obligatoire ?</h3>
                    <div class="text-start">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Communication :</strong> Coordination avec votre équipe et les organisateurs
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Annonces :</strong> Informations importantes sur le tournoi en temps réel
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Support :</strong> Aide technique et réponses à vos questions
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Matchmaking :</strong> Organisation des matchs et planification
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mb-4">
                    <h4 class="mb-3">Étapes pour rejoindre :</h4>
                    <div class="row text-start">
                        <div class="col-md-6 mb-3">
                            <div class="step-card">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h5>Rejoindre le Discord</h5>
                                    <p>Cliquez sur le bouton ci-dessous pour rejoindre notre serveur</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="step-card">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h5>Revenir sur le site</h5>
                                    <p>Une fois membre, revenez ici et rechargez la page</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= $inviteUrl ?>" target="_blank" class="btn btn-discord btn-lg">
                        <i class="fab fa-discord me-2"></i>Rejoindre le Discord Flawless Cup
                    </a>
                    <button onclick="window.location.reload()" class="btn btn-futuristic btn-lg">
                        <i class="fas fa-sync-alt me-2"></i>Vérifier l'adhésion
                    </button>
                </div>

                <div class="mt-4">
                    <small class="text-secondary">
                        <i class="fas fa-info-circle me-1"></i>
                        Après avoir rejoint le Discord, il peut y avoir un délai de quelques minutes avant que votre adhésion soit détectée.
                    </small>
                </div>

                <div class="mt-3">
                    <a href="/dashboard" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-card {
    background: rgba(0, 201, 255, 0.1);
    border: 1px solid rgba(0, 201, 255, 0.3);
    border-radius: 12px;
    padding: 1rem;
    height: 100%;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-number {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.step-content h5 {
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.step-content p {
    margin-bottom: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}
</style>

<?= $this->endSection() ?>