<?php
error_reporting(E_ALL ^ E_NOTICE);

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'zaglavlje.php';

if (!isset($_SESSION["uloga"])) {
    header("Location: obrasci/prijava.php");
    Sesija::obrisiSesiju();
    exit();
}

if (isset($_SESSION["uloga"]) && ($_SESSION["uloga"] == 1 || $_SESSION["uloga"] == 2)) {
    header("Location: obrasci/prijava.php");
    unset($_COOKIE['autenticiran']);
    setcookie("autenticiran", "", time() - 3600, "/");
    Sesija::obrisiSesiju();
    exit();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Narudzbe</title>
        <meta charset="utf-8">
        <meta name="author" content="Matej Forjan">
        <meta name="description" content="08.06.2022.">
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
        <link href="css/mforjan.css" rel="stylesheet" type="text/css">
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
                    echo "<li><a class='menu__item' href=\"$putanja/index.php\">Početna stranica</a></li>";

                    if (!isset($_SESSION["uloga"])) {
                        echo "<li><a class='menu__item' href=\"$putanja/obrasci/prijava.php\">Prijava</a></li>";
                    }

                    echo "<li><a class='menu__item' href=\"$putanja/obrasci/registracija.php\">Registracija</a></li>";

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 2) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 3) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pivnice.php\">Pivnice</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/zemljePodrijetla.php\">Zemlje podrijetla</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/blokirani_korisnici.php\">Blokirani korisnici</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/dnevnik_rada.php\">Dnevnik rada</a></li>";
                    }

                    echo "<li><a class='menu__item' href=\"$putanja/galerija.php\">Galerija</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/rang_lista.php\">Rang lista</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/o_autoru.html\">O autoru</a></li>";
                    echo "<li><a class='menu__item' href=\"$putanja/dokumentacija.html\">Dokumentacija</a></li>";

                    if (isset($_SESSION["uloga"])) {
                        echo "<li><a class='menu__item' href=\"$putanja/index.php?obrisi=true\">Odjava</a></li>";
                    }
                    ?>

                </ul>

            </div>

            <div class = "pozicijaLoga">
                <a href = "index.php">
                    <img class = "logo" src = "materijali/logo.png" alt = "Logo">
                </a>
            </div>

            <h1 class = "naslov" style = "text-align: center">
                NARUDŽBE
            </h1>

        </header>

        <section>

            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">NARUDŽBE</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Datum</th>
                        <th>Plaćeni iznos</th>
                        <th>Ukupan iznos</th>
                        <th>Plaćeno</th>
                        <th>Korisnik</th>
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT narudzba.narudzba_id, narudzba.datum, racun.placeni_iznos, "
                            . "racun.ukupan_iznos, narudzba.placeno, CONCAT(korisnik.ime, ' ', korisnik.prezime) "
                            . "FROM racun, narudzba, korisnik WHERE racun.narudzba = narudzba.narudzba_id "
                            . "AND narudzba.korisnik = korisnik.korisnik_id";

                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <?php
                            if ($red[4] == 1) {
                                ?>
                                <td style="background-color: lightgreen"><?php echo $red[0] ?></td>
                                <?php
                            } else {
                                ?>
                                <td style="background-color: lightcoral"><?php echo $red[0] ?></td>
                                <?php
                            }
                            if ($red[4] == 1) {
                                ?>
                                <td style="background-color: lightgreen"><?php echo $red[1] ?></td>
                                <?php
                            } else {
                                ?>
                                <td style="background-color: lightcoral"><?php echo $red[1] ?></td>
                                <?php
                            }
                            if ($red[4] == 1) {
                                ?>
                                <td style="background-color: lightgreen"><?php echo $red[2] ?></td>
                                <?php
                            } else {
                                ?>
                                <td style="background-color: lightcoral"><?php echo $red[2] ?></td>
                                <?php
                            }
                            if ($red[4] == 1) {
                                ?>
                                <td style="background-color: lightgreen"><?php echo $red[3] ?></td>
                                <?php
                            } else {
                                ?>
                                <td style="background-color: lightcoral"><?php echo $red[3] ?></td>
                                <?php
                            }
                            if ($red[4] == 1) {
                                ?>
                                <td style="background-color: lightgreen">Plaćeno</td>
                                <?php
                            } else {
                                ?>
                                <td style="background-color: lightcoral">Nije plaćeno</td>
                                <?php
                            }
                            if ($red[4] == 1) {
                                ?>
                                <td style="background-color: lightgreen"><?php echo $red[5] ?></td>
                                <?php
                            } else {
                                ?>
                                <td style="background-color: lightcoral"><?php echo $red[5] ?></td>
                                <?php
                            }
                            ?>
                        </tr>

                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>
                </tbody>
            </table>

        </section>
        <footer>
            <address><b>Kontakt:</b> 
                <a style="color: white; text-decoration: none;" href="mailto:mforjan@foi.hr">
                    Matej Forjan</a></address>
            <p>&copy; 2022 M. Forjan</p>
            <img style="width: 60px; height: 60px; position: relative; top: -7px;" src="materijali/HTML5.png" alt="Slika">
            <img style="width: 75px; height: 75px; position: relative; top: 0px;" src="materijali/CSS3.png" alt="Slika">
        </footer>
    </body>
</html>


