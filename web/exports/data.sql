-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vygenerováno: Čtv 07. led 2016, 14:46
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

--
-- Vyprázdnit tabulku před vkládáním `depo`
--

TRUNCATE TABLE `depo`;
--
-- Vypisuji data pro tabulku `depo`
--

INSERT INTO `depo` (`id`, `img_url`, `nazev`, `GPS`, `mesto`, `adresa`, `cislo_popisne`, `stat`, `telefon`, `kapacita`) VALUES
(5, '20160107_132212.jpg', 'Depo Praha', '', 'Praha', '', 0, '', '', 32),
(6, '20160107_132303.jpg', 'Depo Brno', '', 'Brno', '', 0, 'ČR', '', 20),
(7, '20160107_132448.jpg', 'Depo Ostrava', '', 'Ostrava', '', 0, 'ČR', '', 12),
(8, '20160107_132643.jpg', 'na_smazani', '', 'irelevantni', '', 0, '', '', 1);

--
-- Vyprázdnit tabulku před vkládáním `kontrola`
--

TRUNCATE TABLE `kontrola`;
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

--
-- Vyprázdnit tabulku před vkládáním `role`
--

TRUNCATE TABLE `role`;
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

--
-- Vyprázdnit tabulku před vkládáním `sklad`
--

TRUNCATE TABLE `sklad`;
--
-- Vyprázdnit tabulku před vkládáním `stanice`
--

TRUNCATE TABLE `stanice`;
--
-- Vypisuji data pro tabulku `stanice`
--

INSERT INTO `stanice` (`id`, `GPS`, `nazev`, `mesto`, `adresa`, `stat`) VALUES
(7, '', 'Vysoké Mýto', 'Vysoké Mýto, město', '', 'Česká republika'),
(8, '', 'Praha', 'Praha hlavní nadraží', '', 'Česká republika'),
(9, '', 'Brno', 'Brno hlavní nadraží', '', 'Česká republika'),
(10, '', 'Ostrava', 'Ostrava', '', 'Česká republika');

--
-- Vyprázdnit tabulku před vkládáním `stavy`
--

TRUNCATE TABLE `stavy`;
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

--
-- Vyprázdnit tabulku před vkládáním `trasa`
--

TRUNCATE TABLE `trasa`;
--
-- Vypisuji data pro tabulku `trasa`
--

INSERT INTO `trasa` (`id`, `nazev_trasy`, `stanice1`, `stanice2`, `delka`, `vyluka`, `disabled`) VALUES
(8, 'Ostrava->Brno', 10, 9, 120, 1, 0),
(9, 'Mýto->Praha', 7, 8, 203, 6, 0),
(10, 'Praha->Brno', 8, 9, 183, 6, 0);

--
-- Vyprázdnit tabulku před vkládáním `ukony`
--

TRUNCATE TABLE `ukony`;
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

--
-- Vyprázdnit tabulku před vkládáním `vlak`
--

TRUNCATE TABLE `vlak`;
--
-- Vypisuji data pro tabulku `vlak`
--

INSERT INTO `vlak` (`cislo_zkv`, `img_url`, `rada`, `sokv`, `UIC_OLD`, `datum_preznaceni`, `m_stav`, `flag_eko`, `elektromer`, `spotreba_nafty`, `ele_ohrev`, `vkv`, `vz`, `pocet_km`, `vmax`, `pocet_naprav`, `delka`, `hmotnost`, `brvaha_g`, `brvaha_p`, `depo`) VALUES
('111111111111', '20160107_130817.jpg', '708', 'Praha', NULL, '2016-01-01', NULL, NULL, 20, 100, NULL, NULL, NULL, 1810, '80', 8, 15.00, 0, 0.00, NULL, 5),
('222222222222', '20160107_141047.jpg', 'řada 749', 'Praha', NULL, '2016-01-13', NULL, NULL, 15, 140, NULL, NULL, NULL, 2203, '100', 14, 22.00, 63, 2.00, NULL, 5),
('333333333333', '20160107_141158.jpg', 'T 426.0 (715)', 'Praha', NULL, '2016-01-04', NULL, NULL, 15, 125, NULL, NULL, NULL, 1183, '90', 16, 13.00, 103, 2.00, NULL, 5),
('444444444444', '20160107_141319.jpg', ' T 478.1 (751)', '', NULL, '2016-01-03', NULL, NULL, 0, 0, NULL, NULL, NULL, 3000, '100', 12, 14.00, 0, 0.00, NULL, 5);

--
-- Vyprázdnit tabulku před vkládáním `vyluky`
--

TRUNCATE TABLE `vyluky`;
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

--
-- Vyprázdnit tabulku před vkládáním `zamestnanec`
--

TRUNCATE TABLE `zamestnanec`;
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
