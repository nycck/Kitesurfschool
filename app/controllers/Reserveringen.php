<?php

class Reserveringen extends BaseController {
    
    private $lespakketModel;
    private $locatieModel;
    private $reserveringModel;
    private $persoonModel;
    private $userModel;
    
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
        
        $this->lespakketModel = $this->model('Lespakket');
        $this->locatieModel = $this->model('Locatie');
        $this->reserveringModel = $this->model('Reservering');
        $this->persoonModel = $this->model('Persoon');
        $this->userModel = $this->model('User');
    }

    public function index() {
        try {
            // Toon alle reserveringen van de ingelogde gebruiker
            $reserveringen = $this->reserveringModel->getReserveringenByUserId($_SESSION['user_id']);
            
            $data = [
                'title' => 'Mijn Reserveringen',
                'reserveringen' => $reserveringen ?? []
            ];

            $this->view('reserveringen/index', $data);
        } catch (Exception $e) {
            // Log de error en toon gebruiksvriendelijke foutmelding
            error_log("Reserveringen index error: " . $e->getMessage());
            
            $data = [
                'title' => 'Mijn Reserveringen',
                'reserveringen' => []
            ];
            
            flash('reservering_message', 'Er is een probleem opgetreden bij het laden van je reserveringen. Probeer het later opnieuw.', 'alert alert-danger');
            $this->view('reserveringen/index', $data);
        }
    }

    public function maken() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process the reservation form
            $data = [
                'lespakket_id' => trim($_POST['lespakket_id']),
                'locatie_id' => trim($_POST['locatie_id']),
                'gewenste_datum' => trim($_POST['gewenste_datum']),
                'opmerking' => trim($_POST['opmerking']),
                'duo_partner_email' => trim($_POST['duo_partner_email']),
                'lespakket_id_err' => '',
                'locatie_id_err' => '',
                'gewenste_datum_err' => '',
                'duo_partner_email_err' => ''
            ];

            // Validate inputs
            if (empty($data['lespakket_id'])) {
                $data['lespakket_id_err'] = 'Selecteer een lespakket';
            }

            if (empty($data['locatie_id'])) {
                $data['locatie_id_err'] = 'Selecteer een locatie';
            }

            if (empty($data['gewenste_datum'])) {
                $data['gewenste_datum_err'] = 'Selecteer een datum';
            } else {
                // Check if date is not in the past
                $selectedDate = new DateTime($data['gewenste_datum']);
                $today = new DateTime();
                if ($selectedDate < $today) {
                    $data['gewenste_datum_err'] = 'Datum kan niet in het verleden liggen';
                }
            }

            // Validate duo partner email if provided
            if (!empty($data['duo_partner_email'])) {
                if (!filter_var($data['duo_partner_email'], FILTER_VALIDATE_EMAIL)) {
                    $data['duo_partner_email_err'] = 'Ongeldig email adres';
                } else {
                    // Check if duo partner exists and is a customer
                    $duoPartner = $this->userModel->findUserByEmail($data['duo_partner_email']);
                    if (!$duoPartner) {
                        $data['duo_partner_email_err'] = 'Gebruiker niet gevonden. Duo partner moet een account hebben.';
                    } else if ($duoPartner->role !== 'klant') {
                        $data['duo_partner_email_err'] = 'Duo partner moet een klant zijn';
                    } else if ($duoPartner->id == $_SESSION['user_id']) {
                        $data['duo_partner_email_err'] = 'Je kunt jezelf niet als duo partner selecteren';
                    }
                }
            }

            // Make sure errors are empty
            if (empty($data['lespakket_id_err']) && empty($data['locatie_id_err']) && 
                empty($data['gewenste_datum_err']) && empty($data['duo_partner_email_err'])) {
                
                // Get current user's person record
                $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
                $persoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
                
                if (!$persoon) {
                    flash('reservering_message', 'Geen persoongegevens gevonden. Voltooi eerst je profiel.', 'alert alert-danger');
                    redirect('reserveringen/maken');
                }

                $reserveringData = [
                    'persoon_id' => $persoon->id,
                    'lespakket_id' => $data['lespakket_id'],
                    'locatie_id' => $data['locatie_id'],
                    'gewenste_datum' => $data['gewenste_datum'],
                    'opmerking' => $data['opmerking'],
                    'duo_partner_id' => null,
                    'status' => 'aangevraagd',
                    'betaal_status' => 'wachtend'
                ];

                // Add duo partner if provided
                if (!empty($data['duo_partner_email'])) {
                    $duoPartner = $this->userModel->findUserByEmail($data['duo_partner_email']);
                    $duoPartnerPersoon = $this->persoonModel->getPersoonByUserId($duoPartner->id);
                    if ($duoPartnerPersoon) {
                        $reserveringData['duo_partner_id'] = $duoPartnerPersoon->id;
                    }
                }

                // Create reservation
                if ($this->reserveringModel->addReservering($reserveringData)) {
                    // Send confirmation email
                    $lespakket = $this->lespakketModel->getLespakketById($data['lespakket_id']);
                    $locatie = $this->locatieModel->getLocatieById($data['locatie_id']);
                    
                    $this->sendReservationConfirmationEmail($currentUser, $persoon, $lespakket, $locatie, $reserveringData);
                    
                    flash('reservering_message', 'Reservering succesvol aangemaakt! Je ontvangt een bevestigingsmail met betalingsgegevens.', 'alert alert-success');
                    redirect('reserveringen');
                } else {
                    flash('reservering_message', 'Er is iets misgegaan. Probeer het opnieuw.', 'alert alert-danger');
                }
            }

            // Load form data again if there were errors
            $data['lespakketten'] = $this->lespakketModel->getAllLespakketten();
            $data['locaties'] = $this->locatieModel->getAllLocaties();
            $data['title'] = 'Nieuwe Reservering';
            $this->view('reserveringen/maken', $data);

        } else {
            // Load the form
            $data = [
                'title' => 'Nieuwe Reservering',
                'lespakketten' => $this->lespakketModel->getAllLespakketten(),
                'locaties' => $this->locatieModel->getAllLocaties(),
                'lespakket_id' => '',
                'locatie_id' => '',
                'gewenste_datum' => '',
                'opmerking' => '',
                'duo_partner_email' => '',
                'lespakket_id_err' => '',
                'locatie_id_err' => '',
                'gewenste_datum_err' => '',
                'duo_partner_email_err' => ''
            ];

            $this->view('reserveringen/maken', $data);
        }
    }

    public function details($id) {
        $reservering = $this->reserveringModel->getReserveringById($id);
        
        if (!$reservering) {
            flash('reservering_message', 'Reservering niet gevonden.', 'alert alert-danger');
            redirect('reserveringen');
        }

        // Check if user owns this reservation
        $persoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        if ($reservering->persoon_id != $persoon->id && $reservering->duo_partner_id != $persoon->id) {
            flash('reservering_message', 'Je hebt geen toegang tot deze reservering.', 'alert alert-danger');
            redirect('reserveringen');
        }

        $data = [
            'title' => 'Reservering Details',
            'reservering' => $reservering
        ];

        $this->view('reserveringen/details', $data);
    }

    public function annuleren($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reservering = $this->reserveringModel->getReserveringById($id);
            
            if (!$reservering) {
                flash('reservering_message', 'Reservering niet gevonden.', 'alert alert-danger');
                redirect('reserveringen');
            }

            // Check if user owns this reservation
            $persoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
            if ($reservering->persoon_id != $persoon->id) {
                flash('reservering_message', 'Je kunt alleen je eigen reserveringen annuleren.', 'alert alert-danger');
                redirect('reserveringen');
            }

            // Check if reservation can be cancelled (not already cancelled or completed)
            if (in_array($reservering->status, ['geannuleerd', 'afgerond'])) {
                flash('reservering_message', 'Deze reservering kan niet meer geannuleerd worden.', 'alert alert-danger');
                redirect('reserveringen');
            }

            $reden = trim($_POST['reden']);
            if (empty($reden)) {
                flash('reservering_message', 'Geef een reden op voor annulering.', 'alert alert-danger');
                redirect('reserveringen/details/' . $id);
            }

            // Update reservation status
            if ($this->reserveringModel->updateReserveringStatus($id, 'geannuleerd', $reden)) {
                // Send cancellation email
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                $this->sendCancellationEmail($user, $reservering, $reden);
                
                flash('reservering_message', 'Reservering succesvol geannuleerd.', 'alert alert-success');
                redirect('reserveringen');
            } else {
                flash('reservering_message', 'Er is iets misgegaan bij het annuleren.', 'alert alert-danger');
                redirect('reserveringen/details/' . $id);
            }
        } else {
            redirect('reserveringen');
        }
    }

    public function beschikbaarheid() {
        $data = [
            'title' => 'Beschikbaarheid Bekijken',
            'locaties' => $this->locatieModel->getAllLocaties()
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $locatie_id = trim($_POST['locatie_id']);
            $datum = trim($_POST['datum']);
            
            if (!empty($locatie_id) && !empty($datum)) {
                $data['beschikbaarheid'] = $this->reserveringModel->getBeschikbaarheidByDate($locatie_id, $datum);
                $data['geselecteerde_locatie'] = $locatie_id;
                $data['geselecteerde_datum'] = $datum;
            }
        }

        $this->view('reserveringen/beschikbaarheid', $data);
    }

    private function sendReservationConfirmationEmail($user, $persoon, $lespakket, $locatie, $reservering) {
        $emailService = new EmailService();
        
        $subject = 'Bevestiging Kitesurfles Reservering - Windkracht-12';
        
        $body = "
        <h2>Beste {$persoon->voornaam},</h2>
        
        <p>Bedankt voor je reservering bij Kitesurfschool Windkracht-12!</p>
        
        <h3>Reservering Details:</h3>
        <ul>
            <li><strong>Lespakket:</strong> {$lespakket->naam}</li>
            <li><strong>Locatie:</strong> {$locatie->naam}</li>
            <li><strong>Gewenste datum:</strong> {$reservering['gewenste_datum']}</li>
            <li><strong>Prijs:</strong> €{$lespakket->prijs}</li>
            <li><strong>Status:</strong> Aangevraagd</li>
        </ul>
        
        <h3>Betalingsgegevens:</h3>
        <p>Je ontvangt binnen 24 uur een bevestiging van je instructeur met de exacte tijd en betalingsinstructies.</p>
        <p>Rekeningnummer: NL12 ABCD 0123 4567 89<br>
        T.n.v.: Kitesurfschool Windkracht-12<br>
        Onder vermelding van: Reservering {$persoon->achternaam}</p>
        
        <p>Heb je vragen? Neem contact op via info@kitesurfschool-windkracht12.nl</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($user->email, $subject, $body);
    }

    private function sendCancellationEmail($user, $reservering, $reden) {
        $emailService = new EmailService();
        
        $subject = 'Annulering Kitesurfles - Windkracht-12';
        
        $body = "
        <h2>Beste klant,</h2>
        
        <p>Je reservering is succesvol geannuleerd.</p>
        
        <h3>Geannuleerde Reservering:</h3>
        <ul>
            <li><strong>Datum:</strong> {$reservering->gewenste_datum}</li>
            <li><strong>Reden:</strong> {$reden}</li>
        </ul>
        
        <p>Als je binnen 24 uur voor de geplande les hebt geannuleerd, krijg je het volledige bedrag terug of kun je een nieuwe datum kiezen.</p>
        
        <p>Voor vragen kun je contact opnemen via info@kitesurfschool-windkracht12.nl</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($user->email, $subject, $body);
    }
}