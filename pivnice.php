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
if (isset($_SESSION["uloga"]) && ($_SESSION["uloga"] == 1 || $_SESSION["uloga"] == 2 || $_SESSION["uloga"] == 3)) {
    header("Location: obrasci/prijava.php");
    unset($_COOKIE['autenticiran']);
    setcookie("autenticiran", "", time() - 3600, "/");
    Sesija::obrisiSesiju();
    exit();
}

if (isset($_POST['unesiGumb'])) {
    $nazivPivnica = $_POST['nazivPivnice'];
    $adresa = $_POST['adresa'];
    $brojTelefona = $_POST['brojTelefona'];
    $moderator = $_POST['moderator'];
    $cjenik = $_POST['cjenik'];

    if ($nazivPivnica == "" || $adresa == "" || $brojTelefona == "" || $moderator == "0" || $cjenik == "0") {
        $poruka = "Neispravno uneseni podaci!";
        $greska = true;
    }

    if (!$greska) {
        $veza = new Baza();
        $veza->spojiDB();
        $sql = "INSERT INTO pivnica (naziv, adresa, broj_telefona, moderator, cjenik) "
                . "VALUES ('$nazivPivnica', '$adresa', '$brojTelefona', $moderator, $cjenik)";
        $rezultat = $veza->updateDB($sql);
        $veza->zatvoriDB();
    }
}

//pritisnut id zemlje podrijetla
if (isset($_POST['id'])) {
    $azurirajIdGumb = $_POST['id'];
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT * FROM pivnica WHERE pivnica_id='{$azurirajIdGumb}'";
    $rezultat = $veza->selectDB($sql);
    $red = mysqli_fetch_array($rezultat);
    
    $azurirajId = $red['pivnica_id'];
    $azurirajNaziv = $red['naziv'];
    $azurirajAdresa = $red['adresa'];
    $azurirajBrojTelefona = $red['broj_telefona'];
    $azurirajModerator = $red['moderator'];
    $azurirajCjenik = $red['cjenik'];
    
    $veza->zatvoriDB();
}

if (isset($_POST['azurirajGumb'])) {
    $veza = new Baza();
    $veza->spojiDB();
    
    $azurirajId = $_POST['idPivniceAzuriraj'];
    $azurirajNaziv = $_POST['nazivPivniceAzuriraj'];
    $azurirajAdresa = $_POST['adresaAzuriraj'];
    $azurirajBrojTelefona = $_POST['brojTelefonaAzuriraj'];
    $azurirajModerator = $_POST['moderator'];
    $azurirajCjenik = $_POST['cjenik'];

    $sql = "UPDATE pivnica SET naziv = '{$azurirajNaziv}', adresa = '{$azurirajAdresa}', broj_telefona = '{$azurirajBrojTelefona}', "
    . "moderator = {$azurirajModerator}, cjenik = {$azurirajCjenik} WHERE pivnica_id = {$azurirajId}";
    $rezultat = $veza->updateDB($sql);
    
    header("Location: pivnice.php");
    
    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Pivnice</title>
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
                PIVNICE
            </h1>

            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            ?>

        </header>

        <section>

            <form novalidate class="forma" id="formPivnica" method="post" name="formPivnica" action="pivnice.php">
                <label for="nazivPivnice">Naziv pivnice: </label>
                <input class="nazivPivniceTextbox" type="text" name="nazivPivnice" size="30" maxlength="30" placeholder="Naziv pivnice" autofocus="autofocus" required="required"><br>
                
                <label for="adresa">Adresa: </label>
                <input class="adresaPivniceTextbox" type="text" name="adresa" size="30" maxlength="30" placeholder="Adresa"autofocus="autofocus" required="required"><br>
                
                <label for="brojTelefona">Broj telefona: </label>
                <input class="brojTelefonaTextbox" type="text" name="brojTelefona" size="30" maxlength="30" placeholder="Broj telefona" autofocus="autofocus" required="required"><br>
                
                <label for="moderator">Odaberi moderatora: </label>
                <select id="moderator" name="moderator">
                    <option value="0" >Odaberi moderatora:</option>

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT korisnik.korisnik_id, CONCAT(korisnik.ime, ' ',korisnik.prezime) FROM korisnik WHERE korisnik.tip_korisnika = 3";

                    $rezultat = $veza->selectDB($sql);

                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $row[0] ?>" ><?php echo $row[1] ?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>
                
                <br>
                
                <label for="cjenik">Odaberi cjenik: </label>
                <select id="cjenik" name="cjenik">
                    <option value="0" >Odaberi cjenik:</option>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT cjenik.cjenik_id, cjenik.naziv FROM cjenik";

                    $rezultat = $veza->selectDB($sql);

                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $row[0] ?>" ><?php echo $row[1] ?></option>
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
            
            <form novalidate class="forma" id="formPivnicaAzuriranje" method="post" name="formPivnicaAzuriranje" action="pivnice.php">
                <label for="idPivniceAzuriraj">Id pivnice: </label>
                <input <?php
                global $azurirajId;
                if ($azurirajId != null){
                    echo "value='{$azurirajId}'";
                }
                ?> class="idPivniceTextbox" type="text" name="idPivniceAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                
                <label for="nazivPivniceAzuriraj">Naziv pivnice: </label>
                <input <?php
                global $azurirajNaziv;
                if ($azurirajNaziv != null){
                    echo "value='{$azurirajNaziv}'";
                }
                ?> class="nazivPivniceTextbox" type="text" name="nazivPivniceAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                
                <label for="adresaAzuriraj">Adresa: </label>
                
                <input <?php
                global $azurirajAdresa;
                if ($azurirajAdresa != null){
                    echo "value='{$azurirajAdresa}'";
                }
                ?> class="adresaPivniceTextbox" type="text" name="adresaAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                
                <label for="brojTelefonaAzuriraj">Broj telefona: </label>
                
                <input <?php
                global $azurirajBrojTelefona;
                if ($azurirajBrojTelefona != null){
                    echo "value='{$azurirajBrojTelefona}'";
                }
                ?> class="brojTelefonaTextbox" type="text" name="brojTelefonaAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                
                <label for="moderator">Odaberi moderatora: </label>
                <select id="moderator" name="moderator">

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT korisnik.korisnik_id, CONCAT(korisnik.ime, ' ',korisnik.prezime) FROM korisnik WHERE korisnik.tip_korisnika = 3";

                    $rezultat = $veza->selectDB($sql);

                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $row[0]?>" ><?php echo $row[1]?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>
                
                <br>
                
                <label for="cjenik">Odaberi cjenik: </label>
                <select id="cjenik" name="cjenik">

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql = "SELECT cjenik.cjenik_id, cjenik.naziv FROM cjenik";

                    $rezultat = $veza->selectDB($sql);

                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <option value="<?php echo $row[0] ?>" ><?php echo $row[1] ?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>
                
                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formPivnicaAzuriranje" value="Ažuriraj" name="azurirajGumb">
            </div>

            <form name="pivnica" action="" method="post">
                <table class="display" id="tablica">
                    <caption style="font-size: 22px; font-weight: bolder">PIVNICE</caption>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naziv</th>
                            <th>Adresa</th>
                            <th>Broj telefona</th>
                            <th>Moderator</th>
                            <th>Cjenik</th>
                        </tr>
                    </thead>
                    <tbody> 

                        <?php
                        $veza = new Baza();
                        $veza->spojiDB();

                        $sql = "SELECT pivnica_id, pivnica.naziv, adresa, broj_telefona, CONCAT(korisnik.ime, ' ',korisnik.prezime), cjenik.naziv "
                                . "FROM pivnica, korisnik, cjenik "
                                . "WHERE pivnica.moderator = korisnik.korisnik_id AND pivnica.cjenik = cjenik.cjenik_id";

                        $rezultat = $veza->selectDB($sql);

                        while ($row = mysqli_fetch_array($rezultat)) {
                            ?>
                            <tr>
                                <td><input id="id" name="id" type="submit" value="<?php echo $row['pivnica_id'] ?>"> AŽURIRAJ</td>
                                <td><?php echo $row[1] ?></td>
                                <td><?php echo $row['adresa'] ?></td>
                                <td><?php echo $row['broj_telefona'] ?></td>
                                <td><?php echo $row[4] ?></td>
                                <td><?php echo $row[5] ?></td>
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