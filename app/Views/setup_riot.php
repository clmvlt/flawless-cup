<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="futuristic-card">
                <div class="card-header-futuristic">
                    <h2 class="card-title-futuristic">
                        <i class="fas fa-gamepad me-3"></i>Configuration Valorant
                    </h2>
                    <p class="card-subtitle-futuristic">Configurez votre profil Valorant pour participer au tournoi</p>
                </div>
                <div class="card-body-futuristic">
                    <div class="info-banner">
                        <i class="fas fa-info-circle me-3"></i>
                        <div>
                            <strong>Étape obligatoire</strong>
                            <p>Nous devons vérifier votre identité Valorant pour vous inscrire au tournoi.</p>
                        </div>
                    </div>

                    <!-- Messages d'erreur et de succès -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="error-banner">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            <span><?= esc(session()->getFlashdata('error')) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="success-banner">
                            <i class="fas fa-check-circle me-3"></i>
                            <span><?= esc(session()->getFlashdata('success')) ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="/riot/setup" method="post" id="riotSetupForm" class="futuristic-form">
                        <div class="form-group-futuristic">
                            <label for="player_name" class="form-label-futuristic">
                                <i class="fas fa-user me-2"></i>Nom du joueur Valorant
                            </label>
                            <input type="text" class="form-control-futuristic" id="player_name" name="player_name" 
                                   placeholder="Votre nom Valorant" required>
                            <div class="form-hint">
                                <i class="fas fa-lightbulb me-2"></i>
                                Votre nom Valorant exact (avec espaces si applicable)
                            </div>
                        </div>
                        
                        <div class="form-group-futuristic">
                            <label for="tag" class="form-label-futuristic">
                                <i class="fas fa-hashtag me-2"></i>Tag Valorant
                            </label>
                            <input type="text" class="form-control-futuristic" id="tag" name="tag" 
                                   placeholder="1155" required maxlength="5">
                            <div class="form-hint">
                                <i class="fas fa-lightbulb me-2"></i>
                                Votre tag Valorant (3-5 caractères alphanumériques)
                            </div>
                        </div>

                        <div class="help-section">
                            <h6 class="help-title">
                                <i class="fas fa-question-circle me-2"></i>Comment trouver mon Riot ID ?
                            </h6>
                            <p class="help-text">
                                Dans Valorant, votre Riot ID s'affiche en haut à droite de l'écran principal.
                                Format : <span class="code-highlight">NomJoueur#TAG</span>
                            </p>
                        </div>

                        <button type="submit" class="btn-futuristic-primary w-100" id="submitBtn">
                            <i class="fas fa-search me-2"></i>Vérifier et configurer le profil
                        </button>
                    </form>

                    <div class="security-note">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>Vos données Valorant sont utilisées uniquement pour le tournoi et ne sont pas partagées.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour le container futuriste */
.futuristic-card {
    background: var(--bg-card);
    border: 1px solid rgba(0, 201, 255, 0.3);
    border-radius: 16px;
    backdrop-filter: blur(15px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    position: relative;
}

.futuristic-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0, 201, 255, 0.8), transparent);
}

.card-header-futuristic {
    background: linear-gradient(135deg, rgba(0, 201, 255, 0.1), rgba(0, 150, 255, 0.05));
    border-bottom: 1px solid rgba(0, 201, 255, 0.2);
    padding: 2rem;
    text-align: center;
}

.card-title-futuristic {
    color: var(--text-primary);
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 0 10px rgba(0, 201, 255, 0.3);
}

.card-subtitle-futuristic {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
}

.card-body-futuristic {
    padding: 2rem;
}

/* Banners d'information */
.info-banner {
    background: rgba(0, 201, 255, 0.1);
    border: 1px solid rgba(0, 201, 255, 0.3);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    color: var(--text-primary);
}

.info-banner i {
    color: #00c9ff;
    font-size: 1.2rem;
    margin-top: 0.1rem;
}

.info-banner strong {
    color: #00c9ff;
}

.info-banner p {
    margin: 0.3rem 0 0 0;
    color: var(--text-secondary);
}

.error-banner {
    background: rgba(255, 50, 50, 0.1);
    border: 1px solid rgba(255, 50, 50, 0.3);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    color: #ff5555;
}

.success-banner {
    background: rgba(0, 255, 127, 0.1);
    border: 1px solid rgba(0, 255, 127, 0.3);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    color: #00ff7f;
}

/* Formulaire futuriste */
.futuristic-form {
    margin-top: 1rem;
}

.form-group-futuristic {
    margin-bottom: 2rem;
}

.form-label-futuristic {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.8rem;
    font-size: 1rem;
}

.form-label-futuristic i {
    color: #00c9ff;
}

.form-control-futuristic {
    width: 100%;
    padding: 1rem 1.25rem;
    background: rgba(15, 15, 25, 0.8);
    border: 2px solid rgba(0, 201, 255, 0.2);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.form-control-futuristic:focus {
    outline: none;
    border-color: #00c9ff;
    box-shadow: 0 0 20px rgba(0, 201, 255, 0.2);
    background: rgba(0, 201, 255, 0.05);
}

.form-control-futuristic::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.form-hint {
    margin-top: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
}

.form-hint i {
    color: #00c9ff;
    opacity: 0.7;
}

/* Section d'aide */
.help-section {
    background: rgba(0, 201, 255, 0.05);
    border: 1px solid rgba(0, 201, 255, 0.15);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.help-title {
    color: #00c9ff;
    font-weight: 600;
    margin-bottom: 0.8rem;
    display: flex;
    align-items: center;
}

.help-text {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.6;
}

.code-highlight {
    background: rgba(0, 201, 255, 0.2);
    color: #00c9ff;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
}

/* Bouton futuriste */
.btn-futuristic-primary {
    background: linear-gradient(135deg, #00c9ff, #0099cc);
    border: none;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 1rem 2rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-futuristic-primary:hover {
    background: linear-gradient(135deg, #00b5e6, #0088bb);
    box-shadow: 0 5px 20px rgba(0, 201, 255, 0.4);
    transform: translateY(-2px);
}

.btn-futuristic-primary:active {
    transform: translateY(0);
}

.btn-futuristic-primary:disabled {
    background: linear-gradient(135deg, #555, #666);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Note de sécurité */
.security-note {
    margin-top: 2rem;
    padding: 1rem;
    background: rgba(0, 201, 255, 0.05);
    border-radius: 8px;
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.security-note i {
    color: #00c9ff;
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header-futuristic {
        padding: 1.5rem;
    }
    
    .card-body-futuristic {
        padding: 1.5rem;
    }
    
    .card-title-futuristic {
        font-size: 1.5rem;
    }
    
    .form-control-futuristic {
        padding: 0.9rem 1rem;
        font-size: 1rem;
    }
}
</style>

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