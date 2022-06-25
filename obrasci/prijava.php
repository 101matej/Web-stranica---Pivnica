<?php
error_reporting(E_ALL ^ E_NOTICE);

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = dirname(getcwd());

include '../zaglavlje.php';

//prijava putem HTTPS-a
if ($_SERVER['HTTPS'] != 'on') {
    $https = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    header("Location: $https");
}

$zapamtiMeCookie = isset($_COOKIE['zapamtiMe']) ? $_COOKIE['zapamtiMe'] : "";

$poruka = "";
$greska = false;

if (isset($_POST['prijavaGumb'])) {
    $veza = new Baza();
    $veza->spojiDB();

    $korime = $_POST['korime'];
    $lozinka = $_POST['lozinka'];
    
    if($korime == "" || $lozinka == ""){
        $greska = true;
    }
    
    $upit = "SELECT * FROM `korisnik` WHERE "
            . "`korisnicko_ime`='{$korime}'";

    $rezultat = $veza->selectDB($upit);
    
    $prijavljen = false;
    
    $red = mysqli_fetch_array($rezultat);
    if (!$greska) {
        if ($red['korisnicko_ime'] == $korime && $red['status'] != 1) {
            if ($red['validiran'] == 1) {
                if ($red['lozinka'] == $lozinka) {
                $prijavljen = true;
                $tip = $red["tip_korisnika"];
                $sql = "UPDATE korisnik SET broj_neuspjesne_prijave = 0 WHERE korisnik_id = {$red['korisnik_id']}";
                $rezultat = $veza->updateDB($sql);

                if (isset($_POST['zapamtiMe'])) {
                    setcookie("zapamtiMe", $korime, false, '/');
                } else {
                    unset($_COOKIE['zapamtiMe']);
                    setcookie("zapamtiMe", "", time() - 3600, "/");
                }

                setcookie("autenticiran", $korime, false, '/', false);
                Sesija::kreirajKorisnika($korime, $tip);
                
                $dnevnik = new Dnevnik();
                $dnevnik->prijava($_SESSION['korisnik']);
                
                header("Location: ../index.php");
                exit();
            } else {
                $brojPokusaja = $red['broj_neuspjesne_prijave'] + 1;
                if ($brojPokusaja == 3 || $red['status'] == 1) {
                    $sql = "UPDATE korisnik SET status = 1 WHERE korisnik_id = {$red['korisnik_id']}";
                    $rezultat = $veza->updateDB($sql);
                    $poruka = "Upravo ste blokirani!";
                } else {
                    $preostaloPokusaja = 3 - $brojPokusaja;
                    $sql = "UPDATE korisnik SET broj_neuspjesne_prijave = {$brojPokusaja} WHERE korisnik_id = {$red['korisnik_id']}";
                    $rezultat = $veza->updateDB($sql);
                    $poruka = "Krivo unesena lozinka! Broj preostalih pokušaja iznosi {$preostaloPokusaja}";
                }
            }
            } else{
                $validiranPoruka = "Neuspješna prijava! Niste se validirali!";
            }
            
        } else {
            $poruka = "Blokirani ste ili Vam nije dobro korisničko ime!";
        }
    }else{
        $poruka = "Morate unijeti korisničko ime i lozinku!";
    }
    $veza->zatvoriDB();
}

//zaboravljena lozinka
if(isset($_POST['zaboravljenaLozinka'])){
    $veza = new Baza();
    $veza->spojiDB();

    $korime = $_POST['korime'];
    
    $upit = "SELECT * FROM `korisnik` WHERE "
            . "`korisnicko_ime`='{$korime}'";

    $rezultat = $veza->selectDB($upit);
    
    $generiranaLozinka = bin2hex(random_bytes(4));
    $lozinka256 = hash('sha256', $generiranaLozinka);
    
    $red = mysqli_fetch_array($rezultat);
    if ($red['korisnicko_ime'] == $korime) {
        $mail_to = $red['email'];
        $mail_from = "From: mforjan@foi.hr";
        $mail_subject = "Zaboravljena lozinka";
        $mail_body = "Nova lozinka je sljedeca: $generiranaLozinka";
        
        $sql = "UPDATE korisnik SET lozinka = '$generiranaLozinka', lozinka_sha256 = '$lozinka256' WHERE korisnicko_ime = '$korime'";
        $rezultat = $veza->updateDB($sql);

        if (mail($mail_to, $mail_subject, $mail_body, $mail_from)) {
            $emailPoruka = "Poslana poruka za: '$mail_to'!";
        } else {
            $emailPoruka = "Problem kod poruke za: '$mail_to'!";
        }
    }else {
        $poruka = "Blokirani ste ili Vam nije dobro korisničko ime!";
    }
    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Prijava</title>
        <meta charset="utf-8">
        <meta name="author" content="Matej Forjan">
        <meta name="description" content="08.06.2022.">
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
        <link href="../css/mforjan.css" rel="stylesheet" type="text/css">
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
                    echo "<li><a class='menu__item' href=\"$putanja/registracija.php\">Registracija</a></li>";

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
                PRIJAVA
            </h1>
            
            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$validiranPoruka</p>";
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$emailPoruka</p>";
            ?>
            
        </header>

        <section>
            <form novalidate class="forma" id="form2" method="post" name="form2" action="prijava.php">
                <label for="korime">Korisničko ime: </label>
                <input class="korimePrijavaTextbox" type="text" id="korime" name="korime" value="<?php print($zapamtiMeCookie) ?>" size="30" maxlength="30" placeholder="Korisničko ime" autofocus="autofocus" required="required"><br>
                <label for="lozinka">Lozinka: </label>
                <input class="lozinkaPrijavaTextbox" type="password" id="lozinka" name="lozinka" size="30" maxlength="30" placeholder="Lozinka" required="required"><br>
                <br>
                <input type="checkbox" name="zapamtiMe" value="1">Zapamti me<br>
                <div style="text-align: center">
                    <input type="submit" class="submit2" value="Zaboravljena lozinka" name="zaboravljenaLozinka">
                </div>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="form2" value="Prijavi se" name="prijavaGumb">
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
    </body>
</html>


