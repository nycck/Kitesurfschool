<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <!-- Hero Section -->
    <section class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary-gradient mb-4">
            Onze Lespakketten
        </h1>
        <p class="lead">
            Van beginners tot gevorderden - wij hebben het perfecte lespakket voor jou
        </p>
    </section>

    <!-- Lespakketten Grid -->
    <section class="mb-5">
        <?php if (!empty($data['lespakketten'])): ?>
            <div class="row">
                <?php foreach ($data['lespakketten'] as $pakket): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="mb-0"><?= htmlspecialchars($pakket->naam) ?></h4>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-light text-primary fs-6">
                                        <?= formatMoney($pakket->prijs_per_persoon) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <p class="card-text mb-4"><?= htmlspecialchars($pakket->beschrijving) ?></p>
                            
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span><strong><?= $pakket->totale_uren ?> uur</strong> totaal</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <span><strong><?= $pakket->aantal_lessen ?></strong> les<?= $pakket->aantal_lessen > 1 ? 'sen' : '' ?></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <span>Max <strong><?= $pakket->max_personen ?></strong> <?= $pakket->max_personen == 1 ? 'persoon' : 'personen' ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Materialen <strong>inbegrepen</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Wat is inbegrepen -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">Inbegrepen:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Professionele kite</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Kiteboard</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Wetsuit</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Harnas en helm</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Gecertificeerde instructeur</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Veiligheidsbriefing</li>
                                </ul>
                            </div>

                            <div class="text-center">
                                <?php if (isLoggedIn()): ?>
                                <a href="<?= URLROOT ?>reservering/nieuw/<?= $pakket->id ?>" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-calendar-plus me-2"></i>Reserveer Dit Pakket
                                </a>
                                <?php else: ?>
                                <a href="<?= URLROOT ?>auth/register" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>Registreer om te Reserveren
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h3>Geen lespakketten beschikbaar</h3>
                <p class="text-muted">Momenteel zijn er geen lespakketten beschikbaar. Neem contact met ons op voor meer informatie.</p>
                <a href="<?= URLROOT ?>homepages/contact" class="btn btn-primary">
                    <i class="fas fa-envelope me-1"></i>Contact Opnemen
                </a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Vergelijking Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Pakket Vergelijking</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Pakket</th>
                        <th>Aantal Lessen</th>
                        <th>Totale Uren</th>
                        <th>Max Personen</th>
                        <th>Prijs per Persoon</th>
                        <th>Ideaal Voor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Privéles</strong></td>
                        <td>1</td>
                        <td>2,5 uur</td>
                        <td>1</td>
                        <td><?= formatMoney(175) ?></td>
                        <td>Persoonlijke aandacht, snelle voortgang</td>
                    </tr>
                    <tr>
                        <td><strong>Losse Duo Kiteles</strong></td>
                        <td>1</td>
                        <td>3,5 uur</td>
                        <td>2</td>
                        <td><?= formatMoney(135) ?></td>
                        <td>Proefles, samen leren</td>
                    </tr>
                    <tr>
                        <td><strong>Duo Lespakket 3 lessen</strong></td>
                        <td>3</td>
                        <td>10,5 uur</td>
                        <td>2</td>
                        <td><?= formatMoney(375) ?></td>
                        <td>Complete basiscursus</td>
                    </tr>
                    <tr>
                        <td><strong>Duo Lespakket 5 lessen</strong></td>
                        <td>5</td>
                        <td>17,5 uur</td>
                        <td>2</td>
                        <td><?= formatMoney(675) ?></td>
                        <td>Uitgebreide cursus, zelfstandig kitesurfen</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Info Section -->
    <section class="mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-info">
                    <h5 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Belangrijk om te Weten
                    </h5>
                    <ul class="mb-0">
                        <li>Alle materialen (kite, board, wetsuit) zijn bij de prijs inbegrepen</li>
                        <li>Lessen worden gegeven op verschillende locaties langs de Nederlandse kust</li>
                        <li>Groepsgrootte is beperkt tot maximaal 2 personen per instructeur</li>
                        <li>Betaling vindt plaats na reservering via bankoverschrijving</li>
                        <li>Lessen zijn afhankelijk van weersomstandigheden (wind 4-7 Beaufort)</li>
                        <li>Minimumleeftijd is 12 jaar, zwemvaardigheid is vereist</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="mb-5">
        <h2 class="text-center mb-5">Veelgestelde Vragen</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Wat als het weer niet meewerkt?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Bij ongeschikte weersomstandigheden (te weinig of te veel wind) wordt de les gratis verplaatst naar een andere datum. Veiligheid staat altijd voorop.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Moet ik zelf uitrusting meenemen?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Nee, alle benodigde uitrusting is inbegrepen: kite, board, wetsuit, harnas en veiligheidshelm. Je hoeft alleen zwemkleding en een handdoek mee te nemen.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Kan ik alleen deelnemen aan duo lessen?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Ja, je kunt alleen deelnemen aan duo lessen. We koppelen je dan aan een andere cursist van vergelijkbaar niveau, of je kunt kiezen voor een privéles.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Hoe ver van tevoren moet ik reserveren?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We raden aan om minimaal 1 week van tevoren te reserveren, vooral in het hoogseizoen (mei-september). Voor last-minute reserveringen kun je altijd contact opnemen.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <?php if (!isLoggedIn()): ?>
    <section class="text-center">
        <div class="bg-primary text-white p-5 rounded">
            <h3 class="mb-3">Klaar voor je Eerste Kitesurfles?</h3>
            <p class="lead mb-4">
                Registreer je vandaag nog en boek je ideale lespakket!
            </p>
            <a href="<?= URLROOT ?>auth/register" class="btn btn-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>Registreer Nu
            </a>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>