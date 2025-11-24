<?php

class EmailService
{
    private $db;
    private $mail;

    public function __construct()
    {
        // Load PHPMailer
        require_once APPROOT . '/libraries/PHPMailer/src/Exception.php';
        require_once APPROOT . '/libraries/PHPMailer/src/PHPMailer.php';
        require_once APPROOT . '/libraries/PHPMailer/src/SMTP.php';
        
        $this->db = new Database();
        $this->mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        if (ENVIRONMENT !== 'development') {
            try {
                // Server settings
                $this->mail->isSMTP();
                $this->mail->Host       = 'smtp.gmail.com';
                $this->mail->SMTPAuth   = true;
                $this->mail->Username   = 'info.nycck@gmail.com';
                $this->mail->Password   = 'hypy cddn sgfg rxka';
                $this->mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $this->mail->Port       = 587;
                $this->mail->CharSet    = 'UTF-8';
                
                // Sender
                $this->mail->setFrom('info.nycck@gmail.com', 'Kitesurfschool Windkracht-12');
                $this->mail->isHTML(true);
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                error_log("Email setup error: {$e->getMessage()}");
            }
        }
    }

    // Verstuur activatie email
    public function sendActivationEmail($email, $activationToken)
    {
        if (ENVIRONMENT === 'development') {
            error_log("DEV MODE: Activation email would be sent to {$email}");
            error_log("Activation link: " . URLROOT . '/auth/activate/' . $activationToken);
            $this->logEmail($email, 'Activatie Email', 'DEV MODE - Not sent', 'activatie');
            return true;
        }

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Welkom bij Kitesurfschool Windkracht-12 - Activeer je account';
            
            $activationLink = URLROOT . '/auth/activate/' . $activationToken;
            
            $message = "
            <html>
            <body>
                <h2>Welkom bij Kitesurfschool Windkracht-12!</h2>
                <p>Bedankt voor je registratie. Klik op onderstaande link om je account te activeren en je wachtwoord in te stellen:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$activationLink}' style='background-color: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>Account Activeren</a>
                </div>
                <p>Of kopieer deze link naar je browser:</p>
                <p><a href='{$activationLink}'>{$activationLink}</a></p>
                <p><strong>Deze link is 24 uur geldig.</strong></p>
                <hr>
                <p style='color: #666; font-size: 12px;'>Als je deze email niet hebt aangevraagd, kun je deze negeren.</p>
                <br>
                <p>Met vriendelijke groet,<br>Team Windkracht-12</p>
            </body>
            </html>";
            
            $this->mail->Body = $message;
            
            $result = $this->mail->send();
            $this->logEmail($email, $this->mail->Subject, $message, 'activatie');
            
            return $result;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email send error: {$this->mail->ErrorInfo}");
            $this->logEmail($email, 'Activatie Email', "ERROR: {$this->mail->ErrorInfo}", 'activatie');
            return false;
        }
    }

    // Verstuur betalings email
    public function sendPaymentEmail($email, $reserveringData)
    {
        if (ENVIRONMENT === 'development') {
            error_log("DEV MODE: Payment email would be sent to {$email}");
            return true;
        }

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Betalingsgegevens - Kitesurfschool Windkracht-12';
            
            $message = "
            <html>
            <body>
                <h2>Betalingsgegevens voor je kitesurfles</h2>
                <p>Beste {$reserveringData['naam']},</p>
                <p>Bedankt voor je reservering! Hieronder vind je de betalingsgegevens:</p>
                
                <h3>Reserveringsdetails:</h3>
                <ul>
                    <li><strong>Lespakket:</strong> {$reserveringData['pakket']}</li>
                    <li><strong>Locatie:</strong> {$reserveringData['locatie']}</li>
                    <li><strong>Totaalprijs:</strong> €{$reserveringData['prijs']}</li>
                </ul>
                
                <h3>Betalingsgegevens:</h3>
                <p><strong>Rekeningnummer:</strong> NL12 RABO 0123 4567 89<br>
                <strong>Ten name van:</strong> Kitesurfschool Windkracht-12<br>
                <strong>Bedrag:</strong> €{$reserveringData['prijs']}<br>
                <strong>Omschrijving:</strong> Reservering {$reserveringData['reservering_id']}</p>
                
                <p><em>Let op: Je reservering wordt pas definitief na ontvangst van de betaling.</em></p>
                
                <p>Met vriendelijke groet,<br>Team Windkracht-12</p>
            </body>
            </html>";
            
            $this->mail->Body = $message;
            
            $result = $this->mail->send();
            $this->logEmail($email, $this->mail->Subject, $message, 'betaling');
            
            return $result;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email send error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    // Verstuur bevestigings email
    public function sendConfirmationEmail($email, $reserveringData)
    {
        if (ENVIRONMENT === 'development') {
            error_log("DEV MODE: Confirmation email would be sent to {$email}");
            return true;
        }

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Bevestiging kitesurfles - Windkracht-12';
            
            $message = "
            <html>
            <body>
                <h2>Je kitesurfles is bevestigd!</h2>
                <p>Beste {$reserveringData['naam']},</p>
                <p>Goed nieuws! Je betaling is ontvangen en je kitesurfles is definitief bevestigd.</p>
                
                <h3>Lesdetails:</h3>
                <ul>
                    <li><strong>Instructeur:</strong> {$reserveringData['instructeur']}</li>
                    <li><strong>Lespakket:</strong> {$reserveringData['pakket']}</li>
                    <li><strong>Locatie:</strong> {$reserveringData['locatie']}</li>
                    <li><strong>Eerste les:</strong> {$reserveringData['eerste_les']}</li>
                </ul>
                
                <p>Je instructeur zal contact met je opnemen voor de exacte details.</p>
                <p>Veel plezier met kitesurfen!</p>
                
                <p>Met vriendelijke groet,<br>Team Windkracht-12</p>
            </body>
            </html>";
            
            $this->mail->Body = $message;
            
            $result = $this->mail->send();
            $this->logEmail($email, $this->mail->Subject, $message, 'bevestiging');
            
            return $result;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email send error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    // Verstuur annulerings email (ziekte)
    public function sendCancellationEmailSickness($email, $lesData)
    {
        if (ENVIRONMENT === 'development') {
            error_log("DEV MODE: Cancellation (sickness) email would be sent to {$email}");
            return true;
        }

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Les geannuleerd wegens ziekte instructeur - Windkracht-12';
            
            $message = "
            <html>
            <body>
                <h2>Les geannuleerd wegens ziekte</h2>
                <p>Beste {$lesData['klant_naam']},</p>
                <p>Helaas moeten we je kitesurfles van {$lesData['datum']} annuleren wegens ziekte van instructeur {$lesData['instructeur']}.</p>
                
                <p>We nemen zo spoedig mogelijk contact met je op voor het inplannen van een nieuwe datum.</p>
                <p>Onze excuses voor het ongemak.</p>
                
                <p>Met vriendelijke groet,<br>Team Windkracht-12</p>
            </body>
            </html>";
            
            $this->mail->Body = $message;
            
            $result = $this->mail->send();
            $this->logEmail($email, $this->mail->Subject, $message, 'annulering');
            
            return $result;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email send error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    // Verstuur annulerings email (weer)
    public function sendCancellationEmailWeather($email, $lesData)
    {
        if (ENVIRONMENT === 'development') {
            error_log("DEV MODE: Cancellation (weather) email would be sent to {$email}");
            return true;
        }

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Les geannuleerd wegens weersomstandigheden - Windkracht-12';
            
            $message = "
            <html>
            <body>
                <h2>Les geannuleerd wegens weer</h2>
                <p>Beste {$lesData['klant_naam']},</p>
                <p>Helaas moeten we je kitesurfles van {$lesData['datum']} annuleren wegens ongeschikte weersomstandigheden (windkracht > 10).</p>
                
                <p>Voor de veiligheid van onze leerlingen geven we geen les bij extreme weersomstandigheden.</p>
                <p>We nemen contact met je op voor het inplannen van een nieuwe datum.</p>
                
                <p>Met vriendelijke groet,<br>Team Windkracht-12</p>
            </body>
            </html>";
            
            $this->mail->Body = $message;
            
            $result = $this->mail->send();
            $this->logEmail($email, $this->mail->Subject, $message, 'annulering');
            
            return $result;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email send error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    // Log email voor tracking
    private function logEmail($to, $subject, $message, $type)
    {
        $this->db->query("INSERT INTO email_logs (naar_email, onderwerp, bericht, type) VALUES (:naar_email, :onderwerp, :bericht, :type)");
        $this->db->bind(':naar_email', $to);
        $this->db->bind(':onderwerp', $subject);
        $this->db->bind(':bericht', $message);
        $this->db->bind(':type', $type);
        $this->db->execute();
    }
}