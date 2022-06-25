window.addEventListener("load", kreirajDogadaje);

var greska = false;
var porukaOGreski = "";

function kreirajDogadaje() {
    document.getElementById("registriraj").addEventListener("click", function () {
        greska = false;
        provjeraJesuLiUneseniSviElementi();
        provjeraImena();
        provjeraPrezimena();
        provjeraDatuma();
        provjeraLozinki();

        if (greska) {
            alert(porukaOGreski);
            event.preventDefault();
            porukaOGreski = "";
        }
    });
}

function provjeraJesuLiUneseniSviElementi() {
    var ime = document.getElementById("ime").value;
    var prezime = document.getElementById("prez").value;
    var datumRodenja = document.getElementById("danRod").value;
    var email = document.getElementById("email").value;
    var korisnickoIme = document.getElementById("korime").value;
    var lozinka1 = document.getElementById("lozinka1").value;
    var lozinka2 = document.getElementById("lozinka2").value;

    if (ime == "") {
        greska = true;
        porukaOGreski += "Ime mora biti uneseno!\n";
    }
    if (prezime == "") {
        greska = true;
        porukaOGreski += "Prezime mora biti uneseno!\n";
    }
    if (datumRodenja == "") {
        greska = true;
        porukaOGreski += "Datum rođenja mora biti unesen!\n";
    }
    if (korisnickoIme == "") {
        greska = true;
        porukaOGreski += "Korisničko ime mora biti uneseno!\n";
    }
    if (email == "") {
        greska = true;
        porukaOGreski += "Email mora biti unesen!\n";
    }
    if (lozinka1 == "") {
        greska = true;
        porukaOGreski += "Lozinka mora biti unesena!\n";
    }
    if (lozinka2 == "") {
        greska = true;
        porukaOGreski += "Ponovljena lozinka mora biti unesena!\n";
    }
}

function provjeraImena() {
    var ime = document.getElementById("ime").value;
    var rezultat = /^[\w\W]{0,25}$/.test(ime);
    if (!rezultat) {
        greska = true;
        porukaOGreski += "Broj unesenih znakova za ime ne smije biti veći od 25!\n";
    }
}

function provjeraPrezimena() {
    var brojac = 0;

    var string = document.getElementById("prez").value;

    if (string.length > 25) {
        greska = true;
        porukaOGreski += "Broj unesenih znakova za prezime ne smije biti veći od 25!\n";
    }

    for (var i = 0; i < string.length; i++) {
        if (string[i] == "0") {
            brojac++;
        }
        if (string[i] == '1') {
            brojac++;
        }
        if (string[i] == "2") {
            brojac++;
        }
        if (string[i] == "3") {
            brojac++;
        }
        if (string[i] == "4") {
            brojac++;
        }
        if (string[i] == "5") {
            brojac++;
        }
        if (string[i] == "6") {
            brojac++;
        }
        if (string[i] == "7") {
            brojac++;
        }
        if (string[i] == "8") {
            brojac++;
        }
        if (string[i] == "9") {
            brojac++;
        }
    }

    if (brojac > 0) {
        greska = true;
        porukaOGreski += "Ne smijete unijeti broj u prezimenu!\n";
    }
}

function provjeraDatuma() {
    var datumRodenja = document.getElementById("danRod").value;
    var rezultat = /^(3[01]|[12][0-9]|0[1-9])[.](1[0-2]|0[1-9])[.][0-9]{4}[.]$/.test(datumRodenja);
    if (!rezultat) {
        greska = true;
        porukaOGreski += "Datum je neispravnog formata! Ispravni format je 'dd.mm.gggg.'!\n";
    }
}

function provjeraLozinki() {
    var lozinka = document.getElementById("lozinka1").value;
    var ponovljenaLozinka = document.getElementById("lozinka2").value;

    if (lozinka != ponovljenaLozinka) {
        greska = true;
        porukaOGreski += "Lozinke se ne podudaraju!\n";
    }
}