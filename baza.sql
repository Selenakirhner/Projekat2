CREATE DATABASE IF NOT EXISTS udomljavanje;
USE udomljavanje;

CREATE TABLE IF NOT EXISTS korisnik (
    id_korisnik INT AUTO_INCREMENT PRIMARY KEY,
    korisnicko_ime VARCHAR(50) NOT NULL UNIQUE,
    lozinka VARCHAR(255) NOT NULL,
    ime VARCHAR(50),
    prezime VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS zivotinja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vrsta VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS zahtev (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_korisnik INT NOT NULL,
    ime VARCHAR(50) NOT NULL,
    prezime VARCHAR(50) NOT NULL,
    adresa VARCHAR(200) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    datum DATE NOT NULL,
    FOREIGN KEY (id_korisnik) REFERENCES korisnik(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS stavka_zahteva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_zahtev INT NOT NULL,
    id_zivotinja INT NOT NULL,
    ime_zivotinje VARCHAR(50) NOT NULL,
    starost VARCHAR(20) NOT NULL,
    napomena TEXT,
    FOREIGN KEY (id_zahtev) REFERENCES zahtev(id) ON DELETE CASCADE,
    FOREIGN KEY (id_zivotinja) REFERENCES zivotinja(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO zivotinja (vrsta) VALUES 
('pas'),
('mačka'),
('ptica'),
('hrčak'),
('zečić'),
('kornjača'),
('papagaj');

INSERT INTO korisnik (korisnicko_ime, lozinka, ime, prezime) VALUES 
('admin', MD5('admin123'), 'Admin', '1'),
('selena', MD5('selena123'), 'Selena', 'Kirhner');

DROP PROCEDURE IF EXISTS sp_UnosZahteva;
DELIMITER //
CREATE PROCEDURE sp_UnosZahteva(
    IN p_id_korisnik INT,
    IN p_ime VARCHAR(50),
    IN p_prezime VARCHAR(50),
    IN p_adresa VARCHAR(200),
    IN p_telefon VARCHAR(20),
    IN p_datum DATE
)
BEGIN
    INSERT INTO zahtev (id_korisnik, ime, prezime, adresa, telefon, datum)
    VALUES (p_id_korisnik, p_ime, p_prezime, p_adresa, p_telefon, p_datum);
    SELECT LAST_INSERT_ID() AS id;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_UnosStavke;
DELIMITER //
CREATE PROCEDURE sp_UnosStavke(
    IN p_id_zahtev INT,
    IN p_id_zivotinja INT,
    IN p_ime_zivotinje VARCHAR(50),
    IN p_starost VARCHAR(20),
    IN p_napomena TEXT
)
BEGIN
    INSERT INTO stavka_zahteva (id_zahtev, id_zivotinja, ime_zivotinje, starost, napomena)
    VALUES (p_id_zahtev, p_id_zivotinja, p_ime_zivotinje, p_starost, p_napomena);
END //
DELIMITER ;

CREATE OR REPLACE VIEW v_zahtevi AS
SELECT 
    z.id AS id_zahtev,
    z.ime,
    z.prezime,
    z.adresa,
    z.telefon,
    z.datum,
    k.korisnicko_ime,
    COUNT(sz.id) AS broj_zivotinja
FROM zahtev z
JOIN korisnik k ON z.id_korisnik = k.id
LEFT JOIN stavka_zahteva sz ON z.id = sz.id_zahtev
GROUP BY z.id;

CREATE OR REPLACE VIEW v_stavke_zahteva AS
SELECT 
    sz.id AS id_stavke,
    sz.id_zahtev,
    sz.id_zivotinja,
    sz.ime_zivotinje,
    sz.starost,
    sz.napomena,
    z.vrsta
FROM stavka_zahteva sz
JOIN zivotinja z ON sz.id_zivotinja = z.id;

SHOW TABLES;

SELECT * FROM korisnik;
SELECT * FROM zivotinja;