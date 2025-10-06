<?php include_once '../app/views/includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-calendar-alt"></i> Lessenplanning</h1>
                <div>
                    <div class="btn-group me-2" role="group">
                        <input type="radio" class="btn-check" name="view" id="dagView" value="dag" <?= $data['view'] == 'dag' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="dagView">
                            <i class="fas fa-calendar-day"></i> Dag
                        </label>

                        <input type="radio" class="btn-check" name="view" id="weekView" value="week" <?= $data['view'] == 'week' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="weekView">
                            <i class="fas fa-calendar-week"></i> Week
                        </label>

                        <input type="radio" class="btn-check" name="view" id="maandView" value="maand" <?= $data['view'] == 'maand' ? 'checked' : '' ?>>
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
            <div class="card bg-light">
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
                                   id="datumPicker" value="<?= $data['datum'] ?>" style="width: auto;">
                        </div>
                        <div>
                            <h5 class="mb-0" id="huidigeperiode">
                                <?php
                                $datum = new DateTime($data['datum']);
                                switch($data['view']) {
                                    case 'dag':
                                        echo $datum->format('l d F Y');
                                        break;
                                    case 'week':
                                        $weekStart = clone $datum;
                                        $weekStart->modify('monday this week');
                                        $weekEnd = clone $weekStart;
                                        $weekEnd->modify('+6 days');
                                        echo 'Week ' . $datum->format('W') . ': ' . $weekStart->format('d') . ' - ' . $weekEnd->format('d F Y');
                                        break;
                                    case 'maand':
                                        echo $datum->format('F Y');
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
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Mijn Lessen - <?= ucfirst($data['view']) ?>overzicht
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['lessen'])): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Geen lessen gepland</h5>
                            <p class="text-muted">Je hebt geen lessen in deze periode.</p>
                        </div>
                    <?php else: ?>
                        <?php if ($data['view'] == 'dag'): ?>
                            <!-- Dag weergave -->
                            <div class="timeline-container">
                                <?php foreach ($data['lessen'] as $les): ?>
                                    <div class="les-item card mb-3 border-left-<?= $this->getStatusColor($les->status) ?>">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <h5 class="text-primary mb-1"><?= $les->tijd ?? 'Tijd TBD' ?></h5>
                                                    <small class="text-muted"><?= date('d-m-Y', strtotime($les->datum)) ?></small>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="mb-1"><?= htmlspecialchars($les->klant_naam) ?></h6>
                                                    <p class="mb-1">
                                                        <i class="fas fa-graduation-cap me-1"></i>
                                                        <?= htmlspecialchars($les->pakket_naam) ?>
                                                    </p>
                                                    <p class="mb-0">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        <?= htmlspecialchars($les->locatie_naam) ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-2">
                                                    <span class="badge bg-<?= $this->getStatusColor($les->status) ?> fs-6">
                                                        <?= ucfirst($les->status) ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <?php $this->renderLesActies($les); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($data['view'] == 'week'): ?>
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
                                    <tbody>
                                        <?php
                                        $tijden = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                                        $weekdagen = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                        
                                        foreach ($tijden as $tijd):
                                        ?>
                                            <tr>
                                                <td class="table-secondary"><strong><?= $tijd ?></strong></td>
                                                <?php foreach ($weekdagen as $day): ?>
                                                    <td class="lesson-cell">
                                                        <?php
                                                        foreach ($data['lessen'] as $les) {
                                                            $lesDatum = new DateTime($les->datum);
                                                            if ($lesDatum->format('l') == ucfirst($day) && 
                                                                substr($les->tijd, 0, 5) == $tijd) {
                                                                echo $this->renderWeekLes($les);
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
                                foreach ($data['lessen'] as $les) {
                                    $dag = date('d', strtotime($les->datum));
                                    if (!isset($gegroepeerd[$dag])) {
                                        $gegroepeerd[$dag] = [];
                                    }
                                    $gegroepeerd[$dag][] = $les;
                                }
                                
                                foreach ($gegroepeerd as $dag => $dagLessen):
                                ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-calendar-day me-2"></i>
                                                    <?= $dag ?> <?= date('F', strtotime($dagLessen[0]->datum)) ?>
                                                </h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <?php foreach ($dagLessen as $les): ?>
                                                    <div class="les-item-small mb-2 p-2 border rounded">
                                                        <small class="fw-bold text-primary"><?= $les->tijd ?></small><br>
                                                        <small><?= htmlspecialchars($les->klant_naam) ?></small><br>
                                                        <small class="text-muted"><?= htmlspecialchars($les->pakket_naam) ?></small>
                                                        <span class="badge bg-<?= $this->getStatusColor($les->status) ?> float-end">
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

<script>
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

<?php
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

<?php include_once '../app/views/includes/footer.php'; ?>