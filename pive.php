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
    $naziv = $_POST['naziv'];
    $cijena = $_POST['cijena'];
    $opis = $_POST['opis'];
    $rokTrajanja = $_POST['rokTrajanja'];
    $slika = $_POST['slika'];
    $volumen = $_POST['volumen'];
    $zemljaPodrijetla = $_POST['zemljaPodrijetla'];
    $vrsta = $_POST['vrsta'];

    if ($naziv == "" || $cijena == "" || $opis == "" || $rokTrajanja == "" || $slika == "" || $volumen == "" || $zemljaPodrijetla == "0" || $vrsta == "0") {
        $poruka = "Neispravno uneseni podaci!";
        $greska = true;
    }

    if (!$greska) {
        $veza = new Baza();
        $veza->spojiDB();
        $sql = "INSERT INTO pivo (naziv, opis, rok_trajanja, slika, zemlja_podrijetla, vrsta, cijena, volumen) "
                . "VALUES ('$naziv', '$opis', '$rokTrajanja', '$slika', $zemljaPodrijetla, $vrsta, $cijena, '$volumen')";
        $rezultat = $veza->updateDB($sql);
        $veza->zatvoriDB();
    }
}

//pritisnut id piva
if (isset($_POST['id'])) {
    $azurirajIdGumb = $_POST['id'];
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT * FROM pivo WHERE pivo_id='{$azurirajIdGumb}'";
    $rezultat = $veza->selectDB($sql);
    $red = mysqli_fetch_array($rezultat);

    $azurirajId = $red['pivo_id'];
    $azurirajNaziv = $red['naziv'];
    $azurirajOpis = $red['opis'];
    $azurirajRokTrajanja = $red['rok_trajanja'];
    $azurirajSlika = $red['slika'];
    $azurirajZemljaPodrijetla = $red['zemlja_podrijetla'];
    $azurirajVrsta = $red['vrsta'];
    $azurirajCijena = $red['cijena'];
    $azurirajVolumen = $red['volumen'];

    $veza->zatvoriDB();
}

//pritisnut gumb azuriraj
if (isset($_POST['azurirajGumb'])) {
    $veza = new Baza();
    $veza->spojiDB();

    $azurirajId = $_POST['idAzuriraj'];
    $azurirajNaziv = $_POST['nazivAzuriraj'];
    $azurirajOpis = $_POST['opisAzuriraj'];
    $azurirajRokTrajanja = $_POST['rokTrajanjaAzuriraj'];
    $azurirajSlika = $_POST['slikaAzuriraj'];
    $azurirajZemljaPodrijetla = $_POST['zemljaPodrijetlaAzuriraj'];
    $azurirajVrsta = $_POST['vrstaAzuriraj'];
    $azurirajCijena = $_POST['cijenaAzuriraj'];
    $azurirajVolumen = $_POST['volumenAzuriraj'];

    $sql = "UPDATE pivo SET naziv = '$azurirajNaziv', opis = '$azurirajOpis', rok_trajanja = '$azurirajRokTrajanja', "
            . "slika = '$azurirajSlika', zemlja_podrijetla = $azurirajZemljaPodrijetla, vrsta = $azurirajVrsta, "
            . "cijena = $azurirajCijena, volumen = '$azurirajVolumen' WHERE pivo_id = $azurirajId";
    $rezultat = $veza->updateDB($sql);
    
    header("Location: pive.php");

    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Pive</title>
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
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/popis_narucenih_piva.php\">Popis naručenih piva</a></li>";
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
                PIVE
            </h1>

            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            ?>

        </header>

        <section>

            <form novalidate class="forma" id="formPivo" method="post" name="formPivo" action="pive.php">
                <label for="naziv">Naziv: </label>
                <input class="nazivPiveTextbox" type="text" name="naziv" size="30" maxlength="30" placeholder="Naziv" autofocus="autofocus" required="required"><br>

                <label for="cijena">Cijena: </label>
                <input class="cijenaPiveTextbox" type="text" name="cijena" size="30" maxlength="30" placeholder="Cijena"autofocus="autofocus" required="required"><br>

                <label for="opis">Opis: </label>
                <input class="opisPiveTextbox" type="text" name="opis" size="30" maxlength="30" placeholder="Opis" autofocus="autofocus" required="required"><br>

                <label for="rokTrajanja">Rok trajanja: </label>
                <input class="rokTrajanjaTextbox" type="date" name="rokTrajanja" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="slika" id="slika">Slika: </label>
                <input class="slikaInput" type="file" id="slika" name="slika"><br>

                <label for="volumen">Volumen: </label>
                <input class="volumenPiveTextbox" type="text" name="volumen" size="30" maxlength="30" placeholder="Volumen" autofocus="autofocus" required="required"><br>

                <label for="zemljaPodrijetla">Odaberi zemlju podrijetla: </label>
                <select id="zemljaPodrijetla" class="zemljaPodrijetlaCombobox" name="zemljaPodrijetla">
                    <option value="0" >Odaberi zemlju podrijetla:</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT zemlja_podrijetla.zemlja_podrijetla_id, zemlja_podrijetla.naziv FROM zemlja_podrijetla";

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

                <label for="vrsta">Odaberi vrstu: </label>
                <select id="vrsta" class="vrstaCombobox" name="vrsta">
                    <option value="0" >Odaberi vrstu:</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT vrsta_piva.vrsta_piva_id, vrsta_piva.naziv FROM vrsta_piva";

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
                <input type="submit" class="submit" form="formPivo" value="Unesi" name="unesiGumb">
            </div>


            <form novalidate class="forma" id="formPivoAzuriranje" method="post" name="formPivoAzuriranje" action="pive.php">
                <label for="id">Id: </label>
                <input <?php
                global $azurirajId;
                if ($azurirajId != null) {
                    echo "value='{$azurirajId}'";
                }
                ?> class="idPiveTextbox" type="text" name="idAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="naziv">Naziv: </label>
                <input <?php
                global $azurirajNaziv;
                if ($azurirajNaziv != null) {
                    echo "value='{$azurirajNaziv}'";
                }
                ?> class="nazivPiveTextbox" type="text" name="nazivAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="cijena">Cijena: </label>
                <input <?php
                global $azurirajCijena;
                if ($azurirajCijena != null) {
                    echo "value='{$azurirajCijena}'";
                }
                ?> class="cijenaPiveTextbox" type="text" name="cijenaAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="opis">Opis: </label>
                <input <?php
                global $azurirajOpis;
                if ($azurirajOpis != null) {
                    echo "value='{$azurirajOpis}'";
                }
                ?> class="opisPiveTextbox" type="text" name="opisAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="rokTrajanja">Rok trajanja: </label>
                <input <?php
                global $azurirajRokTrajanja;
                if ($azurirajRokTrajanja != null) {
                    echo "value='{$azurirajRokTrajanja}'";
                }
                ?> class="rokTrajanjaTextbox" type="date" name="rokTrajanjaAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="slika" id="slika">Slika: </label>
                <input <?php
                global $azurirajSlika;
                if ($azurirajSlika != null) {
                    echo "value='{$azurirajSlika}'";
                }
                ?> class="slikaInput" type="file" id="slika" name="slikaAzuriraj"><br>

                <label for="volumen">Volumen: </label>
                <input <?php
                global $azurirajVolumen;
                if ($azurirajVolumen != null) {
                    echo "value='{$azurirajVolumen}'";
                }
                ?> class="volumenPiveTextbox" type="text" name="volumenAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>

                <label for="zemljaPodrijetla">Odaberi zemlju podrijetla: </label>
                <select id="zemljaPodrijetla" class="zemljaPodrijetlaCombobox" name="zemljaPodrijetlaAzuriraj">
                    <option value="0" >Odaberi zemlju podrijetla:</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT zemlja_podrijetla.zemlja_podrijetla_id, zemlja_podrijetla.naziv FROM zemlja_podrijetla";

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

                <label for="vrsta">Odaberi vrstu: </label>
                <select id="vrsta" class="vrstaCombobox" name="vrstaAzuriraj">
                    <option value="0" >Odaberi vrstu:</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT vrsta_piva.vrsta_piva_id, vrsta_piva.naziv FROM vrsta_piva";

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
                <input type="submit" class="submit" form="formPivoAzuriranje" value="Ažuriraj" name="azurirajGumb">
            </div>

            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">PIVE PO PIVNICAMA</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naziv</th>
                        <th>Cijena</th>
                        <th>Opis</th>
                        <th>Rok trajanja</th>
                        <th>Slika</th>
                        <th>Volumen</th>
                        <th>Vrsta</th>
                        <th>Pivnica</th>
                        <th>Zemlja podrijetla</th>
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
                        $sql = "SELECT pivo.pivo_id, pivo.naziv, pivo.cijena, pivo.opis, pivo.rok_trajanja, pivo.slika, pivo.volumen, "
                                . "vrsta_piva.naziv, pivnica.naziv, zemlja_podrijetla.naziv, CONCAT(korisnik.ime, ' ',korisnik.prezime) "
                                . "FROM zemlja_podrijetla, pivo, stavka_cjenika, cjenik, pivnica, korisnik, vrsta_piva "
                                . "WHERE vrsta_piva.vrsta_piva_id = pivo.vrsta AND zemlja_podrijetla.zemlja_podrijetla_id = pivo.zemlja_podrijetla "
                                . "AND pivo.pivo_id = stavka_cjenika.pivo AND stavka_cjenika.cjenik = cjenik.cjenik_id "
                                . "AND cjenik.cjenik_id = pivnica.cjenik AND pivnica.moderator = korisnik.korisnik_id ";
                    } else {
                        $sql = "SELECT pivo.pivo_id, pivo.naziv, pivo.cijena, pivo.opis, pivo.rok_trajanja, pivo.slika, pivo.volumen, "
                                . "vrsta_piva.naziv, pivnica.naziv, zemlja_podrijetla.naziv, CONCAT(korisnik.ime, ' ',korisnik.prezime) "
                                . "FROM zemlja_podrijetla, pivo, stavka_cjenika, cjenik, pivnica, korisnik, vrsta_piva "
                                . "WHERE vrsta_piva.vrsta_piva_id = pivo.vrsta AND zemlja_podrijetla.zemlja_podrijetla_id = pivo.zemlja_podrijetla "
                                . "AND pivo.pivo_id = stavka_cjenika.pivo AND stavka_cjenika.cjenik = cjenik.cjenik_id "
                                . "AND cjenik.cjenik_id = pivnica.cjenik AND pivnica.moderator = korisnik.korisnik_id "
                                . "AND pivnica.moderator = $korisnikId";
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
                            <td><img src="materijali/<?php echo $row[5] ?>" width="130" height="130"></td>
                            <td><?php echo $row[6] ?></td>
                            <td><?php echo $row[7] ?></td>
                            <td><?php echo $row[8] ?></td>
                            <td><?php echo $row[9] ?></td>
                            <td><?php echo $row[10] ?></td>
                        </tr>

                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>
                </tbody>
            </table>

            <form name="pivnica" action="" method="post">
                <table class="display" id="tablica">
                    <caption style="font-size: 22px; font-weight: bolder">LISTA SVIH PIVA</caption>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naziv</th>
                            <th>Cijena</th>
                            <th>Opis</th>
                            <th>Rok trajanja</th>
                            <th>Slika</th>
                            <th>Volumen</th>
                            <th>Vrsta</th>
                            <th>Zemlja podrijetla</th>
                        </tr>
                    </thead>
                    <tbody> 

                        <?php
                        $veza = new Baza();
                        $veza->spojiDB();
                        $sql = "SELECT pivo.pivo_id, pivo.naziv, pivo.cijena, pivo.opis, pivo.rok_trajanja, pivo.slika, pivo.volumen, "
                                . "vrsta_piva.naziv, zemlja_podrijetla.naziv "
                                . "FROM zemlja_podrijetla, pivo, vrsta_piva "
                                . "WHERE vrsta_piva.vrsta_piva_id = pivo.vrsta AND zemlja_podrijetla.zemlja_podrijetla_id = pivo.zemlja_podrijetla "
                                . "ORDER BY pivo_id ASC";

                        $rezultat = $veza->selectDB($sql);

                        while ($row = mysqli_fetch_array($rezultat)) {
                            ?>
                            <tr>
                                <td><input id="id" name="id" type="submit" value="<?php echo $row['pivo_id'] ?>"> AŽURIRAJ</td>
                                <td><?php echo $row[1] ?></td>
                                <td><?php echo $row[2] ?></td>
                                <td><?php echo $row[3] ?></td>
                                <td><?php echo $row[4] ?></td>
                                <td><img src="materijali/<?php echo $row[5] ?>" width="130" height="130"></td>
                                <td><?php echo $row[6] ?></td>
                                <td><?php echo $row[7] ?></td>
                                <td><?php echo $row[8] ?></td>
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