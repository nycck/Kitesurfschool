<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid py-4" style="background-color: #1a202c; min-height: 100vh;">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-light">Reservering Details #<?php echo $data['reservering']->id; ?></h1>
                <a href="<?php echo URLROOT; ?>/eigenaar/betalingen" class="btn btn-outline-secondary" style="border-color: #4a5568; color: #cbd5e0;">
                    <i class="fas fa-arrow-left"></i> Terug naar Betalingen
                </a>
            </div>

            <?php flash('error_message'); ?>
            <?php flash('success_message'); ?>

            <div class="row">
                <!-- Reservering Informatie -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light">
                                <i class="fas fa-info-circle me-2"></i>Reservering Informatie
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Status:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-<?php 
                                        echo $data['reservering']->status == 'bevestigd' ? 'success' : 
                                            ($data['reservering']->status == 'aangevraagd' ? 'warning' : 
                                            ($data['reservering']->status == 'afgerond' ? 'info' : 'danger')); 
                                    ?>">
                                        <?php echo ucfirst($data['reservering']->status); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Lespakket:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;"><?php echo htmlspecialchars($data['reservering']->pakket_naam ?? 'Onbekend'); ?></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Locatie:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;"><?php echo htmlspecialchars($data['reservering']->locatie_naam ?? 'Onbekend'); ?></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Gewenste datum:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;">
                                        <?php 
                                        if(isset($data['reservering']->gewenste_datum) && $data['reservering']->gewenste_datum) {
                                            echo date('d-m-Y', strtotime($data['reservering']->gewenste_datum));
                                        } else {
                                            echo 'Niet opgegeven';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>

                            <?php if(isset($data['reservering']->bevestigde_datum) && $data['reservering']->bevestigde_datum): ?>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Bevestigde datum:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;"><?php echo date('d-m-Y', strtotime($data['reservering']->bevestigde_datum)); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($data['reservering']->bevestigde_tijd) && $data['reservering']->bevestigde_tijd): ?>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Bevestigde tijd:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;"><?php echo date('H:i', strtotime($data['reservering']->bevestigde_tijd)); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($data['reservering']->opmerking) && $data['reservering']->opmerking): ?>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Opmerking:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #cbd5e0;"><?php echo nl2br(htmlspecialchars($data['reservering']->opmerking)); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="row mb-0">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Aangemaakt op:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #cbd5e0;"><?php echo date('d-m-Y H:i', strtotime($data['reservering']->aangemaakt_op)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Klant Informatie -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light">
                                <i class="fas fa-user me-2"></i>Klant Informatie
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Naam:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;">
                                        <?php echo htmlspecialchars(($data['reservering']->voornaam ?? '') . ' ' . ($data['reservering']->achternaam ?? '')); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;"><?php echo htmlspecialchars($data['reservering']->klant_email ?? 'Onbekend'); ?></span>
                                </div>
                            </div>

                            <?php if(isset($data['reservering']->telefoon) && $data['reservering']->telefoon): ?>
                            <div class="row mb-0">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Telefoon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #e2e8f0;"><?php echo htmlspecialchars($data['reservering']->telefoon); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Betaling Informatie -->
                    <div class="card border-0 shadow-lg mt-4" style="background-color: #2d3748;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light">
                                <i class="fas fa-euro-sign me-2"></i>Betaling Informatie
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Bedrag:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <strong style="color: #e2e8f0;">€<?php echo number_format($data['reservering']->prijs_per_persoon ?? 0, 2, ',', '.'); ?></strong>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Betaalstatus:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-<?php 
                                        echo $data['reservering']->betaal_status == 'betaald' ? 'success' : 
                                            ($data['reservering']->betaal_status == 'wachtend' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($data['reservering']->betaal_status ?? 'Onbekend'); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if(isset($data['reservering']->betaal_opmerking) && $data['reservering']->betaal_opmerking): ?>
                            <div class="row mb-0">
                                <div class="col-sm-4">
                                    <strong style="color: #f7fafc;">Opmerking:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span style="color: #cbd5e0;"><?php echo nl2br(htmlspecialchars($data['reservering']->betaal_opmerking)); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acties -->
            <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                    <h5 class="mb-0 text-light">
                        <i class="fas fa-tasks me-2"></i>Acties
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <?php if($data['reservering']->betaal_status != 'betaald'): ?>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#wijzigBetalingModal">
                                <i class="fas fa-check-circle me-2"></i>Betaling Goedkeuren
                            </button>
                        <?php endif; ?>
                        
                        <?php if($data['reservering']->status == 'aangevraagd'): ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bevestigReserveringModal">
                                <i class="fas fa-calendar-check me-2"></i>Reservering Bevestigen
                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-info" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Afdrukken
                        </button>
                        
                        <a href="<?php echo URLROOT; ?>/eigenaar/reserveringen" class="btn btn-outline-light">
                            <i class="fas fa-list me-2"></i>Alle Reserveringen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wijzig Betaling Modal -->
<div class="modal fade" id="wijzigBetalingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title text-light">Betaling Goedkeuren</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/update_betaling_status/<?php echo $data['reservering']->id; ?>">
                <div class="modal-body" style="background-color: #2d3748;">
                    <p class="text-light">Weet je zeker dat je deze betaling wilt goedkeuren?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> De klant ontvangt een bevestigingsmail.
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #2d3748; border-top: 1px solid #4a5568;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-success">Goedkeuren</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bevestig Reservering Modal -->
<div class="modal fade" id="bevestigReserveringModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title text-light">Reservering Bevestigen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/reservering_status/<?php echo $data['reservering']->id; ?>">
                <div class="modal-body" style="background-color: #2d3748;">
                    <input type="hidden" name="status" value="bevestigd">
                    
                    <div class="mb-3">
                        <label for="opmerking" class="form-label text-light">Opmerking (optioneel)</label>
                        <textarea name="opmerking" id="opmerking" rows="3" class="form-control bg-secondary text-light" 
                                  style="border-color: #4a5568;" placeholder="Voeg een opmerking toe..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> De klant ontvangt een bevestigingsmail.
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #2d3748; border-top: 1px solid #4a5568;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Bevestigen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
