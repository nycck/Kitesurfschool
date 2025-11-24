<?php require_once APPROOT . '/views/includes/header.php'; ?>

<style>
/* Card */
.reservation-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.reservation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

/* Status badge */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    white-space: nowrap;
}

/* Info rows */
.info-row {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.info-row:last-child { padding-bottom: 0; }

.info-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.info-content { flex: 1; min-width: 0; }

/* Make all cards equal height */
.card.h-100 { height: 100%; }
.card-body.d-flex { display: flex; flex-direction: column; }
.card-body.d-flex .content { flex: 1; }

/* Small responsive tweaks */
@media (max-width: 576px) {
    .info-icon { width: 30px; height: 30px; }
}

</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1" style="font-weight:700; color:#2d3748">Mijn Reserveringen</h1>
                    <p class="text-muted mb-0">Overzicht van al je geboekte kitesurflessen</p>
                </div>
                <a href="<?php echo URLROOT; ?>/klant/dashboard" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Terug
                </a>
            </div>
        </div>
    </div>

    <?php flash('success_message'); ?>
    <?php flash('error_message'); ?>

    <?php if (!empty($data['reserveringen'])): ?>
        <!-- Use gap (g-3) so columns keep spacing -->
        <div class="row g-3">
            <?php foreach ($data['reserveringen'] as $reservering): ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card reservation-card shadow-sm h-100">
                        <div class="card-body p-3 d-flex">
                            <div class="content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0 fw-bold" style="color:#2d3748; font-size:1.05rem;">
                                        <?php echo htmlspecialchars($reservering->pakket_naam); ?>
                                    </h5>

                                    <?php
                                    $statusConfig = [
                                        'bevestigd'   => ['bg'=>'#10b981','icon'=>'check-circle','label'=>'Bevestigd'],
                                        'aangevraagd' => ['bg'=>'#f59e0b','icon'=>'clock','label'=>'Aangevraagd'],
                                        'geannuleerd' => ['bg'=>'#ef4444','icon'=>'times-circle','label'=>'Geannuleerd'],
                                        'afgerond'    => ['bg'=>'#3b82f6','icon'=>'flag-checkered','label'=>'Afgerond']
                                    ];
                                    $key = strtolower($reservering->status);
                                    $config = $statusConfig[$key] ?? ['bg'=>'#6b7280','icon'=>'info-circle','label'=>ucfirst($reservering->status)];
                                    ?>

                                    <span class="status-badge" style="background-color: <?php echo $config['bg']; ?>; color: white;">
                                        <i class="fas fa-<?php echo $config['icon']; ?> me-1"></i>
                                        <?php echo htmlspecialchars($config['label']); ?>
                                    </span>
                                </div>

                                <div class="mt-2">
                                    <?php if (!empty($reservering->bevestigde_datum)): ?>
                                        <div class="info-row">
                                            <div class="info-icon me-2" style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="info-content">
                                                <small class="text-muted d-block" style="font-size:0.75rem; margin-bottom:2px;">Datum</small>
                                                <strong style="color:#2d3748"><?php echo htmlspecialchars(date('d-m-Y', strtotime($reservering->bevestigde_datum))); ?></strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($reservering->bevestigde_tijd)): ?>
                                        <div class="info-row">
                                            <div class="info-icon me-2" style="background: linear-gradient(135deg,#f093fb 0%,#f5576c 100%);">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="info-content">
                                                <small class="text-muted d-block" style="font-size:0.75rem; margin-bottom:2px;">Tijd</small>
                                                <strong style="color:#2d3748"><?php echo htmlspecialchars(date('H:i', strtotime($reservering->bevestigde_tijd))); ?></strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="info-row">
                                        <div class="info-icon me-2" style="background: linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <small class="text-muted d-block" style="font-size:0.75rem; margin-bottom:2px;">Locatie</small>
                                            <strong style="color:#2d3748"><?php echo htmlspecialchars($reservering->locatie_naam); ?></strong>
                                        </div>
                                    </div>

                                    <?php if (!empty($reservering->instructeur_naam)): ?>
                                        <div class="info-row">
                                            <div class="info-icon me-2" style="background: linear-gradient(135deg,#fa709a 0%,#fee140 100%);">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="info-content">
                                                <small class="text-muted d-block" style="font-size:0.75rem; margin-bottom:2px;">Instructeur</small>
                                                <strong style="color:#2d3748"><?php echo htmlspecialchars($reservering->instructeur_naam); ?></strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($reservering->status == 'afgerond' && (!empty($reservering->evaluatie) || !empty($reservering->voortgang) || !empty($reservering->aanbevelingen))): ?>
                                        <div class="mt-2 p-2 rounded" style="background: linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%);">
                                            <small class="fw-bold" style="color:#d97706; font-size:0.8rem;"><i class="fas fa-star me-1"></i> Evaluatie beschikbaar</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Actions: placed after content so all cards align -->
                            <div class="mt-3">
                                <?php
                                // safe date handling
                                $isCancellable = false;
                                if ($reservering->status == 'bevestigd' && !empty($reservering->bevestigde_datum)) {
                                    try {
                                        $lesDatum = new DateTime($reservering->bevestigde_datum);
                                        $vandaag = new DateTime();

                                        // only cancellable if lesDatum is in the future and >= 2 days difference
                                        $interval = $vandaag->diff($lesDatum);
                                        $days = (int)$interval->format('%r%a'); // signed days
                                        if ($days >= 2) { $isCancellable = true; }
                                    } catch (Exception $e) {
                                        $isCancellable = false;
                                    }
                                }
                                ?>

                                <?php if ($isCancellable): ?>
                                    <a href="<?php echo URLROOT; ?>/klant/annuleer_les/<?php echo $reservering->id; ?>" 
                                       class="btn btn-danger w-100 rounded-pill" 
                                       onclick="return confirm('Weet je zeker dat je deze les wilt annuleren?');" 
                                       style="font-weight:500;">
                                        <i class="fas fa-times me-2"></i> Les Annuleren
                                    </a>
                                <?php elseif ($reservering->status == 'bevestigd'): ?>
                                    <div class="text-center p-3 rounded" style="background-color:#fef3c7;">
                                        <small style="color:#92400e; font-weight:500;"><i class="fas fa-lock me-1"></i> Niet meer annuleerbaar</small>
                                    </div>
                                <?php elseif ($reservering->status == 'aangevraagd'): ?>
                                    <div class="text-center p-3 rounded" style="background-color:#dbeafe;">
                                        <small style="color:#1e40af; font-weight:500;"><i class="fas fa-hourglass-half me-1"></i> In afwachting van bevestiging</small>
                                    </div>
                                <?php elseif ($reservering->status == 'afgerond'): ?>
                                    <a href="<?php echo URLROOT; ?>/klant/les_evaluatie/<?php echo $reservering->id; ?>" 
                                       class="btn w-100 rounded-pill" 
                                       style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color: white; font-weight:500; border:none;">
                                        <i class="fas fa-star me-2"></i> Bekijk Evaluatie
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card reservation-card shadow-sm text-center" style="border-radius:20px; border:2px dashed #e5e7eb;">
                    <div class="card-body py-5 px-4">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:80px; height:80px; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                                <i class="fas fa-water fa-2x text-white"></i>
                            </div>
                        </div>
                        <h3 class="mb-3" style="color:#2d3748; font-weight:700;">Nog geen lessen geboekt</h3>
                        <p class="text-muted mb-4">Begin je kitesurfavontuur! Boek je eerste les en ervaar de ultieme vrijheid op het water.</p>
                        <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-lg rounded-pill px-5" style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; font-weight:500; border:none;">
                            <i class="fas fa-plus me-2"></i> Boek Je Eerste Les
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
