<?php

class Eigenaar extends BaseController {
    
    private $userModel;
    private $persoonModel;
    private $reserveringModel;
    private $lespakketModel;
    private $locatieModel;
    
    public function __construct() {
        // Check if user is logged in and is owner
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
        
        $user = $this->model('User')->getUserById($_SESSION['user_id']);
        if (!$user || $user->role !== 'eigenaar') {
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
        // Dashboard voor eigenaar
        $data = [
            'title' => 'Eigenaar Dashboard',
            'totaal_gebruikers' => $this->userModel->getTotaalGebruikers() ?? 0,
            'totaal_reserveringen' => $this->reserveringModel->getTotaalReserveringen() ?? 0,
            'omzet_deze_maand' => $this->reserveringModel->getOmzetDezeMaand() ?? 0,
            'actieve_instructeurs' => $this->userModel->getActieveInstructeurs() ?? 0,
            'recente_activiteit' => $this->getRecenteActiviteit(),
            'statistieken' => $this->getEigenaarStatistieken()
        ];

        $this->view('eigenaar/index', $data);
    }

    public function gebruikers() {
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'alle';
        $zoekterm = isset($_GET['zoek']) ? $_GET['zoek'] : '';
        
        $data = [
            'title' => 'Gebruikers Beheren',
            'gebruikers' => $this->userModel->getAlleGebruikers($filter, $zoekterm),
            'filter' => $filter,
            'zoekterm' => $zoekterm
        ];

        $this->view('eigenaar/gebruikers', $data);
    }

    public function gebruiker_details($id) {
        $gebruiker = $this->userModel->getUserById($id);
        
        if (!$gebruiker) {
            flash('error_message', 'Gebruiker niet gevonden.', 'alert alert-danger');
            redirect('eigenaar/gebruikers');
        }

        $persoon = $this->persoonModel->getPersoonByUserId($id);
        $reserveringen = $this->reserveringModel->getReserveringenByUserId($id);

        $data = [
            'title' => 'Gebruiker Details',
            'gebruiker' => $gebruiker,
            'persoon' => $persoon,
            'reserveringen' => $reserveringen
        ];

        $this->view('eigenaar/gebruiker_details', $data);
    }

    public function wijzig_rol($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nieuwe_rol = trim($_POST['nieuwe_rol']);
            
            if (!in_array($nieuwe_rol, ['klant', 'instructeur', 'eigenaar'])) {
                flash('error_message', 'Ongeldige rol.', 'alert alert-danger');
                redirect('eigenaar/gebruiker_details/' . $id);
            }

            $gebruiker = $this->userModel->getUserById($id);
            if (!$gebruiker) {
                flash('error_message', 'Gebruiker niet gevonden.', 'alert alert-danger');
                redirect('eigenaar/gebruikers');
            }

            if ($this->userModel->updateUserRole($id, $nieuwe_rol)) {
                flash('success_message', 'Gebruikersrol succesvol bijgewerkt.', 'alert alert-success');
                
                // Send email notification
                $this->sendRolWijzigingEmail($gebruiker, $nieuwe_rol);
                
                redirect('eigenaar/gebruiker_details/' . $id);
            } else {
                flash('error_message', 'Er is iets misgegaan bij het bijwerken van de rol.', 'alert alert-danger');
                redirect('eigenaar/gebruiker_details/' . $id);
            }
        } else {
            redirect('eigenaar/gebruikers');
        }
    }

    public function betalingen() {
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'alle';
        $maand = isset($_GET['maand']) ? $_GET['maand'] : date('Y-m');
        
        $data = [
            'title' => 'Betalingen Beheren',
            'betalingen' => $this->reserveringModel->getAlleBetalingen($filter, $maand),
            'filter' => $filter,
            'maand' => $maand,
            'totaal_omzet' => $this->reserveringModel->getTotaalOmzet($maand),
            'openstaand' => $this->reserveringModel->getOpenstaandeBetalingen()
        ];

        $this->view('eigenaar/betalingen', $data);
    }

    public function betaling_status($reservering_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nieuwe_status = trim($_POST['betaal_status']);
            $opmerking = trim($_POST['opmerking']);
            
            if (!in_array($nieuwe_status, ['wachtend', 'betaald', 'mislukt'])) {
                flash('error_message', 'Ongeldige betalingsstatus.', 'alert alert-danger');
                redirect('eigenaar/betalingen');
            }

            $reservering = $this->reserveringModel->getReserveringById($reservering_id);
            if (!$reservering) {
                flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
                redirect('eigenaar/betalingen');
            }

            if ($this->reserveringModel->updateBetalingStatus($reservering_id, $nieuwe_status, $opmerking)) {
                flash('success_message', 'Betalingsstatus succesvol bijgewerkt.', 'alert alert-success');
                
                // Send email notification if payment confirmed
                if ($nieuwe_status == 'betaald') {
                    $klant = $this->userModel->getUserById($reservering->user_id);
                    $this->sendBetalingBevestigingEmail($klant, $reservering);
                }
                
                redirect('eigenaar/betalingen');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het bijwerken van de betalingsstatus.', 'alert alert-danger');
                redirect('eigenaar/betalingen');
            }
        } else {
            redirect('eigenaar/betalingen');
        }
    }

    public function rapporten() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'omzet';
        $periode = isset($_GET['periode']) ? $_GET['periode'] : 'maand';
        $datum = isset($_GET['datum']) ? $_GET['datum'] : date('Y-m');
        
        $data = [
            'title' => 'Rapporten & Statistieken',
            'type' => $type,
            'periode' => $periode,
            'datum' => $datum,
            'rapport_data' => $this->getRapportData($type, $periode, $datum)
        ];

        $this->view('eigenaar/rapporten', $data);
    }

    public function instellingen() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $instellingen = [
                'bedrijfsnaam' => trim($_POST['bedrijfsnaam']),
                'email' => trim($_POST['email']),
                'telefoon' => trim($_POST['telefoon']),
                'adres' => trim($_POST['adres']),
                'btw_nummer' => trim($_POST['btw_nummer']),
                'bank_rekening' => trim($_POST['bank_rekening']),
                'email_automatisch' => isset($_POST['email_automatisch']) ? 1 : 0,
                'backup_automatisch' => isset($_POST['backup_automatisch']) ? 1 : 0
            ];

            if ($this->updateSysteemInstellingen($instellingen)) {
                flash('success_message', 'Instellingen succesvol bijgewerkt.', 'alert alert-success');
                redirect('eigenaar/instellingen');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het bijwerken van de instellingen.', 'alert alert-danger');
            }
        }

        $data = [
            'title' => 'Systeem Instellingen',
            'instellingen' => $this->getSysteemInstellingen()
        ];

        $this->view('eigenaar/instellingen', $data);
    }

    public function backup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $backup_type = trim($_POST['backup_type']);
            
            if ($this->createDatabaseBackup($backup_type)) {
                flash('success_message', 'Database backup succesvol aangemaakt.', 'alert alert-success');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het aanmaken van de backup.', 'alert alert-danger');
            }
            
            redirect('eigenaar/instellingen');
        } else {
            redirect('eigenaar/instellingen');
        }
    }

    public function logs() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'alle';
        $datum = isset($_GET['datum']) ? $_GET['datum'] : date('Y-m-d');
        
        $data = [
            'title' => 'Systeem Logs',
            'logs' => $this->getSystemLogs($type, $datum),
            'type' => $type,
            'datum' => $datum
        ];

        $this->view('eigenaar/logs', $data);
    }

    public function profiel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate and process form data
            $data = [
                'user_id' => $_SESSION['user_id'],
                'voornaam' => trim($_POST['voornaam']),
                'achternaam' => trim($_POST['achternaam']),
                'telefoon' => trim($_POST['telefoon']),
                'adres' => trim($_POST['adres']),
                'postcode' => trim($_POST['postcode']),
                'woonplaats' => trim($_POST['woonplaats']),
                'geboortedatum' => !empty($_POST['geboortedatum']) ? $_POST['geboortedatum'] : null,
                'bsn' => !empty($_POST['bsn']) ? trim($_POST['bsn']) : null
            ];

            $errors = [];
            
            // Validation
            if (empty($data['voornaam'])) {
                $errors[] = 'Voornaam is verplicht';
            }
            if (empty($data['achternaam'])) {
                $errors[] = 'Achternaam is verplicht';
            }
            if (!empty($data['geboortedatum']) && !strtotime($data['geboortedatum'])) {
                $errors[] = 'Ongeldige geboortedatum';
            }
            if (!empty($data['bsn']) && !preg_match('/^[0-9]{9}$/', $data['bsn'])) {
                $errors[] = 'BSN moet uit 9 cijfers bestaan';
            }
            
            if (empty($errors)) {
                if ($this->persoonModel->savePersoon($data)) {
                    flash('success_message', 'Profiel succesvol bijgewerkt!', 'alert alert-success');
                    redirect('eigenaar/profiel');
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
                $this->view('eigenaar/profiel', $viewData);
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
            
            $this->view('eigenaar/profiel', $data);
        }
    }

    public function instructeur_planning($instructeur_id = null) {
        // Get all instructors for dropdown
        $instructeurs = $this->userModel->getAlleGebruikers('instructeur');
        
        $data = [
            'title' => 'Instructeur Planning Overzicht',
            'instructeurs' => $instructeurs
        ];
        
        if ($instructeur_id) {
            // Get selected instructor's planning
            $instructeur = $this->userModel->getUserById($instructeur_id);
            $persoon = $this->persoonModel->getPersoonByUserId($instructeur_id);
            
            if (!$instructeur || $instructeur->role !== 'instructeur') {
                flash('error_message', 'Instructeur niet gevonden.', 'alert alert-danger');
                redirect('eigenaar/instructeur_planning');
            }
            
            $view = isset($_GET['view']) ? $_GET['view'] : 'week';
            $datum = isset($_GET['datum']) ? $_GET['datum'] : date('Y-m-d');
            
            $data['geselecteerde_instructeur'] = $instructeur;
            $data['instructeur_persoon'] = $persoon;
            $data['view'] = $view;
            $data['datum'] = $datum;
            $data['planning'] = $this->getPlanningData($instructeur_id, $view, $datum);
        }
        
        $this->view('eigenaar/instructeur_planning', $data);
    }
    
    private function getPlanningData($instructeur_id, $view, $datum) {
        $persoon = $this->persoonModel->getPersoonByUserId($instructeur_id);
        
        switch ($view) {
            case 'dag':
                return $this->reserveringModel->getLessenByDatum($persoon->id, $datum);
            case 'week':
                $startWeek = date('Y-m-d', strtotime('monday this week', strtotime($datum)));
                return $this->reserveringModel->getLessenByWeek($persoon->id, $startWeek);
            case 'maand':
                $startMaand = date('Y-m-01', strtotime($datum));
                return $this->reserveringModel->getLessenByMaand($persoon->id, $startMaand);
            default:
                return [];
        }
    }

    public function reserveringen() {
        $status = isset($_GET['status']) ? $_GET['status'] : 'alle';
        $periode = isset($_GET['periode']) ? $_GET['periode'] : 'alle';
        
        try {
            // Haal alle reserveringen op
            $alleReserveringen = $this->reserveringModel->getAllReserveringen($status === 'alle' ? null : $status);
            
            // Bereken statistieken uit de opgehaalde data
            $totaal = count($alleReserveringen);
            $bevestigd = count(array_filter($alleReserveringen, function($r) { return $r->status === 'bevestigd'; }));
            $wachtend = count(array_filter($alleReserveringen, function($r) { return $r->status === 'aangevraagd'; }));
            $geannuleerd = count(array_filter($alleReserveringen, function($r) { return $r->status === 'geannuleerd'; }));
            
            $data = [
                'title' => 'Reserveringen Beheren',
                'reserveringen' => $alleReserveringen,
                'status' => $status,
                'periode' => $periode,
                'statistieken' => [
                    'totaal' => $totaal,
                    'bevestigd' => $bevestigd,
                    'wachtend' => $wachtend,
                    'geannuleerd' => $geannuleerd
                ]
            ];

            $this->view('eigenaar/reserveringen', $data);
        } catch (Exception $e) {
            error_log("Eigenaar reserveringen error: " . $e->getMessage());
            
            $data = [
                'title' => 'Reserveringen Beheren',
                'reserveringen' => [],
                'status' => $status,
                'periode' => $periode,
                'statistieken' => [
                    'totaal' => 0,
                    'bevestigd' => 0,
                    'wachtend' => 0,
                    'geannuleerd' => 0
                ]
            ];
            
            flash('error_message', 'Er is een probleem opgetreden bij het laden van de reserveringen.', 'alert alert-danger');
            $this->view('eigenaar/reserveringen', $data);
        }
    }

    public function reservering_details($id) {
        $reservering = $this->reserveringModel->getReserveringById($id);
        
        if (!$reservering) {
            flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
            redirect('eigenaar/reserveringen');
        }

        $data = [
            'title' => 'Reservering Details',
            'reservering' => $reservering
        ];

        $this->view('eigenaar/reservering_details', $data);
    }

    public function reservering_status($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nieuwe_status = trim($_POST['status']);
            $opmerking = trim($_POST['opmerking']);
            
            if (!in_array($nieuwe_status, ['aangevraagd', 'bevestigd', 'geannuleerd', 'afgerond'])) {
                flash('error_message', 'Ongeldige status.', 'alert alert-danger');
                redirect('eigenaar/reserveringen');
            }

            $reservering = $this->reserveringModel->getReserveringById($id);
            if (!$reservering) {
                flash('error_message', 'Reservering niet gevonden.', 'alert alert-danger');
                redirect('eigenaar/reserveringen');
            }

            if ($this->reserveringModel->updateReserveringStatus($id, $nieuwe_status, $opmerking)) {
                flash('success_message', 'Reservering status succesvol bijgewerkt.', 'alert alert-success');
                
                // Send email notification to customer
                $klant = $this->userModel->getUserById($reservering->user_id);
                $this->sendStatusWijzigingEmail($klant, $reservering, $nieuwe_status);
                
                redirect('eigenaar/reserveringen');
            } else {
                flash('error_message', 'Er is iets misgegaan bij het bijwerken van de status.', 'alert alert-danger');
                redirect('eigenaar/reserveringen');
            }
        } else {
            redirect('eigenaar/reserveringen');
        }
    }

    private function getEigenaarStatistieken() {
        // Tijdelijke mock data totdat de models volledig geïmplementeerd zijn
        return [
            'nieuwe_gebruikers_deze_maand' => 5,
            'voltooide_lessen_deze_maand' => 12,
            'gemiddelde_beoordeling' => 4.5,
            'populairste_lespakket' => 'Beginnerscursus Kitesurfen',
            'populairste_locatie' => 'Zandvoort',
            'conversion_rate' => 85.5
        ];
    }

    private function getRecenteActiviteit() {
        // Tijdelijke mock data totdat de models volledig geïmplementeerd zijn
        return [
            'nieuwe_reserveringen' => [
                // Mock data voor nieuwe reserveringen
                (object)[
                    'aangemaakt_op' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'klant_naam' => 'Jan Jansen',
                    'lespakket_naam' => 'Beginnerscursus Kitesurfen'
                ],
                (object)[
                    'aangemaakt_op' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                    'klant_naam' => 'Marie de Vries',
                    'lespakket_naam' => 'Privéles'
                ]
            ],
            'recente_betalingen' => [
                // Mock data voor recente betalingen
                (object)[
                    'betaald_op' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'bedrag' => 150.00,
                    'klant_naam' => 'Peter van Dam'
                ],
                (object)[
                    'betaald_op' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                    'bedrag' => 200.00,
                    'klant_naam' => 'Lisa Bakker'
                ]
            ],
            'nieuwe_gebruikers' => [
                // Mock data voor nieuwe gebruikers
                (object)[
                    'aangemaakt_op' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                    'voornaam' => 'Kees',
                    'achternaam' => 'Groot',
                    'role' => 'klant'
                ],
                (object)[
                    'aangemaakt_op' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                    'voornaam' => 'Anna',
                    'achternaam' => 'Smit',
                    'role' => 'instructeur'
                ]
            ]
        ];
    }

    private function getRapportData($type, $periode, $datum) {
        try {
            switch ($type) {
                case 'omzet':
                    return $this->reserveringModel->getOmzetRapport($periode, $datum);
                case 'gebruikers':
                    return $this->userModel->getGebruikersRapport($periode, $datum);
                case 'lessen':
                    return $this->reserveringModel->getLessenRapport($periode, $datum);
                case 'instructeurs':
                    return $this->userModel->getInstructeursRapport($periode, $datum);
                default:
                    return [
                        'error' => 'Onbekend rapport type'
                    ];
            }
        } catch (Exception $e) {
            // Log de error en return lege data
            error_log("Rapport error: " . $e->getMessage());
            return [
                'error' => 'Er is een fout opgetreden bij het laden van de rapportgegevens.',
                'totale_omzet' => 0,
                'aantal_betalingen' => 0,
                'gemiddelde_betaling' => 0,
                'openstaand_bedrag' => 0,
                'dagelijkse_omzet' => []
            ];
        }
    }

    private function calculateConversionRate() {
        $totaal_reserveringen = $this->reserveringModel->getTotaalReserveringen();
        $voltooide_reserveringen = $this->reserveringModel->getVoltooideReserveringen();
        
        if ($totaal_reserveringen > 0) {
            return round(($voltooide_reserveringen / $totaal_reserveringen) * 100, 1);
        }
        
        return 0;
    }

    private function sendRolWijzigingEmail($gebruiker, $nieuwe_rol) {
        $emailService = new EmailService();
        
        $subject = 'Je account rol is bijgewerkt - Windkracht-12';
        
        $rol_beschrijving = [
            'klant' => 'Je kunt nu lessen reserveren en je reserveringen beheren.',
            'instructeur' => 'Je hebt nu toegang tot de instructeur functionaliteiten.',
            'eigenaar' => 'Je hebt nu volledige toegang tot alle systeem functionaliteiten.'
        ];
        
        $body = "
        <h2>Beste {$gebruiker->voornaam},</h2>
        
        <p>Je account rol is bijgewerkt naar: <strong>" . ucfirst($nieuwe_rol) . "</strong></p>
        
        <p>{$rol_beschrijving[$nieuwe_rol]}</p>
        
        <p>Log opnieuw in om de nieuwe functionaliteiten te gebruiken.</p>
        
        <p>Heb je vragen over deze wijziging? Neem contact op via info@kitesurfschool-windkracht12.nl</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
                $emailService->sendEmail($gebruiker->email, $subject, $body);
    }

    public function toggle_user_status($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $gebruiker = $this->userModel->getUserById($id);
            
            if (!$gebruiker) {
                flash('error_message', 'Gebruiker niet gevonden.', 'alert alert-danger');
                redirect('eigenaar/gebruikers');
            }
            
            // Prevent deactivating the current user (eigenaar)
            if ($id == $_SESSION['user_id']) {
                flash('error_message', 'Je kunt je eigen account niet deactiveren.', 'alert alert-danger');
                redirect('eigenaar/gebruiker_details/' . $id);
            }
            
            $nieuwe_status = $gebruiker->is_active ? 0 : 1;
            
            if ($this->userModel->toggleUserStatus($id, $nieuwe_status)) {
                $status_text = $nieuwe_status ? 'geactiveerd' : 'gedeactiveerd';
                flash('success_message', "Gebruiker succesvol {$status_text}.", 'alert alert-success');
                
                // Send email notification
                $this->sendStatusWijzigingEmail($gebruiker, $nieuwe_status);
                
                redirect('eigenaar/gebruiker_details/' . $id);
            } else {
                flash('error_message', 'Er is iets misgegaan bij het wijzigen van de gebruikersstatus.', 'alert alert-danger');
                redirect('eigenaar/gebruiker_details/' . $id);
            }
        } else {
            redirect('eigenaar/gebruikers');
        }
    }
    
    private function sendStatusWijzigingEmail($gebruiker, $nieuwe_status) {
        $emailService = new EmailService();
        
        $subject = $nieuwe_status ? 'Je account is geactiveerd - Windkracht-12' : 'Je account is gedeactiveerd - Windkracht-12';
        
        if ($nieuwe_status) {
            $body = "
            <h2>Beste {$gebruiker->voornaam},</h2>
            
            <p>Je account is geactiveerd! Je kunt weer inloggen en gebruik maken van onze diensten.</p>
            
            <p>Log in op: <a href='" . URLROOT . "/auth/login'>" . URLROOT . "/auth/login</a></p>
            
            <p>Heb je vragen? Neem contact op via info@kitesurfschool-windkracht12.nl</p>
            
            <p>Met vriendelijke groet,<br>
            Team Windkracht-12</p>
            ";
        } else {
            $body = "
            <h2>Beste {$gebruiker->voornaam},</h2>
            
            <p>Je account is tijdelijk gedeactiveerd. Je kunt momenteel niet inloggen.</p>
            
            <p>Voor meer informatie over deze beslissing, neem contact op via info@kitesurfschool-windkracht12.nl</p>
            
            <p>Met vriendelijke groet,<br>
            Team Windkracht-12</p>
            ";
        }
        
        $emailService->sendEmail($gebruiker->email, $subject, $body);
    }
}
    

    private function sendBetalingBevestigingEmail($klant, $reservering) {
        $emailService = new EmailService();
        
        $subject = 'Betaling ontvangen - Windkracht-12';
        
        $body = "
        <h2>Beste {$klant->voornaam},</h2>
        
        <p>We hebben je betaling ontvangen voor je kitesurfles!</p>
        
        <h3>Betalingsdetails:</h3>
        <ul>
            <li><strong>Reservering:</strong> #{$reservering->id}</li>
            <li><strong>Lespakket:</strong> {$reservering->lespakket_naam}</li>
            <li><strong>Bedrag:</strong> €" . number_format($reservering->lespakket_prijs, 2) . "</li>
            <li><strong>Status:</strong> Betaald</li>
        </ul>
        
        <p>Je reservering is nu volledig bevestigd. Je instructeur neemt contact op voor de definitieve tijdindeling.</p>
        
        <p>Tot ziens bij het water!</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $subject, $body);
    }

    private function sendStatusWijzigingEmail($klant, $reservering, $nieuwe_status) {
        $emailService = new EmailService();
        
        $status_beschrijving = [
            'bevestigd' => 'Je reservering is bevestigd! We nemen binnenkort contact op voor de planning.',
            'geannuleerd' => 'Je reservering is geannuleerd. Neem contact op voor meer informatie.',
            'afgerond' => 'Je kitesurfles is afgerond. Bedankt voor je deelname!'
        ];
        
        $subject = 'Status update voor je kitesurfles - Windkracht-12';
        
        $body = "
        <h2>Beste {$klant->voornaam},</h2>
        
        <p>De status van je reservering is bijgewerkt.</p>
        
        <h3>Reservering Details:</h3>
        <ul>
            <li><strong>Reservering:</strong> #{$reservering->id}</li>
            <li><strong>Nieuwe Status:</strong> " . ucfirst($nieuwe_status) . "</li>
            <li><strong>Lespakket:</strong> {$reservering->lespakket_naam}</li>
        </ul>
        
        <p>{$status_beschrijving[$nieuwe_status]}</p>
        
        <p>Heb je vragen? Neem contact op via info@kitesurfschool-windkracht12.nl</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $subject, $body);
    }

    private function getSysteemInstellingen() {
        // In practice, these would come from a database table
        return [
            'bedrijfsnaam' => 'Kitesurfschool Windkracht-12',
            'email' => 'info@kitesurfschool-windkracht12.nl',
            'telefoon' => '06-12345678',
            'adres' => 'Utrecht, Nederland',
            'btw_nummer' => 'NL123456789B01',
            'bank_rekening' => 'NL12 ABCD 0123 4567 89',
            'email_automatisch' => true,
            'backup_automatisch' => true
        ];
    }

    private function updateSysteemInstellingen($instellingen) {
        // In practice, this would update a database table
        return true;
    }

    private function createDatabaseBackup($type) {
        // In practice, this would create actual database backup
        return true;
    }

    private function getSystemLogs($type, $datum) {
        // In practice, this would read from log files
        return [
            [
                'tijd' => date('H:i:s'),
                'type' => 'info',
                'bericht' => 'Gebruiker heeft ingelogd',
                'gebruiker' => 'jan@email.com'
            ],
            [
                'tijd' => date('H:i:s', strtotime('-1 hour')),
                'type' => 'warning',
                'bericht' => 'Mislukte inlogpoging',
                'gebruiker' => 'onbekend@email.com'
            ]
        ];
    }

    private function getInstructeursRapport($periode, $datum) {
        return $this->userModel->getInstructeursRapport($periode, $datum);
    }
}