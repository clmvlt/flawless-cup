<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="futuristic-card">
                <div class="text-center mb-4">
                    <h2 class="text-glow">
                        <i class="fas fa-users me-3 text-blue"></i>Créer une équipe
                    </h2>
                    <p class="text-secondary">Formez votre équipe pour participer au tournoi Flawless Cup</p>
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

                <form action="/team/create" method="post" id="teamCreateForm">
                    <div class="mb-4">
                        <label for="team_name" class="form-label fw-bold text-blue">
                            <i class="fas fa-flag me-2"></i>Nom de l'équipe
                        </label>
                        <input type="text" class="form-control form-control-lg" id="team_name" name="team_name" 
                               placeholder="Les Flawless Legends" required maxlength="50">
                        <div class="form-text text-secondary">
                            <i class="fas fa-info-circle me-1"></i>
                            Entre 3 et 50 caractères. Ce nom sera visible par tous les participants.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold text-blue">
                            <i class="fas fa-edit me-2"></i>Description (optionnel)
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Décrivez votre équipe, votre style de jeu, vos objectifs..."></textarea>
                        <div class="form-text text-secondary">
                            <i class="fas fa-lightbulb me-1"></i>
                            Présentez votre équipe aux autres joueurs
                        </div>
                    </div>

                    <div class="futuristic-card mb-4" style="background-color: #36454F;">
                        <h6 class="text-blue mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informations importantes
                        </h6>
                        <ul class="list-unstyled mb-0 text-sm">
                            <li class="mb-2">
                                <i class="fas fa-crown text-warning me-2"></i>
                                Vous deviendrez automatiquement le <strong>leader</strong> de cette équipe
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-users text-primary me-2"></i>
                                Une équipe peut compter jusqu'à <strong>5 joueurs maximum</strong>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-gamepad text-success me-2"></i>
                                Tous les membres doivent avoir configuré leur <strong>profil Valorant</strong>
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-lock text-danger me-2"></i>
                                Le nom d'équipe <strong>ne peut pas être modifié</strong> après création
                            </li>
                        </ul>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-futuristic btn-lg" id="submitBtn">
                            <i class="fas fa-plus me-2"></i>Créer l'équipe
                        </button>
                        <a href="/dashboard" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                        </a>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <small class="text-secondary">
                        <i class="fas fa-shield-alt me-1"></i>
                        En créant une équipe, vous acceptez de respecter les règles du tournoi.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('teamCreateForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
    submitBtn.disabled = true;
});

// Validation en temps réel du nom d'équipe
document.getElementById('team_name').addEventListener('input', function(e) {
    const value = e.target.value.trim();
    const submitBtn = document.getElementById('submitBtn');
    
    if (value.length < 3) {
        e.target.setCustomValidity('Le nom doit contenir au moins 3 caractères');
        submitBtn.disabled = true;
    } else if (value.length > 50) {
        e.target.setCustomValidity('Le nom ne peut pas dépasser 50 caractères');
        submitBtn.disabled = true;
    } else {
        e.target.setCustomValidity('');
        submitBtn.disabled = false;
    }
});
</script>

<?= $this->endSection() ?>