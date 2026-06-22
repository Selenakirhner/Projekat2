<?php
require_once dirname(__DIR__) . '/klase/Zahtev.php';
require_once dirname(__DIR__) . '/klase/StavkaZahteva.php';
require_once dirname(__DIR__) . '/klase/Zivotinja.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

class RestKontroler {
    private $zahtev;
    private $stavka;
    private $zivotinja;
    
    public function __construct() {
        $this->zahtev = new Zahtev();
        $this->stavka = new StavkaZahteva();
        $this->zivotinja = new Zivotinja();
    }
    
    public function route() {
        $metoda = $_SERVER['REQUEST_METHOD'];
        $putanja = $_GET['putanja'] ?? '';
        $parametri = explode('/', trim($putanja, '/'));
        $resurs = $parametri[0] ?? '';
        $id = $parametri[1] ?? null;
        
        switch($metoda) {
            case 'GET':
                $this->handleGet($resurs, $id);
                break;
            case 'POST':
                $this->handlePost($resurs);
                break;
            case 'PUT':
                $this->handlePut($resurs, $id);
                break;
            case 'DELETE':
                $this->handleDelete($resurs, $id);
                break;
            default:
                $this->sendResponse(405, ['error' => 'Metoda nije dozvoljena']);
        }
    }
    
    private function handleGet($resurs, $id) {
        switch($resurs) {
            case 'zahtevi':
                if ($id) {
                    $data = $this->zahtev->saStavkama($id);
                    if ($data) {
                        $this->sendResponse(200, $data);
                    } else {
                        $this->sendResponse(404, ['error' => 'Zahtev nije pronađen']);
                    }
                } else {
                    $filter = $_GET['filter'] ?? '';
                    if ($filter) {
                        $data = $this->zahtev->filtriraj($filter);
                    } else {
                        $data = $this->zahtev->sviSaStavkama();
                    }
                    $this->sendResponse(200, $data);
                }
                break;
                
            case 'zivotinje':
                $data = $this->zivotinja->sveZaPadajuciMeni();
                $this->sendResponse(200, $data);
                break;
                
            case 'stavke':
                if ($id) {
                    $data = $this->stavka->poZahtevId($id);
                    $this->sendResponse(200, $data);
                } else {
                    $this->sendResponse(400, ['error' => 'ID zahteva je obavezan']);
                }
                break;
                
            default:
                $this->sendResponse(404, ['error' => 'Resurs nije pronađen']);
        }
    }
    
    private function handlePost($resurs) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch($resurs) {
            case 'zahtevi':
                try {
                    if (empty($input['ime']) || empty($input['prezime']) || empty($input['adresa'])) {
                        $this->sendResponse(400, ['error' => 'Ime, prezime i adresa su obavezni']);
                    }
                    
                    $id = $this->zahtev->sacuvaj($input);
                    $this->sendResponse(201, [
                        'id' => $id, 
                        'message' => 'Zahtev uspešno kreiran'
                    ]);
                } catch(Exception $e) {
                    $this->sendResponse(400, ['error' => $e->getMessage()]);
                }
                break;
                
            case 'stavke':
                try {
                    if (empty($input['id_zahtev']) || empty($input['id_zivotinja']) || empty($input['ime_zivotinje'])) {
                        $this->sendResponse(400, ['error' => 'id_zahtev, id_zivotinja i ime_zivotinje su obavezni']);
                    }
                    
                    $this->stavka->sacuvaj($input);
                    $this->sendResponse(201, ['message' => 'Stavka uspešno kreirana']);
                } catch(Exception $e) {
                    $this->sendResponse(400, ['error' => $e->getMessage()]);
                }
                break;
                
            default:
                $this->sendResponse(404, ['error' => 'Resurs nije pronađen']);
        }
    }
    
    private function handlePut($resurs, $id) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch($resurs) {
            case 'zahtevi':
                try {
                    if (!$id) {
                        $this->sendResponse(400, ['error' => 'ID je obavezan']);
                    }
                    
                    $this->zahtev->azuriraj($id, $input);
                    $this->sendResponse(200, ['message' => 'Zahtev uspešno ažuriran']);
                } catch(Exception $e) {
                    $this->sendResponse(400, ['error' => $e->getMessage()]);
                }
                break;
                
            case 'stavke':
                try {
                    if (!$id) {
                        $this->sendResponse(400, ['error' => 'ID je obavezan']);
                    }
                    
                    $this->stavka->azuriraj($id, $input);
                    $this->sendResponse(200, ['message' => 'Stavka uspešno ažurirana']);
                } catch(Exception $e) {
                    $this->sendResponse(400, ['error' => $e->getMessage()]);
                }
                break;
                
            default:
                $this->sendResponse(404, ['error' => 'Resurs nije pronađen']);
        }
    }
    
    private function handleDelete($resurs, $id) {
        switch($resurs) {
            case 'zahtevi':
                try {
                    if (!$id) {
                        $this->sendResponse(400, ['error' => 'ID je obavezan']);
                    }
                    
                    $this->zahtev->obrisi($id);
                    $this->sendResponse(200, ['message' => 'Zahtev uspešno obrisan']);
                } catch(Exception $e) {
                    $this->sendResponse(400, ['error' => $e->getMessage()]);
                }
                break;
                
            case 'stavke':
                try {
                    if (!$id) {
                        $this->sendResponse(400, ['error' => 'ID je obavezan']);
                    }
                    
                    $this->stavka->obrisi($id);
                    $this->sendResponse(200, ['message' => 'Stavka uspešno obrisana']);
                } catch(Exception $e) {
                    $this->sendResponse(400, ['error' => $e->getMessage()]);
                }
                break;
                
            default:
                $this->sendResponse(404, ['error' => 'Resurs nije pronađen']);
        }
    }
    
    private function sendResponse($code, $data) {
        http_response_code($code);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }
}

if (!isset($rest_kontroler_ran)) {
    $rest_kontroler_ran = true;
    $rest = new RestKontroler();
    $rest->route();
}
?>