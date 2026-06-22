<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prikaz zahteva - Udomljavanje</title>
    <link rel="stylesheet" href="resursi/stilovi/stil.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Prikaz zahteva za udomljavanje #<?php echo $zahtev->getId(); ?></h1>
            <div class="user-info">
                <span>Prijavljen: <?php echo $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime']; ?></span>
                <a href="index.php?akcija=logout" class="btn-logout">Odjavi se</a>
            </div>
        </header>
        
        <nav>
            <a href="index.php?akcija=lista" class="btn-secondary">Nazad na listu</a>
            <a href="index.php?akcija=izmena&id=<?php echo $zahtev->getId(); ?>" class="btn-primary">Izmeni</a>
            <a href="index.php?akcija=stampa&id=<?php echo $zahtev->getId(); ?>" class="btn-secondary">Štampaj</a>
        </nav>
        
        <div class="detail-view">
            <h2>Podaci o udomitelju</h2>
            <table class="detail-table">
                <tr>
                    <th>Ime:</th>
                    <td><?php echo htmlspecialchars($zahtev->getIme()); ?></td>
                </tr>
                <tr>
                    <th>Prezime:</th>
                    <td><?php echo htmlspecialchars($zahtev->getPrezime()); ?></td>
                </tr>
                <tr>
                    <th>Adresa:</th>
                    <td><?php echo htmlspecialchars($zahtev->getAdresa()); ?></td>
                </tr>
                <tr>
                    <th>Telefon:</th>
                    <td><?php echo htmlspecialchars($zahtev->getTelefon()); ?></td>
                </tr>
                <tr>
                    <th>Datum:</th>
                    <td><?php echo date('d.m.Y', strtotime($zahtev->getDatum())); ?></td>
                </tr>
            </table>
            
            <h2>Životinje za udomljavanje</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Vrsta</th>
                        <th>Ime životinje</th>
                        <th>Starost</th>
                        <th>Napomena</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stavke = $zahtev->getStavke();
                    ?>
                    
                    <?php if (empty($stavke)): ?>
                        <tr><td colspan="4" class="text-center">Nema evidentiranih životinja</td></tr>
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
        </div>
    </div>
</body>
</html>