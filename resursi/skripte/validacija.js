function validirajZahtev() {
    var greske = [];

    var ime = document.getElementById('ime');
    var prezime = document.getElementById('prezime');
    var adresa = document.getElementById('adresa');
    var telefon = document.getElementById('telefon');
    var datum = document.getElementById('datum');

    if (ime && ime.value.trim() === '') {
        greske.push('Ime je obavezno');
    } else if (ime && ime.value.length > 50) {
        greske.push('Ime ne sme biti duže od 50 karaktera');
    }

    if (prezime && prezime.value.trim() === '') {
        greske.push('Prezime je obavezno');
    } else if (prezime && prezime.value.length > 50) {
        greske.push('Prezime ne sme biti duže od 50 karaktera');
    }

    if (adresa && adresa.value.trim() === '') {
        greske.push('Adresa je obavezna');
    } else if (adresa && adresa.value.length > 200) {
        greske.push('Adresa ne sme biti duža od 200 karaktera');
    }

    if (telefon && telefon.value.trim() === '') {
        greske.push('Telefon je obavezan');
    } else if (telefon && !/^[0-9+\- ]+$/.test(telefon.value)) {
        greske.push('Telefon može sadržati samo brojeve, + i -');
    } else if (telefon && telefon.value.length > 20) {
        greske.push('Telefon ne sme biti duži od 20 karaktera');
    }

    if (datum && datum.value === '') {
        greske.push('Datum je obavezan');
    }

    var imenaZivotinja = document.querySelectorAll('input[name="ime_zivotinje[]"]');
    var imaStavku = false;
    
    imenaZivotinja.forEach(function(input) {
        if (input.value.trim() !== '') {
            imaStavku = true;
        }
    });
    
    if (!imaStavku) {
        greske.push('Morate uneti bar jednu životinju');
    }

    if (greske.length > 0) {
        alert('Greške u unosu:\n\n' + greske.join('\n'));
        return false;
    }
    
    return true;
}

function validirajPrijavu() {
    var korisnickoIme = document.getElementById('korisnicko_ime');
    var lozinka = document.getElementById('lozinka');
    var greske = [];
    
    if (korisnickoIme && korisnickoIme.value.trim() === '') {
        greske.push('Unesite korisničko ime');
    }
    
    if (lozinka && lozinka.value.trim() === '') {
        greske.push('Unesite lozinku');
    } else if (lozinka && lozinka.value.length < 4) {
        greske.push('Lozinka mora imati najmanje 4 karaktera');
    }
    
    if (greske.length > 0) {
        alert('Greške u unosu:\n\n' + greske.join('\n'));
        return false;
    }
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    var inputs = document.querySelectorAll('input[maxlength]');
    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value.length > this.maxLength) {
                this.value = this.value.substring(0, this.maxLength);
            }
        });
    });
});

function potvrdiBrisanje(id) {
    if (confirm('Da li ste sigurni da želite da obrišete ovaj zahtev?')) {
        window.location.href = 'index.php?akcija=obrisi&id=' + id;
    }
}