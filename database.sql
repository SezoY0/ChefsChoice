CREATE DATABASE IF NOT EXISTS chefs_choice;
USE chefs_choice;

DROP TABLE IF EXISTS bestelling_regels;
DROP TABLE IF EXISTS bestellingen;
DROP TABLE IF EXISTS producten;
DROP TABLE IF EXISTS gebruikers;
DROP TABLE IF EXISTS kortingscodes;

CREATE TABLE gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    wachtwoord VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE producten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    prijs DECIMAL(8,2) NOT NULL,
    beschrijving TEXT NOT NULL,
    categorie VARCHAR(50) NOT NULL,
    afbeelding VARCHAR(255) DEFAULT NULL
);

CREATE TABLE bestellingen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL,
    totaal DECIMAL(8,2) NOT NULL,
    kortingscode VARCHAR(50) DEFAULT NULL,
    korting DECIMAL(8,2) DEFAULT 0,
    datum TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id)
);

CREATE TABLE bestelling_regels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bestelling_id INT NOT NULL,
    product_id INT NOT NULL,
    aantal INT NOT NULL,
    prijs DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (bestelling_id) REFERENCES bestellingen(id),
    FOREIGN KEY (product_id) REFERENCES producten(id)
);

CREATE TABLE kortingscodes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('percentage', 'vast') NOT NULL,
    waarde DECIMAL(8,2) NOT NULL,
    actief TINYINT(1) DEFAULT 1
);

INSERT INTO producten (naam, prijs, beschrijving, categorie, afbeelding) VALUES
('Japans koksmes', 89.95, 'Scherp mes voor vlees, vis en groenten.', 'Messen', 'koksmes.png'),
('Broodmes', 34.95, 'Kartelmes om brood netjes te snijden.', 'Messen', 'broodmes.png'),
('Fileermes', 49.95, 'Dun mes voor het fileren van vis of vlees.', 'Messen', 'fileermes.png'),
('Gietijzeren braadpan', 79.95, 'Een stevige pan die warmte goed vasthoudt, ideaal voor stoofgerechten en bakken.', 'Kookgerei', 'braadpan.png'),
('Wokpan', 44.95, 'Diepe pan voor roerbakgerechten en noedels.', 'Kookgerei', 'wokpan.png'),
('Koekenpan', 39.95, 'Basispan voor bakken van eieren, vlees en groenten.', 'Kookgerei', 'koekenpan.png'),
('Siliconen bakvormenset', 24.95, 'Flexibele vormen voor cakes, muffins en desserts.', 'Bakbenodigdheden', 'bakvorm.png'),
('Deegroller', 12.95, 'Hulpmiddel om deeg gelijkmatig uit te rollen.', 'Bakbenodigdheden', 'deegroller.png'),
('Muffinvorm', 14.95, 'Vorm om meerdere muffins tegelijk te bakken.', 'Bakbenodigdheden', 'muffinvorm.png'),
('Truffelolie extra vierge', 14.95, 'Luxe olie voor pasta, risotto en salades.', 'Ingrediënten', 'truffelolie.png'),
('Saffraan premium', 11.95, 'Exclusieve specerij voor rijstgerechten en sauzen.', 'Ingrediënten', 'saffraan.png'),
('Vanillestokjes', 8.95, 'Natuurlijke smaakmaker voor desserts en gebak.', 'Ingrediënten', 'vanille.png');

INSERT INTO kortingscodes (code, type, waarde, actief) VALUES
('CHEF10', 'percentage', 10, 1),
('WELKOM5', 'vast', 5, 1),
('STUDENT15', 'percentage', 15, 1);