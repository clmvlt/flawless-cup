<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="futuristic-card">
                <div class="text-center mb-4">
                    <h1 class="text-glow">
                        <i class="fas fa-layer-group me-3 text-blue"></i>
                        Gestion des Poules
                    </h1>
                    <p class="text-secondary">Phase de poules du tournoi Flawless Cup</p>
                </div>

                <!-- Système d'onglets -->
                <ul class="nav nav-tabs nav-tabs-futuristic mb-4" id="poulesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="poules-tab" data-bs-toggle="tab" data-bs-target="#poules-content" type="button" role="tab">
                            <i class="fas fa-users me-2"></i>Poules de Qualification
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bracket-tab" data-bs-toggle="tab" data-bs-target="#bracket-content" type="button" role="tab">
                            <i class="fas fa-trophy me-2"></i>Bracket Final
                        </button>
                    </li>
                </ul>

                <!-- Contenu des onglets -->
                <div class="tab-content" id="poulesTabContent">
                    <!-- Premier onglet : Poules -->
                    <div class="tab-pane fade show active" id="poules-content" role="tabpanel">
                        <div class="row">
                            <!-- Poule A -->
                            <div class="col-md-6 mb-4">
                                <div class="poule-container">
                                    <h3 class="poule-title">
                                        <i class="fas fa-circle text-blue me-2"></i>Poule A
                                    </h3>
                                    <div class="poule-teams">
                                        <?php foreach ($pouleA as $index => $team): ?>
                                            <div class="team-row <?= ($allResultsComplete && $index < 2) ? 'qualified' : '' ?>">
                                                <div class="team-rank">
                                                    #<?= $index + 1 ?>
                                                </div>
                                                <div class="team-info">
                                                    <span class="team-name"><?= esc($team['nom']) ?></span>
                                                    <div class="team-stats">
                                                        <span class="points"><?= $team['points'] ?>pts</span>
                                                        <span class="record"><?= $team['victoires'] ?>V-<?= $team['defaites'] ?>D</span>
                                                        <span class="matches"><?= count($team['matches'] ?? []) ?> match<?= count($team['matches'] ?? []) > 1 ? 's' : '' ?></span>
                                                        <?php if (isset($team['membres'])): ?>
                                                            <span class="members"><?= $team['membres'] ?> membres</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="team-results">
                                                    <?php foreach ($team['resultats'] as $resultat): ?>
                                                        <span class="result-dash <?= $resultat === 'V' ? 'victory' : ($resultat === 'D' ? 'defeat' : '') ?>">
                                                            <?= $resultat ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <!-- Icône d'expansion -->
                                                <i class="fas fa-chevron-down team-expand-icon"></i>
                                            </div>
                                            
                                            <!-- Détails de l'équipe (accordéon) -->
                                            <div class="team-details">
                                                <div class="team-details-content">
                                                    <div class="team-details-header">
                                                        <h4 class="team-details-title">
                                                            <i class="fas fa-users me-2"></i>
                                                            Composition de <?= esc($team['nom']) ?>
                                                        </h4>
                                                        <p class="team-summary">
                                                            <?= count($team['players']) ?> joueur<?= count($team['players']) > 1 ? 's' : '' ?> • 
                                                            <?= $team['points'] ?> points • 
                                                            <?= $team['victoires'] ?>V-<?= $team['defaites'] ?>D
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="players-list">
                                                        <?php foreach ($team['players'] as $player): ?>
                                                            <div class="player-item">
                                                                <div class="player-basic-info">
                                                                    <div class="player-name">
                                                                        <i class="fas fa-user me-2"></i>
                                                                        <?= esc($player['pseudo'] ?? 'Joueur') ?>
                                                                    </div>
                                                                    
                                                                    <div class="player-discord">
                                                                        <i class="fab fa-discord"></i>
                                                                        <span><?= esc($player['discord_username'] ?? 'Non renseigné') ?></span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="player-rank">
                                                                    <?php if (isset($player['tier_id']) && $player['tier_id'] > 0): ?>
                                                                        <img src="<?= getRankIcon($player['tier_id']) ?>" 
                                                                             alt="Rank" style="width: 20px; height: 20px;">
                                                                        <span class="rank-rr"><?= esc($player['rr']) ?> RR</span>
                                                                    <?php else: ?>
                                                                        <span class="rank-badge non-classé">Non classé</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        
                                                        <?php if (empty($team['players'])): ?>
                                                            <div class="text-center text-secondary py-3">
                                                                <i class="fas fa-users-slash me-2"></i>
                                                                Aucun joueur enregistré dans cette équipe
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Section des matchs -->
                                                    <?php if (!empty($team['matches'])): ?>
                                                        <hr class="my-4" style="border-color: rgba(0, 201, 255, 0.3);">
                                                        <div class="matches-section">
                                                            <h5 class="text-primary mb-3">
                                                                <i class="fas fa-gamepad me-2"></i>
                                                                Matchs de <?= esc($team['nom']) ?>
                                                            </h5>
                                                            
                                                            <?php foreach ($team['matches'] as $match): ?>
                                                                <div class="match-item mb-3 p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-50">
                                                                    <div class="match-header mb-2">
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <div class="match-teams">
                                                                                <span class="text-cyan"><?= esc($match['team_a_name']) ?></span>
                                                                                <span class="text-white mx-2">VS</span>
                                                                                <span class="text-cyan"><?= esc($match['team_b_name']) ?></span>
                                                                            </div>
                                                                            <div class="match-info">
                                                                                <span class="badge <?= $match['is_tournament'] ? 'bg-warning text-dark' : 'bg-info' ?>">
                                                                                    <i class="fas <?= $match['is_tournament'] ? 'fa-trophy' : 'fa-gamepad' ?> me-1"></i>
                                                                                    <?= $match['is_tournament'] ? 'BO3' : 'Poule' ?>
                                                                                </span>
                                                                                <small class="text-muted ms-2">
                                                                                    <?= date('d/m/Y H:i', strtotime($match['match_date'])) ?>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if (!empty($match['games'])): ?>
                                                                        <div class="games-list">
                                                                            <?php foreach ($match['games'] as $index => $game): ?>
                                                                                <div class="game-result d-flex align-items-center justify-content-between py-2">
                                                                                    <div class="game-info-left">
                                                                                        <span class="badge bg-secondary">Game <?= $index + 1 ?></span>
                                                                                        <?php if (!empty($game['map'])): ?>
                                                                                            <span class="badge bg-info ms-2">
                                                                                                <i class="fas fa-map me-1"></i><?= esc($game['map']) ?>
                                                                                            </span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <?php if ($game['a_score'] !== null && $game['b_score'] !== null): ?>
                                                                                        <span class="score-display">
                                                                                            <span class="<?= $game['a_score'] > $game['b_score'] ? 'text-success' : 'text-danger' ?>">
                                                                                                <?= $game['a_score'] ?>
                                                                                            </span>
                                                                                            <span class="text-white mx-2">-</span>
                                                                                            <span class="<?= $game['b_score'] > $game['a_score'] ? 'text-success' : 'text-danger' ?>">
                                                                                                <?= $game['b_score'] ?>
                                                                                            </span>
                                                                                        </span>
                                                                                    <?php else: ?>
                                                                                        <span class="text-muted">
                                                                                            <i class="fas fa-clock me-1"></i>En attente
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="text-center text-muted py-2">
                                                                            <i class="fas fa-info-circle me-1"></i>
                                                                            Aucun game programmé
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <hr class="my-4" style="border-color: rgba(0, 201, 255, 0.3);">
                                                        <div class="text-center text-muted py-3">
                                                            <i class="fas fa-calendar-times me-2"></i>
                                                            Aucun match programmé pour cette équipe
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Poule B -->
                            <div class="col-md-6 mb-4">
                                <div class="poule-container poule-b">
                                    <h3 class="poule-title">
                                        <i class="fas fa-circle text-cyan me-2"></i>Poule B
                                    </h3>
                                    <div class="poule-teams">
                                        <?php foreach ($pouleB as $index => $team): ?>
                                            <div class="team-row poule-b-team <?= ($allResultsComplete && $index < 2) ? 'qualified' : '' ?>">
                                                <div class="team-rank">
                                                    #<?= $index + 1 ?>
                                                </div>
                                                <div class="team-info">
                                                    <span class="team-name"><?= esc($team['nom']) ?></span>
                                                    <div class="team-stats">
                                                        <span class="points"><?= $team['points'] ?>pts</span>
                                                        <span class="record"><?= $team['victoires'] ?>V-<?= $team['defaites'] ?>D</span>
                                                        <span class="matches"><?= count($team['matches'] ?? []) ?> match<?= count($team['matches'] ?? []) > 1 ? 's' : '' ?></span>
                                                        <?php if (isset($team['membres'])): ?>
                                                            <span class="members"><?= $team['membres'] ?> membres</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="team-results">
                                                    <?php foreach ($team['resultats'] as $resultat): ?>
                                                        <span class="result-dash <?= $resultat === 'V' ? 'victory' : ($resultat === 'D' ? 'defeat' : '') ?>">
                                                            <?= $resultat ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <!-- Icône d'expansion -->
                                                <i class="fas fa-chevron-down team-expand-icon"></i>
                                            </div>
                                            
                                            <!-- Détails de l'équipe (accordéon) -->
                                            <div class="team-details">
                                                <div class="team-details-content">
                                                    <div class="team-details-header">
                                                        <h4 class="team-details-title">
                                                            <i class="fas fa-users me-2"></i>
                                                            Composition de <?= esc($team['nom']) ?>
                                                        </h4>
                                                        <p class="team-summary">
                                                            <?= count($team['players']) ?> joueur<?= count($team['players']) > 1 ? 's' : '' ?> • 
                                                            <?= $team['points'] ?> points • 
                                                            <?= $team['victoires'] ?>V-<?= $team['defaites'] ?>D
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="players-list">
                                                        <?php foreach ($team['players'] as $player): ?>
                                                            <div class="player-item">
                                                                <div class="player-basic-info">
                                                                    <div class="player-name">
                                                                        <i class="fas fa-user me-2"></i>
                                                                        <?= esc($player['pseudo'] ?? 'Joueur') ?>
                                                                    </div>
                                                                    
                                                                    <div class="player-discord">
                                                                        <i class="fab fa-discord"></i>
                                                                        <span><?= esc($player['discord_username'] ?? 'Non renseigné') ?></span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="player-rank">
                                                                    <?php if (isset($player['tier_id']) && $player['tier_id'] > 0): ?>
                                                                        <img src="<?= getRankIcon($player['tier_id']) ?>" 
                                                                             alt="Rank" style="width: 20px; height: 20px;">
                                                                        <span class="rank-rr"><?= esc($player['rr']) ?> RR</span>
                                                                    <?php else: ?>
                                                                        <span class="rank-badge non-classé">Non classé</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        
                                                        <?php if (empty($team['players'])): ?>
                                                            <div class="text-center text-secondary py-3">
                                                                <i class="fas fa-users-slash me-2"></i>
                                                                Aucun joueur enregistré dans cette équipe
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Section des matchs -->
                                                    <?php if (!empty($team['matches'])): ?>
                                                        <hr class="my-4" style="border-color: rgba(0, 201, 255, 0.3);">
                                                        <div class="matches-section">
                                                            <h5 class="text-primary mb-3">
                                                                <i class="fas fa-gamepad me-2"></i>
                                                                Matchs de <?= esc($team['nom']) ?>
                                                            </h5>
                                                            
                                                            <?php foreach ($team['matches'] as $match): ?>
                                                                <div class="match-item mb-3 p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-50">
                                                                    <div class="match-header mb-2">
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <div class="match-teams">
                                                                                <span class="text-cyan"><?= esc($match['team_a_name']) ?></span>
                                                                                <span class="text-white mx-2">VS</span>
                                                                                <span class="text-cyan"><?= esc($match['team_b_name']) ?></span>
                                                                            </div>
                                                                            <div class="match-info">
                                                                                <span class="badge <?= $match['is_tournament'] ? 'bg-warning text-dark' : 'bg-info' ?>">
                                                                                    <i class="fas <?= $match['is_tournament'] ? 'fa-trophy' : 'fa-gamepad' ?> me-1"></i>
                                                                                    <?= $match['is_tournament'] ? 'BO3' : 'Poule' ?>
                                                                                </span>
                                                                                <small class="text-muted ms-2">
                                                                                    <?= date('d/m/Y H:i', strtotime($match['match_date'])) ?>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if (!empty($match['games'])): ?>
                                                                        <div class="games-list">
                                                                            <?php foreach ($match['games'] as $index => $game): ?>
                                                                                <div class="game-result d-flex align-items-center justify-content-between py-2">
                                                                                    <div class="game-info-left">
                                                                                        <span class="badge bg-secondary">Game <?= $index + 1 ?></span>
                                                                                        <?php if (!empty($game['map'])): ?>
                                                                                            <span class="badge bg-info ms-2">
                                                                                                <i class="fas fa-map me-1"></i><?= esc($game['map']) ?>
                                                                                            </span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <?php if ($game['a_score'] !== null && $game['b_score'] !== null): ?>
                                                                                        <span class="score-display">
                                                                                            <span class="<?= $game['a_score'] > $game['b_score'] ? 'text-success' : 'text-danger' ?>">
                                                                                                <?= $game['a_score'] ?>
                                                                                            </span>
                                                                                            <span class="text-white mx-2">-</span>
                                                                                            <span class="<?= $game['b_score'] > $game['a_score'] ? 'text-success' : 'text-danger' ?>">
                                                                                                <?= $game['b_score'] ?>
                                                                                            </span>
                                                                                        </span>
                                                                                    <?php else: ?>
                                                                                        <span class="text-muted">
                                                                                            <i class="fas fa-clock me-1"></i>En attente
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="text-center text-muted py-2">
                                                                            <i class="fas fa-info-circle me-1"></i>
                                                                            Aucun game programmé
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <hr class="my-4" style="border-color: rgba(0, 201, 255, 0.3);">
                                                        <div class="text-center text-muted py-3">
                                                            <i class="fas fa-calendar-times me-2"></i>
                                                            Aucun match programmé pour cette équipe
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Équipes sans poule -->
                        <?php if (!empty($teamsWithoutPoule)): ?>
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="poule-container teams-without-poule">
                                    <h3 class="poule-title">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Équipes sans poule
                                    </h3>
                                    <p class="text-secondary mb-4">
                                        Ces équipes ne sont pas encore assignées à une poule. Les administrateurs peuvent les assigner via l'interface d'administration.
                                    </p>
                                    <div class="poule-teams">
                                        <?php foreach ($teamsWithoutPoule as $index => $team): ?>
                                            <div class="team-row no-poule">
                                                <div class="team-rank">
                                                    <i class="fas fa-question text-warning"></i>
                                                </div>
                                                <div class="team-info">
                                                    <span class="team-name"><?= esc($team['nom']) ?></span>
                                                    <div class="team-stats">
                                                        <span class="members"><?= $team['membres'] ?> membre<?= $team['membres'] > 1 ? 's' : '' ?></span>
                                                        <span class="status-badge">En attente d'assignation</span>
                                                    </div>
                                                </div>
                                                <div class="team-results">
                                                    <span class="result-dash pending">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                                </div>
                                                
                                                <!-- Icône d'expansion -->
                                                <i class="fas fa-chevron-down team-expand-icon"></i>
                                            </div>
                                            
                                            <!-- Détails de l'équipe (accordéon) -->
                                            <div class="team-details">
                                                <div class="team-details-content">
                                                    <div class="team-details-header">
                                                        <h4 class="team-details-title">
                                                            <i class="fas fa-users me-2"></i>
                                                            Composition de <?= esc($team['nom']) ?>
                                                        </h4>
                                                        <p class="team-summary">
                                                            <?= count($team['players']) ?> joueur<?= count($team['players']) > 1 ? 's' : '' ?> • 
                                                            <span class="text-warning">Non assignée à une poule</span>
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="players-list">
                                                        <?php foreach ($team['players'] as $player): ?>
                                                            <div class="player-item">
                                                                <div class="player-basic-info">
                                                                    <div class="player-name">
                                                                        <i class="fas fa-user me-2"></i>
                                                                        <?= esc($player['pseudo'] ?? 'Joueur') ?>
                                                                    </div>
                                                                    
                                                                    <div class="player-discord">
                                                                        <i class="fab fa-discord"></i>
                                                                        <span><?= esc($player['discord_username'] ?? 'Non renseigné') ?></span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="player-rank">
                                                                    <?php if (isset($player['tier_id']) && $player['tier_id'] > 0): ?>
                                                                        <img src="<?= getRankIcon($player['tier_id']) ?>" 
                                                                             alt="Rank" style="width: 20px; height: 20px;">
                                                                        <span class="rank-rr"><?= esc($player['rr']) ?> RR</span>
                                                                    <?php else: ?>
                                                                        <span class="rank-badge non-classé">Non classé</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        
                                                        <?php if (empty($team['players'])): ?>
                                                            <div class="text-center text-secondary py-3">
                                                                <i class="fas fa-users-slash me-2"></i>
                                                                Aucun joueur enregistré dans cette équipe
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Section des matchs -->
                                                    <?php if (!empty($team['matches'])): ?>
                                                        <hr class="my-4" style="border-color: rgba(0, 201, 255, 0.3);">
                                                        <div class="matches-section">
                                                            <h5 class="text-primary mb-3">
                                                                <i class="fas fa-gamepad me-2"></i>
                                                                Matchs de <?= esc($team['nom']) ?>
                                                            </h5>
                                                            
                                                            <?php foreach ($team['matches'] as $match): ?>
                                                                <div class="match-item mb-3 p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-50">
                                                                    <div class="match-header mb-2">
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <div class="match-teams">
                                                                                <span class="text-cyan"><?= esc($match['team_a_name']) ?></span>
                                                                                <span class="text-white mx-2">VS</span>
                                                                                <span class="text-cyan"><?= esc($match['team_b_name']) ?></span>
                                                                            </div>
                                                                            <div class="match-info">
                                                                                <span class="badge <?= $match['is_tournament'] ? 'bg-warning text-dark' : 'bg-info' ?>">
                                                                                    <i class="fas <?= $match['is_tournament'] ? 'fa-trophy' : 'fa-gamepad' ?> me-1"></i>
                                                                                    <?= $match['is_tournament'] ? 'BO3' : 'Poule' ?>
                                                                                </span>
                                                                                <small class="text-muted ms-2">
                                                                                    <?= date('d/m/Y H:i', strtotime($match['match_date'])) ?>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if (!empty($match['games'])): ?>
                                                                        <div class="games-list">
                                                                            <?php foreach ($match['games'] as $index => $game): ?>
                                                                                <div class="game-result d-flex align-items-center justify-content-between py-2">
                                                                                    <div class="game-info-left">
                                                                                        <span class="badge bg-secondary">Game <?= $index + 1 ?></span>
                                                                                        <?php if (!empty($game['map'])): ?>
                                                                                            <span class="badge bg-info ms-2">
                                                                                                <i class="fas fa-map me-1"></i><?= esc($game['map']) ?>
                                                                                            </span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <?php if ($game['a_score'] !== null && $game['b_score'] !== null): ?>
                                                                                        <span class="score-display">
                                                                                            <span class="<?= $game['a_score'] > $game['b_score'] ? 'text-success' : 'text-danger' ?>">
                                                                                                <?= $game['a_score'] ?>
                                                                                            </span>
                                                                                            <span class="text-white mx-2">-</span>
                                                                                            <span class="<?= $game['b_score'] > $game['a_score'] ? 'text-success' : 'text-danger' ?>">
                                                                                                <?= $game['b_score'] ?>
                                                                                            </span>
                                                                                        </span>
                                                                                    <?php else: ?>
                                                                                        <span class="text-muted">
                                                                                            <i class="fas fa-clock me-1"></i>En attente
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="text-center text-muted py-2">
                                                                            <i class="fas fa-info-circle me-1"></i>
                                                                            Aucun game programmé
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <hr class="my-4" style="border-color: rgba(0, 201, 255, 0.3);">
                                                        <div class="text-center text-muted py-3">
                                                            <i class="fas fa-calendar-times me-2"></i>
                                                            Aucun match programmé pour cette équipe
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Légende -->
                        <div class="alert alert-info-futuristic mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instructions :</strong> Les résultats des matchs seront mis à jour manuellement par les administrateurs. 
                            Chaque tiret (-) représente un match à jouer. Les 2 meilleures équipes de chaque poule se qualifieront pour les phases finales.
                            <br><small class="mt-2 d-block">
                                <strong>Total :</strong> <?= $totalTeams ?> équipes inscrites 
                                <?php if (!empty($teamsWithoutPoule)): ?>
                                    • <span class="text-warning"><?= count($teamsWithoutPoule) ?> équipe<?= count($teamsWithoutPoule) > 1 ? 's' : '' ?> sans poule</span>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>

                    <!-- Deuxième onglet : Bracket Final -->
                    <div class="tab-pane fade" id="bracket-content" role="tabpanel">
                        <div class="bracket-container">
                            <div class="text-center mb-4">
                                <h3 class="text-glow">
                                    <i class="fas fa-trophy me-2 text-yellow"></i>
                                    Phase Finale - Élimination Directe
                                </h3>
                                <p class="text-secondary">Les 4 meilleures équipes s'affrontent pour le titre</p>
                            </div>

                            <!-- Tournament Tree Bracket -->
                            <div class="tournament-tree">
                                <div class="bracket-grid">
                                    <!-- Demi-finales (Round 1) -->
                                    <div class="bracket-column semifinals">
                                        <div class="round-title-header">
                                            <h4>Demi-finales</h4>
                                        </div>
                                        
                                        <!-- Demi-finale 1 -->
                                        <?php if (!empty($tournamentMatches['semifinals'][0])): ?>
                                            <?php $sf1 = $tournamentMatches['semifinals'][0]; ?>
                                            <div class="tree-match <?= $sf1['is_completed'] ? 'completed' : 'upcoming' ?>" data-match="sf1">
                                                <div class="team-entry poule-a-team <?= $sf1['winner'] === 'team_a' ? 'winner' : ($sf1['winner'] === 'team_b' ? 'loser' : '') ?>">
                                                    <span class="seed">#1A</span>
                                                    <span class="team"><?= esc($sf1['team_a_name']) ?></span>
                                                    <span class="score"><?= $sf1['is_completed'] ? count(array_filter($sf1['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['a_score'] > $g['b_score']; })) : '-' ?></span>
                                                </div>
                                                <div class="team-entry poule-b-team <?= $sf1['winner'] === 'team_b' ? 'winner' : ($sf1['winner'] === 'team_a' ? 'loser' : '') ?>">
                                                    <span class="seed poule-b-seed">#2B</span>
                                                    <span class="team"><?= esc($sf1['team_b_name']) ?></span>
                                                    <span class="score"><?= $sf1['is_completed'] ? count(array_filter($sf1['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['b_score'] > $g['a_score']; })) : '-' ?></span>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="tree-match upcoming" data-match="sf1">
                                                <div class="team-entry poule-a-team">
                                                    <span class="seed">#1A</span>
                                                    <span class="team">En attente</span>
                                                    <span class="score">-</span>
                                                </div>
                                                <div class="team-entry poule-b-team">
                                                    <span class="seed poule-b-seed">#2B</span>
                                                    <span class="team">En attente</span>
                                                    <span class="score">-</span>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Spacer -->
                                        <div class="match-spacer"></div>

                                        <!-- Demi-finale 2 -->
                                        <?php if (!empty($tournamentMatches['semifinals'][1])): ?>
                                            <?php $sf2 = $tournamentMatches['semifinals'][1]; ?>
                                            <div class="tree-match <?= $sf2['is_completed'] ? 'completed' : 'upcoming' ?>" data-match="sf2">
                                                <div class="team-entry poule-b-team <?= $sf2['winner'] === 'team_a' ? 'winner' : ($sf2['winner'] === 'team_b' ? 'loser' : '') ?>">
                                                    <span class="seed poule-b-seed">#1B</span>
                                                    <span class="team"><?= esc($sf2['team_a_name']) ?></span>
                                                    <span class="score"><?= $sf2['is_completed'] ? count(array_filter($sf2['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['a_score'] > $g['b_score']; })) : '-' ?></span>
                                                </div>
                                                <div class="team-entry poule-a-team <?= $sf2['winner'] === 'team_b' ? 'winner' : ($sf2['winner'] === 'team_a' ? 'loser' : '') ?>">
                                                    <span class="seed">#2A</span>
                                                    <span class="team"><?= esc($sf2['team_b_name']) ?></span>
                                                    <span class="score"><?= $sf2['is_completed'] ? count(array_filter($sf2['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['b_score'] > $g['a_score']; })) : '-' ?></span>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="tree-match upcoming" data-match="sf2">
                                                <div class="team-entry poule-b-team">
                                                    <span class="seed poule-b-seed">#1B</span>
                                                    <span class="team">En attente</span>
                                                    <span class="score">-</span>
                                                </div>
                                                <div class="team-entry poule-a-team">
                                                    <span class="seed">#2A</span>
                                                    <span class="team">En attente</span>
                                                    <span class="score">-</span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Connecteurs visuels -->
                                    <div class="bracket-connectors">
                                        <svg class="connection-svg" viewBox="0 0 100 300">
                                            <!-- Ligne du match 1 vers finale -->
                                            <path d="M 0 75 L 50 75 L 50 150 L 100 150" 
                                                  stroke="rgba(0, 201, 255, 0.6)" 
                                                  stroke-width="2" 
                                                  fill="none"/>
                                            <!-- Ligne du match 2 vers finale -->
                                            <path d="M 0 225 L 50 225 L 50 150 L 100 150" 
                                                  stroke="rgba(0, 201, 255, 0.6)" 
                                                  stroke-width="2" 
                                                  fill="none"/>
                                        </svg>
                                    </div>

                                    <!-- Finale -->
                                    <div class="bracket-column finals">
                                        <div class="round-title-header">
                                            <h4>Finale</h4>
                                        </div>

                                        <div class="finals-container">
                                            <!-- Grande Finale centree par rapport aux demi-finales -->
                                            <?php if (!empty($tournamentMatches['final'])): ?>
                                                <?php $final = $tournamentMatches['final']; ?>
                                                <div class="tree-match champion <?= $final['is_completed'] ? 'completed' : 'upcoming' ?>" data-match="final" style="margin-top: -1rem;">
                                                    <div class="match-label">FINALE</div>
                                                    <div class="team-entry bracket-final-team <?= $final['winner'] === 'team_a' ? 'winner' : ($final['winner'] === 'team_b' ? 'loser' : '') ?>" data-team-name="<?= esc($final['team_a_name']) ?>">
                                                        <span class="seed">W1</span>
                                                        <span class="team"><?= esc($final['team_a_name']) ?></span>
                                                        <span class="score"><?= $final['is_completed'] ? count(array_filter($final['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['a_score'] > $g['b_score']; })) : '-' ?></span>
                                                    </div>
                                                    <div class="team-entry bracket-final-team <?= $final['winner'] === 'team_b' ? 'winner' : ($final['winner'] === 'team_a' ? 'loser' : '') ?>" data-team-name="<?= esc($final['team_b_name']) ?>">
                                                        <span class="seed">W2</span>
                                                        <span class="team"><?= esc($final['team_b_name']) ?></span>
                                                        <span class="score"><?= $final['is_completed'] ? count(array_filter($final['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['b_score'] > $g['a_score']; })) : '-' ?></span>
                                                    </div>
                                                    <div class="prize-indicator">
                                                        <i class="fas fa-trophy"></i>
                                                        100€
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="tree-match champion upcoming" data-match="final" style="margin-top: -1rem;">
                                                    <div class="match-label">FINALE</div>
                                                    <div class="team-entry">
                                                        <span class="seed">W1</span>
                                                        <span class="team">Gagnant SF1</span>
                                                        <span class="score">-</span>
                                                    </div>
                                                    <div class="team-entry">
                                                        <span class="seed">W2</span>
                                                        <span class="team">Gagnant SF2</span>
                                                        <span class="score">-</span>
                                                    </div>
                                                    <div class="prize-indicator">
                                                        <i class="fas fa-trophy"></i>
                                                        100€
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Petite Finale (3ème place) -->
                                            <?php if (!empty($tournamentMatches['third_place'])): ?>
                                                <?php $thirdPlace = $tournamentMatches['third_place']; ?>
                                                <div class="tree-match third-place <?= $thirdPlace['is_completed'] ? 'completed' : 'upcoming' ?>" data-match="third" style="margin-top: 1rem;">
                                                    <div class="match-label">3ÈME PLACE</div>
                                                    <div class="team-entry bracket-final-team <?= $thirdPlace['winner'] === 'team_a' ? 'winner' : ($thirdPlace['winner'] === 'team_b' ? 'loser' : '') ?>" data-team-name="<?= esc($thirdPlace['team_a_name']) ?>">
                                                        <span class="seed">L1</span>
                                                        <span class="team"><?= esc($thirdPlace['team_a_name']) ?></span>
                                                        <span class="score"><?= $thirdPlace['is_completed'] ? count(array_filter($thirdPlace['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['a_score'] > $g['b_score']; })) : '-' ?></span>
                                                    </div>
                                                    <div class="team-entry bracket-final-team <?= $thirdPlace['winner'] === 'team_b' ? 'winner' : ($thirdPlace['winner'] === 'team_a' ? 'loser' : '') ?>" data-team-name="<?= esc($thirdPlace['team_b_name']) ?>">
                                                        <span class="seed">L2</span>
                                                        <span class="team"><?= esc($thirdPlace['team_b_name']) ?></span>
                                                        <span class="score"><?= $thirdPlace['is_completed'] ? count(array_filter($thirdPlace['games'], function($g) { return $g['a_score'] !== null && $g['b_score'] !== null && $g['b_score'] > $g['a_score']; })) : '-' ?></span>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="tree-match third-place upcoming" data-match="third" style="margin-top: 1rem;">
                                                    <div class="match-label">3ÈME PLACE</div>
                                                    <div class="team-entry">
                                                        <span class="seed">L1</span>
                                                        <span class="team">Perdant SF1</span>
                                                        <span class="score">-</span>
                                                    </div>
                                                    <div class="team-entry">
                                                        <span class="seed">L2</span>
                                                        <span class="team">Perdant SF2</span>
                                                        <span class="score">-</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Légende -->
                            <div class="bracket-legend">
                                <div class="legend-item">
                                    <span class="status-indicator completed"></span>
                                    <span>Match terminé</span>
                                </div>
                                <div class="legend-item">
                                    <span class="status-indicator live"></span>
                                    <span>Match en cours</span>
                                </div>
                                <div class="legend-item">
                                    <span class="status-indicator upcoming"></span>
                                    <span>Match à venir</span>
                                </div>
                                <div class="legend-item">
                                    <span class="status-indicator winner"></span>
                                    <span>Équipe qualifiée</span>
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
/* Tournament Tree Styles */
.bracket-container {
    padding: 2rem 1rem;
}

.tournament-tree {
    margin: 2rem 0;
    overflow-x: visible;
    min-height: 400px;
    width: 100%;
}

.bracket-grid {
    display: grid;
    grid-template-columns: 1fr 120px 1fr;
    gap: 0;
    width: 100%;
    align-items: center;
    height: 400px;
}

/* Column Headers */
.round-title-header {
    text-align: center;
    margin-bottom: 2rem;
}

.round-title-header h4 {
    color: var(--text-primary);
    font-size: 1.3rem;
    text-shadow: 0 0 10px rgba(0, 201, 255, 0.5);
    margin: 0;
}

/* Bracket Columns */
.bracket-column {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}

.semifinals {
    justify-content: space-around;
}

/* Match Spacing */
.match-spacer {
    height: 60px;
}

/* Tree Matches */
.tree-match {
    background: rgba(10, 10, 20, 0.9);
    border: 2px solid rgba(0, 201, 255, 0.3);
    border-radius: 8px;
    margin: 8px 0;
    position: relative;
    transition: all 0.3s ease;
    overflow: hidden;
    font-size: 0.9rem;
}

.tree-match:hover {
    border-color: rgba(0, 201, 255, 0.6);
    box-shadow: 0 5px 15px rgba(0, 201, 255, 0.2);
    transform: scale(1.02);
}

/* Match Status */
.tree-match.completed {
    border-color: rgba(0, 255, 100, 0.4);
    background: rgba(10, 25, 15, 0.9);
}

.tree-match.live {
    border-color: rgba(255, 165, 0, 0.6);
    background: rgba(25, 20, 10, 0.9);
    animation: pulse 2s infinite;
}

.tree-match.upcoming {
    border-color: rgba(150, 150, 150, 0.4);
    background: rgba(20, 20, 25, 0.9);
}

.tree-match.champion {
    border-color: rgba(255, 215, 0, 0.8);
    background: rgba(30, 25, 10, 0.95);
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
}

.tree-match.third-place {
    border-color: rgba(205, 127, 50, 0.6);
    background: rgba(25, 18, 10, 0.9);
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 8px rgba(255, 165, 0, 0.4); }
    50% { box-shadow: 0 0 20px rgba(255, 165, 0, 0.8); }
}

/* Match Labels */
.match-label {
    background: rgba(0, 201, 255, 0.2);
    color: var(--primary-blue);
    text-align: center;
    padding: 0.3rem;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.tree-match.champion .match-label {
    background: rgba(255, 215, 0, 0.3);
    color: #ffd700;
}

.tree-match.third-place .match-label {
    background: rgba(205, 127, 50, 0.3);
    color: #cd7f32;
}

/* Team Entries */
.team-entry {
    display: flex;
    align-items: center;
    padding: 0.6rem 0.8rem;
    border-bottom: 1px solid rgba(0, 201, 255, 0.1);
    transition: all 0.3s ease;
}

.team-entry:last-child {
    border-bottom: none;
}

.team-entry.winner {
    background: rgba(0, 255, 100, 0.1);
    border-left: 4px solid #00ff64;
}

.team-entry.loser {
    opacity: 0.6;
    background: rgba(255, 50, 50, 0.05);
}

/* Team Elements */
.seed {
    background: var(--primary-blue);
    color: white;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: bold;
    min-width: 30px;
    text-align: center;
    margin-right: 0.6rem;
}

.team {
    flex-grow: 1;
    color: var(--text-primary);
    font-weight: 500;
    font-size: 0.85rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.score {
    color: var(--accent-cyan);
    font-weight: bold;
    font-size: 1rem;
    margin-left: 0.4rem;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
    min-width: 20px;
    text-align: center;
}

/* Prize Indicator */
.prize-indicator {
    text-align: center;
    padding: 0.5rem;
    background: rgba(255, 215, 0, 0.1);
    border-top: 1px solid rgba(255, 215, 0, 0.3);
    color: #ffd700;
    font-weight: bold;
    font-size: 0.9rem;
}

.prize-indicator i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

/* SVG Connectors */
.bracket-connectors {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    position: relative;
}

.connection-svg {
    width: 100%;
    height: 300px;
}

/* Finals Container */
.finals-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.finals .tree-match {
    min-width: 220px;
    max-width: 280px;
}

/* Legend */
.bracket-legend {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 3rem;
    padding: 1rem;
    background: rgba(15, 15, 25, 0.6);
    border-radius: 12px;
    border: 1px solid rgba(0, 201, 255, 0.1);
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-indicator.completed {
    background: #00ff64;
    box-shadow: 0 0 5px rgba(0, 255, 100, 0.5);
}

.status-indicator.live {
    background: #ffa500;
    box-shadow: 0 0 5px rgba(255, 165, 0, 0.5);
    animation: pulse 2s infinite;
}

.status-indicator.upcoming {
    background: #999999;
}

.status-indicator.winner {
    background: var(--primary-blue);
    box-shadow: 0 0 5px rgba(0, 201, 255, 0.5);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .bracket-grid {
        grid-template-columns: 1fr 100px 1fr;
    }
    
    .finals .tree-match {
        min-width: 200px;
        max-width: 250px;
    }
    
    .team {
        font-size: 0.8rem;
    }
    
    .seed {
        font-size: 0.65rem;
        min-width: 28px;
    }
}

@media (max-width: 768px) {
    .bracket-grid {
        grid-template-columns: 1fr;
        grid-template-rows: auto auto auto;
        gap: 2rem;
        height: auto;
        min-width: 300px;
    }
    
    .bracket-connectors {
        display: none;
    }
    
    .finals-container {
        flex-direction: column;
    }
    
    .bracket-legend {
        flex-direction: column;
        gap: 1rem;
    }
    
    .semifinals {
        justify-content: flex-start;
        gap: 1rem;
    }
    
    .match-spacer {
        display: none;
    }
}

/* Styles pour la section Équipes sans poule */
.teams-without-poule {
    border: 2px dashed rgba(255, 193, 7, 0.4) !important;
    background: rgba(25, 20, 10, 0.3) !important;
}

.teams-without-poule .poule-title {
    color: var(--accent-yellow) !important;
    text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}

.team-row.no-poule {
    background: rgba(25, 20, 10, 0.6) !important;
    border-left: 4px solid #ffc107 !important;
}

.team-row.no-poule:hover {
    background: rgba(35, 25, 15, 0.8) !important;
}

.status-badge {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.result-dash.pending {
    background: rgba(255, 193, 7, 0.2) !important;
    color: #ffc107 !important;
    border: 2px solid rgba(255, 193, 7, 0.4) !important;
}

.result-dash.pending i {
    font-size: 0.9rem;
}

/* Styles pour les sections de matchs */
.matches-section .match-item {
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 201, 255, 0.3) !important;
}

.matches-section .match-item:hover {
    border-color: rgba(0, 201, 255, 0.6) !important;
    transform: translateX(5px);
}

.match-header .match-teams {
    font-weight: 500;
}

.match-info .badge {
    font-size: 0.75rem;
}

.games-list .game-result {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.games-list .game-result:last-child {
    border-bottom: none;
}

.score-display {
    font-weight: bold;
}

.game-info-left {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}
    font-size: 1.1rem;
}

.matches .badge {
    background: rgba(0, 201, 255, 0.2);
    color: var(--primary-blue);
    border: 1px solid rgba(0, 201, 255, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page des poules chargée - Mode administrateur uniquement');
    
    // Gérer l'expansion/réduction des équipes
    document.querySelectorAll('.team-expand-icon').forEach(function(icon) {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const teamRow = this.closest('.team-row');
            const teamDetails = teamRow.nextElementSibling;
            
            if (teamRow.classList.contains('expanded')) {
                // Réduire
                teamRow.classList.remove('expanded');
                teamDetails.classList.remove('expanded');
            } else {
                // Étendre
                teamRow.classList.add('expanded');
                teamDetails.classList.add('expanded');
            }
        });
    });
    
    // Rendre les team-row cliquables aussi
    document.querySelectorAll('.team-row').forEach(function(row) {
        row.addEventListener('click', function(e) {
            // Ne pas déclencher si on clique sur l'icône (déjà géré au-dessus)
            if (e.target.classList.contains('team-expand-icon')) return;
            
            const expandIcon = this.querySelector('.team-expand-icon');
            if (expandIcon) {
                expandIcon.click();
            }
        });
    });
    
    // Appliquer les couleurs de poule aux équipes dans le bracket final
    applyPouleColorsToFinals();
});

function applyPouleColorsToFinals() {
    // Récupérer toutes les équipes de la Poule B
    const pouleBTeams = [];
    document.querySelectorAll('.poule-b .team-name').forEach(function(teamNameEl) {
        pouleBTeams.push(teamNameEl.textContent.trim());
    });
    
    // Appliquer les styles rouge aux équipes de la Poule B dans le bracket final
    document.querySelectorAll('.bracket-final-team').forEach(function(teamEntry) {
        const teamName = teamEntry.getAttribute('data-team-name');
        if (teamName && pouleBTeams.includes(teamName)) {
            // Appliquer le style Poule B
            teamEntry.classList.add('poule-b-team');
            const seedElement = teamEntry.querySelector('.seed');
            if (seedElement) {
                seedElement.classList.add('poule-b-seed');
            }
        }
    });
}
</script>

<?= $this->endSection() ?>