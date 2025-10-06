<?php

class Klant extends BaseController
{
    private $userModel;
    private $persoonModel;
    private $reserveringModel;
    private $lespakketModel;
    private $locatieModel;

    public function __construct()
    {
        // Check if user is logged in and is a customer
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        
        if (!hasRole('klant')) {
            redirect('auth/login');
        }

        $this->userModel = $this->model('User');
        $this->persoonModel = $this->model('Persoon');
        $this->reserveringModel = $this->model('Reservering');
        $this->lespakketModel = $this->model('Lespakket');
        $this->locatieModel = $this->model('Locatie');
    }

    public function dashboard()
    {
        $userId = $_SESSION['user_id'];
        
        // Haal gebruikersgegevens op
        $user = $this->userModel->getUserById($userId);
        $reserveringen = $this->reserveringModel->getReserveringenByKlant($userId);
        $aankomendeLessen = $this->reserveringModel->getAankomendeLessen($userId, 5);
        
        $data = [
            'title' => 'Dashboard - Mijn Account',
            'user' => $user,
            'reserveringen' => $reserveringen,
            'aankomende_lessen' => $aankomendeLessen,
            'stats' => [
                'totaal_reserveringen' => count($reserveringen),
                'actieve_reserveringen' => count(array_filter($reserveringen, function($r) { 
                    return $r->status !== 'geannuleerd'; 
                })),
                'voltooide_lessen' => $this->reserveringModel->getVoltooidelessenCount($userId)
            ]
        ];

        $this->view('klant/dashboard', $data);
    }

    public function profiel()
    {
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $userId,
                'voornaam' => sanitizeInput($_POST['voornaam']),
                'achternaam' => sanitizeInput($_POST['achternaam']),
                'adres' => sanitizeInput($_POST['adres']),
                'postcode' => sanitizeInput($_POST['postcode']),
                'woonplaats' => sanitizeInput($_POST['woonplaats']),
                'geboortedatum' => $_POST['geboortedatum'],
                'telefoon' => sanitizeInput($_POST['telefoon'])
            ];
            
            $errors = [];
            
            // Validatie
            if (empty($data['voornaam'])) {
                $errors[] = 'Voornaam is verplicht';
            }
            
            if (empty($data['achternaam'])) {
                $errors[] = 'Achternaam is verplicht';
            }
            
            if (!empty($data['postcode']) && !validatePostcode($data['postcode'])) {
                $errors[] = 'Ongeldige postcode';
            }
            
            if (empty($errors)) {
                if ($this->persoonModel->savePersoon($data)) {
                    flash('message', 'Profiel succesvol bijgewerkt', 'alert-success');
                    redirect('klant/profiel');
                } else {
                    $errors[] = 'Er ging iets mis bij het opslaan';
                }
            }
            
            $user = $this->userModel->getUserById($userId);
            $viewData = [
                'title' => 'Mijn Profiel',
                'user' => $user,
                'errors' => $errors,
                'form_data' => $data
            ];
            
            $this->view('klant/profiel', $viewData);
        } else {
            // Get user with person data
            $user = $this->userModel->getUserById($userId);
            $persoon = $this->persoonModel->getPersoonByUserId($userId);
            
            // Merge user and person data for the view
            if ($persoon) {
                $user->voornaam = $persoon->voornaam;
                $user->achternaam = $persoon->achternaam;
                $user->adres = $persoon->adres;
                $user->postcode = $persoon->postcode;
                $user->woonplaats = $persoon->woonplaats;
                $user->geboortedatum = $persoon->geboortedatum;
                $user->telefoon = $persoon->telefoon;
            }
            
            $data = [
                'title' => 'Mijn Profiel',
                'user' => $user
            ];
            
            $this->view('klant/profiel', $data);
        }
    }

    public function reserveringen()
    {
        $userId = $_SESSION['user_id'];
        $reserveringen = $this->reserveringModel->getReserveringenByKlant($userId, true); // met details
        
        $data = [
            'title' => 'Mijn Reserveringen',
            'reserveringen' => $reserveringen
        ];
        
        $this->view('klant/reserveringen', $data);
    }

    public function reservering($id = null)
    {
        if (!$id) {
            redirect('klant/reserveringen');
        }
        
        $userId = $_SESSION['user_id'];
        $reservering = $this->reserveringModel->getReserveringByIdAndKlant($id, $userId);
        
        if (!$reservering) {
            flash('message', 'Reservering niet gevonden', 'alert-danger');
            redirect('klant/reserveringen');
        }
        
        $lessen = $this->reserveringModel->getLessenByReservering($id);
        
        $data = [
            'title' => 'Reservering Details',
            'reservering' => $reservering,
            'lessen' => $lessen
        ];
        
        $this->view('klant/reservering_detail', $data);
    }

    public function betaling($reserveringId = null)
    {
        if (!$reserveringId) {
            redirect('klant/reserveringen');
        }
        
        $userId = $_SESSION['user_id'];
        $reservering = $this->reserveringModel->getReserveringByIdAndKlant($reserveringId, $userId);
        
        if (!$reservering) {
            flash('message', 'Reservering niet gevonden', 'alert-danger');
            redirect('klant/reserveringen');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $betaaldatum = trim($_POST['betaaldatum']);
            $betaalMethode = trim($_POST['betaal_methode']);
            $referentie = trim($_POST['referentie']);
            $opmerking = trim($_POST['opmerking']);
            $bevestiging = isset($_POST['bevestig_betaling']);
            
            $errors = [];
            
            if (empty($betaaldatum)) {
                $errors[] = 'Betaaldatum is verplicht';
            }
            
            if (empty($betaalMethode)) {
                $errors[] = 'Betaalmethode is verplicht';
            }
            
            if (!$bevestiging) {
                $errors[] = 'Je moet de bevestiging aanvinken';
            }
            
            if (empty($errors)) {
                $betalingData = [
                    'reservering_id' => $reserveringId,
                    'betaaldatum' => $betaaldatum,
                    'betaal_methode' => $betaalMethode,
                    'referentie' => !empty($referentie) ? $referentie : null,
                    'opmerking' => !empty($opmerking) ? $opmerking : null,
                    'klant_id' => $userId
                ];
                
                if ($this->reserveringModel->markAsPaid($betalingData)) {
                    // Verstuur bevestiging emails
                    $this->sendBetalingBevestigingEmails($reservering, $betalingData);
                    
                    flash('message', 'Betaling succesvol geregistreerd! Je ontvangt een bevestigingsmail. De eigenaar controleert je betaling binnenkort.', 'alert-success');
                    redirect('reserveringen');
                } else {
                    flash('message', 'Er ging iets mis bij het registreren van de betaling', 'alert-danger');
                }
            } else {
                flash('message', implode('<br>', $errors), 'alert-danger');
            }
        }
        
        $data = [
            'title' => 'Betaling Bevestigen',
            'reservering' => $reservering
        ];
        
        $this->view('klant/betaling', $data);
    }

    public function annuleerLes($lesId = null)
    {
        if (!$lesId) {
            redirect('klant/reserveringen');
        }
        
        $userId = $_SESSION['user_id'];
        $les = $this->reserveringModel->getLesById($lesId);
        
        if (!$les || $les->klant_id != $userId) {
            flash('message', 'Les niet gevonden', 'alert-danger');
            redirect('klant/reserveringen');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $redenType = trim($_POST['reden_type']);
            $redenDetails = trim($_POST['reden_details']);
            $extraOpmerking = trim($_POST['extra_opmerking']);
            $voorkeurDatum = trim($_POST['voorkeur_datum']);
            $voorkeurTijd = trim($_POST['voorkeur_tijd']);
            
            if (empty($redenType)) {
                flash('message', 'Reden voor annulering is verplicht', 'alert-danger');
                redirect('klant/annuleerLes/' . $lesId);
            }
            
            if ($redenType === 'anders' && empty($redenDetails)) {
                flash('message', 'Specificatie van reden is verplicht', 'alert-danger');
                redirect('klant/annuleerLes/' . $lesId);
            }
            
            // Bouw complete reden string
            $volledigeReden = $redenType;
            if (!empty($redenDetails)) {
                $volledigeReden .= ': ' . $redenDetails;
            }
            if (!empty($extraOpmerking)) {
                $volledigeReden .= ' | Opmerking: ' . $extraOpmerking;
            }
            if (!empty($voorkeurDatum) || !empty($voorkeurTijd)) {
                $volledigeReden .= ' | Voorkeur nieuwe datum: ';
                if (!empty($voorkeurDatum)) {
                    $volledigeReden .= date('d-m-Y', strtotime($voorkeurDatum));
                }
                if (!empty($voorkeurTijd)) {
                    $volledigeReden .= ' (' . $voorkeurTijd . ')';
                }
            }
            
            $annuleringData = [
                'les_id' => $lesId,
                'reden' => $volledigeReden,
                'reden_type' => $redenType,
                'voorkeur_datum' => !empty($voorkeurDatum) ? $voorkeurDatum : null,
                'voorkeur_tijd' => !empty($voorkeurTijd) ? $voorkeurTijd : null
            ];
            
            if ($this->reserveringModel->annuleerLes($annuleringData)) {
                // Verstuur email naar instructeur en klant
                $this->sendAnnuleringEmails($les, $annuleringData);
                
                flash('message', 'Les succesvol geannuleerd. Je kunt binnenkort een nieuwe datum kiezen na goedkeuring van de instructeur.', 'alert-success');
                redirect('klant/reserveringen');
            } else {
                flash('message', 'Er ging iets mis bij het annuleren', 'alert-danger');
            }
        }
        
        $data = [
            'title' => 'Les Annuleren',
            'les' => $les
        ];
        
        $this->view('klant/annuleer_les', $data);
    }

    private function sendAnnuleringEmails($les, $annuleringData) 
    {
        $emailService = new EmailService();
        $klant = $this->userModel->getUserById($_SESSION['user_id']);
        $klantPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        // Email naar klant (bevestiging annulering)
        $klantSubject = 'Bevestiging Annulering Kitesurfles - Windkracht-12';
        $klantBody = "
        <h2>Beste {$klantPersoon->voornaam},</h2>
        
        <p>Je kitesurfles is succesvol geannuleerd.</p>
        
        <h3>Geannuleerde Les:</h3>
        <ul>
            <li><strong>Lespakket:</strong> {$les->pakket_naam}</li>
            <li><strong>Locatie:</strong> {$les->locatie_naam}</li>
            <li><strong>Datum:</strong> " . (isset($les->les_datum) ? date('l d F Y', strtotime($les->les_datum)) : 'Te bepalen') . "</li>
            <li><strong>Tijd:</strong> " . ($les->start_tijd ?? 'Te bepalen') . "</li>
        </ul>
        
        <h3>Reden voor annulering:</h3>
        <p>{$annuleringData['reden']}</p>
        
        " . (!empty($annuleringData['voorkeur_datum']) ? "
        <h3>Je voorkeur voor nieuwe datum:</h3>
        <p>" . date('l d F Y', strtotime($annuleringData['voorkeur_datum'])) . 
        (!empty($annuleringData['voorkeur_tijd']) ? " ({$annuleringData['voorkeur_tijd']})" : "") . "</p>
        " : "") . "
        
        <h3>Volgende Stappen:</h3>
        <ul>
            <li>Je instructeur wordt geïnformeerd over de annulering</li>
            <li>Na beoordeling van de reden nemen we contact op voor een nieuwe datum</li>
            <li>Het lesgeld blijft behouden voor je nieuwe les</li>
            <li>Bij annulering binnen 24 uur kunnen er kosten in rekening worden gebracht</li>
        </ul>
        
        <p>Vragen? Neem contact op via <a href='tel:0612345678'>06-12345678</a> of <a href='mailto:info@windkracht12.nl'>info@windkracht12.nl</a></p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $klantSubject, $klantBody);
        
        // Email naar instructeur (indien toegewezen)
        if (isset($les->instructeur_id) && $les->instructeur_id) {
            $instructeur = $this->userModel->getUserById($les->instructeur_user_id);
            if ($instructeur) {
                $instructeurSubject = 'Les Geannuleerd door Klant - Windkracht-12';
                $instructeurBody = "
                <h2>Beste instructeur,</h2>
                
                <p>Een van je lessen is geannuleerd door de klant.</p>
                
                <h3>Les Details:</h3>
                <ul>
                    <li><strong>Klant:</strong> {$klantPersoon->voornaam} {$klantPersoon->achternaam}</li>
                    <li><strong>Email:</strong> {$klant->email}</li>
                    <li><strong>Telefoon:</strong> " . ($klantPersoon->telefoon ?? 'Niet opgegeven') . "</li>
                    <li><strong>Lespakket:</strong> {$les->pakket_naam}</li>
                    <li><strong>Locatie:</strong> {$les->locatie_naam}</li>
                    <li><strong>Datum:</strong> " . (isset($les->les_datum) ? date('l d F Y', strtotime($les->les_datum)) : 'Te bepalen') . "</li>
                    <li><strong>Tijd:</strong> " . ($les->start_tijd ?? 'Te bepalen') . "</li>
                </ul>
                
                <h3>Reden voor annulering:</h3>
                <p>{$annuleringData['reden']}</p>
                
                " . (!empty($annuleringData['voorkeur_datum']) ? "
                <h3>Klant voorkeur voor nieuwe datum:</h3>
                <p>" . date('l d F Y', strtotime($annuleringData['voorkeur_datum'])) . 
                (!empty($annuleringData['voorkeur_tijd']) ? " ({$annuleringData['voorkeur_tijd']})" : "") . "</p>
                " : "") . "
                
                <p>Log in op het instructeur dashboard om deze annulering te beoordelen en eventueel een nieuwe datum in te plannen.</p>
                
                <p>Met vriendelijke groet,<br>
                Team Windkracht-12</p>
                ";
                
                $emailService->sendEmail($instructeur->email, $instructeurSubject, $instructeurBody);
            }
        }
    }

    private function sendBetalingBevestigingEmails($reservering, $betalingData)
    {
        $emailService = new EmailService();
        $klant = $this->userModel->getUserById($_SESSION['user_id']);
        $klantPersoon = $this->persoonModel->getPersoonByUserId($_SESSION['user_id']);
        
        // Email naar klant (bevestiging betaling registratie)
        $klantSubject = 'Betaling Geregistreerd - Windkracht-12';
        $klantBody = "
        <h2>Beste {$klantPersoon->voornaam},</h2>
        
        <p>We hebben je betalingsbevestiging ontvangen voor je kitesurfles reservering.</p>
        
        <h3>Betalingsgegevens:</h3>
        <ul>
            <li><strong>Reservering:</strong> #{$reservering->id} - {$reservering->lespakket_naam}</li>
            <li><strong>Bedrag:</strong> €" . number_format($reservering->totale_prijs, 2, ',', '.') . "</li>
            <li><strong>Betaaldatum:</strong> " . date('d-m-Y', strtotime($betalingData['betaaldatum'])) . "</li>
            <li><strong>Methode:</strong> " . ucfirst($betalingData['betaal_methode']) . "</li>
            " . (!empty($betalingData['referentie']) ? "<li><strong>Referentie:</strong> {$betalingData['referentie']}</li>" : "") . "
        </ul>
        
        " . (!empty($betalingData['opmerking']) ? "<h3>Je opmerking:</h3><p>{$betalingData['opmerking']}</p>" : "") . "
        
        <h3>Volgende Stappen:</h3>
        <ul>
            <li>✅ Je betalingsbevestiging is geregistreerd</li>
            <li>⏳ De eigenaar controleert en bevestigt je betaling</li>
            <li>📧 Je ontvangt een email zodra je betaling is goedgekeurd</li>
            <li>🏄 Je instructeur neemt contact op voor de lesplanning</li>
        </ul>
        
        <div style='background: #f8f9fa; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0;'>
            <h4 style='color: #28a745; margin-top: 0;'>Status: Betaling ter controle</h4>
            <p style='margin-bottom: 0;'>We controleren je betaling binnen 1-2 werkdagen. Bij vragen neem contact op via onderstaande gegevens.</p>
        </div>
        
        <p>Vragen? Neem contact op:<br>
        📞 <a href='tel:0612345678'>06-12345678</a><br>
        📧 <a href='mailto:info@windkracht12.nl'>info@windkracht12.nl</a></p>
        
        <p>Bedankt voor je vertrouwen in Windkracht-12!</p>
        
        <p>Met vriendelijke groet,<br>
        Team Windkracht-12</p>
        ";
        
        $emailService->sendEmail($klant->email, $klantSubject, $klantBody);
        
        // Email naar eigenaar (nieuwe betaling ter controle)
        $eigenaarSubject = 'Nieuwe Betalingsbevestiging ter Controle - Windkracht-12';
        $eigenaarBody = "
        <h2>Nieuwe betalingsbevestiging ontvangen</h2>
        
        <p>Een klant heeft een betaling geregistreerd die controle nodig heeft.</p>
        
        <h3>Klant Gegevens:</h3>
        <ul>
            <li><strong>Naam:</strong> {$klantPersoon->voornaam} {$klantPersoon->achternaam}</li>
            <li><strong>Email:</strong> {$klant->email}</li>
            <li><strong>Telefoon:</strong> " . ($klantPersoon->telefoon ?? 'Niet opgegeven') . "</li>
        </ul>
        
        <h3>Reservering Details:</h3>
        <ul>
            <li><strong>Reservering ID:</strong> #{$reservering->id}</li>
            <li><strong>Lespakket:</strong> {$reservering->lespakket_naam}</li>
            <li><strong>Locatie:</strong> {$reservering->locatie_naam}</li>
            <li><strong>Gewenste datum:</strong> " . date('d-m-Y', strtotime($reservering->gewenste_datum)) . "</li>
            <li><strong>Status:</strong> " . ucfirst($reservering->status) . "</li>
        </ul>
        
        <h3>Betalingsgegevens:</h3>
        <ul>
            <li><strong>Bedrag:</strong> €" . number_format($reservering->totale_prijs, 2, ',', '.') . "</li>
            <li><strong>Betaaldatum:</strong> " . date('d-m-Y', strtotime($betalingData['betaaldatum'])) . "</li>
            <li><strong>Methode:</strong> " . ucfirst($betalingData['betaal_methode']) . "</li>
            " . (!empty($betalingData['referentie']) ? "<li><strong>Referentie:</strong> {$betalingData['referentie']}</li>" : "") . "
        </ul>
        
        " . (!empty($betalingData['opmerking']) ? "<h3>Klant Opmerking:</h3><p>{$betalingData['opmerking']}</p>" : "") . "
        
        <div style='background: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; margin: 20px 0;'>
            <h4 style='color: #856404; margin-top: 0;'>⚠️ Actie Vereist</h4>
            <p style='margin-bottom: 0;'>Log in op het eigenaar dashboard om deze betaling te controleren en goed te keuren.</p>
        </div>
        
        <p>Met vriendelijke groet,<br>
        Windkracht-12 Systeem</p>
        ";
        
        // Stuur naar eigenaar(s)
        $eigenaren = $this->userModel->getUsersByRole('eigenaar');
        foreach ($eigenaren as $eigenaar) {
            $emailService->sendEmail($eigenaar->email, $eigenaarSubject, $eigenaarBody);
        }
    }
}