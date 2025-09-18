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

            <!-- Filter Sectie -->
            <div class="card mb-4 border-0 shadow-lg card-dark">
                <div class="card-body">
                    <form method="GET" action="<?php echo URLROOT; ?>/eigenaar/logs" class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Log Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="alle" <?php echo ($data['type'] == 'alle') ? 'selected' : ''; ?>>Alle Logs</option>
                                <option value="info" <?php echo ($data['type'] == 'info') ? 'selected' : ''; ?>>Informatie</option>
                                <option value="warning" <?php echo ($data['type'] == 'warning') ? 'selected' : ''; ?>>Waarschuwingen</option>
                                <option value="error" <?php echo ($data['type'] == 'error') ? 'selected' : ''; ?>>Fouten</option>
                                <option value="security" <?php echo ($data['type'] == 'security') ? 'selected' : ''; ?>>Beveiliging</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="datum" class="form-label">Datum</label>
                            <input type="date" name="datum" id="datum" class="form-control" 
                                   value="<?php echo $data['datum']; ?>">
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
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                                    <i class="fas fa-trash"></i> Logs Wissen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Log Statistieken -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="text-light"><?php echo count(array_filter($data['logs'], function($log) { return $log['type'] == 'info'; })); ?></h4>
                                    <p class="mb-0 text-light-emphasis">Info Logs</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-info-circle fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="text-light"><?php echo count(array_filter($data['logs'], function($log) { return $log['type'] == 'warning'; })); ?></h4>
                                    <p class="mb-0 text-light-emphasis">Waarschuwingen</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="text-light"><?php echo count(array_filter($data['logs'], function($log) { return $log['type'] == 'error'; })); ?></h4>
                                    <p class="mb-0 text-light-emphasis">Fouten</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="text-light"><?php echo count(array_filter($data['logs'], function($log) { return $log['type'] == 'security'; })); ?></h4>
                                    <p class="mb-0 text-light-emphasis">Beveiliging</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shield-alt fa-2x text-dark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Tabel -->
            <div class="card border-0 shadow-lg card-dark">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 text-light">
                        Systeem Logs - <?php echo date('d-m-Y', strtotime($data['datum'])); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($data['logs'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 120px;">Tijd</th>
                                        <th style="width: 100px;">Type</th>
                                        <th>Bericht</th>
                                        <th style="width: 200px;">Gebruiker/IP</th>
                                        <th style="width: 80px;">Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['logs'] as $log): ?>
                                        <tr>
                                            <td>
                                                <small><?php echo $log['tijd']; ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $log['type'] == 'error' ? 'danger' : 
                                                        ($log['type'] == 'warning' ? 'warning' : 
                                                        ($log['type'] == 'security' ? 'dark' : 'info')); 
                                                ?>">
                                                    <?php echo strtoupper($log['type']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="log-message"><?php echo htmlspecialchars($log['bericht']); ?></span>
                                                <?php if(isset($log['details'])): ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($log['details']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small>
                                                    <?php if(isset($log['gebruiker'])): ?>
                                                        <strong><?php echo htmlspecialchars($log['gebruiker']); ?></strong><br>
                                                    <?php endif; ?>
                                                    <?php if(isset($log['ip_adres'])): ?>
                                                        IP: <?php echo htmlspecialchars($log['ip_adres']); ?>
                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#logDetailModal"
                                                        data-log-data="<?php echo htmlspecialchars(json_encode($log)); ?>"
                                                        title="Details bekijken">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Geen logs gevonden</h5>
                            <p class="text-muted">Er zijn geen logs voor de geselecteerde datum en filters.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Live Log Monitoring -->
            <div class="card mt-4 border-0 shadow-lg card-dark">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-light">Live Log Monitoring</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-success" id="startMonitoring">
                                <i class="fas fa-play"></i> Start
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="stopMonitoring" disabled>
                                <i class="fas fa-stop"></i> Stop
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="liveLogContainer" style="height: 200px; overflow-y: auto; background-color: #1e1e1e; color: #ffffff; padding: 10px; font-family: monospace;">
                        <p class="text-muted">Klik op 'Start' om live logs te bekijken...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Detail Modal -->
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailModalLabel">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailContent">
                    <!-- Content will be filled by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-labelledby="clearLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearLogsModalLabel">Logs Wissen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je de logs wilt wissen?</p>
                
                <div class="mb-3">
                    <label for="clear_type" class="form-label">Welke logs wissen?</label>
                    <select id="clear_type" class="form-select">
                        <option value="older_than_30">Ouder dan 30 dagen</option>
                        <option value="older_than_90">Ouder dan 90 dagen</option>
                        <option value="selected_date">Alleen geselecteerde datum</option>
                        <option value="all">Alle logs</option>
                    </select>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Waarschuwing:</strong> Deze actie kan niet ongedaan worden gemaakt.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-danger" id="confirmClearLogs">
                    <i class="fas fa-trash"></i> Logs Wissen
                </button>
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

.card-dark .form-control,
.card-dark .form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.card-dark .form-label {
    color: rgba(255, 255, 255, 0.9);
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logDetailModal = document.getElementById('logDetailModal');
    const logDetailContent = document.getElementById('logDetailContent');
    const startMonitoringBtn = document.getElementById('startMonitoring');
    const stopMonitoringBtn = document.getElementById('stopMonitoring');
    const liveLogContainer = document.getElementById('liveLogContainer');
    const confirmClearLogsBtn = document.getElementById('confirmClearLogs');
    const clearTypeSelect = document.getElementById('clear_type');
    
    let monitoringInterval = null;
    
    // Log detail modal
    logDetailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const logData = JSON.parse(button.getAttribute('data-log-data'));
        
        let detailHtml = `
            <table class="table table-borderless">
                <tr><td><strong>Tijd:</strong></td><td>${logData.tijd}</td></tr>
                <tr><td><strong>Type:</strong></td><td><span class="badge bg-${logData.type == 'error' ? 'danger' : (logData.type == 'warning' ? 'warning' : (logData.type == 'security' ? 'dark' : 'info'))}">${logData.type.toUpperCase()}</span></td></tr>
                <tr><td><strong>Bericht:</strong></td><td>${logData.bericht}</td></tr>
        `;
        
        if (logData.gebruiker) {
            detailHtml += `<tr><td><strong>Gebruiker:</strong></td><td>${logData.gebruiker}</td></tr>`;
        }
        
        if (logData.ip_adres) {
            detailHtml += `<tr><td><strong>IP Adres:</strong></td><td>${logData.ip_adres}</td></tr>`;
        }
        
        if (logData.details) {
            detailHtml += `<tr><td><strong>Details:</strong></td><td>${logData.details}</td></tr>`;
        }
        
        if (logData.stack_trace) {
            detailHtml += `<tr><td><strong>Stack Trace:</strong></td><td><pre style="white-space: pre-wrap; font-size: 12px;">${logData.stack_trace}</pre></td></tr>`;
        }
        
        detailHtml += '</table>';
        logDetailContent.innerHTML = detailHtml;
    });
    
    // Live monitoring
    startMonitoringBtn.addEventListener('click', function() {
        startMonitoringBtn.disabled = true;
        stopMonitoringBtn.disabled = false;
        liveLogContainer.innerHTML = '<p class="text-success">Live monitoring gestart...</p>';
        
        monitoringInterval = setInterval(function() {
            // Simulate live log updates
            const currentTime = new Date().toLocaleTimeString();
            const logTypes = ['info', 'warning', 'error'];
            const randomType = logTypes[Math.floor(Math.random() * logTypes.length)];
            const messages = [
                'Gebruiker heeft ingelogd',
                'Database query uitgevoerd',
                'Email verzonden',
                'Cache geleegd',
                'Backup aangemaakt'
            ];
            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            
            const logLine = `<div style="margin-bottom: 5px;">[${currentTime}] <span style="color: ${randomType == 'error' ? '#ff6b6b' : (randomType == 'warning' ? '#ffd93d' : '#51cf66')}">[${randomType.toUpperCase()}]</span> ${randomMessage}</div>`;
            liveLogContainer.innerHTML += logLine;
            liveLogContainer.scrollTop = liveLogContainer.scrollHeight;
        }, 2000);
    });
    
    stopMonitoringBtn.addEventListener('click', function() {
        startMonitoringBtn.disabled = false;
        stopMonitoringBtn.disabled = true;
        clearInterval(monitoringInterval);
        liveLogContainer.innerHTML += '<p class="text-danger">Live monitoring gestopt.</p>';
    });
    
    // Clear logs
    confirmClearLogsBtn.addEventListener('click', function() {
        const clearType = clearTypeSelect.value;
        
        // Send AJAX request to clear logs
        fetch('<?php echo URLROOT; ?>/eigenaar/clear_logs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                clear_type: clearType,
                datum: '<?php echo $data['datum']; ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Er is een fout opgetreden bij het wissen van de logs.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Er is een fout opgetreden bij het wissen van de logs.');
        });
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('clearLogsModal'));
        modal.hide();
    });
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>