<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <?php flash('message'); ?>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="<?= URLROOT ?>/klant/dashboard" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="display-6 fw-bold text-primary-gradient mb-0">
                    <i class="fas fa-user-edit me-3"></i>Mijn Profiel
                </h1>
            </div>

            <?php if (!empty($data['errors'])): ?>
                <div class="alert alert-danger">
                    <h6 class="alert-heading">Er zijn fouten opgetreden:</h6>
                    <ul class="mb-0">
                        <?php foreach ($data['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>Persoonlijke Gegevens
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= URLROOT ?>/klant/profiel">
                        <div class="row">
                            <!-- Account Informatie -->
                            <div class="col-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
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
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-address-card me-2"></i>Persoonlijke Gegevens
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="voornaam" class="form-label">Voornaam *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="voornaam" 
                                               name="voornaam" 
                                               value="<?= htmlspecialchars($data['form_data']['voornaam'] ?? $data['user']->voornaam ?? '') ?>"
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="achternaam" class="form-label">Achternaam *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="achternaam" 
                                               name="achternaam" 
                                               value="<?= htmlspecialchars($data['form_data']['achternaam'] ?? $data['user']->achternaam ?? '') ?>"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="geboortedatum" class="form-label">Geboortedatum</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="geboortedatum" 
                                               name="geboortedatum" 
                                               value="<?= htmlspecialchars($data['form_data']['geboortedatum'] ?? $data['user']->geboortedatum ?? '') ?>"
                                               max="<?= date('Y-m-d', strtotime('-12 years')) ?>">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Voor kitesurflessen moet je minimaal 12 jaar oud zijn
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefoon" class="form-label">Telefoonnummer</label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="telefoon" 
                                               name="telefoon" 
                                               value="<?= htmlspecialchars($data['form_data']['telefoon'] ?? $data['user']->telefoon ?? '') ?>"
                                               placeholder="06-12345678">
                                    </div>
                                </div>
                            </div>

                            <!-- Adres Informatie -->
                            <div class="col-12 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-home me-2"></i>Adres Informatie
                                </h5>
                                <div class="mb-3">
                                    <label for="adres" class="form-label">Straat en huisnummer</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="adres" 
                                           name="adres" 
                                           value="<?= htmlspecialchars($data['form_data']['adres'] ?? $data['user']->adres ?? '') ?>"
                                           placeholder="Straatnaam 123">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="postcode" class="form-label">Postcode</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="postcode" 
                                               name="postcode" 
                                               value="<?= htmlspecialchars($data['form_data']['postcode'] ?? $data['user']->postcode ?? '') ?>"
                                               placeholder="1234 AB"
                                               pattern="[1-9][0-9]{3}\s?[A-Za-z]{2}">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Nederlandse postcode (bijv. 1234 AB)
                                        </div>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="woonplaats" class="form-label">Woonplaats</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="woonplaats" 
                                               name="woonplaats" 
                                               value="<?= htmlspecialchars($data['form_data']['woonplaats'] ?? $data['user']->woonplaats ?? '') ?>"
                                               placeholder="Amsterdam">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Account Status
                                    </h6>
                                    <p class="mb-0">
                                        <strong>Aangemaakt:</strong> <?= date('d-m-Y', strtotime($data['user']->aangemaakt_op)) ?><br>
                                        <strong>Laatste login:</strong> <?= isset($data['user']->laatste_login) ? date('d-m-Y H:i', strtotime($data['user']->laatste_login)) : 'Nooit' ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-shield-alt me-2"></i>Privacy & Veiligheid
                                    </h6>
                                    <p class="mb-2">
                                        <a href="<?= URLROOT ?>/auth/changePassword" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-key me-1"></i>Wachtwoord Wijzigen
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?= URLROOT ?>/klant/dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Profiel Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Extra Options -->
            <div class="card mt-4 shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Account Opties
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-download me-2"></i>Gegevens Downloaden</h6>
                            <p class="text-muted small">Download al je accountgegevens en reserveringen.</p>
                            <button class="btn btn-outline-primary btn-sm" onclick="downloadUserData()">
                                <i class="fas fa-download me-1"></i>Download Gegevens
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-user-slash me-2"></i>Account Verwijderen</h6>
                            <p class="text-muted small">Permanent je account en alle gegevens verwijderen.</p>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash me-1"></i>Account Verwijderen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Account Verwijderen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Let op:</strong> Deze actie kan niet ongedaan worden gemaakt!
                </div>
                <p>Als je je account verwijdert:</p>
                <ul>
                    <li>Worden al je persoonlijke gegevens permanent verwijderd</li>
                    <li>Verlies je toegang tot je reserveringen en geschiedenis</li>
                    <li>Kun je niet meer inloggen met dit email adres</li>
                    <li>Worden lopende reserveringen geannuleerd</li>
                </ul>
                <p><strong>Weet je zeker dat je je account wilt verwijderen?</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-danger" onclick="deleteAccount()">
                    <i class="fas fa-trash me-1"></i>Ja, Verwijder Account
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Postcode validation
document.getElementById('postcode').addEventListener('input', function() {
    const postcode = this.value.replace(/\s/g, '');
    const regex = /^[1-9][0-9]{3}[A-Za-z]{2}$/;
    
    if (postcode.length > 0 && !regex.test(postcode)) {
        this.setCustomValidity('Voer een geldige Nederlandse postcode in (bijv. 1234AB)');
    } else {
        this.setCustomValidity('');
    }
});

// Phone number formatting
document.getElementById('telefoon').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.startsWith('31')) {
        value = value.substring(2);
    }
    if (value.startsWith('0')) {
        value = value.substring(1);
    }
    if (value.length > 0) {
        value = '06' + value.substring(1);
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        // Format as 06-12345678
        if (value.length > 2) {
            value = value.substring(0, 2) + '-' + value.substring(2);
        }
    }
    this.value = value;
});

function downloadUserData() {
    // Simulate data download
    alert('Je gegevens worden voorbereid en je ontvangt binnenkort een email met de download link.');
}

function deleteAccount() {
    // In a real application, this would make an API call
    alert('Account verwijdering is nog niet geïmplementeerd. Neem contact op met de klantenservice.');
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Vul alle verplichte velden in.');
    }
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
