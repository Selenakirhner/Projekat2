<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista zahteva - Udomljavanje</title>
    <link rel="stylesheet" href="resursi/stilovi/stil.css">
    <script src="resursi/skripte/validacija.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Zahtevi za udomljavanje</h1>
            <div class="user-info">
                <span>Prijavljen: <?php echo $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime']; ?></span>
                <a href="index.php?akcija=logout" class="btn-logout">Odjavi se</a>
            </div>
        </header>
        
        <nav>
            <a href="index.php?akcija=unos" class="btn-primary">Novi zahtev</a>
            <a href="index.php?akcija=lista" class="btn-secondary">Svi zahtevi</a>
        </nav>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-box">Operacija uspešno izvršena!</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box">Došlo je do greške!</div>
        <?php endif; ?>
        
        <div class="filter-box">
            <form method="GET" action="index.php">
                <input type="hidden" name="akcija" value="lista">
                <label>Filter (ime, prezime, adresa):</label>
                <input type="text" name="filter" value="<?php echo $_GET['filter'] ?? ''; ?>">
                <button type="submit" class="btn-secondary">Filtriraj</button>
                <a href="index.php?akcija=lista" class="btn-secondary">Poništi filter</a>
                
                <?php if (!empty($_GET['filter'])): ?>
                    <a href="index.php?akcija=stampa&filter=<?php echo urlencode($_GET['filter']); ?>" class="btn-secondary">Štampaj filtrirano</a>
                <?php else: ?>
                    <a href="index.php?akcija=stampa" class="btn-secondary">Štampaj sve</a>
                <?php endif; ?>
            </form>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ime i prezime</th>
                    <th>Adresa</th>
                    <th>Telefon</th>
                    <th>Datum</th>
                    <th>Br. životinja</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($zahtevi)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Nema evidentiranih zahteva</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($zahtevi as $z): ?>
                        <tr>
                            <td><?php echo $z['id_zahtev']; ?></td>
                            <td><?php echo htmlspecialchars($z['ime'] . ' ' . $z['prezime']); ?></td>
                            <td><?php echo htmlspecialchars($z['adresa']); ?></td>
                            <td><?php echo htmlspecialchars($z['telefon']); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($z['datum'])); ?></td>
                            <td><?php echo $z['broj_zivotinja']; ?></td>
                            <td>
                                <a href="index.php?akcija=prikaz&id=<?php echo $z['id_zahtev']; ?>" class="btn-small">Prikaz</a>
                                <a href="index.php?akcija=izmena&id=<?php echo $z['id_zahtev']; ?>" class="btn-small btn-edit">Izmena</a>
                                <a href="index.php?akcija=stampa&id=<?php echo $z['id_zahtev']; ?>" class="btn-small btn-print">Štampa</a>
                                <a href="javascript:void(0)" onclick="potvrdiBrisanje(<?php echo $z['id_zahtev']; ?>)" class="btn-small btn-delete">Brisanje</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function potvrdiBrisanje(id) {
        if (confirm('Da li ste sigurni da želite da obrišete ovaj zahtev?')) {
            window.location.href = 'index.php?akcija=obrisi&id=' + id;
        }
    }
    </script>
</body>
</html>