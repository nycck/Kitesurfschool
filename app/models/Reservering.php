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

    // Maak nieuwe reservering door instructeur (direct bevestigd)
    public function createReserveringByInstructeur($data)
    {
        $this->db->query("INSERT INTO reserveringen (persoon_id, instructeur_id, lespakket_id, locatie_id, bevestigde_datum, bevestigde_tijd, status) 
                          VALUES (:persoon_id, :instructeur_id, :lespakket_id, :locatie_id, :bevestigde_datum, :bevestigde_tijd, :status)");
        
        $this->db->bind(':persoon_id', $data['persoon_id']);
        $this->db->bind(':instructeur_id', $data['instructeur_id']);
        $this->db->bind(':lespakket_id', $data['lespakket_id']);
        $this->db->bind(':locatie_id', $data['locatie_id']);
        $this->db->bind(':bevestigde_datum', $data['bevestigde_datum']);
        $this->db->bind(':bevestigde_tijd', $data['bevestigde_tijd']);
        $this->db->bind(':status', $data['status']);
        
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

    // Haal reserveringen op van klant (user_id)
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
                              LEFT JOIN personen p ON r.persoon_id = p.id
                              LEFT JOIN les_sessies ls ON r.id = ls.reservering_id
                              WHERE p.user_id = :klant_id
                              GROUP BY r.id
                              ORDER BY r.aangemaakt_op DESC");
        } else {
            $this->db->query("SELECT r.*, lp.naam as pakket_naam, l.naam as locatie_naam 
                              FROM reserveringen r
                              LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                              LEFT JOIN locaties l ON r.locatie_id = l.id
                              LEFT JOIN personen p ON r.persoon_id = p.id
                              WHERE p.user_id = :klant_id
                              ORDER BY r.aangemaakt_op DESC");
        }
        
        $this->db->bind(':klant_id', $klantId);
        return $this->db->resultSet();
    }

    // Haal reservering op met ID en klant verificatie (user_id)
    public function getReserveringByIdAndKlant($id, $klantId)
    {
        $this->db->query("SELECT r.*, lp.naam as pakket_naam, lp.beschrijving as pakket_beschrijving, lp.prijs_per_persoon,
                          l.naam as locatie_naam, l.adres as locatie_adres,
                          p.voornaam, p.achternaam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.id = :id AND p.user_id = :klant_id");
        
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

    // Haal aankomende lessen op voor klant (user_id)
    public function getAankomendeLessen($klantId, $limit = 10)
    {
        $this->db->query("SELECT ls.*, r.id as reservering_id,
                          lp.naam as pakket_naam, l.naam as locatie_naam,
                          pi.voornaam as instructeur_voornaam, pi.achternaam as instructeur_achternaam
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          LEFT JOIN personen pi ON r.instructeur_id = pi.id
                          WHERE p.user_id = :klant_id 
                          AND ls.les_datum >= CURDATE() 
                          AND ls.status = 'gepland'
                          ORDER BY ls.les_datum ASC, ls.start_tijd ASC
                          LIMIT :limit");
        
        $this->db->bind(':klant_id', $klantId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Tel voltooide lessen voor klant (user_id)
    public function getVoltooidelessenCount($klantId)
    {
        $this->db->query("SELECT COUNT(*) as count 
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE p.user_id = :klant_id AND ls.status = 'voltooid'");
        
        $this->db->bind(':klant_id', $klantId);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Markeer als betaald
    public function markAsPaid($reserveringId)
    {
        $this->db->query("UPDATE reserveringen SET betaal_status = 'betaald' WHERE id = :id");
        $this->db->bind(':id', $reserveringId);
        return $this->db->execute();
    }

    // Haal les op bij ID
    public function getLesById($lesId)
    {
        $this->db->query("SELECT ls.*, p.user_id as klant_id, lp.naam as pakket_naam, l.naam as locatie_naam
                          FROM les_sessies ls
                          JOIN reserveringen r ON ls.reservering_id = r.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
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
                          LEFT JOIN personen pk ON r.persoon_id = pk.id
                          LEFT JOIN users uk ON pk.user_id = uk.id
                          LEFT JOIN personen pi ON r.instructeur_id = pi.id
                          LEFT JOIN users ui ON pi.user_id = ui.id
                          {$whereClause}
                          ORDER BY r.aangemaakt_op DESC");
        
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
                          LEFT JOIN personen pk ON r.persoon_id = pk.id
                          LEFT JOIN users uk ON pk.user_id = uk.id
                          WHERE r.betaal_status IN ('wachtend', 'mislukt')
                          ORDER BY r.aangemaakt_op DESC");
        
        return $this->db->resultSet();
    }

    // Haal lessen op voor instructeur
    public function getLessenByInstructeur($instructeurId, $startDate = null, $endDate = null)
    {
        $whereClause = "WHERE r.instructeur_id = :instructeur_id";
        $bindings = [':instructeur_id' => $instructeurId];
        
        if ($startDate) {
            $whereClause .= " AND r.bevestigde_datum >= :start_date";
            $bindings[':start_date'] = $startDate;
        }
        
        if ($endDate) {
            $whereClause .= " AND r.bevestigde_datum <= :end_date";
            $bindings[':end_date'] = $endDate;
        }
        
        $this->db->query("SELECT r.*, r.id as reservering_id,
                          lp.naam as pakket_naam, l.naam as locatie_naam,
                          pk.voornaam as klant_voornaam, pk.achternaam as klant_achternaam,
                          uk.email as klant_email, uk.id as klant_id
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen pk ON r.persoon_id = pk.id
                          LEFT JOIN users uk ON pk.user_id = uk.id
                          {$whereClause}
                          ORDER BY r.bevestigde_datum ASC, r.bevestigde_tijd ASC");
        
        foreach ($bindings as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->resultSet();
    }

    // NIEUWE METHODEN VOOR UITGEBREIDE FUNCTIONALITEIT

    // Voeg nieuwe reservering toe (nieuwe versie voor uitgebreid systeem)
    public function addReservering($data)
    {
        $this->db->query("INSERT INTO reserveringen (persoon_id, lespakket_id, locatie_id, gewenste_datum, opmerking, duo_partner_id, status, betaal_status, aangemaakt_op) 
                          VALUES (:persoon_id, :lespakket_id, :locatie_id, :gewenste_datum, :opmerking, :duo_partner_id, :status, :betaal_status, NOW())");
        
        $this->db->bind(':persoon_id', $data['persoon_id']);
        $this->db->bind(':lespakket_id', $data['lespakket_id']);
        $this->db->bind(':locatie_id', $data['locatie_id']);
        $this->db->bind(':gewenste_datum', $data['gewenste_datum']);
        $this->db->bind(':opmerking', $data['opmerking']);
        $this->db->bind(':duo_partner_id', $data['duo_partner_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':betaal_status', $data['betaal_status']);
        
        return $this->db->execute();
    }

    // Haal reserveringen op van gebruiker (nieuwe versie)
    public function getReserveringenByUserId($userId)
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as lespakket_naam, lp.prijs_per_persoon as lespakket_prijs,
                          l.naam as locatie_naam,
                          CONCAT(dp.voornaam, ' ', dp.achternaam) as duo_partner_naam,
                          p.voornaam, p.achternaam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          LEFT JOIN personen dp ON r.duo_partner_id = dp.id
                          WHERE p.user_id = :user_id
                          ORDER BY r.aangemaakt_op DESC");
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Haal reservering op met ID
    public function getReserveringById($id)
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as lespakket_naam, lp.beschrijving as lespakket_beschrijving, 
                          lp.prijs as lespakket_prijs, lp.totale_uren as lespakket_duur,
                          lp.aantal_lessen as lespakket_aantal_lessen, lp.max_personen as lespakket_max_personen,
                          l.naam as locatie_naam, l.adres as locatie_adres, l.faciliteiten as locatie_faciliteiten,
                          CONCAT(p.voornaam, ' ', p.achternaam) as persoon_naam,
                          CONCAT(dp.voornaam, ' ', dp.achternaam) as duo_partner_naam,
                          CONCAT(ip.voornaam, ' ', ip.achternaam) as instructeur_naam,
                          u.id as user_id
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          LEFT JOIN users u ON p.user_id = u.id
                          LEFT JOIN personen dp ON r.duo_partner_id = dp.id
                          LEFT JOIN personen ip ON r.instructeur_id = ip.id
                          WHERE r.id = :id");
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update reservering status
    public function updateReserveringStatus($id, $status, $reden = null)
    {
        $query = "UPDATE reserveringen SET status = :status, bijgewerkt_op = NOW()";
        if ($reden) {
            $query .= ", annulering_reden = :reden";
        }
        $query .= " WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        if ($reden) {
            $this->db->bind(':reden', $reden);
        }
        
        return $this->db->execute();
    }

    // Bevestig reservering
    public function bevestigReservering($id, $data)
    {
        $this->db->query("UPDATE reserveringen SET 
                          bevestigde_datum = :bevestigde_datum,
                          bevestigde_tijd = :bevestigde_tijd,
                          instructeur_id = :instructeur_id,
                          status = :status,
                          instructeur_opmerking = :instructeur_opmerking,
                          bijgewerkt_op = NOW()
                          WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':bevestigde_datum', $data['bevestigde_datum']);
        $this->db->bind(':bevestigde_tijd', $data['bevestigde_tijd']);
        $this->db->bind(':instructeur_id', $data['instructeur_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':instructeur_opmerking', $data['instructeur_opmerking']);
        
        return $this->db->execute();
    }

    // Rond les af
    public function rondLesAf($id, $data)
    {
        $this->db->query("UPDATE reserveringen SET 
                          status = :status,
                          evaluatie = :evaluatie,
                          voortgang = :voortgang,
                          aanbevelingen = :aanbevelingen,
                          bijgewerkt_op = NOW()
                          WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':evaluatie', $data['evaluatie']);
        $this->db->bind(':voortgang', $data['voortgang']);
        $this->db->bind(':aanbevelingen', $data['aanbevelingen']);
        
        return $this->db->execute();
    }

    // Update les evaluatie (inclusief instructeur opmerking)
    public function updateLesEvaluatie($data)
    {
        $this->db->query("UPDATE reserveringen SET 
                          status = :status,
                          evaluatie = :evaluatie,
                          voortgang = :voortgang,
                          aanbevelingen = :aanbevelingen,
                          instructeur_opmerking = :instructeur_opmerking,
                          bijgewerkt_op = NOW()
                          WHERE id = :id");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':evaluatie', $data['evaluatie']);
        $this->db->bind(':voortgang', $data['voortgang']);
        $this->db->bind(':aanbevelingen', $data['aanbevelingen']);
        $this->db->bind(':instructeur_opmerking', $data['instructeur_opmerking']);
        
        return $this->db->execute();
    }

    // Haal beschikbaarheid op voor datum/locatie
    public function getBeschikbaarheidByDate($locatie_id, $datum)
    {
        // Simuleer beschikbaarheid check
        return [];
    }

    // Haal aankomende lessen op voor instructeur
    public function getAankomendeLessenByInstructeur($instructeur_id)
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as lespakket_naam,
                          l.naam as locatie_naam,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.instructeur_id = :instructeur_id 
                          AND r.bevestigde_datum >= CURDATE()
                          AND r.status IN ('bevestigd')
                          ORDER BY r.bevestigde_datum ASC, r.bevestigde_tijd ASC
                          LIMIT 5");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        return $this->db->resultSet();
    }

    // Haal lessen vandaag op voor instructeur
    public function getLessenVandaagByInstructeur($instructeur_id)
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as lespakket_naam,
                          l.naam as locatie_naam,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.instructeur_id = :instructeur_id 
                          AND DATE(r.bevestigde_datum) = CURDATE()
                          AND r.status IN ('bevestigd')
                          ORDER BY r.bevestigde_tijd ASC");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        return $this->db->resultSet();
    }

    // Haal klanten op van instructeur
    public function getKlantenByInstructeur($instructeur_id)
    {
        $this->db->query("SELECT DISTINCT p.*, u.email,
                          COUNT(r.id) as totaal_lessen,
                          MAX(r.bevestigde_datum) as laatste_les
                          FROM reserveringen r
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          LEFT JOIN users u ON p.user_id = u.id
                          WHERE r.instructeur_id = :instructeur_id
                          GROUP BY p.id, u.email
                          ORDER BY p.achternaam ASC");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        return $this->db->resultSet();
    }

    // Haal totaal klanten op van instructeur
    public function getTotaalKlantenByInstructeur($instructeur_id)
    {
        $this->db->query("SELECT COUNT(DISTINCT r.persoon_id) as count
                          FROM reserveringen r
                          WHERE r.instructeur_id = :instructeur_id");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Update betalingsstatus
    public function updateBetalingStatus($id, $status, $opmerking = null)
    {
        $query = "UPDATE reserveringen SET betaal_status = :status, bijgewerkt_op = NOW()";
        if ($opmerking) {
            $query .= ", betaal_opmerking = :opmerking";
        }
        $query .= " WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        if ($opmerking) {
            $this->db->bind(':opmerking', $opmerking);
        }
        
        return $this->db->execute();
    }

    // Statistieken methoden
    public function getTotaalReserveringen()
    {
        $this->db->query("SELECT COUNT(*) as count FROM reserveringen");
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getOmzetDezeMaand()
    {
        $this->db->query("SELECT SUM(lp.prijs) as omzet
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          WHERE r.betaal_status = 'betaald'
                          AND MONTH(r.aangemaakt_op) = MONTH(CURDATE())
                          AND YEAR(r.aangemaakt_op) = YEAR(CURDATE())");
        
        $result = $this->db->single();
        return $result->omzet ?? 0;
    }

    // Haal lessen op per view (dag/week/maand)
    public function getLessenByDag($instructeur_id, $datum)
    {
        $this->db->query("SELECT r.*, 
                          r.bevestigde_datum as datum,
                          r.bevestigde_tijd as tijd,
                          lp.naam as pakket_naam,
                          l.naam as locatie_naam,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.instructeur_id = :instructeur_id 
                          AND r.bevestigde_datum IS NOT NULL
                          AND DATE(r.bevestigde_datum) = :datum
                          AND r.status != 'geannuleerd'
                          ORDER BY r.bevestigde_tijd ASC");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        $this->db->bind(':datum', $datum);
        return $this->db->resultSet();
    }

    public function getLessenByWeek($instructeur_id, $datum)
    {
        // Bereken maandag en zondag van de week
        $this->db->query("SELECT r.*, 
                          r.bevestigde_datum as datum,
                          r.bevestigde_tijd as tijd,
                          lp.naam as pakket_naam,
                          l.naam as locatie_naam,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.instructeur_id = :instructeur_id 
                          AND r.bevestigde_datum IS NOT NULL
                          AND r.bevestigde_datum >= DATE_SUB(:datum1, INTERVAL WEEKDAY(:datum2) DAY)
                          AND r.bevestigde_datum <= DATE_ADD(DATE_SUB(:datum3, INTERVAL WEEKDAY(:datum4) DAY), INTERVAL 6 DAY)
                          AND r.status != 'geannuleerd'
                          ORDER BY r.bevestigde_datum ASC, r.bevestigde_tijd ASC");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        $this->db->bind(':datum1', $datum);
        $this->db->bind(':datum2', $datum);
        $this->db->bind(':datum3', $datum);
        $this->db->bind(':datum4', $datum);
        return $this->db->resultSet();
    }

    public function getLessenByMaand($instructeur_id, $datum)
    {
        $this->db->query("SELECT r.*, 
                          r.bevestigde_datum as datum,
                          r.bevestigde_tijd as tijd,
                          lp.naam as pakket_naam,
                          l.naam as locatie_naam,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.instructeur_id = :instructeur_id 
                          AND r.bevestigde_datum IS NOT NULL
                          AND MONTH(r.bevestigde_datum) = MONTH(:datum1)
                          AND YEAR(r.bevestigde_datum) = YEAR(:datum2)
                          AND r.status != 'geannuleerd'
                          ORDER BY r.bevestigde_datum ASC, r.bevestigde_tijd ASC");
        
        $this->db->bind(':instructeur_id', $instructeur_id);
        $this->db->bind(':datum1', $datum);
        $this->db->bind(':datum2', $datum);
        return $this->db->resultSet();
    }

    // Extra methoden voor instructeur/eigenaar statistieken
    public function getTotaalLessenGegeven($instructeur_id)
    {
        $this->db->query("SELECT COUNT(*) as count FROM reserveringen 
                          WHERE instructeur_id = :instructeur_id AND status = 'afgerond'");
        $this->db->bind(':instructeur_id', $instructeur_id);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getLessenDezeMaand($instructeur_id)
    {
        $this->db->query("SELECT COUNT(*) as count FROM reserveringen 
                          WHERE instructeur_id = :instructeur_id 
                          AND MONTH(bevestigde_datum) = MONTH(CURDATE())
                          AND YEAR(bevestigde_datum) = YEAR(CURDATE())");
        $this->db->bind(':instructeur_id', $instructeur_id);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getGemiddeldeBeoordelingInstructeur($instructeur_id)
    {
        // Simulatie - in werkelijkheid zou dit uit een beoordelingen tabel komen
        return 4.2;
    }

    public function getNieuweKlantenDezeMaand($instructeur_id)
    {
        $this->db->query("SELECT COUNT(DISTINCT persoon_id) as count FROM reserveringen 
                          WHERE instructeur_id = :instructeur_id 
                          AND MONTH(aangemaakt_op) = MONTH(CURDATE())
                          AND YEAR(aangemaakt_op) = YEAR(CURDATE())");
        $this->db->bind(':instructeur_id', $instructeur_id);
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getVoltooidelessenDezeMaand()
    {
        $this->db->query("SELECT COUNT(*) as count FROM reserveringen 
                          WHERE status = 'afgerond'
                          AND MONTH(bijgewerkt_op) = MONTH(CURDATE())
                          AND YEAR(bijgewerkt_op) = YEAR(CURDATE())");
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getGemiddeldeBeoordeling()
    {
        // Simulatie - in werkelijkheid zou dit uit een beoordelingen tabel komen
        return 4.5;
    }

    public function getVoltooideReserveringen()
    {
        $this->db->query("SELECT COUNT(*) as count FROM reserveringen WHERE status = 'afgerond'");
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getAlleBetalingen($filter = 'alle', $maand = null)
    {
        $whereClause = "WHERE 1=1";
        
        if ($filter !== 'alle') {
            $whereClause .= " AND r.betaal_status = :filter";
        }
        
        if ($maand) {
            $whereClause .= " AND DATE_FORMAT(r.aangemaakt_op, '%Y-%m') = :maand";
        }
        
        $this->db->query("SELECT r.*, r.id as reservering_id,
                          lp.naam as lespakket_naam, lp.prijs_per_persoon as bedrag,
                          l.naam as locatie_naam,
                          p.voornaam as klant_voornaam, p.achternaam as klant_achternaam,
                          u.email as klant_email
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN locaties l ON r.locatie_id = l.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          LEFT JOIN users u ON p.user_id = u.id
                          {$whereClause}
                          ORDER BY r.aangemaakt_op DESC");
        
        if ($filter !== 'alle') {
            $this->db->bind(':filter', $filter);
        }
        
        if ($maand) {
            $this->db->bind(':maand', $maand);
        }
        
        return $this->db->resultSet();
    }

    public function getTotaalOmzet($maand = null)
    {
        $whereClause = "WHERE r.betaal_status = 'betaald'";
        
        if ($maand) {
            $whereClause .= " AND DATE_FORMAT(r.aangemaakt_op, '%Y-%m') = :maand";
        }
        
        $this->db->query("SELECT SUM(lp.prijs) as omzet
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          {$whereClause}");
        
        if ($maand) {
            $this->db->bind(':maand', $maand);
        }
        
        $result = $this->db->single();
        return $result->omzet ?? 0;
    }

    public function getOpenstaandeBetalingen()
    {
        $this->db->query("SELECT SUM(lp.prijs) as bedrag
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          WHERE r.betaal_status = 'wachtend'");
        
        $result = $this->db->single();
        return $result->bedrag ?? 0;
    }

    public function getNieuweReserveringen($limit = 5)
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as lespakket_naam,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          ORDER BY r.aangemaakt_op DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getRecenteBetalingen($limit = 5)
    {
        $this->db->query("SELECT r.*, 
                          lp.naam as lespakket_naam, lp.prijs,
                          CONCAT(p.voornaam, ' ', p.achternaam) as klant_naam
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          LEFT JOIN personen p ON r.persoon_id = p.id
                          WHERE r.betaal_status = 'betaald'
                          ORDER BY r.bijgewerkt_op DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getOmzetRapport($periode, $datum)
    {
        // Voorbeeld implementatie voor omzet rapport
        $this->db->query("SELECT DATE(r.aangemaakt_op) as datum, 
                                 COUNT(*) as aantal_betalingen,
                                 SUM(lp.prijs) as omzet
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          WHERE r.betaal_status = 'betaald'
                          AND MONTH(r.aangemaakt_op) = MONTH(:datum)
                          AND YEAR(r.aangemaakt_op) = YEAR(:datum)
                          GROUP BY DATE(r.aangemaakt_op)
                          ORDER BY datum ASC");
        $this->db->bind(':datum', $datum);
        
        $dagelijkeOmzet = $this->db->resultSet();
        
        // Bereken totalen
        $totaleOmzet = 0;
        $aantalBetalingen = 0;
        
        foreach ($dagelijkeOmzet as $dag) {
            $totaleOmzet += $dag->omzet ?? 0;
            $aantalBetalingen += $dag->aantal_betalingen ?? 0;
        }
        
        $gemiddeldeBetaling = $aantalBetalingen > 0 ? $totaleOmzet / $aantalBetalingen : 0;
        
        // Bereken openstaand bedrag
        $this->db->query("SELECT SUM(lp.prijs) as openstaand
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          WHERE r.betaal_status = 'wachtend'");
        $openstaandResult = $this->db->single();
        $openstaandBedrag = $openstaandResult->openstaand ?? 0;
        
        return [
            'totale_omzet' => $totaleOmzet,
            'aantal_betalingen' => $aantalBetalingen,
            'gemiddelde_betaling' => $gemiddeldeBetaling,
            'openstaand_bedrag' => $openstaandBedrag,
            'dagelijkse_omzet' => $dagelijkeOmzet
        ];
    }

    public function getLessenRapport($periode, $datum)
    {
        // Voorbeeld implementatie voor lessen rapport
        $this->db->query("SELECT COUNT(*) as totaal_lessen,
                                 SUM(CASE WHEN r.status = 'afgerond' THEN 1 ELSE 0 END) as voltooide_lessen,
                                 SUM(CASE WHEN r.status = 'geannuleerd' THEN 1 ELSE 0 END) as geannuleerde_lessen,
                                 AVG(4.5) as gemiddelde_beoordeling
                          FROM reserveringen r
                          WHERE MONTH(r.aangemaakt_op) = MONTH(:datum)
                          AND YEAR(r.aangemaakt_op) = YEAR(:datum)");
        $this->db->bind(':datum', $datum);
        $totalen = $this->db->single();
        
        // Haal populaire pakketten op
        $this->db->query("SELECT lp.naam, 
                                 COUNT(r.id) as aantal_reserveringen,
                                 SUM(lp.prijs) as omzet
                          FROM reserveringen r
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          WHERE MONTH(r.aangemaakt_op) = MONTH(:datum)
                          AND YEAR(r.aangemaakt_op) = YEAR(:datum)
                          GROUP BY lp.id, lp.naam
                          ORDER BY aantal_reserveringen DESC
                          LIMIT 5");
        $this->db->bind(':datum', $datum);
        $populairePakketten = $this->db->resultSet();
        
        return [
            'totaal_lessen' => $totalen->totaal_lessen ?? 0,
            'voltooide_lessen' => $totalen->voltooide_lessen ?? 0,
            'geannuleerde_lessen' => $totalen->geannuleerde_lessen ?? 0,
            'gemiddelde_beoordeling' => $totalen->gemiddelde_beoordeling ?? 0,
            'populaire_pakketten' => $populairePakketten
        ];
    }

    // Beschikbaarheid methoden
    public function updateInstructeurBeschikbaarheid($data)
    {
        // Simulatie - in werkelijkheid zou dit een beschikbaarheid tabel gebruiken
        return true;
    }

    public function getInstructeurBeschikbaarheid($instructeur_id)
    {
        // Simulatie - in werkelijkheid zou dit uit een beschikbaarheid tabel komen
        return [];
    }
}