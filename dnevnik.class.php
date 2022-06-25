<?php

class Dnevnik {
    public function prijava($korisnickoIme) {
        $veza = new Baza();
        $veza->spojiDB();
        
        $radnja = "Prijava u sustav";
        $vrijemeRadnje = date("Y.m.d H:i:s");
        
        $sql2 = "SELECT * FROM korisnik WHERE korisnicko_ime = '$korisnickoIme'";
        $rezultat2 = $veza->selectDB($sql2);
        $red = mysqli_fetch_array($rezultat2);
        $korisnikId = $red['korisnik_id'];
        
        $sql = "INSERT INTO dnevnika_rada (korisnik, tip_radnje, radnja, datum_vrijeme) "
                . "VALUES ($korisnikId, 1, '$radnja', '$vrijemeRadnje')";
        
        $rezultat = $veza->updateDB($sql);
        
        $veza->zatvoriDB();
    }
    
    public function odjava($korime) {
        $veza = new Baza();
        $veza->spojiDB();
        
        $radnja = "Odjava iz sustava";
        $vrijemeRadnje = date("Y.m.d H:i:s");
        
        $sql2 = "SELECT * FROM korisnik WHERE korisnicko_ime = '$korime'";
        $rezultat2 = $veza->selectDB($sql2);
        $red = mysqli_fetch_array($rezultat2);
        $korisnikId = $red['korisnik_id'];
        
        $sql = "INSERT INTO dnevnika_rada (korisnik, tip_radnje, radnja, datum_vrijeme) "
                . "VALUES ($korisnikId, 2, '$radnja', '$vrijemeRadnje')";
        
        $rezultat = $veza->updateDB($sql);
        
        $veza->zatvoriDB();
    }
}
?>
