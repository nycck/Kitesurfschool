<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid py-4" style="background: #1a202c; min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-light"><i class="fas fa-users"></i> Klanten Beheren</h1>
                    <p class="text-light-emphasis mb-0">Overzicht en beheer van alle klanten</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#nieuweKlantModal">
                        <i class="fas fa-user-plus"></i> Nieuwe Klant Toevoegen
                    </button>
                    <a href="<?php echo URLROOT; ?>/eigenaar" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Terug naar Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php flash('success_message'); ?>
    <?php flash('error_message'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: #2d3748;">
                <div class="card-header border-0" style="background: #1a202c;">
                    <h4 class="mb-0 text-light">
                        <i class="fas fa-address-book me-2"></i>Klanten Overzicht
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['klanten'])): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-light-emphasis">Nog geen klanten</h5>
                            <p class="text-light-emphasis">Er zijn nog geen klanten geregistreerd.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead style="background: #1a202c;">
                                    <tr>
                                        <th>Naam</th>
                                        <th>Email</th>
                                        <th>Telefoon</th>
                                        <th>Woonplaats</th>
                                        <th>Aantal Lessen</th>
                                        <th>Laatste Les</th>
                                        <th>Status</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['klanten'] as $klant): ?>
                                        <tr>
                                            <td>
                                                <strong style="color: #f7fafc;">
                                                    <?php 
                                                    $naam = trim($klant->voornaam . ' ' . $klant->achternaam);
                                                    echo htmlspecialchars($naam ?: 'Naam niet ingevuld');
                                                    ?>
                                                </strong>
                                            </td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($klant->email); ?></td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($klant->telefoon ?? 'Niet opgegeven'); ?></td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($klant->woonplaats ?? 'Niet opgegeven'); ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo $klant->aantal_lessen ?? 0; ?> lessen
                                                </span>
                                            </td>
                                            <td style="color: #cbd5e0;">
                                                <?php 
                                                if (isset($klant->laatste_les) && $klant->laatste_les) {
                                                    echo date('d-m-Y', strtotime($klant->laatste_les));
                                                } else {
                                                    echo '<span class="text-muted">Nog geen lessen</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($klant->is_active == 0): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock"></i> Wacht op activatie
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Actief
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/eigenaar/gebruiker_details/<?php echo $klant->user_id; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nieuwe Klant Modal -->
<div class="modal fade" id="nieuweKlantModal" tabindex="-1" aria-labelledby="nieuweKlantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title" id="nieuweKlantModalLabel">
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

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
