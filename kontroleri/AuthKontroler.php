<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/klase/Korisnik.php';

class AuthKontroler {
    private $korisnik;
    
    public function __construct() {
        $this->korisnik = new Korisnik();
    }
    
    public function login() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $korisnicko_ime = $_POST['korisnicko_ime'] ?? '';
            $lozinka = $_POST['lozinka'] ?? '';

            if (empty($korisnicko_ime)) $errors[] = "Korisničko ime je obavezno";
            if (empty($lozinka)) $errors[] = "Lozinka je obavezna";
            
            if (empty($errors)) {
                $user = $this->korisnik->login($korisnicko_ime, $lozinka);
                if ($user) {
                    $_SESSION['user'] = $user;
                    header('Location: index.php?akcija=lista');
                    exit();
                } else {
                    $errors[] = "Pogrešno korisničko ime ili lozinka";
                }
            }
        }
        
        include dirname(__DIR__) . '/prikazi/prijava.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php?akcija=login');
        exit();
    }
}

$akcija = $_GET['akcija'] ?? 'login';
$auth = new AuthKontroler();

switch($akcija) {
    case 'login':
        $auth->login();
        break;
    case 'logout':
        $auth->logout();
        break;
    default:
        $auth->login();
}
?>