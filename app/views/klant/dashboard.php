<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <?php flash('message'); ?>
    
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">
                            <i class="fas fa-tachometer-alt text-primary me-2"></i>
                            Welkom, <?= htmlspecialchars($data['user']->voornaam ?? 'Klant') ?>!
                        </h2>
                        <p class="lead mb-0">
                            Beheer je kitesurflessen en bekijk je reserveringen
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="<?= URLROOT ?>/reserveringen/maken" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i>Nieuwe Reservering
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-number"><?= $data['stats']['totaal_reserveringen'] ?></div>
                <div class="stat-label">Totaal Reserveringen</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card bg-success">
                <div class="stat-number"><?= $data['stats']['actieve_reserveringen'] ?></div>
                <div class="stat-label">Actieve Reserveringen</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card bg-info">
                <div class="stat-number"><?= $data['stats']['voltooide_lessen'] ?></div>
                <div class="stat-label">Voltooide Lessen</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Aankomende Lessen -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Aankomende Lessen
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['aankomende_lessen'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Datum</th>
                                        <th>Tijd</th>
                                        <th>Pakket</th>
                                        <th>Locatie</th>
                                        <th>Instructeur</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['aankomende_lessen'] as $les): ?>
                                    <tr>
                                        <td>
                                            <strong><?= formatDutchDate($les->les_datum) ?></strong>
                                        </td>
                                        <td>
                                            <?= date('H:i', strtotime($les->start_tijd)) ?> - 
                                            <?= date('H:i', strtotime($les->eind_tijd)) ?>
                                        </td>
                                        <td><?= htmlspecialchars($les->pakket_naam) ?></td>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                            <?= htmlspecialchars($les->locatie_naam) ?>
                                        </td>
                                        <td>
                                            <?php if ($les->instructeur_voornaam): ?>
                                                <?= htmlspecialchars($les->instructeur_voornaam . ' ' . $les->instructeur_achternaam) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Wordt toegewezen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?= ucfirst($les->status) ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?= URLROOT ?>/klant/reserveringen" class="btn btn-outline-primary">
                                <i class="fas fa-list me-1"></i>Alle Reserveringen Bekijken
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>Geen aankomende lessen</h5>
                            <p class="text-muted">Je hebt momenteel geen geplande kitesurflessen.</p>
                            <a href="<?= URLROOT ?>/reserveringen/maken" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-1"></i>Reserveer je Eerste Les
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Snelle Acties
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= URLROOT ?>/reserveringen/maken" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Nieuwe Reservering
                        </a>
                        <a href="<?= URLROOT ?>/reserveringen" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Mijn Reserveringen
                        </a>
                        <a href="<?= URLROOT ?>/klant/profiel" class="btn btn-outline-secondary">
                            <i class="fas fa-user-edit me-2"></i>Profiel Bewerken
                        </a>
                        <a href="<?= URLROOT ?>/auth/changePassword" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>Wachtwoord Wijzigen
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Reserveringen -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recente Reserveringen
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['reserveringen'])): ?>
                        <?php foreach (array_slice($data['reserveringen'], 0, 3) as $reservering): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <small class="fw-bold"><?= htmlspecialchars($reservering->pakket_naam) ?></small><br>
                                <small class="text-muted"><?= htmlspecialchars($reservering->locatie_naam) ?></small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $reservering->status === 'definitief' ? 'success' : ($reservering->status === 'geannuleerd' ? 'danger' : 'warning') ?>">
                                    <?= ucfirst($reservering->status) ?>
                                </span><br>
                                <?php if (isset($reservering->totaal_prijs)): ?>
                                <small class="text-muted">€<?= number_format($reservering->totaal_prijs, 2, ',', '.') ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($data['reserveringen']) > 3): ?>
                        <div class="text-center mt-2">
                            <a href="<?= URLROOT ?>/klant/reserveringen" class="btn btn-sm btn-outline-info">
                                Bekijk Alle
                            </a>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Geen reserveringen</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Completion Alert -->
    <?php if (empty($data['user']->voornaam) || empty($data['user']->telefoon)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading">Profiel Incompleet</h5>
                        <p class="mb-2">Vul je profiel aan voor een betere ervaring en om reserveringen te kunnen maken.</p>
                        <a href="<?= URLROOT ?>/klant/profiel" class="btn btn-warning">
                            <i class="fas fa-user-edit me-1"></i>Profiel Aanvullen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>