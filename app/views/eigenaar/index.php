<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid py-4 dashboard-dark">
    <div class="row">
        <div class="col-md-12">
            <!-- Flash Messages -->
            <?php flash('success_message'); ?>
            <?php flash('error_message'); ?>
            
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="display-5 fw-bold text-light mb-1"><?php echo $data['title']; ?></h1>
                    <p class="text-light-emphasis mb-0">Overzicht van je kitesurfschool</p>
                </div>
                <div class="text-end">
                    <small class="text-light-emphasis">Laatste update: <?= date('d-m-Y H:i') ?></small>
                </div>
            </div>
            
            <!-- Statistieken Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 card-dark">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-light-emphasis mb-1 small text-uppercase fw-semibold">Totaal Gebruikers</p>
                                    <h3 class="fw-bold text-light mb-0"><?php echo $data['totaal_gebruikers']; ?></h3>
                                </div>
                                <div class="bg-primary bg-opacity-25 rounded-3 p-3">
                                    <i class="fas fa-users fa-lg text-primary"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>+<?= $data['statistieken']['nieuwe_gebruikers_deze_maand'] ?> deze maand
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 card-dark">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-light-emphasis mb-1 small text-uppercase fw-semibold">Totaal Reserveringen</p>
                                    <h3 class="fw-bold text-light mb-0"><?php echo $data['totaal_reserveringen']; ?></h3>
                                </div>
                                <div class="bg-success bg-opacity-25 rounded-3 p-3">
                                    <i class="fas fa-calendar-check fa-lg text-success"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>Actief seizoen
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 card-dark">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-light-emphasis mb-1 small text-uppercase fw-semibold">Omzet Deze Maand</p>
                                    <h3 class="fw-bold text-light mb-0">€<?php echo number_format($data['omzet_deze_maand'], 0); ?></h3>
                                </div>
                                <div class="bg-info bg-opacity-25 rounded-3 p-3">
                                    <i class="fas fa-euro-sign fa-lg text-info"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-info">
                                    <i class="fas fa-chart-line me-1"></i><?= $data['statistieken']['conversion_rate'] ?>% conversie
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 card-dark">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-light-emphasis mb-1 small text-uppercase fw-semibold">Actieve Instructeurs</p>
                                    <h3 class="fw-bold text-light mb-0"><?php echo $data['actieve_instructeurs']; ?></h3>
                                </div>
                                <div class="bg-warning bg-opacity-25 rounded-3 p-3">
                                    <i class="fas fa-chalkboard-teacher fa-lg text-warning"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-warning">
                                    <i class="fas fa-star me-1"></i><?= number_format($data['statistieken']['gemiddelde_beoordeling'], 1) ?>/5 gemiddeld
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="row g-4 mb-5">
                <!-- Statistieken Details -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg h-100 card-dark">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h5 class="card-title fw-semibold mb-0 text-light">
                                <i class="fas fa-chart-bar text-primary me-2"></i>Maandelijkse Statistieken
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-4">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-dark-subtle rounded-3">
                                        <h4 class="fw-bold text-primary mb-1"><?php echo $data['statistieken']['nieuwe_gebruikers_deze_maand']; ?></h4>
                                        <p class="text-light-emphasis mb-0 small">Nieuwe Gebruikers</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-dark-subtle rounded-3">
                                        <h4 class="fw-bold text-success mb-1"><?php echo $data['statistieken']['voltooide_lessen_deze_maand']; ?></h4>
                                        <p class="text-light-emphasis mb-0 small">Voltooide Lessen</p>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4 border-secondary">
                            
                            <div class="space-y-3">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-light-emphasis">Gemiddelde Beoordeling</span>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold me-2 text-light"><?= number_format($data['statistieken']['gemiddelde_beoordeling'], 1) ?>/5</span>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-light-emphasis">Populairste Pakket</span>
                                    <span class="fw-semibold text-light"><?php echo $data['statistieken']['populairste_lespakket']; ?></span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-light-emphasis">Populairste Locatie</span>
                                    <span class="fw-semibold text-light"><?php echo $data['statistieken']['populairste_locatie']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Snelle Acties -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg h-100 card-dark">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h5 class="card-title fw-semibold mb-0 text-light">
                                <i class="fas fa-bolt text-primary me-2"></i>Snelle Acties
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-grid gap-3">
                                <button type="button" class="btn btn-primary btn-lg d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#toevoegKlantModal">
                                    <i class="fas fa-user-plus me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Klant Toevoegen</div>
                                        <small style="opacity: 0.8;">Nieuwe klant aanmaken met activatiemail</small>
                                    </div>
                                </button>

                                <button type="button" class="btn btn-success btn-lg d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#toevoegInstructeurModal">
                                    <i class="fas fa-chalkboard-teacher me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Instructeur Toevoegen</div>
                                        <small style="opacity: 0.8;">Nieuwe instructeur aanmaken met activatiemail</small>
                                    </div>
                                </button>
                                
                                <a href="<?php echo URLROOT; ?>/eigenaar/gebruikers" class="btn btn-outline-primary btn-lg d-flex align-items-center btn-dark-theme">
                                    <i class="fas fa-users me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Beheer Gebruikers</div>
                                        <small class="text-light-emphasis">Bekijk en beheer alle gebruikers</small>
                                    </div>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/eigenaar/betalingen" class="btn btn-outline-success btn-lg d-flex align-items-center btn-dark-theme">
                                    <i class="fas fa-credit-card me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Bekijk Betalingen</div>
                                        <small class="text-light-emphasis">Beheer betalingen en facturen</small>
                                    </div>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/eigenaar/rapporten" class="btn btn-outline-info btn-lg d-flex align-items-center btn-dark-theme">
                                    <i class="fas fa-chart-bar me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Genereer Rapporten</div>
                                        <small class="text-light-emphasis">Bekijk uitgebreide statistieken</small>
                                    </div>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/eigenaar/profiel" class="btn btn-outline-warning btn-lg d-flex align-items-center btn-dark-theme">
                                    <i class="fas fa-user-cog me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Mijn Profiel</div>
                                        <small class="text-light-emphasis">Beheer persoonlijke gegevens</small>
                                    </div>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/eigenaar/instructeur_planning" class="btn btn-outline-info btn-lg d-flex align-items-center btn-dark-theme">
                                    <i class="fas fa-calendar-alt me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Instructeur Planning</div>
                                        <small class="text-light-emphasis">Bekijk planning van instructeurs</small>
                                    </div>
                                </a>
                                
                                <a href="<?php echo URLROOT; ?>/eigenaar/instellingen" class="btn btn-outline-secondary btn-lg d-flex align-items-center btn-dark-theme">
                                    <i class="fas fa-cogs me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Systeem Instellingen</div>
                                        <small class="text-light-emphasis">Configureer de applicatie</small>
                                    </div>
                                </a>
                            </div>
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
    backdrop-filter: blur(10px);
}

.bg-dark-subtle {
    background-color: rgba(20, 20, 40, 0.8) !important;
}

.text-light-emphasis {
    color: rgba(255, 255, 255, 0.7) !important;
}

.btn-dark-theme {
    background: transparent !important;
    color: #fff !important;
    border-color: currentColor !important;
    transition: all 0.3s ease;
}

.btn-dark-theme:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.space-y-3 > * + * {
    margin-top: 1rem;
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3) !important;
}

.btn-lg.d-flex {
    padding: 1rem 1.5rem;
    text-align: left;
}

.bg-opacity-25 {
    --bs-bg-opacity: 0.25;
}

/* Icon glow effects */
.card-dark .fa-users,
.card-dark .fa-calendar-check,
.card-dark .fa-euro-sign,
.card-dark .fa-chalkboard-teacher {
    filter: drop-shadow(0 0 8px currentColor);
}

/* Scrollbar styling for dark theme */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Enhance borders for better contrast */
.border-secondary {
    border-color: rgba(255, 255, 255, 0.2) !important;
}

/* Badge improvements for dark theme */
.badge.bg-warning.bg-opacity-25,
.badge.bg-primary.bg-opacity-25 {
    color: #fff !important;
}
</style>

<!-- Klant Toevoegen Modal -->
<div class="modal fade" id="toevoegKlantModal" tabindex="-1" aria-labelledby="toevoegKlantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title" id="toevoegKlantModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Nieuwe Klant Toevoegen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/nieuwe_klant">
                <div class="modal-body" style="background: #2d3748;">
                    <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); color: #93c5fd;">
                        <i class="fas fa-info-circle me-2"></i>
                        De klant ontvangt een activatielink via email om hun wachtwoord in te stellen.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="klant_email" class="form-label" style="color: #f7fafc;">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control bg-dark text-light" id="klant_email" name="email" required
                                   style="border-color: #4a5568;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="klant_telefoon" class="form-label" style="color: #f7fafc;">Telefoon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control bg-dark text-light" id="klant_telefoon" name="telefoon" required
                                   style="border-color: #4a5568;">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="klant_voornaam" class="form-label" style="color: #f7fafc;">Voornaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="klant_voornaam" name="voornaam" required
                                   style="border-color: #4a5568;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="klant_achternaam" class="form-label" style="color: #f7fafc;">Achternaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="klant_achternaam" name="achternaam" required
                                   style="border-color: #4a5568;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="klant_geboortedatum" class="form-label" style="color: #f7fafc;">Geboortedatum <span class="text-danger">*</span></label>
                        <input type="date" class="form-control bg-dark text-light" id="klant_geboortedatum" name="geboortedatum" required
                               style="border-color: #4a5568;">
                    </div>
                    
                    <div class="mb-3">
                        <label for="klant_adres" class="form-label" style="color: #f7fafc;">Adres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-dark text-light" id="klant_adres" name="adres" required
                               style="border-color: #4a5568;">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="klant_postcode" class="form-label" style="color: #f7fafc;">Postcode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="klant_postcode" name="postcode" required
                                   style="border-color: #4a5568;">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="klant_woonplaats" class="form-label" style="color: #f7fafc;">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="klant_woonplaats" name="woonplaats" required
                                   style="border-color: #4a5568;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #1a202c; border-top: 1px solid #4a5568;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuleren
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Klant Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Instructeur Toevoegen Modal -->
<div class="modal fade" id="toevoegInstructeurModal" tabindex="-1" aria-labelledby="toevoegInstructeurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title" id="toevoegInstructeurModalLabel">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Nieuwe Instructeur Toevoegen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/nieuwe_instructeur">
                <div class="modal-body" style="background: #2d3748;">
                    <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); color: #93c5fd;">
                        <i class="fas fa-info-circle me-2"></i>
                        De instructeur ontvangt een activatielink via email om hun wachtwoord in te stellen.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instructeur_email" class="form-label" style="color: #f7fafc;">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control bg-dark text-light" id="instructeur_email" name="email" required
                                   style="border-color: #4a5568;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instructeur_telefoon" class="form-label" style="color: #f7fafc;">Telefoon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control bg-dark text-light" id="instructeur_telefoon" name="telefoon" required
                                   style="border-color: #4a5568;">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instructeur_voornaam" class="form-label" style="color: #f7fafc;">Voornaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="instructeur_voornaam" name="voornaam" required
                                   style="border-color: #4a5568;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instructeur_achternaam" class="form-label" style="color: #f7fafc;">Achternaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="instructeur_achternaam" name="achternaam" required
                                   style="border-color: #4a5568;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="instructeur_geboortedatum" class="form-label" style="color: #f7fafc;">Geboortedatum <span class="text-danger">*</span></label>
                        <input type="date" class="form-control bg-dark text-light" id="instructeur_geboortedatum" name="geboortedatum" required
                               style="border-color: #4a5568;">
                    </div>
                    
                    <div class="mb-3">
                        <label for="instructeur_adres" class="form-label" style="color: #f7fafc;">Adres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-dark text-light" id="instructeur_adres" name="adres" required
                               style="border-color: #4a5568;">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instructeur_postcode" class="form-label" style="color: #f7fafc;">Postcode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="instructeur_postcode" name="postcode" required
                                   style="border-color: #4a5568;">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="instructeur_woonplaats" class="form-label" style="color: #f7fafc;">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light" id="instructeur_woonplaats" name="woonplaats" required
                                   style="border-color: #4a5568;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #1a202c; border-top: 1px solid #4a5568;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuleren
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Instructeur Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Scroll to top if flash message exists
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>