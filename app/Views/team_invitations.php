<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="futuristic-card">
                <div class="card-header">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-link me-2"></i>Gérer les invitations d'équipe
                    </h1>
                </div>
                <div class="card-body">
                    <?php if (isset($team) && $team['is_leader']): ?>
                        <!-- Formulaire pour créer une invitation -->
                        <div class="mb-4">
                            <h4><i class="fas fa-plus-circle me-2"></i>Créer une nouvelle invitation</h4>
                            <form action="/team/invite" method="post" class="row g-3">
                                <div class="col-md-6">
                                    <label for="expiration_hours" class="form-label">Durée de validité (heures)</label>
                                    <select class="form-control-futuristic" id="expiration_hours" name="expiration_hours">
                                        <option value="1">1 heure</option>
                                        <option value="6">6 heures</option>
                                        <option value="24" selected>24 heures</option>
                                        <option value="72">3 jours</option>
                                        <option value="168">1 semaine</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="max_uses" class="form-label">Limite d'utilisation (optionnel)</label>
                                    <input type="number" 
                                           class="form-control-futuristic" 
                                           id="max_uses" 
                                           name="max_uses" 
                                           min="1" 
                                           max="5" 
                                           placeholder="Illimité">
                                    <div class="form-text">Laisser vide pour usage illimité</div>
                                </div>
                                <div class="col-12 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary-futuristic">
                                        <i class="fas fa-link me-2"></i>Créer l'invitation
                                    </button>
                                </div>
                            </form>
                        </div>

                        <hr class="my-4">

                        <!-- Liste des invitations actives -->
                        <div>
                            <h4><i class="fas fa-list me-2"></i>Invitations actives</h4>
                            <?php if (isset($invitations) && !empty($invitations)): ?>
                                <div class="table-responsive">
                                    <table class="table table-dark table-futuristic">
                                        <thead>
                                            <tr>
                                                <th>Lien d'invitation</th>
                                                <th>Expire le</th>
                                                <th>Utilisations</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invitations as $invitation): ?>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" 
                                                                   class="form-control-futuristic" 
                                                                   readonly 
                                                                   value="<?= base_url('team/join/' . $invitation['invitation_id']) ?>"
                                                                   id="invite-<?= $invitation['invitation_id'] ?>">
                                                            <button class="btn btn-outline-primary" 
                                                                    type="button" 
                                                                    onclick="copyInviteLink('<?= $invitation['invitation_id'] ?>')">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td><?= date('d/m/Y H:i', strtotime($invitation['expires_at'])) ?></td>
                                                    <td>
                                                        <?= $invitation['current_uses'] ?>
                                                        <?php if ($invitation['max_uses']): ?>
                                                            / <?= $invitation['max_uses'] ?>
                                                        <?php else: ?>
                                                            / ∞
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger-futuristic btn-sm" 
                                                                onclick="deactivateInvite('<?= $invitation['invitation_id'] ?>')">
                                                            <i class="fas fa-times"></i> Désactiver
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info-futuristic">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucune invitation active. Créez-en une pour inviter des joueurs dans votre équipe.
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif (isset($player) && $player['team_id']): ?>
                        <!-- Joueur membre d'une équipe mais pas leader -->
                        <div class="alert alert-warning-futuristic">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Seul le leader de l'équipe peut gérer les invitations.
                        </div>
                        
                    <?php else: ?>
                        <!-- Joueur sans équipe -->
                        <div class="alert alert-info-futuristic">
                            <i class="fas fa-info-circle me-2"></i>
                            Vous devez créer ou rejoindre une équipe pour gérer les invitations.
                        </div>
                        <a href="/team/create" class="btn btn-primary-futuristic">
                            <i class="fas fa-plus me-2"></i>Créer une équipe
                        </a>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="/dashboard" class="btn btn-secondary-futuristic">
                            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyInviteLink(inviteId) {
    const input = document.getElementById('invite-' + inviteId);
    input.select();
    input.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        
        // Feedback visuel
        const button = input.nextElementSibling;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);
        
    } catch (err) {
        console.error('Erreur lors de la copie:', err);
        alert('Impossible de copier le lien automatiquement. Veuillez le copier manuellement.');
    }
}

function deactivateInvite(inviteId) {
    if (confirm('Êtes-vous sûr de vouloir désactiver cette invitation ?')) {
        // TODO: Implémenter la désactivation d'invitation
        console.log('Désactivation de l\'invitation:', inviteId);
        alert('Fonctionnalité à implémenter : désactivation d\'invitation');
    }
}
</script>
<?= $this->endSection() ?>