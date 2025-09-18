<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <section class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary-gradient mb-4">
            Over Kitesurfschool Windkracht-12
        </h1>
        <p class="lead">
            Al 8 jaar dé specialist in kitesurflessen aan de Nederlandse kust
        </p>
    </section>

    <!-- Over Ons Section -->
    <section class="mb-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <h2 class="mb-4">Onze Geschiedenis</h2>
                <p class="lead">
                    Kitesurfschool Windkracht-12 werd 8 jaar geleden opgericht door Terence Olieslager 
                    uit passie voor de kitesurfsport en de wens om deze fantastische sport veilig 
                    en professioneel te onderwijzen.
                </p>
                <p>
                    Vanuit Utrecht rijden onze ervaren instructeurs naar de mooiste locaties 
                    langs de Nederlandse kust om jullie de technieken van het kitesurfen bij te brengen. 
                    Met onze moderne uitrusting en kleine groepsgroottes garanderen we persoonlijke 
                    aandacht en snelle voortgang.
                </p>
                <div class="row mt-4">
                    <div class="col-6 text-center">
                        <div class="stat-number text-primary">8</div>
                        <div class="stat-label">Jaar Ervaring</div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="stat-number text-primary">6</div>
                        <div class="stat-label">Instructeurs</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= URLROOT ?>img/about-kitesurfing.jpg" 
                     alt="Kitesurfschool Windkracht-12 team" 
                     class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Ons Ervaren Team</h2>
        <div class="row">
            <!-- Eigenaar -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-user-tie fa-4x text-success mb-3"></i>
                        <h5 class="card-title">Terence Olieslager</h5>
                        <p class="card-text text-muted">Eigenaar & Oprichter</p>
                        <p class="card-text">
                            Oprichter van Windkracht-12 met meer dan 10 jaar ervaring 
                            in de kitesurfsport. Gepassioneerd over veilig en effectief lesgeven.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Instructeurs -->
            <?php 
            $instructeurs = [
                ['naam' => 'Duco Veenstra', 'specialiteit' => 'Beginner lessen'],
                ['naam' => 'Waldemar van Dongen', 'specialiteit' => 'Gevorderde technieken'],
                ['naam' => 'Ruud Terlingen', 'specialiteit' => 'Freestyle & jumps'],
                ['naam' => 'Saskia Brink', 'specialiteit' => 'Vrouwengroepen'],
                ['naam' => 'Bernie Vredenstein', 'specialiteit' => 'Race & speed']
            ];
            
            foreach ($instructeurs as $instructeur): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                        <h5 class="card-title"><?= $instructeur['naam'] ?></h5>
                        <p class="card-text text-muted">Instructeur</p>
                        <p class="card-text">
                            Gespecialiseerd in: <strong><?= $instructeur['specialiteit'] ?></strong>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Waarom Windkracht-12 Section -->
    <section class="bg-light py-5 mb-5 rounded">
        <div class="container">
            <h2 class="text-center mb-5">Waarom Kitesurfschool Windkracht-12?</h2>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="text-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Veiligheid Eerst</h5>
                        <p>Alle lessen starten met uitgebreide veiligheidsinstructies en we gebruiken alleen gecertificeerde materialen.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5>Kleine Groepen</h5>
                        <p>Maximaal 2 personen per instructeur zorgt voor persoonlijke aandacht en snelle voortgang.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="text-center">
                        <i class="fas fa-medal fa-3x text-primary mb-3"></i>
                        <h5>Ervaren Instructeurs</h5>
                        <p>Al onze instructeurs zijn gecertificeerd en hebben jaren ervaring in het lesgeven.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="text-center">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5>Complete Uitrusting</h5>
                        <p>Kite, board, wetsuit en alle veiligheidsuitrusting zijn inbegrepen bij elke les.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Locaties Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Onze Locaties</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <p class="text-center lead mb-4">
                    We geven les op 6 fantastische locaties langs de Nederlandse kust, 
                    elk met hun eigen unieke eigenschappen en wind condities.
                </p>
                <div class="row">
                    <?php 
                    $locaties = [
                        'Zandvoort' => 'Breed strand met goede wind condities',
                        'Muiderberg' => 'Rustige locatie ideaal voor beginners',
                        'Wijk aan Zee' => 'Populaire kitespot met veel ruimte',
                        'IJmuiden' => 'Goede wind en golven voor gevorderden',
                        'Scheveningen' => 'Bekende kitespot met faciliteiten',
                        'Hoek van Holland' => 'Wind uit verschillende richtingen'
                    ];
                    
                    foreach ($locaties as $naam => $beschrijving): ?>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-1"><?= $naam ?></h6>
                                <p class="text-muted mb-0"><?= $beschrijving ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="text-center">
        <div class="bg-primary text-white p-5 rounded">
            <h3 class="mb-3">Klaar om te Beginnen?</h3>
            <p class="lead mb-4">
                Sluit je aan bij honderden tevreden kitesurfers die bij ons hebben geleerd!
            </p>
            <?php if (isLoggedIn()): ?>
            <a href="<?= URLROOT ?>/reserveringen/maken" class="btn btn-light btn-lg">
                <i class="fas fa-calendar-plus me-2"></i>Reserveer Nu
            </a>
            <?php else: ?>
            <a href="<?= URLROOT ?>/auth/register" class="btn btn-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>Registreer Nu
            </a>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>