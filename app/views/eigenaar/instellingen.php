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

            <div class="row">
                <!-- Bedrijf Instellingen -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-building"></i> Bedrijf Instellingen
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/instellingen">
                                <div class="mb-3">
                                    <label for="bedrijfsnaam" class="form-label">Bedrijfsnaam</label>
                                    <input type="text" name="bedrijfsnaam" id="bedrijfsnaam" class="form-control" 
                                           value="<?php echo htmlspecialchars($data['instellingen']['bedrijfsnaam']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Contact Email</label>
                                    <input type="email" name="email" id="email" class="form-control" 
                                           value="<?php echo htmlspecialchars($data['instellingen']['email']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="telefoon" class="form-label">Telefoon</label>
                                    <input type="tel" name="telefoon" id="telefoon" class="form-control" 
                                           value="<?php echo htmlspecialchars($data['instellingen']['telefoon']); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="adres" class="form-label">Adres</label>
                                    <textarea name="adres" id="adres" rows="3" class="form-control"><?php echo htmlspecialchars($data['instellingen']['adres']); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="btw_nummer" class="form-label">BTW Nummer</label>
                                    <input type="text" name="btw_nummer" id="btw_nummer" class="form-control" 
                                           value="<?php echo htmlspecialchars($data['instellingen']['btw_nummer']); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bank_rekening" class="form-label">Bank Rekening (IBAN)</label>
                                    <input type="text" name="bank_rekening" id="bank_rekening" class="form-control" 
                                           value="<?php echo htmlspecialchars($data['instellingen']['bank_rekening']); ?>">
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Instellingen Opslaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Systeem Instellingen -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs"></i> Systeem Instellingen
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/instellingen">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="email_automatisch" id="email_automatisch" 
                                               <?php echo $data['instellingen']['email_automatisch'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_automatisch">
                                            <strong>Automatische Emails</strong>
                                            <br>
                                            <small class="text-muted">Verstuur automatisch bevestiging emails bij reserveringen en betalingen</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="backup_automatisch" id="backup_automatisch" 
                                               <?php echo $data['instellingen']['backup_automatisch'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="backup_automatisch">
                                            <strong>Automatische Backups</strong>
                                            <br>
                                            <small class="text-muted">Maak dagelijks automatisch een backup van de database</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="max_reserveringen_per_dag" class="form-label">Max Reserveringen per Dag</label>
                                    <input type="number" name="max_reserveringen_per_dag" id="max_reserveringen_per_dag" 
                                           class="form-control" value="10" min="1" max="50">
                                    <small class="text-muted">Maximum aantal reserveringen dat per dag geaccepteerd wordt</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="annulering_deadline" class="form-label">Annulering Deadline (uren)</label>
                                    <input type="number" name="annulering_deadline" id="annulering_deadline" 
                                           class="form-control" value="24" min="1" max="168">
                                    <small class="text-muted">Aantal uren voor de les dat annulering nog mogelijk is</small>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Systeem Instellingen Opslaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Beheer -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-database"></i> Database Beheer
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Backup Maken</h6>
                                    <p class="text-muted">Maak een handmatige backup van de database</p>
                                    <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/backup">
                                        <div class="mb-3">
                                            <select name="backup_type" class="form-select">
                                                <option value="volledig">Volledige Backup</option>
                                                <option value="data">Alleen Data</option>
                                                <option value="structuur">Alleen Structuur</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-download"></i> Backup Maken
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <h6>Database Status</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Grootte:</td>
                                            <td><span class="badge bg-info">~12.5 MB</span></td>
                                        </tr>
                                        <tr>
                                            <td>Tabellen:</td>
                                            <td><span class="badge bg-primary">8</span></td>
                                        </tr>
                                        <tr>
                                            <td>Laatste Backup:</td>
                                            <td><small>Gisteren 02:00</small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onderhoud & Monitoring -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tools"></i> Onderhoud & Monitoring
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Cache Beheer</h6>
                                    <p class="text-muted">Beheer de applicatie cache</p>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="clearCache()">
                                            <i class="fas fa-trash"></i> Cache Legen
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" onclick="refreshCache()">
                                            <i class="fas fa-sync"></i> Cache Verversen
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Systeem Status</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>PHP Versie:</td>
                                            <td><span class="badge bg-success"><?php echo PHP_VERSION; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td>Server:</td>
                                            <td><small><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Onbekend'; ?></small></td>
                                        </tr>
                                        <tr>
                                            <td>Disk Ruimte:</td>
                                            <td><span class="badge bg-success">85% vrij</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="text-center">
                                <a href="<?php echo URLROOT; ?>/eigenaar/logs" class="btn btn-dark">
                                    <i class="fas fa-file-alt"></i> Bekijk Systeem Logs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beveiliging -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-shield-alt"></i> Beveiliging & Toegang
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Wachtwoord Beleid</h6>
                                    <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/update_security">
                                        <div class="mb-3">
                                            <label for="min_password_length" class="form-label">Minimale Wachtwoord Lengte</label>
                                            <input type="number" name="min_password_length" id="min_password_length" 
                                                   class="form-control" value="8" min="6" max="32">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="require_special_chars" 
                                                       id="require_special_chars" checked>
                                                <label class="form-check-label" for="require_special_chars">
                                                    Speciale tekens vereist
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-save"></i> Bijwerken
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6>Sessie Instellingen</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Sessie Timeout:</td>
                                            <td><span class="badge bg-info">30 min</span></td>
                                        </tr>
                                        <tr>
                                            <td>Max Login Pogingen:</td>
                                            <td><span class="badge bg-warning">5</span></td>
                                        </tr>
                                        <tr>
                                            <td>Account Lock Duration:</td>
                                            <td><span class="badge bg-danger">15 min</span></td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#securityModal">
                                        <i class="fas fa-edit"></i> Bewerken
                                    </button>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6>Actieve Sessies</h6>
                                    <p class="text-muted">Beheer actieve gebruiker sessies</p>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="viewActiveSessions()">
                                            <i class="fas fa-users"></i> Bekijk Sessies (3)
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="terminateAllSessions()">
                                            <i class="fas fa-power-off"></i> Alle Sessies Beëindigen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Security Settings Modal -->
<div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="securityModalLabel">Beveiliging Instellingen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/update_security_settings">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="session_timeout" class="form-label">Sessie Timeout (minuten)</label>
                        <input type="number" name="session_timeout" id="session_timeout" 
                               class="form-control" value="30" min="5" max="480">
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_login_attempts" class="form-label">Max Login Pogingen</label>
                        <input type="number" name="max_login_attempts" id="max_login_attempts" 
                               class="form-control" value="5" min="3" max="10">
                    </div>
                    
                    <div class="mb-3">
                        <label for="lockout_duration" class="form-label">Account Lock Duration (minuten)</label>
                        <input type="number" name="lockout_duration" id="lockout_duration" 
                               class="form-control" value="15" min="5" max="120">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="enable_two_factor" 
                                   id="enable_two_factor">
                            <label class="form-check-label" for="enable_two_factor">
                                Twee-factor authenticatie inschakelen
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('Weet je zeker dat je de cache wilt legen?')) {
        fetch('<?php echo URLROOT; ?>/eigenaar/clear_cache', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache succesvol geleegd!');
            } else {
                alert('Er is een fout opgetreden bij het legen van de cache.');
            }
        });
    }
}

function refreshCache() {
    fetch('<?php echo URLROOT; ?>/eigenaar/refresh_cache', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cache succesvol ververst!');
        } else {
            alert('Er is een fout opgetreden bij het verversen van de cache.');
        }
    });
}

function viewActiveSessions() {
    window.open('<?php echo URLROOT; ?>/eigenaar/active_sessions', '_blank');
}

function terminateAllSessions() {
    if (confirm('Weet je zeker dat je alle actieve sessies wilt beëindigen? Alle gebruikers worden uitgelogd.')) {
        fetch('<?php echo URLROOT; ?>/eigenaar/terminate_all_sessions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Alle sessies zijn beëindigd. De pagina wordt opnieuw geladen.');
                location.reload();
            } else {
                alert('Er is een fout opgetreden bij het beëindigen van de sessies.');
            }
        });
    }
}
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>