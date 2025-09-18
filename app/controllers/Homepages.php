<?php

class Homepages extends BaseController
{
    private $lespakketModel;
    private $locatieModel;

    public function __construct()
    {
        $this->lespakketModel = $this->model('Lespakket');
        $this->locatieModel = $this->model('Locatie');
    }

    public function index($firstname = NULL, $infix = NULL, $lastname = NULL)
    {
        // Haal lespakketten en locaties op voor weergave
        $lespakketten = $this->lespakketModel->getAllActiveLespakketten();
        $locaties = $this->locatieModel->getAllActiveLocaties();

        $data = [
            'title' => 'Kitesurfschool Windkracht-12 - Leer kitesurfen op de Nederlandse kust',
            'lespakketten' => $lespakketten,
            'locaties' => $locaties,
            'description' => 'Leer kitesurfen bij de beste kitesurfschool van Nederland. Professionele instructeurs, veilige lessen en complete uitrusting aan de Nederlandse kust.',
            'keywords' => 'kitesurfen, kitesurfles, windkracht-12, kitesurf school, nederlandse kust, watersport'
        ];

        $this->view('homepages/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Over Kitesurfschool Windkracht-12',
            'description' => 'Ontdek alles over Kitesurfschool Windkracht-12, onze ervaren instructeurs en onze unieke aanpak.'
        ];

        $this->view('homepages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact - Kitesurfschool Windkracht-12',
            'description' => 'Neem contact op met Kitesurfschool Windkracht-12 voor vragen over onze kitesurflessen.'
        ];

        $this->view('homepages/contact', $data);
    }

    public function pakketten()
    {
        $lespakketten = $this->lespakketModel->getAllActiveLespakketten();

        $data = [
            'title' => 'Lespakketten - Kitesurfschool Windkracht-12',
            'lespakketten' => $lespakketten,
            'description' => 'Bekijk al onze kitesurflespakketten: van privélessen tot uitgebreide cursussen voor beginners en gevorderden.'
        ];

        $this->view('homepages/pakketten', $data);
    }

    public function locaties()
    {
        $locaties = $this->locatieModel->getAllActiveLocaties();

        $data = [
            'title' => 'Locaties - Kitesurfschool Windkracht-12',
            'locaties' => $locaties,
            'description' => 'Ontdek onze kitesurflocaties langs de Nederlandse kust: Zandvoort, Scheveningen, Wijk aan Zee en meer.'
        ];

        $this->view('homepages/locaties', $data);
    }

    /**
     * De optellen-method berekent de som van twee getallen
     * We gebruiken deze method voor een unittest
     */
}