# Dummy Data Importeren

## Overzicht Dummy Data

Dit bestand bevat realistische test data voor de Kitesurfschool Windkracht-12 applicatie.

### Inbegrepen Data:

- **1 Eigenaar** (al bestaand in database)
- **5 Instructeurs** met complete profielinformatie
- **10 Klanten** met complete profielinformatie  
- **15 Reserveringen** in verschillende statussen
- **13 Les Sessies** (voltooide en geplande lessen)
- **8 Email Logs** (verzonden emails)
- **13 Login Logs** (recente login activiteit)

### Gebruikers Login Gegevens:

**Eigenaar:**
- Email: `terence@windkracht12.nl`
- Password: `Password123!`

**Instructeurs:**
- `lisa.jansen@windkracht12.nl` - Password: `Password123!`
- `mark.devries@windkracht12.nl` - Password: `Password123!`
- `sarah.peters@windkracht12.nl` - Password: `Password123!`
- `tom.bakker@windkracht12.nl` - Password: `Password123!`
- `emma.smit@windkracht12.nl` - Password: `Password123!`

**Klanten:**
- `jan.vandenberg@email.nl` - Password: `Password123!`
- `anna.meijer@email.nl` - Password: `Password123!`
- `peter.dejong@email.nl` - Password: `Password123!`
- `sophie.vandijk@email.nl` - Password: `Password123!`
- `lucas.visser@email.nl` - Password: `Password123!`
- En 5 andere klanten (emma.hendriks, daan.vanleeuwen, julia.dekker, lars.mulder, nora.brouwer)

## Importeren via phpMyAdmin

1. Open phpMyAdmin in je browser: `http://localhost/phpmyadmin`
2. Selecteer de database `kitesurfschool_windkracht12`
3. Klik op het "SQL" tabblad
4. Open het bestand `dummy_data.sql` in een teksteditor
5. Kopieer de volledige inhoud
6. Plak de inhoud in het SQL venster
7. Klik op "Go" om de data te importeren

## Importeren via Command Line

```bash
cd C:\xampp\htdocs\VSHERKANSING\app\db
mysql -u root -p kitesurfschool_windkracht12 < dummy_data.sql
```

## Importeren via PowerShell (Windows)

```powershell
cd C:\xampp\htdocs\VSHERKANSING\app\db
Get-Content dummy_data.sql | & "C:\xampp\mysql\bin\mysql.exe" -u root kitesurfschool_windkracht12
```

## Verificatie

Na het importeren kun je verifiëren door in te loggen als een van de gebruikers:

1. **Als Eigenaar**: Zie alle reserveringen, gebruikers, en statistieken
2. **Als Instructeur**: Zie toegewezen lessen en klanten
3. **Als Klant**: Zie eigen reserveringen en planning

## Data Statistieken

### Reserveringen per Status:
- Afgerond: 5 (alle betaald)
- Bevestigd: 5 (3 betaald, 2 wachtend op betaling)
- Aangevraagd: 3 (nog te bevestigen)
- Geannuleerd: 2

### Lespakketten Gebruikt:
- Privéles: 7x
- Losse Duo Kiteles: 4x
- Duo lespakket 3 lessen: 3x
- Duo lespakket 5 lessen: 1x

### Locaties Gebruikt:
- Zandvoort: 4x
- Muiderberg: 3x
- Wijk aan Zee: 3x
- IJmuiden: 2x
- Scheveningen: 2x
- Hoek van Holland: 1x

## Troubleshooting

### Error: Duplicate entry
Als je deze error krijgt, betekent het dat sommige data al bestaat. Je kunt:
1. De database eerst legen (DROP en opnieuw CREATE)
2. Of alleen de nieuwe dummy data handmatig toevoegen

### Error: Foreign key constraint
Zorg ervoor dat je eerst het `database_schema.sql` hebt geïmporteerd voordat je `dummy_data.sql` importeert.

## Reset Database (Optioneel)

Als je de database volledig wilt resetten en opnieuw beginnen:

```sql
DROP DATABASE IF EXISTS `kitesurfschool_windkracht12`;
```

Daarna importeer eerst `database_schema.sql` en dan `dummy_data.sql`.
