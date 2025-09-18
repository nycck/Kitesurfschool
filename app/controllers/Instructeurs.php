<?php

class Instructeurs extends BaseController {
    
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
        $view = isset($_GET['view']) ? $_GET['view'] : 'week';
        $datum = isset($_GET['datum']) ? $_GET['datum'] : date('Y-m-d');
        
        $data = [
            'title' => 'Lessenplanning',
            'view' => $view,
            'datum' => $datum,
            'lessen' => $this->getLessenForView($instructeurPersoon->id, $view, $datum)
        ];

        $this->view('instructeurs/planning', $data);
    }

    public function klanten() {
        $instructeurPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        $data = [
            'title' => 'Mijn Klanten',
            'klanten' => $this->reserveringModel->getKlantenByInstructeur($instructeurPersoon->id)
        ];

        $this->view('instructeurs/klanten', $data);
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
                <p>Je kitesurfles moet helaas worden geannuleerd vanwege ongunstige weersomstandigheden.</p>
                <p>Veiligheid staat bij ons voorop, daarom geven we alleen les bij geschikte wind- en weercondities.</p>
                <p>We nemen contact met je op om een nieuwe datum in te plannen.</p>
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