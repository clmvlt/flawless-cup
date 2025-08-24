<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h1 class="card-title mb-0">
                        <i class="fas fa-database me-2"></i>Test Base de Données
                    </h1>
                    <p class="card-text mb-0 mt-2 opacity-75">Vérification complète du système Flawless Cup</p>
                </div>
            </div>
        </div>
    </div>

    <?php
    $successCount = 0;
    $warningCount = 0;
    $errorCount = 0;
    
    foreach ($results as $result) {
        switch ($result['status']) {
            case 'success':
                $successCount++;
                break;
            case 'warning':
                $warningCount++;
                break;
            case 'error':
                $errorCount++;
                break;
        }
    }
    ?>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Résumé des Tests
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h4 class="text-success"><?= $successCount ?></h4>
                                    <p class="card-text">Succès</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                    <h4 class="text-warning"><?= $warningCount ?></h4>
                                    <p class="card-text">Avertissements</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                    <h4 class="text-danger"><?= $errorCount ?></h4>
                                    <p class="card-text">Erreurs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <?php foreach ($results as $result): ?>
            <div class="card mb-3 border-start border-<?= $result['status'] === 'success' ? 'success' : ($result['status'] === 'warning' ? 'warning' : 'danger') ?> border-3">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <?php
                        $icon = '';
                        $badgeClass = '';
                        switch ($result['status']) {
                            case 'success':
                                $icon = 'fas fa-check-circle text-success';
                                $badgeClass = 'bg-success';
                                break;
                            case 'warning':
                                $icon = 'fas fa-exclamation-triangle text-warning';
                                $badgeClass = 'bg-warning text-dark';
                                break;
                            case 'error':
                                $icon = 'fas fa-times-circle text-danger';
                                $badgeClass = 'bg-danger';
                                break;
                        }
                        ?>
                        <i class="<?= $icon ?> me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1"><?= esc($result['name']) ?></h6>
                            <p class="card-text mb-0"><?= esc($result['message']) ?></p>
                        </div>
                        <span class="badge <?= $badgeClass ?> ms-2">
                            <?= strtoupper($result['status']) ?>
                        </span>
                    </div>
                </div>
                <?php if (!empty($result['details'])): ?>
                <div class="card-body">
                    <pre class="bg-light p-3 border rounded mb-0" style="font-size: 0.85rem; white-space: pre-wrap; word-wrap: break-word;"><?= esc($result['details']) ?></pre>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="/" class="btn btn-primary me-2">
                <i class="fas fa-home me-1"></i>Accueil
            </a>
            <a href="/dashboard" class="btn btn-outline-primary">
                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>