<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid dashboard-dark py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-light"><i class="fas fa-user-cog"></i> Mijn Profiel</h1>
                <a href="<?php echo URLROOT; ?>/eigenaar" class="btn btn-outline-secondary btn-dark-theme">
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

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg card-dark">
                <div class="card-header bg-transparent border-0">
                    <h4 class="mb-0 text-light">
                        <i class="fas fa-crown me-2 text-warning"></i>Eigenaar Profiel
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= URLROOT ?>/eigenaar/profiel">
                        <div class="row">
                            <!-- Account Informatie -->
                            <div class="col-12 mb-4">
                                <h5 class="text-warning border-bottom border-secondary pb-2 mb-3">
                                    <i class="fas fa-user-circle me-2"></i>Account Informatie
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-light">Email adres</label>
                                        <input type="email" class="form-control" value="<?= htmlspecialchars($data['user']->email) ?>" readonly>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Email adres kan niet worden gewijzigd
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-light">Gebruikersrol</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="<?= ucfirst($data['user']->role) ?>" readonly>
                                            <span class="input-group-text bg-warning text-dark">
                                                <i class="fas fa-crown"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Persoonlijke Gegevens -->
                            <div class="col-12 mb-4">
                                <h5 class="text-warning border-bottom border-secondary pb-2 mb-3">
                                    <i class="fas fa-address-card me-2"></i>Persoonlijke Gegevens
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="voornaam" class="form-label text-light">Voornaam <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="voornaam" 
                                               name="voornaam" 
                                               value="<?= htmlspecialchars($data['persoon']->voornaam ?? '') ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="achternaam" class="form-label text-light">Achternaam <span class="text-danger">*</span></label>
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
                                        <label for="adres" class="form-label text-light">Adres</label>
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
                                        <label for="postcode" class="form-label text-light">Postcode</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="postcode" 
                                               name="postcode" 
                                               placeholder="1234AB"
                                               value="<?= htmlspecialchars($data['persoon']->postcode ?? '') ?>">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="woonplaats" class="form-label text-light">Woonplaats</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="woonplaats" 
                                               name="woonplaats" 
                                               value="<?= htmlspecialchars($data['persoon']->woonplaats ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telefoon" class="form-label text-light">Telefoonnummer</label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="telefoon" 
                                               name="telefoon" 
                                               placeholder="06-12345678"
                                               value="<?= htmlspecialchars($data['persoon']->telefoon ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="geboortedatum" class="form-label text-light">Geboortedatum</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="geboortedatum" 
                                               name="geboortedatum" 
                                               value="<?= htmlspecialchars($data['persoon']->geboortedatum ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bsn" class="form-label text-light">BSN-nummer</label>
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
                                            9 cijfers, optioneel
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Eigenaar Privileges Sectie -->
                            <div class="col-12 mb-4">
                                <div class="alert alert-warning d-flex align-items-center">
                                    <i class="fas fa-crown fa-2x me-3"></i>
                                    <div>
                                        <h6 class="alert-heading">Eigenaar Privileges</h6>
                                        <p class="mb-0">Als eigenaar heb je volledige toegang tot alle systeemfuncties, gebruikersbeheer en instellingen.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= URLROOT ?>/eigenaar" class="btn btn-outline-secondary btn-dark-theme">
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

            <!-- Account Opties -->
            <div class="card mt-4 border-0 shadow-lg card-dark">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 text-light">
                        <i class="fas fa-cogs me-2"></i>Account Opties
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-light"><i class="fas fa-key me-2"></i>Wachtwoord Wijzigen</h6>
                            <p class="text-light-emphasis small">Wijzig je wachtwoord voor extra beveiliging.</p>
                            <a href="<?= URLROOT ?>/auth/changePassword" class="btn btn-outline-warning btn-sm btn-dark-theme">
                                <i class="fas fa-key me-1"></i>Wachtwoord Wijzigen
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-light"><i class="fas fa-shield-alt me-2"></i>Systeem Instellingen</h6>
                            <p class="text-light-emphasis small">Beheer systeeminstellingen en configuratie.</p>
                            <a href="<?= URLROOT ?>/eigenaar/instellingen" class="btn btn-outline-info btn-sm btn-dark-theme">
                                <i class="fas fa-cogs me-1"></i>Naar Instellingen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dark Theme Styles */
.dashboard-dark {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    min-height: 100vh;
}

.card-dark {
    background: rgba(30, 30, 50, 0.9) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.btn-dark-theme {
    background: rgba(40, 40, 60, 0.8) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
}

.btn-dark-theme:hover {
    background: rgba(50, 50, 70, 0.9) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
    color: #fff !important;
}

.form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    color: #fff;
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-text {
    color: rgba(255, 255, 255, 0.7) !important;
}

.border-secondary {
    border-color: rgba(255, 255, 255, 0.2) !important;
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
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>