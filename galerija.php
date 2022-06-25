<?php
error_reporting(E_ALL ^ E_NOTICE);

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'zaglavlje.php';

?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Galerija</title>
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
                        echo "<li><a class='menu__item' href=\"$putanja/dnevnik_rada.php\">Dnevnik rada</a></li>";
                        
                    }

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
                GALERIJA
            </h1>

            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$neispravanFormatPoruka</p>";
            ?>

        </header>

        <section>

            <form novalidate class="forma" id="formGalerija" method="post" name="formGalerija" action="galerija.php">
                <label for="nazivPivnice">Odaberi pivnicu: </label>
                <select id="pivnica" name="pivnica">

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    $sql2 = "SELECT * FROM `pivnica`";

                    $rezultat2 = $veza->selectDB($sql2);

                    while ($row = mysqli_fetch_array($rezultat2)) {
                        ?>
                        <option value="<?php echo $row[0] ?>" ><?php echo $row['naziv'] ?></option>
                        <?php
                    }
                    $veza->zatvoriDB();
                    ?>

                </select>
                
                <br><br>
                
                <div style="text-align: center">
                    <input class="submit2" type="submit" form="formGalerija" value="Sortiraj po zemlji podrijetla" name="sortirajZemljaPodrijetla">
                </div>
                
                <br>
                
                <div style="text-align: center">
                    <input class="submit2" type="submit" form="formGalerija" value="Sortiraj po cijeni" name="sortirajCijena">
                </div>
                
                <br>

                <label for="ocjena">Odaberi ocjenu: </label>
                <select id="ocjena" name="ocjena">
                    <option value="0">===ODABERI OCJENU===</option>
                    <option value="1" >1</option>
                    <option value="2" >2</option>
                    <option value="3" >3</option>
                    <option value="4" >4</option>
                    <option value="5" >5</option>
                </select>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formGalerija" value="Pretraži" name="pretraziGumb">
            </div>


            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">GALERIJA PIVA</caption>
                <thead>
                    <tr>
                        <th>Naziv pivnice</th>
                        <th>Naziv piva</th>
                        <th>Slika</th>
                        <th>Zemlja podrijetla</th>
                        <th>Cijena pive</th>
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();

                    if (isset($_POST['pretraziGumb'])) {

                        $odabranaPivnica = $_POST['pivnica'];
                        $odabranaOcjena = $_POST['ocjena'];

                        if ($odabranaOcjena == 0) {
                            $sql = "SELECT `pivnica`.`naziv`, `pivo`.`naziv`, `pivo`.`slika`, `zemlja_podrijetla`.`naziv`, `pivo`.`cijena` "
                                    . "FROM `pivo`, `stavka_cjenika`, `cjenik`, `pivnica`, `zemlja_podrijetla` "
                                    . "WHERE `pivo`.`pivo_id` = `stavka_cjenika`.`pivo` AND `stavka_cjenika`.`cjenik` = `cjenik`.`cjenik_id` "
                                    . "AND `cjenik`.`cjenik_id` = `pivnica`.`cjenik` AND `pivnica`.`pivnica_id` = $odabranaPivnica AND `pivo`.`zemlja_podrijetla` = `zemlja_podrijetla`.`zemlja_podrijetla_id`";

                            $rezultat = $veza->selectDB($sql);

                            while ($row = mysqli_fetch_array($rezultat)) {
                                ?>
                                <tr>
                                    <td><?php echo $row[0] ?></td>
                                    <td><?php echo $row[1] ?></td>
                                    <td><img src="materijali/<?php echo $row[2] ?>" width="180" height="180"></td>
                                    <td><?php echo $row[3] ?></td>
                                    <td><?php echo $row[4] ?></td>
                                </tr>

                                <?php
                            }
                        } else {
                            $sql = "SELECT `pivnica`.`naziv`, `pivo`.`naziv`, `pivo`.`slika`, `zemlja_podrijetla`.`naziv`, `pivo`.`cijena` "
                                    . "FROM `pivo`, `stavka_cjenika`, `cjenik`, `pivnica`, `zemlja_podrijetla` "
                                    . "WHERE `pivo`.`pivo_id` = `stavka_cjenika`.`pivo` AND `stavka_cjenika`.`cjenik` = `cjenik`.`cjenik_id` "
                                    . "AND `cjenik`.`cjenik_id` = `pivnica`.`cjenik` AND `pivnica`.`pivnica_id` = $odabranaPivnica "
                                    . "AND `stavka_cjenika`.`prosjecna_ocjena` = $odabranaOcjena "
                                    . "AND `pivo`.`zemlja_podrijetla` = `zemlja_podrijetla`.`zemlja_podrijetla_id`";

                            $rezultat = $veza->selectDB($sql);

                            while ($row = mysqli_fetch_array($rezultat)) {
                                ?>
                                <tr>
                                    <td><?php echo $row[0] ?></td>
                                    <td><?php echo $row[1] ?></td>
                                    <td><img src="materijali/<?php echo $row[2] ?>" width="180" height="180"></td>
                                    <td><?php echo $row[3] ?></td>
                                    <td><?php echo $row[4] ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    
                    if(isset($_POST['sortirajZemljaPodrijetla'])){
                        $odabranaPivnica = $_POST['pivnica'];
                        $odabranaOcjena = $_POST['ocjena'];

                        if ($odabranaOcjena == 0) {
                            $sql = "SELECT pivnica.naziv, pivo.naziv, pivo.slika, zemlja_podrijetla.naziv, pivo.cijena "
                                    . "FROM zemlja_podrijetla, pivo, stavka_cjenika, cjenik, pivnica "
                                    . "WHERE zemlja_podrijetla.zemlja_podrijetla_id = pivo.zemlja_podrijetla "
                                    . "AND pivo.pivo_id = stavka_cjenika.pivo AND stavka_cjenika.cjenik = cjenik.cjenik_id "
                                    . "AND cjenik.cjenik_id = pivnica.cjenik AND pivnica.pivnica_id = $odabranaPivnica "
                                    . "ORDER BY 4 ASC";

                            $rezultat = $veza->selectDB($sql);

                            while ($row = mysqli_fetch_array($rezultat)) {
                                ?>
                                <tr>
                                    <td><?php echo $row[0] ?></td>
                                    <td><?php echo $row[1] ?></td>
                                    <td><img src="materijali/<?php echo $row[2] ?>" width="180" height="180"></td>
                                    <td><?php echo $row[3] ?></td>
                                    <td><?php echo $row[4] ?></td>
                                </tr>

                                <?php
                            }
                        }
                    }
                    
                    if(isset($_POST['sortirajCijena'])){
                        $odabranaPivnica = $_POST['pivnica'];
                        $odabranaOcjena = $_POST['ocjena'];

                        if ($odabranaOcjena == 0) {
                            $sql = "SELECT pivnica.naziv, pivo.naziv, pivo.slika, zemlja_podrijetla.naziv, pivo.cijena "
                                    . "FROM zemlja_podrijetla, pivo, stavka_cjenika, cjenik, pivnica "
                                    . "WHERE zemlja_podrijetla.zemlja_podrijetla_id = pivo.zemlja_podrijetla "
                                    . "AND pivo.pivo_id = stavka_cjenika.pivo AND stavka_cjenika.cjenik = cjenik.cjenik_id "
                                    . "AND cjenik.cjenik_id = pivnica.cjenik AND pivnica.pivnica_id = $odabranaPivnica "
                                    . "ORDER BY 5 ASC";

                            $rezultat = $veza->selectDB($sql);

                            while ($row = mysqli_fetch_array($rezultat)) {
                                ?>
                                <tr>
                                    <td><?php echo $row[0] ?></td>
                                    <td><?php echo $row[1] ?></td>
                                    <td><img src="materijali/<?php echo $row[2] ?>" width="180" height="180"></td>
                                    <td><?php echo $row[3] ?></td>
                                    <td><?php echo $row[4] ?></td>
                                </tr>

                                <?php
                            }
                        }
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


