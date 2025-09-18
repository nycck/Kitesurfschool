<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Reservering Bewerken
                    </h3>
                    <p class="mb-0 mt-2">Wijzig je reserveringsgegevens</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Reservering Info -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i><?= htmlspecialchars($data['reservering']->lespakket_naam) ?></h5>
                        <p class="mb-2"><?= htmlspecialchars($data['reservering']->lespakket_beschrijving) ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-user text-primary me-2"></i><?= htmlspecialchars($data['reservering']->voornaam . ' ' . $data['reservering']->achternaam) ?></li>
                                    <li><i class="fas fa-calendar text-primary me-2"></i>Reservering #<?= $data['reservering']->id ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-clock text-primary me-2"></i><?= $data['reservering']->totale_uren ?> uur totaal</li>
                                    <li><i class="fas fa-euro-sign text-primary me-2"></i><?= formatMoney($data['reservering']->prijs_per_persoon) ?> per persoon</li>
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
                    
                    <form method="POST" action="<?= URLROOT ?>/reservering/bewerken/<?= $data['reservering']->id ?>" id="bewerkForm">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="locatie_id" class="form-label">Gewenste locatie *</label>
                                <select class="form-select" id="locatie_id" name="locatie_id" required>
                                    <option value="">Kies een locatie...</option>
                                    <?php foreach ($data['locaties'] as $locatie): ?>
                                        <option value="<?= $locatie->id ?>" <?= $data['reservering']->locatie_id == $locatie->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($locatie->naam) ?> - <?= htmlspecialchars($locatie->adres) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="aantal_personen" class="form-label">Aantal personen *</label>
                                <select class="form-select" id="aantal_personen" name="aantal_personen" required>
                                    <?php for ($i = 1; $i <= $data['reservering']->max_personen; $i++): ?>
                                        <option value="<?= $i ?>" <?= $data['reservering']->aantal_personen == $i ? 'selected' : '' ?>>
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
                                   value="<?= htmlspecialchars($data['reservering']->gewenste_datum) ?>"
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
                                      placeholder="Eventuele wensen of opmerkingen..."><?= htmlspecialchars($data['reservering']->opmerkingen ?? '') ?></textarea>
                        </div>

                        <!-- Nieuwe prijs overzicht -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calculator me-2"></i>Nieuwe prijs overzicht
                                </h6>
                                <div class="d-flex justify-content-between">
                                    <span><?= formatMoney($data['reservering']->prijs_per_persoon) ?> × <span id="prijs-aantal-personen"><?= $data['reservering']->aantal_personen ?></span> persoon/personen</span>
                                    <strong id="totale-prijs"><?= formatMoney($data['reservering']->totale_prijs) ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= URLROOT ?>/klant/dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Terug naar Dashboard
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save me-2"></i>Wijzigingen Opslaan
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
    const prijsPerPersoon = <?= $data['reservering']->prijs_per_persoon ?>;
    const totalePrijs = aantalPersonen * prijsPerPersoon;
    
    document.getElementById('prijs-aantal-personen').textContent = aantalPersonen;
    document.getElementById('totale-prijs').textContent = '€' + totalePrijs.toFixed(2).replace('.', ',');
});

// Form submit loading state
document.getElementById('bewerkForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Bezig met opslaan...';
    submitBtn.disabled = true;
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>