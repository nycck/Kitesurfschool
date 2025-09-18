-- Database schema voor Kitesurfschool Windkracht-12
CREATE DATABASE IF NOT EXISTS kitesurfschool_windkracht12;
USE kitesurfschool_windkracht12;

-- Gebruikers tabel
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(191) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('klant', 'instructeur', 'eigenaar') DEFAULT 'klant',
    is_active BOOLEAN DEFAULT FALSE,
    activation_token VARCHAR(191),
    reset_token VARCHAR(191),
    reset_token_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Personen tabel (uitgebreide informatie)
CREATE TABLE IF NOT EXISTS personen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    voornaam VARCHAR(100) NOT NULL,
    achternaam VARCHAR(100) NOT NULL,
    adres VARCHAR(255),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100),
    geboortedatum DATE,
    telefoon VARCHAR(20),
    bsn VARCHAR(9),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Locaties tabel
CREATE TABLE IF NOT EXISTS locaties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    adres VARCHAR(255),
    beschrijving TEXT,
    is_active BOOLEAN DEFAULT TRUE
);

-- Lespakketten tabel
CREATE TABLE IF NOT EXISTS lespakketten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    beschrijving TEXT,
    aantal_lessen INT NOT NULL,
    totale_uren DECIMAL(3,1) NOT NULL,
    prijs_per_persoon DECIMAL(8,2) NOT NULL,
    max_personen INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Reserveringen tabel
CREATE TABLE IF NOT EXISTS reserveringen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    klant_id INT NOT NULL,
    instructeur_id INT,
    lespakket_id INT NOT NULL,
    locatie_id INT NOT NULL,
    status ENUM('voorlopig', 'definitief', 'geannuleerd') DEFAULT 'voorlopig',
    betaling_status ENUM('open', 'betaald', 'gerefund') DEFAULT 'open',
    totaal_prijs DECIMAL(8,2) NOT NULL,
    duo_partner_naam VARCHAR(200),
    duo_partner_email VARCHAR(191),
    duo_partner_telefoon VARCHAR(20),
    opmerkingen TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (klant_id) REFERENCES users(id),
    FOREIGN KEY (instructeur_id) REFERENCES users(id),
    FOREIGN KEY (lespakket_id) REFERENCES lespakketten(id),
    FOREIGN KEY (locatie_id) REFERENCES locaties(id)
);

-- Les sessies tabel
CREATE TABLE IF NOT EXISTS les_sessies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservering_id INT NOT NULL,
    les_datum DATE NOT NULL,
    start_tijd TIME NOT NULL,
    eind_tijd TIME NOT NULL,
    status ENUM('gepland', 'voltooid', 'geannuleerd') DEFAULT 'gepland',
    annulering_reden TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reservering_id) REFERENCES reserveringen(id) ON DELETE CASCADE
);

-- Login logs tabel
CREATE TABLE IF NOT EXISTS login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    email VARCHAR(191) NOT NULL,
    actie ENUM('login', 'logout') NOT NULL,
    ip_adres VARCHAR(45),
    user_agent TEXT,
    tijdstip TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Email logs tabel
CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naar_email VARCHAR(191) NOT NULL,
    onderwerp VARCHAR(255) NOT NULL,
    bericht TEXT NOT NULL,
    type ENUM('activatie', 'bevestiging', 'annulering', 'betaling', 'overig') NOT NULL,
    verzonden_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('verzonden', 'gefaald') DEFAULT 'verzonden'
);

-- Insert standaard data
-- Eigenaar account (al geregistreerd)
INSERT INTO users (email, password_hash, role, is_active) VALUES 
('terence@windkracht12.nl', '$2y$10$6F.Uf8.UjYH8FRgDXzN.aOQLxeRJ3S.m5t9P7LqvP2K8w3JnVQj1O', 'eigenaar', TRUE);

INSERT INTO personen (user_id, voornaam, achternaam, telefoon) VALUES 
(1, 'Terence', 'Olieslager', '06-12345678');

-- Locaties
INSERT INTO locaties (naam, adres, beschrijving) VALUES 
('Zandvoort', 'Boulevard Zandvoort, Zandvoort', 'Breed strand met goede wind condities'),
('Muiderberg', 'Strand Muiderberg, Muiden', 'Rustige locatie ideaal voor beginners'),
('Wijk aan Zee', 'Boulevard Wijk aan Zee', 'Populaire kitespot met veel ruimte'),
('IJmuiden', 'Zuidpier IJmuiden', 'Goede wind en golven voor gevorderden'),
('Scheveningen', 'Zuidstrand Scheveningen, Den Haag', 'Bekende kitespot met faciliteiten'),
('Hoek van Holland', 'Strand Hoek van Holland', 'Wind uit verschillende richtingen');

-- Lespakketten
INSERT INTO lespakketten (naam, beschrijving, aantal_lessen, totale_uren, prijs_per_persoon, max_personen) VALUES 
('Privéles', 'Persoonlijke kitesurfles met individuele aandacht', 1, 2.5, 175.00, 1),
('Losse Duo Kiteles', 'Introductie kitesurfles voor 2 personen', 1, 3.5, 135.00, 2),
('Kitesurf Duo lespakket 3 lessen', 'Complete basiscursus kitesurfen voor 2 personen', 3, 10.5, 375.00, 2),
('Kitesurf Duo lespakket 5 lessen', 'Uitgebreide kitesurfcursus voor 2 personen', 5, 17.5, 675.00, 2);
