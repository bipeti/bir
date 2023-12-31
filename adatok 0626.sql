-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2009. jún. 26. 14:15
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
(1, 'FAR verzióváltás', 1, 'bihari.peter', '2009-06-19 12:36:36', '2009-07-18', '06.20-án 08:00-10:00-ig FAR verzióváltás lesz. Ez alatt az idõ alatt a Fogvatartotti nem lesz használható.\r\n\r\nA programfrissítés az alábbi csoportokat fogja érinteni:\r\n- nevelõk,\r\n- egészségügy,\r\n- pszichológus.\r\n\r\nAmint megérkezik a változásokról szóló leírás, feltöltésre kerül a hír mellé. Ezt a hírt figyeljétek.'),
(2, 'Elindult a BIR', 2, 'bihari.peter', '2009-06-19 13:11:53', '2009-07-19', '<font color=blue><b>BIR</b>-ismertetõ.\r\n\r\nElindult a Belsõ Információs Rendszer. Ezen az oldalon az intézet belsõ életével kapcsolatos közérdekû hírek, aktuális információk lesznek megosztva veletek.</font>\r\n\r\nA BIR pontosan olyan alapokra épült, mint a web-oldalak többsége, ezért ha interneteztetek már valaha, akkor gyorsan megtanuljátok majd mindenféle tanfolyam, vagy oktatás nélkül.\r\n\r\nA BIR több oldalból áll:\r\nA <a href=''hirek.php''>Hírek</a> menüpont alatt láthatóak az aktuális hírek, események.\r\nA <a href=''programok.php''>Programok</a> között az intézetben használt webes felületen futó alkalmazások vannak egy helyre összegyûjtve.\r\nÚjdonság a Programok között: elkészült a <a href=''index.php''>Helyi intézkedések</a> program. A mûködése hasonlít az OP intézkedések programhoz, azonban itt kulturáltabb formában lehet nyomtatni. Keresni tárgyszavakra lehet, illetve lehet szûkíteni a csak hatályos intézkedésekre.\r\nA <a href=''dokumentumok.php''>Dokumentumok</a> között keressétek majd a legfontosabb nyomtatványokat, leírásokat, jegyzékeket. (Ha van olyan anyagotok, ami szerintetek jól jöhet sokaknak, juttassátok el az informatikára és feltesszük a többi dokumentum közé.)\r\nA <a href=''galeria.php''>Galériába</a> fogjuk egybe gyûjteni a belsõ hálózaton található fényképeket.\r\nA <a href=''linkek.php''>Linkek</a> között hasznos szakmai oldalak lesznek összegyûjtve, ezek eléréséhez Internetre van szükség.\r\n\r\nHasználjátok egészséggel! :-)\r\n\r\n<font color=red><b>Az oldal használatának megkezdése elõtt a legfontosabb:\r\nA Belsõ Információs Rendszer Mozilla Firefox böngészõre lett optimalizálva és Internet Explorer alatt nem jelenik meg helyesen! A használata során mindenképpen Firefox böngészõ ajánlott. Ha nincs ilyen a gépeden, vagy nem tudod, hogy mi az, vagy ha egyszerûen csak nem mûködik valami az oldalon, akkor csörögj az informatikára.</b></font>'),
(3, 'Na még egy', 0, 'bihari.peter', '2009-06-22 12:35:21', '2009-07-19', 'Na még egyet'),
(4, 'A negyedik õ Õ û Û', 0, 'bihari.peter', '2009-06-22 12:37:31', '2009-07-11', 'Blablablablabla'),
(5, 'Felvitel teszt', 1, 'bihari.peter', '2009-06-22 12:42:54', '2009-12-31', 'Felvitel teszt'),
(6, 'Hatos', 0, 'bihari.peter', '2009-06-22 12:44:15', '2009-07-11', 'A\r\nnegyven\r\nHatos\r\n\r\nsárga villamoson \r\n\r\nutazik az anyósom...'),
(8, 'nyolc', 0, 'bihari.peter', '2009-06-22 12:45:44', '2009-12-31', 'n\r\ny\r\no\r\nl\r\nc'),
(9, 'Intézkedési terv módosítás', 1, 'bihari.peter', '2009-06-22 14:31:39', '2009-12-31', 'Módosításra került a 2008. évben megtartott átfogó szakmai ellenõrzésen feltárt hiányosságok megszüntetésére készített intézkedési terv. Lásd melléklet.'),
(10, 'teszt', 0, 'bihari.peter', '2009-06-22 15:06:08', '2009-12-31', '222'),
(11, 'ttt', 0, 'bihari.peter', '2009-06-22 15:08:45', '2009-12-31', '333'),
(12, 'Új intézkedés', 0, 'bihari.peter', '2009-06-22 15:23:27', '2009-07-11', 'Új intézkedés került kiadásra.\r\nMegtalálható a BIR rendszer Programok menü, <a href=''index.php''>Helyi intézkedések</a> menüpontban.'),
(13, 'Új intézkedés', 0, 'bihari.peter', '2009-06-23 12:24:54', '2009-07-11', 'Új intézkedés került kiadásra leltározással kapcs.\r\nMegtalálható itt: <a href=''index.php''>Helyi intézkedések</a> menüpontban.'),
(14, 'Július havi étlap', 0, 'bihari.peter', '2009-06-23 14:21:17', '2009-07-31', 'Július havi étlap'),
(15, 'teszt', 0, 'bihari.peter', '2009-06-23 15:25:05', '2009-01-01', 'eeee'),
(16, 'Véradás', 0, 'biczo.laszlo', '2009-06-25 08:42:47', '2009-07-27', '2009-07-26-án 10 órától véradási lehetõség az intézet kultúrhelységében.\r\nA véradók csapolás után hazamehetnek.');

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

INSERT INTO `intezkedesek` (`intazon`, `ev`, `sorszam`, `intcim`, `hatlep`, `hatveszt`, `hathelyez`, `htmlnev`, `pdfnev`) VALUES
('003/2008', 2008, 3, 'Az intézeti belsõ rendelkezések elkészítésének rendje.', '2008-04-01', NULL, NULL, '003-2008.html', '003-2008.doc'),
('004/2008', 2008, 4, 'A személyi állomány munka-, védõ- és formaruházati ellátása.', '2008-04-02', '2009-01-28', '007/2009', '004-2008.html', '004-2008.doc'),
('005/2008', 2008, 5, 'Fogvatartotti tartozás levonása beküldött letéti pénzbõl.', '2008-04-02', NULL, NULL, '005-2008.html', '005-2008.doc'),
('007/2009', 2009, 7, 'A személyi állomány munka-, védõ- és formaruházati ellátása.', '2009-01-28', NULL, NULL, '007-2009.html', '007-2009.doc'),
('008/2008', 2008, 8, 'Az ajtók zárva tartásának elrendelése.', '2008-04-03', NULL, NULL, '008-2008.html', '008-2008.doc'),
('009/2008', 2008, 9, 'A kényszerítés cselekmények megelõzése.', '2008-04-03', NULL, NULL, '009-2008.html', '009-2008.doc');

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
('csaszovnyikov.kirill', 'Felhasználók', 'rendszergazda'),
('biczo.laszlo', 'Helyi intézkedések', 'adminisztrátor'),
('biczo.laszlo', 'Hírek', 'adminisztrátor'),
('biczo.laszlo', 'Felhasználók', 'rendszergazda');

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

INSERT INTO `mellekletek` (`mihez`, `mihez_azon`, `mellnev`) VALUES
('hírek', '9', 'inttervmod.doc'),
('hírek', '14', '001-2008.doc');

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

INSERT INTO `tags` (`mihez`, `mihez_azon`, `kulcsszo`) VALUES
('intézkedés', '003/2008', 'intézkedés elkészítése'),
('intézkedés', '005/2008', 'gazdasági osztály'),
('intézkedés', '005/2008', 'letéti csoport'),
('intézkedés', '005/2008', 'fogvatartotti tartozás levonása'),
('intézkedés', '008/2008', 'ajtók zárva tartása'),
('intézkedés', '008/2008', 'bv. osztály'),
('intézkedés', '008/2008', 'biztonsági osztály'),
('intézkedés', '008/2008', 'eü. osztály'),
('intézkedés', '009/2008', 'bv. osztály'),
('intézkedés', '009/2008', 'kényszerítés'),
('intézkedés', '007/2009', 'munka-,védõ- és formaruházat'),
('intézkedés', '007/2009', 'gazdasági osztály'),
('intézkedés', '004/2008', 'munka-,védõ- és formaruházat'),
('intézkedés', '004/2008', 'gazdasági osztály');
