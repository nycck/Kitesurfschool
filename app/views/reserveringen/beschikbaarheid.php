<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center mb-4">
                <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="display-6 fw-bold text-primary-gradient mb-0">
                    <i class="fas fa-search me-3"></i>Beschikbaarheid Bekijken
                </h1>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Zoek Beschikbare Tijden
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo URLROOT; ?>/reserveringen/beschikbaarheid">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="locatie_id" class="form-label">Locatie *</label>
                                <select class="form-control" id="locatie_id" name="locatie_id" required>
                                    <option value="">Kies een locatie...</option>
                                    <?php foreach ($data['locaties'] as $locatie): ?>
                                        <option value="<?php echo $locatie->id; ?>" 
                                                <?php echo (isset($data['geselecteerde_locatie']) && $data['geselecteerde_locatie'] == $locatie->id) ? 'selected' : ''; ?>>
                                            <?php echo $locatie->naam; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="datum" class="form-label">Datum *</label>
                                <input type="date" class="form-control" id="datum" name="datum" 
                                       value="<?php echo isset($data['geselecteerde_datum']) ? $data['geselecteerde_datum'] : ''; ?>"
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Beschikbaarheid Zoeken
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($data['beschikbaarheid'])): ?>
                <div class="card mt-4 shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Beschikbaarheid voor 
                            <?php 
                            $selectedLocatie = null;
                            foreach ($data['locaties'] as $locatie) {
                                if ($locatie->id == $data['geselecteerde_locatie']) {
                                    $selectedLocatie = $locatie;
                                    break;
                                }
                            }
                            echo $selectedLocatie ? $selectedLocatie->naam : 'Onbekende locatie';
                            ?>
                            op <?php echo date('d-m-Y', strtotime($data['geselecteerde_datum'])); ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($data['beschikbaarheid'])): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Geen beschikbare tijden</h5>
                                <p class="text-muted">Er zijn geen beschikbare instructeurs op deze datum.</p>
                                <a href="<?php echo URLROOT; ?>/reserveringen/maken" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Probeer een andere datum
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php 
                                $tijdslots = [
                                    '09:00' => 'Ochtend',
                                    '11:00' => 'Late Ochtend', 
                                    '13:00' => 'Middag',
                                    '15:00' => 'Namiddag',
                                    '17:00' => 'Vroege Avond'
                                ];
                                
                                foreach ($tijdslots as $tijd => $label): 
                                    $beschikbaar = rand(0, 1); // Simulate availability
                                    $instructeurs = rand(1, 3); // Simulate number of instructors
                                ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card <?php echo $beschikbaar ? 'border-success' : 'border-danger'; ?>">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">
                                                    <i class="fas fa-clock me-2"></i><?php echo $tijd; ?>
                                                </h5>
                                                <p class="card-text"><?php echo $label; ?></p>
                                                
                                                <?php if ($beschikbaar): ?>
                                                    <div class="mb-3">
                                                        <span class="badge bg-success fs-6">
                                                            <i class="fas fa-check me-1"></i>Beschikbaar
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php echo $instructeurs; ?> instructeur(s) beschikbaar
                                                        </small>
                                                    </div>
                                                    <a href="<?php echo URLROOT; ?>/reserveringen/maken?datum=<?php echo $data['geselecteerde_datum']; ?>&tijd=<?php echo $tijd; ?>&locatie=<?php echo $data['geselecteerde_locatie']; ?>" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-plus me-1"></i>Reserveren
                                                    </a>
                                                <?php else: ?>
                                                    <div class="mb-3">
                                                        <span class="badge bg-danger fs-6">
                                                            <i class="fas fa-times me-1"></i>Bezet
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">Geen instructeurs beschikbaar</small>
                                                    </div>
                                                    <button class="btn btn-outline-secondary btn-sm" disabled>
                                                        <i class="fas fa-ban me-1"></i>Niet Beschikbaar
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Weersvoorspelling -->
                            <div class="card mt-4 bg-light">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cloud-sun me-2"></i>Weersvoorspelling
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-2">
                                            <div class="weather-item">
                                                <i class="fas fa-wind fa-2x text-primary mb-2"></i>
                                                <h6>Wind</h6>
                                                <p class="mb-0">15-20 knopen</p>
                                                <small class="text-success">Perfect voor kitesurfen</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="weather-item">
                                                <i class="fas fa-thermometer-half fa-2x text-warning mb-2"></i>
                                                <h6>Temperatuur</h6>
                                                <p class="mb-0">22°C</p>
                                                <small class="text-muted">Aangenaam</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="weather-item">
                                                <i class="fas fa-water fa-2x text-info mb-2"></i>
                                                <h6>Water</h6>
                                                <p class="mb-0">19°C</p>
                                                <small class="text-muted">Wetsuit aanbevolen</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="weather-item">
                                                <i class="fas fa-sun fa-2x text-warning mb-2"></i>
                                                <h6>Zicht</h6>
                                                <p class="mb-0">Helder</p>
                                                <small class="text-success">Uitstekende omstandigheden</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Let op:</strong> Lessen kunnen worden geannuleerd bij onveilige weersomstandigheden. 
                                        Je wordt hiervan tijdig op de hoogte gesteld.
                                    </div>
                                </div>
                            </div>

                            <!-- Locatie Details -->
                            <?php if ($selectedLocatie): ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-map-marker-alt me-2"></i>Locatie Informatie
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><strong><?php echo $selectedLocatie->naam; ?></strong></h6>
                                                <p class="text-muted"><?php echo $selectedLocatie->adres; ?></p>
                                                <p><strong>Faciliteiten:</strong><br>
                                                <?php echo $selectedLocatie->faciliteiten; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><strong>Windcondities:</strong></h6>
                                                <ul class="list-unstyled">
                                                    <li><i class="fas fa-check text-success me-2"></i>Goede windrichting: ZW, W, NW</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>Minimale windkracht: 12 knopen</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>Veilige opstartplaats</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>Reddingsservice aanwezig</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Hulp Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Hulp bij Beschikbaarheid
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar-plus me-2"></i>Flexibele Data</h6>
                            <p class="small text-muted">
                                Probeer verschillende data voor meer keuzemogelijkheden. Weekdagen hebben vaak meer beschikbare tijden.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-clock me-2"></i>Beste Tijden</h6>
                            <p class="small text-muted">
                                Ochtend (9:00-11:00) en namiddag (15:00-17:00) zijn meestal het minst druk.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-wind me-2"></i>Weersafhankelijk</h6>
                            <p class="small text-muted">
                                Bij te weinig of te veel wind worden lessen verplaatst. We houden je op de hoogte.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-phone me-2"></i>Direct Contact</h6>
                            <p class="small text-muted">
                                Bel <a href="tel:0612345678">06-12345678</a> voor directe beschikbaarheid of speciale wensen.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center mt-5">
                <h4 class="mb-3">Klaar om te Reserveren?</h4>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?php echo URLROOT; ?>/reserveringen/maken" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Maak Reservering
                    </a>
                    <a href="<?php echo URLROOT; ?>/homepages/contact" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-phone me-2"></i>Contact Opnemen
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.weather-item {
    padding: 15px;
    border-radius: 8px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card.border-success {
    border-width: 2px !important;
}

.card.border-danger {
    border-width: 2px !important;
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>