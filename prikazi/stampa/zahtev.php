<?php
if (!isset($zahtev) || empty($zahtev)) {
    echo "Greška: Zahtev nije pronađen!";
    exit();
}

if (is_object($zahtev)) {
    $id = $zahtev->getId();
    $ime = $zahtev->getIme();
    $prezime = $zahtev->getPrezime();
    $adresa = $zahtev->getAdresa();
    $telefon = $zahtev->getTelefon();
    $datum = $zahtev->getDatum();
    $stavke = $zahtev->getStavke();
} else {
    $id = $zahtev['id'] ?? 0;
    $ime = $zahtev['ime'] ?? '';
    $prezime = $zahtev['prezime'] ?? '';
    $adresa = $zahtev['adresa'] ?? '';
    $telefon = $zahtev['telefon'] ?? '';
    $datum = $zahtev['datum'] ?? '';
    $stavke = $zahtev['stavke'] ?? [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ZAHTEV ZA UDOMLJAVANJE #<?php echo $id; ?></title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
        }
        .header h2 {
            font-size: 14pt;
            margin: 5px 0;
        }
        .header p {
            margin: 3px 0;
            font-size: 11pt;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .info-row {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .info-value {
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 10pt;
            text-align: center;
        }
        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-btn:hover {
            background: #0056b3;
        }
        @media print {
            .print-btn {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Štampaj</button>
    
    <h1 style="text-align: center; font-size: 16pt;">ZAHTEV ZA UDOMLJAVANJE</h1>
    
    <div class="section-title">Master deo (podaci o udomitelju)</div>
    
    <div class="info-row">
        <span class="info-label">Ime:</span>
        <span class="info-value"><?php echo htmlspecialchars($ime); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Prezime:</span>
        <span class="info-value"><?php echo htmlspecialchars($prezime); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Adresa:</span>
        <span class="info-value"><?php echo htmlspecialchars($adresa); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Kontakt telefon:</span>
        <span class="info-value"><?php echo htmlspecialchars($telefon); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Datum:</span>
        <span class="info-value"><?php echo date('d.m.Y', strtotime($datum)); ?></span>
    </div>
    
    <div class="section-title">ŽIVOTINJE ZA UDOMLJAVANJE</div>
    <p style="font-style: italic;">Detail deo (životinje koje se biraju za udomljavanje)</p>
    
    <table>
        <thead>
            <tr>
                <th>Vrsta</th>
                <th>Ime životinje</th>
                <th>Starost</th>
                <th>Napomena</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($stavke)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Nema evidentiranih životinja</td>
                </tr>
            <?php else: ?>
                <?php foreach($stavke as $stavka): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stavka['vrsta'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($stavka['ime_zivotinje'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($stavka['starost'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($stavka['napomena'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dokument generisan: <?php echo date('d.m.Y H:i:s'); ?></p>
    </div>
</body>
</html>
