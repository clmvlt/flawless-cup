<nav class="navbar navbar-expand-lg navbar-futuristic">
    <div class="container-fluid px-4">
        <a class="navbar-brand navbar-brand-futuristic" href="/">
            <i class="fas fa-trophy"></i>flawless cup
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-active-check" href="/" data-page="home">
                        <i class="fas fa-home me-2"></i>Accueil
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link nav-link-active-check" href="/poules" data-page="poules">
                        <i class="fas fa-trophy me-2"></i>Tournoi
                    </a>
                </li>
            </ul>
            
            <!-- Statut Twitch -->
            <div class="navbar-nav me-3">
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="https://twitch.tv/urikaynee" target="_blank" id="twitchStatus">
                        <i class="fab fa-twitch me-2"></i>
                        <span id="streamStatus" class="d-none d-md-inline">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Vérification...
                        </span>
                    </a>
                </div>
            </div>
            
            <ul class="navbar-nav">
                <?php if ($isLoggedIn && $player): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (isset($player['avatar']) && $player['avatar']): ?>
                                <img src="<?= esc($player['avatar']) ?>" alt="Avatar" class="user-avatar me-2">
                            <?php else: ?>
                                <div class="user-avatar me-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <span class="d-none d-md-inline"><?= esc($player['pseudo']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <?php if (\App\Controllers\Admin::hasAdminAccess()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/admin">
                                        <i class="fas fa-shield-alt me-2"></i>Administration
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="https://discord.com/oauth2/authorize?client_id=1405941959792791602&response_type=code&redirect_uri=https%3A%2F%2Fflawless-cup.fr%2Fregister&scope=identify">
                            <i class="fab fa-discord me-2"></i><span class="d-none d-md-inline">Se connecter</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>