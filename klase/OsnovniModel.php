<?php
require_once 'Baza.php';

abstract class OsnovniModel {
    protected $baza;
    protected $tabela;
    
    public function __construct() {
        $this->baza = Baza::getInstance();
    }
    
    public function svi() {
        $sql = "SELECT * FROM " . $this->tabela;
        return $this->baza->upit($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function poId($id) {
        $sql = "SELECT * FROM " . $this->tabela . " WHERE id = ?";
        $rezultat = $this->baza->upit($sql, [$id])->fetch(PDO::FETCH_ASSOC);
        return $rezultat;
    }
    
    public function obrisi($id) {
        $sql = "DELETE FROM " . $this->tabela . " WHERE id = ?";
        return $this->baza->upit($sql, [$id]);
    }

    abstract public function sacuvaj($podaci);
    abstract public function azuriraj($id, $podaci);
}
?>