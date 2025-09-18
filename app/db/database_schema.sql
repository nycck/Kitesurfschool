-- Database schema voor Kitesurfschool Windkracht-12
DROP DATABASE IF EXISTS kitesurfschool_windkracht12;
CREATE DATABASE kitesurfschool_windkracht12;
USE kitesurfschool_windkracht12;

-- Gebruikers tabel
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(191) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('klant', 'instructeur', 'eigenaar') DEFAULT 'klant',
    is_active BOOLEAN DEFAULT TRUE,
    activation_token VARCHAR(191),
    reset_token VARCHAR(191),
    reset_token_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Personen tabel
DROP TABLE IF EXISTS personen;
CREATE TABLE personen (
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
DROP TABLE IF EXISTS locaties;
CREATE TABLE locaties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    adres VARCHAR(255),
    beschrijving TEXT,
    faciliteiten TEXT,
    is_active BOOLEAN DEFAULT TRUE
);

-- Lespakketten tabel
DROP TABLE IF EXISTS lespakketten;
CREATE TABLE lespakketten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    beschrijving TEXT,
    aantal_lessen INT NOT NULL,
    totale_uren DECIMAL(3,1) NOT NULL,
    prijs_per_persoon DECIMAL(8,2) NOT NULL,
    prijs DECIMAL(8,2) NOT NULL,
    max_personen INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Reserveringen tabel
DROP TABLE IF EXISTS reserveringen;
CREATE TABLE reserveringen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persoon_id INT NOT NULL,
    instructeur_id INT,
    lespakket_id INT NOT NULL,
    locatie_id INT NOT NULL,
    gewenste_datum DATE NOT NULL,
    bevestigde_datum DATE,
    bevestigde_tijd TIME,
    status ENUM('aangevraagd', 'bevestigd', 'geannuleerd', 'afgerond') DEFAULT 'aangevraagd',
    betaal_status ENUM('wachtend', 'betaald', 'mislukt') DEFAULT 'wachtend',
    duo_partner_id INT,
    opmerking TEXT,
    instructeur_opmerking TEXT,
    evaluatie TEXT,
    voortgang TEXT,
    aanbevelingen TEXT,
    annulering_reden TEXT,
    betaal_opmerking TEXT,
    aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bijgewerkt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (persoon_id) REFERENCES personen(id),
    FOREIGN KEY (instructeur_id) REFERENCES personen(id),
    FOREIGN KEY (lespakket_id) REFERENCES lespakketten(id),
    FOREIGN KEY (locatie_id) REFERENCES locaties(id),
    FOREIGN KEY (duo_partner_id) REFERENCES personen(id)
);

-- Les sessies tabel
DROP TABLE IF EXISTS les_sessies;
CREATE TABLE les_sessies (
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
DROP TABLE IF EXISTS login_logs;
CREATE TABLE login_logs (
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
DROP TABLE IF EXISTS email_logs;
CREATE TABLE email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naar_email VARCHAR(191) NOT NULL,
    onderwerp VARCHAR(255) NOT NULL,
    bericht TEXT NOT NULL,
    type ENUM('activatie', 'bevestiging', 'annulering', 'betaling', 'overig') NOT NULL,
    verzonden_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('verzonden', 'gefaald') DEFAULT 'verzonden'
);

-- Standaard data
-- Eigenaar account (password = admin123)
INSERT INTO users (id, email, password_hash, role, is_active) VALUES 
(1, 'terence@windkracht12.nl', '$2y$10$LHFXivb9GYsmpSWtPFeAOeXP7k6rpIZDz42DDfXPcAEiBhO87T96e', 'eigenaar', TRUE)
ON DUPLICATE KEY UPDATE email=email;

INSERT INTO personen (user_id, voornaam, achternaam, telefoon) VALUES 
(1, 'Terence', 'Olieslager', '06-12345678')
ON DUPLICATE KEY UPDATE voornaam=voornaam;

-- Locaties
INSERT INTO locaties (naam, adres, beschrijving, faciliteiten) VALUES 
('Zandvoort', 'Boulevard Zandvoort, Zandvoort', 'Breed strand met goede wind condities', 'Parkeerplaats, douches, toiletten'),
('Muiderberg', 'Strand Muiderberg, Muiden', 'Rustige locatie ideaal voor beginners', 'Beperkte faciliteiten, rustige omgeving'),
('Wijk aan Zee', 'Boulevard Wijk aan Zee', 'Populaire kitespot met veel ruimte', 'Strandpaviljoen, parkeren mogelijk'),
('IJmuiden', 'Zuidpier IJmuiden', 'Goede wind en golven voor gevorderden', 'Haven nabij, ervaren kiters'),
('Scheveningen', 'Zuidstrand Scheveningen, Den Haag', 'Bekende kitespot met faciliteiten', 'Veel faciliteiten, druk strand'),
('Hoek van Holland', 'Strand Hoek van Holland', 'Wind uit verschillende richtingen', 'Natuurgebied, rustig strand');

-- Lespakketten
INSERT INTO lespakketten (naam, beschrijving, aantal_lessen, totale_uren, prijs_per_persoon, prijs, max_personen) VALUES 
('Privéles', 'Persoonlijke kitesurfles met individuele aandacht', 1, 2.5, 175.00, 175.00, 1),
('Losse Duo Kiteles', 'Introductie kitesurfles voor 2 personen', 1, 3.5, 135.00, 135.00, 2),
('Kitesurf Duo lespakket 3 lessen', 'Complete basiscursus kitesurfen voor 2 personen', 3, 10.5, 375.00, 375.00, 2),
('Kitesurf Duo lespakket 5 lessen', 'Uitgebreide kitesurfcursus voor 2 personen', 5, 17.5, 675.00, 675.00, 2)
ON DUPLICATE KEY UPDATE naam=naam;
