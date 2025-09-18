<?php

class Lespakket
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Haal alle actieve lespakketten op
    public function getAllActiveLespakketten()
    {
        $this->db->query("SELECT * FROM lespakketten WHERE is_active = 1 ORDER BY prijs_per_persoon ASC");
        return $this->db->resultSet();
    }

    // Haal lespakket op bij ID
    public function getLespakketById($id)
    {
        $this->db->query("SELECT * FROM lespakketten WHERE id = :id AND is_active = 1");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Voeg nieuw lespakket toe
    public function addLespakket($data)
    {
        $this->db->query("INSERT INTO lespakketten (naam, beschrijving, aantal_lessen, totale_uren, prijs_per_persoon, max_personen) 
                          VALUES (:naam, :beschrijving, :aantal_lessen, :totale_uren, :prijs_per_persoon, :max_personen)");
        
        $this->db->bind(':naam', $data['naam']);
        $this->db->bind(':beschrijving', $data['beschrijving']);
        $this->db->bind(':aantal_lessen', $data['aantal_lessen']);
        $this->db->bind(':totale_uren', $data['totale_uren']);
        $this->db->bind(':prijs_per_persoon', $data['prijs_per_persoon']);
        $this->db->bind(':max_personen', $data['max_personen']);
        
        return $this->db->execute();
    }

    // Update lespakket
    public function updateLespakket($id, $data)
    {
        $this->db->query("UPDATE lespakketten SET 
                          naam = :naam, 
                          beschrijving = :beschrijving, 
                          aantal_lessen = :aantal_lessen, 
                          totale_uren = :totale_uren, 
                          prijs_per_persoon = :prijs_per_persoon, 
                          max_personen = :max_personen 
                          WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':naam', $data['naam']);
        $this->db->bind(':beschrijving', $data['beschrijving']);
        $this->db->bind(':aantal_lessen', $data['aantal_lessen']);
        $this->db->bind(':totale_uren', $data['totale_uren']);
        $this->db->bind(':prijs_per_persoon', $data['prijs_per_persoon']);
        $this->db->bind(':max_personen', $data['max_personen']);
        
        return $this->db->execute();
    }

    // Deactiveer lespakket
    public function deactivateLespakket($id)
    {
        $this->db->query("UPDATE lespakketten SET is_active = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Activeer lespakket
    public function activateLespakket($id)
    {
        $this->db->query("UPDATE lespakketten SET is_active = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Haal alle lespakketten op (inclusief inactieve)
    public function getAllLespakketten()
    {
        $this->db->query("SELECT * FROM lespakketten ORDER BY naam ASC");
        return $this->db->resultSet();
    }
}