<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-euro-sign me-2"></i>Betaling Bevestigen
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    <?php flash('message'); ?>

                    <!-- Reservering Details -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Reservering Gegevens</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Lespakket:</strong></p>
                                <p class="mb-2"><?= htmlspecialchars($data['reservering']->lespakket_naam ?? 'Onbekend') ?></p>
                                
                                <p class="mb-1"><strong>Locatie:</strong></p>
                                <p class="mb-2"><?= htmlspecialchars($data['reservering']->locatie_naam ?? 'Onbekend') ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Gewenste datum:</strong></p>
                                <p class="mb-2"><?= isset($data['reservering']->gewenste_datum) ? date('d-m-Y', strtotime($data['reservering']->gewenste_datum)) : 'Te bepalen' ?></p>
                                
                                <p class="mb-1"><strong>Status:</strong></p>
                                <p class="mb-0">
                                    <span class="badge bg-<?= $data['reservering']->status == 'bevestigd' ? 'success' : 'warning' ?> fs-6">
                                        <?= ucfirst($data['reservering']->status ?? 'Onbekend') ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Huidige Betaal Status -->
                    <div class="alert alert-<?= ($data['reservering']->betaal_status == 'betaald') ? 'success' : 'warning' ?>">
                        <h6><i class="fas fa-credit-card me-2"></i>Huidige Betaal Status</h6>
                        <p class="mb-0">
                            <strong>Status:</strong> 
                            <span class="badge bg-<?= ($data['reservering']->betaal_status == 'betaald') ? 'success' : 'danger' ?> fs-6">
                                <?= ucfirst($data['reservering']->betaal_status ?? 'Openstaand') ?>
                            </span>
                        </p>
                        <?php if (!empty($data['reservering']->totale_prijs)): ?>
                        <p class="mt-2 mb-0">
                            <strong>Bedrag:</strong> €<?= number_format($data['reservering']->totale_prijs, 2, ',', '.') ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <?php if ($data['reservering']->betaal_status != 'betaald'): ?>
                        <!-- Betalingsgegevens -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-university me-2"></i>Betalingsgegevens Windkracht-12
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Rekeningnummer:</strong><br>
                                        NL12 ABCD 0123 4567 89</p>
                                        
                                        <p><strong>Ten name van:</strong><br>
                                        Kitesurfschool Windkracht-12</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Bedrag:</strong><br>
                                        €<?= number_format($data['reservering']->totale_prijs ?? 0, 2, ',', '.') ?></p>
                                        
                                        <p><strong>Omschrijving:</strong><br>
                                        Reservering #<?= $data['reservering']->id ?> - <?= htmlspecialchars($data['reservering']->lespakket_naam) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Betaling Bevestigen Formulier -->
                        <form method="POST" action="<?= URLROOT ?>/klant/betaling/<?= $data['reservering']->id ?>">
                            <div class="mb-4">
                                <h6><i class="fas fa-check-circle me-2"></i>Betaling Bevestigen</h6>
                                <p class="text-muted">
                                    Heb je het bedrag overgemaakt naar de rekening van Windkracht-12? 
                                    Bevestig dan hieronder je betaling.
                                </p>
                            </div>

                            <div class="mb-4">
                                <label for="betaaldatum" class="form-label">Betaaldatum <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="betaaldatum" name="betaaldatum" 
                                       max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Wanneer heb je de betaling uitgevoerd?
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="betaal_methode" class="form-label">Betaalmethode <span class="text-danger">*</span></label>
                                <select class="form-control" id="betaal_methode" name="betaal_methode" required>
                                    <option value="">Selecteer betaalmethode...</option>
                                    <option value="overboeking">Bankoverschrijving</option>
                                    <option value="ideal">iDEAL</option>
                                    <option value="contant">Contant (bij de les)</option>
                                    <option value="pin">Pinbetaling</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="referentie" class="form-label">Referentie/Transactienummer (optioneel)</label>
                                <input type="text" class="form-control" id="referentie" name="referentie" 
                                       placeholder="Bijv. transactienummer van je bank">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Dit helpt ons je betaling sneller te verwerken
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="opmerking" class="form-label">Opmerking (optioneel)</label>
                                <textarea class="form-control" id="opmerking" name="opmerking" rows="3"
                                          placeholder="Eventuele opmerkingen over de betaling..."></textarea>
                            </div>

                            <!-- Bevestiging Checkbox -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="bevestig_betaling" name="bevestig_betaling" required>
                                    <label class="form-check-label" for="bevestig_betaling">
                                        Ik bevestig dat ik het bedrag van €<?= number_format($data['reservering']->totale_prijs ?? 0, 2, ',', '.') ?> 
                                        heb overgemaakt naar Windkracht-12 en dat de betalingsgegevens correct zijn.
                                    </label>
                                </div>
                            </div>

                            <!-- Important Notice -->
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Belangrijk!</h6>
                                <ul class="mb-0">
                                    <li>Je betalingsstatus wordt pas definitief bijgewerkt na verificatie door de eigenaar</li>
                                    <li>Je ontvangt een bevestiging per email zodra je betaling is goedgekeurd</li>
                                    <li>Bij vragen over de betaling kun je contact opnemen via info@windkracht12.nl</li>
                                    <li>Geef alleen eerlijke informatie op - onjuiste informatie kan leiden tot annulering</li>
                                </ul>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="<?= URLROOT ?>/reserveringen" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Terug naar Reserveringen
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Betaling Bevestigen
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <!-- Betaling al bevestigd -->
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Betaling Bevestigd</h6>
                            <p class="mb-0">
                                Je betaling is al bevestigd en goedgekeurd. 
                                Je instructeur neemt binnenkort contact op voor de planning van je les.
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <a href="<?= URLROOT ?>/reserveringen" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i>Terug naar Reserveringen
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Info -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6><i class="fas fa-headset me-2"></i>Hulp Nodig?</h6>
                        <p class="mb-0">
                            Vragen over je betaling? Neem contact op:<br>
                            <i class="fas fa-phone me-1"></i> <a href="tel:0612345678">06-12345678</a> | 
                            <i class="fas fa-envelope me-1"></i> <a href="mailto:info@windkracht12.nl">info@windkracht12.nl</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alert-success {
    border-left: 4px solid #28a745;
}

.alert-warning {
    border-left: 4px solid #ffc107;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    font-weight: 600;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.card-header.bg-success {
    border-bottom: 3px solid #28a745;
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>