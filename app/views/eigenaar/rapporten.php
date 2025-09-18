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

            <!-- Filter en Selectie -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo URLROOT; ?>/eigenaar/rapporten" class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Rapport Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="omzet" <?php echo ($data['type'] == 'omzet') ? 'selected' : ''; ?>>Omzet Analyse</option>
                                <option value="gebruikers" <?php echo ($data['type'] == 'gebruikers') ? 'selected' : ''; ?>>Gebruikers Statistieken</option>
                                <option value="lessen" <?php echo ($data['type'] == 'lessen') ? 'selected' : ''; ?>>Lessen Overzicht</option>
                                <option value="instructeurs" <?php echo ($data['type'] == 'instructeurs') ? 'selected' : ''; ?>>Instructeur Prestaties</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="periode" class="form-label">Periode</label>
                            <select name="periode" id="periode" class="form-select">
                                <option value="maand" <?php echo ($data['periode'] == 'maand') ? 'selected' : ''; ?>>Per Maand</option>
                                <option value="kwartaal" <?php echo ($data['periode'] == 'kwartaal') ? 'selected' : ''; ?>>Per Kwartaal</option>
                                <option value="jaar" <?php echo ($data['periode'] == 'jaar') ? 'selected' : ''; ?>>Per Jaar</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="datum" class="form-label">Datum/Periode</label>
                            <input type="month" name="datum" id="datum" class="form-control" 
                                   value="<?php echo $data['datum']; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-chart-bar"></i> Rapport Genereren
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Rapport Inhoud -->
            <div class="row">
                <div class="col-md-12">
                    <?php if($data['type'] == 'omzet'): ?>
                        <!-- Omzet Rapport -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-euro-sign"></i> Omzet Analyse - <?php echo date('F Y', strtotime($data['datum'])); ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-success">€<?php echo number_format($data['rapport_data']['totale_omzet'] ?? 0, 2); ?></h3>
                                            <p class="text-muted">Totale Omzet</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-info"><?php echo $data['rapport_data']['aantal_betalingen'] ?? 0; ?></h3>
                                            <p class="text-muted">Aantal Betalingen</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-primary">€<?php echo number_format($data['rapport_data']['gemiddelde_betaling'] ?? 0, 2); ?></h3>
                                            <p class="text-muted">Gemiddelde Betaling</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-warning">€<?php echo number_format($data['rapport_data']['openstaand_bedrag'] ?? 0, 2); ?></h3>
                                            <p class="text-muted">Openstaand</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if(!empty($data['rapport_data']['dagelijkse_omzet'])): ?>
                                    <h6>Dagelijkse Omzet Overzicht</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Datum</th>
                                                    <th>Aantal Betalingen</th>
                                                    <th>Omzet</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data['rapport_data']['dagelijkse_omzet'] as $dag): ?>
                                                    <tr>
                                                        <td><?php echo date('d-m-Y', strtotime($dag->datum)); ?></td>
                                                        <td><?php echo $dag->aantal; ?></td>
                                                        <td>€<?php echo number_format($dag->omzet, 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    
                    <?php elseif($data['type'] == 'gebruikers'): ?>
                        <!-- Gebruikers Rapport -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users"></i> Gebruikers Statistieken - <?php echo date('F Y', strtotime($data['datum'])); ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-primary"><?php echo $data['rapport_data']['totaal_gebruikers'] ?? 0; ?></h3>
                                            <p class="text-muted">Totaal Gebruikers</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-success"><?php echo $data['rapport_data']['nieuwe_gebruikers'] ?? 0; ?></h3>
                                            <p class="text-muted">Nieuwe Gebruikers</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-info"><?php echo $data['rapport_data']['actieve_gebruikers'] ?? 0; ?></h3>
                                            <p class="text-muted">Actieve Gebruikers</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-warning"><?php echo number_format($data['rapport_data']['conversie_rate'] ?? 0, 1); ?>%</h3>
                                            <p class="text-muted">Conversie Rate</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Verdeling per Rol</h6>
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td>Klanten</td>
                                                    <td><span class="badge bg-primary"><?php echo $data['rapport_data']['rol_verdeling']['klant'] ?? 0; ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Instructeurs</td>
                                                    <td><span class="badge bg-warning"><?php echo $data['rapport_data']['rol_verdeling']['instructeur'] ?? 0; ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Eigenaren</td>
                                                    <td><span class="badge bg-danger"><?php echo $data['rapport_data']['rol_verdeling']['eigenaar'] ?? 0; ?></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Registraties per Week</h6>
                                        <?php if(!empty($data['rapport_data']['wekelijkse_registraties'])): ?>
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Week</th>
                                                        <th>Registraties</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($data['rapport_data']['wekelijkse_registraties'] as $week): ?>
                                                        <tr>
                                                            <td>Week <?php echo $week->week_nummer; ?></td>
                                                            <td><?php echo $week->aantal; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <?php elseif($data['type'] == 'lessen'): ?>
                        <!-- Lessen Rapport -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-graduation-cap"></i> Lessen Overzicht - <?php echo date('F Y', strtotime($data['datum'])); ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-success"><?php echo $data['rapport_data']['totaal_lessen'] ?? 0; ?></h3>
                                            <p class="text-muted">Totaal Lessen</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-info"><?php echo $data['rapport_data']['voltooide_lessen'] ?? 0; ?></h3>
                                            <p class="text-muted">Voltooid</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-warning"><?php echo $data['rapport_data']['geannuleerde_lessen'] ?? 0; ?></h3>
                                            <p class="text-muted">Geannuleerd</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h3 class="text-primary"><?php echo number_format($data['rapport_data']['gemiddelde_beoordeling'] ?? 0, 1); ?></h3>
                                            <p class="text-muted">Gem. Beoordeling</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if(!empty($data['rapport_data']['populaire_pakketten'])): ?>
                                    <h6>Populairste Lespakketten</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Lespakket</th>
                                                    <th>Aantal Reserveringen</th>
                                                    <th>Omzet</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data['rapport_data']['populaire_pakketten'] as $pakket): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($pakket->naam); ?></td>
                                                        <td><?php echo $pakket->aantal_reserveringen; ?></td>
                                                        <td>€<?php echo number_format($pakket->omzet, 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    
                    <?php elseif($data['type'] == 'instructeurs'): ?>
                        <!-- Instructeurs Rapport -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chalkboard-teacher"></i> Instructeur Prestaties - <?php echo date('F Y', strtotime($data['datum'])); ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if(!empty($data['rapport_data']['instructeur_stats'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Instructeur</th>
                                                    <th>Aantal Lessen</th>
                                                    <th>Voltooide Lessen</th>
                                                    <th>Gem. Beoordeling</th>
                                                    <th>Gegenereerde Omzet</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data['rapport_data']['instructeur_stats'] as $instructeur): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($instructeur->naam); ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($instructeur->email); ?></small>
                                                        </td>
                                                        <td><?php echo $instructeur->totaal_lessen; ?></td>
                                                        <td>
                                                            <?php echo $instructeur->voltooide_lessen; ?>
                                                            <span class="text-muted">
                                                                (<?php echo $instructeur->totaal_lessen > 0 ? round(($instructeur->voltooide_lessen / $instructeur->totaal_lessen) * 100) : 0; ?>%)
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php echo number_format($instructeur->gemiddelde_beoordeling, 1); ?>/5
                                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?php echo $i <= $instructeur->gemiddelde_beoordeling ? 'text-warning' : 'text-muted'; ?>"></i>
                                                            <?php endfor; ?>
                                                        </td>
                                                        <td>€<?php echo number_format($instructeur->gegenereerde_omzet, 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Geen instructeur data beschikbaar voor deze periode.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Export en Acties -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Export Opties</h5>
                            <div class="btn-group" role="group">
                                <a href="<?php echo URLROOT; ?>/eigenaar/export_rapport?type=<?php echo $data['type']; ?>&periode=<?php echo $data['periode']; ?>&datum=<?php echo $data['datum']; ?>&format=pdf" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Export als PDF
                                </a>
                                <a href="<?php echo URLROOT; ?>/eigenaar/export_rapport?type=<?php echo $data['type']; ?>&periode=<?php echo $data['periode']; ?>&datum=<?php echo $data['datum']; ?>&format=excel" 
                                   class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export als Excel
                                </a>
                                <a href="<?php echo URLROOT; ?>/eigenaar/export_rapport?type=<?php echo $data['type']; ?>&periode=<?php echo $data['periode']; ?>&datum=<?php echo $data['datum']; ?>&format=csv" 
                                   class="btn btn-info">
                                    <i class="fas fa-file-csv"></i> Export als CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periode');
    const datumInput = document.getElementById('datum');
    
    periodeSelect.addEventListener('change', function() {
        const periode = this.value;
        if (periode === 'jaar') {
            datumInput.type = 'number';
            datumInput.value = new Date().getFullYear();
            datumInput.setAttribute('min', '2020');
            datumInput.setAttribute('max', new Date().getFullYear());
        } else {
            datumInput.type = 'month';
            datumInput.value = new Date().toISOString().slice(0, 7);
        }
    });
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>