<?php

// Check of gebruiker is ingelogd
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Check gebruikersrol
function hasRole($role)
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

// Check of gebruiker eigenaar is
function isOwner()
{
    return hasRole('eigenaar');
}

// Check of gebruiker instructeur is
function isInstructor()
{
    return hasRole('instructeur');
}

// Check of gebruiker klant is
function isCustomer()
{
    return hasRole('klant');
}

// Redirect functie
function redirect($url)
{
    header('Location: ' . URLROOT . '/' . $url);
    exit;
}

// Flash messages
function flash($name = '', $message = '', $class = 'alert-info')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">';
            echo $_SESSION[$name];
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Format Nederlandse datum
function formatDutchDate($date)
{
    if (empty($date)) return '';
    
    $dutch_months = [
        'January' => 'januari', 'February' => 'februari', 'March' => 'maart',
        'April' => 'april', 'May' => 'mei', 'June' => 'juni',
        'July' => 'juli', 'August' => 'augustus', 'September' => 'september',
        'October' => 'oktober', 'November' => 'november', 'December' => 'december'
    ];
    
    $dutch_days = [
        'Monday' => 'maandag', 'Tuesday' => 'dinsdag', 'Wednesday' => 'woensdag',
        'Thursday' => 'donderdag', 'Friday' => 'vrijdag', 'Saturday' => 'zaterdag', 'Sunday' => 'zondag'
    ];
    
    $timestamp = strtotime($date);
    $english_date = date('l j F Y', $timestamp);
    
    $dutch_date = str_replace(array_keys($dutch_months), array_values($dutch_months), $english_date);
    $dutch_date = str_replace(array_keys($dutch_days), array_values($dutch_days), $dutch_date);
    
    return $dutch_date;
}

// Format geld
function formatMoney($amount)
{
    return '€' . number_format($amount, 2, ',', '.');
}

// CSRF token genereren
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF token valideren
function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Valideer Nederlandse postcode
function validatePostcode($postcode)
{
    return preg_match('/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/', $postcode);
}

// Valideer Nederlands BSN
function validateBSN($bsn)
{
    $bsn = preg_replace('/\D/', '', $bsn);
    
    if (strlen($bsn) != 9) {
        return false;
    }
    
    $sum = 0;
    for ($i = 0; $i < 8; $i++) {
        $sum += $bsn[$i] * (9 - $i);
    }
    $sum += $bsn[8] * -1;
    
    return ($sum % 11 == 0);
}

// Check beschikbaarheid instructeur
function checkInstructorAvailability($instructorId, $date, $startTime, $endTime)
{
    $database = new Database();
    
    $database->query("SELECT COUNT(*) as count FROM les_sessies ls
                      JOIN reserveringen r ON ls.reservering_id = r.id
                      WHERE r.instructeur_id = :instructor_id 
                      AND ls.les_datum = :date 
                      AND ls.status != 'geannuleerd'
                      AND (
                          (ls.start_tijd < :end_time AND ls.eind_tijd > :start_time)
                      )");
    
    $database->bind(':instructor_id', $instructorId);
    $database->bind(':date', $date);
    $database->bind(':start_time', $startTime);
    $database->bind(':end_time', $endTime);
    
    $result = $database->single();
    return $result->count == 0;
}

// Krijg beschikbare instructeurs voor datum/tijd
function getAvailableInstructors($date, $startTime, $endTime)
{
    $database = new Database();
    
    $database->query("SELECT u.id, p.voornaam, p.achternaam 
                      FROM users u
                      JOIN personen p ON u.id = p.user_id
                      WHERE u.role = 'instructeur' 
                      AND u.is_active = 1
                      AND u.id NOT IN (
                          SELECT DISTINCT r.instructeur_id 
                          FROM reserveringen r
                          JOIN les_sessies ls ON r.id = ls.reservering_id
                          WHERE ls.les_datum = :date 
                          AND ls.status != 'geannuleerd'
                          AND (ls.start_tijd < :end_time AND ls.eind_tijd > :start_time)
                          AND r.instructeur_id IS NOT NULL
                      )");
    
    $database->bind(':date', $date);
    $database->bind(':start_time', $startTime);
    $database->bind(':end_time', $endTime);
    
    return $database->resultSet();
}