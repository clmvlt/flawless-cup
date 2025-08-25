<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitch Chat - <?= esc($channel) ?></title>
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
            background: transparent;
            font-family: 'DreamMMA', 'Segoe UI', sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        .chat-container {
            width: 400px;
            height: 600px;
            background: rgba(18, 18, 18, 0.95);
            border-left: 3px solid #9146ff;
            display: flex;
            flex-direction: column;
            position: fixed;
            right: 0;
            top: 0;
        }

        .chat-header {
            background: rgba(145, 70, 255, 0.1);
            padding: 10px 15px;
            border-bottom: 1px solid rgba(145, 70, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .twitch-logo {
            width: 20px;
            height: 20px;
            fill: #9146ff;
        }

        .channel-name {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }

        .viewer-count {
            color: #9146ff;
            font-size: 12px;
            margin-left: auto;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(145, 70, 255, 0.5);
            border-radius: 3px;
        }

        .chat-message {
            padding: 6px 8px;
            border-radius: 4px;
            background: transparent;
            border-left: 2px solid transparent;
            animation: slideIn 0.3s ease-out;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .chat-message.moderator {
            border-left-color: #00ad03;
        }

        .chat-message.subscriber {
            border-left-color: #9146ff;
        }

        .chat-message.vip {
            border-left-color: #ff69b4;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .username {
            font-weight: 600;
            margin-right: 8px;
            font-size: 13px;
        }

        .message-text {
            color: #efeff1;
            font-size: 13px;
        }

        .badges {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            margin-right: 6px;
        }

        .badge {
            display: inline-block;
            width: 18px;
            height: 18px;
            vertical-align: middle;
            border-radius: 2px;
            flex-shrink: 0;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .emote {
            vertical-align: middle;
            height: 24px;
            margin: 0 2px;
        }

        .connection-status {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            z-index: 100;
        }

        .connection-status.connected {
            background: rgba(0, 255, 0, 0.8);
        }

        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9146ff;
            font-size: 14px;
        }

        /* Couleurs de noms d'utilisateur */
        .username.color-red { color: #ff6b6b; }
        .username.color-blue { color: #4dabf7; }
        .username.color-green { color: #51cf66; }
        .username.color-firebrick { color: #e03131; }
        .username.color-coral { color: #ff8787; }
        .username.color-yellowgreen { color: #8ce99a; }
        .username.color-orangered { color: #ff6b35; }
        .username.color-seagreen { color: #20c997; }
        .username.color-goldenrod { color: #ffd43b; }
        .username.color-dodgerblue { color: #339af0; }
        .username.color-cadetblue { color: #15aabf; }
        .username.color-springgreen { color: #12b886; }
        .username.color-blueviolet { color: #9775fa; }
        .username.color-hotpink { color: #f783ac; }
        .username.color-chocolate { color: #fd7e14; }

        @media (max-width: 768px) {
            .chat-container {
                width: 100vw;
                height: 100vh;
                right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="connection-status" id="connectionStatus">Déconnecté</div>
    
    <div class="chat-container">
        <div class="chat-header">
            <svg class="twitch-logo" viewBox="0 0 24 24">
                <path d="M2.149 0L.537 4.119v16.836h5.731V24h3.224l3.045-3.045h4.657l6.269-6.269V0H2.149zm19.164 13.612l-3.582 3.582h-5.731l-3.045 3.045v-3.045H4.298V2.687h17.015v10.925z"/>
                <path d="M18.731 4.836v8.134h-2.687V4.836h2.687zm-7.269 0v8.134H8.776V4.836h2.686z"/>
            </svg>
            <span class="channel-name">CHATBOX</span>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="loading">Connexion au chat...</div>
        </div>
    </div>

    <script>
        const channel = '<?= esc($channel) ?>';
        const chatMessages = document.getElementById('chatMessages');
        const connectionStatus = document.getElementById('connectionStatus');
        
        let ws = null;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 5;
        const reconnectDelay = 3000;

        // Couleurs pour les noms d'utilisateur
        const usernameColors = [
            'red', 'blue', 'green', 'firebrick', 'coral', 'yellowgreen',
            'orangered', 'seagreen', 'goldenrod', 'dodgerblue', 'cadetblue',
            'springgreen', 'blueviolet', 'hotpink', 'chocolate'
        ];

        // Stocker les couleurs assignées à chaque utilisateur
        const userColors = new Map();

        function getUserColor(username) {
            if (!userColors.has(username)) {
                // Assigner une couleur aléatoire permanente à cet utilisateur
                const randomColor = usernameColors[Math.floor(Math.random() * usernameColors.length)];
                userColors.set(username, randomColor);
            }
            return userColors.get(username);
        }

        function updateConnectionStatus(status, message) {
            if (status === 'connected') {
                connectionStatus.style.display = 'none';
            } else {
                connectionStatus.textContent = message;
                connectionStatus.className = `connection-status ${status}`;
                connectionStatus.style.display = 'block';
            }
        }

        // URLs des badges officiels Twitch
        const badgeUrls = {
            'broadcaster': 'https://static-cdn.jtvnw.net/badges/v1/5527c58c-fb7d-422d-b71b-f309dcb85cc1/1',
            'moderator': 'https://static-cdn.jtvnw.net/badges/v1/3267646d-33f0-4b17-b3df-f923a41db1d0/1',
            'vip': 'https://static-cdn.jtvnw.net/badges/v1/b817aba4-fad8-49e2-b88a-7cc744dfa6ec/1',
            'subscriber': 'https://static-cdn.jtvnw.net/badges/v1/5d9f2208-5dd8-11e7-8513-2ff4adfae661/1',
            'founder': 'https://static-cdn.jtvnw.net/badges/v1/511b78a9-ab37-472f-9569-457753ebe7d3/1',
            'sub-gifter': 'https://static-cdn.jtvnw.net/badges/v1/a5ef6c17-2e5b-4d8f-9b80-2779fd722414/1',
            'turbo': 'https://static-cdn.jtvnw.net/badges/v1/bd444ec6-8f34-4bf9-91f4-af1e3428d80f/1',
            'premium': 'https://static-cdn.jtvnw.net/badges/v1/bbbe0db0-a598-423e-86d0-f9fb98ca1933/1',
            'staff': 'https://static-cdn.jtvnw.net/badges/v1/d97c37bd-a6f5-4c38-8f57-4e4bef88af34/1',
            'admin': 'https://static-cdn.jtvnw.net/badges/v1/9ef7e029-4cdf-4d4d-a0d5-e2b3fb2583fe/1',
            'global_mod': 'https://static-cdn.jtvnw.net/badges/v1/9384c43e-4ce7-4e94-b2a1-b93656896eba/1'
        };

        function createBadgeElement(badgeType) {
            const badge = document.createElement('div');
            badge.className = 'badge';
            
            // Utiliser l'image officielle Twitch
            if (badgeUrls[badgeType]) {
                badge.style.backgroundImage = `url('${badgeUrls[badgeType]}')`;
            } else {
                // Fallback pour badges non reconnus
                badge.style.backgroundColor = '#666';
                badge.style.color = 'white';
                badge.style.fontSize = '8px';
                badge.style.textAlign = 'center';
                badge.style.lineHeight = '18px';
                badge.textContent = '?';
            }
            
            return badge;
        }

        function addMessage(username, message, badges = [], userColor = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message';
            
            // Ajouter des classes pour les badges spéciaux (pour la bordure)
            if (badges.includes('moderator')) messageDiv.classList.add('moderator');
            if (badges.includes('subscriber')) messageDiv.classList.add('subscriber');
            if (badges.includes('vip')) messageDiv.classList.add('vip');
            
            // Créer le conteneur des badges
            const badgesContainer = document.createElement('span');
            badgesContainer.className = 'badges';
            
            // Ajouter les badges dans l'ordre de priorité
            const badgeOrder = ['broadcaster', 'moderator', 'vip', 'founder', 'subscriber', 'sub-gifter', 'turbo', 'premium'];
            
            badgeOrder.forEach(badgeType => {
                if (badges.includes(badgeType)) {
                    badgesContainer.appendChild(createBadgeElement(badgeType));
                }
            });
            
            const usernameSpan = document.createElement('span');
            usernameSpan.className = `username color-${userColor || getUserColor(username)}`;
            usernameSpan.textContent = username.toLowerCase();
            
            const messageSpan = document.createElement('span');
            messageSpan.className = 'message-text';
            messageSpan.textContent = `: ${message.toLowerCase()}`;
            
            // Assembler le message : badges + username + message
            if (badgesContainer.children.length > 0) {
                messageDiv.appendChild(badgesContainer);
            }
            messageDiv.appendChild(usernameSpan);
            messageDiv.appendChild(messageSpan);
            
            chatMessages.appendChild(messageDiv);
            
            // Garder seulement les 100 derniers messages
            while (chatMessages.children.length > 100) {
                chatMessages.removeChild(chatMessages.firstChild);
            }
            
            // Auto-scroll vers le bas
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function connectToTwitchChat() {
            updateConnectionStatus('connecting', 'Connexion...');
            
            ws = new WebSocket('wss://irc-ws.chat.twitch.tv:443');
            
            ws.onopen = function() {
                console.log('Connexion WebSocket ouverte');
                
                // Demander les capacités IRCv3 pour recevoir les badges et métadonnées
                ws.send('CAP REQ :twitch.tv/tags twitch.tv/commands twitch.tv/membership');
                
                // Authentification anonyme
                ws.send('PASS oauth:anonymous');
                ws.send(`NICK justinfan${Math.floor(Math.random() * 100000)}`);
                ws.send(`JOIN #${channel.toLowerCase()}`);
                
                updateConnectionStatus('connected', 'Connecté');
                reconnectAttempts = 0;
                
                // Vider le loading
                chatMessages.innerHTML = '';
            };
            
            ws.onmessage = function(event) {
                const message = event.data.trim();
                
                // Répondre aux pings
                if (message.startsWith('PING')) {
                    ws.send(message.replace('PING', 'PONG'));
                    return;
                }
                
                // Parser les messages de chat
                if (message.includes('PRIVMSG')) {
                    // Séparer les tags IRCv3 du reste du message
                    let tags = '';
                    let restOfMessage = message;
                    
                    if (message.startsWith('@')) {
                        const tagEndIndex = message.indexOf(' ');
                        tags = message.substring(1, tagEndIndex); // Enlever le @ initial
                        restOfMessage = message.substring(tagEndIndex + 1);
                    }
                    
                    const msgParts = restOfMessage.split('PRIVMSG');
                    if (msgParts.length < 2) return;
                    
                    const userInfo = msgParts[0];
                    const messageContent = msgParts[1].split(':').slice(1).join(':').trim();
                    
                    // Extraire le nom d'utilisateur
                    const usernameMatch = userInfo.match(/:([^!]+)!/);
                    if (!usernameMatch) return;
                    
                    const username = usernameMatch[1];
                    const badges = [];
                    
                    // Parser les badges depuis les tags IRCv3
                    if (tags) {
                        const badgesMatch = tags.match(/badges=([^;]*)/);
                        if (badgesMatch && badgesMatch[1] && badgesMatch[1] !== '') {
                            const badgeString = badgesMatch[1];
                            const badgeList = badgeString.split(',');
                            
                            badgeList.forEach(badge => {
                                const [badgeName, badgeVersion] = badge.split('/');
                                switch(badgeName) {
                                    case 'broadcaster':
                                        badges.push('broadcaster');
                                        break;
                                    case 'moderator':
                                        badges.push('moderator');
                                        break;
                                    case 'vip':
                                        badges.push('vip');
                                        break;
                                    case 'subscriber':
                                        badges.push('subscriber');
                                        break;
                                    case 'founder':
                                        badges.push('founder');
                                        break;
                                    case 'sub-gifter':
                                    case 'sub-gift-leader':
                                        badges.push('sub-gifter');
                                        break;
                                    case 'turbo':
                                        badges.push('turbo');
                                        break;
                                    case 'premium':
                                    case 'prime':
                                        badges.push('premium');
                                        break;
                                    case 'staff':
                                        badges.push('staff');
                                        break;
                                    case 'admin':
                                        badges.push('admin');
                                        break;
                                    case 'global_mod':
                                        badges.push('global_mod');
                                        break;
                                    case 'clips-leader':
                                        // Ignorer clips-leader, pas de badge spécial pour ça
                                        break;
                                    case 'predictions':
                                        // Ignorer predictions
                                        break;
                                    default:
                                        // Ignorer les badges non reconnus
                                        break;
                                }
                            });
                        }
                    }
                    
                    // Extraire la couleur depuis les tags IRCv3
                    let userColor = null;
                    if (tags) {
                        const colorMatch = tags.match(/color=([^;]*)/);
                        if (colorMatch && colorMatch[1] && colorMatch[1] !== '') {
                            userColor = colorMatch[1].startsWith('#') ? colorMatch[1].substring(1) : colorMatch[1];
                        }
                    }
                    
                    addMessage(username, messageContent, badges, userColor);
                }
            };
            
            ws.onclose = function(event) {
                console.log('Connexion WebSocket fermée:', event.code, event.reason);
                updateConnectionStatus('disconnected', 'Déconnecté');
                
                // Tentative de reconnexion
                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    updateConnectionStatus('connecting', `Reconnexion... (${reconnectAttempts}/${maxReconnectAttempts})`);
                    setTimeout(connectToTwitchChat, reconnectDelay);
                } else {
                    updateConnectionStatus('error', 'Échec de connexion');
                }
            };
            
            ws.onerror = function(error) {
                console.error('Erreur WebSocket:', error);
                updateConnectionStatus('error', 'Erreur');
            };
        }

        // Démarrer la connexion
        connectToTwitchChat();

        // Nettoyage à la fermeture de la page
        window.addEventListener('beforeunload', function() {
            if (ws) {
                ws.close();
            }
        });
    </script>
</body>
</html>