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

            <!-- Statistieken Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                        <div class="card-body text-center">
                            <h3 class="text-light"><?php echo $data['statistieken']['totaal']; ?></h3>
                            <p class="mb-0" style="color: #cbd5e0;">Totaal Reserveringen</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                        <div class="card-body text-center">
                            <h3 class="text-success"><?php echo $data['statistieken']['bevestigd']; ?></h3>
                            <p class="mb-0" style="color: #cbd5e0;">Bevestigd</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                        <div class="card-body text-center">
                            <h3 class="text-warning"><?php echo $data['statistieken']['wachtend']; ?></h3>
                            <p class="mb-0" style="color: #cbd5e0;">Wachtend</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                        <div class="card-body text-center">
                            <h3 class="text-danger"><?php echo $data['statistieken']['geannuleerd']; ?></h3>
                            <p class="mb-0" style="color: #cbd5e0;">Geannuleerd</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Sectie -->
            <div class="card mb-4 border-0 shadow-lg" style="background-color: #2d3748;">
                <div class="card-body">
                    <form method="GET" action="<?php echo URLROOT; ?>/eigenaar/reserveringen" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label text-light">Status Filter</label>
                            <select name="status" id="status" class="form-select bg-dark text-light" style="border-color: #4a5568;">
                                <option value="alle" <?php echo ($data['status'] == 'alle') ? 'selected' : ''; ?>>Alle Statussen</option>
                                <option value="aangevraagd" <?php echo ($data['status'] == 'aangevraagd') ? 'selected' : ''; ?>>Aangevraagd</option>
                                <option value="bevestigd" <?php echo ($data['status'] == 'bevestigd') ? 'selected' : ''; ?>>Bevestigd</option>
                                <option value="geannuleerd" <?php echo ($data['status'] == 'geannuleerd') ? 'selected' : ''; ?>>Geannuleerd</option>
                                <option value="afgerond" <?php echo ($data['status'] == 'afgerond') ? 'selected' : ''; ?>>Afgerond</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="periode" class="form-label text-light">Periode</label>
                            <select name="periode" id="periode" class="form-select bg-dark text-light" style="border-color: #4a5568;">
                                <option value="alle" <?php echo ($data['periode'] == 'alle') ? 'selected' : ''; ?>>Alle Periodes</option>
                                <option value="vandaag" <?php echo ($data['periode'] == 'vandaag') ? 'selected' : ''; ?>>Vandaag</option>
                                <option value="week" <?php echo ($data['periode'] == 'week') ? 'selected' : ''; ?>>Deze Week</option>
                                <option value="maand" <?php echo ($data['periode'] == 'maand') ? 'selected' : ''; ?>>Deze Maand</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-dark-theme">
                                    <i class="fas fa-filter"></i> Filter Toepassen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reserveringen Tabel -->
            <div class="card border-0 shadow-lg" style="background-color: #2d3748;">
                <div class="card-body">
                    <?php if(!empty($data['reserveringen'])): ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead style="background-color: #1a202c;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Klant</th>
                                        <th>Lespakket</th>
                                        <th>Locatie</th>
                                        <th>Datum</th>
                                        <th>Status</th>
                                        <th>Betaling</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['reserveringen'] as $reservering): ?>
                                        <tr>
                                            <td>#<?php echo $reservering->id; ?></td>
                                            <td>
                                                <?php 
                                                $voornaam = $reservering->klant_voornaam ?? 'Onbekend';
                                                $achternaam = $reservering->klant_achternaam ?? '';
                                                echo htmlspecialchars($voornaam . ' ' . $achternaam); 
                                                ?><br>
                                                <small style="color: #cbd5e0;"><?php echo htmlspecialchars($reservering->klant_email ?? 'Geen email'); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($reservering->pakket_naam ?? 'Onbekend pakket'); ?></td>
                                            <td><?php echo htmlspecialchars($reservering->locatie_naam ?? 'Onbekende locatie'); ?></td>
                                            <td>
                                                <?php 
                                                if(isset($reservering->gewenste_datum) && $reservering->gewenste_datum) {
                                                    echo date('d-m-Y', strtotime($reservering->gewenste_datum));
                                                } else {
                                                    echo 'Niet opgegeven';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $status = $reservering->status ?? 'onbekend';
                                                switch($status) {
                                                    case 'aangevraagd':
                                                        $statusClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'bevestigd':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'geannuleerd':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                    case 'afgerond':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    default:
                                                        $statusClass = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $betalingClass = '';
                                                $betaalStatus = $reservering->betaal_status ?? 'onbekend';
                                                switch($betaalStatus) {
                                                    case 'betaald':
                                                        $betalingClass = 'bg-success';
                                                        break;
                                                    case 'wachtend':
                                                        $betalingClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'mislukt':
                                                        $betalingClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $betalingClass = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $betalingClass; ?>">
                                                    <?php echo ucfirst($betaalStatus); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo URLROOT; ?>/eigenaar/reservering_details/<?php echo $reservering->id; ?>" 
                                                       class="btn btn-outline-info btn-sm btn-dark-theme">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if($status == 'aangevraagd'): ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm btn-dark-theme" 
                                                                data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $reservering->id; ?>">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if(in_array($status, ['aangevraagd', 'bevestigd'])): ?>
                                                        <button type="button" class="btn btn-outline-danger btn-sm btn-dark-theme" 
                                                                data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $reservering->id; ?>">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Status Change Modal -->
                                        <?php if($status == 'aangevraagd'): ?>
                                            <div class="modal fade" id="statusModal<?php echo $reservering->id; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reservering Bevestigen</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?php echo URLROOT; ?>/eigenaar/reservering_status/<?php echo $reservering->id; ?>" method="POST">
                                                            <div class="modal-body">
                                                                <p>Reservering bevestigen voor: <strong><?php echo htmlspecialchars($voornaam . ' ' . $achternaam); ?></strong></p>
                                                                
                                                                <div class="mb-3">
                                                                    <label for="status<?php echo $reservering->id; ?>" class="form-label">Nieuwe Status</label>
                                                                    <select class="form-control" id="status<?php echo $reservering->id; ?>" name="status" required>
                                                                        <option value="bevestigd">Bevestigen</option>
                                                                        <option value="geannuleerd">Afwijzen</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="mb-3">
                                                                    <label for="opmerking<?php echo $reservering->id; ?>" class="form-label">Opmerking (optioneel)</label>
                                                                    <textarea class="form-control" id="opmerking<?php echo $reservering->id; ?>" name="opmerking" 
                                                                              rows="3" placeholder="Aanvullende informatie..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-check me-1"></i>Bijwerken
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Cancel Modal -->
                                        <?php if(in_array($status, ['aangevraagd', 'bevestigd'])): ?>
                                            <div class="modal fade" id="cancelModal<?php echo $reservering->id; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reservering Annuleren</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?php echo URLROOT; ?>/eigenaar/reservering_status/<?php echo $reservering->id; ?>" method="POST">
                                                            <div class="modal-body">
                                                                <p>Reservering annuleren voor: <strong><?php echo htmlspecialchars($voornaam . ' ' . $achternaam); ?></strong></p>
                                                                
                                                                <input type="hidden" name="status" value="geannuleerd">
                                                                
                                                                <div class="mb-3">
                                                                    <label for="reden<?php echo $reservering->id; ?>" class="form-label">Reden voor annulering *</label>
                                                                    <textarea class="form-control" id="reden<?php echo $reservering->id; ?>" name="opmerking" 
                                                                              rows="3" required placeholder="Geef een reden op..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-times me-1"></i>Annuleren
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-light-emphasis">Geen reserveringen gevonden</h5>
                            <p class="text-light-emphasis">Er zijn geen reserveringen die voldoen aan de geselecteerde filters.</p>
                        </div>
                    <?php endif; ?>
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
}

.card-dark .table {
    color: #fff;
}

.card-dark .table th,
.card-dark .table td {
    border-color: rgba(255, 255, 255, 0.1);
}

.card-dark .table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.card-dark .form-control,
.card-dark .form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.card-dark .form-label {
    color: rgba(255, 255, 255, 0.9);
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>
