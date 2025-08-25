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
                            <div class="tournament-stat-card prize-container">
                                <div class="stat-icon prize-icon" id="prizeIcon">
                                    <div class="explosion-confetti" id="iconExplosion">
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                    </div>
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-label">Cash Prize</h4>
                                    <h3 class="prize-amount" id="prizeAmount">
                                        <div class="explosion-confetti" id="amountExplosion">
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                            <div class="confetti"></div>
                                        </div>
                                        <span id="prizeCounter">0</span>€
                                    </h3>
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

<!-- Section Matchs - Container dédié -->
<div class="container-fluid py-5 bg-matches-section">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="matches-container">
                <div class="text-center mb-4">
                    <h2 class="matches-title text-glow">
                        <i class="fas fa-swords me-3 text-cyan"></i>
                        Matchs du Tournoi
                    </h2>
                    <p class="text-secondary">Suivez les derniers résultats et les prochaines confrontations</p>
                </div>
                
                <div class="row g-4">
                    <!-- Matchs Récents -->
                    <div class="col-md-6">
                        <div class="matches-section-card">
                            <div class="section-header">
                                <h4>
                                    <i class="fas fa-history me-2 text-green"></i>
                                    Derniers Matchs
                                </h4>
                            </div>
                            
                            <div class="matches-content">
                                <?php if (!empty($recent_matches)): ?>
                                    <?php foreach ($recent_matches as $match): ?>
                                    <div class="match-card mb-3">
                                        <div class="match-header">
                                            <div class="match-teams">
                                                <span class="team-name <?= $match['winner'] === 'team_a' ? 'team-winner' : '' ?>">
                                                    <?= esc($match['team_a_name']) ?>
                                                </span>
                                                <span class="vs-text">VS</span>
                                                <span class="team-name <?= $match['winner'] === 'team_b' ? 'team-winner' : '' ?>">
                                                    <?= esc($match['team_b_name']) ?>
                                                </span>
                                            </div>
                                            <div class="match-date">
                                                <?= date('d/m H:i', strtotime($match['match_date'])) ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($match['games'])): ?>
                                        <div class="match-games">
                                            <?php foreach ($match['games'] as $game): ?>
                                                <?php if ($game['a_score'] !== null && $game['b_score'] !== null): ?>
                                                <div class="game-info-container">
                                                    <?php if (!empty($game['map'])): ?>
                                                        <div class="game-map">
                                                            <i class="fas fa-map me-1"></i><?= esc($game['map']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="game-score">
                                                        <span class="score <?= $game['a_score'] > $game['b_score'] ? 'score-winner' : '' ?>">
                                                            <?= $game['a_score'] ?>
                                                        </span>
                                                        <span class="score-separator">-</span>
                                                        <span class="score <?= $game['b_score'] > $game['a_score'] ? 'score-winner' : '' ?>">
                                                            <?= $game['b_score'] ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-matches-message">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Aucun match joué pour le moment
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prochains Matchs -->
                    <div class="col-md-6">
                        <div class="matches-section-card">
                            <div class="section-header">
                                <h4>
                                    <i class="fas fa-clock me-2 text-yellow"></i>
                                    Prochains Matchs
                                </h4>
                            </div>
                            
                            <div class="matches-content">
                                <?php if (!empty($upcoming_matches)): ?>
                                    <?php foreach ($upcoming_matches as $match): ?>
                                    <div class="match-card mb-3">
                                        <div class="match-header">
                                            <div class="match-teams">
                                                <span class="team-name">
                                                    <?= esc($match['team_a_name']) ?>
                                                </span>
                                                <span class="vs-text">VS</span>
                                                <span class="team-name">
                                                    <?= esc($match['team_b_name']) ?>
                                                </span>
                                            </div>
                                            <div class="match-date">
                                                <?= date('d/m H:i', strtotime($match['match_date'])) ?>
                                            </div>
                                        </div>
                                        
                                        <div class="match-type">
                                            <i class="fas fa-gamepad me-1"></i>
                                            <?= $match['is_tournament'] ? 'Tournoi (BO3)' : 'Poule' ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-matches-message">
                                        <i class="fas fa-calendar-plus me-2"></i>
                                        Aucun match programmé pour le moment
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
/* Prize Container with Effects */
.prize-container {
    position: relative;
    overflow: visible !important;
}

/* Explosion Confetti */
.explosion-confetti {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 10;
    opacity: 0;
}

.explosion-confetti.active {
    opacity: 1;
}

.confetti {
    position: absolute;
    width: 4px;
    height: 4px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 6px rgba(255, 255, 255, 0.6);
    will-change: transform, opacity;
}

/* Variations simplifiées pour performance */
.confetti:nth-child(odd) { background: rgba(255, 255, 255, 0.95); }
.confetti:nth-child(even) { background: rgba(248, 248, 248, 0.9); }

.confetti:nth-child(1) { animation-delay: 0s; }
.confetti:nth-child(2) { animation-delay: 0.03s; }
.confetti:nth-child(3) { animation-delay: 0.06s; }
.confetti:nth-child(4) { animation-delay: 0.09s; }
.confetti:nth-child(5) { animation-delay: 0.12s; }
.confetti:nth-child(6) { animation-delay: 0.15s; }
.confetti:nth-child(7) { animation-delay: 0.18s; }
.confetti:nth-child(8) { animation-delay: 0.21s; }
.confetti:nth-child(9) { animation-delay: 0.24s; }
.confetti:nth-child(10) { animation-delay: 0.27s; }
.confetti:nth-child(11) { animation-delay: 0.3s; }
.confetti:nth-child(12) { animation-delay: 0.33s; }
.confetti:nth-child(13) { animation-delay: 0.36s; }
.confetti:nth-child(14) { animation-delay: 0.39s; }
.confetti:nth-child(15) { animation-delay: 0.42s; }
.confetti:nth-child(16) { animation-delay: 0.45s; }
.confetti:nth-child(17) { animation-delay: 0.48s; }
.confetti:nth-child(18) { animation-delay: 0.51s; }
.confetti:nth-child(19) { animation-delay: 0.54s; }
.confetti:nth-child(20) { animation-delay: 0.57s; }
.confetti:nth-child(21) { animation-delay: 0.6s; }
.confetti:nth-child(22) { animation-delay: 0.63s; }
.confetti:nth-child(23) { animation-delay: 0.66s; }
.confetti:nth-child(24) { animation-delay: 0.69s; }

/* Différentes directions d'explosion - avec randomisation */
.confetti:nth-child(1) { --direction: -87deg; --distance: 58px; }
.confetti:nth-child(2) { --direction: -73deg; --distance: 67px; }
.confetti:nth-child(3) { --direction: -58deg; --distance: 72px; }
.confetti:nth-child(4) { --direction: -42deg; --distance: 53px; }
.confetti:nth-child(5) { --direction: -28deg; --distance: 77px; }
.confetti:nth-child(6) { --direction: -12deg; --distance: 62px; }
.confetti:nth-child(7) { --direction: 3deg; --distance: 83px; }
.confetti:nth-child(8) { --direction: 18deg; --distance: 64px; }
.confetti:nth-child(9) { --direction: 33deg; --distance: 68px; }
.confetti:nth-child(10) { --direction: 47deg; --distance: 56px; }
.confetti:nth-child(11) { --direction: 63deg; --distance: 74px; }
.confetti:nth-child(12) { --direction: 78deg; --distance: 59px; }
.confetti:nth-child(13) { --direction: 93deg; --distance: 66px; }
.confetti:nth-child(14) { --direction: 108deg; --distance: 71px; }
.confetti:nth-child(15) { --direction: 123deg; --distance: 54px; }
.confetti:nth-child(16) { --direction: 138deg; --distance: 76px; }
.confetti:nth-child(17) { --direction: 153deg; --distance: 61px; }
.confetti:nth-child(18) { --direction: 168deg; --distance: 82px; }
.confetti:nth-child(19) { --direction: 183deg; --distance: 63px; }
.confetti:nth-child(20) { --direction: 198deg; --distance: 69px; }
.confetti:nth-child(21) { --direction: 213deg; --distance: 57px; }
.confetti:nth-child(22) { --direction: 228deg; --distance: 73px; }
.confetti:nth-child(23) { --direction: 243deg; --distance: 65px; }
.confetti:nth-child(24) { --direction: 258deg; --distance: 70px; }

.explosion-confetti.active .confetti {
    animation: explodeConfetti 2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    will-change: transform, opacity;
}

@keyframes explodeConfetti {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(0.2);
    }
    20% {
        opacity: 1;
        transform: translate(
            calc(-50% + cos(var(--direction)) * calc(var(--distance) * 0.4)),
            calc(-50% + sin(var(--direction)) * calc(var(--distance) * 0.4))
        ) scale(1.2);
    }
    50% {
        opacity: 0.8;
        transform: translate(
            calc(-50% + cos(var(--direction)) * calc(var(--distance) * 0.9)),
            calc(-50% + sin(var(--direction)) * calc(var(--distance) * 0.9) + 15px)
        ) scale(1);
    }
    80% {
        opacity: 0.3;
        transform: translate(
            calc(-50% + cos(var(--direction)) * calc(var(--distance) * 1.1)),
            calc(-50% + sin(var(--direction)) * calc(var(--distance) * 1.1) + 50px)
        ) scale(0.6);
    }
    100% {
        opacity: 0;
        transform: translate(
            calc(-50% + cos(var(--direction)) * calc(var(--distance) * 1.3)),
            calc(-50% + sin(var(--direction)) * calc(var(--distance) * 1.3) + 90px)
        ) scale(0.2);
    }
}

/* Prize Icon Styles */
.prize-icon {
    position: relative;
    background: linear-gradient(135deg, #ffd700, #ffed4e, #ffc107) !important;
    animation: goldGlow 3s ease-in-out infinite;
    z-index: 2;
}

@keyframes goldGlow {
    0%, 100% {
        background: linear-gradient(135deg, #ffd700, #ffed4e, #ffc107) !important;
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.5), 0 0 40px rgba(255, 215, 0, 0.2);
    }
    50% {
        background: linear-gradient(135deg, #ffed4e, #ffd700, #ffb300) !important;
        box-shadow: 0 0 30px rgba(255, 215, 0, 0.8), 0 0 60px rgba(255, 215, 0, 0.3);
    }
}

.prize-icon i {
    color: #8b4513 !important;
    text-shadow: 0 0 15px rgba(255, 215, 0, 0.8), 0 0 30px rgba(255, 215, 0, 0.4);
    position: relative;
    z-index: 3;
}

/* Prize Amount Styles */
.prize-amount {
    position: relative;
    font-size: 2rem;
    font-weight: bold;
    background: linear-gradient(135deg, #ffd700, #ffed4e, #ffc107);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: prizeGlow 2s ease-in-out infinite alternate;
    margin: 0;
    filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.5));
}

@keyframes prizeGlow {
    0% {
        filter: brightness(1) drop-shadow(0 0 10px rgba(255, 215, 0, 0.5));
        transform: scale(1);
    }
    100% {
        filter: brightness(1.3) drop-shadow(0 0 20px rgba(255, 215, 0, 0.8));
        transform: scale(1.05);
    }
}

/* Glow effect behind amount */
.amount-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(255, 215, 0, 0.2) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    animation: amountGlowPulse 3s ease-in-out infinite;
    z-index: -1;
}

@keyframes amountGlowPulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.5;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0.8;
    }
}

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

/* Container dédié pour les matchs */
.bg-matches-section {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(10, 10, 25, 0.9) 100%);
    border-top: 1px solid rgba(0, 201, 255, 0.1);
    border-bottom: 1px solid rgba(0, 201, 255, 0.1);
}

.matches-container {
    background: rgba(5, 5, 15, 0.6);
    border: 1px solid rgba(0, 201, 255, 0.2);
    border-radius: 16px;
    padding: 2rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}

.matches-title {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.matches-section-card {
    background: rgba(15, 15, 25, 0.8);
    border: 1px solid rgba(0, 201, 255, 0.15);
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
}

.matches-section-card:hover {
    border-color: rgba(0, 201, 255, 0.3);
    box-shadow: 0 6px 20px rgba(0, 201, 255, 0.1);
    transform: translateY(-2px);
}

.section-header {
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(0, 201, 255, 0.1);
}

.section-header h4 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.matches-content {
    min-height: 200px;
}

.no-matches-message {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    color: var(--text-secondary);
    font-style: italic;
    background: rgba(0, 0, 0, 0.2);
    border: 1px dashed rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    text-align: center;
}

/* Styles pour les matchs */
.match-card {
    background: rgba(10, 10, 20, 0.9);
    border: 1px solid rgba(0, 201, 255, 0.2);
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.match-card:hover {
    border-color: rgba(0, 201, 255, 0.4);
    box-shadow: 0 4px 15px rgba(0, 201, 255, 0.15);
    transform: translateY(-1px);
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.match-teams {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
}

.team-name {
    color: var(--text-primary);
    font-weight: 500;
    font-size: 0.9rem;
}

.team-winner {
    color: var(--primary-blue) !important;
    font-weight: 700;
    text-shadow: 0 0 8px rgba(0, 201, 255, 0.5);
}

.vs-text {
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 300;
    margin: 0 0.3rem;
}

.match-date {
    color: var(--text-secondary);
    font-size: 0.8rem;
    white-space: nowrap;
}

.match-games {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    margin-top: 0.5rem;
    flex-wrap: wrap;
}

.game-score {
    display: flex;
    align-items: center;
    gap: 0.2rem;
    background: rgba(0, 0, 0, 0.4);
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.score {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.score-winner {
    color: var(--primary-blue) !important;
    text-shadow: 0 0 4px rgba(0, 201, 255, 0.3);
}

.score-separator {
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin: 0 0.1rem;
}

.match-type {
    text-align: center;
    margin-top: 0.5rem;
    padding: 0.4rem;
    background: rgba(0, 201, 255, 0.1);
    border-radius: 6px;
    color: var(--text-secondary);
    font-size: 0.8rem;
    border: 1px solid rgba(0, 201, 255, 0.2);
}

.game-info-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
}

.game-map {
    font-size: 0.7rem;
    color: var(--text-secondary);
    text-align: center;
    padding: 0.2rem 0.4rem;
    background: rgba(0, 201, 255, 0.1);
    border-radius: 4px;
    border: 1px solid rgba(0, 201, 255, 0.2);
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
            
            if (displayElement) {
                displayElement.innerHTML = `${teamCount}<span class="text-secondary">/8</span>`;
            }
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du compteur d\'équipes:', error);
    }
}

// Animation du compteur de prix
function animatePrizeCounter() {
    const counter = document.getElementById('prizeCounter');
    const iconExplosion = document.getElementById('iconExplosion');
    const amountExplosion = document.getElementById('amountExplosion');
    let count = 0;
    const target = 100;
    const duration = 2000; // 2 secondes
    const increment = target / (duration / 50); // 50ms par frame
    
    const interval = setInterval(() => {
        if (count < target) {
            count += increment;
            counter.textContent = Math.floor(count);
        } else {
            counter.textContent = target;
            clearInterval(interval);
            
            // Déclencher l'explosion de confettis
            setTimeout(() => {
                triggerConfettiExplosion(iconExplosion);
                triggerConfettiExplosion(amountExplosion);
            }, 200);
        }
    }, 50);
}

// Fonction pour déclencher l'explosion de confettis
function triggerConfettiExplosion(explosionElement) {
    explosionElement.classList.add('active');
    
    // Retirer la classe active après l'animation (2s + marge)
    setTimeout(() => {
        explosionElement.classList.remove('active');
    }, 2300);
}

// Vérifier le statut au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updateStreamStatusHome();
    updateTeamCount();
    
    // Lancer l'animation du prix après 500ms
    setTimeout(animatePrizeCounter, 500);
    
    // Vérifier plus fréquemment pour les tests (30 secondes)
    setInterval(updateStreamStatusHome, 30000);
    
    // Vérifier le compteur d'équipes toutes les 3 minutes (180000ms)
    setInterval(updateTeamCount, 180000);
    
    // Debug: afficher les réponses dans la console
    console.log('Stream status check initialized');
});
</script>

<?= $this->endSection() ?>