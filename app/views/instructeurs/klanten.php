<?php include_once '../app/views/includes/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-users"></i> Mijn Klanten</h1>
                <div>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#nieuweKlantModal">
                        <i class="fas fa-user-plus"></i> Nieuwe Klant Toevoegen
                    </button>
                    <a href="<?php echo URLROOT; ?>/instructeurs" class="btn btn-secondary">
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
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-address-book me-2"></i>Klanten Overzicht
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['klanten'])): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Nog geen klanten</h5>
                            <p class="text-muted">Je hebt nog geen lessen gegeven aan klanten.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Naam</th>
                                        <th>Email</th>
                                        <th>Telefoon</th>
                                        <th>Woonplaats</th>
                                        <th>Aantal Lessen</th>
                                        <th>Laatste Les</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['klanten'] as $klant): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($klant->voornaam . ' ' . $klant->achternaam); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($klant->email); ?></td>
                                            <td><?php echo htmlspecialchars($klant->telefoon ?? 'Niet opgegeven'); ?></td>
                                            <td><?php echo htmlspecialchars($klant->woonplaats ?? 'Niet opgegeven'); ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo $klant->aantal_lessen ?? 0; ?> lessen
                                                </span>
                                            </td>
                                            <td>
                                                <?php 
                                                if (isset($klant->laatste_les) && $klant->laatste_les) {
                                                    echo date('d-m-Y', strtotime($klant->laatste_les));
                                                } else {
                                                    echo '<span class="text-muted">Nog geen lessen</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="bekijkKlant(<?php echo $klant->user_id; ?>)"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#klantDetailsModal">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="bewerkKlant(<?php echo $klant->user_id; ?>)"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#bewerkKlantModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="verwijderKlant(<?php echo $klant->user_id; ?>, '<?php echo htmlspecialchars($klant->voornaam . ' ' . $klant->achternaam); ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
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
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="nieuweKlantModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Nieuwe Klant Toevoegen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/instructeurs/nieuwe_klant">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email adres <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            De klant ontvangt een activatielink per email om een wachtwoord in te stellen
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="voornaam" class="form-label">Voornaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="voornaam" name="voornaam" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="achternaam" class="form-label">Achternaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="achternaam" name="achternaam" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefoon" class="form-label">Telefoon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telefoon" name="telefoon" 
                                   placeholder="06-12345678" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="geboortedatum" class="form-label">Geboortedatum</label>
                            <input type="date" class="form-control" id="geboortedatum" name="geboortedatum">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="adres" class="form-label">Adres</label>
                            <input type="text" class="form-control" id="adres" name="adres" 
                                   placeholder="Straatnaam + huisnummer">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="postcode" class="form-label">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" 
                                   placeholder="1234AB">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="woonplaats" class="form-label">Woonplaats</label>
                            <input type="text" class="form-control" id="woonplaats" name="woonplaats">
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        De klant ontvangt een email met inloggegevens om hun account te activeren.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Klant Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Klant Details Modal -->
<div class="modal fade" id="klantDetailsModal" tabindex="-1" aria-labelledby="klantDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="klantDetailsModalLabel">
                    <i class="fas fa-user me-2"></i>Klant Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="klantDetailsContent">
                <!-- Content wordt dynamisch geladen via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Bewerk Klant Modal -->
<div class="modal fade" id="bewerkKlantModal" tabindex="-1" aria-labelledby="bewerkKlantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="bewerkKlantModalLabel">
                    <i class="fas fa-edit me-2"></i>Klant Bewerken
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bewerkKlantContent">
                <!-- Content wordt dynamisch geladen via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
function bekijkKlant(userId) {
    // Laad klant details via AJAX
    fetch(`<?php echo URLROOT; ?>/instructeurs/klant_details/${userId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('klantDetailsContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('klantDetailsContent').innerHTML = 
                '<div class="alert alert-danger">Fout bij het laden van klantgegevens.</div>';
        });
}

function bewerkKlant(userId) {
    // Laad bewerkingsformulier via AJAX
    fetch(`<?php echo URLROOT; ?>/instructeurs/bewerk_klant/${userId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('bewerkKlantContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('bewerkKlantContent').innerHTML = 
                '<div class="alert alert-danger">Fout bij het laden van bewerkingsformulier.</div>';
        });
}

function verwijderKlant(userId, naam) {
    if (confirm(`Weet je zeker dat je de klantgegevens van ${naam} wilt verwijderen?\n\nLet op: Dit verwijdert ook alle bijbehorende reserveringen!`)) {
        window.location.href = `<?php echo URLROOT; ?>/instructeurs/verwijder_klant/${userId}`;
    }
}
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.85em;
}

.modal-header {
    border-bottom: 3px solid;
}

.modal-header.bg-success {
    border-bottom-color: #198754;
}

.modal-header.bg-primary {
    border-bottom-color: #0d6efd;
}

.modal-header.bg-warning {
    border-bottom-color: #ffc107;
}
</style>

<?php include_once '../app/views/includes/footer.php'; ?>