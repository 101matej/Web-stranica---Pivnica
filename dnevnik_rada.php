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
if (isset($_SESSION["uloga"]) && ($_SESSION["uloga"] == 1 || $_SESSION["uloga"] == 2 || $_SESSION["uloga"] == 3)) {
    header("Location: obrasci/prijava.php");
    unset($_COOKIE['autenticiran']);
    setcookie("autenticiran", "", time() - 3600, "/");
    Sesija::obrisiSesiju();
    exit();
}

if (isset($_POST['pretraziGumb'])) {
    $vrijemeGreska = false;

    $vrijemeOd = $_POST['vrijemeOd'];
    $vrijemeDo = $_POST['vrijemeDo'];

    $formatiranoVrijemeOd = date("Y-m-d H:i:s", strtotime($vrijemeOd));
    $formatiranoVrijemeDo = date("Y-m-d H:i:s", strtotime($vrijemeDo));

    $formatiranoVrijemeOdPocetno = "1970-01-01 01:00:00";
    $formatiranoVrijemeDoPocetno = "1970-01-01 01:00:00";

    $korisnik = $_POST['korisnik'];

    $tipRadnje = $_POST['tipRadnje'];

    if ($formatiranoVrijemeOd == $formatiranoVrijemeOdPocetno || $formatiranoVrijemeDo == $formatiranoVrijemeDoPocetno) {
        $vrijemeGreska = true;
    }
}

if (!isset($_POST['pretraziGumb'])) {
    $OnLoad = true;
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Dnevnik rada</title>
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
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                        
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pivnice.php\">Pivnice</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/zemljePodrijetla.php\">Zemlje podrijetla</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/blokirani_korisnici.php\">Blokirani korisnici</a></li>";
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
                DNEVNIK RADA
            </h1>

        </header>

        <section>

            <form novalidate class="forma" id="formDnevnikRada" method="post" name="formDnevnikRada" action="dnevnik_rada.php">
                <label for="vrijemeOd">Vrijeme radnje od: </label>
                <input class="korimePrijavaTextbox" type="datetime-local" name="vrijemeOd" size="30" maxlength="30" autofocus="autofocus" required="required"><br><br>

                <label for="vrijemeDo">Vrijeme radnje do: </label>
                <input class="korimePrijavaTextbox" type="datetime-local" name="vrijemeDo" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <br>

                <label for="korisnik">Odaberi korisnika: </label>
                <select id="korisnik" name="korisnik" class="korisnikCombobox">
                    <option value="0" >Odaberite korisnika</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT korisnik_id, CONCAT(korisnik.ime, ' ', korisnik.prezime) FROM korisnik";

                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $red[0] ?>" ><?php echo $red[1] ?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>

                <br><br>

                <label for="tipRadnje">Odaberite tip radnje: </label>
                <select id="tipRadnje" name="tipRadnje" class="tipRadnjeCombobox">
                    <option value="0" >Odaberite tip radnje</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT tip_radnje_id, naziv FROM tip_radnje";

                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $red[0] ?>" ><?php echo $red[1] ?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>

                <br><br>

            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formDnevnikRada" value="Pretraži" name="pretraziGumb">
            </div>

            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">PREGLED DNEVNIKA</caption>
                <thead>
                    <tr>
                        <th>Broj radnje</th>
                        <th>Korisnik</th>
                        <th>Tip radnje</th>
                        <th>Opis radnje</th>
                        <th>Upit</th>
                        <th>Datum i vrijeme radnje</th>
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    global $formatiranoVrijemeOd;
                    global $formatiranoVrijemeDo;
                    global $formatiranoVrijemeOdPocetno;
                    global $formatiranoVrijemeDoPocetno;
                    global $vrijemeGreska;
                    global $korisnik;
                    global $tipRadnje;

                    if (!$OnLoad) {
                        if ($vrijemeGreska == true && $korisnik == "0" && $tipRadnje == "0") {
                            $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), tip_radnje.naziv, "
                                    . "dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                    . "FROM dnevnika_rada, korisnik, tip_radnje "
                                    . "WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                    . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id ORDER BY 1";
                        } else {
                            if ($vrijemeGreska == false && $korisnik == "0" && $tipRadnje == "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), tip_radnje.naziv, "
                                        . "dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje "
                                        . "WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id "
                                        . "AND dnevnika_rada.datum_vrijeme BETWEEN '$formatiranoVrijemeOd' AND '$formatiranoVrijemeDo' "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == false && $korisnik != "0" && $tipRadnje == "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), tip_radnje.naziv, "
                                        . "dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id "
                                        . "AND dnevnika_rada.datum_vrijeme BETWEEN '$formatiranoVrijemeOd' AND '$formatiranoVrijemeDo' "
                                        . "AND korisnik.korisnik_id = $korisnik "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == false && $korisnik == "0" && $tipRadnje != "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), tip_radnje.naziv, "
                                        . "dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id "
                                        . "AND dnevnika_rada.datum_vrijeme BETWEEN '$formatiranoVrijemeOd' AND '$formatiranoVrijemeDo' "
                                        . "AND tip_radnje.tip_radnje_id = $tipRadnje "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == true && $korisnik != "0" && $tipRadnje == "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), "
                                        . "tip_radnje.naziv, dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id AND korisnik.korisnik_id = $korisnik "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == true && $korisnik == "0" && $tipRadnje != "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), "
                                        . "tip_radnje.naziv, dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id AND tip_radnje.tip_radnje_id = $tipRadnje "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == true && $korisnik != "0" && $tipRadnje != "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), "
                                        . "tip_radnje.naziv, dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id AND tip_radnje.tip_radnje_id = $tipRadnje "
                                        . "AND korisnik.korisnik_id = $korisnik "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == false && $korisnik == "0" && $tipRadnje != "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), "
                                        . "tip_radnje.naziv, dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id AND tip_radnje.tip_radnje_id = $tipRadnje "
                                        . "AND dnevnika_rada.datum_vrijeme BETWEEN '$formatiranoVrijemeOd' AND '$formatiranoVrijemeDo' "
                                        . "ORDER BY 6 ASC";
                            } else if ($vrijemeGreska == false && $korisnik != "0" && $tipRadnje != "0") {
                                $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), "
                                        . "tip_radnje.naziv, dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                        . "FROM dnevnika_rada, korisnik, tip_radnje WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                        . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id AND tip_radnje.tip_radnje_id = $tipRadnje "
                                        . "AND dnevnika_rada.datum_vrijeme BETWEEN '$formatiranoVrijemeOd' AND '$formatiranoVrijemeDo' "
                                        . "AND korisnik.korisnik_id = $korisnik "
                                        . "ORDER BY 6 ASC";
                            }
                        }
                    } else {
                        $sql = "SELECT dnevnika_rada.dnevnik_rada_id, CONCAT(korisnik.ime, ' ', korisnik.prezime), tip_radnje.naziv, "
                                . "dnevnika_rada.radnja, dnevnika_rada.upit, dnevnika_rada.datum_vrijeme "
                                . "FROM dnevnika_rada, korisnik, tip_radnje "
                                . "WHERE korisnik.korisnik_id = dnevnika_rada.korisnik "
                                . "AND dnevnika_rada.tip_radnje = tip_radnje.tip_radnje_id "
                                . "ORDER BY 6 ASC";
                    }



                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <td><?php echo $red[0] ?></td>
                            <td><?php echo $red[1] ?></td>
                            <td><?php echo $red[2] ?></td>
                            <td><?php echo $red[3] ?></td>
                            <td><?php echo $red[4] ?></td>
                            <td><?php echo $red[5] ?></td>
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