<?php
error_reporting(E_ALL ^ E_NOTICE);

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'zaglavlje.php';

$greska = false;

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

if (isset($_POST['unesiGumb'])) {
    $cjenik = $_POST['cjenik'];
    $pivo = $_POST['pivo'];

    if ($cjenik == "0" || $pivo == "0") {
        $poruka = "Neispravno uneseni podaci!";
        $greska = true;
    }

    if (!$greska) {
        $veza = new Baza();
        $veza->spojiDB();
        $sql = "INSERT INTO stavka_cjenika (cjenik, pivo) "
                . "VALUES ($cjenik, $pivo)";
        $rezultat = $veza->updateDB($sql);
        $veza->zatvoriDB();
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Stavke cjenika</title>
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
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
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
                STAVKE CJENIKA U PIVNICAMA
            </h1>

            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            ?>

        </header>

        <section>

            <form novalidate class="forma" id="formPivnica" method="post" name="formPivnica" action="stavke_cjenika.php">
                <label for="cjenik">Odaberi cjenik: </label>
                <select class="cjenikCombobox" name="cjenik">
                    <option value="0" >Odaberi cjenik:</option>

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    
                    $korime = $_COOKIE['autenticiran'];

                    $sql2 = "SELECT * FROM korisnik WHERE korisnicko_ime='{$korime}'";
                    $rezultat2 = $veza->selectDB($sql2);
                    $red2 = mysqli_fetch_array($rezultat2);

                    $korisnikId = $red2['korisnik_id'];
                    
                    if(isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4){
                        $sql = "SELECT cjenik.cjenik_id, cjenik.naziv FROM cjenik";
                    } else{
                        $sql = "SELECT cjenik.cjenik_id, cjenik.naziv "
                                . "FROM cjenik, pivnica, korisnik "
                                . "WHERE cjenik.cjenik_id = pivnica.cjenik AND pivnica.moderator = korisnik.korisnik_id "
                                . "AND pivnica.moderator = $korisnikId";
                    }

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

                <label for="pivo">Odaberi pivo: </label>
                <select id="pivo" class="pivoCombobox" name="pivo">
                    <option value="0" >Odaberi pivo:</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT pivo.pivo_id, pivo.naziv FROM pivo";

                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $red[0] ?>" ><?php echo $red[1] ?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>

                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formPivnica" value="Unesi" name="unesiGumb">
            </div>

            <form name="pivnica" action="" method="post">
                <table class="display" id="tablica">
                    <caption style="font-size: 22px; font-weight: bolder">STAVKE CJENIKA</caption>
                    <thead>
                        <tr>
                            <th>Naziv pivnice</th>
                            <th>Naziv cjenika</th>
                            <th>Naziv piva</th>
                            <th>Moderator</th>
                        </tr>
                    </thead>
                    <tbody> 

                        <?php
                        $veza = new Baza();
                        $veza->spojiDB();

                        $korime = $_COOKIE['autenticiran'];

                        $sql2 = "SELECT * FROM korisnik WHERE korisnicko_ime='{$korime}'";
                        $rezultat2 = $veza->selectDB($sql2);
                        $red = mysqli_fetch_array($rezultat2);

                        $korisnikId = $red['korisnik_id'];

                        if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                            $sql = "SELECT pivnica.naziv, cjenik.naziv, pivo.naziv, CONCAT(korisnik.ime, ' ', korisnik.prezime) "
                                    . "FROM pivo, stavka_cjenika, cjenik, pivnica, korisnik "
                                    . "WHERE pivo.pivo_id = stavka_cjenika.pivo AND stavka_cjenika.cjenik = cjenik.cjenik_id "
                                    . "AND cjenik.cjenik_id = pivnica.cjenik AND pivnica.moderator = korisnik.korisnik_id "
                                    . "GROUP BY pivnica.naziv, cjenik.naziv, pivo.naziv, 4 "
                                    . "ORDER BY 4";
                        } else {
                            $sql = "SELECT pivnica.naziv, cjenik.naziv, pivo.naziv, CONCAT(korisnik.ime, ' ', korisnik.prezime) "
                                    . "FROM pivo, stavka_cjenika, cjenik, pivnica, korisnik "
                                    . "WHERE pivo.pivo_id = stavka_cjenika.pivo AND stavka_cjenika.cjenik = cjenik.cjenik_id "
                                    . "AND cjenik.cjenik_id = pivnica.cjenik AND pivnica.moderator = korisnik.korisnik_id "
                                    . "AND pivnica.moderator = $korisnikId "
                                    . "GROUP BY pivnica.naziv, cjenik.naziv, pivo.naziv, 4";
                        }

                        $rezultat = $veza->selectDB($sql);

                        while ($row = mysqli_fetch_array($rezultat)) {
                            ?>
                            <tr>
                                <td><?php echo $row[0] ?></td>
                                <td><?php echo $row[1] ?></td>
                                <td><?php echo $row[2] ?></td>
                                <td><?php echo $row[3] ?></td>
                                <td><?php echo $row[4] ?></td>
                            </tr>

                            <?php
                        }
                        $veza->zatvoriDB();
                        ?>
                    </tbody>
                </table>
            </form>

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