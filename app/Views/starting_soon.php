<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starting Soon</title>
    <style>
        @font-face {
            font-family: 'DreamMMA';
            src: url('/fonts/dream-mma.ttf') format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            overflow: hidden;
            background-image: url('/back screen site internet.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            animation: subtleBackground 8s ease-in-out infinite;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(135, 206, 250, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 0, 139, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(0, 0, 0, 0.4) 0%, transparent 40%);
            animation: gentleBluePulse 6s ease-in-out infinite;
            z-index: 1;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(45deg, transparent 48%, rgba(135, 206, 250, 0.08) 49%, rgba(135, 206, 250, 0.08) 51%, transparent 52%),
                linear-gradient(-45deg, transparent 48%, rgba(25, 25, 112, 0.06) 49%, rgba(25, 25, 112, 0.06) 51%, transparent 52%);
            background-size: 100px 100px, 150px 150px;
            animation: floatGrid 12s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes subtleBackground {
            0% {
                filter: brightness(0.8) contrast(1.1) saturate(0.9);
                transform: scale(1);
            }
            50% {
                filter: brightness(1.1) contrast(1.3) saturate(1.2);
                transform: scale(1.01);
            }
            100% {
                filter: brightness(0.8) contrast(1.1) saturate(0.9);
                transform: scale(1);
            }
        }

        @keyframes gentleBluePulse {
            0% {
                opacity: 0.4;
                transform: translateX(-20px);
            }
            50% {
                opacity: 0.7;
                transform: translateX(20px);
            }
            100% {
                opacity: 0.4;
                transform: translateX(-20px);
            }
        }

        @keyframes floatGrid {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }
            50% {
                transform: translate(50px, 30px) scale(1.05);
                opacity: 0.5;
            }
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }
        }

        .container {
            text-align: center;
            z-index: 10;
            position: relative;
        }

        .starting-text {
            font-family: 'DreamMMA', Arial, sans-serif;
            font-size: 4rem;
            color: #000080;
            font-weight: bold;
            letter-spacing: 3px;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin-bottom: 2rem;
            transform: translateY(-20px);
            opacity: 0;
            animation: textFadeIn 2s ease-out forwards, letterFloat 6s ease-in-out infinite 2s, neonOutlineSweep 4s ease-in-out infinite 2s;
            position: relative;
            display: inline-block;
        }

        @keyframes textFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes letterFloat {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
            }
            25% {
                transform: translateY(-3px) translateX(2px) rotate(0.5deg);
            }
            50% {
                transform: translateY(2px) translateX(-1px) rotate(-0.3deg);
            }
            75% {
                transform: translateY(-1px) translateX(3px) rotate(0.4deg);
            }
            100% {
                transform: translateY(0) translateX(0) rotate(0deg);
            }
        }

        @keyframes neonOutlineSweep {
            0% {
                text-shadow: 
                    0 0 5px rgba(135, 206, 250, 0),
                    0 0 10px rgba(135, 206, 250, 0),
                    0 0 15px rgba(135, 206, 250, 0),
                    0 0 20px rgba(135, 206, 250, 0);
                background: linear-gradient(90deg, 
                    rgba(135, 206, 250, 0) 0%,
                    rgba(135, 206, 250, 0) 100%);
                -webkit-background-clip: text;
                background-clip: text;
            }
            20% {
                text-shadow: 
                    0 0 8px rgba(135, 206, 250, 0.6),
                    0 0 16px rgba(135, 206, 250, 0.4),
                    0 0 24px rgba(135, 206, 250, 0.3),
                    0 0 32px rgba(135, 206, 250, 0.2);
                background: linear-gradient(90deg, 
                    rgba(135, 206, 250, 0.8) 0%,
                    rgba(135, 206, 250, 0.6) 30%,
                    rgba(135, 206, 250, 0) 100%);
                -webkit-background-clip: text;
                background-clip: text;
            }
            40% {
                text-shadow: 
                    0 0 12px rgba(135, 206, 250, 0.8),
                    0 0 24px rgba(135, 206, 250, 0.6),
                    0 0 36px rgba(135, 206, 250, 0.4),
                    0 0 48px rgba(135, 206, 250, 0.3);
                background: linear-gradient(90deg, 
                    rgba(135, 206, 250, 0.4) 0%,
                    rgba(135, 206, 250, 0.8) 50%,
                    rgba(135, 206, 250, 0.6) 80%,
                    rgba(135, 206, 250, 0) 100%);
                -webkit-background-clip: text;
                background-clip: text;
            }
            60% {
                text-shadow: 
                    0 0 15px rgba(135, 206, 250, 1),
                    0 0 30px rgba(135, 206, 250, 0.8),
                    0 0 45px rgba(135, 206, 250, 0.6),
                    0 0 60px rgba(135, 206, 250, 0.4);
                background: linear-gradient(90deg, 
                    rgba(135, 206, 250, 0) 0%,
                    rgba(135, 206, 250, 0.6) 40%,
                    rgba(135, 206, 250, 1) 70%,
                    rgba(135, 206, 250, 0.8) 100%);
                -webkit-background-clip: text;
                background-clip: text;
            }
            80% {
                text-shadow: 
                    0 0 8px rgba(135, 206, 250, 0.6),
                    0 0 16px rgba(135, 206, 250, 0.4),
                    0 0 24px rgba(135, 206, 250, 0.3),
                    0 0 32px rgba(135, 206, 250, 0.2);
                background: linear-gradient(90deg, 
                    rgba(135, 206, 250, 0) 0%,
                    rgba(135, 206, 250, 0) 60%,
                    rgba(135, 206, 250, 0.8) 90%,
                    rgba(135, 206, 250, 0.6) 100%);
                -webkit-background-clip: text;
                background-clip: text;
            }
            100% {
                text-shadow: 
                    0 0 5px rgba(135, 206, 250, 0),
                    0 0 10px rgba(135, 206, 250, 0),
                    0 0 15px rgba(135, 206, 250, 0),
                    0 0 20px rgba(135, 206, 250, 0);
                background: linear-gradient(90deg, 
                    rgba(135, 206, 250, 0) 0%,
                    rgba(135, 206, 250, 0) 100%);
                -webkit-background-clip: text;
                background-clip: text;
            }
        }

        .countdown {
            font-family: 'DreamMMA', Arial, sans-serif;
            font-size: 6rem;
            color: #87CEFA;
            text-shadow: 
                0 0 25px rgba(135, 206, 250, 0.8),
                0 0 45px rgba(135, 206, 250, 0.6),
                0 0 65px rgba(135, 206, 250, 0.4);
            margin-top: 2rem;
            animation: countdownPulse 1s ease-in-out infinite;
            transform: scale(0.8);
            opacity: 0;
            animation: countdownFadeIn 2.5s ease-out forwards, countdownPulse 1s ease-in-out infinite 2.5s;
        }

        @keyframes countdownFadeIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes countdownPulse {
            0%, 100% {
                transform: scale(1);
                text-shadow: 
                    0 0 25px rgba(135, 206, 250, 0.8),
                    0 0 45px rgba(135, 206, 250, 0.6),
                    0 0 65px rgba(135, 206, 250, 0.4);
            }
            50% {
                transform: scale(1.05);
                text-shadow: 
                    0 0 35px rgba(135, 206, 250, 1),
                    0 0 55px rgba(135, 206, 250, 0.8),
                    0 0 75px rgba(135, 206, 250, 0.6);
            }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(135deg, 
                    rgba(135, 206, 250, 0.15) 0%, 
                    transparent 25%, 
                    rgba(0, 0, 139, 0.2) 50%, 
                    transparent 75%, 
                    rgba(0, 0, 0, 0.3) 100%);
            z-index: 3;
            animation: smoothBlueOverlay 10s ease-in-out infinite;
        }

        @keyframes smoothBlueOverlay {
            0% {
                opacity: 0.3;
                transform: translateY(-10px);
            }
            50% {
                opacity: 0.6;
                transform: translateY(10px);
            }
            100% {
                opacity: 0.3;
                transform: translateY(-10px);
            }
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 5;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle.neon-cyan {
            background: rgba(0, 255, 255, 0.6);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.8), 0 0 20px rgba(0, 255, 255, 0.6), 0 0 30px rgba(0, 255, 255, 0.4);
        }

        .particle.neon-magenta {
            background: rgba(255, 0, 255, 0.6);
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.8), 0 0 20px rgba(255, 0, 255, 0.6), 0 0 30px rgba(255, 0, 255, 0.4);
        }

        .particle.neon-green {
            background: rgba(0, 255, 127, 0.6);
            box-shadow: 0 0 10px rgba(0, 255, 127, 0.8), 0 0 20px rgba(0, 255, 127, 0.6), 0 0 30px rgba(0, 255, 127, 0.4);
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
                transform: translateY(90vh) rotate(36deg) scale(1);
            }
            50% {
                opacity: 1;
                transform: translateY(50vh) rotate(180deg) scale(1.2);
            }
            90% {
                opacity: 1;
                transform: translateY(10vh) rotate(324deg) scale(0.8);
            }
            100% {
                transform: translateY(-10vh) rotate(360deg) scale(0);
                opacity: 0;
            }
        }

        .timer-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 20;
            display: flex;
            flex-direction: row;
            gap: 15px;
            align-items: center;
        }

        .timer-control {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .timer-control:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.1);
        }

        .timer-control svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .time-input {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 25px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .time-input:hover {
            background: rgba(0, 0, 0, 0.9);
        }

        .time-input input[type="text"] {
            background: transparent;
            border: 1px solid #87CEFA;
            border-radius: 5px;
            padding: 5px 10px;
            color: white;
            font-family: 'DreamMMA', Arial, sans-serif;
            font-size: 14px;
            width: 60px;
            text-align: center;
            outline: none;
        }

        .time-input input[type="text"]:focus {
            border-color: #87CEFA;
            box-shadow: 0 0 10px rgba(135, 206, 250, 0.3);
        }

        .time-input label {
            color: white;
            font-size: 12px;
            font-family: 'DreamMMA', Arial, sans-serif;
        }

        @media (max-width: 768px) {
            .starting-text {
                font-size: 2.5rem;
            }
            .countdown {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="particles" id="particles"></div>
    
    <div class="container">
        <div class="starting-text">j'arrive bientot !</div>
        <div class="countdown" id="countdown">05:00</div>
    </div>

    <div class="timer-controls">
        <div class="time-input">
            <label>Timer:</label>
            <input type="text" id="timeInput" value="05:00" placeholder="05:00">
        </div>
        
        <div class="timer-control" id="timerControl">
            <svg viewBox="0 0 24 24" id="playIcon">
                <path d="M8 5v14l11-7z"/>
            </svg>
            <svg viewBox="0 0 24 24" id="pauseIcon" style="display: none;">
                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
        </div>
    </div>

    <script>
        // Récupérer le paramètre timer depuis l'URL
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        // Initialiser avec le paramètre URL ou valeur par défaut
        const urlTimer = getUrlParameter('timer') || '05:00';
        let totalSeconds = parseTimeFromUrl(urlTimer);
        
        const countdownElement = document.getElementById('countdown');
        const timerControl = document.getElementById('timerControl');
        const playIcon = document.getElementById('playIcon');
        const pauseIcon = document.getElementById('pauseIcon');
        const timeInput = document.getElementById('timeInput');
        
        // Définir la valeur par défaut du champ
        timeInput.value = urlTimer;
        
        let countdownInterval;

        function updateCountdown() {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            
            const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            countdownElement.textContent = formattedTime;
            
            if (totalSeconds > 0) {
                totalSeconds--;
            } else {
                countdownElement.textContent = "00:00";
                countdownElement.style.color = "#87CEFA";
                countdownElement.style.textShadow = `
                    0 0 25px rgba(135, 206, 250, 0.8),
                    0 0 45px rgba(135, 206, 250, 0.6),
                    0 0 65px rgba(135, 206, 250, 0.4)`;
                clearInterval(countdownInterval);
            }
        }

        // Fonction pour parser le temps depuis l'URL - accepte MM:SS ou juste MM
        function parseTimeFromUrl(timeStr) {
            const parts = timeStr.split(':');
            if (parts.length === 2) {
                // Format MM:SS
                const minutes = parseInt(parts[0]) || 0;
                const seconds = parseInt(parts[1]) || 0;
                return minutes * 60 + seconds;
            } else if (parts.length === 1) {
                // Format MM seulement (juste les minutes)
                const minutes = parseInt(parts[0]) || 0;
                return minutes * 60;
            }
            return 5 * 60; // Valeur par défaut
        }

        // Fonction pour parser le temps - accepte MM:SS ou juste MM
        function parseTimeInput(timeStr) {
            const parts = timeStr.split(':');
            if (parts.length === 2) {
                // Format MM:SS
                const minutes = parseInt(parts[0]) || 0;
                const seconds = parseInt(parts[1]) || 0;
                return minutes * 60 + seconds;
            } else if (parts.length === 1) {
                // Format MM seulement (juste les minutes)
                const minutes = parseInt(parts[0]) || 0;
                return minutes * 60;
            }
            return 5 * 60; // Valeur par défaut
        }

        // Fonction pour démarrer le countdown
        function startCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            totalSeconds = parseTimeInput(timeInput.value);
            // Mettre à jour immédiatement l'affichage
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            countdownElement.textContent = formattedTime;
            countdownElement.style.color = "#87CEFA";
            countdownElement.style.textShadow = `
                0 0 25px rgba(135, 206, 250, 0.8),
                0 0 45px rgba(135, 206, 250, 0.6),
                0 0 65px rgba(135, 206, 250, 0.4)`;
            
            countdownInterval = setInterval(updateCountdown, 1000);
        }

        // Démarrer le countdown initial
        startCountdown();

        // Gestion du bouton play/pause pour le timer
        timerControl.addEventListener('click', function() {
            if (countdownInterval) {
                // Timer en cours - pause
                clearInterval(countdownInterval);
                countdownInterval = null;
                playIcon.style.display = 'block';
                pauseIcon.style.display = 'none';
            } else {
                // Timer en pause - start/restart
                startCountdown();
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'block';
            }
        });

        function createParticle() {
            const particle = document.createElement('div');
            const neonColors = ['neon-cyan', 'neon-magenta', 'neon-green'];
            const randomColor = neonColors[Math.floor(Math.random() * neonColors.length)];
            
            particle.className = `particle ${randomColor}`;
            
            const size = Math.random() * 6 + 3;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 6 + 6) + 's';
            particle.style.animationDelay = Math.random() * 3 + 's';
            
            document.getElementById('particles').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 12000);
        }

        setInterval(createParticle, 500);

        for (let i = 0; i < 10; i++) {
            setTimeout(createParticle, i * 200);
        }
    </script>
</body>
</html>