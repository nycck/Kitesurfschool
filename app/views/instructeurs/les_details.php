<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid" style="background-color: #1a202c; min-height: 100vh; padding-top: 20px;">
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4" style="border-bottom: 2px solid #4a5568;">
            <h1 class="h2 text-light">Les Details</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo URLROOT; ?>/instructeurs/planning" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Terug naar Planning
                </a>
            </div>
        </div>

            <?php flash('success_message'); ?>
            <?php flash('error_message'); ?>

            <div class="row">
                <div class="col-md-8">
                    <!-- Les Informatie -->
                    <div class="card mb-4" style="background-color: #2d3748; border: 1px solid #4a5568;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light"><i class="fas fa-info-circle me-2"></i> Les Informatie</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong class="text-light">Datum:</strong>
                                    </div>
                                    <p class="text-light ms-4"><?php echo date('d-m-Y', strtotime($data['reservering']->bevestigde_datum)); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <strong class="text-light">Tijd:</strong>
                                    </div>
                                    <p class="text-light ms-4"><?php echo date('H:i', strtotime($data['reservering']->bevestigde_tijd)); ?></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-flag text-primary me-2"></i>
                                        <strong class="text-light">Status:</strong>
                                    </div>
                                    <div class="ms-4">
                                        <?php 
                                        $statusClass = '';
                                        switch($data['reservering']->status) {
                                            case 'bevestigd':
                                                $statusClass = 'badge bg-success';
                                                break;
                                            case 'aangevraagd':
                                                $statusClass = 'badge bg-warning text-dark';
                                                break;
                                            case 'geannuleerd':
                                                $statusClass = 'badge bg-danger';
                                                break;
                                            case 'afgerond':
                                                $statusClass = 'badge bg-info';
                                                break;
                                        }
                                        ?>
                                        <span class="<?php echo $statusClass; ?> px-3 py-2"><?php echo ucfirst($data['reservering']->status); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-book text-primary me-2"></i>
                                        <strong class="text-light">Lespakket:</strong>
                                    </div>
                                    <p class="text-light ms-4"><?php echo $data['reservering']->lespakket_naam; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-hourglass-half text-primary me-2"></i>
                                        <strong class="text-light">Duur:</strong>
                                    </div>
                                    <p class="text-light ms-4"><?php echo $data['reservering']->lespakket_duur; ?> uur</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-euro-sign text-primary me-2"></i>
                                        <strong class="text-light">Prijs:</strong>
                                    </div>
                                    <p class="text-light ms-4">€<?php echo number_format($data['reservering']->lespakket_prijs, 2, ',', '.'); ?></p>
                                </div>
                            </div>
                            <?php if (!empty($data['reservering']->lespakket_beschrijving)): ?>
                            <hr style="border-color: #4a5568;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-align-left text-primary me-2"></i>
                                        <strong class="text-light">Beschrijving:</strong>
                                    </div>
                                    <p class="text-secondary ms-4"><?php echo $data['reservering']->lespakket_beschrijving; ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Locatie Informatie -->
                    <div class="card mb-4" style="background-color: #2d3748; border: 1px solid #4a5568;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light"><i class="fas fa-map-marker-alt me-2"></i> Locatie</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-location-dot text-primary me-2"></i>
                                    <strong class="text-light">Naam:</strong>
                                </div>
                                <p class="text-light ms-4"><?php echo $data['reservering']->locatie_naam; ?></p>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map text-primary me-2"></i>
                                    <strong class="text-light">Adres:</strong>
                                </div>
                                <p class="text-light ms-4"><?php echo $data['reservering']->locatie_adres; ?></p>
                            </div>
                            <?php if (!empty($data['reservering']->locatie_faciliteiten)): ?>
                            <div class="mb-0">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <strong class="text-light">Faciliteiten:</strong>
                                </div>
                                <p class="text-secondary ms-4"><?php echo $data['reservering']->locatie_faciliteiten; ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Opmerkingen en Evaluatie -->
                    <div class="card mb-4" style="background-color: #2d3748; border: 1px solid #4a5568;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light"><i class="fas fa-comment me-2"></i> Opmerkingen & Evaluatie</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data['reservering']->opmerking)): ?>
                            <div class="mb-3 p-3" style="background-color: #1a202c; border-radius: 8px; border-left: 4px solid #3b82f6;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-info me-2"></i>
                                    <strong class="text-light">Klant opmerking:</strong>
                                </div>
                                <p class="text-secondary mb-0 ms-4"><?php echo $data['reservering']->opmerking; ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($data['reservering']->instructeur_opmerking)): ?>
                            <div class="mb-3 p-3" style="background-color: #1a202c; border-radius: 8px; border-left: 4px solid #10b981;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                                    <strong class="text-light">Instructeur opmerking:</strong>
                                </div>
                                <p class="text-secondary mb-0 ms-4"><?php echo $data['reservering']->instructeur_opmerking; ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($data['reservering']->evaluatie)): ?>
                            <div class="mb-3 p-3" style="background-color: #1a202c; border-radius: 8px; border-left: 4px solid #8b5cf6;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong class="text-light">Evaluatie:</strong>
                                </div>
                                <p class="text-secondary mb-0 ms-4"><?php echo $data['reservering']->evaluatie; ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($data['reservering']->voortgang)): ?>
                            <div class="mb-3 p-3" style="background-color: #1a202c; border-radius: 8px; border-left: 4px solid #06b6d4;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chart-line text-info me-2"></i>
                                    <strong class="text-light">Voortgang:</strong>
                                </div>
                                <p class="text-secondary mb-0 ms-4"><?php echo $data['reservering']->voortgang; ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($data['reservering']->aanbevelingen)): ?>
                            <div class="mb-0 p-3" style="background-color: #1a202c; border-radius: 8px; border-left: 4px solid #f59e0b;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-lightbulb text-warning me-2"></i>
                                    <strong class="text-light">Aanbevelingen:</strong>
                                </div>
                                <p class="text-secondary mb-0 ms-4"><?php echo $data['reservering']->aanbevelingen; ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if (empty($data['reservering']->opmerking) && empty($data['reservering']->instructeur_opmerking) && empty($data['reservering']->evaluatie) && empty($data['reservering']->voortgang) && empty($data['reservering']->aanbevelingen)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-secondary mb-2" style="font-size: 2rem;"></i>
                                <p class="text-secondary mb-0">Geen opmerkingen of evaluatie beschikbaar.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Klant Informatie -->
                    <div class="card mb-4" style="background-color: #2d3748; border: 1px solid #4a5568;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light"><i class="fas fa-user me-2"></i> Klant</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-circle text-primary me-2"></i>
                                    <strong class="text-light">Naam:</strong>
                                </div>
                                <p class="text-light ms-4 mb-0"><?php echo $data['reservering']->persoon_naam; ?></p>
                            </div>
                            <?php if (!empty($data['reservering']->duo_partner_naam)): ?>
                            <div class="mb-0">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-friends text-primary me-2"></i>
                                    <strong class="text-light">Duo Partner:</strong>
                                </div>
                                <p class="text-light ms-4 mb-0"><?php echo $data['reservering']->duo_partner_naam; ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Acties -->
                    <?php if ($data['reservering']->status == 'bevestigd'): ?>
                    <div class="card mb-4" style="background-color: #2d3748; border: 1px solid #4a5568;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light"><i class="fas fa-cog me-2"></i> Acties</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo URLROOT; ?>/instructeurs/snelle_annulering/<?php echo $data['reservering']->id; ?>" class="mb-2">
                                <input type="hidden" name="template" value="ziekte">
                                <button type="submit" class="btn w-100 mb-2 d-flex align-items-center justify-content-center" style="background-color: #f59e0b; color: #1a202c; border: none; padding: 12px;" onclick="return confirm('Weet je zeker dat je deze les wilt annuleren wegens ziekte?');">
                                    <i class="fas fa-thermometer-half me-2"></i> Annuleren (Ziekte)
                                </button>
                            </form>
                            
                            <form method="POST" action="<?php echo URLROOT; ?>/instructeurs/snelle_annulering/<?php echo $data['reservering']->id; ?>" class="mb-2">
                                <input type="hidden" name="template" value="weer">
                                <button type="submit" class="btn w-100 mb-3 d-flex align-items-center justify-content-center" style="background-color: #06b6d4; color: #1a202c; border: none; padding: 12px;" onclick="return confirm('Weet je zeker dat je deze les wilt annuleren wegens slecht weer?');">
                                    <i class="fas fa-cloud-rain me-2"></i> Annuleren (Slecht Weer)
                                </button>
                            </form>

                            <button type="button" class="btn w-100 d-flex align-items-center justify-content-center" style="background-color: #10b981; color: white; border: none; padding: 12px; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#evaluatieModal">
                                <i class="fas fa-check me-2"></i> Les Afronden
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Betaling Informatie -->
                    <div class="card mb-4" style="background-color: #2d3748; border: 1px solid #4a5568;">
                        <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                            <h5 class="mb-0 text-light"><i class="fas fa-euro-sign me-2"></i> Betaling</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-credit-card text-primary me-2"></i>
                                <strong class="text-light">Status:</strong>
                            </div>
                            <div class="ms-4">
                                <?php 
                                $betalingClass = '';
                                $betalingIcon = '';
                                switch($data['reservering']->betaal_status) {
                                    case 'betaald':
                                        $betalingClass = 'badge bg-success';
                                        $betalingIcon = 'fa-check-circle';
                                        break;
                                    case 'wachtend':
                                        $betalingClass = 'badge bg-warning text-dark';
                                        $betalingIcon = 'fa-clock';
                                        break;
                                    case 'mislukt':
                                        $betalingClass = 'badge bg-danger';
                                        $betalingIcon = 'fa-times-circle';
                                        break;
                                }
                                ?>
                                <span class="<?php echo $betalingClass; ?> px-3 py-2">
                                    <i class="fas <?php echo $betalingIcon; ?> me-1"></i>
                                    <?php echo ucfirst($data['reservering']->betaal_status); ?>
                                </span>
                            </div>
                            <?php if (!empty($data['reservering']->betaal_opmerking)): ?>
                            <div class="mt-3 p-2" style="background-color: #1a202c; border-radius: 6px;">
                                <p class="text-secondary small mb-0"><?php echo $data['reservering']->betaal_opmerking; ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Evaluatie Modal -->
<div class="modal fade" id="evaluatieModal" tabindex="-1" aria-labelledby="evaluatieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #2d3748; border: 1px solid #4a5568;">
            <div class="modal-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title text-light" id="evaluatieModalLabel">Les Afronden & Evalueren</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/instructeurs/les_afronden/<?php echo $data['reservering']->id; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="evaluatie" class="form-label text-light">Evaluatie</label>
                        <textarea class="form-control bg-secondary text-light border-secondary" id="evaluatie" name="evaluatie" rows="3" placeholder="Hoe verliep de les?" style="color: white !important;"><?php echo $data['reservering']->evaluatie ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voortgang" class="form-label text-light">Voortgang</label>
                        <textarea class="form-control bg-secondary text-light border-secondary" id="voortgang" name="voortgang" rows="3" placeholder="Wat heeft de klant geleerd?" style="color: white !important;"><?php echo $data['reservering']->voortgang ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="aanbevelingen" class="form-label text-light">Aanbevelingen</label>
                        <textarea class="form-control bg-secondary text-light border-secondary" id="aanbevelingen" name="aanbevelingen" rows="3" placeholder="Wat moet de klant verder oefenen?" style="color: white !important;"><?php echo $data['reservering']->aanbevelingen ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="instructeur_opmerking" class="form-label text-light">Opmerking (optioneel)</label>
                        <textarea class="form-control bg-secondary text-light border-secondary" id="instructeur_opmerking" name="instructeur_opmerking" rows="2" placeholder="Eventuele extra opmerkingen" style="color: white !important;"><?php echo $data['reservering']->instructeur_opmerking ?? ''; ?></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #4a5568;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-success">Les Afronden</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
