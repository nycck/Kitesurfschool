<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid py-4" style="background: #1a202c; min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-light"><i class="fas fa-chalkboard-teacher"></i> Instructeurs Beheren</h1>
                    <p class="text-light-emphasis mb-0">Overzicht en beheer van alle instructeurs</p>
                </div>
                <div>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#nieuweInstructeurModal">
                        <i class="fas fa-chalkboard-teacher"></i> Nieuwe Instructeur Toevoegen
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
                        <i class="fas fa-chalkboard-teacher me-2"></i>Instructeurs Overzicht
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['instructeurs'])): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                            <h5 class="text-light-emphasis">Nog geen instructeurs</h5>
                            <p class="text-light-emphasis">Er zijn nog geen instructeurs geregistreerd.</p>
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
                                        <th>BSN</th>
                                        <th>Status</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['instructeurs'] as $instructeur): ?>
                                        <tr>
                                            <td>
                                                <strong style="color: #f7fafc;">
                                                    <?php 
                                                    $naam = trim($instructeur->voornaam . ' ' . $instructeur->achternaam);
                                                    echo htmlspecialchars($naam ?: 'Naam niet ingevuld');
                                                    ?>
                                                </strong>
                                            </td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($instructeur->email); ?></td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($instructeur->telefoon ?? 'Niet opgegeven'); ?></td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($instructeur->woonplaats ?? 'Niet opgegeven'); ?></td>
                                            <td style="color: #cbd5e0;"><?php echo htmlspecialchars($instructeur->bsn ?? 'Niet opgegeven'); ?></td>
                                            <td>
                                                <?php if ($instructeur->is_active == 0): ?>
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
                                                <a href="<?php echo URLROOT; ?>/eigenaar/gebruiker_details/<?php echo $instructeur->id; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/eigenaar/instructeur_planning/<?php echo $instructeur->id; ?>" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-calendar-alt"></i>
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

<!-- Nieuwe Instructeur Modal -->
<div class="modal fade" id="nieuweInstructeurModal" tabindex="-1" aria-labelledby="nieuweInstructeurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title" id="nieuweInstructeurModalLabel">
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

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
