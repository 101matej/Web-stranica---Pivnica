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

if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 1) {
    header("Location: obrasci/prijava.php");
    unset($_COOKIE['autenticiran']);
    setcookie("autenticiran", "", time() - 3600, "/");
    Sesija::obrisiSesiju();
    exit();
}

if (isset($_POST['naziv'])) {
    $ocijeniNazivGumb = $_POST['naziv'];
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT * FROM pivo WHERE naziv='{$ocijeniNazivGumb}'";
    $rezultat = $veza->selectDB($sql);
    $red = mysqli_fetch_array($rezultat);
    
    $ocijeniId = $red['pivnica_id'];
    $ocijeniNaziv = $red['naziv'];
    
    $veza->zatvoriDB();
}

if (isset($_POST['ocijeniGumb'])) {
    $korisnik = $_SESSION['korisnik'];
    $pivo = $_POST['pivo'];
    $ocjena = $_POST['ocjena'];

    $veza = new Baza();
    $veza->spojiDB();
    
    $sqlKorisnik = "SELECT * FROM korisnik WHERE korisnicko_ime = '{$korisnik}'";
    $rezultatKorisnik = $veza->selectDB($sqlKorisnik);
    $redKorisnik = mysqli_fetch_array($rezultatKorisnik);
    $korisnikId = $redKorisnik[0];
    
    $sqlPivo = "SELECT * FROM pivo WHERE naziv = '{$pivo}'";
    $rezultatPivo = $veza->selectDB($sqlPivo);
    $redPivo = mysqli_fetch_array($rezultatPivo);
    $pivoId = $redPivo[0];
    
    if ($ocjena == "0") {
        $poruka = "Neispravno uneseni podaci!";
        $greska = true;
    }
    
    if (!$greska){
        $sqlInsert = "INSERT INTO ocjena_pive (korisnik, pivo, ocjena) "
            . "VALUES ('$korisnikId', '$pivoId', '$ocjena')";
        $rezultatInsert = $veza->updateDB($sqlInsert);
    }
    
    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Popis narucenih piva</title>
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
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 3) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/pive.php\">Pive</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/stavke_cjenika.php\">Stavke cjenika</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/narudzbe.php\">Narudžbe</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/neplacene_narudzbe.php\">Neplaćene narudžbe</a></li>";
                    }

                    if (isset($_SESSION["uloga"]) && $_SESSION["uloga"] == 4) {
                        echo "<li><a class='menu__item' href=\"$putanja/pregled_narudzbi.php\">Pregled i kreiranje narudžbi</a></li>";
                        echo "<li><a class='menu__item' href=\"$putanja/placanje_narudzbi.php\">Plaćanje narudžbi</a></li>";
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
                POPIS NARUČENIH PIVA
            </h1>
            
            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            ?>

        </header>

        <section>
            
            <form novalidate class="forma" id="formOcjena" method="post" name="formOcjena" action="popis_narucenih_piva.php">
                <label for="korisnik">Korisnik: </label>
                <input <?php
                
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sql = "SELECT * FROM korisnik WHERE korisnicko_ime='{$_SESSION['korisnik']}'";
                    $rezultat = $veza->selectDB($sql);
                    $red = mysqli_fetch_array($rezultat);
                    $ime = $red['ime'];
                    $prezime = $red['prezime'];

                    echo "value='{$ime} {$prezime}'";
                ?> class="korisnikTextbox" readonly type="text" name="korisnik" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                
                <label for="pivo">Pivo: </label>
                <input <?php
                global $ocijeniNaziv;
                if ($ocijeniNaziv != null){
                    echo "value='{$ocijeniNaziv}'";
                }
                ?> class="pivoTextbox" readonly type="text" name="pivo" size="30" autofocus="autofocus" required="required"><br>
                
                <label for="ocjena">Odaberi ocjenu: </label>
                <select id="ocjenaCombobox" name="ocjena">
                    <option value="0" >Odaberi ocjenu:</option>
                    <option value="1" >1</option>
                    <option value="2" >2</option>
                    <option value="3" >3</option>
                    <option value="4" >4</option>
                    <option value="5" >5</option>
                </select>
                
                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formOcjena" value="Ocijeni" name="ocijeniGumb">
            </div>
            
            <form name="popisNarucenihPiva" action="" method="post">
            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">POPIS NARUČENIH PIVA</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Datum</th>
                        <th>Korisnik</th>
                        <th>Naziv piva</th>
                        <th>Naziv pivnice</th>
                        <th>Plaćeno</th>
                        <th>Ocijeni</th>
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

                    $sql = "SELECT narudzba.narudzba_id, narudzba.datum, CONCAT(korisnik.ime, ' ', korisnik.prezime), "
                            . "pivo.naziv, pivnica.naziv, narudzba.placeno "
                            . "FROM pivo, stavka_narudzbe, narudzba, korisnik, pivnica "
                            . "WHERE pivo.pivo_id = stavka_narudzbe.pivo AND stavka_narudzbe.narudzba = narudzba.narudzba_id "
                            . "AND narudzba.korisnik = korisnik.korisnik_id AND narudzba.pivnica = pivnica.pivnica_id "
                            . "AND korisnik_id = $korisnikId";

                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <td><?php echo $red[0] ?></td>
                            <td><?php echo $red[1] ?></td>
                            <td><?php echo $red[2] ?></td>
                            <td><?php echo $red[3] ?></td>
                            <td><?php echo $red[4] ?></td>
                            <?php
                            if ($red[5] == 1) {
                            ?>
                                <td>Plaćeno</td>
                            <?php
                            } else {
                            ?>
                                <td>Nije plaćeno</td>
                            <?php
                            }
                            if ($red[5] == 1) {
                            ?>
                                <td>OCIJENI <input id="naziv" name="naziv" type="submit" value="<?php echo $red[3] ?>"></td>
                            <?php
                            } else {
                            ?>
                                <td>Ne možete ocijeniti</td>
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
            
            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">MOJE OCJENE</caption>
                <thead>
                    <tr>
                        <th>Korisnik</th>
                        <th>Pivo</th>
                        <th>Ocjena</th>
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

                    $sql = "SELECT CONCAT(korisnik.ime, ' ', korisnik.prezime), pivo.naziv, ocjena "
                            . "FROM ocjena_pive, korisnik, pivo "
                            . "WHERE ocjena_pive.korisnik = korisnik.korisnik_id "
                            . "AND ocjena_pive.pivo = pivo.pivo_id "
                            . "AND korisnik = $korisnikId";

                    $rezultat = $veza->selectDB($sql);

                    while ($red = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <td><?php echo $red[0] ?></td>
                            <td><?php echo $red[1] ?></td>
                            <td><?php echo $red[2] ?></td>
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