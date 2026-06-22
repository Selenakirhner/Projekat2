<?php
require_once 'OsnovniModel.php';
require_once 'StavkaZahteva.php';
require_once 'Zivotinja.php';

class Zahtev extends OsnovniModel {
    protected $tabela = 'zahtev';
    private $stavkeObj;
    private $id;
    private $ime;
    private $prezime;
    private $adresa;
    private $telefon;
    private $datum;
    private $id_korisnik;
    private $stavkeLista = [];
    
    public function __construct() {
        parent::__construct();
        $this->stavkeObj = new StavkaZahteva();
    }
    
    // Getteri
    public function getId() { return $this->id; }
    public function getIme() { return $this->ime; }
    public function getPrezime() { return $this->prezime; }
    public function getAdresa() { return $this->adresa; }
    public function getTelefon() { return $this->telefon; }
    public function getDatum() { return $this->datum; }
    public function getIdKorisnik() { return $this->id_korisnik; }
    public function getStavke() { return $this->stavkeLista; }

    // Setteri
    public function setId($id) { $this->id = $id; }
    public function setIme($ime) { $this->ime = $ime; }
    public function setPrezime($prezime) { $this->prezime = $prezime; }
    public function setAdresa($adresa) { $this->adresa = $adresa; }
    public function setTelefon($telefon) { $this->telefon = $telefon; }
    public function setDatum($datum) { $this->datum = $datum; }
    public function setIdKorisnik($id_korisnik) { $this->id_korisnik = $id_korisnik; }
    public function setStavke($stavke) { $this->stavkeLista = $stavke; }
    
    public function popuni($podaci) {
        if (isset($podaci['id'])) $this->id = $podaci['id'];
        if (isset($podaci['ime'])) $this->ime = $podaci['ime'];
        if (isset($podaci['prezime'])) $this->prezime = $podaci['prezime'];
        if (isset($podaci['adresa'])) $this->adresa = $podaci['adresa'];
        if (isset($podaci['telefon'])) $this->telefon = $podaci['telefon'];
        if (isset($podaci['datum'])) $this->datum = $podaci['datum'];
        if (isset($podaci['id_korisnik'])) $this->id_korisnik = $podaci['id_korisnik'];
        if (isset($podaci['stavke'])) $this->stavkeLista = $podaci['stavke'];
        return $this;
    }
    
    public function getZivotinje() {
        $zivotinja = new Zivotinja();
        return $zivotinja->sveZaPadajuciMeni();
    }
    
    public function sacuvaj($podaci) {
        $this->baza->zapocniTransakciju();

        try {
            $sql = "SELECT COUNT(*) broj
                    FROM zahtev
                    WHERE ime = ?
                    AND prezime = ?
                    AND telefon = ?";

            $provera = $this->baza->upit($sql, [
                $podaci['ime'],
                $podaci['prezime'],
                $podaci['telefon']
            ])->fetch(PDO::FETCH_ASSOC);

            if ($provera['broj'] > 0) {
                throw new Exception('Zahtev već postoji.');
            }
            
            $rezultat = $this->baza->pozoviProceduru('sp_UnosZahteva', [
                $podaci['id_korisnik'],
                $podaci['ime'],
                $podaci['prezime'],
                $podaci['adresa'],
                $podaci['telefon'],
                $podaci['datum']
            ]);

            $row = $rezultat->fetch(PDO::FETCH_ASSOC);
            $id_zahtev = $row['id'];
            $rezultat->closeCursor();

            if (isset($podaci['stavke']) && is_array($podaci['stavke'])) {
                foreach ($podaci['stavke'] as $stavka) {
                    $stavka['id_zahtev'] = $id_zahtev;
                    $this->stavkeObj->sacuvaj($stavka);
                }
            }

            $this->baza->potvrdiTransakciju();
            return $id_zahtev;

        } catch(Exception $e) {
            $this->baza->vratiTransakciju();
            throw $e;
        }
    }
    
    public function azuriraj($id, $podaci) {
        $this->baza->zapocniTransakciju();
        
        try {
            $sql = "UPDATE zahtev SET 
                    ime = ?,
                    prezime = ?,
                    adresa = ?,
                    telefon = ?,
                    datum = ?
                    WHERE id = ?";
            
            $this->baza->upit($sql, [
                $podaci['ime'],
                $podaci['prezime'],
                $podaci['adresa'],
                $podaci['telefon'],
                $podaci['datum'],
                $id
            ]);
            
            $this->stavkeObj->obrisiPoZahtevId($id);
            
            if (isset($podaci['stavke']) && is_array($podaci['stavke'])) {
                foreach ($podaci['stavke'] as $stavka) {
                    $stavka['id_zahtev'] = $id;
                    $this->stavkeObj->sacuvaj($stavka);
                }
            }
            
            $this->baza->potvrdiTransakciju();
            return true;
            
        } catch(Exception $e) {
            $this->baza->vratiTransakciju();
            throw $e;
        }
    }
    
    public function saStavkama($id) {
        $podaci = $this->poId($id);
        if ($podaci) {
            $podaci['stavke'] = $this->stavkeObj->poZahtevId($id);
            $this->popuni($podaci);
            return $this;
        }
        return null;
    }
    
    public function sviSaStavkama() {
        $sql = "SELECT * FROM v_zahtevi ORDER BY id_zahtev DESC";
        return $this->baza->upit($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obrisi($id) {
        $this->baza->zapocniTransakciju();
        try {
            $this->stavkeObj->obrisiPoZahtevId($id);
            
            $sql = "DELETE FROM zahtev WHERE id = ?";
            $this->baza->upit($sql, [$id]);
            
            $this->baza->potvrdiTransakciju();
            return true;
        } catch(Exception $e) {
            $this->baza->vratiTransakciju();
            throw $e;
        }
    }
    
    public function filtriraj($filter) {
        $sql = "SELECT * FROM v_zahtevi 
                WHERE ime LIKE ? OR prezime LIKE ? OR adresa LIKE ? 
                ORDER BY id_zahtev DESC";
        $param = '%' . $filter . '%';
        return $this->baza->upit($sql, [$param, $param, $param])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>