<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="display-6 fw-bold text-primary-gradient mb-0">
                    <i class="fas fa-plus-circle me-3"></i>Nieuwe Reservering
                </h1>
            </div>

            <?php flash('reservering_message'); ?>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Reserveer je Kitesurfles
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/reserveringen/maken" method="POST">
                        <div class="row">
                            <!-- Lespakket Selection -->
                            <div class="col-md-6 mb-4">
                                <label for="lespakket_id" class="form-label">Lespakket *</label>
                                <select class="form-control <?php echo (!empty($data['lespakket_id_err'])) ? 'is-invalid' : ''; ?>" 
                                        id="lespakket_id" name="lespakket_id" required>
                                    <option value="">Kies een lespakket...</option>
                                    <?php foreach ($data['lespakketten'] as $lespakket): ?>
                                        <option value="<?php echo $lespakket->id; ?>" 
                                                <?php echo ($data['lespakket_id'] == $lespakket->id) ? 'selected' : ''; ?>
                                                data-prijs="<?php echo $lespakket->prijs; ?>"
                                                data-beschrijving="<?php echo htmlspecialchars($lespakket->beschrijving); ?>">
                                            <?php echo $lespakket->naam; ?> - €<?php echo number_format($lespakket->prijs, 2); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo $data['lespakket_id_err']; ?>
                                </div>
                                <div id="lespakketInfo" class="form-text"></div>
                            </div>

                            <!-- Locatie Selection -->
                            <div class="col-md-6 mb-4">
                                <label for="locatie_id" class="form-label">Locatie *</label>
                                <select class="form-control <?php echo (!empty($data['locatie_id_err'])) ? 'is-invalid' : ''; ?>" 
                                        id="locatie_id" name="locatie_id" required>
                                    <option value="">Kies een locatie...</option>
                                    <?php foreach ($data['locaties'] as $locatie): ?>
                                        <option value="<?php echo $locatie->id; ?>" 
                                                <?php echo ($data['locatie_id'] == $locatie->id) ? 'selected' : ''; ?>
                                                data-faciliteiten="<?php echo htmlspecialchars($locatie->faciliteiten); ?>">
                                            <?php echo $locatie->naam; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo $data['locatie_id_err']; ?>
                                </div>
                                <div id="locatieInfo" class="form-text"></div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Gewenste Datum -->
                            <div class="col-md-6 mb-4">
                                <label for="gewenste_datum" class="form-label">Gewenste Datum *</label>
                                <input type="date" 
                                       class="form-control <?php echo (!empty($data['gewenste_datum_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="gewenste_datum" name="gewenste_datum" 
                                       value="<?php echo $data['gewenste_datum']; ?>"
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                <div class="invalid-feedback">
                                    <?php echo $data['gewenste_datum_err']; ?>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Reservering minimaal 1 dag van tevoren
                                </div>
                            </div>

                            <!-- Duo Partner -->
                            <div class="col-md-6 mb-4">
                                <label for="duo_partner_email" class="form-label">Duo Partner Email (optioneel)</label>
                                <input type="email" 
                                       class="form-control <?php echo (!empty($data['duo_partner_email_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="duo_partner_email" name="duo_partner_email" 
                                       value="<?php echo $data['duo_partner_email']; ?>"
                                       placeholder="partner@email.com">
                                <div class="invalid-feedback">
                                    <?php echo $data['duo_partner_email_err']; ?>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-user-friends me-1"></i>
                                    Email van je duo partner (moet geregistreerd zijn)
                                </div>
                            </div>
                        </div>

                        <!-- Opmerking -->
                        <div class="mb-4">
                            <label for="opmerking" class="form-label">Opmerking (optioneel)</label>
                            <textarea class="form-control" id="opmerking" name="opmerking" rows="3"
                                      placeholder="Bijv. ervaring, speciale wensen, medische info..."><?php echo $data['opmerking']; ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-comment me-1"></i>
                                Deel relevante informatie met je instructeur
                            </div>
                        </div>

                        <!-- Prijs Overzicht -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-calculator me-2"></i>Prijs Overzicht
                                </h5>
                                <div id="prijsOverzicht">
                                    <p class="text-muted">Selecteer een lespakket om de prijs te zien</p>
                                </div>
                            </div>
                        </div>

                        <!-- Voorwaarden -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="voorwaarden" name="voorwaarden" required>
                                <label class="form-check-label" for="voorwaarden">
                                    Ik ga akkoord met de <a href="#" class="text-primary">algemene voorwaarden</a> 
                                    en het <a href="#" class="text-primary">privacy beleid</a> *
                                </label>
                            </div>
                        </div>

                        <!-- Belangrijk Info -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Belangrijk
                            </h6>
                            <ul class="mb-0">
                                <li>Je ontvangt een bevestigingsmail met betalingsgegevens</li>
                                <li>De instructeur neemt binnen 24 uur contact op voor tijdbevestiging</li>
                                <li>Lessen zijn afhankelijk van weersomstandigheden</li>
                                <li>Annulering is gratis tot 24 uur van tevoren</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                <i class="fas fa-check me-2"></i>Reservering Bevestigen
                            </button>
                            <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Annuleren
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hulp Sectie -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Hulp Nodig?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar-alt me-2"></i>Beschikbaarheid</h6>
                            <p class="small">
                                <a href="<?php echo URLROOT; ?>/reserveringen/beschikbaarheid" class="text-primary">
                                    Bekijk beschikbaarheid per locatie en datum
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-phone me-2"></i>Direct Contact</h6>
                            <p class="small">
                                Vragen? Bel ons op <a href="tel:0612345678" class="text-primary">06-12345678</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update lespakket info when selection changes
document.getElementById('lespakket_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('lespakketInfo');
    const prijsDiv = document.getElementById('prijsOverzicht');
    
    if (selected.value) {
        const beschrijving = selected.getAttribute('data-beschrijving');
        const prijs = selected.getAttribute('data-prijs');
        
        infoDiv.innerHTML = `<i class="fas fa-info-circle me-1"></i>${beschrijving}`;
        prijsDiv.innerHTML = `
            <div class="d-flex justify-content-between">
                <span>Lespakket:</span>
                <strong>€${parseFloat(prijs).toFixed(2)}</strong>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between">
                <strong>Totaal:</strong>
                <strong class="text-primary">€${parseFloat(prijs).toFixed(2)}</strong>
            </div>
        `;
    } else {
        infoDiv.innerHTML = '';
        prijsDiv.innerHTML = '<p class="text-muted">Selecteer een lespakket om de prijs te zien</p>';
    }
});

// Update locatie info when selection changes  
document.getElementById('locatie_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('locatieInfo');
    
    if (selected.value) {
        const faciliteiten = selected.getAttribute('data-faciliteiten');
        infoDiv.innerHTML = `<i class="fas fa-map-marker-alt me-1"></i>${faciliteiten}`;
    } else {
        infoDiv.innerHTML = '';
    }
});

// Trigger initial update if form has values
document.addEventListener('DOMContentLoaded', function() {
    const lespakketSelect = document.getElementById('lespakket_id');
    const locatieSelect = document.getElementById('locatie_id');
    
    if (lespakketSelect.value) {
        lespakketSelect.dispatchEvent(new Event('change'));
    }
    
    if (locatieSelect.value) {
        locatieSelect.dispatchEvent(new Event('change'));
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const voorwaarden = document.getElementById('voorwaarden');
    
    if (!voorwaarden.checked) {
        e.preventDefault();
        alert('Je moet akkoord gaan met de voorwaarden om een reservering te maken.');
        voorwaarden.focus();
        return false;
    }
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>