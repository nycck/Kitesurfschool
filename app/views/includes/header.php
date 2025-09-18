<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Kitesurfschool Windkracht-12' ?></title>
    <meta name="description" content="<?= $data['description'] ?? 'Leer kitesurfen bij Kitesurfschool Windkracht-12. Professionele instructeurs, complete uitrusting en veilige lessen aan de Nederlandse kust.' ?>">
    <meta name="keywords" content="<?= $data['keywords'] ?? 'kitesurfen, kitesurfles, windkracht-12, kitesurf school, nederlandse kust, watersport' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= URLROOT ?>/img/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= URLROOT ?>">
                <i class="fas fa-wind me-2"></i>Windkracht-12
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>"><i class="fas fa-home me-1"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>/homepages/about"><i class="fas fa-info-circle me-1"></i>Over Ons</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>/homepages/pakketten"><i class="fas fa-list me-1"></i>Lespakketten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>/homepages/locaties"><i class="fas fa-map-marker-alt me-1"></i>Locaties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>/homepages/contact"><i class="fas fa-envelope me-1"></i>Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?= $_SESSION['user_email'] ?? 'Gebruiker' ?>
                                <span class="badge bg-secondary ms-1"><?= ucfirst($_SESSION['user_role'] ?? 'klant') ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (hasRole('eigenaar')): ?>
                                    <li><a class="dropdown-item" href="<?= URLROOT ?>/eigenaar/dashboard"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                <?php elseif (hasRole('instructeur')): ?>
                                    <li><a class="dropdown-item" href="<?= URLROOT ?>/instructeur/dashboard"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?= URLROOT ?>/klant/dashboard"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= URLROOT ?>/auth/changePassword"><i class="fas fa-key me-1"></i>Wachtwoord wijzigen</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= URLROOT ?>/auth/logout"><i class="fas fa-sign-out-alt me-1"></i>Uitloggen</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/auth/login"><i class="fas fa-sign-in-alt me-1"></i>Inloggen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/auth/register"><i class="fas fa-user-plus me-1"></i>Registreren</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-5 mt-3"><?php flash('message'); ?>