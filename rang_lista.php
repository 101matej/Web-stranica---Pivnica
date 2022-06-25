<?php
error_reporting(E_ALL ^ E_NOTICE);

$direktorij = getcwd();
$putanja = dirname($_SERVER['REQUEST_URI']);

include 'zaglavlje.php';

$greska = false;

if(isset($_POST['pretraziGumb'])){
    $datumOd = $_POST['datumOd'];
    $datumDo = $_POST['datumDo'];
    
    $regularniIzrazDatum = '/^(3[01]|[12][0-9]|0[1-9])[.](1[0-2]|0[1-9])[.][0-9]{4}[.]$/';
    if (!preg_match($regularniIzrazDatum, $datumOd) || !preg_match($regularniIzrazDatum, $datumDo)) {
        $neispravanFormatPoruka = "Datum je neispravnog formata! Ispravan format je 'dd.mm.gggg.'!";
        $greska = true;
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Rang lista</title>
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
                    
                    echo "<li><a class='menu__item' href=\"$putanja/galerija.php\">Galerija</a></li>";
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
                RANG LISTA
            </h1>
            
            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$neispravanFormatPoruka</p>";
            
            ?>

        </header>

        <section>
            
            <form novalidate class="forma" id="formRangLista" method="post" name="formRangLista" action="rang_lista.php">
                <label for="datumOd">Datum od: </label>
                <input class="korimePrijavaTextbox" type="text" name="datumOd" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                <label for="datumDo">Datum do: </label>
                <input class="korimePrijavaTextbox" type="text" name="datumDo" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formRangLista" value="Pretraži" name="pretraziGumb">
            </div>
            
            <table class="display" id="tablica">
                <caption style="font-size: 22px; font-weight: bolder">RANG LISTA</caption>
                <thead>
                    <tr>
                        <th>Broj piva</th>
                        <th>Pivnica</th>
                        <th>Datum od</th>
                        <th>Datum do</th>
                    </tr>
                </thead>
                <tbody> 

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    global $datumOd;
                    global $datumDo;
                    global $greska;
                    
                    $formatiraniDatumOd = date("Y-m-d", strtotime($datumOd));
                    $formatiraniDatumDo = date("Y-m-d", strtotime($datumDo));
                    
                    if($greska == false){
                    
                    $sql = "SELECT SUM(`kolicina`), naziv FROM `stavka_narudzbe`, `narudzba`, `pivnica` "
                            . "WHERE `stavka_narudzbe`.`narudzba` = `narudzba`.`narudzba_id` AND `narudzba`.`pivnica` = `pivnica`.`pivnica_id` "
                            . "AND `narudzba`.`datum` BETWEEN '$formatiraniDatumOd' AND '$formatiraniDatumDo' GROUP BY `pivnica`.`pivnica_id`";

                    $rezultat = $veza->selectDB($sql);
                    
                    while ($row = mysqli_fetch_array($rezultat)) {
                        ?>
                        <tr>
                            <td><?php echo $row[0] ?></td>
                            <td><?php echo $row[1] ?></td>
                            <td><?php echo "$formatiraniDatumOd" ?></td>
                            <td><?php echo "$formatiraniDatumDo" ?></td>
                        </tr>

                        <?php
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


