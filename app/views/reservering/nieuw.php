<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Nieuwe Reservering
                    </h3>
                    <p class="mb-0 mt-2">Reserveer je kitesurfles</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Lespakket Info -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i><?= htmlspecialchars($data['lespakket']->naam) ?></h5>
                        <p class="mb-2"><?= htmlspecialchars($data['lespakket']->beschrijving) ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-clock text-primary me-2"></i><?= $data['lespakket']->totale_uren ?> uur totaal</li>
                                    <li><i class="fas fa-calendar text-primary me-2"></i><?= $data['lespakket']->aantal_lessen ?> les(sen)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-users text-primary me-2"></i>Max <?= $data['lespakket']->max_personen ?> personen</li>
                                    <li><i class="fas fa-euro-sign text-primary me-2"></i><?= formatMoney($data['lespakket']->prijs_per_persoon) ?> per persoon</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($data['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($data['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= URLROOT ?>/reserveringen/maken" id="reserveringForm">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <!-- Persoonlijke Gegevens -->
                        <h5 class="mb-3 mt-4">
                            <i class="fas fa-user me-2"></i>Persoonlijke Gegevens
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="voornaam" class="form-label">Voornaam *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="voornaam" 
                                       name="voornaam" 
                                       value="<?= htmlspecialchars($data['voornaam']) ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tussenvoegsel" class="form-label">Tussenvoegsel</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="tussenvoegsel" 
                                       name="tussenvoegsel" 
                                       value="<?= htmlspecialchars($data['tussenvoegsel']) ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="achternaam" class="form-label">Achternaam *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="achternaam" 
                                       name="achternaam" 
                                       value="<?= htmlspecialchars($data['achternaam']) ?>"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="geboortedatum" class="form-label">Geboortedatum *</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="geboortedatum" 
                                       name="geboortedatum" 
                                       value="<?= htmlspecialchars($data['geboortedatum']) ?>"
                                       max="<?= date('Y-m-d', strtotime('-12 years')) ?>"
                                       required>
                                <div class="form-text">Je moet minimaal 12 jaar oud zijn</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefoonnummer" class="form-label">Telefoonnummer *</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="telefoonnummer" 
                                   name="telefoonnummer" 
                                   value="<?= htmlspecialchars($data['telefoonnummer']) ?>"
                                   placeholder="06-12345678"
                                   required>
                        </div>

                        <!-- Reserveringsdetails -->
                        <h5 class="mb-3 mt-4">
                            <i class="fas fa-calendar-alt me-2"></i>Reserveringsdetails
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="locatie_id" class="form-label">Gewenste locatie *</label>
                                <select class="form-select" id="locatie_id" name="locatie_id" required>
                                    <option value="">Kies een locatie...</option>
                                    <?php foreach ($data['locaties'] as $locatie): ?>
                                        <option value="<?= $locatie->id ?>" <?= $data['locatie_id'] == $locatie->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($locatie->naam) ?> - <?= htmlspecialchars($locatie->adres) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="aantal_personen" class="form-label">Aantal personen *</label>
                                <select class="form-select" id="aantal_personen" name="aantal_personen" required>
                                    <?php for ($i = 1; $i <= $data['lespakket']->max_personen; $i++): ?>
                                        <option value="<?= $i ?>" <?= $data['aantal_personen'] == $i ? 'selected' : '' ?>>
                                            <?= $i ?> <?= $i == 1 ? 'persoon' : 'personen' ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gewenste_datum" class="form-label">Gewenste startdatum *</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="gewenste_datum" 
                                   name="gewenste_datum" 
                                   value="<?= htmlspecialchars($data['gewenste_datum']) ?>"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   required>
                            <div class="form-text">We plannen de lessen in overleg met jou</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="opmerkingen" class="form-label">Opmerkingen</label>
                            <textarea class="form-control" 
                                      id="opmerkingen" 
                                      name="opmerkingen" 
                                      rows="3"
                                      placeholder="Eventuele wensen of opmerkingen..."><?= htmlspecialchars($data['opmerkingen']) ?></textarea>
                        </div>

                        <!-- Prijs overzicht -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calculator me-2"></i>Prijs overzicht
                                </h6>
                                <div class="d-flex justify-content-between">
                                    <span><?= formatMoney($data['lespakket']->prijs_per_persoon) ?> × <span id="prijs-aantal-personen">1</span> persoon/personen</span>
                                    <strong id="totale-prijs"><?= formatMoney($data['lespakket']->prijs_per_persoon) ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= URLROOT ?>/homepages/pakketten" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Terug naar Pakketten
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus me-2"></i>Reservering Aanmaken
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Prijs berekening update
document.getElementById('aantal_personen').addEventListener('change', function() {
    const aantalPersonen = parseInt(this.value);
    const prijsPerPersoon = <?= $data['lespakket']->prijs_per_persoon ?>;
    const totalePrijs = aantalPersonen * prijsPerPersoon;
    
    document.getElementById('prijs-aantal-personen').textContent = aantalPersonen;
    document.getElementById('totale-prijs').textContent = '€' + totalePrijs.toFixed(2).replace('.', ',');
});

// Form submit loading state
document.getElementById('reserveringForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Bezig met reserveren...';
    submitBtn.disabled = true;
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>