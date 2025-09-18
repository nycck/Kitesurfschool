<?php

class Reservering
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Maak nieuwe reservering
    public function createReservering($data)
    {
        $this->db->query("INSERT INTO reserveringen (klant_id, lespakket_id, locatie_id, totaal_prijs, duo_partner_naam, duo_partner_email, duo_partner_telefoon, opmerkingen) 
                          VALUES (:klant_id, :lespakket_id, :locatie_id, :totaal_prijs, :duo_partner_naam, :duo_partner_email, :duo_partner_telefoon, :opmerkingen)");
        
        $this->db->bind(':klant_id', $data['klant_id']);
        $this->db->bind(':lespakket_id', $data['lespakket_id']);
        $this->db->bind(':locatie_id', $data['locatie_id']);
        $this->db->bind(':totaal_prijs', $data['totaal_prijs']);
        $this->db->bind(':duo_partner_naam', $data['duo_partner_naam'] ?? null);
        $this->db->bind(':duo_partner_email', $data['duo_partner_email'] ?? null);
        $this->db->bind(':duo_partner_telefoon', $data['duo_partner_telefoon'] ?? null);
        $this->db->bind(':opmerkingen', $data['opmerkingen'] ?? null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // Voeg les sessie toe aan reservering
    public function addLesSessie($reserveringId, $lesDatum, $startTijd, $eindTijd)
    {
        $this->db->query("INSERT INTO les_sessies (reservering_id, les_datum, start_tijd, eind_tijd) 
                          VALUES (:reservering_id, :les_datum, :start_tijd, :eind_tijd)");
        
        $this->db->bind(':reservering_id', $reserveringId);
        $this->db->bind(':les_datum', $lesDatum);
        $this->db->bind(':start_tijd', $startTijd);
        $this->db->bind(':eind_tijd', $eindTijd);
        
        return $this->db->execute();
    }

    // Haal reserveringen op van klant
    public function getReserveringenByKlant($klantId, $withDetails = false)
    {
        if ($withDetails) {
            $this->db->query("SELECT r.*, lp.naam as pakket_naam, l.naam as locatie_naam, 
                              p.voornaam, p.achternaam,
                              COUNT(ls.id) as aantal_lessen_gepland,
                              SUM(CASE WHEN ls.status = 'voltooid' THEN 1 ELSE 0 END) as aantal_lessen_voltooid
                              FROM reserveringen r
                              LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                              LEFT JOIN locaties l ON r.locatie_id = l.id
                              LEFT JOIN users u ON r.klant_id = u.id
                              LEFT JOIN personen p ON u.id = p.user_id
                              LEFT JOIN les_sessies ls ON r.id = ls.reservering_id
                              WHERE r.klant_id = :klant_id
                              GROUP BY r.id
                              ORDER BY r.created_at DESC");
        } else {
            $this->db->query("SELECT r.*, lp.naam as pakket_naam, l.naam as locatie_naam 
                              FROM reserveringen r
                              LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                              LEFT JOIN locaties l ON r.locatie_id = l.id
                              WHERE r.klant_id = :klant_id
                              ORDER BY r.created_at DESC");
        }
        
        $this->db->bind(':klant_id', $klantId);
        return $this->db->resultSet();
    }

    // Haal reservering op met ID en klant verificatie
    public function getReserveringByIdAndKlant($id, $klantId)
    {
        $this->db->query("SELECT r.*, lp.naam as pakket_naam, lp.beschrijving as pakket_beschrijving,
                          l.naam as locatie_naam, l.adres as locatie_adres,
                          p.voornaam, p.achternaam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN users u ON r.klant_id = u.id
                          LEFT JOIN personen p ON u.id = p.user_id
                          WHERE r.id = :id AND r.klant_id = :klant_id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':klant_id', $klantId);
        return $this->db->single();
    }

    // Haal lessen op van reservering
    public function getLessenByReservering($reserveringId)
    {
        $this->db->query("SELECT ls.*, 
                          pi.voornaam as instructeur_voornaam, pi.achternaam as instructeur_achternaam
                          FROM les_sessies ls
                          LEFT JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN users ui ON r.instructeur_id = ui.id
                          LEFT JOIN personen pi ON ui.id = pi.user_id
                          WHERE ls.reservering_id = :reservering_id
                          ORDER BY ls.les_datum ASC, ls.start_tijd ASC");
        
        $this->db->bind(':reservering_id', $reserveringId);
        return $this->db->resultSet();
    }

    // Haal aankomende lessen op voor klant
    public function getAankomendeLessen($klantId, $limit = 10)
    {
        $this->db->query("SELECT ls.*, r.id as reservering_id,
                          lp.naam as pakket_naam, l.naam as locatie_naam,
                          pi.voornaam as instructeur_voornaam, pi.achternaam as instructeur_achternaam
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN users ui ON r.instructeur_id = ui.id
                          LEFT JOIN personen pi ON ui.id = pi.user_id
                          WHERE r.klant_id = :klant_id 
                          AND ls.les_datum >= CURDATE() 
                          AND ls.status = 'gepland'
                          ORDER BY ls.les_datum ASC, ls.start_tijd ASC
                          LIMIT :limit");
        
        $this->db->bind(':klant_id', $klantId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Tel voltooide lessen voor klant
    public function getVoltooidelessenCount($klantId)
    {
        $this->db->query("SELECT COUNT(*) as count 
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          WHERE r.klant_id = :klant_id AND ls.status = 'voltooid'");
        
        $this->db->bind(':klant_id', $klantId);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Markeer als betaald
    public function markAsPaid($reserveringId)
    {
        $this->db->query("UPDATE reserveringen SET betaling_status = 'betaald' WHERE id = :id");
        $this->db->bind(':id', $reserveringId);
        return $this->db->execute();
    }

    // Haal les op bij ID
    public function getLesById($lesId)
    {
        $this->db->query("SELECT ls.*, r.klant_id, lp.naam as pakket_naam, l.naam as locatie_naam
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          WHERE ls.id = :id");
        
        $this->db->bind(':id', $lesId);
        return $this->db->single();
    }

    // Annuleer les
    public function annuleerLes($lesId, $reden)
    {
        $this->db->query("UPDATE les_sessies SET status = 'geannuleerd', annulering_reden = :reden WHERE id = :id");
        $this->db->bind(':id', $lesId);
        $this->db->bind(':reden', $reden);
        return $this->db->execute();
    }

    // Wijs instructeur toe aan reservering
    public function assignInstructeur($reserveringId, $instructeurId)
    {
        $this->db->query("UPDATE reserveringen SET instructeur_id = :instructeur_id WHERE id = :id");
        $this->db->bind(':instructeur_id', $instructeurId);
        $this->db->bind(':id', $reserveringId);
        return $this->db->execute();
    }

    // Update reservering status
    public function updateStatus($reserveringId, $status)
    {
        $this->db->query("UPDATE reserveringen SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $reserveringId);
        return $this->db->execute();
    }

    // Haal alle reserveringen op (voor eigenaar/instructeur)
    public function getAllReserveringen($status = null, $instructeurId = null)
    {
        $whereClause = "WHERE 1=1";
        $bindings = [];
        
        if ($status) {
            $whereClause .= " AND r.status = :status";
            $bindings[':status'] = $status;
        }
        
        if ($instructeurId) {
            $whereClause .= " AND r.instructeur_id = :instructeur_id";
            $bindings[':instructeur_id'] = $instructeurId;
        }
        
        $this->db->query("SELECT r.*, 
                          lp.naam as pakket_naam, l.naam as locatie_naam,
                          pk.voornaam as klant_voornaam, pk.achternaam as klant_achternaam,
                          pi.voornaam as instructeur_voornaam, pi.achternaam as instructeur_achternaam,
                          uk.email as klant_email
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN users uk ON r.klant_id = uk.id
                          LEFT JOIN personen pk ON uk.id = pk.user_id
                          LEFT JOIN users ui ON r.instructeur_id = ui.id
                          LEFT JOIN personen pi ON ui.id = pi.user_id
                          {$whereClause}
                          ORDER BY r.created_at DESC");
        
        foreach ($bindings as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->resultSet();
    }

    // Haal onbetaalde reserveringen op
    public function getUnpaidReservations()
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as pakket_naam, l.naam as locatie_naam,
                          pk.voornaam as klant_voornaam, pk.achternaam as klant_achternaam,
                          uk.email as klant_email
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN users uk ON r.klant_id = uk.id
                          LEFT JOIN personen pk ON uk.id = pk.user_id
                          WHERE r.betaling_status = 'open' OR r.betaling_status = 'betaald'
                          ORDER BY r.created_at DESC");
        
        return $this->db->resultSet();
    }

    // Haal lessen op voor instructeur
    public function getLessenByInstructeur($instructeurId, $startDate = null, $endDate = null)
    {
        $whereClause = "WHERE r.instructeur_id = :instructeur_id";
        $bindings = [':instructeur_id' => $instructeurId];
        
        if ($startDate) {
            $whereClause .= " AND ls.les_datum >= :start_date";
            $bindings[':start_date'] = $startDate;
        }
        
        if ($endDate) {
            $whereClause .= " AND ls.les_datum <= :end_date";
            $bindings[':end_date'] = $endDate;
        }
        
        $this->db->query("SELECT ls.*, r.id as reservering_id,
                          lp.naam as pakket_naam, l.naam as locatie_naam,
                          pk.voornaam as klant_voornaam, pk.achternaam as klant_achternaam,
                          uk.email as klant_email, uk.id as klant_id
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN users uk ON r.klant_id = uk.id
                          LEFT JOIN personen pk ON uk.id = pk.user_id
                          {$whereClause}
                          ORDER BY ls.les_datum ASC, ls.start_tijd ASC");
        
        foreach ($bindings as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->resultSet();
    }
}