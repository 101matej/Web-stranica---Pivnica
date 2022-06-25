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
if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 1) {
    header("Location: obrasci/prijava.php");
    unset($_COOKIE['autenticiran']);
    setcookie("autenticiran", "", time() - 3600, "/");
    Sesija::obrisiSesiju();
    exit();
}

if (isset($_POST['plati'])) {
    $veza = new Baza();
    $veza->spojiDB();

    $narudzbaId = $_POST['plati'];

    $sql = "UPDATE narudzba SET placeno = 1 WHERE narudzba_id = {$narudzbaId}";
    $rezultat = $veza->updateDB($sql);

    $sql2 = "UPDATE racun SET placeni_iznos = ukupan_iznos WHERE narudzba = {$narudzbaId}";
    $rezultat2 = $veza->updateDB($sql2);

    //odblokiranje korisnika
    $korisnikoIme = $_COOKIE['autenticiran'];

    $sql4 = "SELECT * FROM korisnik WHERE korisnicko_ime='{$korisnikoIme}'";
    $rezultat4 = $veza->selectDB($sql4);
    $red4 = mysqli_fetch_array($rezultat4);

    $korisnikId = $red4['korisnik_id'];

    $sql3 = "SELECT narudzba.narudzba_id, pivo.naziv, racun.placeni_iznos, racun.ukupan_iznos, 
        narudzba.placeno, pivnica.naziv, narudzba.datum, CONCAT (korisnik.ime, ' ', korisnik.prezime), korisnik.status
        FROM narudzba, korisnik, racun, pivnica, stavka_narudzbe, pivo 
        WHERE narudzba.korisnik = korisnik.korisnik_id 
        AND narudzba.narudzba_id = racun.narudzba 
        AND pivnica.pivnica_id = narudzba.pivnica 
        AND korisnik.korisnik_id = $korisnikId
        AND stavka_narudzbe.narudzba = narudzba.narudzba_id 
        AND pivo.pivo_id = stavka_narudzbe.pivo 
        AND narudzba.placeno = 0 ORDER BY 1 ASC";

    $rezultat3 = $veza->selectDB($sql3);
    
    if (mysqli_num_rows($rezultat3) == 0) {
        $sqlUpdate = "UPDATE korisnik SET status = 0 WHERE korisnik_id = $korisnikId";
        $rezultatUpdate = $veza->updateDB($sqlUpdate);
    }
    
    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Placanje narudzbi</title>
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
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 3) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
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
                PLAĆANJE NARUDŽBI
            </h1>

        </header>

        <section>

            <form name="pregledNarudzbi" action="" method="post">
                <table class="display" id="tablica">
                    <caption style="font-size: 22px; font-weight: bolder">PLAĆANJE NARUDŽBI</caption>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Datum</th>
                            <th>Korisnik</th>
                            <th>Naziv pivnice</th>
                            <th>Naziv piva</th>
                            <th>Plaćeni iznos</th>
                            <th>Ukupan iznos</th>
                            <th>Plaćeno</th>
                            <th>Blokiran</th>
                            <th>Plati</th>
                        </tr>
                    </thead>
                    <tbody> 

                        <?php
                        $veza = new Baza();
                        $veza->spojiDB();
                        
                        $korisnikoIme = $_COOKIE['autenticiran'];
                        
                        $sql2 = "SELECT * FROM korisnik WHERE korisnicko_ime='{$korisnikoIme}'";
                        $rezultat2 = $veza->selectDB($sql2);
                        $red2 = mysqli_fetch_array($rezultat2);

                        $korisnikId = $red2['korisnik_id'];

                        $sql = "SELECT narudzba.narudzba_id, narudzba.datum, CONCAT (korisnik.ime, ' ', korisnik.prezime), "
                                . "pivnica.naziv, pivo.naziv, racun.placeni_iznos, racun.ukupan_iznos, narudzba.placeno, korisnik.status "
                                . "FROM narudzba, korisnik, racun, pivnica, stavka_narudzbe, pivo "
                                . "WHERE narudzba.korisnik = korisnik.korisnik_id AND narudzba.narudzba_id = racun.narudzba "
                                . "AND pivnica.pivnica_id = narudzba.pivnica AND narudzba.narudzba_id = stavka_narudzbe.narudzba "
                                . "AND stavka_narudzbe.pivo = pivo.pivo_id AND korisnik.korisnik_id = $korisnikId "
                                . "ORDER BY 1";

                        $rezultat = $veza->selectDB($sql);

                        while ($row = mysqli_fetch_array($rezultat)) {
                            ?>
                            <tr>
                                <td><?php echo $row[0] ?></td>
                                <td><?php echo $row[1] ?></td>
                                <td><?php echo $row[2] ?></td>
                                <td><?php echo $row[3] ?></td>
                                <td><?php echo $row[4] ?></td>
                                <td><?php echo $row[5] ?></td>
                                <td><?php echo $row[6] ?></td>
                                <?php
                                if ($row[7] == 1) {
                                ?>
                                    <td>Plaćeno</td>
                                <?php
                                } else {
                                ?>
                                    <td>Nije plaćeno</td>
                                <?php
                                }
                                if ($row[8] == 1) {
                                ?>
                                    <td>Blokiran</td>
                                <?php
                                } else {
                                ?>
                                    <td>Nije blokiran</td>
                                <?php
                                }
                                if ($row[7] == 1) {
                                ?>
                                    <td>PLAĆENO</td>
                                <?php
                                } else {
                                ?>
                                    <td><input id="id" name="plati" type="submit" value="<?php echo $row['narudzba_id'] ?>"> PLATI</td>
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