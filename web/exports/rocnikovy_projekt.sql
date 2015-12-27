-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vygenerováno: Ned 27. pro 2015, 23:26
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `depo`
--

INSERT INTO `depo` (`id`, `img_url`, `nazev`, `GPS`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `telefon`, `kapacita`) VALUES
(0, '20151219_173643.jpg', 'Depo1', '11333', 'njasndkj', 'dajsnkjak', 21, 'andksjd', 'jdnakdsnk', 12),
(1, '20151219_230358.jpg', 'depo', '11333', 'njasndkj', 'dajsnkjak', 21, 'andksjd', 'jdnakdsnk', 12),
(2, '20151219_173449.jpg', 'DEPO Litomysl', '', '', '', 51, '', '608610174', 66),
(3, '20151219_222744.jpg', 'NoveDpo', '', '', '', 0, '', '', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `kontrola`
--

CREATE TABLE IF NOT EXISTS `kontrola` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provedl` int(11) DEFAULT NULL,
  `vlak` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `datum_expirace` date DEFAULT NULL,
  `kontrola_od` date DEFAULT NULL,
  `kontrola_do` date DEFAULT NULL,
  `vysledek` varchar(1000) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Kontrola_id` (`id`),
  KEY `vlak` (`vlak`),
  KEY `provedl` (`provedl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

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
(1, 'Basic'),
(2, 'Master'),
(3, 'Admin'),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `stanice`
--

INSERT INTO `stanice` (`id`, `GPS`, `nazev`, `mesto`, `adresa`, `stat`) VALUES
(2, '', 'Brno', 'Brno', '', ''),
(3, '49.953770, 16.158764', 'VysokÃ© MÃ½to, mÄ›sto', 'VysokÃ© MÃ½to', 'Plk. B. Kouhouta 804', 'ÄŒeskÃ¡ Republika'),
(5, '8888, 9999', 'Praha hlavni nadrazi', 'Praha', 'Podebradska 69', 'Ceska Republika');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `trasa`
--

INSERT INTO `trasa` (`id`, `nazev_trasy`, `stanice1`, `stanice2`, `delka`, `vyluka`, `disabled`) VALUES
(2, 'Mejto -> Brno', 3, 2, 156, 2, 0),
(3, 'Brno -> Mejto', 2, 3, 0, 6, 0),
(4, 'Praha -> Brno', 5, 2, 203, 1, 1),
(5, 'Trasa', 5, 2, 99, 6, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `trat`
--

CREATE TABLE IF NOT EXISTS `trat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ohodnoceni` float(5,2) DEFAULT NULL,
  `vychozi_stanice` int(11) NOT NULL,
  `cilova_stanice` int(11) NOT NULL,
  `aktivni` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Trat_id` (`id`),
  KEY `vychozi_stanice` (`vychozi_stanice`),
  KEY `cilova_stanice` (`cilova_stanice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

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
  `km_probeh_po` varchar(8) COLLATE utf8_czech_ci DEFAULT NULL,
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

INSERT INTO `vlak` (`cislo_zkv`, `img_url`, `rada`, `sokv`, `UIC_OLD`, `datum_preznaceni`, `m_stav`, `flag_eko`, `elektromer`, `spotreba_nafty`, `ele_ohrev`, `vkv`, `vz`, `km_probeh_po`, `vmax`, `pocet_naprav`, `delka`, `hmotnost`, `brvaha_g`, `brvaha_p`, `depo`) VALUES
('', '20151219_222215.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 0),
('43843', '20151123_151616.jpg', '', '', '', '2015-11-11', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 1),
('4567890', '20151125_094646.jpg', '666', 'hbhbjbhjb', 'hhbhjbjhbhj', '2015-11-05', 'hdiudhai', 1, 21312, 12321, 1231321, '999', 'bagr', 'jknjkand', 'dnajdskan', 21123, 122.00, 1212, 22.00, 122.00, 1),
('666', '20151222_113632.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 1),
('666699994d5as4', '20151219_222706.png', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 0),
('77', '20151123_151659.png', '', '', '', '0000-00-00', 'bagr', 0, 0, 0, 0, '', '666', '', '', 0, 0.00, 0, 0.00, 0.00, 0),
('7777', '20151123_151527.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 1),
('999vlacek', '20151219_223016.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `vlakjede`
--

CREATE TABLE IF NOT EXISTS `vlakjede` (
  `vlak` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `trasa` int(11) NOT NULL,
  `pozice` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  KEY `trasa` (`trasa`),
  KEY `vlak` (`vlak`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=671 ;

--
-- Vypisuji data pro tabulku `zamestnanec`
--

INSERT INTO `zamestnanec` (`id`, `img_url`, `druh_pomeru`, `email`, `jmeno`, `prijmeni`, `telefon`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `depo`, `role`, `smlouva_od`, `login`, `password`, `smlouva_do`) VALUES
(667, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 69, NULL, 'admin', 'lamer', NULL),
(668, '20151219_222354.jpg', 'Picus', '', '', '', '', '', '', 0, '', 0, 1, '0000-00-00', '', 'bagr', '2015-12-16'),
(669, '20151219_222649.jpg', 'Dement', '', 'picus', '', '', '', '', 0, '', 0, 1, '0000-00-00', 'nejsem_pica', '123456', '0000-00-00'),
(670, '20151222_113744.jpg', '', '', '', '', '', '', '', 0, '', 2, 3, '0000-00-00', '', '', '0000-00-00');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `kontrola`
--
ALTER TABLE `kontrola`
  ADD CONSTRAINT `FK_Kontrola_Vlak` FOREIGN KEY (`vlak`) REFERENCES `vlak` (`cislo_zkv`),
  ADD CONSTRAINT `FK_Kontrola_Zamestnanec` FOREIGN KEY (`provedl`) REFERENCES `zamestnanec` (`id`);

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
-- Omezení pro tabulku `trat`
--
ALTER TABLE `trat`
  ADD CONSTRAINT `FK_Trat_Stanice_02` FOREIGN KEY (`vychozi_stanice`) REFERENCES `stanice` (`id`),
  ADD CONSTRAINT `FK_Trat_Stanice_03` FOREIGN KEY (`cilova_stanice`) REFERENCES `stanice` (`id`);

--
-- Omezení pro tabulku `vlak`
--
ALTER TABLE `vlak`
  ADD CONSTRAINT `FK_Vlak_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Omezení pro tabulku `vlakjede`
--
ALTER TABLE `vlakjede`
  ADD CONSTRAINT `FK_VlakJede_Trasa` FOREIGN KEY (`trasa`) REFERENCES `trasa` (`id`),
  ADD CONSTRAINT `FK_VlakJede_Vlak` FOREIGN KEY (`vlak`) REFERENCES `vlak` (`cislo_zkv`);

--
-- Omezení pro tabulku `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD CONSTRAINT `FK_Zamestnanec_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`),
  ADD CONSTRAINT `FK_Zamestnanec_Role` FOREIGN KEY (`role`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
