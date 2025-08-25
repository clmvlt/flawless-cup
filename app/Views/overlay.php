<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valorant Rank Overlay - <?= esc($account['name']) ?>#<?= esc($account['tag']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: transparent;
            color: white;
            overflow: hidden;
        }

        .overlay-container {
            position: fixed;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            min-width: 280px;
            max-width: 320px;
        }

        .player-info {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .player-name {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
        }

        .player-tag {
            font-size: 14px;
            color: #ff4655;
            margin-left: 3px;
        }

        .rank-container {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .rank-icon {
            width: 50px;
            height: 50px;
            margin-right: 12px;
        }

        .rank-details {
            flex-grow: 1;
        }

        .rank-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .rr-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 3px 6px;
            border-radius: 4px;
            margin-top: 4px;
        }

        .rr-text {
            font-size: 13px;
            color: #00ff88;
            font-weight: 600;
        }

        .error-container {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            text-align: center;
            color: #ff4655;
            font-size: 13px;
        }

    </style>
</head>
<body>
    <div class="overlay-container">
        <?php if ($success): ?>
            <div class="rank-container">
                <img src="<?= esc($rank['icon']) ?>" alt="Rank Icon" class="rank-icon">
                <div class="rank-details">
                    <div class="rank-name" style="color: <?= esc($rank['color']) ?>">
                        <?= esc($rank['name']) ?>
                    </div>
                    <div class="rr-container">
                        <span class="rr-text"><?= esc($rr) ?> RR</span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="error-container">
                <p><?= isset($error) ? esc($error) : 'Données indisponibles' ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        setInterval(() => location.reload(), 60000);
    </script>
</body>
</html>