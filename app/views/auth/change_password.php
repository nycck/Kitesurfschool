<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i><?php echo $data['title']; ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($data['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo URLROOT; ?>/auth/changePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Huidig Wachtwoord
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   placeholder="Voer je huidige wachtwoord in">
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key me-1"></i>Nieuw Wachtwoord
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   minlength="12"
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])[A-Za-z\d@#$%^&+=!]{12,}$"
                                   placeholder="Minimaal 12 tekens, hoofdletter, cijfer en leesteken">
                            <div class="form-text">
                                <small>
                                    <strong>Wachtwoord eisen:</strong><br>
                                    • Minimaal 12 tekens lang<br>
                                    • Ten minste 1 hoofdletter<br>
                                    • Ten minste 1 cijfer<br>
                                    • Ten minste 1 leesteken (@, #, $, %, ^, &, +, =, !)
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-check me-1"></i>Bevestig Nieuw Wachtwoord
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required
                                   placeholder="Herhaal je nieuwe wachtwoord">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save me-2"></i>Wachtwoord Wijzigen
                            </button>
                            <a href="<?php echo URLROOT; ?>/<?php echo hasRole('eigenaar') ? 'eigenaar' : (hasRole('instructeur') ? 'instructeurs' : 'klant/dashboard'); ?>" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuleren
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Veiligheids tip:</strong> Gebruik een uniek wachtwoord dat je nergens anders gebruikt.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    // Real-time password validation
    newPassword.addEventListener('input', function() {
        const password = this.value;
        const requirements = {
            length: password.length >= 12,
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            special: /[@#$%^&+=!]/.test(password)
        };
        
        let isValid = Object.values(requirements).every(req => req);
        
        if (password.length > 0) {
            if (isValid) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        }
    });
    
    // Confirm password matching
    confirmPassword.addEventListener('input', function() {
        if (this.value === newPassword.value && this.value.length > 0) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else if (this.value.length > 0) {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
});
</script>

<style>
.card-header {
    border-bottom: 3px solid #ffc107;
}

.form-text small {
    font-size: 0.85em;
    line-height: 1.4;
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

.is-valid {
    border-color: #198754 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.94-.94 1.44 1.44L7.53 4.4 6.6 3.47 4.23 5.84z'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
    padding-right: calc(1.5em + 0.75rem) !important;
}

.is-invalid {
    border-color: #dc3545 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.8M8.2 4.6 5.8 7.4'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
    padding-right: calc(1.5em + 0.75rem) !important;
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>