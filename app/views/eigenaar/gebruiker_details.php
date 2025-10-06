<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container-fluid dashboard-dark py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-light"><?php echo $data['title']; ?></h1>
                <div>
                    <a href="<?php echo URLROOT; ?>/eigenaar/gebruikers" class="btn btn-outline-secondary btn-dark-theme">
                        <i class="fas fa-arrow-left"></i> Terug naar Gebruikers
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Gebruiker Informatie -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="card-title mb-0 text-light">
                                <i class="fas fa-user text-primary me-2"></i>Gebruiker Informatie
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            </div>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td><?php echo $data['gebruiker']->id; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Naam:</strong></td>
                                    <td><?php echo htmlspecialchars($data['gebruiker']->voornaam . ' ' . $data['gebruiker']->achternaam); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?php echo htmlspecialchars($data['gebruiker']->email); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Rol:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $data['gebruiker']->role == 'eigenaar' ? 'danger' : 
                                                ($data['gebruiker']->role == 'instructeur' ? 'warning' : 'primary'); 
                                        ?>">
                                            <?php echo ucfirst($data['gebruiker']->role); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo $data['gebruiker']->is_active ? 'success' : 'secondary'; ?>">
                                            <?php echo $data['gebruiker']->is_active ? 'Actief' : 'Inactief'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Aangemeld:</strong></td>
                                    <td>
                                        <?php 
                                        if(isset($data['gebruiker']->aangemaakt_op) && $data['gebruiker']->aangemaakt_op) {
                                            echo date('d-m-Y H:i', strtotime($data['gebruiker']->aangemaakt_op));
                                        } else {
                                            echo 'Datum onbekend';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Laatste login:</strong></td>
                                    <td>
                                        <?php 
                                        if(isset($data['gebruiker']->laatste_login) && $data['gebruiker']->laatste_login) {
                                            echo date('d-m-Y H:i', strtotime($data['gebruiker']->laatste_login));
                                        } else {
                                            echo 'Nog niet ingelogd';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>

                            <!-- Acties -->
                            <div class="d-grid gap-2 mt-3">
                                <?php if($data['gebruiker']->role != 'eigenaar'): ?>
                                    <button type="button" class="btn btn-warning" 
                                            data-bs-toggle="modal" data-bs-target="#wijzigRolModal">
                                        <i class="fas fa-user-edit"></i> Rol Wijzigen
                                    </button>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-outline-info" 
                                        data-bs-toggle="modal" data-bs-target="#emailModal">
                                    <i class="fas fa-envelope"></i> Stuur Email
                                </button>
                                
                                <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/toggle_user_status/<?php echo $data['gebruiker']->id; ?>" 
                                      style="display: inline;" onsubmit="return confirm('Weet je zeker dat je de status wilt wijzigen?')">
                                    <button type="submit" class="btn btn-outline-<?php echo $data['gebruiker']->is_active ? 'danger' : 'success'; ?>">
                                        <i class="fas fa-<?php echo $data['gebruiker']->is_active ? 'user-slash' : 'user-check'; ?>"></i>
                                        <?php echo $data['gebruiker']->is_active ? 'Deactiveren' : 'Activeren'; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Persoonlijke Informatie -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="card-title mb-0 text-light">
                                <i class="fas fa-address-card text-info me-2"></i>Persoonlijke Informatie
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if($data['persoon']): ?>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Telefoon:</strong></td>
                                        <td><?php echo htmlspecialchars($data['persoon']->telefoon ?: 'Niet opgegeven'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Geboortedatum:</strong></td>
                                        <td>
                                            <?php 
                                            if(isset($data['persoon']->geboortedatum) && $data['persoon']->geboortedatum) {
                                                echo date('d-m-Y', strtotime($data['persoon']->geboortedatum));
                                                $leeftijd = date('Y') - date('Y', strtotime($data['persoon']->geboortedatum));
                                                echo " ({$leeftijd} jaar)";
                                            } else {
                                                echo 'Niet opgegeven';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Adres:</strong></td>
                                        <td><?php echo htmlspecialchars($data['persoon']->adres ?: 'Niet opgegeven'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Postcode:</strong></td>
                                        <td><?php echo htmlspecialchars($data['persoon']->postcode ?: 'Niet opgegeven'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Plaats:</strong></td>
                                        <td><?php echo htmlspecialchars($data['persoon']->plaats ?: 'Niet opgegeven'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gewicht:</strong></td>
                                        <td><?php echo $data['persoon']->gewicht ? $data['persoon']->gewicht . ' kg' : 'Niet opgegeven'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lengte:</strong></td>
                                        <td><?php echo $data['persoon']->lengte ? $data['persoon']->lengte . ' cm' : 'Niet opgegeven'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ervaring:</strong></td>
                                        <td>
                                            <?php 
                                            $ervaringen = ['beginner' => 'Beginner', 'gevorderd' => 'Gevorderd', 'expert' => 'Expert'];
                                            echo $ervaringen[$data['persoon']->ervaring_niveau] ?? 'Niet opgegeven';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Geen persoonlijke informatie beschikbaar.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reserveringen -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-lg card-dark">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="card-title mb-0 text-light">
                                <i class="fas fa-calendar-check text-success me-2"></i>Reserveringen 
                                <span class="badge bg-primary"><?php echo count($data['reserveringen']); ?></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($data['reserveringen'])): ?>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <?php foreach($data['reserveringen'] as $reservering): ?>
                                        <div class="border-bottom py-2 mb-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($reservering->lespakket_naam); ?></h6>
                                                    <small class="text-muted">
                                                        <?php 
                                                        if(isset($reservering->gewenste_datum) && $reservering->gewenste_datum) {
                                                            echo date('d-m-Y', strtotime($reservering->gewenste_datum));
                                                        } else {
                                                            echo 'Datum onbekend';
                                                        }
                                                        ?>
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        €<?php echo number_format($reservering->lespakket_prijs, 2); ?>
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-<?php 
                                                        echo $reservering->status == 'bevestigd' ? 'success' : 
                                                            ($reservering->status == 'wachtend' ? 'warning' : 'secondary'); 
                                                    ?>">
                                                        <?php echo ucfirst($reservering->status); ?>
                                                    </span>
                                                    <br>
                                                    <span class="badge bg-<?php 
                                                        echo $reservering->betaal_status == 'betaald' ? 'success' : 
                                                            ($reservering->betaal_status == 'wachtend' ? 'warning' : 'danger'); 
                                                    ?>">
                                                        <?php echo ucfirst($reservering->betaal_status); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Geen reserveringen gevonden.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wijzig Rol Modal -->
<div class="modal fade" id="wijzigRolModal" tabindex="-1" aria-labelledby="wijzigRolModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wijzigRolModalLabel">Gebruikersrol Wijzigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/wijzig_rol/<?php echo $data['gebruiker']->id; ?>">
                <div class="modal-body">
                    <p>Weet je zeker dat je de rol wilt wijzigen voor gebruiker: 
                       <strong><?php echo htmlspecialchars($data['gebruiker']->voornaam . ' ' . $data['gebruiker']->achternaam); ?></strong>?</p>
                    
                    <div class="mb-3">
                        <label for="nieuwe_rol" class="form-label">Nieuwe Rol</label>
                        <select name="nieuwe_rol" id="nieuwe_rol" class="form-select" required>
                            <option value="">Selecteer een rol...</option>
                            <option value="klant" <?php echo ($data['gebruiker']->role == 'klant') ? 'disabled' : ''; ?>>Klant</option>
                            <option value="instructeur" <?php echo ($data['gebruiker']->role == 'instructeur') ? 'disabled' : ''; ?>>Instructeur</option>
                            <option value="eigenaar" <?php echo ($data['gebruiker']->role == 'eigenaar') ? 'disabled' : ''; ?>>Eigenaar</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Let op:</strong> De gebruiker ontvangt een email notificatie over deze wijziging.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-warning">Rol Wijzigen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Email Versturen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo URLROOT; ?>/eigenaar/stuur_email/<?php echo $data['gebruiker']->id; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email_onderwerp" class="form-label">Onderwerp</label>
                        <input type="text" name="onderwerp" id="email_onderwerp" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email_bericht" class="form-label">Bericht</label>
                        <textarea name="bericht" id="email_bericht" rows="6" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Versturen
                    </button>
                </div>
            </form>
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

.card-dark .table-borderless th,
.card-dark .table-borderless td {
    color: #fff;
}
</style>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>