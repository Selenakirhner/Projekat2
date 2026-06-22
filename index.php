<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$javne_akcije = ['login', 'logout'];
$akcija = $_GET['akcija'] ?? 'login';

if ($akcija == 'api') {
    require_once 'kontroleri/RestKontroler.php';
    exit();
}

if (!isset($_SESSION['user']) && !in_array($akcija, $javne_akcije)) {
    header('Location: index.php?akcija=login');
    exit();
}

if ($akcija == 'login' || $akcija == 'logout') {
    require_once 'kontroleri/AuthKontroler.php';
} else {
    require_once 'kontroleri/ZahtevKontroler.php';
}
?>