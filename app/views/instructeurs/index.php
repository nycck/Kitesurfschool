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
        <!-- Vandaag's Reserveringen -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-calendar-day"></i> Vandaag's Lessen</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['today_reservations'])): ?>
                        <p class="text-muted">Geen lessen vandaag</p>
                    <?php else: ?>
                        <?php foreach ($data['today_reservations'] as $reservation): ?>
                            <div class="reservation-item mb-2 p-2 border-left border-primary">
                                <strong><?php echo $reservation->tijd; ?></strong> - 
                                <?php echo $reservation->klant_naam; ?>
                                <br>
                                <small class="text-muted"><?php echo $reservation->pakket_naam; ?> | <?php echo $reservation->locatie_naam; ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Komende Reserveringen -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-calendar-alt"></i> Komende Lessen</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($data['upcoming_reservations'])): ?>
                        <p class="text-muted">Geen komende lessen</p>
                    <?php else: ?>
                        <?php foreach (array_slice($data['upcoming_reservations'], 0, 5) as $reservation): ?>
                            <div class="reservation-item mb-2 p-2 border-left border-info">
                                <strong><?php echo date('d-m-Y', strtotime($reservation->datum)); ?></strong> om <?php echo $reservation->tijd; ?>
                                <br>
                                <?php echo $reservation->klant_naam; ?> - <?php echo $reservation->pakket_naam; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                            <a href="<?php echo URLROOT; ?>/reserveringen" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-list"></i><br>
                                Alle Reserveringen
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/reserveringen/beschikbaarheid" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-calendar-check"></i><br>
                                Beschikbaarheid
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo URLROOT; ?>/klant/profiel" class="btn btn-info btn-lg btn-block">
                                <i class="fas fa-user"></i><br>
                                Mijn Profiel
                            </a>
                        </div>
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