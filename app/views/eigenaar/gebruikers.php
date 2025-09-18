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

            <!-- Filter en Zoek Sectie -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo URLROOT; ?>/eigenaar/gebruikers" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter" class="form-label">Filter op Rol</label>
                            <select name="filter" id="filter" class="form-select">
                                <option value="alle" <?php echo ($data['filter'] == 'alle') ? 'selected' : ''; ?>>Alle Gebruikers</option>
                                <option value="klant" <?php echo ($data['filter'] == 'klant') ? 'selected' : ''; ?>>Klanten</option>
                                <option value="instructeur" <?php echo ($data['filter'] == 'instructeur') ? 'selected' : ''; ?>>Instructeurs</option>
                                <option value="eigenaar" <?php echo ($data['filter'] == 'eigenaar') ? 'selected' : ''; ?>>Eigenaren</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="zoek" class="form-label">Zoeken</label>
                            <input type="text" name="zoek" id="zoek" class="form-control" 
                                   placeholder="Zoek op naam of email..." 
                                   value="<?php echo htmlspecialchars($data['zoekterm']); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Zoeken
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Gebruikers Tabel -->
            <div class="card">
                <div class="card-body">
                    <?php if(!empty($data['gebruikers'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Naam</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Status</th>
                                        <th>Aangemeld op</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['gebruikers'] as $gebruiker): ?>
                                        <tr>
                                            <td><?php echo $gebruiker->id; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($gebruiker->voornaam . ' ' . $gebruiker->achternaam); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($gebruiker->email); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $gebruiker->role == 'eigenaar' ? 'danger' : 
                                                        ($gebruiker->role == 'instructeur' ? 'warning' : 'primary'); 
                                                ?>">
                                                    <?php echo ucfirst($gebruiker->role); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $gebruiker->is_active ? 'success' : 'secondary'; ?>">
                                                    <?php echo $gebruiker->is_active ? 'Actief' : 'Inactief'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($gebruiker->aangemaakt_op)); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo URLROOT; ?>/eigenaar/gebruiker_details/<?php echo $gebruiker->id; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Details bekijken">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if($gebruiker->role != 'eigenaar'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                data-bs-toggle="modal" data-bs-target="#wijzigRolModal" 
                                                                data-user-id="<?php echo $gebruiker->id; ?>" 
                                                                data-user-name="<?php echo htmlspecialchars($gebruiker->voornaam . ' ' . $gebruiker->achternaam); ?>"
                                                                data-current-role="<?php echo $gebruiker->role; ?>"
                                                                title="Rol wijzigen">
                                                            <i class="fas fa-user-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Geen gebruikers gevonden</h5>
                            <p class="text-muted">Probeer andere filterinstellingen of zoektermen.</p>
                        </div>
                    <?php endif; ?>
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
            <form id="wijzigRolForm" method="POST">
                <div class="modal-body">
                    <p>Weet je zeker dat je de rol wilt wijzigen voor gebruiker: <strong id="modalUserName"></strong>?</p>
                    
                    <div class="mb-3">
                        <label for="nieuwe_rol" class="form-label">Nieuwe Rol</label>
                        <select name="nieuwe_rol" id="nieuwe_rol" class="form-select" required>
                            <option value="">Selecteer een rol...</option>
                            <option value="klant">Klant</option>
                            <option value="instructeur">Instructeur</option>
                            <option value="eigenaar">Eigenaar</option>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wijzigRolModal = document.getElementById('wijzigRolModal');
    const wijzigRolForm = document.getElementById('wijzigRolForm');
    const modalUserName = document.getElementById('modalUserName');
    const nieuweRolSelect = document.getElementById('nieuwe_rol');
    
    wijzigRolModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        const currentRole = button.getAttribute('data-current-role');
        
        modalUserName.textContent = userName;
        wijzigRolForm.action = '<?php echo URLROOT; ?>/eigenaar/wijzig_rol/' + userId;
        
        // Reset en disable current role
        nieuweRolSelect.value = '';
        const options = nieuweRolSelect.querySelectorAll('option');
        options.forEach(option => {
            option.disabled = false;
            if (option.value === currentRole) {
                option.disabled = true;
            }
        });
    });
});
</script>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>