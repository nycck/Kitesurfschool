<?php

class Eigenaar extends BaseController {
    
    public function __construct() {
        parent::__construct();
        
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
            'totaal_gebruikers' => $this->userModel->getTotaalGebruikers(),
            'totaal_reserveringen' => $this->reserveringModel->getTotaalReserveringen(),
            'omzet_deze_maand' => $this->reserveringModel->getOmzetDezeMaand(),
            'actieve_instructeurs' => $this->userModel->getActieveInstructeurs(),
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

        $persoon = $this->persoonModel->getPersonByUserId($id);
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

    private function getEigenaarStatistieken() {
        return [
            'nieuwe_gebruikers_deze_maand' => $this->userModel->getNieuweGebruikersDezeMaand(),
            'voltooide_lessen_deze_maand' => $this->reserveringModel->getVoltooidelessenDezeMaand(),
            'gemiddelde_beoordeling' => $this->reserveringModel->getGemiddeldeBeoordeling(),
            'populairste_lespakket' => $this->lespakketModel->getPopulairsteLespakket(),
            'populairste_locatie' => $this->locatieModel->getPopulairsteLocatie(),
            'conversion_rate' => $this->calculateConversionRate()
        ];
    }

    private function getRecenteActiviteit() {
        return [
            'nieuwe_reserveringen' => $this->reserveringModel->getNieuweReserveringen(5),
            'recente_betalingen' => $this->reserveringModel->getRecenteBetalingen(5),
            'nieuwe_gebruikers' => $this->userModel->getNieuweGebruikers(5)
        ];
    }

    private function getRapportData($type, $periode, $datum) {
        switch ($type) {
            case 'omzet':
                return $this->reserveringModel->getOmzetRapport($periode, $datum);
            case 'gebruikers':
                return $this->userModel->getGebruikersRapport($periode, $datum);
            case 'lessen':
                return $this->reserveringModel->getLessenRapport($periode, $datum);
            case 'instructeurs':
                return $this->getInstructeursRapport($periode, $datum);
            default:
                return [];
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