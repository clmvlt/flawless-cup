<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Flawless Cup' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="/css/futuristic.css?v=4" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'DreamMMA';
            src: url('/fonts/dream-mma.ttf?v=4') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'ValorantFont';
            src: url('/fonts/valorant-font.ttf?v=1') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        .hero-title {
            font-family: 'DreamMMA', 'Courier New', monospace !important;
            font-weight: normal !important;
        }
        .dream-mma-font {
            font-family: 'DreamMMA', 'Courier New', monospace !important;
            font-weight: normal !important;
        }
        .valorant-font {
            font-family: 'ValorantFont', 'Roboto', sans-serif !important;
            font-weight: normal !important;
        }
    </style>
    <?= $extraHead ?? '' ?>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-logo">
                <span class="loading-text dream-mma-font">flawless cup</span>
            </div>
            <div class="loading-progress">
                <div class="loading-bar"></div>
            </div>
            <div class="loading-subtitle">Initialisation du tournoi...</div>
        </div>
    </div>

    <!-- Mouse cursor trail effect -->
    <div class="cursor-trail-container" id="cursorTrail"></div>
    
    <!-- RGB Mode Indicator -->
    <div class="rgb-mode-indicator" id="rgbModeIndicator">
        🌈 RGB Mode Activated!
    </div>

    <!-- Page Transition Overlay -->
    <div class="transition-overlay" id="transitionOverlay">
        <div class="transition-loading">
            <div class="transition-spinner"></div>
            <div class="transition-text">Chargement...</div>
        </div>
    </div>

    <!-- Animated Grid Background -->
    <div class="grid-background">
        <div class="grid-container"></div>
        <div class="grid-light"></div>
        <div class="grid-light"></div>
        <div class="grid-light"></div>
        <div class="grid-light"></div>
        <div class="grid-light"></div>
        <div class="grid-light"></div>
    </div>

    <!-- Animated Background Elements -->
    <div class="diagonal-lines">
        <div class="diagonal-line"></div>
        <div class="diagonal-line"></div>
        <div class="diagonal-line"></div>
        <div class="diagonal-line"></div>
        <div class="diagonal-line"></div>
    </div>
    
    <div class="particles" id="particles"></div>

    <!-- Navbar -->
    <?= view('partials/navbar', ['isLoggedIn' => $isLoggedIn ?? false, 'player' => $player ?? null]) ?>

    <!-- Main Content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Easter Egg: RGB Mode
        let rgbModeActive = false;
        let keySequence = '';
        const secretCode = 'dimzlebg';
        
        function initEasterEgg() {
            document.addEventListener('keydown', function(e) {
                // Only register keys if not typing in an input field
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) {
                    return;
                }
                
                keySequence += e.key.toLowerCase();
                
                // Keep only the last 8 characters
                if (keySequence.length > secretCode.length) {
                    keySequence = keySequence.slice(-secretCode.length);
                }
                
                // Check if the secret code was typed
                if (keySequence === secretCode) {
                    activateRGBMode();
                    keySequence = ''; // Reset sequence
                }
            });
        }
        
        function activateRGBMode() {
            rgbModeActive = true;
            
            // Clear sessionStorage first to ensure fresh state
            sessionStorage.removeItem('rgbMode');
            // Then set it
            sessionStorage.setItem('rgbMode', 'true');
            
            // Show indicator
            const indicator = document.getElementById('rgbModeIndicator');
            indicator.classList.add('show');
            
            // Hide indicator after 4 seconds
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 4000);
            
            // Add some visual feedback
            document.body.style.setProperty('--rgb-active', '1');
            
            console.log('🌈 RGB Mode Activated! Trail particles will now be rainbow colored!');
        }
        
        function clearRGBMode() {
            rgbModeActive = false;
            sessionStorage.removeItem('rgbMode');
            document.body.style.removeProperty('--rgb-active');
            console.log('🔵 RGB Mode cleared - back to blue particles');
        }
        
        function checkRGBModeFromSession() {
            if (sessionStorage.getItem('rgbMode') === 'true') {
                rgbModeActive = true;
                document.body.style.setProperty('--rgb-active', '1');
                console.log('🌈 RGB Mode restored from session!');
            }
        }

        // Mouse cursor trail effect
        function initCursorTrail() {
            const trailContainer = document.getElementById('cursorTrail');
            let lastMouseX = 0;
            let lastMouseY = 0;
            let isOverContainer = false;
            
            // Create particle pool for performance
            const particlePool = [];
            const maxParticles = 60;
            
            // Initialize particle pool
            for (let i = 0; i < maxParticles; i++) {
                const particle = document.createElement('div');
                particle.className = 'trail-particle';
                particle.style.opacity = '0';
                trailContainer.appendChild(particle);
                particlePool.push({
                    element: particle,
                    active: false
                });
            }
            
            function getAvailableParticle() {
                return particlePool.find(p => !p.active) || particlePool[0];
            }
            
            function createTrailParticle(x, y) {
                const particle = getAvailableParticle();
                if (!particle) return;
                
                // Don't create particles if over a container
                if (isOverContainer) return;
                
                // Reset classes and styles
                particle.element.className = 'trail-particle';
                particle.element.style.opacity = '1';
                
                // Add RGB class if easter egg is active
                if (rgbModeActive) {
                    particle.element.classList.add('rgb');
                }
                
                // Set position with small random offset
                const offsetX = (Math.random() - 0.5) * 8;
                const offsetY = (Math.random() - 0.5) * 8;
                particle.element.style.left = (x + offsetX) + 'px';
                particle.element.style.top = (y + offsetY) + 'px';
                
                // Activate particle
                particle.active = true;
                
                // Animate
                const duration = rgbModeActive ? 800 : 600;
                
                if (rgbModeActive) {
                    particle.element.style.animation = `rgbCycle 1.5s linear infinite, particleFade ${duration}ms ease-out forwards`;
                } else {
                    particle.element.style.animation = `particleFade ${duration}ms ease-out forwards`;
                }
                
                // Deactivate after animation
                setTimeout(() => {
                    particle.active = false;
                    particle.element.style.opacity = '0';
                    particle.element.style.animation = '';
                }, duration);
            }
            
            // Track mouse movement
            document.addEventListener('mousemove', function(e) {
                const newMouseX = e.clientX;
                const newMouseY = e.clientY;
                
                // Calculate movement
                const deltaX = Math.abs(newMouseX - lastMouseX);
                const deltaY = Math.abs(newMouseY - lastMouseY);
                
                // Check if mouse is over a container element
                const elementUnderMouse = document.elementFromPoint(newMouseX, newMouseY);
                const containerSelectors = [
                    '.futuristic-card',
                    '.navbar-futuristic',
                    '.btn-futuristic',
                    '.alert-futuristic',
                    '.alert-success-futuristic',
                    '.alert-warning-futuristic',
                    '.alert-danger-futuristic',
                    '.dropdown-menu',
                    '.twitch-container',
                    '.form-control-futuristic'
                ];
                
                isOverContainer = false;
                if (elementUnderMouse) {
                    isOverContainer = containerSelectors.some(selector => 
                        elementUnderMouse.closest(selector) !== null
                    );
                }
                
                // Create particles if mouse is moving
                if ((deltaX > 2 || deltaY > 2) && !isOverContainer) {
                    createTrailParticle(newMouseX, newMouseY);
                }
                
                lastMouseX = newMouseX;
                lastMouseY = newMouseY;
            });
        }

        // Generate floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Grid Trail System
        function initGridTrails() {
            const gridContainer = document.querySelector('.grid-container');
            if (!gridContainer) {
                console.error('Grid container not found!');
                return;
            }
            
            console.log('🔷 Grid trails system initialized');
            const gridSize = 50; // Taille des carrés de la grille (50px)
            
            function createGridTrail() {
                const trail = document.createElement('div');
                trail.className = 'grid-trail';
                
                // Déterminer si c'est une trainée horizontale ou verticale
                const isHorizontal = Math.random() > 0.5;
                
                if (isHorizontal) {
                    // Trainée horizontale
                    const row = Math.floor(Math.random() * Math.floor(window.innerHeight / gridSize));
                    const direction = Math.random() > 0.5 ? 'right' : 'left';
                    
                    trail.style.top = (row * gridSize + 25) + 'px'; // Centrer sur la ligne
                    trail.style.left = direction === 'right' ? '0px' : 'calc(100% - 6px)';
                    
                    const animationName = direction === 'right' ? 'trailHorizontalRight' : 'trailHorizontalLeft';
                    const duration = 2 + Math.random() * 2; // 2-4 secondes
                    
                    trail.style.animation = `${animationName} ${duration}s linear forwards`;
                    
                    console.log(`🔷 Creating horizontal trail: row ${row}, direction ${direction}`);
                } else {
                    // Trainée verticale
                    const col = Math.floor(Math.random() * Math.floor(window.innerWidth / gridSize));
                    const direction = Math.random() > 0.5 ? 'down' : 'up';
                    
                    trail.style.left = (col * gridSize + 25) + 'px'; // Centrer sur la ligne
                    trail.style.top = direction === 'down' ? '0px' : 'calc(100% - 6px)';
                    
                    const animationName = direction === 'down' ? 'trailVerticalDown' : 'trailVerticalUp';
                    const duration = 2 + Math.random() * 2; // 2-4 secondes
                    
                    trail.style.animation = `${animationName} ${duration}s linear forwards`;
                    
                    console.log(`🔷 Creating vertical trail: col ${col}, direction ${direction}`);
                }
                
                gridContainer.appendChild(trail);
                
                // Supprimer la trainée après l'animation
                const cleanupDelay = (2 + Math.random() * 2) * 1000 + 500;
                setTimeout(() => {
                    if (trail.parentNode) {
                        trail.parentNode.removeChild(trail);
                    }
                }, cleanupDelay);
            }
            
            function spawnRandomTrail() {
                createGridTrail();
                
                // Programmer la prochaine trainée (intervalle plus fréquent pour tester)
                const nextSpawn = 500 + Math.random() * 2000; // Entre 0.5 et 2.5 secondes
                setTimeout(spawnRandomTrail, nextSpawn);
            }
            
            // Démarrer le système de trainées immédiatement pour tester
            console.log('🔷 Starting grid trails in 1 second...');
            setTimeout(spawnRandomTrail, 1000);
        }

        // Team Accordion System
        function initTeamAccordion() {
            const teamRows = document.querySelectorAll('.team-row');
            
            teamRows.forEach(teamRow => {
                teamRow.addEventListener('click', function(e) {
                    // Éviter de déclencher l'accordéon si on clique sur les résultats
                    if (e.target.closest('.team-results')) {
                        return;
                    }
                    
                    const teamDetails = this.nextElementSibling;
                    if (!teamDetails || !teamDetails.classList.contains('team-details')) {
                        return;
                    }
                    
                    const isExpanded = this.classList.contains('expanded');
                    
                    // Fermer tous les autres accordéons ouverts
                    document.querySelectorAll('.team-row.expanded').forEach(expandedRow => {
                        if (expandedRow !== this) {
                            expandedRow.classList.remove('expanded');
                            const otherDetails = expandedRow.nextElementSibling;
                            if (otherDetails && otherDetails.classList.contains('team-details')) {
                                otherDetails.classList.remove('expanded');
                            }
                        }
                    });
                    
                    // Toggle l'accordéon actuel
                    if (isExpanded) {
                        // Fermer
                        this.classList.remove('expanded');
                        teamDetails.classList.remove('expanded');
                    } else {
                        // Ouvrir
                        this.classList.add('expanded');
                        teamDetails.classList.add('expanded');
                        
                        // Scroll smooth vers l'équipe si nécessaire
                        setTimeout(() => {
                            this.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }, 200);
                    }
                });
            });
        }

        // Vérifier le statut Twitch
        async function checkTwitchStatus() {
            try {
                const response = await fetch('/twitch/status');
                const data = await response.json();
                
                const statusElement = document.getElementById('streamStatus');
                
                if (data.isLive) {
                    statusElement.innerHTML = '<span class="status-dot online"></span><span class="status-online">En ligne</span>';
                } else {
                    statusElement.innerHTML = '<span class="status-dot offline"></span><span class="status-offline">Hors ligne</span>';
                }
            } catch (error) {
                console.error('Erreur lors de la vérification du statut Twitch:', error);
                const statusElement = document.getElementById('streamStatus');
                statusElement.innerHTML = '<span class="status-dot offline"></span><span class="status-offline">Hors ligne</span>';
            }
        }

        // Active page highlighting
        function initActivePageHighlight() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link-active-check');
            
            console.log('Checking active page for path:', currentPath);
            
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                const pageName = link.getAttribute('data-page');
                
                // Remove any existing active class
                link.classList.remove('active-page');
                
                // Check if current page matches this link
                let isActive = false;
                
                if (currentPath === '/' && linkPath === '/') {
                    isActive = true; // Home page
                } else if (currentPath === '/poules' && linkPath === '/poules') {
                    isActive = true; // Poules page
                } else if (currentPath.startsWith('/poules') && linkPath === '/poules') {
                    isActive = true; // Any poules subpage
                }
                
                console.log(`Link ${pageName}: path=${linkPath}, current=${currentPath}, active=${isActive}`);
                
                if (isActive) {
                    link.classList.add('active-page');
                    console.log('Active page set:', pageName);
                }
            });
        }

        // Loading screen management
        function initLoadingScreen() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Check if we're on the homepage of the main domain
            const currentHost = window.location.hostname;
            const currentPath = window.location.pathname;
            const isMainDomain = currentHost === 'flawless-cup.fr' || currentHost === 'localhost' || currentHost.startsWith('127.0.0.1');
            const isHomePage = currentPath === '/' || currentPath === '';
            
            // Check if user has already seen the loading animation this session
            const hasSeenLoadingAnimation = sessionStorage.getItem('hasSeenLoadingAnimation');
            
            // Only show animation on main domain homepage and if not already seen this session
            if (!isMainDomain || !isHomePage || hasSeenLoadingAnimation === 'true') {
                // Hide loading screen immediately
                loadingOverlay.style.display = 'none';
                return;
            }
            
            // Mark that user has seen the loading animation
            sessionStorage.setItem('hasSeenLoadingAnimation', 'true');
            
            // Slide up loading screen after 3.5 seconds (after progress bar completes)
            setTimeout(() => {
                loadingOverlay.classList.add('slide-up');
                
                // Remove from DOM after transition
                setTimeout(() => {
                    loadingOverlay.remove();
                }, 800);
            }, 3500);
        }

        // Page Transition System
        function initPageTransitions() {
            const navLinks = document.querySelectorAll('.nav-link-active-check');
            const mainContent = document.querySelector('.main-content');
            const transitionOverlay = document.getElementById('transitionOverlay');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetUrl = this.getAttribute('href');
                    const currentUrl = window.location.pathname;
                    
                    // Don't transition if clicking on the same page
                    if (targetUrl === currentUrl) {
                        return;
                    }
                    
                    // Start transition
                    startPageTransition(targetUrl);
                });
            });
        }
        
        function startPageTransition(targetUrl) {
            const mainContent = document.querySelector('.main-content');
            const transitionOverlay = document.getElementById('transitionOverlay');
            const currentUrl = window.location.pathname;
            
            // Determine transition direction based on navigation flow
            const direction = getTransitionDirection(currentUrl, targetUrl);
            
            // Add transitioning class to prevent interactions
            mainContent.classList.add('transitioning');
            
            // Start slide-out animation based on direction
            if (direction === 'forward') {
                // Going from Home to Tournament (slide left)
                mainContent.style.transform = 'translateX(-100%)';
            } else {
                // Going from Tournament to Home (slide right) 
                mainContent.style.transform = 'translateX(100%)';
            }
            
            // Show loading overlay after a brief delay
            setTimeout(() => {
                transitionOverlay.classList.add('active');
            }, 200);
            
            // Store transition direction for the incoming page
            sessionStorage.setItem('transitionDirection', direction);
            
            // Navigate to new page after animation
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 600);
        }
        
        function getTransitionDirection(currentUrl, targetUrl) {
            // Define page hierarchy (lower index = earlier in flow)
            const pageHierarchy = {
                '/': 0,
                '': 0,
                '/poules': 1
            };
            
            const currentIndex = pageHierarchy[currentUrl] || 0;
            const targetIndex = pageHierarchy[targetUrl] || 0;
            
            // Forward = going to higher index, Backward = going to lower index
            return targetIndex > currentIndex ? 'forward' : 'backward';
        }
        
        function initPageTransitionIn() {
            const mainContent = document.querySelector('.main-content');
            const transitionOverlay = document.getElementById('transitionOverlay');
            
            // Check if we're coming from a transition
            const referrer = document.referrer;
            const currentDomain = window.location.origin;
            const transitionDirection = sessionStorage.getItem('transitionDirection');
            
            if (referrer && referrer.startsWith(currentDomain) && transitionDirection) {
                // Set initial position for slide-in based on direction
                if (transitionDirection === 'forward') {
                    // Coming from Home to Tournament (slide in from right)
                    mainContent.style.transform = 'translateX(100%)';
                } else {
                    // Coming from Tournament to Home (slide in from left)
                    mainContent.style.transform = 'translateX(-100%)';
                }
                
                mainContent.style.transition = 'none';
                
                // Hide overlay
                transitionOverlay.classList.remove('active');
                
                // Force reflow
                mainContent.offsetHeight;
                
                // Re-enable transition and slide in
                setTimeout(() => {
                    mainContent.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    mainContent.style.transform = 'translateX(0)';
                    mainContent.classList.remove('transitioning');
                    
                    // Re-run active page highlighting after transition
                    setTimeout(() => {
                        initActivePageHighlight();
                    }, 100);
                }, 50);
                
                // Clean up transition direction after use
                sessionStorage.removeItem('transitionDirection');
            } else {
                // Normal page load, ensure content is in correct position
                mainContent.style.transform = 'translateX(0)';
                mainContent.classList.remove('transitioning');
                transitionOverlay.classList.remove('active');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Start loading screen
            initLoadingScreen();
            
            // Clear RGB mode on page refresh
            clearRGBMode();
            
            initEasterEgg();
            initCursorTrail();
            createParticles();
            initGridTrails();
            initTeamAccordion();
            checkTwitchStatus();
            initActivePageHighlight();
            initPageTransitions();
            initPageTransitionIn();
            
            // Vérifier toutes les 2 minutes
            setInterval(checkTwitchStatus, 120000);
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
    <?= $extraScripts ?? '' ?>
</body>
</html>