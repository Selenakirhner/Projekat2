<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Izmena zahteva - Udomljavanje</title>
    <link rel="stylesheet" href="resursi/stilovi/stil.css">
    <script src="resursi/skripte/validacija.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Izmena zahteva za udomljavanje #<?php echo $zahtev->getId(); ?></h1>
            <div class="user-info">
                <span>Prijavljen: <?php echo $_SESSION['user']['ime'] . ' ' . $_SESSION['user']['prezime']; ?></span>
                <a href="index.php?akcija=logout" class="btn-logout">Odjavi se</a>
            </div>
        </header>
        
        <nav>
            <a href="index.php?akcija=lista" class="btn-secondary">Nazad na listu</a>
        </nav>
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?akcija=izmena&id=<?php echo $zahtev->getId(); ?>" onsubmit="return validirajZahtev()">
            <fieldset>
                <legend>Podaci o udomitelju</legend>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Ime: *</label>
                        <input type="text" name="ime" id="ime" maxlength="50" value="<?php echo htmlspecialchars($zahtev->getIme()); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Prezime: *</label>
                        <input type="text" name="prezime" id="prezime" maxlength="50" value="<?php echo htmlspecialchars($zahtev->getPrezime()); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Adresa: *</label>
                        <input type="text" name="adresa" id="adresa" maxlength="200" value="<?php echo htmlspecialchars($zahtev->getAdresa()); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Telefon: *</label>
                        <input type="text" name="telefon" id="telefon" maxlength="20" value="<?php echo htmlspecialchars($zahtev->getTelefon()); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Datum: *</label>
                    <input type="date" name="datum" id="datum" value="<?php echo $zahtev->getDatum(); ?>" required>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Životinje za udomljavanje</legend>
                <div id="stavke-container">
                    <?php 
                    $stavke = $zahtev->getStavke();
                    ?>
                    
                    <?php if (!empty($stavke)): ?>
                        <?php foreach($stavke as $stavka): ?>
                            <div class="stavka-row">
                                <div class="form-group">
                                    <label>Vrsta:</label>
                                    <select name="id_zivotinja[]">
                                        <option value="">-- Izaberite --</option>
                                        <?php foreach($zivotinje as $z): ?>
                                            <?php 
                                            $selected = '';
                                            if (isset($stavka['id_zivotinja']) && $stavka['id_zivotinja'] == $z['id']) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?php echo $z['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($z['vrsta']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Ime životinje: *</label>
                                    <input type="text" name="ime_zivotinje[]" value="<?php echo htmlspecialchars($stavka['ime_zivotinje'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Starost:</label>
                                    <input type="text" name="starost[]" value="<?php echo htmlspecialchars($stavka['starost'] ?? ''); ?>" placeholder="npr. 2 godine">
                                </div>
                                
                                <div class="form-group">
                                    <label>Napomena:</label>
                                    <input type="text" name="napomena[]" value="<?php echo htmlspecialchars($stavka['napomena'] ?? ''); ?>" placeholder="npr. veseo, razigran">
                                </div>
                                
                                <button type="button" class="btn-remove" onclick="ukloniStavku(this)">✕</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="stavka-row">
                            <div class="form-group">
                                <label>Vrsta:</label>
                                <select name="id_zivotinja[]">
                                    <option value="">-- Izaberite --</option>
                                    <?php foreach($zivotinje as $z): ?>
                                        <option value="<?php echo $z['id']; ?>"><?php echo htmlspecialchars($z['vrsta']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Ime životinje: *</label>
                                <input type="text" name="ime_zivotinje[]" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Starost:</label>
                                <input type="text" name="starost[]" placeholder="npr. 2 godine">
                            </div>
                            
                            <div class="form-group">
                                <label>Napomena:</label>
                                <input type="text" name="napomena[]" placeholder="npr. veseo, razigran">
                            </div>
                            
                            <button type="button" class="btn-remove" onclick="ukloniStavku(this)">✕</button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="button" class="btn-secondary" onclick="dodajStavku()">+ Dodaj životinju</button>
            </fieldset>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Sačuvaj izmene</button>
                <a href="index.php?akcija=lista" class="btn-secondary">Otkaži</a>
            </div>
        </form>
    </div>
    
    <script>
    function dodajStavku() {
        var container = document.getElementById('stavke-container');
        var row = document.createElement('div');
        row.className = 'stavka-row';
        
        var selectOptions = '';
        <?php foreach($zivotinje as $z): ?>
            selectOptions += '<option value="<?php echo $z['id']; ?>"><?php echo addslashes($z['vrsta']); ?></option>';
        <?php endforeach; ?>
        
        row.innerHTML = `
            <div class="form-group">
                <label>Vrsta:</label>
                <select name="id_zivotinja[]">
                    <option value="">-- Izaberite --</option>
                    ${selectOptions}
                </select>
            </div>
            
            <div class="form-group">
                <label>Ime životinje: *</label>
                <input type="text" name="ime_zivotinje[]" required>
            </div>
            
            <div class="form-group">
                <label>Starost:</label>
                <input type="text" name="starost[]" placeholder="npr. 2 godine">
            </div>
            
            <div class="form-group">
                <label>Napomena:</label>
                <input type="text" name="napomena[]" placeholder="npr. veseo, razigran">
            </div>
            
            <button type="button" class="btn-remove" onclick="ukloniStavku(this)">✕</button>
        `;
        
        container.appendChild(row);
    }
    
    function ukloniStavku(btn) {
        var stavke = document.querySelectorAll('.stavka-row');
        if (stavke.length > 1) {
            btn.parentElement.remove();
        } else {
            alert('Mora postojati bar jedna životinja.');
        }
    }
    </script>
</body>
</html>