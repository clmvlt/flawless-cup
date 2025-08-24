<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="card-title mb-0">
                        <i class="fas fa-gamepad me-2"></i>Configuration Valorant
                    </h2>
                    <p class="card-text mt-2 mb-0 opacity-75">Configurez votre profil Valorant pour participer au tournoi</p>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Étape obligatoire :</strong> Nous devons vérifier votre identité Valorant pour vous inscrire au tournoi.
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <form action="/riot/setup" method="post" id="riotSetupForm">
                        <div class="mb-4">
                            <label for="player_name" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Nom du joueur Valorant
                            </label>
                            <input type="text" class="form-control form-control-lg" id="player_name" name="player_name" 
                                   placeholder="M8 Dimz" required>
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Votre nom Valorant exact (avec espaces si applicable)
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="tag" class="form-label fw-bold">
                                <i class="fas fa-hashtag me-1"></i>Tag Valorant
                            </label>
                            <input type="text" class="form-control form-control-lg" id="tag" name="tag" 
                                   placeholder="1155" required maxlength="5">
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Votre tag Valorant (3-5 caractères alphanumériques)
                            </div>
                        </div>

                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-question-circle me-1"></i>Comment trouver mon Riot ID ?
                                </h6>
                                <p class="card-text small mb-0">
                                    Dans Valorant, votre Riot ID s'affiche en haut à droite de l'écran principal.
                                    Format : <code>NomJoueur#TAG</code>
                                </p>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-search me-2"></i>Vérifier et configurer le profil
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Vos données Valorant sont utilisées uniquement pour le tournoi et ne sont pas partagées.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('riotSetupForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Vérification en cours...';
    submitBtn.disabled = true;
});

// Validation en temps réel
document.getElementById('tag').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^a-zA-Z0-9]/g, '');
    if (value.length > 5) value = value.substring(0, 5);
    e.target.value = value;
});

document.getElementById('player_name').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^a-zA-Z0-9\s]/g, '');
    if (value.length > 16) value = value.substring(0, 16);
    e.target.value = value;
});
</script>

<?= $this->endSection() ?>