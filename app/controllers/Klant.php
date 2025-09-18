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
            $user = $this->userModel->getUserById($userId);
            
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
            // Mark as paid
            if ($this->reserveringModel->markAsPaid($reserveringId)) {
                flash('message', 'Betaling geregistreerd. Wacht op bevestiging van de eigenaar.', 'alert-success');
                redirect('klant/reservering/' . $reserveringId);
            } else {
                flash('message', 'Er ging iets mis bij het registreren van de betaling', 'alert-danger');
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
            $reden = sanitizeInput($_POST['reden']);
            
            if (empty($reden)) {
                flash('message', 'Reden voor annulering is verplicht', 'alert-danger');
                redirect('klant/annuleerLes/' . $lesId);
            }
            
            if ($this->reserveringModel->annuleerLes($lesId, $reden)) {
                flash('message', 'Les succesvol geannuleerd', 'alert-success');
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
}