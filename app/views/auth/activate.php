<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-key me-2"></i>Account Activeren
                    </h3>
                    <p class="mb-0 mt-2">Stel je wachtwoord in</p>
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
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Wachtwoord eisen:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Minimaal 12 tekens lang</li>
                            <li>Bevat minimaal één hoofdletter</li>
                            <li>Bevat minimaal één cijfer</li>
                            <li>Bevat minimaal één speciaal teken (@, #, etc.)</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="<?= URLROOT ?>/auth/activate/<?= $data['token'] ?>" id="activateForm">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Wachtwoord *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimaal 12 tekens"
                                       required
                                       onkeyup="checkPasswordStrength()">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="form-text"></div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Wachtwoord bevestigen *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Herhaal je wachtwoord"
                                       required
                                       onkeyup="checkPasswordMatch()">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="form-text"></div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-check me-2"></i>Account Activeren
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    var passwordField = document.getElementById(fieldId);
    var toggleIcon = document.getElementById(fieldId === 'password' ? 'toggleIcon1' : 'toggleIcon2');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

function checkPasswordStrength() {
    var password = document.getElementById('password').value;
    var strengthDiv = document.getElementById('passwordStrength');
    var score = 0;
    var feedback = [];
    
    // Check length
    if (password.length >= 12) {
        score++;
    } else {
        feedback.push('Minimaal 12 tekens');
    }
    
    // Check uppercase
    if (/[A-Z]/.test(password)) {
        score++;
    } else {
        feedback.push('Hoofdletter');
    }
    
    // Check number
    if (/[0-9]/.test(password)) {
        score++;
    } else {
        feedback.push('Cijfer');
    }
    
    // Check special character
    if (/[@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        score++;
    } else {
        feedback.push('Speciaal teken');
    }
    
    if (score === 4) {
        strengthDiv.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i>Wachtwoord voldoet aan alle eisen</span>';
        strengthDiv.className = 'form-text';
    } else {
        strengthDiv.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>Nog nodig: ' + feedback.join(', ') + '</span>';
        strengthDiv.className = 'form-text';
    }
    
    checkFormValidity();
}

function checkPasswordMatch() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var matchDiv = document.getElementById('passwordMatch');
    
    if (confirmPassword === '') {
        matchDiv.innerHTML = '';
        checkFormValidity();
        return;
    }
    
    if (password === confirmPassword) {
        matchDiv.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i>Wachtwoorden komen overeen</span>';
    } else {
        matchDiv.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>Wachtwoorden komen niet overeen</span>';
    }
    
    checkFormValidity();
}

function checkFormValidity() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var submitBtn = document.getElementById('submitBtn');
    
    var isValidPassword = password.length >= 12 && 
                         /[A-Z]/.test(password) && 
                         /[0-9]/.test(password) && 
                         /[@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    var isMatching = password === confirmPassword && confirmPassword !== '';
    
    if (isValidPassword && isMatching) {
        submitBtn.disabled = false;
        submitBtn.className = 'btn btn-success btn-lg';
    } else {
        submitBtn.disabled = true;
        submitBtn.className = 'btn btn-secondary btn-lg';
    }
}

document.getElementById('activateForm').addEventListener('submit', function() {
    var submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Account activeren...';
    submitBtn.disabled = true;
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>