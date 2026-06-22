<?php
require_once 'OsnovniModel.php';

class Korisnik extends OsnovniModel {
    protected $tabela = 'korisnik';
    
    public function sacuvaj($podaci) {

        $provera = $this->baza->upit(
            "SELECT COUNT(*) FROM korisnik WHERE korisnicko_ime = ?",
            [$podaci['korisnicko_ime']]
        )->fetchColumn();

        if ($provera > 0) {
            throw new Exception("Korisničko ime već postoji.");
        }

        $sql = "INSERT INTO korisnik (korisnicko_ime, lozinka, ime, prezime)
                VALUES (?, ?, ?, ?)";

        return $this->baza->upit($sql, [
            $podaci['korisnicko_ime'],
            md5($podaci['lozinka']),
            $podaci['ime'],
            $podaci['prezime']
        ]);
    }
    
    public function azuriraj($id, $podaci) {
        $sql = "UPDATE korisnik SET korisnicko_ime = ?, ime = ?, prezime = ? WHERE id = ?";
        $parametri = [$podaci['korisnicko_ime'], $podaci['ime'], $podaci['prezime'], $id];
        
        if (!empty($podaci['lozinka'])) {
            $sql = "UPDATE korisnik SET korisnicko_ime = ?, lozinka = ?, ime = ?, prezime = ? WHERE id = ?";
            $parametri = [$podaci['korisnicko_ime'], md5($podaci['lozinka']), $podaci['ime'], $podaci['prezime'], $id];
        }
        
        return $this->baza->upit($sql, $parametri);
    }
    
    public function login($korisnicko_ime, $lozinka) {
        $sql = "SELECT * FROM korisnik WHERE korisnicko_ime = ? AND lozinka = ?";
        $rezultat = $this->baza->upit($sql, [$korisnicko_ime, md5($lozinka)])->fetch(PDO::FETCH_ASSOC);
        return $rezultat;
    }
}
?>