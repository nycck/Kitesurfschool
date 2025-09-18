<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <section class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary-gradient mb-4">
            Onze Kitesurflocaties
        </h1>
        <p class="lead">
            6 fantastische spots langs de Nederlandse kust voor optimale kitesurfbeleving
        </p>
    </section>

    <!-- Locaties Grid -->
    <section class="mb-5">
        <?php if (!empty($data['locaties'])): ?>
            <div class="row">
                <?php foreach ($data['locaties'] as $index => $locatie): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <?= htmlspecialchars($locatie->naam) ?>
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                <i class="fas fa-location-arrow me-1"></i>
                                <?= htmlspecialchars($locatie->adres) ?>
                            </p>
                            <p class="card-text mb-4"><?= htmlspecialchars($locatie->beschrijving) ?></p>
                            
                            <!-- Locatie kenmerken -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary mb-2">Kenmerken:</h6>
                                    <?php 
                                    // Voeg specifieke kenmerken toe per locatie
                                    $kenmerken = [];
                                    switch(strtolower($locatie->naam)) {
                                        case 'zandvoort':
                                            $kenmerken = ['Breed strand', 'Goede bereikbaarheid', 'Faciliteiten aanwezig', 'Geschikt voor beginners'];
                                            break;
                                        case 'muiderberg':
                                            $kenmerken = ['Rustige omgeving', 'Ideaal voor beginners', 'Minder druk', 'Goede windcondities'];
                                            break;
                                        case 'wijk aan zee':
                                            $kenmerken = ['Populaire spot', 'Veel ruimte', 'Constante wind', 'Kitesurfcommunity'];
                                            break;
                                        case 'ijmuiden':
                                            $kenmerken = ['Sterke wind', 'Golven aanwezig', 'Voor gevorderden', 'Uitdagende condities'];
                                            break;
                                        case 'scheveningen':
                                            $kenmerken = ['Bekende locatie', 'Veel faciliteiten', 'Parkeerplaatsen', 'Horeca nabij'];
                                            break;
                                        case 'hoek van holland':
                                            $kenmerken = ['Variabele wind', 'Mooi strand', 'Minder druk', 'Natuurlijke omgeving'];
                                            break;
                                        default:
                                            $kenmerken = ['Geschikt voor kitesurfen', 'Professionele begeleiding', 'Veilige omgeving'];
                                    }
                                    ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($kenmerken as $kenmerk): ?>
                                        <li><i class="fas fa-check text-success me-2"></i><?= $kenmerk ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Instructeurs rijden vanuit Utrecht naar deze locatie
                            </small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h3>Geen locaties beschikbaar</h3>
                <p class="text-muted">Momenteel zijn er geen locaties beschikbaar. Neem contact met ons op voor meer informatie.</p>
                <a href="<?= URLROOT ?>homepages/contact" class="btn btn-primary">
                    <i class="fas fa-envelope me-1"></i>Contact Opnemen
                </a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Locatie Kaart Info -->
    <section class="mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-info text-white text-center">
                        <h5 class="mb-0">
                            <i class="fas fa-map me-2"></i>Locatie-overzicht Nederlandse Kust
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-primary">Noord-Holland</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-map-marker-alt text-primary me-1"></i> Zandvoort</li>
                                    <li><i class="fas fa-map-marker-alt text-primary me-1"></i> Muiderberg</li>
                                    <li><i class="fas fa-map-marker-alt text-primary me-1"></i> Wijk aan Zee</li>
                                    <li><i class="fas fa-map-marker-alt text-primary me-1"></i> IJmuiden</li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-primary">Zuid-Holland</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-map-marker-alt text-primary me-1"></i> Scheveningen</li>
                                    <li><i class="fas fa-map-marker-alt text-primary me-1"></i> Hoek van Holland</li>
                                </ul>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Alle locaties zijn goed bereikbaar vanaf Utrecht en bieden optimale kitesurfcondities
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wind & Weer Info -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Wind & Weersomstandigheden</h2>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-wind fa-3x text-primary mb-3"></i>
                        <h5>Optimale Windkracht</h5>
                        <p class="card-text">
                            Wij geven les bij windkracht 4-7 Beaufort (15-30 knopen) voor optimale veiligheid en leerervaaring.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-thermometer-half fa-3x text-primary mb-3"></i>
                        <h5>Seizoen</h5>
                        <p class="card-text">
                            Hoofdseizoen is april-oktober. Ook in de winter geven we les bij geschikte omstandigheden.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                        <h5>Veiligheid</h5>
                        <p class="card-text">
                            Bij onveilige omstandigheden (storm, te weinig wind) wordt de les verplaatst naar een andere datum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bereikbaarheid Section -->
    <section class="mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-primary">
                    <h5 class="alert-heading">
                        <i class="fas fa-car me-2"></i>Bereikbaarheid & Parkeren
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Met de Auto:</h6>
                            <ul class="mb-3">
                                <li>Alle locaties zijn goed bereikbaar via de snelweg</li>
                                <li>Parkeerplaatsen beschikbaar (mogelijk betaald)</li>
                                <li>GPS-coördinaten worden bij reservering verstrekt</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Openbaar Vervoer:</h6>
                            <ul class="mb-0">
                                <li>Treinstations nabij Zandvoort en Scheveningen</li>
                                <li>Busverbindingen naar andere locaties</li>
                                <li>Vraag naar mogelijkheden bij reservering</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tips Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Tips voor je Eerste Bezoek</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-clock text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Kom op tijd</h6>
                                <p class="mb-0">Arriveer 15 minuten voor aanvang voor de uitrusting en briefing.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-tshirt text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Juiste Kleding</h6>
                                <p class="mb-0">Zwemkleding onder de wetsuit, handdoek en warme kleren voor erna.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-sun text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Zonbescherming</h6>
                                <p class="mb-0">Zonnebrandcrème en zonnebril, ook bij bewolkt weer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex">
                            <i class="fas fa-mobile-alt text-primary me-3 mt-1"></i>
                            <div>
                                <h6>Contact</h6>
                                <p class="mb-0">Sla het telefoonnummer van je instructeur op voor laatste updates.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="text-center">
        <div class="bg-primary text-white p-5 rounded">
            <h3 class="mb-3">Kies je Perfecte Locatie</h3>
            <p class="lead mb-4">
                Elke locatie biedt unieke mogelijkheden. Laat je adviseren door onze ervaren instructeurs!
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= URLROOT ?>homepages/pakketten" class="btn btn-light btn-lg">
                    <i class="fas fa-list me-2"></i>Bekijk Lespakketten
                </a>
                <?php if (isLoggedIn()): ?>
                <a href="<?= URLROOT ?>reservering/nieuw" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-calendar-plus me-2"></i>Reserveer Nu
                </a>
                <?php else: ?>
                <a href="<?= URLROOT ?>auth/register" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Registreer Nu
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>