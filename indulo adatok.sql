-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2009. jún. 29. 11:43
-- Szerver verzió: 5.1.30
-- PHP verzió: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Adatbázis: `bir`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `felhasznalok`
--

CREATE TABLE IF NOT EXISTS `felhasznalok` (
  `felhnev` varchar(30) NOT NULL,
  `nev` varchar(30) NOT NULL,
  `jelszo` varchar(32) NOT NULL,
  PRIMARY KEY (`felhnev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`felhnev`, `nev`, `jelszo`) VALUES
('biczo.laszlo', 'Biczó László', 'b8fc6f895e14948a5144bb9f6e40f7a7'),
('bihari.peter', 'Bihari Péter', '02f413f629405ecba16da439fe77aeb0'),
('csaszovnyikov.kirill', 'Csaszovnyikov Kirill', 'kk'),
('szabo.miklos', 'Szabó Miklós', 'ee');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `hirek`
--

CREATE TABLE IF NOT EXISTS `hirek` (
  `hirazon` int(11) DEFAULT NULL,
  `hircim` varchar(40) DEFAULT NULL,
  `kiemelt` tinyint(4) NOT NULL,
  `felhnev` varchar(30) DEFAULT NULL,
  `ido` datetime DEFAULT NULL,
  `meddig` date DEFAULT NULL,
  `leiras` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `hirek`
--

INSERT INTO `hirek` (`hirazon`, `hircim`, `kiemelt`, `felhnev`, `ido`, `meddig`, `leiras`) VALUES
(2, 'Elindult a BIR', 2, 'bihari.peter', '2009-06-19 13:11:53', '2009-07-19', '<font color=blue><b>BIR</b>-ismertetõ.\r\n\r\nElindult a Belsõ Információs Rendszer. Ezen az oldalon az intézet belsõ életével kapcsolatos közérdekû hírek, aktuális információk lesznek megosztva veletek.</font>\r\n\r\nA BIR pontosan olyan alapokra épült, mint a web-oldalak többsége, ezért ha interneteztetek már valaha, akkor gyorsan megtanuljátok majd mindenféle tanfolyam, vagy oktatás nélkül.\r\n\r\nA BIR több oldalból áll:\r\nA <a href=''hirek.php''>Hírek</a> menüpont alatt láthatóak az aktuális hírek, események.\r\nA <a href=''programok.php''>Programok</a> között az intézetben használt webes felületen futó alkalmazások vannak egy helyre összegyûjtve.\r\nÚjdonság a Programok között: elkészült a <a href=''index.php''>Helyi intézkedések</a> program. A mûködése hasonlít az OP intézkedések programhoz, azonban itt kulturáltabb formában lehet nyomtatni. Keresni tárgyszavakra lehet, illetve lehet szûkíteni a csak hatályos intézkedésekre.\r\nA <a href=''dokumentumok.php''>Dokumentumok</a> között keressétek majd a legfontosabb nyomtatványokat, leírásokat, jegyzékeket. (Ha van olyan anyagotok, ami szerintetek jól jöhet sokaknak, juttassátok el az informatikára és feltesszük a többi dokumentum közé.)\r\nA <a href=''galeria.php''>Galériába</a> fogjuk egybe gyûjteni a belsõ hálózaton található fényképeket.\r\nA <a href=''linkek.php''>Linkek</a> között hasznos szakmai oldalak lesznek összegyûjtve, ezek eléréséhez Internetre van szükség.\r\n\r\nHasználjátok egészséggel! :-)\r\n\r\n<font color=red><b>Az oldal használatának megkezdése elõtt a legfontosabb:\r\nA Belsõ Információs Rendszer Mozilla Firefox böngészõre lett optimalizálva és Internet Explorer alatt nem jelenik meg helyesen! A használata során mindenképpen Firefox böngészõ ajánlott. Ha nincs ilyen a gépeden, vagy nem tudod, hogy mi az, vagy ha egyszerûen csak nem mûködik valami az oldalon, akkor csörögj az informatikára.</b></font>');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `intezkedesek`
--

CREATE TABLE IF NOT EXISTS `intezkedesek` (
  `intazon` varchar(8) NOT NULL,
  `ev` year(4) DEFAULT NULL,
  `sorszam` int(11) DEFAULT NULL,
  `intcim` varchar(80) DEFAULT NULL,
  `hatlep` date DEFAULT NULL,
  `hatveszt` date DEFAULT NULL,
  `hathelyez` varchar(8) DEFAULT NULL,
  `htmlnev` varchar(80) DEFAULT NULL,
  `pdfnev` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`intazon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `intezkedesek`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `jogosultsagok`
--

CREATE TABLE IF NOT EXISTS `jogosultsagok` (
  `kinek` varchar(30) NOT NULL,
  `mire` varchar(30) NOT NULL,
  `szint` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `jogosultsagok`
--

INSERT INTO `jogosultsagok` (`kinek`, `mire`, `szint`) VALUES
('bihari.peter', 'Helyi intézkedések', 'adminisztrátor'),
('bihari.peter', 'Hírek', 'rendszergazda'),
('bihari.peter', 'Felhasználók', 'rendszergazda'),
('szabo.miklos', 'Helyi intézkedések', 'adminisztrátor'),
('csaszovnyikov.kirill', 'Felhasználók', 'rendszergazda');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `mellekletek`
--

CREATE TABLE IF NOT EXISTS `mellekletek` (
  `mihez` varchar(20) DEFAULT NULL,
  `mihez_azon` varchar(8) DEFAULT NULL,
  `mellnev` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `mellekletek`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `modositasok`
--

CREATE TABLE IF NOT EXISTS `modositasok` (
  `mit` varchar(8) DEFAULT NULL,
  `mi` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `modositasok`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `mihez` varchar(20) DEFAULT NULL,
  `mihez_azon` varchar(8) DEFAULT NULL,
  `kulcsszo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `tags`
--

