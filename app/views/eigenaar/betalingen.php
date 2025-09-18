<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><?php echo $data['title']; ?></h1>
                <a href="<?php echo URLROOT; ?>/eigenaar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Terug naar Dashboard
                </a>
            </div>

            <!-- Statistieken Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>€<?php echo number_format($data['totaal_omzet'], 2); ?></h4>
                                    <p class="mb-0">Totale Omzet</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-euro-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>€<?php echo number_format($data['openstaand'], 2); ?></h4>
                                    <p class="mb-0">Openstaand</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo count($data['betalingen']); ?></h4>
                                    <p class="mb-0">Totaal Betalingen</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-receipt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo date('m-Y', strtotime($data['maand'])); ?></h4>
                                    <p class="mb-0">Geselecteerde Maand</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Sectie -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo URLROOT; ?>/eigenaar/betalingen" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter" class="form-label">Filter op Status</label>
                            <select name="filter" id="filter" class="form-select">
                                <option value="alle" <?php echo ($data['filter'] == 'alle') ? 'selected' : ''; ?>>Alle Betalingen</option>
                                <option value="betaald" <?php echo ($data['filter'] == 'betaald') ? 'selected' : ''; ?>>Betaald</option>
                                <option value="wachtend" <?php echo ($data['filter'] == 'wachtend') ? 'selected' : ''; ?>>Wachtend</option>
                                <option value="mislukt" <?php echo ($data['filter'] == 'mislukt') ? 'selected' : ''; ?>>Mislukt</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="maand" class="form-label">Maand</label>
                            <input type="month" name="maand" id="maand" class="form-control" 
                                   value="<?php echo $data['maand']; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filteren
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <a href="<?php echo URLROOT; ?>/eigenaar/export_betalingen?maand=<?php echo $data['maand']; ?>&filter=<?php echo $data['filter']; ?>" 
                                   class="btn btn-success">
                                    <i class="fas fa-download"></i> Exporteren
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Betalingen Tabel -->
            <div class="card">
                <div class="card-body">
                    <?php if(!empty($data['betalingen'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Reservering ID</th>
                                        <th>Klant</th>
                                        <th>Lespakket</th>
                                        <th>Bedrag</th>
                                        <th>Status</th>
                                        <th>Datum</th>
                                        <th>Betaald op</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['betalingen'] as $betaling): ?>
                                        <tr>
                                            <td>
                                                <strong>#<?php echo $betaling->reservering_id; ?></strong>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($betaling->klant_voornaam . ' ' . $betaling->klant_achternaam); ?>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($betaling->klant_email); ?></small>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($betaling->lespakket_naam); ?>
                                                <br>
                                                <small class="text-muted"><?php echo date('d-m-Y', strtotime($betaling->gewenste_datum)); ?></small>
                                            </td>
                                            <td>
                                                <strong>€<?php echo number_format($betaling->bedrag, 2); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $betaling->betaal_status == 'betaald' ? 'success' : 
                                                        ($betaling->betaal_status == 'wachtend' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($betaling->betaal_status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo date('d-m-Y H:i', strtotime($betaling->aangemaakt_op)); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if($betaling->betaald_op) {
                                                    echo date('d-m-Y H:i', strtotime($betaling->betaald_op));
                                                } else {
                                                    echo '<span class="text-muted">Nog niet betaald</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo URLROOT; ?>/reserveringen/details/<?php echo $betaling->reservering_id; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Details bekijken">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($betaling->betaal_status != 'betaald'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                data-bs-toggle="modal" data-bs-target="#wijzigStatusModal" 
                                                                data-reservering-id="<?php echo $betaling->reservering_id; ?>" 
                                                                data-klant-naam="<?php echo htmlspecialchars($betaling->klant_voornaam . ' ' . $betaling->klant_achternaam); ?>"
                                                                data-bedrag="<?php echo number_format($betaling->bedrag, 2); ?>"
                                                                data-current-status="<?php echo $betaling->betaal_status; ?>"
                                                                title="Status wijzigen">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" data-bs-target="#factuurModal" 
                                                            data-reservering-id="<?php echo $betaling->reservering_id; ?>"
                                                            title="Factuur genereren">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Geen betalingen gevonden</h5>
                            <p class="text-muted">Probeer andere filterinstellingen of selecteer een andere maand.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wijzig Status Modal -->
<div class="modal fade" id="wijzigStatusModal" tabindex="-1" aria-labelledby="wijzigStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wijzigStatusModalLabel">Betalingsstatus Wijzigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="wijzigStatusForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Reservering Details:</h6>
                        <p><strong>Klant:</strong> <span id="modalKlantNaam"></span></p>
                        <p><strong>Bedrag:</strong> €<span id="modalBedrag"></span></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="betaal_status" class="form-label">Nieuwe Status</label>
                        <select name="betaal_status" id="betaal_status" class="form-select" required>
                            <option value="">Selecteer een status...</option>
                            <option value="wachtend">Wachtend</option>
                            <option value="betaald">Betaald</option>
                            <option value="mislukt">Mislukt</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="opmerking" class="form-label">Opmerking (optioneel)</label>
                        <textarea name="opmerking" id="opmerking" rows="3" class="form-control" 
                                  placeholder="Voeg een opmerking toe..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Let op:</strong> Als je de status wijzigt naar 'Betaald', ontvangt de klant een bevestigingsmail.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-success">Status Wijzigen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Factuur Modal -->
<div class="modal fade" id="factuurModal" tabindex="-1" aria-labelledby="factuurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="factuurModalLabel">Factuur Genereren</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Wil je een factuur genereren voor deze reservering?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Let op:</strong> De factuur wordt automatisch naar de klant gemaild.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" id="genererenFactuurBtn">
                    <i class="fas fa-file-invoice"></i> Genereren en Versturen
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wijzigStatusModal = document.getElementById('wijzigStatusModal');
    const wijzigStatusForm = document.getElementById('wijzigStatusForm');
    const modalKlantNaam = document.getElementById('modalKlantNaam');
    const modalBedrag = document.getElementById('modalBedrag');
    const betaalStatusSelect = document.getElementById('betaal_status');
    
    const factuurModal = document.getElementById('factuurModal');
    const genererenFactuurBtn = document.getElementById('genererenFactuurBtn');
    let currentReserveringId = null;
    
    wijzigStatusModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const reserveringId = button.getAttribute('data-reservering-id');
        const klantNaam = button.getAttribute('data-klant-naam');
        const bedrag = button.getAttribute('data-bedrag');
        const currentStatus = button.getAttribute('data-current-status');
        
        modalKlantNaam.textContent = klantNaam;
        modalBedrag.textContent = bedrag;
        wijzigStatusForm.action = '<?php echo URLROOT; ?>/eigenaar/betaling_status/' + reserveringId;
        
        // Reset en disable current status
        betaalStatusSelect.value = '';
        const options = betaalStatusSelect.querySelectorAll('option');
        options.forEach(option => {
            option.disabled = false;
            if (option.value === currentStatus) {
                option.disabled = true;
            }
        });
    });
    
    factuurModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        currentReserveringId = button.getAttribute('data-reservering-id');
    });
    
    genererenFactuurBtn.addEventListener('click', function() {
        if (currentReserveringId) {
            window.location.href = '<?php echo URLROOT; ?>/eigenaar/genereer_factuur/' + currentReserveringId;
        }
    });
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>