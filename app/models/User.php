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
        $this->db->query("SELECT * FROM users WHERE email = :email AND is_active = 1");
        $this->db->bind(':email', $email);
        $user = $this->db->single();

        if ($user && password_verify($password, $user->password_hash)) {
            // Log login
            $this->logActivity($user->id, $email, 'login');
            return $user;
        }
        
        return false;
    }

    // Log logout
    public function logout($userId, $email)
    {
        $this->logActivity($userId, $email, 'logout');
    }

    // Log login/logout activiteit
    private function logActivity($userId, $email, $action)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $this->db->query("INSERT INTO login_logs (user_id, email, actie, ip_adres, user_agent) VALUES (:user_id, :email, :actie, :ip_adres, :user_agent)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':email', $email);
        $this->db->bind(':actie', $action);
        $this->db->bind(':ip_adres', $ipAddress);
        $this->db->bind(':user_agent', $userAgent);
        $this->db->execute();
    }

    // Haal gebruiker op bij ID
    public function getUserById($id)
    {
        $this->db->query("SELECT u.*, p.voornaam, p.achternaam, p.adres, p.postcode, p.woonplaats, p.geboortedatum, p.telefoon, p.bsn 
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
}