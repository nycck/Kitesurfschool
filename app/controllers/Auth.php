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
                // Registreer en activeer gebruiker direct
                $userId = $this->userModel->registerAndActivate($email, $password);
                
                if ($userId) {
                    $data = [
                        'title' => 'Registratie succesvol',
                        'message' => 'Je account is aangemaakt! Je kunt nu inloggen.'
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
                            redirect('instructeur/dashboard');
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
}