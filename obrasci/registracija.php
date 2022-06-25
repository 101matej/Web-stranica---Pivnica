<?php
error_reporting(E_ALL ^ E_NOTICE);

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = dirname(getcwd());

include '../zaglavlje.php';

$korisnickoIme = $_POST['korime'];
$greska = false;

if(isset($_POST['registrirajGumb']) && $_POST['g-recaptcha-response'] != ""){
    $tajniKljuc = '6LdezHQgAAAAAATPERJafkgU_Xg';
    $odgovor = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $tajniKljuc . '&response=' . $_POST['g-recaptcha-response']);
    $odgovorPodaci = json_decode($odgovor);
    if ($responseData->success){
        $captcha = false;
    }else{
        $captcha = true;
    }
}

//provjera postojanja korisničkog imena
if($korisnickoIme != "" && isset($_POST['registrirajGumb'])){
    $veza = new Baza();
    $veza->spojiDB();

    $korime = $_POST['korime'];
    
    $upit = "SELECT * FROM `korisnik` WHERE "
            . "`korisnicko_ime`='{$korime}'";

    $rezultat = $veza->selectDB($upit);
    
    $red = mysqli_fetch_array($rezultat);
    if($red['korisnicko_ime'] == $korime){
        $poruka = "Korisnik već postoji u bazi!";
        $greska = true;
    }
}

//pritisak na gumb pošalji podatke
if (isset($_POST['registrirajGumb'])) {
    if ($captcha == true) {
        //provjera jesu li uneseni svi elementi
        $ime = $_POST['ime'];
        $prezime = $_POST['prez'];
        $datumRodenja = $_POST['danRod'];
        $email = $_POST['email'];
        $korisnickoIme = $_POST['korime'];
        $lozinka = $_POST['lozinka1'];
        $ponovljenaLozinka = $_POST['lozinka2'];

        if ($ime == null) {
            $imePraznoPoruka = "Ime mora biti uneseno!";
            $imePrazno = true;
            $greska = true;
        }
        if ($prezime == null) {
            $prezimePraznoPoruka = "Prezime mora biti uneseno!";
            $prezimePrazno = true;
            $greska = true;
        }
        if ($datumRodenja == null) {
            $datumRodenjaPraznoPoruka = "Datum rođenja mora biti unesen!";
            $datumRodenjaPrazno = true;
            $greska = true;
        }
        if ($email == null) {
            $emailPraznoPoruka = "Email mora biti unesen!";
            $emailPrazno = true;
            $greska = true;
        }
        if ($korisnickoIme == null) {
            $korisnickoImePraznoPoruka = "Korisničko ime mora biti uneseno!";
            $korisnickoImePrazno = true;
            $greska = true;
        }
        if ($lozinka == null) {
            $lozinkaPraznoPoruka = "Lozinka mora biti unesena!";
            $lozinkaPrazno = true;
            $greska = true;
        }
        if ($ponovljenaLozinka == null) {
            $ponovljenaLozinkaPraznoPoruka = "Ponovljena lozinka mora biti unesena!";
            $ponovljenaLozinkaPrazno = true;
            $greska = true;
        }

        //provjera imena
        $regularniIzrazIme = '/^[\w\W]{0,25}$/';
        if (!preg_match($regularniIzrazIme, $ime)) {
            $neispravnoImePoruka = "Broj unesenih znakova za ime ne smije biti veći od 25!";
            $neispravnoIme = true;
            $greska = true;
        }

        //provjera prezimena
        if (strlen($prezime) > 25) {
            $neispravnoPrezimeDuljinaPoruka = "Broj unesenih znakova za prezime ne smije biti veći od 25!";
            $neispravnoPrezime = true;
            $greska = true;
        }
        for ($i = 0; $i < strlen($prezime); $i++) {
            if ($prezime[$i] == "0" || $prezime[$i] == "1" || $prezime[$i] == "2" || $prezime[$i] == "3" || $prezime[$i] == "4" ||
                    $prezime[$i] == "5" || $prezime[$i] == "6" || $prezime[$i] == "7" || $prezime[$i] == "8" || $prezime[$i] == "9") {
                $neispravnoPrezimeBrojPoruka = "Ne smijete unijeti broj u prezimenu!";
                $neispravnoPrezime = true;
                $greska = true;
            }
        }

        //provjera datuma
        $regularniIzrazDatum = '/^(3[01]|[12][0-9]|0[1-9])[.](1[0-2]|0[1-9])[.][0-9]{4}[.]$/';
        if (!preg_match($regularniIzrazDatum, $datumRodenja)) {
            $neispravanFormatPoruka = "Datum je neispravnog formata! Ispravan format je 'dd.mm.gggg.'!";
            $neispravanFormat = true;
            $greska = true;
        }
        $danasnjiDatumMinusOsamnaestGodina = date('d.m.Y', strtotime(date("d.m.Y", mktime()) . " - 18 year"));
        $timestamp1 = strtotime($datumRodenja);
        $timestamp2 = strtotime($danasnjiDatumMinusOsamnaestGodina);
        if ($timestamp1 > $timestamp2) {
            $maloljetanPoruka = "Korisnik mora biti punoljetan!";
            $maloljetan = true;
            $greska = true;
        }

        //provjera lozinki
        if ($lozinka != $ponovljenaLozinka) {
            $neispravneLozinkePoruka = "Lozinke se ne podudaraju!";
            $neispravneLozinke = true;
            $greska = true;
        }

        //provjerava jesu li svi elementi ispravno uneseni
        if ($greska == false) {
            $veza = new Baza();
            $veza->spojiDB();
            
            $ponovljenaLozinkaSHA = hash('sha256', $ponovljenaLozinka);
            $formatiraniDatum = date("Y-m-d", strtotime($datumRodenja));
            $generiranAktivacijkiKod = bin2hex(random_bytes(4));
            $vrijeme_registracije = date('Y-m-d H:i:s');
            
            $mail_to = $email;
            $mail_from = "From: mforjan@foi.hr";
            $mail_subject = "Validacijski kljuc";
            $mail_body = "Vas kljuc je: $generiranAktivacijkiKod.https://barka.foi.hr/WebDiP/2021_projekti/WebDiP2021x024/obrasci/validiraj.php";
            
            $sql = "INSERT INTO korisnik (ime, prezime, datum_rodenja, email, korisnicko_ime, lozinka, lozinka_sha256, aktivacijski_kod, vrijeme_registracije, tip_korisnika) "
                    . "VALUES ('$ime', '$prezime', '$formatiraniDatum', '$email', '$korisnickoIme', '$lozinka', '$ponovljenaLozinkaSHA', '$generiranAktivacijkiKod', '$vrijeme_registracije',2)";
            $rezultat = $veza->updateDB($sql);
            $veza->zatvoriDB();
            
            if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
                $emailPoruka = "Poslana poruka za: '$mail_to'!";
            } else {
                $emailPoruka = "Problem kod poruke za: '$mail_to'!";
            }

            header("Location: validiraj.php");
        }
    } else{
        $captchaPoruka = "Morate potvrditi da niste robot!";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Registracija</title>
        <meta charset="utf-8">
        <meta name="author" content="Matej Forjan">
        <meta name="description" content="08.06.2022.">
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
        <link href="../css/mforjan.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="../javascript/mforjan.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <header>
            <div>
                <input id="menu__toggle" type="checkbox" />
                <label class="menu__btn" for="menu__toggle">
                    <span></span>
                </label>

                <ul class="menu__box">
                    
                    <?php
                    echo "<li><a class='menu__item' href=\"$putanja/../index.php\">Početna stranica</a></li>";
                    
                    if (!isset($_SESSION["uloga"])) {
                        echo "<li><a class='menu__item' href=\"$putanja/prijava.php\">Prijava</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 2) {
                        echo "<li><a class='menu__item' href=\"$putanja/../pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 3) {
                        echo "<li><a class='menu__item' href=\"$putanja/../pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/../pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../pivnice.php\">Pivnice</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../zemljePodrijetla.php\">Zemlje podrijetla</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../blokirani_korisnici.php\">Blokirani korisnici</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/../dnevnik_rada.php\">Dnevnik rada</a></li>";
                    }
                    
                    echo "<li><a class='menu__item' href=\"$putanja/../galerija.php\">Galerija</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/../rang_lista.php\">Rang lista</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/../o_autoru.html\">O autoru</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/../dokumentacija.html\">Dokumentacija</a></li>";
                    
                    if (isset($_SESSION["uloga"])) {
                        echo "<li><a class='menu__item' href=\"$putanja/../index.php?obrisi=true\">Odjava</a></li>";
                    }
                    ?>
                    
                </ul>

            </div>

            <div class = "pozicijaLoga">
                <a href = "../index.php">
                    <img class = "logo" src = "../materijali/logo.png" alt = "Logo">
                </a>
            </div>

            <h1 class = "naslov" style = "text-align: center">
                REGISTRACIJA
            </h1>
            
            <?php
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$emailPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$captchaPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$imePraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$prezimePraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$datumRodenjaPraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$emailPraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$korisnickoImePraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$lozinkaPraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$ponovljenaLozinkaPraznoPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$neispravnoImePoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$neispravnoPrezimeDuljinaPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$neispravnoPrezimeBrojPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$neispravanFormatPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$maloljetanPoruka</p>";
                echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center; position:relative;'>$neispravneLozinkePoruka</p>";
            ?>
            
        </header>

        <section>
            
            <form novalidate class = "forma" id = "form1" method = "post" name = "form1" action = "registracija.php">

                <label <?php
                    global $imePrazno;
                    if ($imePrazno == true || $neispravnoIme == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "ime" id="imeLabel">Ime: </label>
                <input class = "imeRegistracijaTextbox" type = "text" id = "ime" name = "ime" size = "35" placeholder = "Ime" required = "required" autofocus = "autofocus"><br>

                <label <?php
                    global $prezimePrazno;
                    if ($prezimePrazno == true || $neispravnoPrezime == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "prez">Prezime: </label>
                <input class = "prezimeRegistracijaTextbox" type = "text" id = "prez" name = "prez" size = "35" placeholder = "Prezime" required = "required"><br>

                <label <?php
                    global $datumRodenjaPrazno;
                    global $neispravanFormat;
                    if ($datumRodenjaPrazno == true || $neispravanFormat == true || $maloljetan == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "danRod" id="datumRodenjaLabel">Datum rođenja: </label>
                <input class = "datumRodenjaRegistracijaTextbox" type = "text" id = "danRod" name = "danRod" size="35" required = "required" placeholder="Datum rođenja u formatu dd.mm.gggg."><br>

                <label <?php
                    global $emailPrazno;
                    if ($emailPrazno == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "email">Email adresa: </label>
                <input class = "emailRegistracijaTextbox" type = "email" id = "email" name = "email" size = "35" maxlength = "35" placeholder = "ldap@foi.hr" required = "required"><br>

                <label <?php
                    global $korisnickoImePrazno;
                    if ($korisnickoImePrazno == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "korime">Korisničko ime: </label>
                <input class = "korimeRegistracijaTextbox" type = "text" id = "korime" name = "korime" size = "35" maxlength = "25" placeholder = "Korisničko ime" required = "required"><br>

                <label <?php
                    global $lozinkaPrazno;
                    if ($lozinkaPrazno == true || $neispravneLozinke == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "lozinka1" id = "lozinka1Label">Lozinka: </label>
                <input class = "lozinkaRegistracijaTextbox" type = "password" id = "lozinka1" name = "lozinka1" size = "35" maxlength = "50" placeholder = "Lozinka" required = "required"><br>

                <label <?php
                    global $ponovljenaLozinkaPrazno;
                    if ($ponovljenaLozinkaPrazno == true || $neispravneLozinke == true) {
                        echo "class='neispravanElement'";
                    }
                    ?> for = "lozinka2" id = "lozinka2Label">Ponovi lozinku: </label>
                <input class = "ponovljenaLozinkaRegistracijaTextbox" type = "password" id = "lozinka2" name = "lozinka2" size = "35" maxlength = "50" placeholder = "Lozinka" required = "required"><br>
                
                <br>
                
                <div class="g-recaptcha" data-sitekey="6LdezHQgAAAAAM0kz90fyLtlITXX7IoK7fY3ibuo"></div>
                
                <br>
                
            </form>
            <div style = "text-align: center">
                <input form = "form1" type = "submit" id = "registriraj" name="registrirajGumb" class = "submit" value = "Registriraj se ">
            </div>
            
        </section>
        <footer>
            <address><b>Kontakt:</b> 
                <a style="color: white; text-decoration: none;" href="mailto:mforjan@foi.hr">
                    Matej Forjan</a></address>
            <p>&copy; 2022 M. Forjan</p>
            <img style="width: 60px; height: 60px; position: relative; top: -7px;" src="../materijali/HTML5.png" alt="Slika">
            <img style="width: 75px; height: 75px; position: relative; top: 0px;" src="../materijali/CSS3.png" alt="Slika">
        </footer>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(){
            $("#registriraj").onclick(function(){
                var korime = $("#korime").val();

               $.ajax({
                    method: "POST",
                    url: "registracija.php",
                    data: {
                        korime: korime
                    }
                })
            }
            }));
        </script>
    </body>
</html>


