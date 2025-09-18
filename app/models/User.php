<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Registreer nieuwe gebruiker
    public function register($email, $activationToken)
    {
        $this->db->query("INSERT INTO users (email, activation_token, role) VALUES (:email, :activation_token, 'klant')");
        $this->db->bind(':email', $email);
        $this->db->bind(':activation_token', $activationToken);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Registreer en activeer gebruiker direct (zonder activatielink)
    public function registerAndActivate($email, $password)
    {
        // Valideer wachtwoord sterkte
        if (!$this->validatePassword($password)) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->db->query("INSERT INTO users (email, password_hash, is_active, role) VALUES (:email, :password_hash, 1, 'klant')");
        $this->db->bind(':email', $email);
        $this->db->bind(':password_hash', $passwordHash);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Activeer gebruiker account
    public function activateUser($token, $password)
    {
        // Valideer wachtwoord sterkte
        if (!$this->validatePassword($password)) {
            return ['success' => false, 'message' => 'Wachtwoord voldoet niet aan de eisen: minimaal 12 tekens, hoofdletter, cijfer en speciaal teken (@, #, etc.)'];
        }

        $this->db->query("SELECT id FROM users WHERE activation_token = :token AND is_active = 0");
        $this->db->bind(':token', $token);
        $user = $this->db->single();

        if ($user) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $this->db->query("UPDATE users SET password_hash = :password, is_active = 1, activation_token = NULL WHERE id = :id");
            $this->db->bind(':password', $passwordHash);
            $this->db->bind(':id', $user->id);
            
            if ($this->db->execute()) {
                return ['success' => true, 'user_id' => $user->id];
            }
        }
        
        return ['success' => false, 'message' => 'Ongeldige activatielink'];
    }

    // Valideer wachtwoord sterkte
    private function validatePassword($password)
    {
        // Minimaal 12 tekens
        if (strlen($password) < 12) return false;
        
        // Bevat hoofdletter
        if (!preg_match('/[A-Z]/', $password)) return false;
        
        // Bevat cijfer
        if (!preg_match('/[0-9]/', $password)) return false;
        
        // Bevat speciaal teken
        if (!preg_match('/[@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) return false;
        
        return true;
    }

    // Login gebruiker
    public function login($email, $password)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $user = $this->db->single();

        if ($user && password_verify($password, $user->password_hash)) {
            if ($user->is_active) {
                // Log the login
                $this->logUserActivity($user->id, $email, 'login');
                return $user;
            }
        }
        return false;
    }

    // Log logout
    public function logout($userId, $email)
    {
        // Log the logout
        $this->logUserActivity($userId, $email, 'logout');
    }

    private function logUserActivity($userId, $email, $action)
    {
        $this->db->query("INSERT INTO login_logs (user_id, email, actie, ip_adres, user_agent) 
                          VALUES (:user_id, :email, :actie, :ip_adres, :user_agent)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':email', $email);
        $this->db->bind(':actie', $action);
        $this->db->bind(':ip_adres', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $this->db->bind(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
        $this->db->execute();
    }

    // Haal gebruiker op bij ID
    public function getUserById($id)
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.adres, p.postcode, p.woonplaats, p.geboortedatum, p.telefoon, p.bsn,
                          (SELECT tijdstip FROM login_logs WHERE user_id = u.id AND actie = 'login' ORDER BY tijdstip DESC LIMIT 1) as last_login
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          WHERE u.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update gebruikersrol (alleen eigenaar)
    public function updateUserRole($userId, $newRole)
    {
        $this->db->query("UPDATE users SET role = :role WHERE id = :id");
        $this->db->bind(':role', $newRole);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Haal alle gebruikers op
    public function getAllUsers()
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.telefoon 
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          ORDER BY u.created_at DESC");
        return $this->db->resultSet();
    }

    // Check of email al bestaat
    public function emailExists($email)
    {
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single() ? true : false;
    }

    // Update wachtwoord
    public function updatePassword($userId, $newPassword)
    {
        if (!$this->validatePassword($newPassword)) {
            return false;
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $this->db->query("UPDATE users SET password_hash = :password WHERE id = :id");
        $this->db->bind(':password', $passwordHash);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Haal instructeurs op
    public function getInstructeurs()
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.telefoon 
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          WHERE u.role = 'instructeur' AND u.is_active = 1
                          ORDER BY p.voornaam, p.achternaam");
        return $this->db->resultSet();
    }

    // Zoek gebruiker op email
    public function findUserByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    // Statistieken methoden voor eigenaar
    public function getTotaalGebruikers()
    {
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE is_active = 1");
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getActieveInstructeurs()
    {
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'instructeur' AND is_active = 1");
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getNieuweGebruikersDezeMaand()
    {
        $this->db->query("SELECT COUNT(*) as count FROM users 
                          WHERE MONTH(created_at) = MONTH(CURDATE()) 
                          AND YEAR(created_at) = YEAR(CURDATE())");
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function getAlleGebruikers($filter = 'alle', $zoekterm = '')
    {
        $whereClause = "WHERE 1=1";
        
        if ($filter !== 'alle') {
            $whereClause .= " AND u.role = :filter";
        }
        
        if (!empty($zoekterm)) {
            $whereClause .= " AND (u.email LIKE :zoekterm OR p.voornaam LIKE :zoekterm OR p.achternaam LIKE :zoekterm)";
        }
        
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.telefoon 
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          {$whereClause}
                          ORDER BY u.created_at DESC");
        
        if ($filter !== 'alle') {
            $this->db->bind(':filter', $filter);
        }
        
        if (!empty($zoekterm)) {
            $this->db->bind(':zoekterm', '%' . $zoekterm . '%');
        }
        
        return $this->db->resultSet();
    }

    public function getNieuweGebruikers($limit = 5)
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam 
                          FROM users u 
                          LEFT JOIN personen p ON u.id = p.user_id 
                          ORDER BY u.created_at DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getGebruikersRapport($periode, $datum)
    {
        // Totaal gebruikers
        $this->db->query("SELECT COUNT(*) as totaal_gebruikers,
                                 SUM(CASE WHEN MONTH(created_at) = MONTH(:datum) AND YEAR(created_at) = YEAR(:datum) THEN 1 ELSE 0 END) as nieuwe_gebruikers,
                                 SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as actieve_gebruikers
                          FROM users");
        $this->db->bind(':datum', $datum);
        $totalen = $this->db->single();
        
        // Rol verdeling
        $this->db->query("SELECT role, COUNT(*) as aantal FROM users WHERE is_active = 1 GROUP BY role");
        $rolVerdeling = $this->db->resultSet();
        
        $rolArray = [];
        foreach ($rolVerdeling as $rol) {
            $rolArray[$rol->role] = $rol->aantal;
        }
        
        // Wekelijkse registraties
        $this->db->query("SELECT WEEK(created_at) as week_nummer, COUNT(*) as aantal
                          FROM users 
                          WHERE MONTH(created_at) = MONTH(:datum)
                          AND YEAR(created_at) = YEAR(:datum)
                          GROUP BY WEEK(created_at)
                          ORDER BY week_nummer ASC");
        $this->db->bind(':datum', $datum);
        $wekelijks = $this->db->resultSet();
        
        // Bereken conversie rate (mock data)
        $conversieRate = 85.5;
        
        return [
            'totaal_gebruikers' => $totalen->totaal_gebruikers ?? 0,
            'nieuwe_gebruikers' => $totalen->nieuwe_gebruikers ?? 0,
            'actieve_gebruikers' => $totalen->actieve_gebruikers ?? 0,
            'conversie_rate' => $conversieRate,
            'rol_verdeling' => $rolArray,
            'wekelijkse_registraties' => $wekelijks
        ];
    }

    public function getInstructeursRapport($periode, $datum)
    {
        // Haal instructeurs op met hun statistieken
        $this->db->query("SELECT u.id, u.email,
                                 CONCAT(p.voornaam, ' ', p.achternaam) as naam,
                                 COUNT(DISTINCT r.id) as totaal_lessen,
                                 SUM(CASE WHEN r.status = 'afgerond' THEN 1 ELSE 0 END) as voltooide_lessen,
                                 AVG(4.2) as gemiddelde_beoordeling,
                                 SUM(CASE WHEN r.betaal_status = 'betaald' THEN lp.prijs ELSE 0 END) as gegenereerde_omzet
                          FROM users u
                          LEFT JOIN personen p ON u.id = p.user_id
                          LEFT JOIN reserveringen r ON p.id = r.instructeur_id
                          LEFT JOIN lespakketten lp ON r.lespakket_id = lp.id
                          WHERE u.role = 'instructeur' AND u.is_active = 1
                          GROUP BY u.id, u.email, p.voornaam, p.achternaam
                          ORDER BY totaal_lessen DESC");
        
        return [
            'instructeur_stats' => $this->db->resultSet()
        ];
    }
}