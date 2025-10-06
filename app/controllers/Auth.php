<?php

class Auth extends BaseController
{
    private $userModel;
    private $persoonModel;
    private $emailService;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->persoonModel = $this->model('Persoon');
        $this->emailService = new EmailService();
    }

    // Registratie pagina
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize inputs
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm_password']);
            
            // Validatie
            $errors = [];
            
            if (empty($email)) {
                $errors[] = 'Email is verplicht';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Ongeldige email';
            } elseif ($this->userModel->emailExists($email)) {
                $errors[] = 'Email is al geregistreerd';
            }
            
            if (empty($password)) {
                $errors[] = 'Wachtwoord is verplicht';
            } elseif (strlen($password) < 12) {
                $errors[] = 'Wachtwoord moet minimaal 12 tekens zijn';
            } elseif (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Wachtwoord moet een hoofdletter bevatten';
            } elseif (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Wachtwoord moet een cijfer bevatten';
            } elseif (!preg_match('/[@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
                $errors[] = 'Wachtwoord moet een speciaal teken bevatten (@, #, $, etc.)';
            }
            
            if (empty($confirmPassword)) {
                $errors[] = 'Bevestig wachtwoord is verplicht';
            } elseif ($password !== $confirmPassword) {
                $errors[] = 'Wachtwoorden komen niet overeen';
            }
            
            if (empty($errors)) {
                // Generate activation token
                $activationToken = bin2hex(random_bytes(32));
                
                // Register user with activation token (niet geactiveerd)
                $userId = $this->userModel->register($email, $activationToken);
                
                if ($userId) {
                    // Send activation email
                    $this->sendActivationEmail($email, $activationToken);
                    
                    $data = [
                        'title' => 'Registratie succesvol',
                        'message' => 'Een activatielink is naar je email gestuurd. Controleer je inbox om je account te activeren.'
                    ];
                    $this->view('auth/success', $data);
                } else {
                    $errors[] = 'Registratie mislukt';
                }
            }
            
            if (!empty($errors)) {
                $data = [
                    'title' => 'Registratie',
                    'errors' => $errors,
                    'email' => $email,
                    'password' => '',
                    'confirm_password' => ''
                ];
                $this->view('auth/register', $data);
            }
        } else {
            $data = [
                'title' => 'Registratie',
                'email' => '',
                'password' => '',
                'confirm_password' => ''
            ];
            $this->view('auth/register', $data);
        }
    }

    // Activatie van account
    public function activate($token = null)
    {
        if (!$token) {
            redirect('auth/register');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            $errors = [];
            
            if (empty($password)) {
                $errors[] = 'Wachtwoord is verplicht';
            } elseif (strlen($password) < 12) {
                $errors[] = 'Wachtwoord moet minimaal 12 tekens zijn';
            } elseif (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Wachtwoord moet een hoofdletter bevatten';
            } elseif (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Wachtwoord moet een cijfer bevatten';
            } elseif (!preg_match('/[@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
                $errors[] = 'Wachtwoord moet een speciaal teken bevatten (@, #, $, etc.)';
            } elseif ($password !== $confirmPassword) {
                $errors[] = 'Wachtwoorden komen niet overeen';
            }
            
            if (empty($errors)) {
                $result = $this->userModel->activateUser($token, $password);
                
                if ($result['success']) {
                    // Automatisch inloggen na activatie
                    $_SESSION['user_id'] = $result['user_id'];
                    $_SESSION['user_role'] = 'klant';
                    
                    redirect('klant/dashboard');
                } else {
                    $errors[] = $result['message'];
                }
            }
            
            $data = [
                'title' => 'Account activeren',
                'token' => $token,
                'errors' => $errors
            ];
            $this->view('auth/activate', $data);
        } else {
            $data = [
                'title' => 'Account activeren',
                'token' => $token
            ];
            $this->view('auth/activate', $data);
        }
    }

    // Login pagina
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            
            $errors = [];
            
            if (empty($email)) {
                $errors[] = 'Email is verplicht';
            }
            
            if (empty($password)) {
                $errors[] = 'Wachtwoord is verplicht';
            }
            
            if (empty($errors)) {
                $user = $this->userModel->login($email, $password);
                
                if ($user) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['user_role'] = $user->role;
                    
                    // Redirect naar juiste dashboard
                    switch ($user->role) {
                        case 'eigenaar':
                            redirect('eigenaar/dashboard');
                            break;
                        case 'instructeur':
                            redirect('instructeurs/dashboard');
                            break;
                        default:
                            redirect('klant/dashboard');
                            break;
                    }
                } else {
                    $errors[] = 'Ongeldige inloggegevens';
                }
            }
            
            $data = [
                'title' => 'Inloggen',
                'errors' => $errors,
                'email' => $email
            ];
            $this->view('auth/login', $data);
        } else {
            $data = ['title' => 'Inloggen'];
            $this->view('auth/login', $data);
        }
    }

    // Logout
    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            $this->userModel->logout($_SESSION['user_id'], $_SESSION['user_email']);
        }
        
        session_destroy();
        redirect('');
    }

    // Wachtwoord wijzigen
    public function changePassword()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            $errors = [];
            
            // Validatie
            if (empty($currentPassword)) {
                $errors[] = 'Huidig wachtwoord is verplicht';
            }
            
            if (empty($newPassword)) {
                $errors[] = 'Nieuw wachtwoord is verplicht';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Nieuwe wachtwoorden komen niet overeen';
            }
            
            // Verify current password
            if (empty($errors)) {
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                if (!password_verify($currentPassword, $user->password_hash)) {
                    $errors[] = 'Huidig wachtwoord is onjuist';
                }
            }
            
            if (empty($errors)) {
                if ($this->userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
                    $data = [
                        'title' => 'Wachtwoord gewijzigd',
                        'message' => 'Je wachtwoord is succesvol gewijzigd'
                    ];
                    $this->view('auth/success', $data);
                } else {
                    $errors[] = 'Wachtwoord voldoet niet aan de eisen';
                }
            }
            
            if (!empty($errors)) {
                $data = [
                    'title' => 'Wachtwoord wijzigen',
                    'errors' => $errors
                ];
                $this->view('auth/change_password', $data);
            }
        } else {
            $data = ['title' => 'Wachtwoord wijzigen'];
            $this->view('auth/change_password', $data);
        }
    }

    // Send activation email
    private function sendActivationEmail($email, $activationToken)
    {
        $emailService = new EmailService();
        
        $subject = 'Welkom bij Kitesurfschool Windkracht-12 - Activeer je account';
        
        $activationLink = URLROOT . '/auth/activate/' . $activationToken;
        
        $body = "
        <h2>Welkom bij Kitesurfschool Windkracht-12!</h2>
        
        <p>Bedankt voor je registratie bij onze kitesurfschool. Je bent bijna klaar!</p>
        
        <p>Om je account te activeren en je wachtwoord in te stellen, klik je op de onderstaande link:</p>
        
        <div style='text-align: center; margin: 30px 0;'>
            <a href='{$activationLink}' 
               style='background-color: #ffc107; color: #212529; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>
                Account Activeren
            </a>
        </div>
        
        <p>Of kopieer en plak deze link in je browser:</p>
        <p><a href='{$activationLink}'>{$activationLink}</a></p>
        
        <p><strong>Belangrijk:</strong> Deze activatielink is geldig voor 24 uur.</p>
        
        <hr>
        
        <h3>Wat kun je verwachten?</h3>
        <ul>
            <li>✅ Lessen reserveren bij onze professionele instructeurs</li>
            <li>🏄 Leren kitesurfen op de mooiste locaties</li>
            <li>📧 Updates over weersomstandigheden en cursusaanbod</li>
            <li>🎯 Persoonlijke begeleiding en voortgangstracking</li>
        </ul>
        
        <p>Heb je vragen? Neem gerust contact op via info@kitesurfschool-windkracht12.nl</p>
        
        <p>Tot ziens op het water!</p>
        
        <p>Met vriendelijke groet,<br>
        Het team van Windkracht-12</p>
        
        <hr style='margin-top: 30px;'>
        <p style='font-size: 12px; color: #666;'>
            Als je deze email niet hebt aangevraagd, kun je deze negeren. 
            Je account wordt dan niet geactiveerd.
        </p>
        ";
        
        $emailService->sendEmail($email, $subject, $body);
    }
}