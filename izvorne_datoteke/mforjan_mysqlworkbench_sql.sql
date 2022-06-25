-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema WebDiP2021x024
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema WebDiP2021x024
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `WebDiP2021x024` DEFAULT CHARACTER SET utf8 ;
USE `WebDiP2021x024` ;

-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`tip_korisnika`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`tip_korisnika` (
  `tip_korisnika_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`tip_korisnika_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`korisnik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`korisnik` (
  `korisnik_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(45) NULL,
  `prezime` VARCHAR(45) NULL,
  `datum_rodenja` DATE NULL,
  `email` VARCHAR(50) NULL,
  `korisnicko_ime` VARCHAR(45) NULL,
  `lozinka` VARCHAR(45) NULL,
  `lozinka_sha256` CHAR(64) NULL,
  `broj_neuspjesne_prijave` TINYINT NULL,
  `status` TINYINT NULL,
  `aktivacijski_kod` VARCHAR(45) NULL,
  `validiran` TINYINT NULL,
  `vrijeme_registracija` TIMESTAMP NULL,
  `tip_korisnika` INT NOT NULL,
  PRIMARY KEY (`korisnik_id`),
  INDEX `fk_korisnik_tip_korisnika_idx` (`tip_korisnika` ASC),
  CONSTRAINT `fk_korisnik_tip_korisnika`
    FOREIGN KEY (`tip_korisnika`)
    REFERENCES `WebDiP2021x024`.`tip_korisnika` (`tip_korisnika_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`cjenik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`cjenik` (
  `cjenik_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(30) NOT NULL,
  `napomena` VARCHAR(70) NULL,
  PRIMARY KEY (`cjenik_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`pivnica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`pivnica` (
  `pivnica_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(60) NOT NULL,
  `adresa` VARCHAR(200) NOT NULL,
  `broj_telefona` VARCHAR(25) NOT NULL,
  `moderator` INT NOT NULL,
  `cjenik` INT NOT NULL,
  PRIMARY KEY (`pivnica_id`),
  INDEX `fk_pivnica_korisnik_idx` (`moderator` ASC),
  UNIQUE INDEX `cjenik_UNIQUE` (`cjenik` ASC),
  CONSTRAINT `fk_pivnica_korisnik`
    FOREIGN KEY (`moderator`)
    REFERENCES `WebDiP2021x024`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pivnica_cjenik`
    FOREIGN KEY (`cjenik`)
    REFERENCES `WebDiP2021x024`.`cjenik` (`cjenik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`zemlja_podrijetla`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`zemlja_podrijetla` (
  `zemlja_podrijetla_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(50) NOT NULL,
  `glavni_grad` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`zemlja_podrijetla_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`vrsta_piva`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`vrsta_piva` (
  `vrsta_piva_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(25) NOT NULL,
  PRIMARY KEY (`vrsta_piva_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`pivo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`pivo` (
  `pivo_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(50) NOT NULL,
  `opis` VARCHAR(100) NOT NULL,
  `rok_trajanja` DATE NOT NULL,
  `slika` VARCHAR(200) NOT NULL,
  `zemlja_podrijetla` INT NOT NULL,
  `vrsta` INT NOT NULL,
  `cijena` INT NOT NULL,
  `volumen` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`pivo_id`),
  INDEX `fk_pivo_zemlja_podrijetla_idx` (`zemlja_podrijetla` ASC),
  INDEX `fk_pivo_vrsta_piva_idx` (`vrsta` ASC),
  CONSTRAINT `fk_pivo_zemlja_podrijetla`
    FOREIGN KEY (`zemlja_podrijetla`)
    REFERENCES `WebDiP2021x024`.`zemlja_podrijetla` (`zemlja_podrijetla_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pivo_vrsta_piva`
    FOREIGN KEY (`vrsta`)
    REFERENCES `WebDiP2021x024`.`vrsta_piva` (`vrsta_piva_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`stavka_cjenika`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`stavka_cjenika` (
  `cjenik` INT NOT NULL,
  `pivo` INT NOT NULL,
  `prosjecna_ocjena` FLOAT NOT NULL,
  PRIMARY KEY (`cjenik`, `pivo`),
  INDEX `fk_stavka_cjenika_pivo_pivo_id_idx` (`pivo` ASC),
  CONSTRAINT `fk_stavka_cjenika_cjenik_cjenik_id`
    FOREIGN KEY (`cjenik`)
    REFERENCES `WebDiP2021x024`.`cjenik` (`cjenik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_stavka_cjenika_pivo_pivo_id`
    FOREIGN KEY (`pivo`)
    REFERENCES `WebDiP2021x024`.`pivo` (`pivo_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`narudzba`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`narudzba` (
  `narudzba_id` INT NOT NULL AUTO_INCREMENT,
  `datum` DATETIME NOT NULL,
  `placeno` TINYINT NOT NULL,
  `korisnik` INT NOT NULL,
  `pivnica` INT NOT NULL,
  PRIMARY KEY (`narudzba_id`),
  INDEX `fk_narudzba_korisnik_idx` (`korisnik` ASC),
  INDEX `fk_narudzba_pivnica_idx` (`pivnica` ASC),
  CONSTRAINT `fk_narudzba_korisnik`
    FOREIGN KEY (`korisnik`)
    REFERENCES `WebDiP2021x024`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_narudzba_pivnica`
    FOREIGN KEY (`pivnica`)
    REFERENCES `WebDiP2021x024`.`pivnica` (`pivnica_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`stavka_narudzbe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`stavka_narudzbe` (
  `narudzba` INT NOT NULL,
  `pivo` INT NOT NULL,
  `kolicina` INT NOT NULL,
  PRIMARY KEY (`narudzba`, `pivo`),
  INDEX `fk_stavka_narudzbe_pivo_idx` (`pivo` ASC),
  CONSTRAINT `fk_stavka_narudzbe_narudzba`
    FOREIGN KEY (`narudzba`)
    REFERENCES `WebDiP2021x024`.`narudzba` (`narudzba_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_stavka_narudzbe_pivo`
    FOREIGN KEY (`pivo`)
    REFERENCES `WebDiP2021x024`.`pivo` (`pivo_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`racun`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`racun` (
  `racun_id` INT NOT NULL AUTO_INCREMENT,
  `ukupan_iznos` INT NOT NULL,
  `placeni_iznos` INT NOT NULL,
  `narudzba` INT NOT NULL,
  PRIMARY KEY (`racun_id`),
  UNIQUE INDEX `narudzba_UNIQUE` (`narudzba` ASC),
  CONSTRAINT `fk_racun_narudzba_narudzba_id`
    FOREIGN KEY (`narudzba`)
    REFERENCES `WebDiP2021x024`.`narudzba` (`narudzba_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`tip_radnje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`tip_radnje` (
  `tip_radnje_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`tip_radnje_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`dnevnika_rada`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`dnevnika_rada` (
  `dnevnik_rada_id` INT NOT NULL AUTO_INCREMENT,
  `korisnik` INT NOT NULL,
  `tip_radnje` INT NOT NULL,
  `radnja` VARCHAR(200) NULL,
  `upit` VARCHAR(1000) NULL,
  `datum_vrijeme` DATETIME NOT NULL,
  PRIMARY KEY (`dnevnik_rada_id`, `tip_radnje`, `korisnik`),
  INDEX `fk_dnevnika_rada_korisnik_idx` (`korisnik` ASC),
  INDEX `fk_dnevnika_rada_tip_radnje_idx` (`tip_radnje` ASC),
  CONSTRAINT `fk_dnevnika_rada_korisnik`
    FOREIGN KEY (`korisnik`)
    REFERENCES `WebDiP2021x024`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_dnevnika_rada_tip_radnje`
    FOREIGN KEY (`tip_radnje`)
    REFERENCES `WebDiP2021x024`.`tip_radnje` (`tip_radnje_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebDiP2021x024`.`ocjena_pive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebDiP2021x024`.`ocjena_pive` (
  `korisnik` INT NOT NULL,
  `pivo` INT NOT NULL,
  `ocjena` INT NOT NULL,
  PRIMARY KEY (`korisnik`, `pivo`),
  INDEX `fk_ocjena_pive_pivo_pivo_id_idx` (`pivo` ASC),
  CONSTRAINT `fk_ocjena_pive_korisnik_korisnik_id`
    FOREIGN KEY (`korisnik`)
    REFERENCES `WebDiP2021x024`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ocjena_pive_pivo_pivo_id`
    FOREIGN KEY (`pivo`)
    REFERENCES `WebDiP2021x024`.`pivo` (`pivo_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
