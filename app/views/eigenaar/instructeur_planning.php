<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid dashboard-dark py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-light"><?php echo $data['title']; ?></h1>
                <a href="<?php echo URLROOT; ?>/eigenaar" class="btn btn-outline-secondary btn-dark-theme">
                    <i class="fas fa-arrow-left"></i> Terug naar Dashboard
                </a>
            </div>

            <!-- Instructeur Selectie -->
            <div class="card mb-4 border-0 shadow-lg card-dark">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 text-light">
                        <i class="fas fa-chalkboard-teacher text-warning me-2"></i>Selecteer Instructeur
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" id="planningForm" class="row g-3">
                        <div class="col-md-6">
                            <label for="instructeur_id" class="form-label text-light">Kies Instructeur</label>
                            <select name="instructeur_id" id="instructeur_id" class="form-select" onchange="selectInstructeur()">
                                <option value="">-- Selecteer een instructeur --</option>
                                <?php foreach($data['instructeurs'] as $instructeur): ?>
                                    <option value="<?php echo $instructeur->id; ?>" 
                                            <?php echo (isset($data['geselecteerde_instructeur']) && $data['geselecteerde_instructeur']->id == $instructeur->id) ? 'selected' : ''; ?>>
                                        <?php 
                                        $naam = trim($instructeur->voornaam . ' ' . $instructeur->achternaam);
                                        echo htmlspecialchars($naam ?: $instructeur->email);
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php if(isset($data['geselecteerde_instructeur'])): ?>
                            <div class="col-md-3">
                                <label for="view" class="form-label text-light">Weergave</label>
                                <select name="view" id="view" class="form-select" onchange="updatePlanning()">
                                    <option value="dag" <?php echo ($data['planning_view'] == 'dag') ? 'selected' : ''; ?>>Dag</option>
                                    <option value="week" <?php echo ($data['planning_view'] == 'week') ? 'selected' : ''; ?>>Week</option>
                                    <option value="maand" <?php echo ($data['planning_view'] == 'maand') ? 'selected' : ''; ?>>Maand</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="datum" class="form-label text-light">Datum</label>
                                <input type="date" name="datum" id="datum" class="form-control" 
                                       value="<?php echo $data['datum']; ?>" onchange="updatePlanning()">
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php if(isset($data['geselecteerde_instructeur'])): ?>
                <!-- Instructeur Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg card-dark">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-chalkboard-teacher fa-lg text-dark"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-light mb-1">
                                            <?php 
                                            $naam = trim($data['geselecteerde_instructeur']->voornaam . ' ' . $data['geselecteerde_instructeur']->achternaam);
                                            echo htmlspecialchars($naam ?: 'Naam niet ingevuld');
                                            ?>
                                        </h5>
                                        <p class="text-light-emphasis mb-0">
                                            <i class="fas fa-envelope me-1"></i>
                                            <?php echo htmlspecialchars($data['geselecteerde_instructeur']->email); ?>
                                        </p>
                                        <?php if(isset($data['instructeur_persoon']->telefoon)): ?>
                                            <p class="text-light-emphasis mb-0">
                                                <i class="fas fa-phone me-1"></i>
                                                <?php echo htmlspecialchars($data['instructeur_persoon']->telefoon); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg card-dark">
                            <div class="card-body text-center">
                                <h6 class="text-light mb-2">Planning voor:</h6>
                                <h4 class="text-warning mb-0">
                                    <?php 
                                    switch($data['planning_view']) {
                                        case 'dag':
                                            echo date('d-m-Y', strtotime($data['datum']));
                                            break;
                                        case 'week':
                                            $startWeek = date('Y-m-d', strtotime('monday this week', strtotime($data['datum'])));
                                            $endWeek = date('Y-m-d', strtotime('sunday this week', strtotime($data['datum'])));
                                            echo 'Week ' . date('W', strtotime($data['datum'])) . ' (' . date('d-m', strtotime($startWeek)) . ' t/m ' . date('d-m', strtotime($endWeek)) . ')';
                                            break;
                                        case 'maand':
                                            setlocale(LC_TIME, 'nl_NL.utf8', 'Dutch');
                                            echo ucfirst(strftime('%B %Y', strtotime($data['datum'])));
                                            break;
                                    }
                                    ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Planning Overzicht -->
                <div class="card border-0 shadow-lg card-dark">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0 text-light">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Planning - <?php echo ucfirst($data['planning_view']); ?>overzicht
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($data['planning'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="border-bottom border-secondary">
                                        <tr>
                                            <th class="text-light">Datum & Tijd</th>
                                            <th class="text-light">Klant</th>
                                            <th class="text-light">Lespakket</th>
                                            <th class="text-light">Locatie</th>
                                            <th class="text-light">Status</th>
                                            <th class="text-light">Betaling</th>
                                            <th class="text-light">Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['planning'] as $les): ?>
                                            <tr>
                                                <td class="text-light">
                                                    <div class="fw-semibold"><?= date('d-m-Y', strtotime($les->gewenste_datum)) ?></div>
                                                    <?php if($les->bevestigde_tijd): ?>
                                                        <small class="text-success">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <?= date('H:i', strtotime($les->bevestigde_tijd)) ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Nog niet bevestigd
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-light">
                                                    <div class="fw-semibold"><?= htmlspecialchars($les->klant_naam) ?></div>
                                                    <?php if($les->duo_partner_naam): ?>
                                                        <small class="text-info">
                                                            <i class="fas fa-users me-1"></i>
                                                            + <?= htmlspecialchars($les->duo_partner_naam) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-light">
                                                    <div><?= htmlspecialchars($les->lespakket_naam) ?></div>
                                                    <small class="text-light-emphasis">
                                                        €<?= number_format($les->lespakket_prijs, 2) ?>
                                                    </small>
                                                </td>
                                                <td class="text-light">
                                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                                    <?= htmlspecialchars($les->locatie_naam) ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        switch($les->status) {
                                                            case 'bevestigd': echo 'success'; break;
                                                            case 'aangevraagd': echo 'warning'; break;
                                                            case 'geannuleerd': echo 'danger'; break;
                                                            case 'afgerond': echo 'info'; break;
                                                            default: echo 'secondary';
                                                        }
                                                    ?>">
                                                        <?= ucfirst($les->status) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        switch($les->betaal_status) {
                                                            case 'betaald': echo 'success'; break;
                                                            case 'wachtend': echo 'warning'; break;
                                                            case 'mislukt': echo 'danger'; break;
                                                            default: echo 'secondary';
                                                        }
                                                    ?>">
                                                        <?= ucfirst($les->betaal_status) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= URLROOT ?>/eigenaar/reservering_details/<?= $les->id ?>" 
                                                           class="btn btn-sm btn-outline-primary btn-dark-theme" 
                                                           title="Details bekijken">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        <?php if($les->status !== 'geannuleerd' && $les->status !== 'afgerond'): ?>
                                                            <button type="button" class="btn btn-sm btn-outline-warning btn-dark-theme" 
                                                                    data-bs-toggle="modal" data-bs-target="#statusModal<?= $les->id ?>"
                                                                    title="Status wijzigen">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <!-- Status Wijzig Modal -->
                                            <div class="modal fade" id="statusModal<?= $les->id ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Les Status Wijzigen</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="<?= URLROOT ?>/eigenaar/reservering_status/<?= $les->id ?>">
                                                            <div class="modal-body">
                                                                <p><strong>Klant:</strong> <?= htmlspecialchars($les->klant_naam) ?></p>
                                                                <p><strong>Lespakket:</strong> <?= htmlspecialchars($les->lespakket_naam) ?></p>
                                                                
                                                                <div class="mb-3">
                                                                    <label for="status<?= $les->id ?>" class="form-label">Nieuwe Status</label>
                                                                    <select name="status" id="status<?= $les->id ?>" class="form-select" required>
                                                                        <option value="aangevraagd" <?= $les->status == 'aangevraagd' ? 'selected' : '' ?>>Aangevraagd</option>
                                                                        <option value="bevestigd" <?= $les->status == 'bevestigd' ? 'selected' : '' ?>>Bevestigd</option>
                                                                        <option value="geannuleerd" <?= $les->status == 'geannuleerd' ? 'selected' : '' ?>>Geannuleerd</option>
                                                                        <option value="afgerond" <?= $les->status == 'afgerond' ? 'selected' : '' ?>>Afgerond</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="mb-3">
                                                                    <label for="opmerking<?= $les->id ?>" class="form-label">Opmerking (optioneel)</label>
                                                                    <textarea name="opmerking" id="opmerking<?= $les->id ?>" class="form-control" rows="3" 
                                                                              placeholder="Reden voor statuswijziging..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                                                                <button type="submit" class="btn btn-warning">Status Wijzigen</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-light-emphasis">Geen lessen gevonden</h5>
                                <p class="text-light-emphasis">
                                    Deze instructeur heeft geen geplande lessen voor de geselecteerde periode.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Navigatie Controls -->
                <?php 
                $prevDate = '';
                $nextDate = '';
                
                switch($data['planning_view']) {
                    case 'dag':
                        $prevDate = date('Y-m-d', strtotime($data['datum'] . ' -1 day'));
                        $nextDate = date('Y-m-d', strtotime($data['datum'] . ' +1 day'));
                        break;
                    case 'week':
                        $prevDate = date('Y-m-d', strtotime($data['datum'] . ' -1 week'));
                        $nextDate = date('Y-m-d', strtotime($data['datum'] . ' +1 week'));
                        break;
                    case 'maand':
                        $prevDate = date('Y-m-d', strtotime($data['datum'] . ' -1 month'));
                        $nextDate = date('Y-m-d', strtotime($data['datum'] . ' +1 month'));
                        break;
                }
                ?>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="<?= URLROOT ?>/eigenaar/instructeur_planning/<?= $data['geselecteerde_instructeur']->id ?>?view=<?= $data['planning_view'] ?>&datum=<?= $prevDate ?>" 
                       class="btn btn-outline-secondary btn-dark-theme">
                        <i class="fas fa-chevron-left me-1"></i>
                        Vorige <?= ucfirst($data['planning_view']) ?>
                    </a>
                    
                    <a href="<?= URLROOT ?>/eigenaar/instructeur_planning/<?= $data['geselecteerde_instructeur']->id ?>?view=<?= $data['planning_view'] ?>&datum=<?= date('Y-m-d') ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-calendar-day me-1"></i>
                        Vandaag
                    </a>
                    
                    <a href="<?= URLROOT ?>/eigenaar/instructeur_planning/<?= $data['geselecteerde_instructeur']->id ?>?view=<?= $data['planning_view'] ?>&datum=<?= $nextDate ?>" 
                       class="btn btn-outline-secondary btn-dark-theme">
                        Volgende <?= ucfirst($data['planning_view']) ?>
                        <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-lg card-dark">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                        <h5 class="text-light-emphasis">Selecteer een Instructeur</h5>
                        <p class="text-light-emphasis">
                            Kies een instructeur uit de dropdown hierboven om hun planning te bekijken.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
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
}

.btn-dark-theme {
    background: rgba(40, 40, 60, 0.8) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
}

.btn-dark-theme:hover {
    background: rgba(50, 50, 70, 0.9) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
    color: #fff !important;
}

.form-control, .form-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
}

.form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    color: #fff;
}

.form-select option {
    background: #2d3748;
    color: #fff;
}

.border-secondary {
    border-color: rgba(255, 255, 255, 0.2) !important;
}

.table {
    --bs-table-bg: transparent;
}
</style>

<script>
function selectInstructeur() {
    const instructeurId = document.getElementById('instructeur_id').value;
    if (instructeurId) {
        window.location.href = '<?php echo URLROOT; ?>/eigenaar/instructeur_planning/' + instructeurId;
    }
}

function updatePlanning() {
    const instructeurId = document.getElementById('instructeur_id').value;
    const view = document.getElementById('view').value;
    const datum = document.getElementById('datum').value;
    
    if (instructeurId) {
        window.location.href = '<?php echo URLROOT; ?>/eigenaar/instructeur_planning/' + instructeurId + 
                              '?view=' + view + '&datum=' + datum;
    }
}
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
