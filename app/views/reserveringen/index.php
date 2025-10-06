<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <?php flash('reservering_message'); ?>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-5 fw-bold text-primary-gradient">
                    <i class="fas fa-calendar-check me-3"></i>Mijn Reserveringen
                </h1>
                <a href="<?php echo URLROOT; ?>/reserveringen/maken" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Nieuwe Reservering
                </a>
            </div>

            <?php if (empty($data['reserveringen'])): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-5x text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">Geen reserveringen gevonden</h3>
                    <p class="text-muted mb-4">
                        Je hebt nog geen lessen gereserveerd. Boek nu je eerste kitesurfles!
                    </p>
                    <a href="<?php echo URLROOT; ?>/homepages/pakketten" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Bekijk Lespakketten
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($data['reserveringen'] as $reservering): ?>
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($reservering->lespakket_naam ?? 'Onbekend pakket'); ?></h5>
                                    <?php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    $status = $reservering->status ?? 'onbekend';
                                    switch($status) {
                                        case 'aangevraagd':
                                            $statusClass = 'bg-warning text-dark';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        case 'bevestigd':
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'fas fa-check';
                                            break;
                                        case 'geannuleerd':
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'fas fa-times';
                                            break;
                                        case 'afgerond':
                                            $statusClass = 'bg-primary';
                                            $statusIcon = 'fas fa-flag-checkered';
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary';
                                            $statusIcon = 'fas fa-question';
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <i class="<?php echo $statusIcon; ?> me-1"></i>
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </div>
                                
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <strong>Locatie:</strong> <?php echo htmlspecialchars($reservering->locatie_naam ?? 'Onbekend'); ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong>Datum:</strong> 
                                        <?php 
                                        if (isset($reservering->gewenste_datum) && $reservering->gewenste_datum) {
                                            echo date('d-m-Y', strtotime($reservering->gewenste_datum));
                                        } else {
                                            echo 'Nog niet vastgesteld';
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <i class="fas fa-euro-sign text-primary me-2"></i>
                                        <strong>Prijs:</strong> €<?php echo number_format($reservering->lespakket_prijs ?? 0, 2); ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <i class="fas fa-credit-card text-primary me-2"></i>
                                        <strong>Betaling:</strong> 
                                        <?php
                                        $betalingClass = '';
                                        $betaalStatus = $reservering->betaal_status ?? 'onbekend';
                                        switch($betaalStatus) {
                                            case 'betaald':
                                                $betalingClass = 'text-success';
                                                break;
                                            case 'wachtend':
                                                $betalingClass = 'text-warning';
                                                break;
                                            case 'mislukt':
                                                $betalingClass = 'text-danger';
                                                break;
                                            default:
                                                $betalingClass = 'text-muted';
                                        }
                                        ?>
                                        <span class="<?php echo $betalingClass; ?>">
                                            <?php echo ucfirst($betaalStatus); ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($reservering->duo_partner_naam)): ?>
                                        <div class="mb-3">
                                            <i class="fas fa-user-friends text-primary me-2"></i>
                                            <strong>Duo partner:</strong> <?php echo htmlspecialchars($reservering->duo_partner_naam); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($reservering->opmerking)): ?>
                                        <div class="mb-3">
                                            <i class="fas fa-comment text-primary me-2"></i>
                                            <strong>Opmerking:</strong><br>
                                            <small class="text-muted"><?php echo nl2br(htmlspecialchars($reservering->opmerking)); ?></small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Gereserveerd op: 
                                            <?php 
                                            if (isset($reservering->aangemaakt_op) && $reservering->aangemaakt_op) {
                                                echo date('d-m-Y H:i', strtotime($reservering->aangemaakt_op));
                                            } else {
                                                echo 'Datum onbekend';
                                            }
                                            ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-light">
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo URLROOT; ?>/reserveringen/details/<?php echo $reservering->id; ?>" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                        
                                        <?php if (isset($reservering->betaal_status) && $reservering->betaal_status != 'betaald'): ?>
                                            <a href="<?php echo URLROOT; ?>/klant/betaling/<?php echo $reservering->id; ?>" 
                                               class="btn btn-outline-success btn-sm flex-fill">
                                                <i class="fas fa-euro-sign me-1"></i>Betalen
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($reservering->status) && ($reservering->status == 'aangevraagd' || $reservering->status == 'bevestigd')): ?>
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $reservering->id; ?>">
                                                <i class="fas fa-times me-1"></i>Annuleren
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cancel Modal -->
                        <?php if (isset($reservering->status) && ($reservering->status == 'aangevraagd' || $reservering->status == 'bevestigd')): ?>
                            <div class="modal fade" id="cancelModal<?php echo $reservering->id; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reservering Annuleren</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?php echo URLROOT; ?>/reserveringen/annuleren/<?php echo $reservering->id; ?>" method="POST">
                                            <div class="modal-body">
                                                <p>Weet je zeker dat je deze reservering wilt annuleren?</p>
                                                <p><strong><?php echo htmlspecialchars($reservering->lespakket_naam ?? 'Onbekend pakket'); ?></strong><br>
                                                <?php 
                                                if (isset($reservering->gewenste_datum) && $reservering->gewenste_datum) {
                                                    echo date('d-m-Y', strtotime($reservering->gewenste_datum));
                                                } else {
                                                    echo 'Datum nog niet vastgesteld';
                                                }
                                                ?> in <?php echo htmlspecialchars($reservering->locatie_naam ?? 'Onbekende locatie'); ?></p>
                                                
                                                <div class="mb-3">
                                                    <label for="reden<?php echo $reservering->id; ?>" class="form-label">Reden voor annulering *</label>
                                                    <textarea class="form-control" id="reden<?php echo $reservering->id; ?>" name="reden" 
                                                              rows="3" required placeholder="Geef een korte uitleg..."></textarea>
                                                </div>
                                                
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Bij annulering minimaal 24 uur van tevoren krijg je het volledige bedrag terug.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times me-1"></i>Ja, Annuleren
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination could be added here if needed -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?php echo URLROOT; ?>/reserveringen/beschikbaarheid" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Beschikbaarheid Bekijken
                            </a>
                            <a href="<?php echo URLROOT; ?>/reserveringen/maken" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Nieuwe Reservering
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>