<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prijava - Udomljavanje</title>
    <link rel="stylesheet" href="resursi/stilovi/stil.css">
</head>
<body>
    //admin/admin123 i selena/selena123
    <div class="login-container">
        <h1>Prijava na sistem</h1>
        <h2>Udomljavanje životinja</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?akcija=login" onsubmit="return validirajPrijavu()">
            <div class="form-group">
                <label>Korisničko ime:</label>
                <input type="text" name="korisnicko_ime" id="korisnicko_ime" required>
            </div>
            
            <div class="form-group">
                <label>Lozinka:</label>
                <input type="password" name="lozinka" id="lozinka" required>
            </div>
            
            <button type="submit" class="btn-primary">Prijavi se</button>
        </form>
        
    </div>
    
    <script src="resursi/skripte/validacija.js"></script>
</body>
</html>