<?php

class ReserveringController extends BaseController
{
    private $reserveringModel;
    private $lespakketModel;
    private $persoonModel;
    private $locatieModel;

    public function __construct()
    {
        // Zorg dat alleen ingelogde gebruikers kunnen reserveren
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        
        $this->reserveringModel = $this->model('Reservering');
        $this->lespakketModel = $this->model('Lespakket');
        $this->persoonModel = $this->model('Persoon');
        $this->locatieModel = $this->model('Locatie');
    }

    public function index()
    {
        redirect('klant/dashboard');
    }

    // Nieuwe reservering aanmaken
    public function nieuw($lespakketId = null)
    {
        if (!$lespakketId) {
            flash('message', 'Geen lespakket geselecteerd', 'alert alert-danger');
            redirect('homepages/pakketten');
        }

        $lespakket = $this->lespakketModel->getLespakketById($lespakketId);
        if (!$lespakket) {
            flash('message', 'Lespakket niet gevonden', 'alert alert-danger');
            redirect('homepages/pakketten');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF check
            if (!verifyCSRFToken($_POST['csrf_token'])) {
                flash('message', 'Ongeldig formulier. Probeer opnieuw.', 'alert alert-danger');
                redirect("reservering/nieuw/$lespakketId");
            }

            $data = [
                'lespakket' => $lespakket,
                'locaties' => $this->locatieModel->getAllLocaties(),
                'voornaam' => trim($_POST['voornaam']),
                'tussenvoegsel' => trim($_POST['tussenvoegsel']),
                'achternaam' => trim($_POST['achternaam']),
                'geboortedatum' => $_POST['geboortedatum'],
                'telefoonnummer' => trim($_POST['telefoonnummer']),
                'locatie_id' => $_POST['locatie_id'],
                'aantal_personen' => (int)$_POST['aantal_personen'],
                'gewenste_datum' => $_POST['gewenste_datum'],
                'opmerkingen' => trim($_POST['opmerkingen']),
                'errors' => []
            ];

            // Validatie
            if (empty($data['voornaam'])) {
                $data['errors'][] = 'Voornaam is verplicht';
            }

            if (empty($data['achternaam'])) {
                $data['errors'][] = 'Achternaam is verplicht';
            }

            if (empty($data['geboortedatum'])) {
                $data['errors'][] = 'Geboortedatum is verplicht';
            } else {
                $birthDate = new DateTime($data['geboortedatum']);
                $today = new DateTime();
                $age = $today->diff($birthDate)->y;
                
                if ($age < 12) {
                    $data['errors'][] = 'Je moet minimaal 12 jaar oud zijn om deel te nemen';
                }
            }

            if (empty($data['telefoonnummer'])) {
                $data['errors'][] = 'Telefoonnummer is verplicht';
            }

            if (empty($data['locatie_id'])) {
                $data['errors'][] = 'Locatie is verplicht';
            }

            if ($data['aantal_personen'] < 1 || $data['aantal_personen'] > $lespakket->max_personen) {
                $data['errors'][] = "Aantal personen moet tussen 1 en {$lespakket->max_personen} zijn";
            }

            if (empty($data['gewenste_datum'])) {
                $data['errors'][] = 'Gewenste datum is verplicht';
            } else {
                $gewensteDatum = new DateTime($data['gewenste_datum']);
                $vandaag = new DateTime();
                
                if ($gewensteDatum <= $vandaag) {
                    $data['errors'][] = 'Gewenste datum moet in de toekomst liggen';
                }
            }

            // Als er geen fouten zijn, maak reservering aan
            if (empty($data['errors'])) {
                // Eerst persoon aanmaken of ophalen
                $persoonId = $this->persoonModel->createPersoon(
                    $_SESSION['user_id'],
                    $data['voornaam'],
                    $data['tussenvoegsel'],
                    $data['achternaam'],
                    $data['geboortedatum'],
                    $data['telefoonnummer']
                );

                if ($persoonId) {
                    // Totale prijs berekenen
                    $totalePrijs = $lespakket->prijs_per_persoon * $data['aantal_personen'];

                    // Reservering aanmaken
                    $reserveringId = $this->reserveringModel->createReservering([
                        'persoon_id' => $persoonId,
                        'lespakket_id' => $lespakketId,
                        'locatie_id' => $data['locatie_id'],
                        'aantal_personen' => $data['aantal_personen'],
                        'totale_prijs' => $totalePrijs,
                        'gewenste_datum' => $data['gewenste_datum'],
                        'opmerkingen' => $data['opmerkingen']
                    ]);

                    if ($reserveringId) {
                        flash('message', 'Reservering succesvol aangemaakt! We nemen binnenkort contact met je op.', 'alert alert-success');
                        redirect('klant/dashboard');
                    } else {
                        $data['errors'][] = 'Er is een fout opgetreden bij het aanmaken van de reservering';
                    }
                } else {
                    $data['errors'][] = 'Er is een fout opgetreden bij het verwerken van je gegevens';
                }
            }

            $this->view('reservering/nieuw', $data);
        } else {
            // GET request - toon formulier
            $data = [
                'lespakket' => $lespakket,
                'locaties' => $this->locatieModel->getAllLocaties(),
                'voornaam' => '',
                'tussenvoegsel' => '',
                'achternaam' => '',
                'geboortedatum' => '',
                'telefoonnummer' => '',
                'locatie_id' => '',
                'aantal_personen' => 1,
                'gewenste_datum' => '',
                'opmerkingen' => '',
                'errors' => []
            ];

            $this->view('reservering/nieuw', $data);
        }
    }

    // Reservering bewerken
    public function bewerken($id)
    {
        $reservering = $this->reserveringModel->getReserveringById($id);
        
        if (!$reservering) {
            flash('message', 'Reservering niet gevonden', 'alert alert-danger');
            redirect('klant/dashboard');
        }

        // Check of de reservering van de ingelogde gebruiker is
        if ($reservering->user_id != $_SESSION['user_id']) {
            flash('message', 'Je hebt geen toegang tot deze reservering', 'alert alert-danger');
            redirect('klant/dashboard');
        }

        // Check of reservering nog bewerkt kan worden (niet bevestigd/gestart)
        if ($reservering->status !== 'aangevraagd') {
            flash('message', 'Deze reservering kan niet meer bewerkt worden', 'alert alert-warning');
            redirect('klant/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF check
            if (!verifyCSRFToken($_POST['csrf_token'])) {
                flash('message', 'Ongeldig formulier. Probeer opnieuw.', 'alert alert-danger');
                redirect("reservering/bewerken/$id");
            }

            $data = [
                'reservering' => $reservering,
                'locaties' => $this->locatieModel->getAllLocaties(),
                'aantal_personen' => (int)$_POST['aantal_personen'],
                'locatie_id' => $_POST['locatie_id'],
                'gewenste_datum' => $_POST['gewenste_datum'],
                'opmerkingen' => trim($_POST['opmerkingen']),
                'errors' => []
            ];

            // Validatie
            if ($data['aantal_personen'] < 1 || $data['aantal_personen'] > $reservering->max_personen) {
                $data['errors'][] = "Aantal personen moet tussen 1 en {$reservering->max_personen} zijn";
            }

            if (empty($data['gewenste_datum'])) {
                $data['errors'][] = 'Gewenste datum is verplicht';
            } else {
                $gewensteDatum = new DateTime($data['gewenste_datum']);
                $vandaag = new DateTime();
                
                if ($gewensteDatum <= $vandaag) {
                    $data['errors'][] = 'Gewenste datum moet in de toekomst liggen';
                }
            }

            if (empty($data['errors'])) {
                // Nieuwe totale prijs berekenen
                $totalePrijs = $reservering->prijs_per_persoon * $data['aantal_personen'];

                $updateData = [
                    'aantal_personen' => $data['aantal_personen'],
                    'locatie_id' => $data['locatie_id'],
                    'totale_prijs' => $totalePrijs,
                    'gewenste_datum' => $data['gewenste_datum'],
                    'opmerkingen' => $data['opmerkingen']
                ];

                if ($this->reserveringModel->updateReservering($id, $updateData)) {
                    flash('message', 'Reservering succesvol bijgewerkt', 'alert alert-success');
                    redirect('klant/dashboard');
                } else {
                    $data['errors'][] = 'Er is een fout opgetreden bij het bijwerken van de reservering';
                }
            }

            $this->view('reservering/bewerken', $data);
        } else {
            $data = [
                'reservering' => $reservering,
                'locaties' => $this->locatieModel->getAllLocaties(),
                'errors' => []
            ];

            $this->view('reservering/bewerken', $data);
        }
    }

    // Reservering annuleren
    public function annuleren($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF check
            if (!verifyCSRFToken($_POST['csrf_token'])) {
                flash('message', 'Ongeldig formulier. Probeer opnieuw.', 'alert alert-danger');
                redirect('klant/dashboard');
            }

            $reservering = $this->reserveringModel->getReserveringById($id);
            
            if (!$reservering) {
                flash('message', 'Reservering niet gevonden', 'alert alert-danger');
                redirect('klant/dashboard');
            }

            // Check of de reservering van de ingelogde gebruiker is
            if ($reservering->user_id != $_SESSION['user_id']) {
                flash('message', 'Je hebt geen toegang tot deze reservering', 'alert alert-danger');
                redirect('klant/dashboard');
            }

            // Check of reservering geannuleerd kan worden
            if ($reservering->status === 'geannuleerd') {
                flash('message', 'Deze reservering is al geannuleerd', 'alert alert-warning');
                redirect('klant/dashboard');
            }

            if ($reservering->status === 'afgerond') {
                flash('message', 'Een afgeronde reservering kan niet geannuleerd worden', 'alert alert-danger');
                redirect('klant/dashboard');
            }

            if ($this->reserveringModel->updateReservering($id, ['status' => 'geannuleerd'])) {
                flash('message', 'Reservering succesvol geannuleerd', 'alert alert-success');
            } else {
                flash('message', 'Er is een fout opgetreden bij het annuleren van de reservering', 'alert alert-danger');
            }
        }

        redirect('klant/dashboard');
    }
}