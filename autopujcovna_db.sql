-- MySQL Script generated by MySQL Workbench
-- Thu Nov 27 15:34:55 2014
-- Model: New Model    Version: 1.0
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`auta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`auta` (
  `id_auta` INT NOT NULL AUTO_INCREMENT,
  `znacka` VARCHAR(20) NOT NULL,
  `model` VARCHAR(20) NOT NULL,
  `typ` VARCHAR(20) NOT NULL,
  `spz` VARCHAR(8) NOT NULL,
  `barva` VARCHAR(20) NULL,
  `stav_tachometru` VARCHAR(6) NOT NULL,
  `pujceno` VARCHAR(3) NOT NULL DEFAULT 'NE',
  PRIMARY KEY (`id_auta`))
ENGINE = InnoDB
PACK_KEYS = Default;


-- -----------------------------------------------------
-- Table `mydb`.`zakaznik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`zakaznik` (
  `id_zakaznika` INT NULL AUTO_INCREMENT,
  `jmeno` VARCHAR(20) NULL,
  `prijmeni` VARCHAR(30) NOT NULL,
  `bydliste` VARCHAR(50) NOT NULL,
  `cislo_op` VARCHAR(11) NOT NULL,
  `login_name` VARCHAR(30) NOT NULL,
  `login_passw` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id_zakaznika`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`pristupova_prava`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`pristupova_prava` (
  `id_pristupova_prava` INT NOT NULL AUTO_INCREMENT,
  `pravo` VARCHAR(20) NOT NULL,
  `id_zakaznik` INT NULL,
  PRIMARY KEY (`id_pristupova_prava`),
  INDEX `fk_pristupova_prava_zakaznik1_idx` (`id_zakaznik` ASC),
  CONSTRAINT `fk_pristupova_prava_zakaznik1`
    FOREIGN KEY (`id_zakaznik`)
    REFERENCES `mydb`.`zakaznik` (`id_zakaznika`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`servis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`servis` (
  `id_servis` INT NULL AUTO_INCREMENT,
  `datum_kontroly` DATE NULL,
  `stav_komentar` VARCHAR(200) NULL,
  PRIMARY KEY (`id_servis`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`auta_has_servis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`auta_has_servis` (
  `id_auta` INT NOT NULL,
  `id_servis` INT NOT NULL,
  PRIMARY KEY (`id_servis`, `id_auta`),
  INDEX `fk_auta_has_servis_servis1_idx` (`id_servis` ASC),
  INDEX `fk_auta_has_servis_auta1_idx` (`id_auta` ASC),
  CONSTRAINT `fk_auta_has_servis_auta1`
    FOREIGN KEY (`id_auta`)
    REFERENCES `mydb`.`auta` (`id_auta`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_auta_has_servis_servis1`
    FOREIGN KEY (`id_servis`)
    REFERENCES `mydb`.`servis` (`id_servis`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`auta_has_zakaznik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`auta_has_zakaznik` (
  `id_auta` INT NOT NULL,
  `id_zakaznika` INT NOT NULL,
  `datum_zapujceni` DATE NULL,
  PRIMARY KEY (`id_auta`, `id_zakaznika`),
  INDEX `fk_auta_has_zakaznik_zakaznik1_idx` (`id_zakaznika` ASC),
  INDEX `fk_auta_has_zakaznik_auta1_idx` (`id_auta` ASC),
  CONSTRAINT `fk_auta_has_zakaznik_auta1`
    FOREIGN KEY (`id_auta`)
    REFERENCES `mydb`.`auta` (`id_auta`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_auta_has_zakaznik_zakaznik1`
    FOREIGN KEY (`id_zakaznika`)
    REFERENCES `mydb`.`zakaznik` (`id_zakaznika`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


--
-- Vypisuji data pro tabulku `auta`
--

INSERT INTO `auta` (`id_auta`, `znacka`, `model`, `typ`, `spz`, `barva`, `stav_tachometru`, `pujceno`) VALUES
(13, 'Škoda', 'Octavia', 'osobní', '1P83329', 'černá', '301901', 'NE'),
(14, 'Ford', 'Focus', 'osobní', '6P16677', 'stříbrná', '254674', 'NE'),
(15, 'Seat', 'Toledo', 'osobní', '5H19012', 'žlutá', '88091', 'NE'),
(16, 'Škoda', 'Felicia', 'osobní', '1P11234', 'modrá', '134568', 'NE'),
(17, 'Fiat', 'Uno', 'osobní', '1P34567', 'zelená', '201223', 'NE'),
(18, 'VW', 'Polo', 'osobní', '1P23213', 'stříbrná', '88987', 'ANO'),
(19, 'Renault', 'Megane', 'osobní', '3K47654', 'bílá', '176345', 'NE'),
(20, 'BMW', '316i', 'osobní', '1H63490', 'modrá', '183421', 'ANO'),
(21, 'Renault', 'Laguna', 'osobní', '2S39872', 'zelená', '220348', 'NE'),
(22, 'Opel', 'Corsa', 'osobní', '1H63492', 'stříbrná', '212347', 'NE'),
(23, 'Hyundai', 'SantaFe', 'osobní', '1H63493', 'stříbrná', '82301', 'NE'),
(24, 'Volvo', 'V50', 'osobní', '1H54356', 'černá', '34256', 'NE'),
(25, 'Toyota', 'Corola Verso', 'osobní', '2S11234', 'modrá', '144346', 'NE'),
(26, 'BMW', '116i', 'osobní', '1H56743', 'černá', '134567', 'ANO');


INSERT INTO `servis` (`id_servis`, `datum_kontroly`, `stav_komentar`) VALUES
(128, '2014-10-02', 'Lanovod ruční brzdy'),
(129, '2014-10-03', 'Brzdové destičky + kotouče'),
(130, '2014-09-11', 'Výměna oleje + olejový filtr'),
(131, '2014-11-01', 'Výměna pneu'),
(132, '2014-11-01', 'Výměna pneu'),
(133, '2014-10-11', 'Turbodmychadlo'),
(134, '2014-11-01', 'Výměna pneu'),
(135, '2014-10-02', 'Vzduchový filtr');

INSERT INTO `auta_has_servis` (`id_auta`, `id_servis`) VALUES
(13, 128),
(13, 129),
(14, 130),
(14, 131),
(16, 132),
(20, 133),
(20, 134),
(24, 135);


INSERT INTO `zakaznik` (`id_zakaznika`, `jmeno`, `prijmeni`, `bydliste`, `cislo_op`, `login_name`, `login_passw`) VALUES
(221, 'Adam', 'Modrý', 'Plánice 12, 341 01 Horažďovice', '101987231', 'name1', 'pass1'),
(222, 'Jan', 'Bílý', 'Na Dlouhých 456, 306 23 Plzeň', '101987232', 'name2', 'pass2'),
(223, 'Jiří', 'Homolka', 'Pačejov 67, 341 01 Horažďovice', '101987233', 'root', 'root'),
(224, 'Aneta', 'Červená', 'Jablonkého 80, 306 10 Plzeň', '101987234', 'name3', 'pass3'),
(225, 'Lenka', 'Černá', 'Nehodív 23, 341 01 Horažďovice', '101987235', 'name4', 'pass4'),
(226, 'Jiří', 'Homolka', 'Olšany, Horažďovice', '101987236', 'Administrator', 'admin'),
(227, 'Jan', 'Červenka', 'Plzeň 45/5, 306 01 Plzeň', '101987212', 'cervenka', 'cervenka'),
(228, 'Lukáš', 'Pěnkava', 'Olšany 12, 341 01 Horažďovice', '101987435', 'penkava', 'penkava'),
(229, 'Ondřej', 'Malý', 'Praha 123/4, 110 01 Praha 10', '101987295', 'malyo', 'malyo'),
(230, 'Jiří', 'Doubrava', 'Plánice 456, 341 01 Horažďovice', '101987111', 'jhomolka', 'jhomolka');

INSERT INTO `auta_has_zakaznik` (`id_auta`, `id_zakaznika`, `datum_zapujceni`) VALUES
(14, 221, '2014-11-26'),
(13, 223, '2014-02-14'),
(14, 223, '2014-11-26'),
(14, 225, '2014-02-15'),
(18, 230, '2014-11-27'),
(20, 230, '2014-11-27'),
(26, 230, '2014-11-27');

INSERT INTO `pristupova_prava` (`id_pristupova_prava`, `pravo`, `id_zakaznik`) VALUES
(301, 'host', 223),
(302, 'host', 221),
(303, 'host', 222),
(304, 'host', 226),
(305, 'host', 227),
(306, 'host', 228),
(307, 'host', 229),
(308, 'host', 230),
(309, 'host', 224),
(310, 'host', 225);