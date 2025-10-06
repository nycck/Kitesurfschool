<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Les Annuleren
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    <?php flash('message'); ?>

                    <!-- Les Details -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Les Gegevens</h6>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Lespakket:</strong></p>
                                <p class="mb-2"><?= htmlspecialchars($data['les']->pakket_naam ?? 'Onbekend') ?></p>
                                
                                <p class="mb-1"><strong>Locatie:</strong></p>
                                <p class="mb-2"><?= htmlspecialchars($data['les']->locatie_naam ?? 'Onbekend') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Datum:</strong></p>
                                <p class="mb-2"><?= isset($data['les']->les_datum) ? date('d-m-Y', strtotime($data['les']->les_datum)) : 'Te bepalen' ?></p>
                                
                                <p class="mb-1"><strong>Tijd:</strong></p>
                                <p class="mb-0"><?= htmlspecialchars($data['les']->start_tijd ?? 'Te bepalen') ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Annulering Waarschuwing -->
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Let Op!</h6>
                        <ul class="mb-0">
                            <li>Annulering moet minimaal 24 uur van tevoren gebeuren</li>
                            <li>Je kunt na goedkeuring een nieuwe datum kiezen</li>
                            <li>Bij annulering binnen 24 uur kunnen er kosten in rekening worden gebracht</li>
                            <li>De instructeur wordt automatisch op de hoogte gesteld</li>
                        </ul>
                    </div>

                    <!-- Annulerings Formulier -->
                    <form method="POST" action="<?= URLROOT ?>/klant/annuleerLes/<?= $data['les']->id ?>">
                        <div class="mb-4">
                            <label for="reden" class="form-label">Reden voor annulering <span class="text-danger">*</span></label>
                            <select class="form-control" id="reden_type" name="reden_type" required onchange="toggleCustomReason()">
                                <option value="">Selecteer een reden...</option>
                                <option value="ziekte">Ziekte</option>
                                <option value="werk">Werk/zakelijke verplichtingen</option>
                                <option value="weer">Weersomstandigheden</option>
                                <option value="persoonlijk">Persoonlijke omstandigheden</option>
                                <option value="planning">Planning conflict</option>
                                <option value="anders">Anders (specificeer hieronder)</option>
                            </select>
                        </div>

                        <div class="mb-4" id="custom_reason_container" style="display: none;">
                            <label for="reden_details" class="form-label">Specificeer je reden</label>
                            <textarea class="form-control" id="reden_details" name="reden_details" rows="3"
                                      placeholder="Geef een uitgebreide reden voor de annulering..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="extra_opmerking" class="form-label">Extra opmerking (optioneel)</label>
                            <textarea class="form-control" id="extra_opmerking" name="extra_opmerking" rows="2"
                                      placeholder="Bijv. voorkeursdatum voor nieuwe afspraak, speciale wensen..."></textarea>
                        </div>

                        <!-- Nieuwe Datum Voorkeur -->
                        <div class="mb-4">
                            <label class="form-label">Voorkeur voor nieuwe datum (optioneel)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="voorkeur_datum" 
                                           min="<?= date('Y-m-d', strtotime('+2 days')) ?>"
                                           placeholder="Gewenste datum">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" name="voorkeur_tijd">
                                        <option value="">Voorkeurstijd...</option>
                                        <option value="ochtend">Ochtend (9:00-12:00)</option>
                                        <option value="middag">Middag (12:00-15:00)</option>
                                        <option value="namiddag">Namiddag (15:00-18:00)</option>
                                        <option value="flexibel">Flexibel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= URLROOT ?>/klant/reserveringen" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Terug
                            </a>
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Weet je zeker dat je deze les wilt annuleren?')">
                                <i class="fas fa-times me-1"></i>Les Annuleren
                            </button>
                        </div>
                    </form>

                    <!-- Contact Info -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6><i class="fas fa-phone me-2"></i>Vragen?</h6>
                        <p class="mb-0">
                            Neem contact op via <a href="tel:0612345678">06-12345678</a> of 
                            <a href="mailto:info@windkracht12.nl">info@windkracht12.nl</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomReason() {
    const redenType = document.getElementById('reden_type').value;
    const customContainer = document.getElementById('custom_reason_container');
    const customTextarea = document.getElementById('reden_details');
    
    if (redenType === 'anders') {
        customContainer.style.display = 'block';
        customTextarea.required = true;
    } else {
        customContainer.style.display = 'none';
        customTextarea.required = false;
        customTextarea.value = '';
    }
}
</script>

<style>
.alert-warning {
    border-left: 4px solid #ffc107;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
    font-weight: 600;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #212529;
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>