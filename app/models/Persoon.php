<?php

class Persoon
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Voeg persoon toe of update bestaande
    public function savePersoon($data)
    {
        // Check of persoon al bestaat
        $this->db->query("SELECT id FROM personen WHERE user_id = :user_id");
        $this->db->bind(':user_id', $data['user_id']);
        $existing = $this->db->single();

        if ($existing) {
            // Update bestaande persoon
            $this->db->query("UPDATE personen SET 
                              voornaam = :voornaam, 
                              achternaam = :achternaam, 
                              adres = :adres, 
                              postcode = :postcode, 
                              woonplaats = :woonplaats, 
                              geboortedatum = :geboortedatum, 
                              telefoon = :telefoon, 
                              bsn = :bsn 
                              WHERE user_id = :user_id");
        } else {
            // Nieuwe persoon
            $this->db->query("INSERT INTO personen (user_id, voornaam, achternaam, adres, postcode, woonplaats, geboortedatum, telefoon, bsn) 
                              VALUES (:user_id, :voornaam, :achternaam, :adres, :postcode, :woonplaats, :geboortedatum, :telefoon, :bsn)");
        }

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':voornaam', $data['voornaam']);
        $this->db->bind(':achternaam', $data['achternaam']);
        $this->db->bind(':adres', $data['adres'] ?? null);
        $this->db->bind(':postcode', $data['postcode'] ?? null);
        $this->db->bind(':woonplaats', $data['woonplaats'] ?? null);
        $this->db->bind(':geboortedatum', $data['geboortedatum'] ?? null);
        $this->db->bind(':telefoon', $data['telefoon'] ?? null);
        $this->db->bind(':bsn', $data['bsn'] ?? null);

        return $this->db->execute();
    }

    // Haal persoon op
    public function getPersoonByUserId($userId)
    {
        $this->db->query("SELECT * FROM personen WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    // Haal alle klanten op
    public function getKlanten()
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.telefoon, p.woonplaats 
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          WHERE u.role = 'klant' AND u.is_active = 1
                          ORDER BY p.voornaam, p.achternaam");
        return $this->db->resultSet();
    }

    // Haal alle instructeurs op
    public function getInstructeurs()
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.telefoon, p.woonplaats, p.bsn 
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          WHERE u.role = 'instructeur' AND u.is_active = 1
                          ORDER BY p.voornaam, p.achternaam");
        return $this->db->resultSet();
    }

    // Verwijder persoon
    public function deletePersoon($userId)
    {
        $this->db->query("DELETE FROM personen WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Maak nieuwe persoon aan voor reservering
    public function createPersoon($userId, $voornaam, $tussenvoegsel, $achternaam, $geboortedatum, $telefoon)
    {
        // Check of persoon al bestaat
        $this->db->query("SELECT id FROM personen WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $existing = $this->db->single();

        if ($existing) {
            // Update bestaande persoon
            $this->db->query("UPDATE personen SET 
                              voornaam = :voornaam, 
                              tussenvoegsel = :tussenvoegsel, 
                              achternaam = :achternaam, 
                              geboortedatum = :geboortedatum, 
                              telefoon = :telefoon 
                              WHERE user_id = :user_id");
                              
            $this->db->bind(':voornaam', $voornaam);
            $this->db->bind(':tussenvoegsel', $tussenvoegsel);
            $this->db->bind(':achternaam', $achternaam);
            $this->db->bind(':geboortedatum', $geboortedatum);
            $this->db->bind(':telefoon', $telefoon);
            $this->db->bind(':user_id', $userId);

            if ($this->db->execute()) {
                return $existing->id;
            }
        } else {
            // Maak nieuwe persoon aan
            $this->db->query("INSERT INTO personen (user_id, voornaam, tussenvoegsel, achternaam, geboortedatum, telefoon) 
                              VALUES (:user_id, :voornaam, :tussenvoegsel, :achternaam, :geboortedatum, :telefoon)");
                              
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':voornaam', $voornaam);
            $this->db->bind(':tussenvoegsel', $tussenvoegsel);
            $this->db->bind(':achternaam', $achternaam);
            $this->db->bind(':geboortedatum', $geboortedatum);
            $this->db->bind(':telefoon', $telefoon);

            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            }
        }

        return false;
    }
}