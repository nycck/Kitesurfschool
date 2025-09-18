<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="display-6 fw-bold text-primary-gradient mb-0">
                    <i class="fas fa-file-alt me-3"></i>Reservering Details
                </h1>
            </div>

            <?php flash('reservering_message'); ?>

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Reservering #<?php echo $data['reservering']->id; ?>
                    </h4>
                    <?php
                    $statusClass = '';
                    $statusIcon = '';
                    switch($data['reservering']->status) {
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
                    <span class="badge <?php echo $statusClass; ?> fs-6">
                        <i class="<?php echo $statusIcon; ?> me-2"></i>
                        <?php echo ucfirst($data['reservering']->status); ?>
                    </span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Lespakket Info -->
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary">
                                <i class="fas fa-graduation-cap me-2"></i>Lespakket
                            </h5>
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-2"><?php echo $data['reservering']->lespakket_naam; ?></h6>
                                <p class="text-muted mb-2"><?php echo $data['reservering']->lespakket_beschrijving; ?></p>
                                <div class="d-flex justify-content-between">
                                    <span>Duur:</span>
                                    <strong><?php echo $data['reservering']->lespakket_duur; ?> uur</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Prijs:</span>
                                    <strong class="text-success">€<?php echo number_format($data['reservering']->lespakket_prijs, 2); ?></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Locatie Info -->
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>Locatie
                            </h5>
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-2"><?php echo $data['reservering']->locatie_naam; ?></h6>
                                <p class="text-muted mb-2"><?php echo $data['reservering']->locatie_adres; ?></p>
                                <div class="mb-2">
                                    <strong>Faciliteiten:</strong><br>
                                    <small class="text-muted"><?php echo $data['reservering']->locatie_faciliteiten; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Datum & Tijd -->
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary">
                                <i class="fas fa-calendar me-2"></i>Planning
                            </h5>
                            <div class="bg-light p-3 rounded">
                                <div class="mb-2">
                                    <strong>Gewenste Datum:</strong><br>
                                    <?php echo date('l d F Y', strtotime($data['reservering']->gewenste_datum)); ?>
                                </div>
                                <?php if (!empty($data['reservering']->bevestigde_datum)): ?>
                                    <div class="mb-2">
                                        <strong>Bevestigde Datum:</strong><br>
                                        <span class="text-success">
                                            <?php echo date('l d F Y', strtotime($data['reservering']->bevestigde_datum)); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($data['reservering']->bevestigde_tijd)): ?>
                                    <div>
                                        <strong>Tijd:</strong><br>
                                        <span class="text-success"><?php echo $data['reservering']->bevestigde_tijd; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Betaling -->
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary">
                                <i class="fas fa-credit-card me-2"></i>Betaling
                            </h5>
                            <div class="bg-light p-3 rounded">
                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    <?php
                                    $betalingClass = '';
                                    $betalingIcon = '';
                                    switch($data['reservering']->betaal_status) {
                                        case 'betaald':
                                            $betalingClass = 'text-success';
                                            $betalingIcon = 'fas fa-check-circle';
                                            break;
                                        case 'wachtend':
                                            $betalingClass = 'text-warning';
                                            $betalingIcon = 'fas fa-clock';
                                            break;
                                        case 'mislukt':
                                            $betalingClass = 'text-danger';
                                            $betalingIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $betalingClass = 'text-muted';
                                            $betalingIcon = 'fas fa-question-circle';
                                    }
                                    ?>
                                    <span class="<?php echo $betalingClass; ?>">
                                        <i class="<?php echo $betalingIcon; ?> me-1"></i>
                                        <?php echo ucfirst($data['reservering']->betaal_status); ?>
                                    </span>
                                </div>
                                
                                <?php if ($data['reservering']->betaal_status == 'wachtend'): ?>
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <h6 class="alert-heading">Betalingsgegevens:</h6>
                                        <small>
                                            <strong>IBAN:</strong> NL12 ABCD 0123 4567 89<br>
                                            <strong>T.n.v.:</strong> Kitesurfschool Windkracht-12<br>
                                            <strong>Bedrag:</strong> €<?php echo number_format($data['reservering']->lespakket_prijs, 2); ?><br>
                                            <strong>Omschrijving:</strong> Reservering <?php echo $data['reservering']->id; ?>
                                        </small>
                                    </div>
                                <?php elseif ($data['reservering']->betaal_status == 'betaald'): ?>
                                    <div class="alert alert-success mt-3 mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Betaling ontvangen op <?php echo date('d-m-Y', strtotime($data['reservering']->bijgewerkt_op)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($data['reservering']->duo_partner_naam)): ?>
                        <!-- Duo Partner -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5 class="text-primary">
                                    <i class="fas fa-user-friends me-2"></i>Duo Partner
                                </h5>
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1"><?php echo $data['reservering']->duo_partner_naam; ?></h6>
                                            <small class="text-muted">Duo partner voor deze les</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data['reservering']->opmerking)): ?>
                        <!-- Opmerking -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5 class="text-primary">
                                    <i class="fas fa-comment me-2"></i>Opmerking
                                </h5>
                                <div class="bg-light p-3 rounded">
                                    <?php echo nl2br(htmlspecialchars($data['reservering']->opmerking)); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Instructeur Info -->
                    <?php if (!empty($data['reservering']->instructeur_naam)): ?>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5 class="text-primary">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Instructeur
                                </h5>
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tie fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1"><?php echo $data['reservering']->instructeur_naam; ?></h6>
                                            <small class="text-muted">Je instructeur voor deze les</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Annulering Info -->
                    <?php if ($data['reservering']->status == 'geannuleerd'): ?>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5 class="text-danger">
                                    <i class="fas fa-times-circle me-2"></i>Annulering
                                </h5>
                                <div class="alert alert-danger">
                                    <strong>Geannuleerd op:</strong> <?php echo date('d-m-Y H:i', strtotime($data['reservering']->bijgewerkt_op)); ?><br>
                                    <?php if (!empty($data['reservering']->annulering_reden)): ?>
                                        <strong>Reden:</strong> <?php echo htmlspecialchars($data['reservering']->annulering_reden); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tijdlijn -->
                    <div class="row">
                        <div class="col-12 mb-4">
                            <h5 class="text-primary">
                                <i class="fas fa-history me-2"></i>Tijdlijn
                            </h5>
                            <div class="bg-light p-3 rounded">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <i class="fas fa-plus-circle text-primary"></i>
                                        <strong>Reservering aangemaakt</strong><br>
                                        <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($data['reservering']->aangemaakt_op)); ?></small>
                                    </div>
                                    
                                    <?php if ($data['reservering']->status == 'bevestigd'): ?>
                                        <div class="timeline-item">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>Reservering bevestigd</strong><br>
                                            <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($data['reservering']->bijgewerkt_op)); ?></small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($data['reservering']->status == 'geannuleerd'): ?>
                                        <div class="timeline-item">
                                            <i class="fas fa-times-circle text-danger"></i>
                                            <strong>Reservering geannuleerd</strong><br>
                                            <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($data['reservering']->bijgewerkt_op)); ?></small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($data['reservering']->status == 'afgerond'): ?>
                                        <div class="timeline-item">
                                            <i class="fas fa-flag-checkered text-primary"></i>
                                            <strong>Les afgerond</strong><br>
                                            <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($data['reservering']->bijgewerkt_op)); ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex gap-3 justify-content-between">
                        <div>
                            <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Terug naar Overzicht
                            </a>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <?php if ($data['reservering']->status == 'aangevraagd' || $data['reservering']->status == 'bevestigd'): ?>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="fas fa-times me-2"></i>Annuleren
                                </button>
                            <?php endif; ?>
                            
                            <a href="<?php echo URLROOT; ?>/reserveringen/maken" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Nieuwe Reservering
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-phone me-2"></i>Vragen over je Reservering?
                    </h5>
                    <p class="card-text">
                        Neem contact op voor vragen over je reservering, wijzigingen of bijzondere wensen.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="tel:0612345678" class="btn btn-outline-primary">
                                <i class="fas fa-phone me-2"></i>06-12345678
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="mailto:info@kitesurfschool-windkracht12.nl" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Email Sturen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<?php if ($data['reservering']->status == 'aangevraagd' || $data['reservering']->status == 'bevestigd'): ?>
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reservering Annuleren</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo URLROOT; ?>/reserveringen/annuleren/<?php echo $data['reservering']->id; ?>" method="POST">
                    <div class="modal-body">
                        <p>Weet je zeker dat je deze reservering wilt annuleren?</p>
                        
                        <div class="alert alert-warning">
                            <strong><?php echo $data['reservering']->lespakket_naam; ?></strong><br>
                            <?php echo date('d-m-Y', strtotime($data['reservering']->gewenste_datum)); ?> in <?php echo $data['reservering']->locatie_naam; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="reden" class="form-label">Reden voor annulering *</label>
                            <textarea class="form-control" id="reden" name="reden" 
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

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-left: 2px solid #e9ecef;
}

.timeline-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
}

.timeline-item i {
    position: absolute;
    left: -9px;
    top: 0;
    background: white;
    padding: 2px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -1px;
    top: 25px;
    bottom: -20px;
    width: 2px;
    background: #e9ecef;
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>