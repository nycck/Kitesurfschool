<?php require_once APPROOT . '/views/includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5 mb-5" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-wind me-3"></i>
                    Kitesurfschool Windkracht-12
                </h1>
                <p class="lead mb-4">
                    Leer kitesurfen bij de beste kitesurfschool van Nederland. 
                    8 jaar ervaring, professionele instructeurs en complete uitrusting 
                    aan de mooiste locaties van de Nederlandse kust.
                </p>
                
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= URLROOT ?>/homepages/pakketten" class="btn btn-light btn-lg">
                        <i class="fas fa-list me-2"></i>Bekijk Lespakketten
                    </a>
                    <?php if (!isLoggedIn()): ?>
                    <a href="<?= URLROOT ?>/auth/register" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Reserveer Nu
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-6 text-center">
                <img src="<?= URLROOT ?>/img/kitesurf-hero.jpg" 
                     alt="Kitesurfen aan de Nederlandse kust" 
                     class="img-fluid rounded shadow-lg"
                     style="max-height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Over Kitesurfen Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Wat is Kitesurfen?</h2>
                <p class="lead">
                    Kitesurfen is een vorm van watersport waarbij een sporter op een kleine surfplank staat 
                    en zich laat voorttrekken door een kite oftewel vlieger. Bij sterke wind kunnen er 
                    snelheden behaald worden van 100 km/h!
                </p>
                <p>
                    Op dit moment zijn er 117 spots waar de sport kan worden beoefend in Nederland, 
                    voornamelijk bij de kustgebieden. Wij bieden lessen op 6 fantastische locaties.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Lespakketten Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Onze Lespakketten</h2>
        <div class="row">
            <?php if (!empty($data['lespakketten'])): ?>
                <?php foreach ($data['lespakketten'] as $pakket): ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($pakket->naam) ?></h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><?= htmlspecialchars($pakket->beschrijving) ?></p>
                            
                            <div class="mt-auto">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-clock text-primary me-2"></i><?= $pakket->totale_uren ?> uur</li>
                                    <li><i class="fas fa-calendar text-primary me-2"></i><?= $pakket->aantal_lessen ?> les(sen)</li>
                                    <li><i class="fas fa-users text-primary me-2"></i>Max <?= $pakket->max_personen ?> persoon/personen</li>
                                </ul>
                                
                                <div class="price-tag text-center py-2 bg-success text-white rounded mb-3">
                                    <strong><?= formatMoney($pakket->prijs_per_persoon) ?> per persoon</strong>
                                </div>
                                
                                <?php if (isLoggedIn()): ?>
                                <a href="<?= URLROOT ?>/reservering/nieuw/<?= $pakket->id ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-calendar-plus me-1"></i>Reserveren
                                </a>
                                <?php else: ?>
                                <a href="<?= URLROOT ?>/auth/register" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus me-1"></i>Registreer om te Reserveren
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="lead">Momenteel zijn er geen lespakketten beschikbaar.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Locaties Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Onze Locaties</h2>
        <div class="row">
            <?php if (!empty($data['locaties'])): ?>
                <?php foreach ($data['locaties'] as $locatie): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <?= htmlspecialchars($locatie->naam) ?>
                            </h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-location-arrow me-1"></i>
                                <?= htmlspecialchars($locatie->adres) ?>
                            </p>
                            <p class="card-text"><?= htmlspecialchars($locatie->beschrijving) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="lead">Momenteel zijn er geen locaties beschikbaar.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= URLROOT ?>/homepages/locaties" class="btn btn-outline-primary">
                <i class="fas fa-map me-1"></i>Alle Locaties Bekijken
            </a>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Ons Ervaren Team</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <p class="text-center lead mb-4">
                    Onze kitesurfschool werkt met 5 ervaren instructeurs die samen met eigenaar 
                    Terence Olieslager zorgen voor veilige en leuke kitesurflessen.
                </p>
                
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="instructor-card p-3">
                            <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                            <h6>Duco Veenstra</h6>
                            <small class="text-muted">Instructeur</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="instructor-card p-3">
                            <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                            <h6>Waldemar van Dongen</h6>
                            <small class="text-muted">Instructeur</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="instructor-card p-3">
                            <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                            <h6>Ruud Terlingen</h6>
                            <small class="text-muted">Instructeur</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="instructor-card p-3">
                            <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                            <h6>Saskia Brink</h6>
                            <small class="text-muted">Instructeur</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="instructor-card p-3">
                            <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                            <h6>Bernie Vredenstein</h6>
                            <small class="text-muted">Instructeur</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="instructor-card p-3">
                            <i class="fas fa-user-tie fa-3x text-success mb-2"></i>
                            <h6>Terence Olieslager</h6>
                            <small class="text-muted">Eigenaar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<?php if (!isLoggedIn()): ?>
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Klaar om te Beginnen?</h2>
        <p class="lead mb-4">
            Registreer je vandaag nog en boek je eerste kitesurfles. 
            Alle materialen zijn inbegrepen!
        </p>
        <a href="<?= URLROOT ?>/auth/register" class="btn btn-light btn-lg">
            <i class="fas fa-user-plus me-2"></i>Registreer Nu
        </a>
    </div>
</section>
<?php endif; ?>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>