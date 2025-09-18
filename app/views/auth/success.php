<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($data['title']) ?>
                    </h3>
                </div>
                
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h4 class="text-success mb-3">Gelukt!</h4>
                    <p class="lead mb-4"><?= htmlspecialchars($data['message']) ?></p>
                    
                    <div class="d-grid gap-2">
                        <?php if (isset($data['redirect_url'])): ?>
                            <a href="<?= $data['redirect_url'] ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-right me-2"></i>Verder gaan
                            </a>
                        <?php else: ?>
                            <a href="<?= URLROOT ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>Naar Homepage
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!isLoggedIn()): ?>
                            <a href="<?= URLROOT ?>/auth/login" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Inloggen
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>