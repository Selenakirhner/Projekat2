<?php
require_once dirname(__DIR__) . '/../klase/Zahtev.php';

$zahtev = new Zahtev();

if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $zahtevi = $zahtev->filtriraj($_GET['filter']);
} else {
    $zahtevi = $zahtev->sviSaStavkama();
}

if (empty($zahtevi)) {
    echo "<h1>Nema evidentiranih zahteva</h1>";
    echo "<a href='/udomljavanje/index.php?akcija=lista'>Nazad na listu</a>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Spisak zahteva za udomljavanje</title>
    <style>
        @page { margin: 15mm; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; }
        h1 { text-align: center; font-size: 16pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        .filter-info { margin: 10px 0; font-style: italic; }
        .total { margin-top: 10px; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 9pt; border-top: 1px solid #000; padding-top: 10px; }
        .print-btn { position: fixed; top: 10px; right: 10px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Štampaj</button>
    
    <h1>SPISAK ZAHTEVA ZA UDOMLJAVANJE</h1>
    
    <?php if (isset($_GET['filter']) && !empty($_GET['filter'])): ?>
        <div class="filter-info">Filter: <?php echo htmlspecialchars($_GET['filter']); ?></div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Adresa</th>
                <th>Telefon</th>
                <th>Datum</th>
                <th>Br. životinja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($zahtevi as $z): ?>
                <tr>
                    <td><?php echo $z['id_zahtev']; ?></td>
                    <td><?php echo htmlspecialchars($z['ime']); ?></td>
                    <td><?php echo htmlspecialchars($z['prezime']); ?></td>
                    <td><?php echo htmlspecialchars($z['adresa']); ?></td>
                    <td><?php echo htmlspecialchars($z['telefon']); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($z['datum'])); ?></td>
                    <td style="text-align: center;"><?php echo $z['broj_zivotinja']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="total">Ukupan broj zahteva: <?php echo count($zahtevi); ?></div>
    
    <div class="footer">
        <p>Dokument generisan: <?php echo date('d.m.Y H:i:s'); ?></p>
    </div>
</body>
</html>