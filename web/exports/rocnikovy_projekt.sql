-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2016 at 11:47 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rocnikovy_projekt`
--

-- --------------------------------------------------------

--
-- Table structure for table `depo`
--

CREATE TABLE IF NOT EXISTS `depo` (
  `id` int(11) NOT NULL,
  `img_url` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `nazev` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `GPS` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `mesto` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `adresa` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `cislo_popisne` int(11) DEFAULT NULL,
  `stat` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  `kapacita` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `depo`
--

INSERT INTO `depo` (`id`, `img_url`, `nazev`, `GPS`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `telefon`, `kapacita`) VALUES
(0, '20151230_152334.jpg', 'Depo1', '11333', 'njasndkj', 'dajsnkjak', 21, 'andksjd', 'jdnakdsnk', 12),
(1, '20151219_230358.jpg', 'depo', '11333', 'njasndkj', 'dajsnkjak', 21, 'andksjd', 'jdnakdsnk', 12),
(2, '20151219_173449.jpg', 'DEPO Litomysl', '', '', '', 51, '', '608610174', 66);

-- --------------------------------------------------------

--
-- Table structure for table `kontrola`
--

CREATE TABLE IF NOT EXISTS `kontrola` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `cislo_zkv` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `datum_expirace` date DEFAULT NULL,
  `kontrola_od` date DEFAULT NULL,
  `kontrola_do` date DEFAULT NULL,
  `vysledek` varchar(1000) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `kontrola`
--

INSERT INTO `kontrola` (`id`, `id_user`, `cislo_zkv`, `datum_expirace`, `kontrola_od`, `kontrola_do`, `vysledek`) VALUES
(2, 667, '_6666', '2016-01-28', '2014-01-01', NULL, 'Kontrola byla na picu :D'),
(5, 667, '666', '2016-01-01', '2015-01-01', NULL, 'Kontrola probehla uspesne :))'),
(6, 667, '999vlacek', '2016-12-31', '2013-01-09', NULL, 'Stará kontrola'),
(7, 667, '77', '2016-01-31', '2015-11-01', NULL, 'bagr'),
(8, 667, '666', '2016-08-18', '2016-01-01', NULL, 'bagr'),
(9, 673, '777', '2016-01-20', '2016-01-01', NULL, 'technik test');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `nazev` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `role`
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
-- Table structure for table `sklad`
--

CREATE TABLE IF NOT EXISTS `sklad` (
  `id` int(11) NOT NULL,
  `kapacita` int(11) DEFAULT NULL,
  `depo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stanice`
--

CREATE TABLE IF NOT EXISTS `stanice` (
  `id` int(11) NOT NULL,
  `GPS` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `nazev` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `mesto` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `adresa` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `stat` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `stanice`
--

INSERT INTO `stanice` (`id`, `GPS`, `nazev`, `mesto`, `adresa`, `stat`) VALUES
(2, '', 'Brno', 'Brno', '', ''),
(3, '49.953770, 16.158764', 'Vysoké Mýto, Město', 'Vysoké Mýto', 'Plk. B. Kouhouta 804', 'Česká Republika');

-- --------------------------------------------------------

--
-- Table structure for table `stavy`
--

CREATE TABLE IF NOT EXISTS `stavy` (
  `id_stav` int(11) NOT NULL,
  `nazev` varchar(20) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `stavy`
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
-- Table structure for table `trasa`
--

CREATE TABLE IF NOT EXISTS `trasa` (
  `id` int(11) NOT NULL,
  `nazev_trasy` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `stanice1` int(11) NOT NULL,
  `stanice2` int(11) NOT NULL,
  `delka` int(11) NOT NULL,
  `vyluka` tinyint(1) NOT NULL,
  `disabled` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `trasa`
--

INSERT INTO `trasa` (`id`, `nazev_trasy`, `stanice1`, `stanice2`, `delka`, `vyluka`, `disabled`) VALUES
(2, 'Brno -> Mejto', 2, 3, 156, 3, 1),
(3, 'Brno -> Mejto', 2, 3, 35, 6, 0),
(6, 'Mejto -> Brno', 3, 2, 69, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ukony`
--

CREATE TABLE IF NOT EXISTS `ukony` (
  `id_ukon` int(11) NOT NULL,
  `cislo_zkv` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_trasa` int(11) NOT NULL,
  `pocet_vagonu` int(10) NOT NULL,
  `cas` int(10) NOT NULL,
  `stav` int(5) NOT NULL DEFAULT '1',
  `finished` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `ukony`
--

INSERT INTO `ukony` (`id_ukon`, `cislo_zkv`, `id_user`, `id_trasa`, `pocet_vagonu`, `cas`, `stav`, `finished`) VALUES
(2, '666', 669, 6, 10, 136, 6, '2014-01-02'),
(8, '999vlacek', 668, 3, 17, 66, 6, '2013-01-01'),
(22, '777', 668, 3, 15, 16, 6, '2015-03-09'),
(23, '_6666', 669, 3, 10, 10, 6, '2013-01-02'),
(24, '4567890', 668, 6, 10, 10, 6, '2016-01-03'),
(25, '666', 668, 2, 10, 10, 6, '2014-05-14'),
(26, '_6666', 668, 2, 10, 10, 6, '2016-01-03'),
(27, '_6666', 669, 6, 15, 132, 6, '2016-01-03'),
(28, '666699994d5as4', 668, 2, 10, 20, 1, '0000-00-00'),
(29, '77', 669, 2, 15, 20, 1, '0000-00-00'),
(30, '777', 670, 2, 15, 20, 1, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `vlak`
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
  `depo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `vlak`
--

INSERT INTO `vlak` (`cislo_zkv`, `img_url`, `rada`, `sokv`, `UIC_OLD`, `datum_preznaceni`, `m_stav`, `flag_eko`, `elektromer`, `spotreba_nafty`, `ele_ohrev`, `vkv`, `vz`, `pocet_km`, `vmax`, `pocet_naprav`, `delka`, `hmotnost`, `brvaha_g`, `brvaha_p`, `depo`) VALUES
('_6666', '20160102_232958.jpg', 'Satan', 'Satan666', NULL, '2016-01-13', NULL, NULL, 10, 10, NULL, NULL, NULL, 1596, '166', 15, 32.00, 66, 4.00, NULL, 2),
('43843', '20151123_151616.jpg', '', '', '', '2015-11-11', '', 0, 0, 0, 0, '', '', 6666, '', 0, 0.00, 0, 0.00, 0.00, 1),
('4567890', '20151125_094646.jpg', '666', 'hbhbjbhjb', 'hhbhjbjhbhj', '2015-11-05', 'hdiudhai', 1, 21312, 12321, 1231321, '999', 'bagr', 138, 'dnajdskan', 21123, 122.00, 1212, 22.00, 122.00, 1),
('666', '20151222_113632.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', 156, '', 0, 0.00, 0, 0.00, 0.00, 2),
('666699994d5as4', '20151219_222706.png', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', 35, '', 0, 0.00, 0, 0.00, 0.00, 0),
('77', '20151123_151659.png', '', '', '', '0000-00-00', 'bagr', 0, 0, 0, 0, '', '666', 0, '', 0, 0.00, 0, 0.00, 0.00, 0),
('777', '20151219_222215.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', 35, '', 0, 0.00, 0, 0.00, 0.00, 0),
('7777', '20151123_151527.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', 0, '', 0, 0.00, 0, 0.00, 0.00, 1),
('999vlacek', '20151219_223016.jpg', '', '', '', '0000-00-00', '', 0, 0, 0, 0, '', '', 0, '', 0, 0.00, 0, 0.00, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vyluky`
--

CREATE TABLE IF NOT EXISTS `vyluky` (
  `id` int(11) NOT NULL,
  `nazev` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `dopad` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `vyluky`
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
-- Table structure for table `zamestnanec`
--

CREATE TABLE IF NOT EXISTS `zamestnanec` (
  `id` int(11) NOT NULL,
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
  `smlouva_do` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=675 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `zamestnanec`
--

INSERT INTO `zamestnanec` (`id`, `img_url`, `druh_pomeru`, `email`, `jmeno`, `prijmeni`, `telefon`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `depo`, `role`, `smlouva_od`, `login`, `password`, `smlouva_do`) VALUES
(667, '', NULL, NULL, 'admin', 'pico', NULL, NULL, NULL, NULL, NULL, 1, 69, NULL, 'admin', 'lamer', NULL),
(668, '20151219_222354.jpg', 'Picus', 'p.vomacko@gmail.com', 'dement', '', '', '', '', 0, '', 0, 1, '0000-00-00', '', 'bagr', '2015-12-16'),
(669, '20151219_222649.jpg', 'Dement', '', 'picus', 'Velký', '', '', '', 0, '', 0, 1, '2015-08-10', 'nejsem_pica', '123456', '2016-01-13'),
(670, '20151222_113744.jpg', '', '', 'Admin pico!', '', '', '', '', 0, '', 2, 1, '0000-00-00', '', '', '0000-00-00'),
(672, '20160102_164741.jpg', 'Nasrat', 'pardubice@symbiodigital.com', 'Petr', 'Vomáčko', '608610174', 'Vysoké Mýto', 'Bučina', 95, 'Česká Republika', 2, 3, '2014-11-01', 'mansfield', 'lamer', '2016-01-08'),
(673, '20160103_232644.jpg', '', '', 'technik', 'depa', '', '', '', 0, '', 0, 4, '0000-00-00', 'technik', 'bagr', '2016-01-07'),
(674, '20160103_233857.png', '', '', 'dispicer', '', '', '', '', 0, '', 2, 1, '2016-01-20', 'dispecer', 'pica', '2016-01-22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `depo`
--
ALTER TABLE `depo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Depo_id` (`id`);

--
-- Indexes for table `kontrola`
--
ALTER TABLE `kontrola`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Kontrola_id` (`id`),
  ADD KEY `vlak` (`cislo_zkv`),
  ADD KEY `provedl` (`id_user`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Role_id` (`id`);

--
-- Indexes for table `sklad`
--
ALTER TABLE `sklad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Sklad_id` (`id`),
  ADD KEY `depo` (`depo`);

--
-- Indexes for table `stanice`
--
ALTER TABLE `stanice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Stanice_id` (`id`);

--
-- Indexes for table `stavy`
--
ALTER TABLE `stavy`
  ADD PRIMARY KEY (`id_stav`);

--
-- Indexes for table `trasa`
--
ALTER TABLE `trasa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Trasa_id` (`id`),
  ADD KEY `staniceA` (`stanice1`),
  ADD KEY `staniceB` (`stanice2`);

--
-- Indexes for table `ukony`
--
ALTER TABLE `ukony`
  ADD PRIMARY KEY (`id_ukon`),
  ADD KEY `cislo_zkv` (`cislo_zkv`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_trasa` (`id_trasa`),
  ADD KEY `stav` (`stav`);

--
-- Indexes for table `vlak`
--
ALTER TABLE `vlak`
  ADD PRIMARY KEY (`cislo_zkv`),
  ADD UNIQUE KEY `UQ_Vlak_cislo_zkv` (`cislo_zkv`),
  ADD KEY `depo` (`depo`);

--
-- Indexes for table `vyluky`
--
ALTER TABLE `vyluky`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UQ_Zamestnanec_id` (`id`),
  ADD KEY `role` (`role`),
  ADD KEY `depo` (`depo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `depo`
--
ALTER TABLE `depo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `kontrola`
--
ALTER TABLE `kontrola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `sklad`
--
ALTER TABLE `sklad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stanice`
--
ALTER TABLE `stanice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `stavy`
--
ALTER TABLE `stavy`
  MODIFY `id_stav` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `trasa`
--
ALTER TABLE `trasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ukony`
--
ALTER TABLE `ukony`
  MODIFY `id_ukon` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `vyluky`
--
ALTER TABLE `vyluky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `zamestnanec`
--
ALTER TABLE `zamestnanec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=675;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `kontrola`
--
ALTER TABLE `kontrola`
  ADD CONSTRAINT `FK_Kontrola_Vlak` FOREIGN KEY (`cislo_zkv`) REFERENCES `vlak` (`cislo_zkv`),
  ADD CONSTRAINT `FK_Kontrola_Zamestnanec` FOREIGN KEY (`id_user`) REFERENCES `zamestnanec` (`id`);

--
-- Constraints for table `sklad`
--
ALTER TABLE `sklad`
  ADD CONSTRAINT `FK_Sklad_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Constraints for table `trasa`
--
ALTER TABLE `trasa`
  ADD CONSTRAINT `trasa_ibfk_1` FOREIGN KEY (`stanice1`) REFERENCES `stanice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trasa_ibfk_2` FOREIGN KEY (`stanice2`) REFERENCES `stanice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ukony`
--
ALTER TABLE `ukony`
  ADD CONSTRAINT `ukony_ibfk_1` FOREIGN KEY (`cislo_zkv`) REFERENCES `vlak` (`cislo_zkv`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ukony_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `zamestnanec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ukony_ibfk_3` FOREIGN KEY (`id_trasa`) REFERENCES `trasa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vlak`
--
ALTER TABLE `vlak`
  ADD CONSTRAINT `FK_Vlak_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`);

--
-- Constraints for table `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD CONSTRAINT `FK_Zamestnanec_Depo` FOREIGN KEY (`depo`) REFERENCES `depo` (`id`),
  ADD CONSTRAINT `FK_Zamestnanec_Role` FOREIGN KEY (`role`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
