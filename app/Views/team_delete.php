<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="futuristic-card">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h2 class="text-glow">
                        <span class="text-danger">Supprimer l'équipe</span>
                    </h2>
                    <p class="text-secondary">Cette action est irréversible</p>
                </div>

                <!-- Messages d'erreur et de succès -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger-futuristic" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-warning-futuristic mb-4">
                    <h5 class="text-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>Attention !
                    </h5>
                    <p class="mb-2">Vous êtes sur le point de supprimer définitivement l'équipe :</p>
                    <h4 class="text-center text-cyan my-3">
                        <i class="fas fa-users me-2"></i><?= esc($team['team_name']) ?>
                    </h4>
                    
                    <div class="mt-3">
                        <strong>Conséquences de cette action :</strong>
                        <ul class="mt-2 mb-0">
                            <li>🗑️ L'équipe sera <strong>supprimée définitivement</strong></li>
                            <li>👥 Tous les membres (<strong><?= count($members) ?> joueur<?= count($members) > 1 ? 's' : '' ?></strong>) seront retirés</li>
                            <li>🚫 Cette action <strong>ne peut pas être annulée</strong></li>
                            <li>📊 L'historique de l'équipe sera perdu</li>
                        </ul>
                    </div>
                </div>

                <?php if (count($members) > 1): ?>
                <div class="futuristic-card bg-info-subtle mb-4">
                    <h6 class="text-blue mb-3">
                        <i class="fas fa-users me-2"></i>Membres qui seront affectés
                    </h6>
                    <div class="row g-2">
                        <?php foreach ($members as $member): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <?php if ($member['is_leader']): ?>
                                        <i class="fas fa-crown text-warning me-2"></i>
                                    <?php else: ?>
                                        <i class="fas fa-user text-primary me-2"></i>
                                    <?php endif; ?>
                                    <span class="text-sm"><?= esc($member['pseudo']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <form action="/team/delete" method="post" id="deleteTeamForm">
                    <div class="mb-4">
                        <label for="confirmation" class="form-label fw-bold text-danger">
                            <i class="fas fa-keyboard me-2"></i>Confirmation requise
                        </label>
                        <p class="text-secondary mb-2">
                            Pour confirmer la suppression, tapez le nom exact de l'équipe ci-dessous :
                        </p>
                        <div class="text-center mb-3">
                            <code class="bg-dark text-cyan p-2 rounded"><?= esc($team['team_name']) ?></code>
                        </div>
                        <input type="text" class="form-control form-control-lg" id="confirmation" name="confirmation" 
                               placeholder="Tapez le nom de l'équipe..." required>
                        <div class="form-text text-danger">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            La saisie doit correspondre exactement au nom de l'équipe
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-danger-futuristic btn-lg" id="submitBtn" disabled>
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement l'équipe
                        </button>
                        <a href="/dashboard" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Annuler et retourner au Dashboard
                        </a>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <small class="text-secondary">
                        <i class="fas fa-info-circle me-1"></i>
                        Si vous souhaitez simplement quitter l'équipe sans la supprimer, 
                        <a href="/team/leave" class="text-primary">cliquez ici</a>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation de la confirmation
document.getElementById('confirmation').addEventListener('input', function(e) {
    const teamName = <?= json_encode($team['team_name']) ?>;
    const submitBtn = document.getElementById('submitBtn');
    
    if (e.target.value === teamName) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Supprimer définitivement l\'équipe';
    } else {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Tapez le nom exact de l\'équipe';
    }
});

// Confirmation finale avant suppression
document.getElementById('deleteTeamForm').addEventListener('submit', function(e) {
    const teamName = <?= json_encode($team['team_name']) ?>;
    const confirmation = document.getElementById('confirmation').value;
    
    if (confirmation !== teamName) {
        e.preventDefault();
        alert('Le nom saisi ne correspond pas au nom de l\'équipe');
        return false;
    }
    
    const finalConfirm = confirm(
        '⚠️ DERNIÈRE CONFIRMATION ⚠️\n\n' +
        'Êtes-vous absolument certain de vouloir supprimer l\'équipe "' + teamName + '" ?\n\n' +
        'Cette action est IRRÉVERSIBLE et affectera ' + <?= count($members) ?> + ' joueur(s).\n\n' +
        'Cliquez sur OK pour procéder à la suppression définitive.'
    );
    
    if (!finalConfirm) {
        e.preventDefault();
        return false;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Suppression en cours...';
    submitBtn.disabled = true;
});
</script>

<?= $this->endSection() ?>