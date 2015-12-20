-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vygenerováno: Stř 25. lis 2015, 19:47
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `depo`
--

INSERT INTO `depo` (`id`, `nazev`, `GPS`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `telefon`, `kapacita`) VALUES
(0, 'depo', '11333', 'njasndkj', 'dajsnkjak', 21, 'andksjd', 'jdnakdsnk', 12),
(1, 'depo', '11333', 'njasndkj', 'dajsnkjak', 21, 'andksjd', 'jdnakdsnk', 12);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Role_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `role`
--

INSERT INTO `role` (`id`, `nazev`) VALUES
(1, 'Basic'),
(2, 'Master'),
(3, 'Admin');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `stanicetrasy`
--

CREATE TABLE IF NOT EXISTS `stanicetrasy` (
  `trasa` int(11) NOT NULL,
  `stanice` int(11) NOT NULL,
  `poradi` int(11) NOT NULL,
  KEY `trasa` (`trasa`),
  KEY `stanice` (`stanice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `trasa`
--

CREATE TABLE IF NOT EXISTS `trasa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_trasy` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_Trasa_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

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
('', '20151123_151927.png', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 0),
('43843', '20151123_151616.jpg', '', '', '', '2015-11-11', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 1),
('4567890', '20151125_094646.jpg', '666', 'hbhbjbhjb', 'hhbhjbjhbhj', '2015-11-05', 'hdiudhai', 1, 21312, 12321, 1231321, '999', 'bagr', 'jknjkand', 'dnajdskan', 21123, 122.00, 1212, 22.00, 122.00, 1),
('666', '20151122_133023.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 1),
('77', '20151123_151659.png', '', '', '', '0000-00-00', 'bagr', 0, 0, 0, 0, '', '666', '', '', 0, 0.00, 0, 0.00, 0.00, 0),
('7777', '20151123_151527.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', '', '', 0, 0.00, 0, 0.00, 0.00, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `zamestnanec`
--

INSERT INTO `zamestnanec` (`id`, `img_url`, `druh_pomeru`, `email`, `jmeno`, `prijmeni`, `telefon`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `depo`, `role`, `smlouva_od`, `login`, `password`, `smlouva_do`) VALUES
(666, '20151125_172450.jpg', 'ak', 'k', 'm', 'l', 'm', 'l', 'ml', 0, 'ml', 1, 2, '2015-11-04', 'Neo', 'bahr', '2015-11-27');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `kontrola`
--
ALTER TABLE `kontrola`
  ADD CONSTRAINT `FK_Kontrola_Zamestnanec` FOREIGN KEY (`provedl`) REFERENCES `zamestnanec` (`id`),
  ADD CONSTRAINT `FK_Kontrola_Vlak` FOREIGN KEY (`vlak`) REFERENCES `vlak` (`cislo_zkv`);

--
-- Omezení pro tabulku `sklad`
--
ALTER TABLE `sklad`
  ADD CONSTRAINT `FK_Sklad_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Omezení pro tabulku `stanicetrasy`
--
ALTER TABLE `stanicetrasy`
  ADD CONSTRAINT `FK_StaniceTrasy_Stanice` FOREIGN KEY (`stanice`) REFERENCES `stanice` (`id`),
  ADD CONSTRAINT `FK_StaniceTrasy_Trasa` FOREIGN KEY (`trasa`) REFERENCES `trasa` (`id`);

--
-- Omezení pro tabulku `trat`
--
ALTER TABLE `trat`
  ADD CONSTRAINT `FK_Trat_Stanice_03` FOREIGN KEY (`cilova_stanice`) REFERENCES `stanice` (`id`),
  ADD CONSTRAINT `FK_Trat_Stanice_02` FOREIGN KEY (`vychozi_stanice`) REFERENCES `stanice` (`id`);

--
-- Omezení pro tabulku `vlak`
--
ALTER TABLE `vlak`
  ADD CONSTRAINT `FK_Vlak_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Omezení pro tabulku `vlakjede`
--
ALTER TABLE `vlakjede`
  ADD CONSTRAINT `FK_VlakJede_Vlak` FOREIGN KEY (`vlak`) REFERENCES `vlak` (`cislo_zkv`),
  ADD CONSTRAINT `FK_VlakJede_Trasa` FOREIGN KEY (`trasa`) REFERENCES `trasa` (`id`);

--
-- Omezení pro tabulku `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD CONSTRAINT `FK_Zamestnanec_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`),
  ADD CONSTRAINT `FK_Zamestnanec_Role` FOREIGN KEY (`role`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
