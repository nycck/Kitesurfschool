-- Dummy Data voor Kitesurfschool Windkracht-12
-- Gebruik deze data voor test doeleinden

USE `kitesurfschool_windkracht12`;

-- --------------------------------------------------------
-- USERS & PERSONEN (Eigenaar bestaat al, voeg instructeurs en klanten toe)
-- --------------------------------------------------------

-- Password voor alle dummy users: "Password123!" 
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- INSTRUCTEURS (5x)
INSERT INTO `users` (`email`, `password_hash`, `role`, `is_active`, `activation_token`) VALUES
('lisa.jansen@windkracht12.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructeur', 1, NULL),
('mark.devries@windkracht12.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructeur', 1, NULL),
('sarah.peters@windkracht12.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructeur', 1, NULL),
('tom.bakker@windkracht12.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructeur', 1, NULL),
('emma.smit@windkracht12.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructeur', 1, NULL);

-- KLANTEN (10x)
INSERT INTO `users` (`email`, `password_hash`, `role`, `is_active`) VALUES
('jan.vandenberg@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('anna.meijer@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('peter.dejong@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('sophie.vandijk@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('lucas.visser@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('emma.hendriks@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('daan.vanleeuwen@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('julia.dekker@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('lars.mulder@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1),
('nora.brouwer@email.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'klant', 1);

-- PERSONEN DATA voor Instructeurs
INSERT INTO `personen` (`user_id`, `voornaam`, `achternaam`, `adres`, `postcode`, `woonplaats`, `geboortedatum`, `telefoon`, `bsn`) VALUES
(2, 'Lisa', 'Jansen', 'Strandweg 45', '2041 KL', 'Zandvoort', '1992-03-15', '06-12345001', '123456789'),
(3, 'Mark', 'de Vries', 'Kustlaan 23', '1976 CA', 'IJmuiden', '1988-07-22', '06-12345002', '234567890'),
(4, 'Sarah', 'Peters', 'Duinweg 67', '2586 AB', 'Den Haag', '1995-11-08', '06-12345003', '345678901'),
(5, 'Tom', 'Bakker', 'Zeelaan 12', '1398 BN', 'Muiden', '1990-05-30', '06-12345004', '456789012'),
(6, 'Emma', 'Smit', 'Boulevard 89', '1949 BG', 'Wijk aan Zee', '1993-09-17', '06-12345005', '567890123');

-- PERSONEN DATA voor Klanten
INSERT INTO `personen` (`user_id`, `voornaam`, `achternaam`, `adres`, `postcode`, `woonplaats`, `geboortedatum`, `telefoon`) VALUES
(7, 'Jan', 'van den Berg', 'Hoofdstraat 123', '1012 AB', 'Amsterdam', '1985-04-12', '06-23456001'),
(8, 'Anna', 'Meijer', 'Lange Straat 45', '3011 BC', 'Rotterdam', '1990-08-25', '06-23456002'),
(9, 'Peter', 'de Jong', 'Marktplein 67', '3511 CD', 'Utrecht', '1982-12-03', '06-23456003'),
(10, 'Sophie', 'van Dijk', 'Parkweg 89', '6511 DE', 'Nijmegen', '1995-02-18', '06-23456004'),
(11, 'Lucas', 'Visser', 'Dorpsstraat 34', '9711 EF', 'Groningen', '1988-06-30', '06-23456005'),
(12, 'Emma', 'Hendriks', 'Kerkstraat 56', '5611 FG', 'Eindhoven', '1993-10-14', '06-23456006'),
(13, 'Daan', 'van Leeuwen', 'Schoolweg 78', '2011 GH', 'Haarlem', '1991-03-22', '06-23456007'),
(14, 'Julia', 'Dekker', 'Molenstraat 90', '4811 HI', 'Breda', '1987-07-09', '06-23456008'),
(15, 'Lars', 'Mulder', 'Stationsweg 12', '7511 IJ', 'Enschede', '1994-11-27', '06-23456009'),
(16, 'Nora', 'Brouwer', 'Havenweg 34', '8011 JK', 'Zwolle', '1989-05-16', '06-23456010');

-- --------------------------------------------------------
-- RESERVERINGEN (15x - mix van statussen)
-- --------------------------------------------------------

INSERT INTO `reserveringen` (`persoon_id`, `instructeur_id`, `lespakket_id`, `locatie_id`, `gewenste_datum`, `bevestigde_datum`, `bevestigde_tijd`, `status`, `betaal_status`, `duo_partner_id`, `opmerking`, `aangemaakt_op`) VALUES
-- Afgeronde reserveringen (met instructeur en betaald)
(2, 2, 1, 1, '2025-10-15', '2025-10-15', '10:00:00', 'afgerond', 'betaald', NULL, 'Eerste privéles, klant heeft goede voortgang gemaakt.', '2025-10-01 09:00:00'),
(3, 3, 2, 2, '2025-10-20', '2025-10-20', '14:00:00', 'afgerond', 'betaald', 4, 'Duo les verlopen prima, beide deelnemers enthousiast.', '2025-10-05 10:30:00'),
(5, 4, 3, 3, '2025-11-01', '2025-11-01', '09:00:00', 'afgerond', 'betaald', 6, 'Pakket van 3 lessen afgerond, klanten zijn gevorderd.', '2025-10-15 14:20:00'),
(7, 5, 1, 4, '2025-11-10', '2025-11-10', '11:00:00', 'afgerond', 'betaald', NULL, 'Privéles bij IJmuiden, goede wind condities.', '2025-10-25 08:45:00'),
(9, 2, 2, 5, '2025-11-12', '2025-11-12', '15:00:00', 'afgerond', 'betaald', 10, 'Duo les Scheveningen, beide beginners nu op niveau.', '2025-10-28 16:10:00'),

-- Bevestigde reserveringen (aankomend, betaald)
(11, 3, 1, 1, '2025-11-28', '2025-11-28', '10:00:00', 'bevestigd', 'betaald', NULL, 'Privéles Zandvoort, klant heeft ervaring.', '2025-11-15 09:15:00'),
(13, 4, 4, 2, '2025-11-30', '2025-11-30', '13:00:00', 'bevestigd', 'betaald', 14, 'Duo pakket 5 lessen, start volgende week.', '2025-11-18 10:45:00'),
(15, 5, 2, 3, '2025-12-02', '2025-12-02', '09:30:00', 'bevestigd', 'betaald', 16, 'Introductie duo les Wijk aan Zee.', '2025-11-20 11:30:00'),

-- Bevestigde reserveringen (wachtend op betaling)
(8, 2, 3, 4, '2025-12-05', '2025-12-05', '14:00:00', 'bevestigd', 'wachtend', 12, 'Duo pakket 3 lessen, betaling volgt.', '2025-11-21 13:20:00'),
(10, 3, 1, 5, '2025-12-08', '2025-12-08', '11:00:00', 'bevestigd', 'wachtend', NULL, 'Privéles Scheveningen, nog niet betaald.', '2025-11-22 14:50:00'),

-- Aangevraagde reserveringen (nog te bevestigen)
(2, NULL, 1, 1, '2025-12-15', NULL, NULL, 'aangevraagd', 'wachtend', NULL, 'Aanvraag voor privéles half december.', '2025-11-23 09:00:00'),
(4, NULL, 2, 2, '2025-12-18', NULL, NULL, 'aangevraagd', 'wachtend', 5, 'Duo les aanvraag, flexibel met datum.', '2025-11-23 10:15:00'),
(6, NULL, 3, 3, '2025-12-20', NULL, NULL, 'aangevraagd', 'wachtend', 7, 'Pakket van 3 lessen, voorkeur ochtend.', '2025-11-23 11:30:00'),

-- Geannuleerde reserveringen
(14, 4, 1, 4, '2025-11-25', '2025-11-25', '10:00:00', 'geannuleerd', 'wachtend', NULL, 'Klant had onverwachte verhindering.', '2025-11-10 15:40:00'),
(16, 5, 2, 6, '2025-11-26', '2025-11-26', '14:00:00', 'geannuleerd', 'wachtend', 11, 'Slechte weersomstandigheden, les verplaatst.', '2025-11-12 16:25:00');

-- --------------------------------------------------------
-- LES SESSIES (voor afgeronde reserveringen)
-- --------------------------------------------------------

INSERT INTO `les_sessies` (`reservering_id`, `les_datum`, `start_tijd`, `eind_tijd`, `status`) VALUES
-- Reservering 1 (privéles - 1 sessie)
(1, '2025-10-15', '10:00:00', '12:30:00', 'voltooid'),

-- Reservering 2 (duo les - 1 sessie)
(2, '2025-10-20', '14:00:00', '17:30:00', 'voltooid'),

-- Reservering 3 (duo pakket 3 lessen - 3 sessies)
(3, '2025-11-01', '09:00:00', '12:30:00', 'voltooid'),
(3, '2025-11-03', '09:00:00', '12:30:00', 'voltooid'),
(3, '2025-11-05', '09:00:00', '12:30:00', 'voltooid'),

-- Reservering 4 (privéles - 1 sessie)
(4, '2025-11-10', '11:00:00', '13:30:00', 'voltooid'),

-- Reservering 5 (duo les - 1 sessie)
(5, '2025-11-12', '15:00:00', '18:30:00', 'voltooid'),

-- Toekomstige sessies voor bevestigde reserveringen
(6, '2025-11-28', '10:00:00', '12:30:00', 'gepland'),
(7, '2025-11-30', '13:00:00', '16:30:00', 'gepland'),
(7, '2025-12-02', '13:00:00', '16:30:00', 'gepland'),
(7, '2025-12-04', '13:00:00', '16:30:00', 'gepland'),
(7, '2025-12-06', '13:00:00', '16:30:00', 'gepland'),
(7, '2025-12-08', '13:00:00', '16:30:00', 'gepland'),
(8, '2025-12-02', '09:30:00', '13:00:00', 'gepland');

-- --------------------------------------------------------
-- EMAIL LOGS (verzonden emails)
-- --------------------------------------------------------

INSERT INTO `email_logs` (`naar_email`, `onderwerp`, `bericht`, `type`, `verzonden_at`, `status`) VALUES
('jan.vandenberg@email.nl', 'Bevestiging Kitesurfles', 'Uw privéles is bevestigd voor 15 oktober 2025 om 10:00.', 'bevestiging', '2025-10-01 09:05:00', 'verzonden'),
('anna.meijer@email.nl', 'Bevestiging Duo Kitesurfles', 'Uw duo les is bevestigd voor 20 oktober 2025 om 14:00.', 'bevestiging', '2025-10-05 10:35:00', 'verzonden'),
('lucas.visser@email.nl', 'Betalingsbevestiging', 'Bedankt voor uw betaling van €375,00 voor het duo lespakket.', 'betaling', '2025-10-15 14:25:00', 'verzonden'),
('lars.mulder@email.nl', 'Bevestiging Privéles', 'Uw privéles bij IJmuiden is bevestigd voor 10 november 2025.', 'bevestiging', '2025-10-25 08:50:00', 'verzonden'),
('julia.dekker@email.nl', 'Annulering Kitesurfles', 'Uw les van 25 november is geannuleerd zoals gevraagd.', 'annulering', '2025-11-10 15:45:00', 'verzonden'),
('emma.hendriks@email.nl', 'Bevestiging Boeking', 'Uw privéles is bevestigd voor 28 november 2025 om 10:00.', 'bevestiging', '2025-11-15 09:20:00', 'verzonden'),
('daan.vanleeuwen@email.nl', 'Bevestiging Duo Lespakket', 'Uw duo pakket van 5 lessen start op 30 november 2025.', 'bevestiging', '2025-11-18 10:50:00', 'verzonden'),
('nora.brouwer@email.nl', 'Bevestiging Duo Les', 'Uw introductie duo les is bevestigd voor 2 december 2025.', 'bevestiging', '2025-11-20 11:35:00', 'verzonden');

-- --------------------------------------------------------
-- LOGIN LOGS (recente login activiteit)
-- --------------------------------------------------------

INSERT INTO `login_logs` (`user_id`, `email`, `actie`, `ip_adres`, `user_agent`, `tijdstip`) VALUES
-- Eigenaar logins
(1, 'terence@windkracht12.nl', 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-11-24 08:00:00'),
(1, 'terence@windkracht12.nl', 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-11-24 17:00:00'),

-- Instructeurs logins
(2, 'lisa.jansen@windkracht12.nl', 'login', '192.168.1.10', 'Mozilla/5.0 (iPhone; CPU iPhone OS)', '2025-11-24 07:30:00'),
(3, 'mark.devries@windkracht12.nl', 'login', '192.168.1.11', 'Mozilla/5.0 (Windows NT 10.0)', '2025-11-24 08:15:00'),
(4, 'sarah.peters@windkracht12.nl', 'login', '192.168.1.12', 'Mozilla/5.0 (Macintosh; Intel Mac OS X)', '2025-11-23 09:45:00'),
(5, 'tom.bakker@windkracht12.nl', 'login', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0)', '2025-11-23 10:20:00'),
(6, 'emma.smit@windkracht12.nl', 'login', '192.168.1.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS)', '2025-11-23 11:00:00'),

-- Klanten logins
(7, 'jan.vandenberg@email.nl', 'login', '192.168.2.10', 'Mozilla/5.0 (Windows NT 10.0)', '2025-11-23 14:30:00'),
(8, 'anna.meijer@email.nl', 'login', '192.168.2.11', 'Mozilla/5.0 (iPad; CPU OS)', '2025-11-23 15:00:00'),
(9, 'peter.dejong@email.nl', 'login', '192.168.2.12', 'Mozilla/5.0 (Android)', '2025-11-22 16:45:00'),
(10, 'sophie.vandijk@email.nl', 'login', '192.168.2.13', 'Mozilla/5.0 (Windows NT 10.0)', '2025-11-22 17:20:00'),
(11, 'lucas.visser@email.nl', 'login', '192.168.2.14', 'Mozilla/5.0 (Macintosh)', '2025-11-22 18:00:00');

-- ========================================================
-- SAMENVATTING DUMMY DATA:
-- ========================================================
-- - 1 Eigenaar (al bestaand: terence@windkracht12.nl)
-- - 5 Instructeurs (lisa, mark, sarah, tom, emma)
-- - 10 Klanten (jan, anna, peter, sophie, lucas, emma, daan, julia, lars, nora)
-- - 15 Reserveringen (5 afgerond, 3 bevestigd+betaald, 2 bevestigd+wachtend, 3 aangevraagd, 2 geannuleerd)
-- - 13 Les Sessies (mix van voltooide en geplande lessen)
-- - 8 Email Logs (verzonden emails)
-- - 13 Login Logs (recente login activiteit)
-- 
-- Alle dummy users hebben wachtwoord: "Password123!"
-- ========================================================
