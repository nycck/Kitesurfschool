<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Registreren
                    </h3>
                    <p class="mb-0 mt-2">Maak een account om te reserveren</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (!empty($data['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($data['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= URLROOT ?>/auth/register" id="registerForm">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email adres *
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                                   placeholder="jouw@email.nl"
                                   required>
                            <div class="form-text">
                                Dit wordt je gebruikersnaam voor inloggen
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Registreren
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Al een account?</p>
                        <a href="<?= URLROOT ?>/auth/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-1"></i>Inloggen
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 text-center text-muted">
                <small>
                    <i class="fas fa-shield-alt me-1"></i>
                    Je gegevens worden veilig opgeslagen en nooit gedeeld met derden
                </small>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function() {
    var submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Bezig met registreren...';
    submitBtn.disabled = true;
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>