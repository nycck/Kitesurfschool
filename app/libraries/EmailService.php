<?php

class EmailService
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Verstuur activatie email
    public function sendActivationEmail($email, $activationToken)
    {
        $subject = 'Welkom bij Kitesurfschool Windkracht-12 - Activeer je account';
        $activationLink = URLROOT . '/auth/activate/' . $activationToken;
        
        $message = "
        <html>
        <body>
            <h2>Welkom bij Kitesurfschool Windkracht-12!</h2>
            <p>Bedankt voor je registratie. Klik op onderstaande link om je account te activeren:</p>
            <p><a href='{$activationLink}' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Account activeren</a></p>
            <p>Of kopieer deze link naar je browser: {$activationLink}</p>
            <p>Als je deze email niet hebt aangevraagd, kun je deze negeren.</p>
            <br>
            <p>Met vriendelijke groet,<br>Team Windkracht-12</p>
        </body>
        </html>";

        return $this->sendEmail($email, $subject, $message, 'activatie');
    }

    // Verstuur betalings email
    public function sendPaymentEmail($email, $reserveringData)
    {
        $subject = 'Betalingsgegevens - Kitesurfschool Windkracht-12';
        
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

        return $this->sendEmail($email, $subject, $message, 'betaling');
    }

    // Verstuur bevestigings email
    public function sendConfirmationEmail($email, $reserveringData)
    {
        $subject = 'Bevestiging kitesurfles - Windkracht-12';
        
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

        return $this->sendEmail($email, $subject, $message, 'bevestiging');
    }

    // Verstuur annulerings email (ziekte)
    public function sendCancellationEmailSickness($email, $lesData)
    {
        $subject = 'Les geannuleerd wegens ziekte instructeur - Windkracht-12';
        
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

        return $this->sendEmail($email, $subject, $message, 'annulering');
    }

    // Verstuur annulerings email (weer)
    public function sendCancellationEmailWeather($email, $lesData)
    {
        $subject = 'Les geannuleerd wegens weersomstandigheden - Windkracht-12';
        
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

        return $this->sendEmail($email, $subject, $message, 'annulering');
    }

    // Verstuur email (basis functie)
    private function sendEmail($to, $subject, $message, $type)
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@kitesurfschool-windkracht12.nl" . "\r\n";

        // Log email
        $this->logEmail($to, $subject, $message, $type);

        // In development: don't actually send emails, just return true
        if (ENVIRONMENT === 'development') {
            return true;
        }

        // Send email
        return mail($to, $subject, $message, $headers);
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