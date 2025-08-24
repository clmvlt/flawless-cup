<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-5">
    <div class="row g-4 justify-content-center">
        <!-- Section principale du tournoi -->
        <div class="col-xl-6 col-lg-6">
            <section class="hero-futuristic h-100">
                <div class="futuristic-card h-100 d-flex flex-column">
                    <div class="text-center mb-4">
                        <h1 class="hero-title dream-mma-font">
                            flawless cup
                        </h1>
                        <p class="hero-subtitle valorant-font">Valorant Tournament</p>
                    </div>
                    
                    <!-- Statistiques du tournoi -->
                    <div class="row g-3 mb-4 flex-grow-1">
                        <div class="col-12">
                            <div class="tournament-stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number" id="teamCountDisplay"><?= $team_count ?><span class="text-secondary">/8</span></h3>
                                    <p class="stat-label">Équipes inscrites</p>
                                    <small id="teamCountStatus">
                                        <?php if ($team_count < 4): ?>
                                            <span class="text-warning">Recherche d'équipes</span>
                                        <?php elseif ($team_count < 8): ?>
                                            <span class="text-info">Plus que <?= 8 - $team_count ?> places !</span>
                                        <?php else: ?>
                                            <span class="text-success">Tournoi complet !</span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="tournament-stat-card">
                                <div class="stat-icon">
                                    <i class="fab fa-discord"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-label">Discord requis</h4>
                                    <a href="https://discord.gg/mkA7tHejGN" target="_blank" class="btn btn-discord-small">
                                        <i class="fab fa-discord me-1"></i>Rejoindre
                                    </a>
                                    <small class="text-secondary d-block mt-1">Obligatoire pour participer</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="tournament-stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-label">Format tournoi</h4>
                                    <p class="stat-description">
                                        <strong class="text-cyan">Phase de poules</strong>
                                        <br><small>2 poules de 4 équipes</small>
                                        <br><small class="text-warning">Top 2 → Élimination directe</small>
                                    </p>
                                </div>
                            </div>
                        </div>
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
                    
                    <div class="mt-auto">
                        <?php if (!$isLoggedIn): ?>
                            <div class="mb-3">
                                <a href="https://discord.com/oauth2/authorize?client_id=1405941959792791602&response_type=code&redirect_uri=https%3A%2F%2Fflawless-cup.fr%2Fregister&scope=identify" class="btn btn-futuristic">
                                    <i class="fab fa-discord me-2"></i>Se connecter avec Discord
                                </a>
                            </div>
                            
                            <div class="description">
                                <p>Connectez-vous avec Discord pour participer au tournoi Flawless Cup et rejoindre votre équipe !</p>
                                <div class="alert alert-info-futuristic mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Étapes pour participer :</strong>
                                    <ol class="mt-2 mb-0">
                                        <li>Rejoindre le Discord obligatoire</li>
                                        <li>Se connecter sur le site</li>
                                        <li>Créer ou rejoindre une équipe</li>
                                        <li>Configurer son profil Riot</li>
                                    </ol>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-futuristic" role="alert">
                                <i class="fas fa-info-circle me-2"></i>Bienvenue, <strong class="text-cyan"><?= esc($player['pseudo']) ?></strong> ! Vous êtes connecté.
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-3">
                                <a href="/dashboard" class="btn btn-futuristic">
                                    <i class="fas fa-tachometer-alt me-2"></i>Accéder au Dashboard
                                </a>
                                <a href="https://discord.gg/mkA7tHejGN" target="_blank" class="btn btn-discord">
                                    <i class="fab fa-discord me-2"></i>Rejoindre le Discord
                                </a>
                            </div>
                            
                            <div class="description">
                                <p>Consultez votre profil, gérez votre équipe et suivez vos matchs depuis votre dashboard !</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>

        <!-- Section Twitch Stream -->
        <div class="col-xl-6 col-lg-6">
            <section>
                <div class="futuristic-card">
                    <div class="text-center mb-3">
                        <h2 id="streamTitle" class="text-glow">
                            <i class="fab fa-twitch me-3 text-blue"></i>
                            <span id="streamTitleText">Stream Live</span>
                            <i id="liveIndicator" class="fas fa-circle ms-2" style="display: none; font-size: 0.5em; transition: color 0.8s ease; color: red;"></i>
                        </h2>
                        <div id="streamStatusHome" class="stream-status mb-3">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Vérification du statut...
                        </div>
                    </div>
                    
                    <div class="twitch-container mb-3" style="width: 100%; margin: 0 auto; border: none !important; background: transparent !important;">
                        <div class="d-flex gap-3 align-items-center">
                            <!-- Player Twitch -->
                            <div style="width: 68%;">
                                <div style="position: relative; width: 100%; padding-bottom: 56.25%; border-radius: 12px; overflow: hidden; /* Force 16:9 ratio */">
                                    <!-- Image offline affichée par défaut -->
                                    <img
                                        id="offlineImage"
                                        src="/assets/offline-stream.png"
                                        alt="Stream Offline"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 12px; border: none; z-index: 10;">
                                    
                                    <!-- Iframe Twitch cachée par défaut -->
                                    <iframe
                                        id="twitchEmbed"
                                        src="https://player.twitch.tv/?channel=urikaynee&parent=localhost&parent=flawless-cup.fr&parent=127.0.0.1&muted=false&autoplay=false&quality=auto"
                                        width="100%"
                                        height="100%"
                                        style="position: absolute; top: 0; left: 0; border-radius: 12px; border: 1px solid transparent !important; outline: none !important; display: none; z-index: 5; min-width: 400px; min-height: 300px;"
                                        frameborder="0"
                                        scrolling="no"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                            
                            <!-- Chat Twitch -->
                            <div style="width: 32%;">
                                <div style="height: 600px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(0, 201, 255, 0.2); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);">
                                    <iframe
                                        id="twitchChat"
                                        src="https://www.twitch.tv/embed/urikaynee/chat?parent=localhost&parent=flawless-cup.fr&parent=127.0.0.1&darkpopout"
                                        width="100%"
                                        height="100%"
                                        style="border-radius: 12px;"
                                        frameborder="0"
                                        scrolling="no">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="https://twitch.tv/urikaynee" target="_blank" class="btn btn-futuristic btn-sm">
                            <i class="fab fa-twitch me-2"></i>Voir sur Twitch
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Section Règles du Tournoi -->
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="futuristic-card">
                <div class="text-center mb-4">
                    <h2 class="text-glow">
                        <i class="fas fa-gavel me-3 text-cyan"></i>
                        Règles & Spécificités
                    </h2>
                    <p class="text-secondary">Informations essentielles pour participer au tournoi</p>
                </div>
                
                <div class="row g-4">
                    <!-- Format du Tournoi -->
                    <div class="col-md-6">
                        <div class="rule-card">
                            <div class="rule-header">
                                <i class="fas fa-trophy text-cyan me-2"></i>
                                <h4>Format</h4>
                            </div>
                            <div class="rule-content">
                                <ul class="rule-list">
                                    <li><strong>Phase de poules :</strong> 2 poules de 4 équipes</li>
                                    <li><strong>Élimination directe :</strong> Top 2 de chaque poule</li>
                                    <li><strong>Finale :</strong> BO3 (Best of 3)</li>
                                    <li><strong>Équipes :</strong> 5 joueurs + 1 remplaçant max</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modalités d'inscription -->
                    <div class="col-md-6">
                        <div class="rule-card">
                            <div class="rule-header">
                                <i class="fas fa-user-plus text-cyan me-2"></i>
                                <h4>Modalités d'inscription</h4>
                            </div>
                            <div class="rule-content">
                                <ul class="rule-list">
                                    <li><strong>Discord obligatoire :</strong> Rejoindre le serveur</li>
                                    <li><strong>Compte Riot :</strong> Configuration du profil requis</li>
                                    <li><strong>Équipe complète :</strong> 5 joueurs minimum</li>
                                    <li><strong>Deadline :</strong> Jusqu'à complétion (8 équipes)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cast Twitch -->
                    <div class="col-md-6">
                        <div class="rule-card">
                            <div class="rule-header">
                                <i class="fab fa-twitch text-purple me-2"></i>
                                <h4>Cast Twitch</h4>
                            </div>
                            <div class="rule-content">
                                <ul class="rule-list">
                                    <li><strong>Stream officiel :</strong> urikaynee sur Twitch</li>
                                    <li><strong>Tous les matchs :</strong> Diffusion en direct</li>
                                    <li><strong>Commentaires :</strong> Analysis et replay</li>
                                    <li><strong>Guest :</strong> le goat Dimzou</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cash Prize -->
                    <div class="col-md-6">
                        <div class="rule-card">
                            <div class="rule-header">
                                <i class="fas fa-euro-sign text-yellow me-2"></i>
                                <h4>Cash Prize</h4>
                            </div>
                            <div class="rule-content">
                                <ul class="rule-list">
                                    <li><strong>1ère place :</strong> 100€ à partager</li>
                                    <li><strong>Répartition :</strong> À décider par l'équipe</li>
                                    <li><strong>Paiement :</strong> Après validation finale</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Fair Play - Section pleine largeur -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="rule-card rule-card-special">
                            <div class="rule-header">
                                <i class="fas fa-handshake text-green me-2"></i>
                                <h4>Fair Play</h4>
                            </div>
                            <div class="rule-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="rule-list">
                                            <li><strong>Respect mutuel :</strong> Entre toutes les équipes</li>
                                            <li><strong>Communication :</strong> Langage approprié obligatoire</li>
                                            <li><strong>Anti-triche :</strong> Logiciels interdits, smurfs détectés = équipe disqualifiée</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="rule-list">
                                            <li><strong>Sanctions :</strong> Avertissement puis exclusion</li>
                                            <li><strong>Réclamations :</strong> Via Discord uniquement</li>
                                            <li><strong>Esprit sportif :</strong> Priorité absolue</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rule-card {
    background: rgba(15, 15, 25, 0.6);
    border: 1px solid rgba(0, 201, 255, 0.15);
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
}

.rule-card:hover {
    border-color: rgba(0, 201, 255, 0.4);
    box-shadow: 0 8px 25px rgba(0, 201, 255, 0.15);
    transform: translateY(-3px);
}

.rule-card-special {
    border-color: rgba(0, 255, 255, 0.2);
}

.rule-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(0, 201, 255, 0.1);
}

.rule-header h4 {
    margin: 0;
    font-size: 1.1rem;
    color: var(--text-primary);
}

.rule-content {
    color: var(--text-secondary);
}

.rule-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.rule-list li {
    padding: 0.4rem 0;
    position: relative;
    padding-left: 1.2rem;
}

.rule-list li:before {
    content: '▶';
    position: absolute;
    left: 0;
    color: var(--primary-blue);
    font-size: 0.8rem;
}

.rule-list li strong {
    color: var(--text-primary);
}

.text-purple {
    color: #9146ff !important;
}

.text-yellow {
    color: #ffd700 !important;
}

.text-green {
    color: #00ff88 !important;
}
</style>

<script>
// Fonction pour mettre à jour le statut du stream sur la page d'accueil
let isLiveAnimating = false;
let liveAnimation;

async function updateStreamStatusHome() {
    try {
        const response = await fetch('/twitch/status');
        const data = await response.json();
        
        // Debug: afficher la réponse dans la console
        console.log('Twitch API response:', data);
        console.log('Debug object:', data.debug);
        console.log('isLive status:', data.isLive);
        console.log('Raw response from API:', data.debug ? data.debug.raw_response : 'No debug data');
        
        const statusElement = document.getElementById('streamStatusHome');
        const streamTitleElement = document.getElementById('streamTitleText');
        const liveIndicator = document.getElementById('liveIndicator');
        const twitchEmbed = document.getElementById('twitchEmbed');
        const offlineImage = document.getElementById('offlineImage');
        
        if (data.isLive) {
            console.log('STREAM DETECTED - Showing Twitch iframe, hiding offline image');
            
            statusElement.innerHTML = `
                <span class="status-dot online"></span>
                <span class="status-online">En direct</span>
                ${data.viewers ? `<br><small>${data.viewers} spectateurs</small>` : ''}
            `;
            
            // LIVE : Masquer l'image offline et afficher l'iframe
            offlineImage.style.display = 'none';
            twitchEmbed.style.display = 'block';
            
            // Activer l'animation du titre et l'indicateur live
            if (!isLiveAnimating) {
                isLiveAnimating = true;
                liveIndicator.style.display = 'inline';
                
                // Animation seulement du point rouge
                liveAnimation = setInterval(() => {
                    if (liveIndicator.style.color === 'red') {
                        liveIndicator.style.color = 'white';
                    } else {
                        liveIndicator.style.color = 'red';
                    }
                }, 1500); // Change de couleur toutes les 1.5 secondes
            }
        } else {
            console.log('NO STREAM - Showing offline image, hiding iframe');
            
            statusElement.innerHTML = '<span class="status-dot offline"></span><span class="status-offline">Hors ligne</span>';
            
            // OFFLINE : Afficher l'image offline et masquer l'iframe
            offlineImage.style.display = 'block';
            twitchEmbed.style.display = 'none';
            
            // Désactiver l'animation
            if (isLiveAnimating) {
                isLiveAnimating = false;
                clearInterval(liveAnimation);
                liveIndicator.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Erreur lors de la vérification du statut Twitch:', error);
        console.log('ERROR - Defaulting to offline image');
        
        const statusElement = document.getElementById('streamStatusHome');
        const twitchEmbed = document.getElementById('twitchEmbed');
        const offlineImage = document.getElementById('offlineImage');
        
        statusElement.innerHTML = '<span class="status-dot offline"></span><span class="status-offline">Hors ligne</span>';
        
        // En cas d'erreur : toujours afficher l'image offline
        if (offlineImage) offlineImage.style.display = 'block';
        if (twitchEmbed) twitchEmbed.style.display = 'none';
        
        // Désactiver l'animation en cas d'erreur
        if (isLiveAnimating) {
            isLiveAnimating = false;
            clearInterval(liveAnimation);
            document.getElementById('liveIndicator').style.display = 'none';
        }
    }
}

// Fonction pour mettre à jour le compteur d'équipes
async function updateTeamCount() {
    try {
        const response = await fetch('/api/team-count');
        const data = await response.json();
        
        if (data.success) {
            const teamCount = data.team_count;
            const displayElement = document.getElementById('teamCountDisplay');
            const statusElement = document.getElementById('teamCountStatus');
            
            if (displayElement) {
                displayElement.innerHTML = `${teamCount}<span class="text-secondary">/8</span>`;
            }
            
            if (statusElement) {
                let statusText = '';
                if (teamCount < 4) {
                    statusText = '<span class="text-warning">Recherche d\'équipes</span>';
                } else if (teamCount < 8) {
                    statusText = `<span class="text-info">Plus que ${8 - teamCount} places !</span>`;
                } else {
                    statusText = '<span class="text-success">Tournoi complet !</span>';
                }
                statusElement.innerHTML = statusText;
            }
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du compteur d\'équipes:', error);
    }
}

// Vérifier le statut au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updateStreamStatusHome();
    updateTeamCount();
    
    // Vérifier plus fréquemment pour les tests (30 secondes)
    setInterval(updateStreamStatusHome, 30000);
    
    // Vérifier le compteur d'équipes toutes les 3 minutes (180000ms)
    setInterval(updateTeamCount, 180000);
    
    // Debug: afficher les réponses dans la console
    console.log('Stream status check initialized');
});
</script>

<?= $this->endSection() ?>