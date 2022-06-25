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

if (isset($_POST['odblokiraj'])) {
    $veza = new Baza();
    $veza->spojiDB();
    
    $korisnikId = $_POST['odblokiraj'];

    $sql = "UPDATE korisnik SET status = 0, broj_neuspjesne_prijave = 0 WHERE korisnik_id = {$korisnikId}";
    $rezultat = $veza->updateDB($sql);
    
    $veza->zatvoriDB();
}

if (isset($_POST['blokiraj'])) {
    $veza = new Baza();
    $veza->spojiDB();
    
    $korisnikId = $_POST['blokiraj'];

    $sql = "UPDATE korisnik SET status = 1 WHERE korisnik_id = {$korisnikId}";
    $rezultat = $veza->updateDB($sql);
    
    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Korisnici</title>
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
                KORISNICI
            </h1>

        </header>

        <section>
            
            <form name="blokiraniKorisnici" action="" method="post">
            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">BLOKIRANI KORISNICI</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Datum rođenja</th>
                        <th>Email</th>
                        <th>Korisničko ime</th>
                        <th>Status</th>
                        <th>Validiran</th>
                        <th>Tip korisnika</th>
                        <th>Odblokiranje</th>
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    
                    $sql = "SELECT korisnik_id, ime, prezime, datum_rodenja, email, korisnicko_ime, status, validiran, naziv "
                            . "FROM korisnik, tip_korisnika WHERE korisnik.tip_korisnika = tip_korisnika.tip_korisnika_id AND status = 1 "
                            . "ORDER BY 1";

                    $rezultat = $veza->selectDB($sql);
                    
                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <td><?php echo $row['korisnik_id'] ?></td>
                            <td><?php echo $row['ime'] ?></td>
                            <td><?php echo $row['prezime'] ?></td>
                            <td><?php echo $row['datum_rodenja'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['korisnicko_ime'] ?></td>
                            <?php
                            if ($row['status'] == 1) {
                            ?>
                                <td>Blokiran</td>
                            <?php
                            } else {
                            ?>
                                <td>Nije blokiran</td>
                            <?php
                            }
                            if ($row['validiran'] == 1) {
                            ?>
                                <td>Validiran račun</td>
                            <?php
                            } else {
                            ?>
                                <td>Nevalidiran račun</td>
                            <?php
                            }
                            ?>
                            <td><?php echo $row['naziv'] ?></td>
                            <td><input id="id" name="odblokiraj" type="submit" value="<?php echo $row['korisnik_id'] ?>"> ODBLOKIRAJ</td>
                        </tr>

                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>
                </tbody>
            </table>
            </form>
            
            <form name="odblokiraniKorisnici" action="" method="post">
            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">ODBLOKIRANI KORISNICI</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Datum rođenja</th>
                        <th>Email</th>
                        <th>Korisničko ime</th>
                        <th>Status</th>
                        <th>Validiran</th>
                        <th>Tip korisnika</th>
                        <th>Blokiranje</th>
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    
                    $sql = "SELECT korisnik_id, ime, prezime, datum_rodenja, email, korisnicko_ime, status, validiran, naziv "
                            . "FROM korisnik, tip_korisnika WHERE korisnik.tip_korisnika = tip_korisnika.tip_korisnika_id AND status = 0 "
                            . "ORDER BY 1";

                    $rezultat = $veza->selectDB($sql);
                    
                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <td><?php echo $row['korisnik_id'] ?></td>
                            <td><?php echo $row['ime'] ?></td>
                            <td><?php echo $row['prezime'] ?></td>
                            <td><?php echo $row['datum_rodenja'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['korisnicko_ime'] ?></td>
                            <?php
                            if ($row['status'] == 1) {
                            ?>
                                <td>Blokiran</td>
                            <?php
                            } else {
                            ?>
                                <td>Nije blokiran</td>
                            <?php
                            }
                            if ($row['validiran'] == 1) {
                            ?>
                                <td>Validiran račun</td>
                            <?php
                            } else {
                            ?>
                                <td>Nevalidiran račun</td>
                            <?php
                            }
                            ?>
                            <td><?php echo $row['naziv'] ?></td>
                            <td><input id="id" name="blokiraj" type="submit" value="<?php echo $row['korisnik_id'] ?>"> BLOKIRAJ</td>
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
