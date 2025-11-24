<?php

class Instructeurs extends BaseController {
    
    private $userModel;
    private $persoonModel;
    private $reserveringModel;
    private $lespakketModel;
    private $locatieModel;
    
    public function __construct() {
        // Check if user is logged in and is an instructor or owner
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
        
        $user = $this->model('User')->getUserById($_SESSION['user_id']);
        if (!$user || !in_array($user->role, ['instructeur', 'eigenaar'])) {
            flash('error_message', 'Je hebt geen toegang tot deze functionaliteit.', 'alert alert-danger');
            redirect('dashboard');
        }
        
        $this->userModel = $this->model('User');
        $this->persoonModel = $this->model('Persoon');
        $this->reserveringModel = $this->model('Reservering');
        $this->lespakketModel = $this->model('Lespakket');
        $this->locatieModel = $this->model('Locatie');
    }

    public function index() {
        // Dashboard voor instructeurs
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        $data = [
            'title' => 'Instructeur Dashboard',
            'aankomende_lessen' => $this->reserveringModel->getAankomendeLessenByInstructeur($instructeurPersoon->id),
            'vandaag_lessen' => $this->reserveringModel->getLessenVandaagByInstructeur($instructeurPersoon->id),
            'totaal_klanten' => $this->reserveringModel->getTotaalKlantenByInstructeur($instructeurPersoon->id),
            'statistieken' => $this->getInstructeurStatistieken($instructeurPersoon->id)
        ];

        $this->view('instructeurs/index', $data);
    }

    public function planning() {
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        $viewType = isset($_GET['view']) ? $_GET['view'] : 'week';
        $datum = isset($_GET['datum']) ? $_GET['datum'] : date('Y-m-d');
        
        $data = [
            'title' => 'Lessenplanning',
            'viewType' => $viewType,
            'datum' => $datum,
            'lessen' => $this->getLessenForView($instructeurPersoon->id, $viewType, $datum)
        ];

        $this->view('instructeurs/planning', $data);
    }

    public function profiel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $data = [
                'user_id' => $_SESSION['user_id'],
                'voornaam' => trim($_POST['voornaam']),
                'achternaam' => trim($_POST['achternaam']),
                'adres' => trim($_POST['adres']),
                'postcode' => trim($_POST['postcode']),
                'woonplaats' => trim($_POST['woonplaats']),
                'geboortedatum' => trim($_POST['geboortedatum']),
                'telefoon' => trim($_POST['telefoon']),
                'bsn' => trim($_POST['bsn'])
            ];
            
            $errors = [];
            
            // Validatie
            if (empty($data['voornaam'])) {
                $errors[] = 'Voornaam is verplicht';
            }
            if (empty($data['achternaam'])) {
                $errors[] = 'Achternaam is verplicht';
            }
            if (empty($data['telefoon'])) {
                $errors[] = 'Telefoon is verplicht';
            }
            if (!empty($data['bsn']) && strlen($data['bsn']) !== 9) {
                $errors[] = 'BSN moet 9 cijfers zijn';
            }
            if (!empty($data['geboortedatum']) && !strtotime($data['geboortedatum'])) {
                $errors[] = 'Ongeldige geboortedatum';
            }
            
            if (empty($errors)) {
                if ($this->persoonModel->savePersoon($data)) {
                    flash('success_message', 'Profiel succesvol bijgewerkt!', 'alert alert-success');
                    redirect('instructeurs/profiel');
                } else {
                    $errors[] = 'Er is een fout opgetreden bij het opslaan';
                }
            }
            
            if (!empty($errors)) {
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                $persoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
                
                $viewData = [
                    'title' => 'Mijn Profiel',
                    'user' => $user,
                    'persoon' => $persoon,
                    'errors' => $errors
                ];
                
                $this->view('instructeurs/profiel', $viewData);
            }
        } else {
            // Display form
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            $persoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            
            $data = [
                'title' => 'Mijn Profiel',
                'user' => $user,
                'persoon' => $persoon
            ];
            
            $this->view('instructeurs/profiel', $data);
        }
    }

    public function klanten() {
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        $data = [
            'title' => 'Mijn Klanten',
            'klanten' => $this->persoonModel->getKlantenMetStatistieken()
        ];

        $this->view('instructeurs/klanten', $data);
    }

    public function nieuwe_klant() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $voornaam = trim($_POST['voornaam']);
            $achternaam = trim($_POST['achternaam']);
            $telefoon = trim($_POST['telefoon']);
            $geboortedatum = trim($_POST['geboortedatum']);
            $adres = trim($_POST['adres']);
            $postcode = trim($_POST['postcode']);
            $woonplaats = trim($_POST['woonplaats']);
            
            $errors = [];
            
            // Validatie
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Geldig email adres is verplicht';
            } elseif ($this->userModel->emailExists($email)) {
                $errors[] = 'Email adres is al geregistreerd';
            }
            
            if (empty($voornaam) || empty($achternaam)) {
                $errors[] = 'Voor- en achternaam zijn verplicht';
            }
            
            if (empty($telefoon)) {
                $errors[] = 'Telefoon is verplicht';
            }
            
            if (empty($errors)) {
                // Generate activation token
                $activationToken = bin2hex(random_bytes(32));
                
                // Maak gebruiker aan met activation token (niet direct actief)
                $userId = $this->userModel->register($email, $activationToken);
                
                if ($userId) {
                    // Voeg persoonsgegevens toe
                    $persoonData = [
                        'user_id' => $userId,
                        'voornaam' => $voornaam,
                        'achternaam' => $achternaam,
                        'telefoon' => $telefoon,
                        'geboortedatum' => !empty($geboortedatum) ? $geboortedatum : null,
                        'adres' => !empty($adres) ? $adres : null,
                        'postcode' => !empty($postcode) ? $postcode : null,
                        'woonplaats' => !empty($woonplaats) ? $woonplaats : null
                    ];
                    
                    if ($this->persoonModel->savePersoon($persoonData)) {
                        // Stuur activatie email
                        $emailService = new EmailService();
                        $emailService->sendActivationEmail($email, $activationToken);
                        
                        flash('success_message', "Klant {$voornaam} {$achternaam} is succesvol toegevoegd! Een activatielink is naar {$email} verstuurd.", 'alert alert-success');
                    } else {
                        flash('error_message', 'Fout bij het opslaan van persoonsgegevens.', 'alert alert-danger');
                    }
                } else {
                    flash('error_message', 'Fout bij het aanmaken van gebruikersaccount.', 'alert alert-danger');
                }
            } else {
                flash('error_message', implode('<br>', $errors), 'alert alert-danger');
            }
        }
        
        redirect('instructeurs/klanten');
    }

    public function klant_details($userId) {
        $klant = $this->userModel->getUserById($userId);
        $persoon = $this->persoonModel->getPersoonByUserId($userId);
        $reserveringen = $this->reserveringModel->getReserveringenByUserId($userId);
        
        if (!$klant) {
            echo '<div class="alert alert-danger">Klant niet gevonden.</div>';
            return;
        }
        
        // Render details template
        $html = "
        <div class='row'>
            <div class='col-md-6'>
                <h6><i class='fas fa-user me-2'></i>Persoonlijke Gegevens</h6>
                <table class='table table-sm'>
                    <tr><td><strong>Naam:</strong></td><td>" . htmlspecialchars($persoon->voornaam . ' ' . $persoon->achternaam) . "</td></tr>
                    <tr><td><strong>Email:</strong></td><td>" . htmlspecialchars($klant->email) . "</td></tr>
                    <tr><td><strong>Telefoon:</strong></td><td>" . htmlspecialchars($persoon->telefoon ?? 'Niet opgegeven') . "</td></tr>
                    <tr><td><strong>Adres:</strong></td><td>" . htmlspecialchars($persoon->adres ?? 'Niet opgegeven') . "</td></tr>
                    <tr><td><strong>Woonplaats:</strong></td><td>" . htmlspecialchars($persoon->woonplaats ?? 'Niet opgegeven') . "</td></tr>
                    <tr><td><strong>Geboortedatum:</strong></td><td>" . ($persoon->geboortedatum ? date('d-m-Y', strtotime($persoon->geboortedatum)) : 'Niet opgegeven') . "</td></tr>
                </table>
            </div>
            <div class='col-md-6'>
                <h6><i class='fas fa-calendar me-2'></i>Reservering Geschiedenis</h6>";
        
        if (empty($reserveringen)) {
            $html .= "<p class='text-muted'>Nog geen reserveringen.</p>";
        } else {
            $html .= "<div class='list-group list-group-flush'>";
            foreach (array_slice($reserveringen, 0, 5) as $reservering) {
                $statusClass = $reservering->status == 'bevestigd' ? 'success' : 
                              ($reservering->status == 'geannuleerd' ? 'danger' : 'warning');
                $html .= "
                <div class='list-group-item'>
                    <div class='d-flex justify-content-between'>
                        <strong>" . date('d-m-Y', strtotime($reservering->gewenste_datum)) . "</strong>
                        <span class='badge bg-{$statusClass}'>" . ucfirst($reservering->status) . "</span>
                    </div>
                    <small class='text-muted'>" . htmlspecialchars($reservering->lespakket_naam) . " - " . htmlspecialchars($reservering->locatie_naam) . "</small>
                </div>";
            }
            $html .= "</div>";
        }
        
        $html .= "
            </div>
        </div>";
        
        echo $html;
    }

    public function bewerk_klant($userId) {
        $klant = $this->userModel->getUserById($userId);
        $persoon = $this->persoonModel->getPersoonByUserId($userId);
        
        if (!$klant) {
            echo '<div class="alert alert-danger">Klant niet gevonden.</div>';
            return;
        }
        
        // Render edit form
        echo "
        <form method='POST' action='" . URLROOT . "/instructeurs/update_klant/{$userId}'>
            <div class='row'>
                <div class='col-md-6 mb-3'>
                    <label for='edit_voornaam' class='form-label'>Voornaam *</label>
                    <input type='text' class='form-control' id='edit_voornaam' name='voornaam' value='" . htmlspecialchars($persoon->voornaam) . "' required>
                </div>
                <div class='col-md-6 mb-3'>
                    <label for='edit_achternaam' class='form-label'>Achternaam *</label>
                    <input type='text' class='form-control' id='edit_achternaam' name='achternaam' value='" . htmlspecialchars($persoon->achternaam) . "' required>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6 mb-3'>
                    <label for='edit_telefoon' class='form-label'>Telefoon *</label>
                    <input type='tel' class='form-control' id='edit_telefoon' name='telefoon' value='" . htmlspecialchars($persoon->telefoon ?? '') . "' required>
                </div>
                <div class='col-md-6 mb-3'>
                    <label for='edit_geboortedatum' class='form-label'>Geboortedatum</label>
                    <input type='date' class='form-control' id='edit_geboortedatum' name='geboortedatum' value='" . htmlspecialchars($persoon->geboortedatum ?? '') . "'>
                </div>
            </div>
            <div class='mb-3'>
                <label for='edit_adres' class='form-label'>Adres</label>
                <input type='text' class='form-control' id='edit_adres' name='adres' value='" . htmlspecialchars($persoon->adres ?? '') . "'>
            </div>
            <div class='row'>
                <div class='col-md-4 mb-3'>
                    <label for='edit_postcode' class='form-label'>Postcode</label>
                    <input type='text' class='form-control' id='edit_postcode' name='postcode' value='" . htmlspecialchars($persoon->postcode ?? '') . "'>
                </div>
                <div class='col-md-8 mb-3'>
                    <label for='edit_woonplaats' class='form-label'>Woonplaats</label>
                    <input type='text' class='form-control' id='edit_woonplaats' name='woonplaats' value='" . htmlspecialchars($persoon->woonplaats ?? '') . "'>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuleren</button>
                <button type='submit' class='btn btn-warning'>
                    <i class='fas fa-save me-1'></i>Wijzigingen Opslaan
                </button>
            </div>
        </form>";
    }

    public function update_klant($userId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $persoonData = [
                'user_id' => $userId,
                'voornaam' => trim($_POST['voornaam']),
                'achternaam' => trim($_POST['achternaam']),
                'telefoon' => trim($_POST['telefoon']),
                'geboortedatum' => !empty($_POST['geboortedatum']) ? $_POST['geboortedatum'] : null,
                'adres' => !empty($_POST['adres']) ? $_POST['adres'] : null,
                'postcode' => !empty($_POST['postcode']) ? $_POST['postcode'] : null,
                'woonplaats' => !empty($_POST['woonplaats']) ? $_POST['woonplaats'] : null
            ];
            
            if ($this->persoonModel->savePersoon($persoonData)) {
                flash('success_message', 'Klantgegevens succesvol bijgewerkt!', 'alert alert-success');
            } else {
                flash('error_message', 'Fout bij het bijwerken van klantgegevens.', 'alert alert-danger');
            }
        }
        
        redirect('instructeurs/klanten');
    }

    public function verwijder_klant($userId) {
        $klant = $this->userModel->getUserById($userId);
        
        if ($klant) {
            $naam = $this->persoonModel->getPersoonByUserId($userId);
            $volledigeNaam = $naam ? $naam->voornaam . ' ' . $naam->achternaam : $klant->email;
            
            // Verwijder persoon en gebruiker (CASCADE zorgt voor reserveringen)
            if ($this->persoonModel->deletePersoon($userId) && $this->userModel->deleteUser($userId)) {
                flash('success_message', "Klant {$volledigeNaam} is succesvol verwijderd.", 'alert alert-success');
            } else {
                flash('error_message', 'Fout bij het verwijderen van de klant.', 'alert alert-danger');
            }
        } else {
            flash('error_message', 'Klant niet gevonden.', 'alert alert-danger');
        }
        
        redirect('instructeurs/klanten');
    }

    public function snelle_annulering($reservering_id) {
        // Get template from POST data
        $template = $_POST['template'] ?? null;
        
        if (!$template) {
            flash('error_message', 'Geen annuleringsreden opgegeven.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }
        
        $reservering = $this->reserveringModel->getReserveringById($reservering_id);
        
        if (!$reservering) {
            flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }

        // Check if this instructor can cancel this lesson
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        if ($reservering->instructeur_id != $instructeurPersoon->id) {
            flash('error_message', 'Je kunt alleen je eigen lessen annuleren.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }

        $redenen = [
            'ziekte' => 'Les geannuleerd vanwege ziekte van de instructeur',
            'weer' => 'Les geannuleerd vanwege gevaarlijke weersomstandigheden (windkracht > 10)'
        ];
        
        $reden = $redenen[$template] ?? 'Les geannuleerd';

        if ($this->reserveringModel->updateReserveringStatus($reservering_id, 'geannuleerd', $reden)) {
            // Send cancellation email with preset template
            $klant = $this->userModel->getUserById($reservering->user_id);
            $this->sendLesAnnuleringEmail($klant, $reservering, $reden, $template);
            
            $template_name = $template == 'ziekte' ? 'ziekte van instructeur' : 'slechte weersomstandigheden';
            flash('success_message', "Les succesvol geannuleerd ({$template_name}). De klant is per email geïnformeerd.", 'alert alert-success');
        } else {
            flash('error_message', 'Er is iets misgegaan bij het annuleren van de les.', 'alert alert-danger');
        }
        
        redirect('instructeurs/planning');
    }



    public function les_details($reservering_id) {
        $reservering = $this->reserveringModel->getReserveringById($reservering_id);
        
        if (!$reservering) {
            flash('error_message', 'Les niet gevonden.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }

        // Check if this instructor is assigned to this lesson
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        if ($reservering->instructeur_id != $instructeurPersoon->id) {
            flash('error_message', 'Je hebt geen toegang tot deze les.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }

        $data = [
            'title' => 'Les Details',
            'reservering' => $reservering
        ];

        $this->view('instructeurs/les_details', $data);
    }

    public function les_afronden($reservering_id) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('instructeurs/planning');
        }

        $reservering = $this->reserveringModel->getReserveringById($reservering_id);
        
        if (!$reservering) {
            flash('error_message', 'Les niet gevonden.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }

        // Check if this instructor can complete this lesson
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        if ($reservering->instructeur_id != $instructeurPersoon->id) {
            flash('error_message', 'Je kunt alleen je eigen lessen afronden.', 'alert alert-danger');
            redirect('instructeurs/planning');
        }

        // Get form data
        $evaluatie = trim($_POST['evaluatie'] ?? '');
        $voortgang = trim($_POST['voortgang'] ?? '');
        $aanbevelingen = trim($_POST['aanbevelingen'] ?? '');
        $instructeur_opmerking = trim($_POST['instructeur_opmerking'] ?? '');

        // Update reservation with evaluation data
        $data = [
            'id' => $reservering_id,
            'status' => 'afgerond',
            'evaluatie' => $evaluatie,
            'voortgang' => $voortgang,
            'aanbevelingen' => $aanbevelingen,
            'instructeur_opmerking' => $instructeur_opmerking
        ];

        if ($this->reserveringModel->updateLesEvaluatie($data)) {
            // Send email to client
            $klant = $this->userModel->getUserById($reservering->user_id);
            
            // Get persoon info for email
            $persoon = $this->persoonModel->getPersoonById($reservering->persoon_id);
            if ($persoon) {
                $klant->voornaam = $persoon->voornaam;
            }
            
            $afronding = [
                'evaluatie' => $evaluatie,
                'voortgang' => $voortgang,
                'aanbevelingen' => $aanbevelingen
            ];
            
            $this->sendLesAfgerondEmail($klant, $reservering, $afronding);
            
            flash('success_message', 'Les succesvol afgerond! De klant is per email geïnformeerd met de evaluatie.', 'alert alert-success');
        } else {
            flash('error_message', 'Er is iets misgegaan bij het afronden van de les.', 'alert alert-danger');
        }

        redirect('instructeurs/les_details/' . $reservering_id);
    }

    public function bevestigen($reservering_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bevestigde_datum = trim($_POST['bevestigde_datum']);
            $bevestigde_tijd = trim($_POST['bevestigde_tijd']);
            $opmerking = trim($_POST['opmerking']);

            $reservering = $this->reserveringModel->getReserveringById($reservering_id);
            
            if (!$reservering) {
                flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
                redirect('instructeurs/planning');
            }

            // Check if this instructor can confirm this lesson
            $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            
            $bevestigingData = [
                'bevestigde_datum' => $bevestigde_datum,
                'bevestigde_tijd' => $bevestigde_tijd,
                'instructeur_id' => $instructeurPersoon->id,
                'status' => 'bevestigd',
                'instructeur_opmerking' => $opmerking
            ];

            if ($this->reserveringModel->bevestigReservering($reservering_id, $bevestigingData)) {
                // Send confirmation email
                $klant = $this->userModel->getUserById($reservering->user_id);
                $this->sendLesBevestigingEmail($klant, $reservering, $bevestigingData);
                
                flash('success_message', 'Les succesvol bevestigd! De klant is per email geïnformeerd.', 'alert alert-success');
                redirect('instructeurs/planning');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het bevestigen van de les.', 'alert alert-danger');
                redirect('instructeurs/planning');
            }
        } else {
            redirect('instructeurs/planning');
        }
    }

    public function annuleren($reservering_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reden = trim($_POST['reden']);
            $email_template = trim($_POST['email_template']);

            if (empty($reden)) {
                flash('error_message', 'Geef een reden op voor annulering.', 'alert alert-danger');
                redirect('instructeurs/les_details/' . $reservering_id);
            }

            $reservering = $this->reserveringModel->getReserveringById($reservering_id);
            
            if (!$reservering) {
                flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
                redirect('instructeurs/planning');
            }

            // Check if this instructor can cancel this lesson
            $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            if ($reservering->instructeur_id != $instructeurPersoon->id) {
                flash('error_message', 'Je kunt alleen je eigen lessen annuleren.', 'alert alert-danger');
                redirect('instructeurs/planning');
            }

            if ($this->reserveringModel->updateReserveringStatus($reservering_id, 'geannuleerd', $reden)) {
                // Send cancellation email based on template
                $klant = $this->userModel->getUserById($reservering->user_id);
                $this->sendLesAnnuleringEmail($klant, $reservering, $reden, $email_template);
                
                flash('success_message', 'Les succesvol geannuleerd. De klant is per email geïnformeerd.', 'alert alert-success');
                redirect('instructeurs/planning');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het annuleren van de les.', 'alert alert-danger');
                redirect('instructeurs/les_details/' . $reservering_id);
            }
        } else {
            redirect('instructeurs/planning');
        }
    }

    public function afronden($reservering_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $evaluatie = trim($_POST['evaluatie']);
            $voortgang = trim($_POST['voortgang']);
            $aanbevelingen = trim($_POST['aanbevelingen']);

            $reservering = $this->reserveringModel->getReserveringById($reservering_id);
            
            if (!$reservering) {
                flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
                redirect('instructeurs/planning');
            }

            // Check if this instructor can complete this lesson
            $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            if ($reservering->instructeur_id != $instructeurPersoon->id) {
                flash('error_message', 'Je kunt alleen je eigen lessen afronden.', 'alert alert-danger');
                redirect('instructeurs/planning');
            }

            $afronding_data = [
                'status' => 'afgerond',
                'evaluatie' => $evaluatie,
                'voortgang' => $voortgang,
                'aanbevelingen' => $aanbevelingen
            ];

            if ($this->reserveringModel->rondLesAf($reservering_id, $afronding_data)) {
                // Send completion email
                $klant = $this->userModel->getUserById($reservering->user_id);
                $this->sendLesAfgerondEmail($klant, $reservering, $afronding_data);
                
                flash('success_message', 'Les succesvol afgerond! Evaluatie is opgeslagen.', 'alert alert-success');
                redirect('instructeurs/planning');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het afronden van de les.', 'alert alert-danger');
                redirect('instructeurs/les_details/' . $reservering_id);
            }
        } else {
            redirect('instructeurs/planning');
        }
    }

    public function beschikbaarheid() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            
            $beschikbaarheidData = [
                'instructeur_id' => $instructeurPersoon->id,
                'datum_van' => trim($_POST['datum_van']),
                'datum_tot' => trim($_POST['datum_tot']),
                'tijd_van' => trim($_POST['tijd_van']),
                'tijd_tot' => trim($_POST['tijd_tot']),
                'locaties' => $_POST['locaties'] ?? [],
                'opmerking' => trim($_POST['opmerking'])
            ];

            if ($this->reserveringModel->updateInstructeurBeschikbaarheid($beschikbaarheidData)) {
                flash('success_message', 'Beschikbaarheid succesvol bijgewerkt!', 'alert alert-success');
                redirect('instructeurs/beschikbaarheid');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het bijwerken van je beschikbaarheid.', 'alert alert-danger');
            }
        }

        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        $data = [
            'title' => 'Beschikbaarheid Beheren',
            'locaties' => $this->locatieModel->getAllLocaties(),
            'huidige_beschikbaarheid' => $this->reserveringModel->getInstructeurBeschikbaarheid($instructeurPersoon->id)
        ];

        $this->view('instructeurs/beschikbaarheid', $data);
    }

    private function getInstructeurStatistieken($instructeur_id) {
        return [
            'totaal_lessen_gegeven' => $this->reserveringModel->getTotaalLessenGegeven($instructeur_id),
            'lessen_deze_maand' => $this->reserveringModel->getLessenDezeMaand($instructeur_id),
            'gemiddelde_beoordeling' => $this->reserveringModel->getGemiddeldeBeoordelingInstructeur($instructeur_id),
            'nieuwe_klanten_deze_maand' => $this->reserveringModel->getNieuweKlantenDezeMaand($instructeur_id)
        ];
    }

    public function nieuwe_les() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            
            $data = [
                'persoon_id' => trim($_POST['klant_id']),
                'instructeur_id' => $instructeurPersoon->id,
                'lespakket_id' => trim($_POST['lespakket_id']),
                'locatie_id' => trim($_POST['locatie_id']),
                'bevestigde_datum' => trim($_POST['bevestigde_datum']),
                'bevestigde_tijd' => trim($_POST['bevestigde_tijd']),
                'opmerkingen' => trim($_POST['opmerkingen']),
                'status' => 'bevestigd'
            ];
            
            // Validatie
            $errors = [];
            if (empty($data['persoon_id']) || empty($data['lespakket_id']) || empty($data['locatie_id'])) {
                $errors[] = 'Alle verplichte velden moeten ingevuld zijn';
            }
            
            if (empty($data['bevestigde_datum']) || empty($data['bevestigde_tijd'])) {
                $errors[] = 'Datum en tijd zijn verplicht';
            }
            
            if (empty($errors)) {
                // Maak reservering aan
                if ($this->reserveringModel->createReserveringByInstructeur($data)) {
                    // Stuur bevestigingsmail naar klant
                    $this->sendNieuweLesEmail($data);
                    
                    flash('success_message', 'Les succesvol toegevoegd!', 'alert alert-success');
                    redirect('instructeurs/planning');
                } else {
                    flash('error_message', 'Er is iets misgegaan bij het toevoegen van de les.', 'alert alert-danger');
                    redirect('instructeurs/planning');
                }
            } else {
                flash('error_message', implode('<br>', $errors), 'alert alert-danger');
                redirect('instructeurs/planning');
            }
        } else {
            redirect('instructeurs/planning');
        }
    }

    private function getLessenForView($instructeur_id, $view, $datum) {
        switch ($view) {
            case 'dag':
                return $this->reserveringModel->getLessenByDag($instructeur_id, $datum);
            case 'week':
                return $this->reserveringModel->getLessenByWeek($instructeur_id, $datum);
            case 'maand':
                return $this->reserveringModel->getLessenByMaand($instructeur_id, $datum);
            default:
                return $this->reserveringModel->getLessenByWeek($instructeur_id, $datum);
        }
    }

    private function sendNieuweLesEmail($data) {
        $emailService = new EmailService();
        
        // Haal klant gegevens op
        $persoon = $this->persoonModel->getPersoonById($data['persoon_id']);
        $user = $this->userModel->getUserById($persoon->user_id);
        
        // Haal les details op
        $lespakket = $this->lespakketModel->getLespakketById($data['lespakket_id']);
        $locatie = $this->locatieModel->getLocatieById($data['locatie_id']);
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        $subject = 'Nieuwe Kitesurfles Ingepland - Windkracht-12';
        
        $datumFormatted = date('l d F Y', strtotime($data['bevestigde_datum']));
        
        $body = "
        <h2>Beste {$persoon->voornaam},</h2>
        
        <p>Er is een nieuwe kitesurfles voor je ingepland!</p>
        
        <h3>Les Details:</h3>
        <ul>
            <li><strong>Datum:</strong> {$datumFormatted}</li>
            <li><strong>Tijd:</strong> {$data['bevestigde_tijd']}</li>
            <li><strong>Lespakket:</strong> {$lespakket->naam}</li>
            <li><strong>Locatie:</strong> {$locatie->naam}</li>
            <li><strong>Adres:</strong> {$locatie->adres}</li>
            <li><strong>Instructeur:</strong> {$instructeurPersoon->voornaam} {$instructeurPersoon->achternaam}</li>
        </ul>
        " . (!empty($data['opmerkingen']) ? "<p><strong>Opmerkingen:</strong><br>{$data['opmerkingen']}</p>" : "") . "
        
        <p>We kijken ernaar uit je te zien op het water!</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        // Gebruik de juiste EmailService methode
        $emailService->sendEmail($user->email, $subject, $body);
    }

    private function sendLesBevestigingEmail($klant, $reservering, $bevestiging) {
        $emailService = new EmailService();
        
        $subject = 'Bevestiging Kitesurfles - Windkracht-12';
        
        $body = "
        <h2>Beste {$klant->voornaam},</h2>
        
        <p>Goed nieuws! Je kitesurfles is bevestigd door je instructeur.</p>
        
        <h3>Les Details:</h3>
        <ul>
            <li><strong>Datum:</strong> " . date('l d F Y', strtotime($bevestiging['bevestigde_datum'])) . "</li>
            <li><strong>Tijd:</strong> {$bevestiging['bevestigde_tijd']}</li>
            <li><strong>Lespakket:</strong> {$reservering->lespakket_naam}</li>
            <li><strong>Locatie:</strong> {$reservering->locatie_naam}</li>
        </ul>
        
        " . (!empty($bevestiging['instructeur_opmerking']) ? "<p><strong>Opmerking van instructeur:</strong><br>{$bevestiging['instructeur_opmerking']}</p>" : "") . "
        
        <h3>Wat mee te nemen:</h3>
        <ul>
            <li>Zwemkleding</li>
            <li>Handdoek</li>
            <li>Zonnebrandcrème</li>
            <li>Eventueel wetsuit (beschikbaar ter plaatse)</li>
        </ul>
        
        <p>Tot ziens bij het water!</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $subject, $body);
    }

    private function sendLesAnnuleringEmail($klant, $reservering, $reden, $template) {
        $emailService = new EmailService();
        
        $subject = 'Annulering Kitesurfles - Windkracht-12';
        
        $templates = [
            'ziekte' => "
                <p>Helaas moet je kitesurfles worden geannuleerd vanwege ziekte van de instructeur.</p>
                <p>We bieden onze excuses aan voor het ongemak en zorgen ervoor dat je zo snel mogelijk een nieuwe datum krijgt aangeboden.</p>
            ",
            'weer' => "
                <p>Je kitesurfles moet helaas worden geannuleerd vanwege gevaarlijke weersomstandigheden.</p>
                <p><strong>Windkracht te hoog (> 10 Beaufort)</strong> - Veiligheid staat bij ons voorop!</p>
                <p>Bij dergelijke windsterkte is kitesurfen te gevaarlijk. We geven alleen les bij veilige wind- en weercondities.</p>
                <p>We nemen zo snel mogelijk contact met je op om een nieuwe datum in te plannen wanneer de weersomstandigheden gunstiger zijn.</p>
            ",
            'anders' => "
                <p>Je kitesurfles moet helaas worden geannuleerd.</p>
                <p><strong>Reden:</strong> {$reden}</p>
                <p>We zorgen ervoor dat je zo snel mogelijk een nieuwe datum krijgt aangeboden.</p>
            "
        ];
        
        $template_text = $templates[$template] ?? $templates['anders'];
        
        $body = "
        <h2>Beste {$klant->voornaam},</h2>
        
        {$template_text}
        
        <h3>Geannuleerde Les:</h3>
        <ul>
            <li><strong>Datum:</strong> " . date('l d F Y', strtotime($reservering->bevestigde_datum)) . "</li>
            <li><strong>Tijd:</strong> {$reservering->bevestigde_tijd}</li>
            <li><strong>Lespakket:</strong> {$reservering->lespakket_naam}</li>
            <li><strong>Locatie:</strong> {$reservering->locatie_naam}</li>
        </ul>
        
        <p>Je kunt een nieuwe datum kiezen via je account of door contact met ons op te nemen.</p>
        <p>Het lesgeld blijft behouden voor je nieuwe les.</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $subject, $body);
    }

    private function sendLesAfgerondEmail($klant, $reservering, $afronding) {
        $emailService = new EmailService();
        
        $subject = 'Evaluatie Kitesurfles - Windkracht-12';
        
        $body = "
        <h2>Beste {$klant->voornaam},</h2>
        
        <p>Bedankt voor je kitesurfles! Hieronder vind je de evaluatie van je instructeur.</p>
        
        <h3>Les Evaluatie:</h3>
        <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
            " . (!empty($afronding['evaluatie']) ? "<p><strong>Algemene evaluatie:</strong><br>{$afronding['evaluatie']}</p>" : "") . "
            " . (!empty($afronding['voortgang']) ? "<p><strong>Voortgang:</strong><br>{$afronding['voortgang']}</p>" : "") . "
            " . (!empty($afronding['aanbevelingen']) ? "<p><strong>Aanbevelingen:</strong><br>{$afronding['aanbevelingen']}</p>" : "") . "
        </div>
        
        <p>We hopen dat je een geweldige les hebt gehad! Overweeg je meer lessen? Bekijk onze pakketten op de website.</p>
        
        <p>Deel je ervaring gerust met anderen en volg ons op social media voor kitesurftips en updates.</p>
        
        <p>Tot de volgende keer op het water!</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $subject, $body);
    }
}