<?php include_once '../app/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-user-cog"></i> Mijn Profiel</h1>
                <a href="<?php echo URLROOT; ?>/instructeurs" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Terug naar Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Foutmeldingen -->
    <?php if (!empty($data['errors'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($data['errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Succes melding -->
    <?php flash('success_message'); ?>
    <?php flash('error_message'); ?>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Instructeur Profiel
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= URLROOT ?>/instructeurs/profiel">
                        <div class="row">
                            <!-- Account Informatie -->
                            <div class="col-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-circle me-2"></i>Account Informatie
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email adres</label>
                                        <input type="email" class="form-control" value="<?= htmlspecialchars($data['user']->email) ?>" readonly>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Email adres kan niet worden gewijzigd
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gebruikersrol</label>
                                        <input type="text" class="form-control" value="<?= ucfirst($data['user']->role) ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Persoonlijke Gegevens -->
                            <div class="col-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2 mb-3">
                                    <i class="fas fa-address-card me-2"></i>Persoonlijke Gegevens
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="voornaam" class="form-label">Voornaam <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="voornaam" 
                                               name="voornaam" 
                                               value="<?= htmlspecialchars($data['persoon']->voornaam ?? '') ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="achternaam" class="form-label">Achternaam <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="achternaam" 
                                               name="achternaam" 
                                               value="<?= htmlspecialchars($data['persoon']->achternaam ?? '') ?>" 
                                               required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="adres" class="form-label">Adres</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="adres" 
                                               name="adres" 
                                               placeholder="Straatnaam + huisnummer"
                                               value="<?= htmlspecialchars($data['persoon']->adres ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="postcode" class="form-label">Postcode</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="postcode" 
                                               name="postcode" 
                                               placeholder="1234AB"
                                               value="<?= htmlspecialchars($data['persoon']->postcode ?? '') ?>">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="woonplaats" class="form-label">Woonplaats</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="woonplaats" 
                                               name="woonplaats" 
                                               value="<?= htmlspecialchars($data['persoon']->woonplaats ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Contactgegevens en Overige Informatie -->
                            <div class="col-12 mb-4">
                                <h5 class="text-warning border-bottom pb-2 mb-3">
                                    <i class="fas fa-phone me-2"></i>Contact & Overige Informatie
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telefoon" class="form-label">Mobiel telefoon <span class="text-danger">*</span></label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="telefoon" 
                                               name="telefoon" 
                                               placeholder="06-12345678"
                                               value="<?= htmlspecialchars($data['persoon']->telefoon ?? '') ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="geboortedatum" class="form-label">Geboortedatum</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="geboortedatum" 
                                               name="geboortedatum" 
                                               value="<?= htmlspecialchars($data['persoon']->geboortedatum ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bsn" class="form-label">BSN-nummer</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="bsn" 
                                               name="bsn" 
                                               placeholder="123456789"
                                               maxlength="9"
                                               pattern="[0-9]{9}"
                                               value="<?= htmlspecialchars($data['persoon']->bsn ?? '') ?>">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            9 cijfers, optioneel maar aanbevolen voor instructeurs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= URLROOT ?>/instructeurs" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Annuleren
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i>Profiel Opslaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    border-bottom: 3px solid #ffc107;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    font-weight: bold;
}

.border-bottom {
    border-width: 2px !important;
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

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
</style>

<?php include_once '../app/views/includes/footer.php'; ?>