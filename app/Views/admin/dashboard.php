<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
/* SOLUTION DRASTIQUE POUR LES MODALS ADMIN */

/* Désactiver TOUS les éléments décoratifs en permanence sur la page admin */
.cursor-trail-container,
.grid-background,
.diagonal-lines,
.particles {
    display: none !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

/* S'assurer que les modals sont au-dessus de tout */
body.modal-open {
    /* Garder le scroll disponible */
}

/* Centrage parfait pour tous les modals */
.modal.show .modal-dialog,
.simple-modal.show .simple-modal-content {
    transform: none !important;
}

/* Protection contre les éléments qui pourraient passer au-dessus */
.simple-modal, 
.modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
}

/* Permettre le scroll même quand un modal est ouvert */
body {
    /* Pas de restriction de scroll */
}

/* Forcer la visibilité complète du modal */
.simple-modal.show {
    display: flex !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Centrage visible dans la viewport actuelle */
.simple-modal-content {
    margin: auto !important;
    max-height: 80vh !important;
    overflow-y: auto !important;
}

/* S'assurer que rien ne peut masquer le modal */
.simple-modal.show,
.simple-modal.show * {
    visibility: visible !important;
    opacity: 1 !important;
}

/* Modal styles simples et fonctionnels */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.9) !important;
    z-index: 9998 !important;
}

.modal {
    z-index: 9999 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
}

.modal-dialog {
    z-index: 10000 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    max-width: 600px !important;
}

.modal-content {
    background-color: #212529 !important;
    border: 2px solid #0d6efd !important;
    border-radius: 8px !important;
}

.modal-content.border-warning {
    border-color: #ffc107 !important;
}

.modal-header {
    background-color: #343a40 !important;
    border-bottom: 1px solid #495057 !important;
}

.modal-body {
    background-color: #212529 !important;
    color: #ffffff !important;
}

.modal-footer {
    background-color: #343a40 !important;
    border-top: 1px solid #495057 !important;
}

/* Styles pour les formulaires dans les modals */
.modal .form-control {
    background-color: #343a40 !important;
    border: 1px solid #495057 !important;
    color: #ffffff !important;
}

.modal .form-control:focus {
    background-color: #343a40 !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.modal select.form-control option {
    background-color: #343a40 !important;
    color: #ffffff !important;
}

/* Styles pour les form-select (menus déroulants) dans les modals */
.modal .form-select,
.simple-modal .form-select {
    background-color: #2a2a2a !important;
    border: 1px solid #444 !important;
    color: #fff !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
}

.modal .form-select:focus,
.simple-modal .form-select:focus {
    background-color: #2a2a2a !important;
    border-color: #0d6efd !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}

.modal .form-select option,
.simple-modal .form-select option {
    background-color: #2a2a2a !important;
    color: #fff !important;
}

/* Styles pour tous les selects dans toute la page admin */
select.form-select {
    background-color: #2a2a2a !important;
    border: 1px solid #444 !important;
    color: #fff !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
}

select.form-select:focus {
    background-color: #2a2a2a !important;
    border-color: #0d6efd !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}

select.form-select option {
    background-color: #2a2a2a !important;
    color: #fff !important;
}

/* MODALS SIMPLES - CSS PUR */
.simple-modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    display: none !important; /* Masqué par défaut */
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
    box-sizing: border-box !important;
    pointer-events: auto !important;
    inset: 0 !important;
    /* Centrage parfait dans le viewport visible */
    margin: 0 !important;
    overflow: hidden !important; /* Empêcher le scroll du modal lui-même */
}

.simple-modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(0, 0, 0, 0.85) !important;
    z-index: 10000 !important;
    inset: 0 !important;
}

.simple-modal-content {
    position: relative !important;
    background: #212529 !important;
    border: 2px solid #0d6efd !important;
    border-radius: 8px !important;
    width: 100% !important;
    max-width: 600px !important;
    max-height: 90vh !important;
    overflow-y: auto !important; /* Permettre le scroll dans le contenu du modal */
    z-index: 10001 !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8) !important;
    transform: translateY(0) !important;
    animation: modalFadeIn 0.2s ease-out !important;
    margin: 0 !important;
    flex-shrink: 0 !important;
    /* Centrage parfait sans dépendance au scroll */
    align-self: center !important;
    justify-self: center !important;
    /* Améliorer le scroll dans le modal */
    scrollbar-width: thin !important;
    scrollbar-color: var(--primary-blue) var(--bg-darker) !important;
}

/* Scrollbar personnalisée pour les modals */
.simple-modal-content::-webkit-scrollbar {
    width: 8px !important;
}

.simple-modal-content::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1) !important;
    border-radius: 4px !important;
}

.simple-modal-content::-webkit-scrollbar-thumb {
    background: var(--primary-blue) !important;
    border-radius: 4px !important;
}

.simple-modal-content::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-blue) !important;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Fallback pour les navigateurs plus anciens */
.simple-modal.show {
    display: flex !important;
}

/* Responsive pour les petits écrans */
@media (max-width: 768px) {
    .simple-modal {
        padding: 10px;
    }
    
    .simple-modal-content {
        width: 100%;
        max-width: none;
        max-height: 90vh;
    }
    
    .simple-modal-header,
    .simple-modal-body,
    .simple-modal-footer {
        padding: 15px;
    }
}

/* Centrage vertical parfait - Version améliorée */
/* État par défaut : masqué */
.simple-modal {
    display: none !important;
}

/* État visible : centré parfaitement */
.simple-modal.show {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    /* Empêcher tout défilement sur le modal parent */
    scroll-behavior: auto !important;
}

.simple-modal-header {
    padding: 15px 20px;
    background: #343a40;
    border-bottom: 1px solid #495057;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.simple-modal-header h5 {
    margin: 0;
    font-weight: 600;
}

.simple-close-btn {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.simple-close-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Styles pour la gestion du bracket */
.bracket-preview {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(0, 201, 255, 0.2);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.bracket-match-card {
    background: rgba(15, 15, 25, 0.8);
    border: 1px solid rgba(0, 201, 255, 0.2);
    border-radius: 6px;
    padding: 1rem;
    text-align: center;
}

.bracket-match-teams {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin: 0.5rem 0;
}

.team-placeholder {
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 0.9rem;
}

.bracket-actions {
    text-align: center;
}

.simple-modal-body {
    padding: 20px;
    color: #ffffff;
}

.simple-modal-footer {
    padding: 15px 20px;
    background: #343a40;
    border-top: 1px solid #495057;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Styles pour les formulaires dans les modals simples */
.simple-modal .form-control {
    background-color: #343a40 !important;
    border: 1px solid #495057 !important;
    color: #ffffff !important;
    padding: 8px 12px;
}

.simple-modal .form-control:focus {
    background-color: #343a40 !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
    outline: none;
}

.simple-modal select.form-control option {
    background-color: #343a40 !important;
    color: #ffffff !important;
}

/* Ajustement pour le modal de changement de poule */
#changePouleModal .simple-modal-content {
    border-color: #ffc107;
}

#changePouleModal .simple-modal-header h5 {
    color: #ffc107 !important;
}
</style>

<div class="container py-4">
    <!-- Section d'accueil admin -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card text-center">
                <h1 class="hero-title mb-3" style="font-size: 2.5rem;">
                    <i class="fas fa-shield-alt me-3 text-glow" style="color: #ff0033;"></i>
                    Administration
                </h1>
                <p class="hero-subtitle mb-0">
                    <span class="text-danger fw-bold">Interface d'administration</span> - Accès restreint
                </p>
            </div>
        </div>
    </div>

    <!-- Section liste des équipes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users me-3 text-blue" style="font-size: 2rem;"></i>
                        <h3 class="text-glow mb-0">Liste des équipes</h3>
                    </div>
                    <span class="badge badge-futuristic">
                        <i class="fas fa-team me-1"></i>
                        <?= count($teams) ?> équipes
                    </span>
                </div>

                <?php if (!empty($teams)): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr class="border-primary">
                                    <th scope="col">
                                        <i class="fas fa-tag me-2"></i>Nom de l'équipe
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-info-circle me-2"></i>Description
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-layer-group me-2"></i>Poule
                                    </th>
                                    <th scope="col" class="text-center">
                                        <i class="fas fa-users me-2"></i>Membres
                                    </th>
                                    <th scope="col" class="text-center">
                                        <i class="fas fa-tools me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teams as $team): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-shield text-primary me-2"></i>
                                                <strong class="text-cyan"><?= esc($team['name']) ?></strong>
                                            </div>
                                            <small class="text-muted">ID: <?= esc($team['team_id']) ?></small>
                                        </td>
                                        <td>
                                            <?php if ($team['description']): ?>
                                                <span class="text-white"><?= esc($team['description']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic">Aucune description</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($team['poule_name']): ?>
                                                <span class="badge bg-info text-dark">
                                                    <i class="fas fa-layer-group me-1"></i>
                                                    <?= esc($team['poule_name']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-question me-1"></i>
                                                    Non assignée
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="loadTeamMembers('<?= esc($team['team_id']) ?>')"
                                                <i class="fas fa-eye me-1"></i>
                                                Voir les membres
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                        title="Changer la poule"
                                                        onclick="showChangePouleModal('<?= esc($team['team_id']) ?>', '<?= esc($team['name']) ?>', '<?= esc($team['poule_name'] ?? '') ?>')">
                                                    <i class="fas fa-layer-group"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                        title="Détails de l'équipe"
                                                        onclick="showTeamDetails('<?= esc($team['team_id']) ?>')">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-secondary mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                        <h4 class="text-secondary">Aucune équipe trouvée</h4>
                        <p class="text-muted">Il n'y a actuellement aucune équipe enregistrée dans le système.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Section gestion des matchs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="futuristic-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trophy me-3 text-warning" style="font-size: 2rem;"></i>
                        <h3 class="text-glow mb-0">Gestion des Matchs</h3>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge badge-futuristic">
                            <i class="fas fa-gamepad me-1"></i>
                            <?= count($matches) ?> matchs
                        </span>
                        <button class="btn btn-sm btn-outline-success" onclick="showCreateMatchModal()">
                            <i class="fas fa-plus me-1"></i>Nouveau Match
                        </button>
                    </div>
                </div>

                <?php if (!empty($matches)): ?>
                    <div class="row g-4">
                        <?php foreach ($matches as $match): ?>
                            <div class="col-lg-6 col-xl-4">
                                <div class="card bg-dark border-primary">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="text-primary mb-0 me-2">
                                                        <i class="fas fa-calendar me-2"></i>
                                                        <?= date('d/m/Y H:i', strtotime($match['match_date'])) ?>
                                                    </h6>
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="showEditMatchDateModal('<?= esc($match['match_id']) ?>', '<?= date('Y-m-d\TH:i', strtotime($match['match_date'])) ?>')"
                                                            title="Modifier la date">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted">ID: <?= esc($match['match_id']) ?></small>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <?php if ($match['is_tournament']): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-trophy me-1"></i>BO3
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-gamepad me-1"></i>Poule
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- Affichage des équipes du match -->
                                        <div class="mt-3 d-flex align-items-center justify-content-center">
                                            <strong class="text-cyan me-3"><?= esc($match['team_a_name'] ?? 'Équipe A non assignée') ?></strong>
                                            <span class="text-white mx-3 fs-4">VS</span>
                                            <strong class="text-cyan ms-3"><?= esc($match['team_b_name'] ?? 'Équipe B non assignée') ?></strong>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($match['games'])): ?>
                                            <div class="games-list">
                                                <?php foreach ($match['games'] as $index => $game): ?>
                                                    <div class="game-item mb-3 p-3 bg-secondary bg-opacity-25 rounded border border-secondary border-opacity-50">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-secondary me-2">Game <?= $index + 1 ?></span>
                                                                <span class="text-cyan"><?= esc($game['team_a_name']) ?></span>
                                                                <span class="text-white mx-2">vs</span>
                                                                <span class="text-cyan"><?= esc($game['team_b_name']) ?></span>
                                                            </div>
                                                            <div class="d-flex gap-1">
                                                                <button class="btn btn-sm btn-outline-warning" 
                                                                        onclick="showEditScoreModal('<?= esc($game['game_id']) ?>', '<?= esc($game['team_a_name']) ?>', '<?= esc($game['team_b_name']) ?>', <?= $game['a_score'] ?? 'null' ?>, <?= $game['b_score'] ?? 'null' ?>, '<?= esc($game['map'] ?? '') ?>')">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger" 
                                                                        onclick="confirmDeleteGame('<?= esc($game['game_id']) ?>')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="score-display text-center">
                                                            <?php if (!empty($game['map'])): ?>
                                                                <div class="map-info mb-2">
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-map me-1"></i><?= esc($game['map']) ?>
                                                                    </span>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($game['a_score'] !== null && $game['b_score'] !== null): ?>
                                                                <span class="badge bg-success fs-6">
                                                                    <?= $game['a_score'] ?> - <?= $game['b_score'] ?>
                                                                </span>
                                                                <?php 
                                                                $winner = '';
                                                                if ($game['a_score'] > $game['b_score']) {
                                                                    $winner = $game['team_a_name'];
                                                                } elseif ($game['b_score'] > $game['a_score']) {
                                                                    $winner = $game['team_b_name'];
                                                                } else {
                                                                    $winner = 'Égalité';
                                                                }
                                                                ?>
                                                                <div class="mt-1">
                                                                    <small class="text-success">
                                                                        <i class="fas fa-crown me-1"></i>
                                                                        <?= $winner ?>
                                                                    </small>
                                                                </div>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">
                                                                    <i class="fas fa-clock me-1"></i>En attente
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-3">
                                                <i class="fas fa-info-circle text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Aucun game dans ce match</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-secondary bg-opacity-25">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-success flex-fill" 
                                                    onclick="showAddGameModal('<?= esc($match['match_id']) ?>')">
                                                <i class="fas fa-plus me-1"></i>Ajouter Game
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDeleteMatch('<?= esc($match['match_id']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-trophy text-secondary mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                        <h4 class="text-secondary">Aucun match trouvé</h4>
                        <p class="text-muted mb-4">Commencez par créer un nouveau match pour organiser des games entre équipes.</p>
                        <button class="btn btn-outline-success" onclick="showCreateMatchModal()">
                            <i class="fas fa-plus me-2"></i>Créer le premier match
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal simple pour afficher les membres d'une équipe -->
<div id="teamMembersModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('teamMembersModal')"></div>
    <div class="simple-modal-content">
        <div class="simple-modal-header">
            <h5 class="text-primary">
                <i class="fas fa-users me-2"></i>Membres de l'équipe
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('teamMembersModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <div id="teamMembersContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="text-muted mt-2">Chargement des membres...</p>
                </div>
            </div>
        </div>
        
        <!-- Section Bracket Final -->
        <div class="col-12 mt-4">
            <div class="admin-section">
                <div class="section-header">
                    <h3><i class="fas fa-trophy text-warning me-2"></i>Gestion du Bracket Final</h3>
                    <button class="btn btn-warning" onclick="openBracketManagement()">
                        <i class="fas fa-edit me-1"></i>Gérer le Bracket
                    </button>
                </div>
                
                <div class="bracket-preview">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Demi-finales</h5>
                            <div id="semifinalMatches">
                                <!-- Les demi-finales seront chargées ici -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Finale</h5>
                            <div id="finalMatch">
                                <!-- La finale sera chargée ici -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal simple pour changer la poule d'une équipe -->
<div id="changePouleModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('changePouleModal')"></div>
    <div class="simple-modal-content">
        <div class="simple-modal-header">
            <h5 class="text-warning">
                <i class="fas fa-layer-group me-2"></i>Changer la poule
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('changePouleModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <form id="changePouleForm">
                <input type="hidden" id="teamIdInput" name="team_id">
                
                <div class="mb-4">
                    <h6 class="text-primary">Équipe sélectionnée</h6>
                    <div class="p-3 bg-secondary bg-opacity-25 rounded">
                        <strong id="teamNameDisplay" class="text-cyan"></strong>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-primary">Poule actuelle</h6>
                    <div class="p-3 bg-secondary bg-opacity-25 rounded">
                        <span id="currentPouleDisplay" class="text-white"></span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="pouleSelect" class="form-label text-primary">
                        <i class="fas fa-layer-group me-2"></i>Nouvelle poule
                    </label>
                    <select class="form-control bg-dark text-white border-primary" id="pouleSelect" name="poule_id">
                        <option value="none">Aucune poule</option>
                        <?php if (!empty($poules)): ?>
                            <?php foreach ($poules as $poule): ?>
                                <?php if ($poule['poule_id'] !== 'default_poule'): ?>
                                    <option value="<?= esc($poule['poule_id']) ?>">
                                        Poule <?= esc($poule['poule_id']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong> Cette action modifiera la poule de l'équipe de manière permanente.
                </div>
            </form>
        </div>
        <div class="simple-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('changePouleModal')">
                <i class="fas fa-times me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-warning" onclick="savePouleChange()">
                <i class="fas fa-save me-1"></i>Sauvegarder
            </button>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau match -->
<div id="createMatchModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('createMatchModal')"></div>
    <div class="simple-modal-content">
        <div class="simple-modal-header">
            <h5 class="text-success">
                <i class="fas fa-plus me-2"></i>Créer un nouveau match
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('createMatchModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <form id="createMatchForm">
                <div class="mb-4">
                    <label for="matchTeamA" class="form-label text-primary">
                        <i class="fas fa-shield me-2"></i>Équipe A
                    </label>
                    <select class="form-control" id="matchTeamA" name="team_id_a" required>
                        <option value="">Sélectionnez une équipe</option>
                        <?php if (!empty($teams)): ?>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?= esc($team['team_id']) ?>">
                                    <?= esc($team['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="matchTeamB" class="form-label text-primary">
                        <i class="fas fa-shield me-2"></i>Équipe B
                    </label>
                    <select class="form-control" id="matchTeamB" name="team_id_b" required>
                        <option value="">Sélectionnez une équipe</option>
                        <?php if (!empty($teams)): ?>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?= esc($team['team_id']) ?>">
                                    <?= esc($team['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="matchDate" class="form-label text-primary">
                        <i class="fas fa-calendar me-2"></i>Date et heure du match
                    </label>
                    <input type="datetime-local" class="form-control" id="matchDate" name="match_date">
                    <small class="text-muted">Laissez vide pour utiliser la date actuelle</small>
                </div>
                
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isTournament" name="is_tournament" value="1">
                        <label class="form-check-label text-primary" for="isTournament">
                            <i class="fas fa-trophy me-2"></i>Match de tournoi (BO3)
                        </label>
                        <small class="d-block text-muted mt-1">
                            Non coché = Match de poule (1 game), Coché = Tournoi (BO3)
                        </small>
                    </div>
                </div>
            </form>
        </div>
        <div class="simple-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('createMatchModal')">
                <i class="fas fa-times me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-success" onclick="createMatch()">
                <i class="fas fa-save me-1"></i>Créer le match
            </button>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un game à un match -->
<div id="addGameModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('addGameModal')"></div>
    <div class="simple-modal-content">
        <div class="simple-modal-header">
            <h5 class="text-success">
                <i class="fas fa-plus me-2"></i>Ajouter un game au match
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('addGameModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <form id="addGameForm">
                <input type="hidden" id="gameMatchId" name="match_id">
                
                <div class="mb-4">
                    <h6 class="text-primary">Match</h6>
                    <div class="p-3 bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center">
                        <strong id="gameMatchTeamA" class="text-cyan me-3"></strong>
                        <span class="text-white mx-3 fs-5">VS</span>
                        <strong id="gameMatchTeamB" class="text-cyan ms-3"></strong>
                    </div>
                    <small class="text-muted">Les équipes sont héritées du match</small>
                </div>
                
                <div class="mb-3">
                    <label for="gameMap" class="form-label text-primary">
                        <i class="fas fa-map me-2"></i>Carte jouée
                    </label>
                    <select class="form-select" id="gameMap" name="map">
                        <option value="">Sélectionnez une carte</option>
                        <?php if (isset($valorantMaps) && !empty($valorantMaps)): ?>
                            <?php foreach ($valorantMaps as $map): ?>
                                <option value="<?= esc($map['map_name']) ?>"><?= esc($map['map_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Carte Valorant (optionnel)</small>
                </div>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <label for="scoreA" class="form-label text-primary">
                            <i class="fas fa-trophy me-2"></i>Score <span id="gameScoreALabel"></span>
                        </label>
                        <input type="number" class="form-control" id="scoreA" name="a_score" min="0">
                        <small class="text-muted">Laissez vide si pas encore joué</small>
                    </div>
                    <div class="col-6">
                        <label for="scoreB" class="form-label text-primary">
                            <i class="fas fa-trophy me-2"></i>Score <span id="gameScoreBLabel"></span>
                        </label>
                        <input type="number" class="form-control" id="scoreB" name="b_score" min="0">
                        <small class="text-muted">Laissez vide si pas encore joué</small>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note :</strong> Un game représente une partie individuelle du match entre ces deux équipes.
                </div>
            </form>
        </div>
        <div class="simple-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('addGameModal')">
                <i class="fas fa-times me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-success" onclick="addGameToMatch()">
                <i class="fas fa-save me-1"></i>Ajouter le game
            </button>
        </div>
    </div>
</div>

<!-- Modal pour modifier le score d'un game -->
<div id="editScoreModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('editScoreModal')"></div>
    <div class="simple-modal-content">
        <div class="simple-modal-header">
            <h5 class="text-warning">
                <i class="fas fa-edit me-2"></i>Modifier le score
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('editScoreModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <form id="editScoreForm">
                <input type="hidden" id="editGameId" name="game_id">
                
                <div class="mb-4">
                    <h6 class="text-primary">Match</h6>
                    <div class="p-3 bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center">
                        <strong id="editTeamAName" class="text-cyan me-2"></strong>
                        <span class="text-white mx-2">vs</span>
                        <strong id="editTeamBName" class="text-cyan"></strong>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="editGameMap" class="form-label text-primary">
                        <i class="fas fa-map me-2"></i>Carte jouée
                    </label>
                    <select class="form-select" id="editGameMap" name="map">
                        <option value="">Sélectionnez une carte</option>
                        <?php if (isset($valorantMaps) && !empty($valorantMaps)): ?>
                            <?php foreach ($valorantMaps as $map): ?>
                                <option value="<?= esc($map['map_name']) ?>"><?= esc($map['map_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Carte Valorant (optionnel)</small>
                </div>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <label for="editScoreA" class="form-label text-primary">
                            <i class="fas fa-trophy me-2"></i>Score <span id="editScoreALabel"></span>
                        </label>
                        <input type="number" class="form-control" id="editScoreA" name="a_score" min="0">
                    </div>
                    <div class="col-6">
                        <label for="editScoreB" class="form-label text-primary">
                            <i class="fas fa-trophy me-2"></i>Score <span id="editScoreBLabel"></span>
                        </label>
                        <input type="number" class="form-control" id="editScoreB" name="b_score" min="0">
                    </div>
                </div>
            </form>
        </div>
        <div class="simple-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('editScoreModal')">
                <i class="fas fa-times me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-warning" onclick="updateGameScore()">
                <i class="fas fa-save me-1"></i>Mettre à jour
            </button>
        </div>
    </div>
</div>

<!-- Modal pour gérer le bracket final -->
<div id="bracketManagementModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('bracketManagementModal')"></div>
    <div class="simple-modal-content" style="max-width: 900px;">
        <div class="simple-modal-header">
            <h5 class="text-warning">
                <i class="fas fa-trophy me-2"></i>Gestion du Bracket Final
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('bracketManagementModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <div class="row">
                <!-- Section Qualification -->
                <div class="col-md-6">
                    <h6 class="text-primary">
                        <i class="fas fa-users me-2"></i>Équipes Qualifiées
                    </h6>
                    <form id="qualificationForm">
                        <div class="mb-3">
                            <label class="form-label">1er Poule A</label>
                            <select class="form-control" id="firstPouleA" name="first_poule_a">
                                <option value="">Sélectionner une équipe</option>
                                <?php if (!empty($teams)): ?>
                                    <?php foreach ($teams as $team): ?>
                                        <?php if ($team['poule_id'] === 'A'): ?>
                                            <option value="<?= esc($team['team_id']) ?>">
                                                <?= esc($team['name']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">2ème Poule A</label>
                            <select class="form-control" id="secondPouleA" name="second_poule_a">
                                <option value="">Sélectionner une équipe</option>
                                <?php if (!empty($teams)): ?>
                                    <?php foreach ($teams as $team): ?>
                                        <?php if ($team['poule_id'] === 'A'): ?>
                                            <option value="<?= esc($team['team_id']) ?>">
                                                <?= esc($team['name']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">1er Poule B</label>
                            <select class="form-control" id="firstPouleB" name="first_poule_b">
                                <option value="">Sélectionner une équipe</option>
                                <?php if (!empty($teams)): ?>
                                    <?php foreach ($teams as $team): ?>
                                        <?php if ($team['poule_id'] === 'B'): ?>
                                            <option value="<?= esc($team['team_id']) ?>">
                                                <?= esc($team['name']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">2ème Poule B</label>
                            <select class="form-control" id="secondPouleB" name="second_poule_b">
                                <option value="">Sélectionner une équipe</option>
                                <?php if (!empty($teams)): ?>
                                    <?php foreach ($teams as $team): ?>
                                        <?php if ($team['poule_id'] === 'B'): ?>
                                            <option value="<?= esc($team['team_id']) ?>">
                                                <?= esc($team['name']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </form>
                </div>
                
                <!-- Section Matchs du Bracket -->
                <div class="col-md-6">
                    <h6 class="text-primary">
                        <i class="fas fa-swords me-2"></i>Matchs du Bracket
                    </h6>
                    
                    <div class="bracket-matches">
                        <div class="bracket-match-card mb-3">
                            <h6>Demi-finale 1</h6>
                            <div class="bracket-match-teams">
                                <span class="team-placeholder" id="sf1TeamA">1er Poule A</span>
                                <span class="vs-text">VS</span>
                                <span class="team-placeholder" id="sf1TeamB">2ème Poule B</span>
                            </div>
                            <div class="bracket-actions mt-2">
                                <button class="btn btn-sm btn-success" onclick="createSemifinal1()">
                                    <i class="fas fa-plus me-1"></i>Créer Match
                                </button>
                            </div>
                        </div>
                        
                        <div class="bracket-match-card mb-3">
                            <h6>Demi-finale 2</h6>
                            <div class="bracket-match-teams">
                                <span class="team-placeholder" id="sf2TeamA">1er Poule B</span>
                                <span class="vs-text">VS</span>
                                <span class="team-placeholder" id="sf2TeamB">2ème Poule A</span>
                            </div>
                            <div class="bracket-actions mt-2">
                                <button class="btn btn-sm btn-success" onclick="createSemifinal2()">
                                    <i class="fas fa-plus me-1"></i>Créer Match
                                </button>
                            </div>
                        </div>
                        
                        <div class="bracket-match-card">
                            <h6>Finale</h6>
                            <div class="bracket-match-teams">
                                <span class="team-placeholder">Gagnant SF1</span>
                                <span class="vs-text">VS</span>
                                <span class="team-placeholder">Gagnant SF2</span>
                            </div>
                            <div class="bracket-actions mt-2">
                                <button class="btn btn-sm btn-warning" onclick="createFinal()" disabled>
                                    <i class="fas fa-trophy me-1"></i>Créer Finale
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="simple-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('bracketManagementModal')">
                <i class="fas fa-times me-1"></i>Fermer
            </button>
        </div>
    </div>
</div>

<!-- Modal pour modifier la date d'un match -->
<div id="editMatchDateModal" class="simple-modal" style="display: none;">
    <div class="simple-modal-backdrop" onclick="closeSimpleModal('editMatchDateModal')"></div>
    <div class="simple-modal-content">
        <div class="simple-modal-header">
            <h5 class="text-info">
                <i class="fas fa-calendar-edit me-2"></i>Modifier la date du match
            </h5>
            <button type="button" class="simple-close-btn" onclick="closeSimpleModal('editMatchDateModal')">×</button>
        </div>
        <div class="simple-modal-body">
            <form id="editMatchDateForm">
                <input type="hidden" id="editMatchId" name="match_id">
                
                <div class="mb-4">
                    <label for="editMatchDateInput" class="form-label text-primary">
                        <i class="fas fa-clock me-2"></i>Nouvelle date et heure
                    </label>
                    <input type="datetime-local" class="form-control" id="editMatchDateInput" name="match_date" required>
                    <small class="text-muted">Sélectionnez la nouvelle date et heure pour ce match</small>
                </div>
            </form>
        </div>
        <div class="simple-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('editMatchDateModal')">
                <i class="fas fa-times me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-info" onclick="updateMatchDate()">
                <i class="fas fa-save me-1"></i>Mettre à jour
            </button>
        </div>
    </div>
</div>

<script>
// FONCTIONS POUR LES MODALS SIMPLES

// Variables pour maintenir la position de scroll
let scrollPosition = 0;
let bodyScrollTop = 0;

// Fonction pour ouvrir un modal simple
function openSimpleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Sauvegarder la position de scroll actuelle
        bodyScrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Configuration du modal pour centrage parfait
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.zIndex = '9999';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.margin = '0';
        modal.style.padding = '20px';
        modal.style.boxSizing = 'border-box';
        
        // Fixer la position du body pour éviter qu'il remonte
        document.body.style.position = 'fixed';
        document.body.style.top = `-${bodyScrollTop}px`;
        document.body.style.width = '100%';
        document.body.style.overflow = 'hidden';
        
        // Afficher le modal
        modal.classList.add('show');
    }
}

// Fonction pour fermer un modal simple
function closeSimpleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Masquer le modal
        modal.style.display = 'none';
        modal.classList.remove('show');
        
        // Restaurer la position et le style du body
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        
        // Restaurer la position de scroll exacte
        window.scrollTo(0, bodyScrollTop);
    }
}

// Fonction pour charger les membres d'une équipe
function loadTeamMembers(teamId) {
    console.log('Loading team members for:', teamId);
    
    const content = document.getElementById('teamMembersContent');
    
    // Afficher le spinner de chargement
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="text-muted mt-2">Chargement des membres...</p>
        </div>
    `;
    
    // Ouvrir le modal
    openSimpleModal('teamMembersModal');
    
    // Faire une requête pour récupérer les membres
    fetch(`/admin/team/${teamId}/members`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTeamMembers(data.members, data.team_name);
            } else {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur: ${data.message || 'Impossible de charger les membres'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Une erreur est survenue lors du chargement des membres
                </div>
            `;
        });
}

// Fonction pour afficher les membres d'une équipe
function displayTeamMembers(members, teamName) {
    const content = document.getElementById('teamMembersContent');
    
    if (members && members.length > 0) {
        let html = '<div class="row g-3">';
        
        members.forEach(member => {
            html += `
                <div class="col-md-6">
                    <div class="p-3 bg-secondary bg-opacity-25 rounded-3 border border-primary border-opacity-25">
                        <div class="d-flex align-items-center mb-2">
                            ${member.avatar ? 
                                `<img src="${member.avatar}" alt="Avatar" class="rounded-circle me-3" style="width: 40px; height: 40px; border: 2px solid var(--primary-blue);">` :
                                `<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; border: 2px solid var(--primary-blue);"><i class="fas fa-user text-white"></i></div>`
                            }
                            <div class="flex-grow-1">
                                <h6 class="text-primary mb-0">${member.pseudo}</h6>
                                <small class="text-secondary">${member.tag || 'N/A'}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            ${member.is_leader ? '<span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i>Leader</span>' : ''}
                            ${member.riot_id ? `<span class="badge bg-info"><i class="fas fa-gamepad me-1"></i>${member.riot_id}</span>` : ''}
                        </div>
                        
                        <div class="small text-muted">
                            <div><i class="fab fa-discord me-1"></i> ${member.discord_id}</div>
                            ${member.mmr ? `<div><i class="fas fa-trophy me-1"></i> ${member.mmr} MMR</div>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        content.innerHTML = html;
    } else {
        content.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-user-slash text-secondary mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                <h5 class="text-secondary">Aucun membre</h5>
                <p class="text-muted">Cette équipe n'a actuellement aucun membre.</p>
            </div>
        `;
    }
}

// Fonction pour afficher le modal de changement de poule
function showChangePouleModal(teamId, teamName, currentPoule) {
    console.log('Opening change poule modal for:', teamId, teamName, currentPoule);
    
    // Remplir les données du modal
    document.getElementById('teamIdInput').value = teamId;
    document.getElementById('teamNameDisplay').textContent = teamName;
    document.getElementById('currentPouleDisplay').textContent = currentPoule || 'Aucune poule';
    
    // Réinitialiser le select
    document.getElementById('pouleSelect').value = 'none';
    
    // Ouvrir le modal
    openSimpleModal('changePouleModal');
}

// Fonction pour afficher les détails d'une équipe
function showTeamDetails(teamId) {
    alert('Détails de l\'équipe: ' + teamId);
}

// Fonction pour sauvegarder le changement de poule
function savePouleChange() {
    const teamId = document.getElementById('teamIdInput').value;
    const pouleId = document.getElementById('pouleSelect').value;
    const teamName = document.getElementById('teamNameDisplay').textContent;
    
    // Désactiver le bouton pendant la requête
    const saveBtn = document.querySelector('#changePouleModal .btn-warning');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sauvegarde...';
    
    // Envoyer la requête
    fetch('/admin/update-team-poule', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `team_id=${encodeURIComponent(teamId)}&poule_id=${encodeURIComponent(pouleId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            closeSimpleModal('changePouleModal');
            
            // Afficher un message de succès
            showAlert('success', `La poule de l'équipe "${teamName}" a été mise à jour vers "${data.poule_name}".`);
            
            // Recharger la page pour refléter les changements
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la mise à jour de la poule');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la mise à jour');
    })
    .finally(() => {
        // Réactiver le bouton
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Fonction pour afficher des alertes
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// ===========================
// FONCTIONS POUR LES MATCHS
// ===========================

// Fonction pour afficher le modal de création de match
function showCreateMatchModal() {
    // Réinitialiser le formulaire
    document.getElementById('createMatchForm').reset();
    
    // Définir la date/heure actuelle
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('matchDate').value = now.toISOString().slice(0, 16);
    
    // Ouvrir le modal
    openSimpleModal('createMatchModal');
}

// Fonction pour créer un match
function createMatch() {
    const matchDate = document.getElementById('matchDate').value;
    const isTournament = document.getElementById('isTournament').checked;
    const teamIdA = document.getElementById('matchTeamA').value;
    const teamIdB = document.getElementById('matchTeamB').value;
    
    // Debug logging
    console.log('CreateMatch Debug:', {
        matchDate,
        isTournament,
        isTournamentValue: isTournament ? '1' : '0',
        teamIdA,
        teamIdB
    });
    
    // Validation
    if (!teamIdA || !teamIdB) {
        showAlert('warning', 'Veuillez sélectionner les deux équipes');
        return;
    }
    
    if (teamIdA === teamIdB) {
        showAlert('warning', 'Une équipe ne peut pas jouer contre elle-même');
        return;
    }
    
    // Désactiver le bouton pendant la requête
    const saveBtn = document.querySelector('#createMatchModal .btn-success');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Création...';
    
    // Envoyer la requête
    fetch('/admin/create-match', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `match_date=${encodeURIComponent(matchDate)}&is_tournament=${isTournament ? '1' : '0'}&team_id_a=${encodeURIComponent(teamIdA)}&team_id_b=${encodeURIComponent(teamIdB)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            closeSimpleModal('createMatchModal');
            
            // Afficher un message de succès
            showAlert('success', `Match créé avec succès (ID: ${data.match_id})`);
            
            // Recharger la page pour afficher le nouveau match
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la création du match');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la création du match');
    })
    .finally(() => {
        // Réactiver le bouton
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Fonction pour afficher le modal de modification de date de match
function showEditMatchDateModal(matchId, currentDate) {
    // Pré-remplir le formulaire
    document.getElementById('editMatchId').value = matchId;
    document.getElementById('editMatchDateInput').value = currentDate;
    
    // Ouvrir le modal
    openSimpleModal('editMatchDateModal');
}

// Fonction pour mettre à jour la date du match
function updateMatchDate() {
    const matchId = document.getElementById('editMatchId').value;
    const newDate = document.getElementById('editMatchDateInput').value;
    
    // Validation
    if (!newDate) {
        showAlert('warning', 'Veuillez sélectionner une date et heure');
        return;
    }
    
    // Désactiver le bouton pendant la requête
    const updateBtn = document.querySelector('#editMatchDateModal .btn-info');
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mise à jour...';
    
    // Envoyer la requête
    fetch('/admin/update-match-date', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `match_id=${encodeURIComponent(matchId)}&match_date=${encodeURIComponent(newDate)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            closeSimpleModal('editMatchDateModal');
            
            // Afficher un message de succès
            showAlert('success', 'Date du match mise à jour avec succès');
            
            // Recharger la page pour afficher la nouvelle date
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la mise à jour de la date');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la mise à jour');
    })
    .finally(() => {
        // Réactiver le bouton
        updateBtn.disabled = false;
        updateBtn.innerHTML = originalText;
    });
}

// Fonction pour afficher le modal d'ajout de game
function showAddGameModal(matchId) {
    // Trouver le match dans la page pour obtenir les noms des équipes
    const matchCards = document.querySelectorAll('.card');
    let teamAName = '', teamBName = '';
    
    matchCards.forEach(card => {
        const matchIdElement = card.querySelector('small');
        if (matchIdElement && matchIdElement.textContent.includes(matchId)) {
            const teamElements = card.querySelectorAll('.text-cyan');
            if (teamElements.length >= 2) {
                teamAName = teamElements[0].textContent.trim();
                teamBName = teamElements[1].textContent.trim();
            }
        }
    });
    
    // Réinitialiser le formulaire
    document.getElementById('addGameForm').reset();
    document.getElementById('gameMatchId').value = matchId;
    
    // Remplir les informations du match
    document.getElementById('gameMatchTeamA').textContent = teamAName;
    document.getElementById('gameMatchTeamB').textContent = teamBName;
    document.getElementById('gameScoreALabel').textContent = teamAName;
    document.getElementById('gameScoreBLabel').textContent = teamBName;
    
    // Ouvrir le modal
    openSimpleModal('addGameModal');
}

// Fonction pour ajouter un game à un match
function addGameToMatch() {
    const matchId = document.getElementById('gameMatchId').value;
    const scoreA = document.getElementById('scoreA').value;
    const scoreB = document.getElementById('scoreB').value;
    const map = document.getElementById('gameMap').value;
    
    // Désactiver le bouton pendant la requête
    const saveBtn = document.querySelector('#addGameModal .btn-success');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Ajout...';
    
    // Envoyer la requête
    fetch('/admin/add-game-to-match', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `match_id=${encodeURIComponent(matchId)}&a_score=${encodeURIComponent(scoreA)}&b_score=${encodeURIComponent(scoreB)}&map=${encodeURIComponent(map)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            closeSimpleModal('addGameModal');
            
            // Afficher un message de succès
            showAlert('success', 'Game ajouté au match avec succès');
            
            // Recharger la page pour afficher le nouveau game
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de l\'ajout du game');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de l\'ajout du game');
    })
    .finally(() => {
        // Réactiver le bouton
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Fonction pour afficher le modal de modification de score
function showEditScoreModal(gameId, teamAName, teamBName, scoreA, scoreB, map) {
    // Remplir les données du modal
    document.getElementById('editGameId').value = gameId;
    document.getElementById('editTeamAName').textContent = teamAName;
    document.getElementById('editTeamBName').textContent = teamBName;
    document.getElementById('editScoreALabel').textContent = teamAName;
    document.getElementById('editScoreBLabel').textContent = teamBName;
    document.getElementById('editScoreA').value = scoreA !== null ? scoreA : '';
    document.getElementById('editScoreB').value = scoreB !== null ? scoreB : '';
    // Sélectionner la carte dans le menu déroulant
    const editGameMapSelect = document.getElementById('editGameMap');
    if (editGameMapSelect && map) {
        editGameMapSelect.value = map;
    } else if (editGameMapSelect) {
        editGameMapSelect.value = '';
    }
    
    // Ouvrir le modal
    openSimpleModal('editScoreModal');
}

// Fonction pour mettre à jour le score d'un game
function updateGameScore() {
    const gameId = document.getElementById('editGameId').value;
    const scoreA = document.getElementById('editScoreA').value;
    const scoreB = document.getElementById('editScoreB').value;
    const map = document.getElementById('editGameMap').value;
    
    // Désactiver le bouton pendant la requête
    const saveBtn = document.querySelector('#editScoreModal .btn-warning');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mise à jour...';
    
    // Envoyer la requête
    fetch('/admin/update-game-score', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `game_id=${encodeURIComponent(gameId)}&a_score=${encodeURIComponent(scoreA)}&b_score=${encodeURIComponent(scoreB)}&map=${encodeURIComponent(map)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            closeSimpleModal('editScoreModal');
            
            // Afficher un message de succès
            showAlert('success', 'Score mis à jour avec succès');
            
            // Recharger la page pour afficher les nouveaux scores
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la mise à jour du score');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la mise à jour du score');
    })
    .finally(() => {
        // Réactiver le bouton
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Fonction pour confirmer la suppression d'un match
function confirmDeleteMatch(matchId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce match et tous ses games associés ? Cette action est irréversible.')) {
        deleteMatch(matchId);
    }
}

// Fonction pour supprimer un match
function deleteMatch(matchId) {
    // Envoyer la requête de suppression
    fetch(`/admin/delete-match/${matchId}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès
            showAlert('success', 'Match supprimé avec succès');
            
            // Recharger la page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la suppression du match');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la suppression du match');
    });
}

// Fonction pour confirmer la suppression d'un game
function confirmDeleteGame(gameId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce game ? Cette action est irréversible.')) {
        deleteGame(gameId);
    }
}

// ===========================
// GESTION DU BRACKET FINAL
// ===========================

// Fonction pour ouvrir le modal de gestion du bracket
function openBracketManagement() {
    // Charger les matchs de tournoi existants
    loadBracketMatches();
    openSimpleModal('bracketManagementModal');
}

// Fonction pour charger les matchs de tournoi existants
function loadBracketMatches() {
    // Cette fonction chargera les matchs de tournoi depuis la BDD
    // Pour l'instant, on affiche juste le modal
}

// Fonction pour créer la demi-finale 1 (1er Poule A vs 2ème Poule B)
function createSemifinal1() {
    const team1 = document.getElementById('firstPouleA').value;
    const team2 = document.getElementById('secondPouleB').value;
    
    if (!team1 || !team2) {
        showAlert('warning', 'Veuillez sélectionner les équipes qualifiées');
        return;
    }
    
    createTournamentMatch(team1, team2, 'Demi-finale 1');
}

// Fonction pour créer la demi-finale 2 (1er Poule B vs 2ème Poule A)
function createSemifinal2() {
    const team1 = document.getElementById('firstPouleB').value;
    const team2 = document.getElementById('secondPouleA').value;
    
    if (!team1 || !team2) {
        showAlert('warning', 'Veuillez sélectionner les équipes qualifiées');
        return;
    }
    
    createTournamentMatch(team1, team2, 'Demi-finale 2');
}

// Fonction pour créer un match de tournoi
function createTournamentMatch(teamIdA, teamIdB, matchType) {
    const formData = new FormData();
    formData.append('team_id_a', teamIdA);
    formData.append('team_id_b', teamIdB);
    formData.append('is_tournament', '1');
    formData.append('match_date', new Date().toISOString().slice(0, 16));
    
    fetch('/admin/create-match', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `team_id_a=${encodeURIComponent(teamIdA)}&team_id_b=${encodeURIComponent(teamIdB)}&is_tournament=1&match_date=${encodeURIComponent(new Date().toISOString().slice(0, 16))}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `${matchType} créé avec succès`);
            // Recharger la page pour voir les nouveaux matchs
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || `Erreur lors de la création de ${matchType}`);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', `Une erreur est survenue lors de la création de ${matchType}`);
    });
}

// Fonction pour créer la finale (sera activée quand les demi-finales seront terminées)
function createFinal() {
    // Cette fonction sera implémentée pour créer automatiquement la finale
    // basée sur les gagnants des demi-finales
    showAlert('info', 'La création automatique de la finale sera disponible après les demi-finales');
}

// Fonction pour supprimer un game
function deleteGame(gameId) {
    // Envoyer la requête de suppression
    fetch(`/admin/delete-game/${gameId}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès
            showAlert('success', 'Game supprimé avec succès');
            
            // Recharger la page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Erreur lors de la suppression du game');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('danger', 'Une erreur est survenue lors de la suppression du game');
    });
}
</script>

<?= $this->endSection() ?>