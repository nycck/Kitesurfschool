<?php include_once '../app/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="dashboard-header">
                <h1>Instructeur Dashboard</h1>
                <p class="lead">Welkom terug, <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Instructeur'; ?>!</p>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Vandaag's Lessen -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-calendar-day"></i> Vandaag's Lessen</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['vandaag_lessen'])): ?>
                        <p class="text-muted">Geen lessen vandaag</p>
                    <?php else: ?>
                        <?php foreach ($data['vandaag_lessen'] as $les): ?>
                            <div class="reservation-item mb-2 p-2 border-left border-primary">
                                <strong><?php echo isset($les->tijd) ? $les->tijd : 'Tijd onbekend'; ?></strong> - 
                                <?php echo isset($les->klant_naam) ? $les->klant_naam : 'Klant onbekend'; ?>
                                <br>
                                <small class="text-muted">
                                    <?php echo isset($les->pakket_naam) ? $les->pakket_naam : 'Pakket onbekend'; ?> | 
                                    <?php echo isset($les->locatie_naam) ? $les->locatie_naam : 'Locatie onbekend'; ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Aankomende Lessen -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-calendar-alt"></i> Aankomende Lessen</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['aankomende_lessen'])): ?>
                        <p class="text-muted">Geen aankomende lessen</p>
                    <?php else: ?>
                        <?php foreach (array_slice($data['aankomende_lessen'], 0, 5) as $les): ?>
                            <div class="reservation-item mb-2 p-2 border-left border-info">
                                <strong><?php echo isset($les->datum) ? date('d-m-Y', strtotime($les->datum)) : 'Datum onbekend'; ?></strong> 
                                om <?php echo isset($les->tijd) ? $les->tijd : 'Tijd onbekend'; ?>
                                <br>
                                <?php echo isset($les->klant_naam) ? $les->klant_naam : 'Klant onbekend'; ?> - 
                                <?php echo isset($les->pakket_naam) ? $les->pakket_naam : 'Pakket onbekend'; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistieken -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h3><?php echo isset($data['statistieken']['totaal_lessen_gegeven']) ? $data['statistieken']['totaal_lessen_gegeven'] : '0'; ?></h3>
                    <p class="mb-0">Totaal Lessen</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h3><?php echo isset($data['statistieken']['lessen_deze_maand']) ? $data['statistieken']['lessen_deze_maand'] : '0'; ?></h3>
                    <p class="mb-0">Deze Maand</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h3><?php echo isset($data['totaal_klanten']) ? $data['totaal_klanten'] : '0'; ?></h3>
                    <p class="mb-0">Totaal Klanten</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h3><?php echo isset($data['statistieken']['gemiddelde_beoordeling']) ? number_format($data['statistieken']['gemiddelde_beoordeling'], 1) : 'N/A'; ?></h3>
                    <p class="mb-0">Gemiddelde Beoordeling</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Snelle Acties -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tachometer-alt"></i> Snelle Acties</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/instructeurs/planning" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-calendar"></i><br>
                                Mijn Planning
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/instructeurs/beschikbaarheid" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-calendar-check"></i><br>
                                Beschikbaarheid
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/instructeurs/klanten" class="btn btn-info btn-lg btn-block">
                                <i class="fas fa-users"></i><br>
                                Mijn Klanten
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/instructeurs/profiel" class="btn btn-warning btn-lg btn-block">
                                <i class="fas fa-user-cog"></i><br>
                                Mijn Profiel
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/auth/logout" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-sign-out-alt"></i><br>
                                Uitloggen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}

.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0 !important;
}

.reservation-item {
    border-radius: 5px;
    background: #f8f9fa;
}

.btn-lg {
    padding: 15px;
    font-size: 14px;
}

.btn-lg i {
    font-size: 24px;
    margin-bottom: 5px;
}
</style>

<?php include_once '../app/views/includes/footer.php'; ?>