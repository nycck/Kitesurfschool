<?php 
include_once '../app/views/includes/header.php'; 

// Helper functions for the view
function getStatusColor($status) {
    switch($status) {
        case 'bevestigd': return 'success';
        case 'afgerond': return 'primary';
        case 'geannuleerd': return 'danger';
        default: return 'warning';
    }
}

function renderLesActies($les) {
    echo '<div class="btn-group" role="group">';
    
    if ($les->status != 'geannuleerd' && $les->status != 'afgerond') {
        echo '<a href="' . URLROOT . '/instructeurs/les_details/' . $les->id . '" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye"></i>
              </a>';
        
        // Snelle annulering buttons
        echo '<button type="button" class="btn btn-sm btn-outline-danger dropdown-toggle" 
                      data-bs-toggle="dropdown">
                <i class="fas fa-times"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="snelleAnnulering(' . $les->id . ', \'ziekte\', \'' . htmlspecialchars($les->klant_naam) . '\')">
                      <i class="fas fa-thermometer-half me-2"></i>Ziekte instructeur
                    </a></li>
                <li><a class="dropdown-item" href="#" onclick="snelleAnnulering(' . $les->id . ', \'weer\', \'' . htmlspecialchars($les->klant_naam) . '\')">
                      <i class="fas fa-wind me-2"></i>Slechte weersomstandigheden
                    </a></li>
              </ul>';
    }
    
    echo '</div>';
}

function renderWeekLes($les) {
    $statusColor = getStatusColor($les->status);
    return '
    <div class="small bg-' . $statusColor . ' text-white p-1 rounded mb-1">
        <div class="fw-bold">' . htmlspecialchars($les->klant_naam) . '</div>
        <div>' . htmlspecialchars($les->pakket_naam) . '</div>
    </div>';
}
?>

<div class="container-fluid py-4" style="background-color: #1a202c; min-height: 100vh;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="text-light"><i class="fas fa-calendar-alt me-2"></i> Lessenplanning</h1>
                <div>
                    <button type="button" id="openModalBtn" class="btn btn-success me-2">
                        <i class="fas fa-plus"></i> Les Toevoegen
                    </button>
                    <div class="btn-group me-2" role="group">
                        <input type="radio" class="btn-check" name="view" id="dagView" value="dag" <?= $viewType == 'dag' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="dagView">
                            <i class="fas fa-calendar-day"></i> Dag
                        </label>

                        <input type="radio" class="btn-check" name="view" id="weekView" value="week" <?= $viewType == 'week' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="weekView">
                            <i class="fas fa-calendar-week"></i> Week
                        </label>

                        <input type="radio" class="btn-check" name="view" id="maandView" value="maand" <?= $viewType == 'maand' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="maandView">
                            <i class="fas fa-calendar"></i> Maand
                        </label>
                    </div>
                    <a href="<?php echo URLROOT; ?>/instructeurs" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Terug naar Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Datum navigatie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background-color: #2d3748; border: 1px solid #4a5568;">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="voorigePeriode">
                                <i class="fas fa-chevron-left"></i> Vorige
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="vandaag">
                                <i class="fas fa-calendar-day"></i> Vandaag
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="volgendePeriode">
                                Volgende <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div>
                            <input type="date" class="form-control form-control-sm d-inline-block" 
                                   id="datumPicker" value="<?= $datum ?>" style="width: auto;">
                        </div>
                        <div>
                            <h5 class="mb-0 text-light" id="huidigeperiode">
                                <?php
                                $datumObj = new DateTime($datum);
                                switch($viewType) {
                                    case 'dag':
                                        echo $datumObj->format('l d F Y');
                                        break;
                                    case 'week':
                                        $weekStart = clone $datumObj;
                                        $weekStart->modify('monday this week');
                                        $weekEnd = clone $weekStart;
                                        $weekEnd->modify('+6 days');
                                        echo 'Week ' . $datumObj->format('W') . ': ' . $weekStart->format('d') . ' - ' . $weekEnd->format('d F Y');
                                        break;
                                    case 'maand':
                                        echo $datumObj->format('F Y');
                                        break;
                                }
                                ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php flash('success_message'); ?>
    <?php flash('error_message'); ?>

    <!-- Planning Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow" style="background-color: #2d3748; border: 1px solid #4a5568;">
                <div class="card-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                    <h4 class="mb-0 text-light">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Mijn Lessen - <?= ucfirst($viewType) ?>overzicht
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (empty($lessen)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-secondary mb-3"></i>
                            <h5 class="text-light">Geen lessen gepland</h5>
                            <p class="text-secondary">Je hebt geen lessen in deze periode.</p>
                        </div>
                    <?php else: ?>
                        <?php if ($viewType == 'dag'): ?>
                            <!-- Dag weergave -->
                            <div class="timeline-container">
                                <?php foreach ($lessen as $les): ?>
                                    <div class="les-item card mb-3 border-left-<?= getStatusColor($les->status) ?>" style="background-color: #1a202c; border: 1px solid #4a5568;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <h5 class="text-primary mb-1"><?= $les->tijd ?? 'Tijd TBD' ?></h5>
                                                    <small class="text-secondary"><?= date('d-m-Y', strtotime($les->datum)) ?></small>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="mb-1 text-light"><?= htmlspecialchars($les->klant_naam) ?></h6>
                                                    <p class="mb-1 text-light">
                                                        <i class="fas fa-graduation-cap me-1"></i>
                                                        <?= htmlspecialchars($les->pakket_naam) ?>
                                                    </p>
                                                    <p class="mb-0 text-secondary">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        <?= htmlspecialchars($les->locatie_naam) ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-<?= getStatusColor($les->status) ?> fs-6">
                                                        <?= ucfirst($les->status) ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <?php renderLesActies($les); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($viewType == 'week'): ?>
                            <!-- Week weergave -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="12%">Tijd</th>
                                            <th width="12.5%">Maandag</th>
                                            <th width="12.5%">Dinsdag</th>
                                            <th width="12.5%">Woensdag</th>
                                            <th width="12.5%">Donderdag</th>
                                            <th width="12.5%">Vrijdag</th>
                                            <th width="12.5%">Zaterdag</th>
                                            <th width="12.5%">Zondag</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: #1a202c;">
                                        <?php
                                        $tijden = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                                        $weekdagen = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                        
                                        foreach ($tijden as $tijd):
                                        ?>
                                            <tr>
                                                <td style="background-color: #2d3748; border-color: #4a5568;"><strong class="text-light"><?= $tijd ?></strong></td>
                                                <?php foreach ($weekdagen as $day): ?>
                                                    <td class="lesson-cell" style="background-color: #1a202c; border-color: #4a5568;">
                                                        <?php
                                                        foreach ($lessen as $les) {
                                                            $lesDatum = new DateTime($les->datum);
                                                            if ($lesDatum->format('l') == ucfirst($day) && 
                                                                substr($les->tijd, 0, 5) == $tijd) {
                                                                echo renderWeekLes($les);
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <!-- Maand weergave -->
                            <div class="row">
                                <?php 
                                $gegroepeerd = [];
                                foreach ($lessen as $les) {
                                    $dag = date('d', strtotime($les->datum));
                                    if (!isset($gegroepeerd[$dag])) {
                                        $gegroepeerd[$dag] = [];
                                    }
                                    $gegroepeerd[$dag][] = $les;
                                }
                                
                                foreach ($gegroepeerd as $dag => $dagLessen):
                                ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card" style="background-color: #1a202c; border: 1px solid #4a5568;">
                                            <div class="card-header" style="background-color: #2d3748; border-bottom: 1px solid #4a5568;">
                                                <h6 class="mb-0 text-light">
                                                    <i class="fas fa-calendar-day me-2"></i>
                                                    <?= $dag ?> <?= date('F', strtotime($dagLessen[0]->datum)) ?>
                                                </h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <?php foreach ($dagLessen as $les): ?>
                                                    <div class="les-item-small mb-2 p-2 rounded" style="background-color: #2d3748; border: 1px solid #4a5568;">
                                                        <small class="fw-bold text-primary"><?= $les->tijd ?></small><br>
                                                        <small class="text-light"><?= htmlspecialchars($les->klant_naam) ?></small><br>
                                                        <small class="text-secondary"><?= htmlspecialchars($les->pakket_naam) ?></small>
                                                        <span class="badge bg-<?= getStatusColor($les->status) ?> float-end">
                                                            <?= ucfirst($les->status) ?>
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal voor nieuwe les toevoegen -->
<div class="modal fade" id="nieuweLesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2d3748; border: 1px solid #4a5568;">
            <div class="modal-header" style="background-color: #1a202c; border-bottom: 1px solid #4a5568;">
                <h5 class="modal-title text-light"><i class="fas fa-plus-circle me-2"></i> Nieuwe Les Toevoegen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= URLROOT ?>/instructeurs/nieuwe_les" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="klant_id" class="form-label text-light">Klant <span class="text-danger">*</span></label>
                        <select class="form-select bg-secondary text-light border-secondary" id="klant_id" name="klant_id" required>
                            <option value="">Selecteer klant...</option>
                            <?php
                            $persoonModel = new Persoon();
                            $klanten = $persoonModel->getKlantenMetStatistieken();
                            foreach ($klanten as $klant):
                                if ($klant->is_active == 1):
                            ?>
                                <option value="<?= $klant->persoon_id ?>">
                                    <?= htmlspecialchars($klant->voornaam . ' ' . $klant->achternaam) ?> (<?= $klant->email ?>)
                                </option>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="lespakket_id" class="form-label text-light">Lespakket <span class="text-danger">*</span></label>
                        <select class="form-select bg-secondary text-light border-secondary" id="lespakket_id" name="lespakket_id" required>
                            <option value="">Selecteer lespakket...</option>
                            <?php
                            $lespakketModel = new Lespakket();
                            $pakketten = $lespakketModel->getAllLespakketten();
                            foreach ($pakketten as $pakket):
                            ?>
                                <option value="<?= $pakket->id ?>">
                                    <?= htmlspecialchars($pakket->naam) ?> - €<?= number_format($pakket->prijs, 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="locatie_id" class="form-label text-light">Locatie <span class="text-danger">*</span></label>
                        <select class="form-select bg-secondary text-light border-secondary" id="locatie_id" name="locatie_id" required>
                            <option value="">Selecteer locatie...</option>
                            <?php
                            $locatieModel = new Locatie();
                            $locaties = $locatieModel->getAllLocaties();
                            foreach ($locaties as $locatie):
                            ?>
                                <option value="<?= $locatie->id ?>">
                                    <?= htmlspecialchars($locatie->naam) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bevestigde_datum" class="form-label text-light">Datum <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-secondary text-light border-secondary" id="bevestigde_datum" name="bevestigde_datum" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bevestigde_tijd" class="form-label text-light">Tijd <span class="text-danger">*</span></label>
                            <input type="time" class="form-control bg-secondary text-light border-secondary" id="bevestigde_tijd" name="bevestigde_tijd" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="opmerkingen" class="form-label">Opmerkingen</label>
                        <textarea class="form-control" id="opmerkingen" name="opmerkingen" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Les Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
console.log('Script is loading...');

// Open modal when button is clicked
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded fired!');
    var openModalBtn = document.getElementById('openModalBtn');
    var modalElement = document.getElementById('nieuweLesModal');
    console.log('Button:', openModalBtn);
    console.log('Modal:', modalElement);
    
    if (openModalBtn && modalElement) {
        openModalBtn.addEventListener('click', function() {
            console.log('Opening modal...');
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    } else {
        console.error('Button or modal not found!', {
            button: openModalBtn,
            modal: modalElement
        });
    }
});

// View switcher
document.querySelectorAll('input[name="view"]').forEach(input => {
    input.addEventListener('change', function() {
        updateUrl();
    });
});

// Datum picker
document.getElementById('datumPicker').addEventListener('change', function() {
    updateUrl();
});

// Navigatie buttons
document.getElementById('voorigePeriode').addEventListener('click', function() {
    navigatePeriod(-1);
});

document.getElementById('volgendePeriode').addEventListener('click', function() {
    navigatePeriod(1);
});

document.getElementById('vandaag').addEventListener('click', function() {
    document.getElementById('datumPicker').value = new Date().toISOString().split('T')[0];
    updateUrl();
});

function updateUrl() {
    const view = document.querySelector('input[name="view"]:checked').value;
    const datum = document.getElementById('datumPicker').value;
    window.location.href = `<?= URLROOT ?>/instructeurs/planning?view=${view}&datum=${datum}`;
}

function navigatePeriod(direction) {
    const currentDate = new Date(document.getElementById('datumPicker').value);
    const view = document.querySelector('input[name="view"]:checked').value;
    
    switch(view) {
        case 'dag':
            currentDate.setDate(currentDate.getDate() + direction);
            break;
        case 'week':
            currentDate.setDate(currentDate.getDate() + (direction * 7));
            break;
        case 'maand':
            currentDate.setMonth(currentDate.getMonth() + direction);
            break;
    }
    
    document.getElementById('datumPicker').value = currentDate.toISOString().split('T')[0];
    updateUrl();
}

function snelleAnnulering(lesId, template, naam) {
    const templates = {
        'ziekte': 'ziekte van instructeur',
        'weer': 'slechte weersomstandigheden (windkracht > 10)'
    };
    
    if (confirm(`Weet je zeker dat je de les van ${naam} wilt annuleren vanwege ${templates[template]}?`)) {
        window.location.href = `<?= URLROOT ?>/instructeurs/snelle_annulering/${lesId}/${template}`;
    }
}
</script>

<style>
.border-left-primary { border-left: 4px solid #0d6efd !important; }
.border-left-success { border-left: 4px solid #198754 !important; }
.border-left-warning { border-left: 4px solid #ffc107 !important; }
.border-left-danger { border-left: 4px solid #dc3545 !important; }
.border-left-secondary { border-left: 4px solid #6c757d !important; }

.lesson-cell {
    height: 60px;
    vertical-align: top;
    position: relative;
}

.les-item-small {
    font-size: 0.75rem;
    background-color: #f8f9fa;
}

.timeline-container .les-item {
    transition: all 0.3s ease;
}

.timeline-container .les-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-group .btn-check:checked + .btn {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}
</style>

<?php include_once '../app/views/includes/footer.php'; ?>