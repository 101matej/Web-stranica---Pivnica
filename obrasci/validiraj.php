<?php
error_reporting(E_ALL ^ E_NOTICE);

$putanja = dirname($_SERVER['REQUEST_URI']);
$direktorij = dirname(getcwd());

include '../zaglavlje.php';

$poruka = "";
$greska = false;

if (isset($_POST['validirajGumb'])) {
    $veza = new Baza();
    $veza->spojiDB();

    $email = $_POST['email'];
    $kljuc = $_POST['kljuc'];

    if ($email == "" || $kljuc == "") {
        $greskaPoruka = "Morate unijeti email i ključ koji ste dobili u emailu!";
    } else {

        $upit = "SELECT * FROM `korisnik` WHERE "
                . "`email`='{$email}'";
        $rezultat = $veza->selectDB($upit);
        $red = mysqli_fetch_array($rezultat);

        $vrijeme_registracije = $red['vrijeme_registracije'];
        $trenutno_vrijeme = date('Y-m-d H:i:s');
        $sekunde = strtotime($trenutno_vrijeme) - strtotime($vrijeme_registracije);
        $broj_sati = $sekunde / 60 / 60;
        
        if ($email == $red['email']) {
            if ($broj_sati < 7) {
                if ($kljuc == $red['aktivacijski_kod']) {
                    $sql = "UPDATE korisnik SET `validiran`='1' WHERE email='" . $email . "'";
                    $rezultat = $veza->updateDB($sql);

                    $poruka = "Uspješno ste se validirali!";
                    header('Location: prijava.php');
                } else {
                    $greskaPoruka = "Ključ koji ste unijeli nije dobar!";
                }
            } else {
                $greskaPoruka = "Prošlo je više od 7h nakon što ste registrirali. Ne možete sada validirati mail!";
            }
        } else {
            $greskaPoruka = "Niste unijeli dobar email!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Validacija</title>
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
                    echo "<li><a class='menu__item' href=\"$putanja/../galerija.php\">Galerija</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/../rang_lista.php\">Rang lista</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/../o_autoru.html\">O autoru</a></li>";
                    
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
                VALIDACIJA
            </h1>

            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$greskaPoruka</p>";
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            ?>

        </header>

        <section>
            <form novalidate class="forma" id="form2" method="post" name="form2" action="validiraj.php">
                <label for="email">Email: </label>
                <input class="email" type="text" id="email" name="email" size="40" require><br>
                <label for="kljuc">Aktivacijski kod: </label>
                <input class="kljuc" type="password" id="kljuc" name="kljuc" size="40" require><br>
                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="form2" value="Validiraj" name="validirajGumb">
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