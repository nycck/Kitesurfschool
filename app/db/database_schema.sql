-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Gegenereerd op: 06 okt 2025 om 18:07
-- Serverversie: 8.2.0
-- PHP-versie: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kitesurfschool_windkracht12`
--
CREATE DATABASE IF NOT EXISTS `kitesurfschool_windkracht12` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `kitesurfschool_windkracht12`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
CREATE TABLE IF NOT EXISTS `email_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `naar_email` varchar(191) NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `bericht` text NOT NULL,
  `type` enum('activatie','bevestiging','annulering','betaling','overig') NOT NULL,
  `verzonden_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('verzonden','gefaald') DEFAULT 'verzonden',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `lespakketten`
--

DROP TABLE IF EXISTS `lespakketten`;
CREATE TABLE IF NOT EXISTS `lespakketten` (
  `id` int NOT NULL AUTO_INCREMENT,
  `naam` varchar(100) NOT NULL,
  `beschrijving` text,
  `aantal_lessen` int NOT NULL,
  `totale_uren` decimal(3,1) NOT NULL,
  `prijs_per_persoon` decimal(8,2) NOT NULL,
  `prijs` decimal(8,2) NOT NULL,
  `max_personen` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `lespakketten`
--

INSERT INTO `lespakketten` (`id`, `naam`, `beschrijving`, `aantal_lessen`, `totale_uren`, `prijs_per_persoon`, `prijs`, `max_personen`, `is_active`) VALUES
(1, 'Privéles', 'Persoonlijke kitesurfles met individuele aandacht', 1, 2.5, 175.00, 175.00, 1, 1),
(2, 'Losse Duo Kiteles', 'Introductie kitesurfles voor 2 personen', 1, 3.5, 135.00, 135.00, 2, 1),
(3, 'Kitesurf Duo lespakket 3 lessen', 'Complete basiscursus kitesurfen voor 2 personen', 3, 10.5, 375.00, 375.00, 2, 1),
(4, 'Kitesurf Duo lespakket 5 lessen', 'Uitgebreide kitesurfcursus voor 2 personen', 5, 17.5, 675.00, 675.00, 2, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `les_sessies`
--

DROP TABLE IF EXISTS `les_sessies`;
CREATE TABLE IF NOT EXISTS `les_sessies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reservering_id` int NOT NULL,
  `les_datum` date NOT NULL,
  `start_tijd` time NOT NULL,
  `eind_tijd` time NOT NULL,
  `status` enum('gepland','voltooid','geannuleerd') DEFAULT 'gepland',
  `annulering_reden` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reservering_id` (`reservering_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `locaties`
--

DROP TABLE IF EXISTS `locaties`;
CREATE TABLE IF NOT EXISTS `locaties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `naam` varchar(100) NOT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `beschrijving` text,
  `faciliteiten` text,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `locaties`
--

INSERT INTO `locaties` (`id`, `naam`, `adres`, `beschrijving`, `faciliteiten`, `is_active`) VALUES
(1, 'Zandvoort', 'Boulevard Zandvoort, Zandvoort', 'Breed strand met goede wind condities', 'Parkeerplaats, douches, toiletten', 1),
(2, 'Muiderberg', 'Strand Muiderberg, Muiden', 'Rustige locatie ideaal voor beginners', 'Beperkte faciliteiten, rustige omgeving', 1),
(3, 'Wijk aan Zee', 'Boulevard Wijk aan Zee', 'Populaire kitespot met veel ruimte', 'Strandpaviljoen, parkeren mogelijk', 1),
(4, 'IJmuiden', 'Zuidpier IJmuiden', 'Goede wind en golven voor gevorderden', 'Haven nabij, ervaren kiters', 1),
(5, 'Scheveningen', 'Zuidstrand Scheveningen, Den Haag', 'Bekende kitespot met faciliteiten', 'Veel faciliteiten, druk strand', 1),
(6, 'Hoek van Holland', 'Strand Hoek van Holland', 'Wind uit verschillende richtingen', 'Natuurgebied, rustig strand', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `actie` enum('login','logout') NOT NULL,
  `ip_adres` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `tijdstip` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `email`, `actie`, `ip_adres`, `user_agent`, `tijdstip`) VALUES
(1, 1, 'terence@windkracht12.nl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 15:05:59.426731'),
(2, 1, 'terence@windkracht12.nl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-06 18:07:11.365785');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `personen`
--

DROP TABLE IF EXISTS `personen`;
CREATE TABLE IF NOT EXISTS `personen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `woonplaats` varchar(100) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `telefoon` varchar(20) DEFAULT NULL,
  `bsn` varchar(9) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `personen`
--

INSERT INTO `personen` (`id`, `user_id`, `voornaam`, `achternaam`, `adres`, `postcode`, `woonplaats`, `geboortedatum`, `telefoon`, `bsn`, `created_at`, `updated_at`) VALUES
(1, 1, 'Terence', 'Olieslager', NULL, NULL, NULL, NULL, '06-12345678', NULL, '2025-09-18 15:04:48', '2025-09-18 15:04:48');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reserveringen`
--

DROP TABLE IF EXISTS `reserveringen`;
CREATE TABLE IF NOT EXISTS `reserveringen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `persoon_id` int NOT NULL,
  `instructeur_id` int DEFAULT NULL,
  `lespakket_id` int NOT NULL,
  `locatie_id` int NOT NULL,
  `gewenste_datum` date NOT NULL,
  `bevestigde_datum` date DEFAULT NULL,
  `bevestigde_tijd` time DEFAULT NULL,
  `status` enum('aangevraagd','bevestigd','geannuleerd','afgerond') DEFAULT 'aangevraagd',
  `betaal_status` enum('wachtend','betaald','mislukt') DEFAULT 'wachtend',
  `duo_partner_id` int DEFAULT NULL,
  `opmerking` text,
  `instructeur_opmerking` text,
  `evaluatie` text,
  `voortgang` text,
  `aanbevelingen` text,
  `annulering_reden` text,
  `betaal_opmerking` text,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bijgewerkt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `persoon_id` (`persoon_id`),
  KEY `instructeur_id` (`instructeur_id`),
  KEY `lespakket_id` (`lespakket_id`),
  KEY `locatie_id` (`locatie_id`),
  KEY `duo_partner_id` (`duo_partner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('klant','instructeur','eigenaar') DEFAULT 'klant',
  `is_active` tinyint(1) DEFAULT '1',
  `activation_token` varchar(191) DEFAULT NULL,
  `reset_token` varchar(191) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role`, `is_active`, `activation_token`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'terence@windkracht12.nl', '$2y$10$LHFXivb9GYsmpSWtPFeAOeXP7k6rpIZDz42DDfXPcAEiBhO87T96e', 'eigenaar', 1, NULL, NULL, NULL, '2025-09-18 15:04:48', '2025-09-18 15:04:48');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
