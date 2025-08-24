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

/* Modal styles simples et fonctionnels */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.9) !important;
    z-index: 1040 !important;
}

.modal {
    z-index: 1050 !important;
}

.modal-dialog {
    z-index: 1051 !important;
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

/* MODALS SIMPLES - CSS PUR */
.simple-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2000;
    display: none;
}

.simple-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 2001;
}

.simple-modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #212529;
    border: 2px solid #0d6efd;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 80%;
    overflow-y: auto;
    z-index: 2002;
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

<script>
// FONCTIONS POUR LES MODALS SIMPLES

// Fonction pour ouvrir un modal simple
function openSimpleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

// Fonction pour fermer un modal simple
function closeSimpleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
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
</script>

<?= $this->endSection() ?>