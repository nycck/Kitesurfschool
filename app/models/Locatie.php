<?php

class Locatie
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Haal alle actieve locaties op
    public function getAllActiveLocaties()
    {
        $this->db->query("SELECT * FROM locaties WHERE is_active = 1 ORDER BY naam ASC");
        return $this->db->resultSet();
    }

    // Haal locatie op bij ID
    public function getLocatieById($id)
    {
        $this->db->query("SELECT * FROM locaties WHERE id = :id AND is_active = 1");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Voeg nieuwe locatie toe
    public function addLocatie($data)
    {
        $this->db->query("INSERT INTO locaties (naam, adres, beschrijving) VALUES (:naam, :adres, :beschrijving)");
        
        $this->db->bind(':naam', $data['naam']);
        $this->db->bind(':adres', $data['adres']);
        $this->db->bind(':beschrijving', $data['beschrijving']);
        
        return $this->db->execute();
    }

    // Update locatie
    public function updateLocatie($id, $data)
    {
        $this->db->query("UPDATE locaties SET naam = :naam, adres = :adres, beschrijving = :beschrijving WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':naam', $data['naam']);
        $this->db->bind(':adres', $data['adres']);
        $this->db->bind(':beschrijving', $data['beschrijving']);
        
        return $this->db->execute();
    }

    // Deactiveer locatie
    public function deactivateLocatie($id)
    {
        $this->db->query("UPDATE locaties SET is_active = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Activeer locatie
    public function activateLocatie($id)
    {
        $this->db->query("UPDATE locaties SET is_active = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Haal alle locaties op (inclusief inactieve)
    public function getAllLocaties()
    {
        $this->db->query("SELECT * FROM locaties ORDER BY naam ASC");
        return $this->db->resultSet();
    }

    // Haal populairste locatie op
    public function getPopulairsteLocatie()
    {
        $this->db->query("SELECT l.naam, COUNT(r.id) as aantal_reserveringen
                          FROM locaties l
                          LEFT JOIN reserveringen r ON l.id = r.locatie_id
                          GROUP BY l.id, l.naam
                          ORDER BY aantal_reserveringen DESC
                          LIMIT 1");
        $result = $this->db->single();
        return $result ? $result->naam : 'Geen data';
    }
}