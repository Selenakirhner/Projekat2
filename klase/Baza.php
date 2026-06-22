<?php
require_once dirname(__DIR__) . '/konfiguracija/baza.php';

class Baza {
    private static $instanca = null;
    private $konekcija;
    
    private function __construct() {
        try {
            $this->konekcija = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $this->konekcija->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Greška pri povezivanju: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instanca == null) {
            self::$instanca = new Baza();
        }
        return self::$instanca;
    }
    
    public function getKonekcija() {
        return $this->konekcija;
    }
    
    public function upit($sql, $parametri = []) {
        $stmt = $this->konekcija->prepare($sql);
        $stmt->execute($parametri);
        return $stmt;
    }
    
    public function insert($sql, $parametri = []) {
        $stmt = $this->konekcija->prepare($sql);
        $stmt->execute($parametri);
        return $this->konekcija->lastInsertId();
    }
    
    public function pozoviProceduru($naziv, $parametri = []) {
        $placeholderi = implode(',', array_fill(0, count($parametri), '?'));
        $sql = "CALL $naziv($placeholderi)";
        $stmt = $this->konekcija->prepare($sql);
        $stmt->execute($parametri);
        return $stmt;
    }

    public function zapocniTransakciju() {
        return $this->konekcija->beginTransaction();
    }

    public function potvrdiTransakciju() {
        return $this->konekcija->commit();
    }

    public function vratiTransakciju() {
        return $this->konekcija->rollback();
    }
}
?>