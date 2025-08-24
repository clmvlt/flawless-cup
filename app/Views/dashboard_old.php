<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - Flawless Cup' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #5865f2;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #5865f2;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
            text-decoration: none;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-title {
            font-size: 2rem;
            color: #5865f2;
            margin-bottom: 20px;
        }

        .player-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #5865f2;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 1.1rem;
            color: #333;
        }

        .team-status {
            text-align: center;
            padding: 20px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            margin-top: 20px;
        }

        .team-status.default {
            background: #f8d7da;
            border-color: #f5c6cb;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .player-details {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">🏆 Flawless Cup</div>
            <div class="user-info">
                <?php if (isset($player['avatar']) && $player['avatar']): ?>
                    <img src="<?= esc($player['avatar']) ?>" alt="Avatar" class="avatar">
                <?php else: ?>
                    <div class="avatar" style="background: #5865f2; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        <?= strtoupper(substr($player['pseudo'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <span><?= esc($player['pseudo']) ?></span>
                <a href="/logout" class="logout-btn">Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <h1 class="welcome-title">Bienvenue, <?= esc($player['pseudo']) ?> !</h1>
            
            <div class="player-details">
                <div class="detail-item">
                    <div class="detail-label">Discord ID</div>
                    <div class="detail-value"><?= esc($player['discord_id']) ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Discord Tag</div>
                    <div class="detail-value"><?= esc($player['tag']) ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Pseudo</div>
                    <div class="detail-value"><?= esc($player['pseudo']) ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Avatar URL</div>
                    <div class="detail-value">
                        <?php if ($player['avatar']): ?>
                            <a href="<?= esc($player['avatar']) ?>" target="_blank" style="color: #5865f2; text-decoration: none;">
                                Voir l'avatar
                            </a>
                        <?php else: ?>
                            Aucun avatar
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Équipe</div>
                    <div class="detail-value"><?= esc($player['team_name'] ?? 'Aucune équipe') ?></div>
                </div>
                
                <?php if (isset($player['riot_id']) && $player['riot_id']): ?>
                <div class="detail-item">
                    <div class="detail-label">Riot ID</div>
                    <div class="detail-value"><?= esc($player['riot_id']) ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (isset($player['mmr']) && $player['mmr']): ?>
                <div class="detail-item">
                    <div class="detail-label">MMR</div>
                    <div class="detail-value"><?= esc($player['mmr']) ?></div>
                </div>
                <?php endif; ?>
                
                <div class="detail-item">
                    <div class="detail-label">Rôle</div>
                    <div class="detail-value"><?= $player['is_leader'] ? 'Leader d\'équipe' : 'Membre' ?></div>
                </div>
            </div>

            <?php if ($player['team_id'] === 'default_team'): ?>
            <div class="team-status default">
                <strong>⚠️ Vous n'avez pas encore d'équipe assignée</strong>
                <p>Contactez un administrateur pour être assigné à une équipe ou créer la vôtre.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Prochaines étapes</h2>
            <ul style="margin-top: 15px; line-height: 1.8;">
                <li>Rejoindre ou créer une équipe</li>
                <li>Configurer votre profil Riot Games</li>
                <li>Consulter le planning des matchs</li>
                <li>Participer aux tournois</li>
            </ul>
        </div>

        <!-- Section Debug (à retirer en production) -->
        <div class="card" style="background: #f8f9fa; border: 1px solid #dee2e6;">
            <h2 style="color: #6c757d;">🔧 Debug - Données Discord brutes (API)</h2>
            <?php if (isset($discord_raw_data) && $discord_raw_data): ?>
            <pre style="background: #e9ecef; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 0.9rem;">
<?= json_encode($discord_raw_data, JSON_PRETTY_PRINT) ?>
            </pre>
            <?php else: ?>
            <p style="color: #6c757d; font-style: italic;">Aucune donnée Discord brute disponible (reconnectez-vous pour les voir)</p>
            <?php endif; ?>
        </div>

        <div class="card" style="background: #f8f9fa; border: 1px solid #dee2e6;">
            <h2 style="color: #6c757d;">🔧 Debug - Données joueur sauvegardées</h2>
            <pre style="background: #e9ecef; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 0.9rem;">
<?= json_encode($player, JSON_PRETTY_PRINT) ?>
            </pre>
        </div>
    </div>
</body>
</html>