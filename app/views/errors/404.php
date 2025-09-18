<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="error-page">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle fa-5x text-warning"></i>
                </div>
                
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h2 class="mb-4">Pagina niet gevonden</h2>
                
                <p class="lead mb-4">
                    Sorry, de pagina die je zoekt bestaat niet of is verplaatst.
                </p>
                
                <div class="mb-5">
                    <p class="text-muted">
                        Mogelijke oorzaken:
                    </p>
                    <ul class="list-unstyled text-muted">
                        <li>• De URL is verkeerd getypt</li>
                        <li>• De pagina is verwijderd of verplaatst</li>
                        <li>• Je hebt geen toegang tot deze pagina</li>
                    </ul>
                </div>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= URLROOT ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>Terug naar Home
                    </a>
                    <a href="<?= URLROOT ?>/homepages/pakketten" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-list me-2"></i>Bekijk Lespakketten
                    </a>
                    <a href="<?= URLROOT ?>/homepages/contact" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Contact Opnemen
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 3rem 0;
}

.error-page .display-1 {
    font-size: 8rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .error-page .display-1 {
        font-size: 6rem;
    }
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
