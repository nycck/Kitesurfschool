<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>Inloggen
                    </h3>
                    <p class="mb-0 mt-2">Welkom terug bij Windkracht-12</p>
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
                    
                    <form method="POST" action="<?= URLROOT ?>/auth/login" id="loginForm">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <div class="mb-3">
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
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Wachtwoord *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••••••"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Inloggen
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-2">Nog geen account?</p>
                        <a href="<?= URLROOT ?>/auth/register" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-1"></i>Registreren
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="#" class="text-muted small">
                            <i class="fas fa-question-circle me-1"></i>Wachtwoord vergeten?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    var passwordField = document.getElementById('password');
    var toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    var submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Bezig met inloggen...';
    submitBtn.disabled = true;
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>