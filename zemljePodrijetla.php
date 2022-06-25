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
    $nazivZemlje = $_POST['nazivZemlje'];
    $glavniGrad = $_POST['glavniGrad'];

    if ($nazivZemlje == "" || $glavniGrad == "") {
        $poruka = "Neispravno uneseni podaci!";
        $greska = true;
    }

    if (!$greska) {
        $veza = new Baza();
        $veza->spojiDB();
        $sql = "INSERT INTO zemlja_podrijetla (naziv, glavni_grad) VALUES ('$nazivZemlje', '$glavniGrad')";
        $rezultat = $veza->updateDB($sql);
        $veza->zatvoriDB();
    }
}

//pritisnut id zemlje podrijetla
if (isset($_POST['id'])) {
    $azurirajIdGumb = $_POST['id'];
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT * FROM zemlja_podrijetla WHERE zemlja_podrijetla_id='{$azurirajIdGumb}'";
    $rezultat = $veza->selectDB($sql);
    $red = mysqli_fetch_array($rezultat);
    
    $azurirajId = $red['zemlja_podrijetla_id'];
    $azurirajNaziv = $red['naziv'];
    $azurirajGrad = $red['glavni_grad'];
    
    $veza->zatvoriDB();
}

if (isset($_POST['azurirajGumb'])) {
    $veza = new Baza();
    $veza->spojiDB();
    
    $azurirajId = $_POST['idZemljeAzuriraj'];
    $azurirajNaziv = $_POST['nazivZemljeAzuriraj'];
    $azurirajGrad = $_POST['glavniGradAzuriraj'];

    $sql = "UPDATE zemlja_podrijetla SET naziv = '{$azurirajNaziv}', glavni_grad = '{$azurirajGrad}' WHERE zemlja_podrijetla_id = {$azurirajId}";
    $rezultat = $veza->updateDB($sql);
    
    header("Location: zemljePodrijetla.php");
    
    $veza->zatvoriDB();
}
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <title>Zemlje podrijetla</title>
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
                ZEMLJE PODRIJETLA
            </h1>

            <?php
            echo "<p style = 'color:darkblue; font-weight:bolder; font-size:25px; text-align:center;'>$poruka</p>";
            ?>

        </header>

        <section>

            <form novalidate class="forma" id="formZemljaPodrijetla" method="post" name="formZemljaPodrijetla" action="zemljePodrijetla.php">
                <label for="nazivZemlje">Naziv zemlje podrijetla: </label>
                <input class="nazivZemljeTextbox" type="text" name="nazivZemlje" size="30" maxlength="30" placeholder="Naziv zemlje podrijetla" autofocus="autofocus" required="required"><br>
                <label for="glavniGrad">Glavni grad: </label>
                <input class="glavniGradTextbox" type="text" name="glavniGrad" size="30" maxlength="30" placeholder="Glavni grad"autofocus="autofocus" required="required"><br>
                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formZemljaPodrijetla" value="Unesi" name="unesiGumb">
            </div>
            
            <form novalidate class="forma" id="formZemljaPodrijetlaAzuriranje" method="post" name="formZemljaPodrijetlaAzuriranje" action="zemljePodrijetla.php">
                <label for="idZemljeAzuriraj">Id zemlje podrijetla: </label>
                <input <?php
                global $azurirajId;
                if ($azurirajId != null){
                    echo "value='{$azurirajId}'";
                }
                ?> class="idZemljeTextbox" type="text" name="idZemljeAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                
                <label for="nazivZemljeAzuriraj">Naziv zemlje podrijetla: </label>
                <input <?php
                global $azurirajNaziv;
                if ($azurirajNaziv != null){
                    echo "value='{$azurirajNaziv}'";
                }
                ?> class="nazivZemljeTextbox" type="text" name="nazivZemljeAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                <label for="glavniGradAzuriraj">Glavni grad: </label>
                
                <input <?php
                global $azurirajGrad;
                if ($azurirajGrad != null){
                    echo "value='{$azurirajGrad}'";
                }
                ?> class="glavniGradTextbox" type="text" name="glavniGradAzuriraj" size="30" maxlength="30" autofocus="autofocus" required="required"><br>
                <br>
            </form>
            <div style="text-align: center">
                <input type="submit" class="submit" form="formZemljaPodrijetlaAzuriranje" value="Ažuriraj" name="azurirajGumb">
            </div>

            <form name="zemljePodrijetla" action="" method="post">
                <table class="display" id="tablica">
                    <caption style="font-size: 22px; font-weight: bolder">ZEMLJE PODRIJETLA</caption>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naziv</th>
                            <th>Glavni grad</th>
                        </tr>
                    </thead>
                    <tbody> 

                        <?php
                        $veza = new Baza();
                        $veza->spojiDB();

                        $sql = "SELECT * FROM zemlja_podrijetla";

                        $rezultat = $veza->selectDB($sql);

                        while ($row = mysqli_fetch_array($rezultat)) {
                            ?>
                            <tr>
                                <td><input id="id" name="id" type="submit" value="<?php echo $row['zemlja_podrijetla_id'] ?>"> AŽURIRAJ</td>
                                <td><?php echo $row[1] ?></td>
                                <td><?php echo $row[2] ?></td>
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



