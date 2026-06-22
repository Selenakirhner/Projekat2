<?php
require_once 'OsnovniModel.php';

class Zivotinja extends OsnovniModel {
    protected $tabela = 'zivotinja';
    
    public function sacuvaj($podaci) {
        $sql = "INSERT INTO zivotinja (vrsta) VALUES (?)";
        return $this->baza->upit($sql, [$podaci['vrsta']]);
    }
    
    public function azuriraj($id, $podaci) {
        $sql = "UPDATE zivotinja SET vrsta = ? WHERE id = ?";
        return $this->baza->upit($sql, [$podaci['vrsta'], $id]);
    }
    
    public function sveZaPadajuciMeni() {
        $sql = "SELECT id, vrsta FROM zivotinja ORDER BY vrsta";
        return $this->baza->upit($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>