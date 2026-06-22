<?php
require_once 'OsnovniModel.php';

class StavkaZahteva extends OsnovniModel {
    protected $tabela = 'stavka_zahteva';
    
    public function sacuvaj($podaci) {
        $rezultat = $this->baza->pozoviProceduru('sp_UnosStavke', [
            $podaci['id_zahtev'],
            $podaci['id_zivotinja'],
            $podaci['ime_zivotinje'],
            $podaci['starost'],
            $podaci['napomena']
        ]);
        
        return $rezultat;
    }
    
    public function azuriraj($id, $podaci) {
        $sql = "UPDATE stavka_zahteva SET 
                id_zivotinja = ?,
                ime_zivotinje = ?,
                starost = ?,
                napomena = ?
                WHERE id = ?";
        return $this->baza->upit($sql, [
            $podaci['id_zivotinja'],
            $podaci['ime_zivotinje'],
            $podaci['starost'],
            $podaci['napomena'],
            $id
        ]);
    }

    public function poZahtevId($id_zahtev) {
        $sql = "SELECT * FROM v_stavke_zahteva WHERE id_zahtev = ?";
        return $this->baza->upit($sql, [$id_zahtev])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obrisiPoZahtevId($id_zahtev) {
        $sql = "DELETE FROM stavka_zahteva WHERE id_zahtev = ?";
        return $this->baza->upit($sql, [$id_zahtev]);
    }
}
?>