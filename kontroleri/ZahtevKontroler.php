<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/klase/Zahtev.php';
require_once dirname(__DIR__) . '/klase/Zivotinja.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?akcija=login');
    exit();
}

class ZahtevKontroler {
    private $zahtev;
    
    public function __construct() {
        $this->zahtev = new Zahtev();
    }
    
    public function index() {
        $filter = $_GET['filter'] ?? '';
        if (!empty($filter)) {
            $zahtevi = $this->zahtev->filtriraj($filter);
        } else {
            $zahtevi = $this->zahtev->sviSaStavkama();
        }
        include dirname(__DIR__) . '/prikazi/zahtevi/lista.php';
    }
    
    public function show() {
        $id = $_GET['id'] ?? 0;
        $zahtev = $this->zahtev->saStavkama($id);
        if (!$zahtev) {
            header('Location: index.php?akcija=lista');
            exit();
        }
        include dirname(__DIR__) . '/prikazi/zahtevi/prikaz.php';
    }
    
    public function create() {
        $zivotinje = $this->zahtev->getZivotinje();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->validiraj($_POST);
            
            if (empty($errors)) {
                try {
                    $podaci = [
                        'id_korisnik' => $_SESSION['user']['id'],
                        'ime' => $_POST['ime'],
                        'prezime' => $_POST['prezime'],
                        'adresa' => $_POST['adresa'],
                        'telefon' => $_POST['telefon'],
                        'datum' => $_POST['datum'],
                        'stavke' => []
                    ];
                    
                    if (isset($_POST['id_zivotinja'])) {
                        for ($i = 0; $i < count($_POST['id_zivotinja']); $i++) {
                            if (!empty($_POST['id_zivotinja'][$i]) && !empty($_POST['ime_zivotinje'][$i])) {
                                $podaci['stavke'][] = [
                                    'id_zivotinja' => $_POST['id_zivotinja'][$i],
                                    'ime_zivotinje' => $_POST['ime_zivotinje'][$i],
                                    'starost' => $_POST['starost'][$i] ?? '',
                                    'napomena' => $_POST['napomena'][$i] ?? ''
                                ];
                            }
                        }
                    }
                    
                    $this->zahtev->sacuvaj($podaci);
                    
                    $_SESSION['success'] = "Operacija uspešno izvršena!";
                    header('Location: /udomljavanje/index.php?akcija=lista');
                    exit();
                    
                } catch(Exception $e) {
                    $errors[] = "Greška pri unosu: " . $e->getMessage();
                }
            }
        }
        
        include dirname(__DIR__) . '/prikazi/zahtevi/unos.php';
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $zahtev = $this->zahtev->saStavkama($id);
        if (!$zahtev) {
            header('Location: /udomljavanje/index.php?akcija=lista');
            exit();
        }
        $zivotinje = $this->zahtev->getZivotinje();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->validiraj($_POST);
            
            if (empty($errors)) {
                try {
                    $podaci = [
                        'ime' => $_POST['ime'],
                        'prezime' => $_POST['prezime'],
                        'adresa' => $_POST['adresa'],
                        'telefon' => $_POST['telefon'],
                        'datum' => $_POST['datum'],
                        'stavke' => []
                    ];
                    
                    if (isset($_POST['id_zivotinja'])) {
                        for ($i = 0; $i < count($_POST['id_zivotinja']); $i++) {
                            if (!empty($_POST['id_zivotinja'][$i]) && !empty($_POST['ime_zivotinje'][$i])) {
                                $podaci['stavke'][] = [
                                    'id_zivotinja' => $_POST['id_zivotinja'][$i],
                                    'ime_zivotinje' => $_POST['ime_zivotinje'][$i],
                                    'starost' => $_POST['starost'][$i] ?? '',
                                    'napomena' => $_POST['napomena'][$i] ?? ''
                                ];
                            }
                        }
                    }
                    
                    $this->zahtev->azuriraj($id, $podaci);
                    
                    $_SESSION['success'] = "Operacija uspešno izvršena!";
                    header('Location: /udomljavanje/index.php?akcija=lista');
                    exit();
                    
                } catch(Exception $e) {
                    $errors[] = "Greška pri izmeni: " . $e->getMessage();
                }
            }
        }
        
        include dirname(__DIR__) . '/prikazi/zahtevi/izmena.php';
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            try {
                $this->zahtev->obrisi($id);
                header('Location: /udomljavanje/index.php?akcija=lista&success=1');
                exit();
            } catch(Exception $e) {
                header('Location: /udomljavanje/index.php?akcija=lista&error=1');
                exit();
            }
        } else {
            header('Location: /udomljavanje/index.php?akcija=lista');
            exit();
        }
    }
    
    public function print() {
        $id = $_GET['id'] ?? 0;
        $zahtev = $this->zahtev->saStavkama($id);
        if (!$zahtev) {
            header('Location: /udomljavanje/index.php?akcija=lista');
            exit();
        }
        include dirname(__DIR__) . '/prikazi/stampa/zahtev.php';
    }
    
    public function printList() {
        $filter = $_GET['filter'] ?? '';
        if (!empty($filter)) {
            $zahtevi = $this->zahtev->filtriraj($filter);
        } else {
            $zahtevi = $this->zahtev->sviSaStavkama();
        }
        include dirname(__DIR__) . '/prikazi/stampa/lista.php';
    }
    
    private function validiraj($podaci) {
        $errors = [];
        
        if (empty($podaci['ime'])) $errors[] = "Ime je obavezno";
        if (empty($podaci['prezime'])) $errors[] = "Prezime je obavezno";
        if (empty($podaci['adresa'])) $errors[] = "Adresa je obavezna";
        if (empty($podaci['telefon'])) $errors[] = "Telefon je obavezan";
        if (empty($podaci['datum'])) $errors[] = "Datum je obavezan";
        
        if (strlen($podaci['ime']) > 50) $errors[] = "Ime ne sme biti duže od 50 karaktera";
        if (strlen($podaci['prezime']) > 50) $errors[] = "Prezime ne sme biti duže od 50 karaktera";
        if (strlen($podaci['adresa']) > 200) $errors[] = "Adresa ne sme biti duža od 200 karaktera";
        if (strlen($podaci['telefon']) > 20) $errors[] = "Telefon ne sme biti duži od 20 karaktera";
        
        if (!empty($podaci['telefon']) && !preg_match('/^[0-9+\- ]+$/', $podaci['telefon'])) {
            $errors[] = "Telefon može sadržati samo brojeve, + i -";
        }
        
        return $errors;
    }
}

$akcija = $_GET['akcija'] ?? 'lista';
$kontroler = new ZahtevKontroler();

switch($akcija) {
    case 'lista':
        $kontroler->index();
        break;
    case 'prikaz':
        $kontroler->show();
        break;
    case 'unos':
        $kontroler->create();
        break;
    case 'izmena':
        $kontroler->edit();
        break;
    case 'obrisi':
        $kontroler->delete();
        break;
    case 'stampa':
        if (isset($_GET['id'])) {
            $kontroler->print();
        } else {
            $kontroler->printList();
        }
        break;
    default:
        $kontroler->index();
}
?>