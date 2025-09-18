<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4"><?php echo $data['title']; ?></h1>
            
            <!-- Statistieken Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo $data['totaal_gebruikers']; ?></h4>
                                    <p class="mb-0">Totaal Gebruikers</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo $data['totaal_reserveringen']; ?></h4>
                                    <p class="mb-0">Totaal Reserveringen</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>€<?php echo number_format($data['omzet_deze_maand'], 2); ?></h4>
                                    <p class="mb-0">Omzet Deze Maand</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-euro-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo $data['actieve_instructeurs']; ?></h4>
                                    <p class="mb-0">Actieve Instructeurs</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistieken Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Maandelijkse Statistieken</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Nieuwe Gebruikers:</strong> <?php echo $data['statistieken']['nieuwe_gebruikers_deze_maand']; ?></p>
                                    <p><strong>Voltooide Lessen:</strong> <?php echo $data['statistieken']['voltooide_lessen_deze_maand']; ?></p>
                                    <p><strong>Conversion Rate:</strong> <?php echo $data['statistieken']['conversion_rate']; ?>%</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Gemiddelde Beoordeling:</strong> 
                                        <?php 
                                        $beoordeling = $data['statistieken']['gemiddelde_beoordeling'];
                                        echo number_format($beoordeling, 1) . '/5';
                                        for($i = 1; $i <= 5; $i++) {
                                            if($i <= $beoordeling) {
                                                echo ' <i class="fas fa-star text-warning"></i>';
                                            } else {
                                                echo ' <i class="far fa-star text-warning"></i>';
                                            }
                                        }
                                        ?>
                                    </p>
                                    <p><strong>Populairste Pakket:</strong> <?php echo $data['statistieken']['populairste_lespakket']; ?></p>
                                    <p><strong>Populairste Locatie:</strong> <?php echo $data['statistieken']['populairste_locatie']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Snelle Acties</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo URLROOT; ?>/eigenaar/gebruikers" class="btn btn-primary">
                                    <i class="fas fa-users me-2"></i>Beheer Gebruikers
                                </a>
                                <a href="<?php echo URLROOT; ?>/eigenaar/betalingen" class="btn btn-success">
                                    <i class="fas fa-credit-card me-2"></i>Bekijk Betalingen
                                </a>
                                <a href="<?php echo URLROOT; ?>/eigenaar/rapporten" class="btn btn-info">
                                    <i class="fas fa-chart-bar me-2"></i>Genereer Rapporten
                                </a>
                                <a href="<?php echo URLROOT; ?>/eigenaar/instellingen" class="btn btn-secondary">
                                    <i class="fas fa-cogs me-2"></i>Systeem Instellingen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recente Activiteit -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Nieuwe Reserveringen</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($data['recente_activiteit']['nieuwe_reserveringen'])): ?>
                                <?php foreach($data['recente_activiteit']['nieuwe_reserveringen'] as $reservering): ?>
                                    <div class="border-bottom py-2">
                                        <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($reservering->aangemaakt_op)); ?></small>
                                        <p class="mb-1"><strong><?php echo $reservering->klant_naam; ?></strong></p>
                                        <p class="mb-0 text-muted"><?php echo $reservering->lespakket_naam; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Geen recente reserveringen</p>
                            <?php endif; ?>
                            <div class="mt-3">
                                <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-sm btn-outline-primary">Alle reserveringen</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recente Betalingen</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($data['recente_activiteit']['recente_betalingen'])): ?>
                                <?php foreach($data['recente_activiteit']['recente_betalingen'] as $betaling): ?>
                                    <div class="border-bottom py-2">
                                        <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($betaling->betaald_op)); ?></small>
                                        <p class="mb-1"><strong>€<?php echo number_format($betaling->bedrag, 2); ?></strong></p>
                                        <p class="mb-0 text-muted"><?php echo $betaling->klant_naam; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Geen recente betalingen</p>
                            <?php endif; ?>
                            <div class="mt-3">
                                <a href="<?php echo URLROOT; ?>/eigenaar/betalingen" class="btn btn-sm btn-outline-success">Alle betalingen</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Nieuwe Gebruikers</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($data['recente_activiteit']['nieuwe_gebruikers'])): ?>
                                <?php foreach($data['recente_activiteit']['nieuwe_gebruikers'] as $gebruiker): ?>
                                    <div class="border-bottom py-2">
                                        <small class="text-muted"><?php echo date('d-m-Y H:i', strtotime($gebruiker->aangemaakt_op)); ?></small>
                                        <p class="mb-1"><strong><?php echo $gebruiker->voornaam . ' ' . $gebruiker->achternaam; ?></strong></p>
                                        <p class="mb-0 text-muted">
                                            <span class="badge bg-<?php echo $gebruiker->role == 'instructeur' ? 'warning' : 'primary'; ?>">
                                                <?php echo ucfirst($gebruiker->role); ?>
                                            </span>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Geen nieuwe gebruikers</p>
                            <?php endif; ?>
                            <div class="mt-3">
                                <a href="<?php echo URLROOT; ?>/eigenaar/gebruikers" class="btn btn-sm btn-outline-primary">Alle gebruikers</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>