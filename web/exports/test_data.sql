-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vygenerováno: Čtv 07. led 2016, 14:27
-- Verze serveru: 5.6.11
-- Verze PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `rocnikovy_projekt`
--
CREATE DATABASE IF NOT EXISTS `rocnikovy_projekt` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `rocnikovy_projekt`;

-- --------------------------------------------------------

--
-- Struktura tabulky `depo`
--

CREATE TABLE IF NOT EXISTS `depo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_url` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `nazev` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `GPS` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `mesto` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `adresa` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `cislo_popisne` int(11) DEFAULT NULL,
  `stat` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  `kapacita` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Depo_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=10 ;

--
-- Vypisuji data pro tabulku `depo`
--

INSERT INTO `depo` (`id`, `img_url`, `nazev`, `GPS`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `telefon`, `kapacita`) VALUES
(5, '20160107_132212.jpg', 'Depo Praha', '', 'Praha', '', 0, '', '', 32),
(6, '20160107_132303.jpg', 'Depo Brno', '', 'Brno', '', 0, 'ČR', '', 20),
(7, '20160107_132448.jpg', 'Depo Ostrava', '', 'Ostrava', '', 0, 'ČR', '', 12),
(8, '20160107_132643.jpg', 'na_smazani', '', 'irelevantni', '', 0, '', '', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `kontrola`
--

CREATE TABLE IF NOT EXISTS `kontrola` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `cislo_zkv` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `datum_expirace` date DEFAULT NULL,
  `kontrola_od` date DEFAULT NULL,
  `kontrola_do` date DEFAULT NULL,
  `vysledek` varchar(1000) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Kontrola_id` (`id`),
  KEY `vlak` (`cislo_zkv`),
  KEY `provedl` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=18 ;

--
-- Vypisuji data pro tabulku `kontrola`
--

INSERT INTO `kontrola` (`id`, `id_user`, `cislo_zkv`, `datum_expirace`, `kontrola_od`, `kontrola_do`, `vysledek`) VALUES
(12, 677, '111111111111', '2016-01-31', '2015-10-04', NULL, 'Kontrola probehla uspesne'),
(13, 676, '222222222222', '2016-01-29', '2015-01-03', NULL, 'Kontrola bez chyb'),
(14, 677, '111111111111', '2014-01-31', '2015-10-04', NULL, 'Kontrola probehla uspesne'),
(15, 676, '111111111111', '2013-01-31', '2014-10-04', NULL, 'Kontrola probehla uspesne'),
(16, 676, '333333333333', '2016-01-31', '2015-04-06', NULL, 'Kontrola na smazani'),
(17, 676, '444444444444', '2016-11-17', '2014-07-08', NULL, 'Toto je testovací kontrola, která se korektně zobrazuje.');

-- --------------------------------------------------------

--
-- Struktura tabulky `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Role_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=70 ;

--
-- Vypisuji data pro tabulku `role`
--

INSERT INTO `role` (`id`, `nazev`) VALUES
(1, 'Dispečer'),
(2, 'Personální evident'),
(3, 'Strojvedoucí'),
(4, 'Technik depa'),
(5, 'Admin'),
(69, 'Superadmin');

-- --------------------------------------------------------

--
-- Struktura tabulky `sklad`
--

CREATE TABLE IF NOT EXISTS `sklad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kapacita` int(11) DEFAULT NULL,
  `depo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Sklad_id` (`id`),
  KEY `depo` (`depo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `stanice`
--

CREATE TABLE IF NOT EXISTS `stanice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `GPS` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `nazev` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `mesto` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `adresa` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `stat` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Stanice_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `stanice`
--

INSERT INTO `stanice` (`id`, `GPS`, `nazev`, `mesto`, `adresa`, `stat`) VALUES
(7, '', 'Vysoké Mýto', 'Vysoké Mýto, město', '', 'Česká republika'),
(8, '', 'Praha', 'Praha hlavní nadraží', '', 'Česká republika'),
(9, '', 'Brno', 'Brno hlavní nadraží', '', 'Česká republika'),
(10, '', 'Ostrava', 'Ostrava', '', 'Česká republika');

-- --------------------------------------------------------

--
-- Struktura tabulky `stavy`
--

CREATE TABLE IF NOT EXISTS `stavy` (
  `id_stav` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id_stav`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=7 ;

--
-- Vypisuji data pro tabulku `stavy`
--

INSERT INTO `stavy` (`id_stav`, `nazev`) VALUES
(1, 'Vytvořen'),
(2, 'Připraven'),
(3, 'Aktivní'),
(4, 'Pozastaven'),
(5, 'Zrušen'),
(6, 'Ukončen');

-- --------------------------------------------------------

--
-- Struktura tabulky `trasa`
--

CREATE TABLE IF NOT EXISTS `trasa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_trasy` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `stanice1` int(11) NOT NULL,
  `stanice2` int(11) NOT NULL,
  `delka` int(11) NOT NULL,
  `vyluka` tinyint(1) NOT NULL,
  `disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Trasa_id` (`id`),
  KEY `staniceA` (`stanice1`),
  KEY `staniceB` (`stanice2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `trasa`
--

INSERT INTO `trasa` (`id`, `nazev_trasy`, `stanice1`, `stanice2`, `delka`, `vyluka`, `disabled`) VALUES
(8, 'Ostrava->Brno', 10, 9, 120, 1, 0),
(9, 'Mýto->Praha', 7, 8, 203, 6, 0),
(10, 'Praha->Brno', 8, 9, 183, 6, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `ukony`
--

CREATE TABLE IF NOT EXISTS `ukony` (
  `id_ukon` int(11) NOT NULL AUTO_INCREMENT,
  `cislo_zkv` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_trasa` int(11) NOT NULL,
  `pocet_vagonu` int(10) NOT NULL,
  `cas` int(10) NOT NULL,
  `stav` int(5) NOT NULL DEFAULT '1',
  `finished` date NOT NULL,
  PRIMARY KEY (`id_ukon`),
  KEY `cislo_zkv` (`cislo_zkv`),
  KEY `id_user` (`id_user`),
  KEY `id_trasa` (`id_trasa`),
  KEY `stav` (`stav`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=46 ;

--
-- Vypisuji data pro tabulku `ukony`
--

INSERT INTO `ukony` (`id_ukon`, `cislo_zkv`, `id_user`, `id_trasa`, `pocet_vagonu`, `cas`, `stav`, `finished`) VALUES
(40, '111111111111', 678, 8, 10, 150, 6, '2014-01-07'),
(41, '111111111111', 678, 9, 10, 200, 6, '2014-01-07'),
(42, '222222222222', 679, 9, 15, 236, 6, '2014-01-07'),
(43, '333333333333', 680, 10, 10, 250, 6, '2016-01-07'),
(44, '111111111111', 679, 9, 13, 233, 6, '2016-01-07'),
(45, '111111111111', 678, 8, 10, 115, 5, '2014-01-07');

-- --------------------------------------------------------

--
-- Struktura tabulky `vlak`
--

CREATE TABLE IF NOT EXISTS `vlak` (
  `cislo_zkv` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `img_url` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `rada` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `sokv` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `UIC_OLD` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `datum_preznaceni` date DEFAULT NULL,
  `m_stav` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  `flag_eko` tinyint(1) DEFAULT NULL,
  `elektromer` int(11) DEFAULT NULL,
  `spotreba_nafty` int(11) DEFAULT NULL,
  `ele_ohrev` int(11) DEFAULT NULL,
  `vkv` varchar(256) COLLATE utf8_czech_ci DEFAULT NULL,
  `vz` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `pocet_km` int(10) DEFAULT NULL,
  `vmax` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `pocet_naprav` int(11) DEFAULT NULL,
  `delka` float(5,2) DEFAULT NULL,
  `hmotnost` int(11) DEFAULT NULL,
  `brvaha_g` float(5,2) DEFAULT NULL,
  `brvaha_p` float(5,2) DEFAULT NULL,
  `depo` int(11) NOT NULL,
  PRIMARY KEY (`cislo_zkv`),
  UNIQUE KEY `UQ_Vlak_cislo_zkv` (`cislo_zkv`),
  KEY `depo` (`depo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `vlak`
--

INSERT INTO `vlak` (`cislo_zkv`, `img_url`, `rada`, `sokv`, `UIC_OLD`, `datum_preznaceni`, `m_stav`, `flag_eko`, `elektromer`, `spotreba_nafty`, `ele_ohrev`, `vkv`, `vz`, `pocet_km`, `vmax`, `pocet_naprav`, `delka`, `hmotnost`, `brvaha_g`, `brvaha_p`, `depo`) VALUES
('111111111111', '20160107_130817.jpg', '708', 'Praha', NULL, '2016-01-01', NULL, NULL, 20, 100, NULL, NULL, NULL, 1810, '80', 8, 15.00, 0, 0.00, NULL, 5),
('222222222222', '20160107_141047.jpg', 'řada 749', 'Praha', NULL, '2016-01-13', NULL, NULL, 15, 140, NULL, NULL, NULL, 2203, '100', 14, 22.00, 63, 2.00, NULL, 5),
('333333333333', '20160107_141158.jpg', 'T 426.0 (715)', 'Praha', NULL, '2016-01-04', NULL, NULL, 15, 125, NULL, NULL, NULL, 1183, '90', 16, 13.00, 103, 2.00, NULL, 5),
('444444444444', '20160107_141319.jpg', ' T 478.1 (751)', '', NULL, '2016-01-03', NULL, NULL, 0, 0, NULL, NULL, NULL, 3000, '100', 12, 14.00, 0, 0.00, NULL, 5);

-- --------------------------------------------------------

--
-- Struktura tabulky `vyluky`
--

CREATE TABLE IF NOT EXISTS `vyluky` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `dopad` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=7 ;

--
-- Vypisuji data pro tabulku `vyluky`
--

INSERT INTO `vyluky` (`id`, `nazev`, `dopad`) VALUES
(1, 'vyluka do 30m', 1),
(2, 'vyluka do 1h', 2),
(3, 'vyluka do 2h', 3),
(4, 'vyluka do 4h', 4),
(5, 'Trasa neprujezdna', 100),
(6, 'bez vyluky', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `zamestnanec`
--

CREATE TABLE IF NOT EXISTS `zamestnanec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_url` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `druh_pomeru` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `jmeno` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `prijmeni` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `mesto` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `adresa` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `cislo_popisne` int(11) DEFAULT NULL,
  `stat` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `depo` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `smlouva_od` date DEFAULT NULL,
  `login` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `password` varchar(60) COLLATE utf8_czech_ci DEFAULT NULL,
  `smlouva_do` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Zamestnanec_id` (`id`),
  KEY `role` (`role`),
  KEY `depo` (`depo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=682 ;

--
-- Vypisuji data pro tabulku `zamestnanec`
--

INSERT INTO `zamestnanec` (`id`, `img_url`, `druh_pomeru`, `email`, `jmeno`, `prijmeni`, `telefon`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `depo`, `role`, `smlouva_od`, `login`, `password`, `smlouva_do`) VALUES
(667, '', NULL, NULL, 'admin', 'pico', NULL, NULL, NULL, NULL, NULL, 5, 69, NULL, 'admin', 'lamer', NULL),
(676, '20160107_132845.jpg', '', 'email', 'Technik1', '', '', '', '', 0, '', 5, 4, '2016-01-11', 'technik1', '1234', '2016-01-20'),
(677, '20160107_132942.jpg', '', 'mail', 'Technik2', '', '', '', '', 0, '', 5, 4, '0000-00-00', '', '', '2016-01-27'),
(678, '20160107_133110.jpg', '', 'strojvedouci1', 'strojvedouci1', '', '', '', '', 0, '', 5, 3, '0000-00-00', 'stroj1', '1234', '0000-00-00'),
(679, '20160107_133138.jpg', '', 'strojvedouci2', 'strojvedouci2', '', '', '', '', 0, '', 5, 3, '0000-00-00', '', '', '0000-00-00'),
(680, '20160107_133155.jpg', '', 'strojvedouci3', 'strojvedouci3', '', '', '', '', 0, '', 5, 3, '0000-00-00', '', '', '0000-00-00'),
(681, '20160107_133309.png', '', 'propusteny_zamestnanec', 'propusteny_zamestnanec', '', '', '', '', 0, '', 5, 2, '0000-00-00', '', '', '2016-01-05');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `kontrola`
--
ALTER TABLE `kontrola`
  ADD CONSTRAINT `FK_Kontrola_Vlak` FOREIGN KEY (`cislo_zkv`) REFERENCES `vlak` (`cislo_zkv`),
  ADD CONSTRAINT `FK_Kontrola_Zamestnanec` FOREIGN KEY (`id_user`) REFERENCES `zamestnanec` (`id`);

--
-- Omezení pro tabulku `sklad`
--
ALTER TABLE `sklad`
  ADD CONSTRAINT `FK_Sklad_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Omezení pro tabulku `trasa`
--
ALTER TABLE `trasa`
  ADD CONSTRAINT `trasa_ibfk_1` FOREIGN KEY (`stanice1`) REFERENCES `stanice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trasa_ibfk_2` FOREIGN KEY (`stanice2`) REFERENCES `stanice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `ukony`
--
ALTER TABLE `ukony`
  ADD CONSTRAINT `ukony_ibfk_1` FOREIGN KEY (`cislo_zkv`) REFERENCES `vlak` (`cislo_zkv`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ukony_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `zamestnanec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ukony_ibfk_3` FOREIGN KEY (`id_trasa`) REFERENCES `trasa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `vlak`
--
ALTER TABLE `vlak`
  ADD CONSTRAINT `FK_Vlak_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Omezení pro tabulku `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD CONSTRAINT `FK_Zamestnanec_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`),
  ADD CONSTRAINT `FK_Zamestnanec_Role` FOREIGN KEY (`role`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
