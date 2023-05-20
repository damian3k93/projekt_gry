-- Host: localhost
-- Czas wygenerowania: 12 Lip 2016, 19:16
-- Wersja serwera: 10.1.14-MariaDB
-- Wersja PHP: 5.6.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `game`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `applications`
--

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `changelog`
--

CREATE TABLE IF NOT EXISTS `changelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ver` float NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `changelog`
--

INSERT INTO `changelog` (`id`, `ver`, `date`, `content`) VALUES
(1, 0.5, '2016-06-10 00:32:55', '<ul><li>Utworzono ten changelog</li><li>Utworzono podstawowy skrypt gildii<ul><li>Tworzenie i usuwanie gildii</li><li>Dodawanie do i usuwanie z gildii</li><li>Prosty system przywilejów</li><li>Dostosowanie skryptu rankingu dla gildii</li></ul></li><li>System maili systemowych używanych przez skrypty</li></ul>'),
(2, 0.6, '2016-06-10 01:58:31', '<ul><li>Dodano system komentarzy do listy zmian</li><li>Dodano pełną listę zmian</li></ul>'),
(3, 0.61, '2016-06-14 20:41:52', '<ul><li>Dodano powiadomienia o odbytej walce PVP</li><li>Dodano otrzymywanie i tracenie punktów reputacji podczas walk na arenie</li><li>Dodano avatary gildyjne</li><li>Dodano formularze do zmiany hasła, adresu email i avatara</li></ul>'),
(4, 0.62, '2016-06-16 23:54:08', '<ul><li>Poprawka logiki oraz kodu prezentacji sklepu</li></ul>'),
(5, 0.63, '2016-06-17 19:07:52', '<ul><li>Dodano ustawienia gracza</li><ul><li>Zmiana hasła</li><li>Zmiana adresu email</li><li>Zmiana avatara</li></ul><li>Dodano zmianę sztandaru gildii</li></ul>'),
(6, 0.64, '2016-07-04 14:44:57', '<ul><li>Dodano tawernę</li></ul>'),
(7, 0.7, '2016-07-12 19:03:49', '<ul><li>Zoptymalizowano kod silnika</li></ul>');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_action` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `cron`
--

INSERT INTO `cron` (`id`, `last_action`) VALUES
(1, 1468306103);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `guilds`
--

CREATE TABLE IF NOT EXISTS `guilds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerid` int(11) NOT NULL,
  `tag` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `avatar` int(11) NOT NULL DEFAULT '0',
  `cash` int(11) NOT NULL DEFAULT '0',
  `lvl` int(11) NOT NULL DEFAULT '1',
  `rep` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `obj` int(11) NOT NULL,
  `used` int(11) NOT NULL DEFAULT '0',
  `sta` int(100) NOT NULL DEFAULT '0',
  `str` int(100) NOT NULL DEFAULT '0',
  `dex` int(100) NOT NULL DEFAULT '0',
  `intell` int(100) NOT NULL DEFAULT '0',
  `luck` int(100) NOT NULL DEFAULT '0',
  `stamina` int(11) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `lvl` int(11) NOT NULL,
  `class` varchar(255) NOT NULL,
  `type` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `min_dmg` int(11) NOT NULL,
  `max_dmg` int(11) NOT NULL,
  `resist` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `sta` int(100) NOT NULL DEFAULT '0',
  `str` int(100) NOT NULL DEFAULT '0',
  `dex` int(100) NOT NULL DEFAULT '0',
  `intell` int(100) NOT NULL DEFAULT '0',
  `luck` int(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci ;

--
-- Zrzut danych tabeli `items`
--

INSERT INTO `items` (`id`, `name`, `lvl`, `class`, `type`, `min_dmg`, `max_dmg`, `resist`, `cost`, `ista`, `istr`, `idex`, `iintell`, `iluck`) VALUES
(1101, 'Kij 1', 1,  'mag', 'weapon', 5, 12, 0, 50, 0, 0, 0, 0, 0),
(1102, 'Kij 2', 5,  'mag', 'weapon', 11, 19, 0, 85 , 0, 0, 0, 0, 0),
(1103, 'Kij 3', 10,  'mag', 'weapon', 17, 26, 0, 135, 0, 0, 0, 0, 0),
(1104, 'Kij 4', 15,  'mag', 'weapon',22, 34, 0, 225, 0, 0, 0, 0, 0),
(1105, 'Kij 5', 20,  'mag', 'weapon', 28, 42, 0, 370, 0, 0, 0, 0, 0),
(1106, 'Kij 6', 25,  'mag', 'weapon', 34, 50, 0, 610, 0, 0, 0, 0, 0),
(1107, 'Kij 7', 30,  'mag', 'weapon', 40, 58, 0, 1000, 0, 0, 0, 0, 0),
(1108, 'Kij 8', 35,  'mag', 'weapon', 46, 66, 0, 1660, 0, 0, 0, 0, 0),
(1109, 'Kij 9', 40,  'mag', 'weapon', 52, 74, 0, 2750, 0, 0, 0, 0, 0),
(1110, 'Kij 10', 45,  'mag', 'weapon', 58, 82, 0, 4530, 0, 0, 0, 0, 0),
(1111, 'Kij 11', 50,  'mag', 'weapon', 64, 90, 0, 7480, 0, 0, 0, 0, 0),
(1112, 'Kij 12', 55,  'mag', 'weapon', 70, 98, 0, 12340, 0, 0, 0, 0, 0),
(1113, 'Kij 13', 60,  'mag', 'weapon', 76, 106, 0, 20360, 0, 0, 0, 0, 0),
(1114, 'Kij 14', 65,  'mag', 'weapon', 82, 114, 0, 33600, 0, 0, 0, 0, 0),
(1115, 'Kij 15', 70,  'mag', 'weapon', 88, 122, 0, 55430, 0, 0, 0, 0, 0),
(1116, 'Kij 16', 75,  'mag', 'weapon', 94, 130, 0, 91460, 0, 0, 0, 0, 0),
(1117, 'Kij 17', 80,  'mag', 'weapon', 100, 138, 0, 150910, 0, 0, 0, 0, 0),
(1118, 'Kij 18', 85,  'mag', 'weapon', 106, 146, 0, 249000, 0, 0, 0, 0, 0),
(1119, 'Kij 19', 90,  'mag', 'weapon', 112, 154, 0, 410850, 0, 0, 0, 0, 0),
(1120, 'Kij 20', 95,  'mag', 'weapon', 118, 162, 0, 677900, 0, 0, 0, 0, 0),
(1121, 'Kij 21', 100,  'mag', 'weapon', 124, 170, 0, 1118530, 0, 0, 0, 0, 0),

(1201, 'Miecz 1', 1,  'łotr', 'weapon', 2, 7, 0, 50, 0, 0, 0, 0, 0),
(1202, 'Miecz 2', 5,  'łotr', 'weapon', 5, 9, 0, 85, 0, 0, 0, 0, 0),
(1203, 'Miecz 3', 10,  'łotr', 'weapon', 8, 11, 0, 135, 0, 0, 0, 0, 0),
(1204, 'Miecz 4', 15,  'łotr', 'weapon',10, 14, 0, 225, 0, 0, 0, 0, 0),
(1205, 'Miecz 5', 20,  'łotr', 'weapon', 13, 17, 0, 370, 0, 0, 0, 0, 0),
(1206, 'Miecz 6', 25,  'łotr', 'weapon', 16, 20, 0, 610, 0, 0, 0, 0, 0),
(1207, 'Miecz 7', 30,  'łotr', 'weapon', 19, 23, 0, 1000, 0, 0, 0, 0, 0),
(1208, 'Miecz 8', 35,  'łotr', 'weapon', 22, 26, 0, 1660, 0, 0, 0, 0, 0),
(1209, 'Miecz 9', 40,  'łotr', 'weapon', 25, 29, 0, 2750, 0, 0, 0, 0, 0),
(1210, 'Miecz 10', 45,  'łotr', 'weapon', 28, 32, 0, 4530, 0, 0, 0, 0, 0),
(1211, 'Miecz 11', 50,  'łotr', 'weapon', 31, 35, 0, 7480, 0, 0, 0, 0, 0),
(1212, 'Miecz 12', 55,  'łotr', 'weapon', 34, 38, 0, 12340, 0, 0, 0, 0, 0),
(1213, 'Miecz 13', 60,  'łotr', 'weapon', 37, 41, 0, 20360, 0, 0, 0, 0, 0),
(1214, 'Miecz 14', 65,  'łotr', 'weapon', 40, 44, 0, 33600, 0, 0, 0, 0, 0),
(1215, 'Miecz 15', 70,  'łotr', 'weapon', 43, 47, 0, 55430, 0, 0, 0, 0, 0),
(1216, 'Miecz 16', 75,  'łotr', 'weapon', 46, 50, 0, 91460, 0, 0, 0, 0, 0),
(1217, 'Miecz 17', 80,  'łotr', 'weapon', 49, 53, 0, 150910, 0, 0, 0, 0, 0),
(1218, 'Miecz 18', 85,  'łotr', 'weapon', 52, 56, 0, 249000, 0, 0, 0, 0, 0),
(1219, 'Miecz 19', 90,  'łotr', 'weapon', 55, 59, 0, 410850, 0, 0, 0, 0, 0),
(1220, 'Miecz 20', 95,  'łotr', 'weapon', 58, 62, 0, 677900, 0, 0, 0, 0, 0),
(1221, 'Miecz 21', 100,  'łotr', 'weapon', 61, 65, 0, 1118530, 0, 0, 0, 0, 0),

(1301, 'Obuch 1', 1,  'wojownik', 'weapon', 3, 6, 0, 50, 0, 0, 0, 0, 0),
(1302, 'Obuch 2', 5,  'wojownik', 'weapon', 6, 10, 0, 85, 0, 0, 0, 0, 0),
(1303, 'Obuch 3', 10,  'wojownik', 'weapon',10, 14, 0, 135, 0, 0, 0, 0, 0),
(1304, 'Obuch 4', 15,  'wojownik', 'weapon',14, 18, 0, 225, 0, 0, 0, 0, 0),
(1305, 'Obuch 5', 20,  'wojownik', 'weapon', 18, 22, 0, 370, 0, 0, 0, 0, 0),
(1306, 'Obuch 6', 25,  'wojownik', 'weapon', 22, 26, 0, 610, 0, 0, 0, 0, 0),
(1307, 'Obuch 7', 30,  'wojownik', 'weapon', 26, 30, 0, 1000, 0, 0, 0, 0, 0),
(1308, 'Obuch 8', 35,  'wojownik', 'weapon', 30, 34, 0, 1660, 0, 0, 0, 0, 0),
(1309, 'Obuch 9', 40,  'wojownik', 'weapon', 34, 38, 0, 2750, 0, 0, 0, 0, 0),
(1310, 'Obuch 10', 45,  'wojownik', 'weapon', 38, 42, 0, 4530, 0, 0, 0, 0, 0),
(1311, 'Obuch 11', 50,  'wojownik', 'weapon', 42, 46, 0, 7480, 0, 0, 0, 0, 0),
(1312, 'Obuch 12', 55,  'wojownik', 'weapon', 46, 50, 0, 12340, 0, 0, 0, 0, 0),
(1313, 'Obuch 13', 60,  'wojownik', 'weapon', 50, 54, 0, 20360, 0, 0, 0, 0, 0),
(1314, 'Obuch 14', 65,  'wojownik', 'weapon', 54, 58, 0, 33600, 0, 0, 0, 0, 0),
(1315, 'Obuch 15', 70,  'wojownik', 'weapon', 58, 62, 0, 55430, 0, 0, 0, 0, 0),
(1316, 'Obuch 16', 75,  'wojownik', 'weapon', 62, 66, 0, 91460, 0, 0, 0, 0, 0),
(1317, 'Obuch 17', 80,  'wojownik', 'weapon', 66, 70, 0, 150910, 0, 0, 0, 0, 0),
(1318, 'Obuch 18', 85,  'wojownik', 'weapon', 70, 74, 0, 249000, 0, 0, 0, 0, 0),
(1319, 'Obuch 19', 90,  'wojownik', 'weapon', 74, 78, 0, 410850, 0, 0, 0, 0, 0),
(1320, 'Obuch 20', 95,  'wojownik', 'weapon', 78, 82, 0, 677900, 0, 0, 0, 0, 0),
(1321, 'Obuch 21', 100,  'wojownik', 'weapon', 82, 86, 0, 1118530, 0, 0, 0, 0, 0),

(2100, 'Zbroja 1', 1, 'mag', 'armor', 0, 0, 8, 250, 0, 0, 0, 0, 0),
(2101, 'Zbroja 2', 9, 'mag', 'armor', 0, 0, 15, 440, 0, 0, 0, 0, 0),
(2102, 'Zbroja 3', 18, 'mag', 'armor', 0, 0, 21, 765, 0, 0, 0, 0, 0),
(2103, 'Zbroja 4', 27, 'mag', 'armor', 0, 0, 27, 1340, 0, 0, 0, 0, 0),
(2104, 'Zbroja 5', 36, 'mag', 'armor', 0, 0, 34, 2345, 0, 0, 0, 0, 0),
(2105, 'Zbroja 6', 45,'mag', 'armor', 0, 0, 40, 4100, 0, 0, 0, 0, 0),
(2106, 'Zbroja 7', 54, 'mag', 'armor', 0, 0, 46, 7180, 0, 0, 0, 0, 0),
(2107, 'Zbroja 8', 63, 'mag', 'armor', 0, 0, 53, 12565, 0, 0, 0, 0, 0),
(2108, 'Zbroja 9', 72, 'mag', 'armor', 0, 0, 59, 21990, 0, 0, 0, 0, 0),
(2109, 'Zbroja 10', 81, 'mag', 'armor', 0, 0, 65, 38485, 0, 0, 0, 0, 0),
(2110, 'Zbroja 11', 90, 'mag', 'armor', 0, 0, 71, 67350, 0, 0, 0, 0, 0),
(2111, 'Zbroja 12', 99, 'mag', 'armor', 0, 0, 78, 117860, 0, 0, 0, 0, 0),
(2112, 'Zbroja 13', 105, 'mag', 'armor', 0, 0, 84, 206250, 0, 0, 0, 0, 0),

(2200, 'Zbroja 1', 1, 'łotr', 'armor', 0, 0, 12, 250, 0, 0, 0, 0, 0),
(2201, 'Zbroja 2', 9, 'łotr', 'armor', 0, 0, 21, 440, 0, 0, 0, 0, 0),
(2202, 'Zbroja 3', 18, 'łotr', 'armor', 0, 0, 30, 765, 0, 0, 0, 0, 0),
(2203, 'Zbroja 4', 27, 'łotr', 'armor', 0, 0, 39, 1340, 0, 0, 0, 0, 0),
(2204, 'Zbroja 5', 36, 'łotr', 'armor', 0, 0, 48, 2345, 0, 0, 0, 0, 0),
(2205, 'Zbroja 6', 45,'łotr', 'armor', 0, 0, 57, 4100, 0, 0, 0, 0, 0),
(2206, 'Zbroja 7', 54, 'łotr', 'armor', 0, 0, 66, 7180, 0, 0, 0, 0, 0),
(2207, 'Zbroja 8', 63, 'łotr', 'armor', 0, 0, 75, 12565, 0, 0, 0, 0, 0),
(2208, 'Zbroja 9', 72, 'łotr', 'armor', 0, 0, 84, 21990, 0, 0, 0, 0, 0),
(2209, 'Zbroja 10', 81, 'łotr', 'armor', 0, 0, 93, 38485, 0, 0, 0, 0, 0),
(2210, 'Zbroja 11', 90, 'łotr', 'armor', 0, 0, 102, 67350, 0, 0, 0, 0, 0),
(2211, 'Zbroja 12', 99, 'łotr', 'armor', 0, 0, 111, 117860, 0, 0, 0, 0, 0),
(2212, 'Zbroja 13', 105, 'łotr', 'armor', 0, 0, 120, 206250, 0, 0, 0, 0, 0),

(2300, 'Zbroja 1', 1, 'wojownik', 'armor', 0, 0, 16, 250, 0, 0, 0, 0, 0),
(2301, 'Zbroja 2', 9, 'wojownik', 'armor', 0, 0, 27, 440, 0, 0, 0, 0, 0),
(2302, 'Zbroja 3', 18, 'wojownik', 'armor', 0, 0, 39, 765, 0, 0, 0, 0, 0),
(2303, 'Zbroja 4', 27, 'wojownik', 'armor', 0, 0, 51, 1340, 0, 0, 0, 0, 0),
(2304, 'Zbroja 5', 36, 'wojownik', 'armor', 0, 0, 62, 2345, 0, 0, 0, 0, 0),
(2305, 'Zbroja 6', 45,'wojownik', 'armor', 0, 0, 74, 4100, 0, 0, 0, 0, 0),
(2306, 'Zbroja 7', 54, 'wojownik', 'armor', 0, 0, 86, 7180, 0, 0, 0, 0, 0),
(2307, 'Zbroja 8', 63, 'wojownik', 'armor', 0, 0, 98, 12565, 0, 0, 0, 0, 0),
(2308, 'Zbroja 9', 72, 'wojownik', 'armor', 0, 0, 109, 21990, 0, 0, 0, 0, 0),
(2309, 'Zbroja 10', 81, 'wojownik', 'armor', 0, 0, 121, 38485, 0, 0, 0, 0, 0),
(2310, 'Zbroja 11', 90, 'wojownik', 'armor', 0, 0, 133, 67350, 0, 0, 0, 0, 0),
(2311, 'Zbroja 12', 99, 'wojownik', 'armor', 0, 0, 144, 117860, 0, 0, 0, 0, 0),
(2312, 'Zbroja 13', 105, 'wojownik', 'armor', 0, 0, 156, 206250, 0, 0, 0, 0, 0),

(3100, 'Hełm 1', 6, 'mag', 'helmet', 0, 0, 6, 150, 0, 0, 0, 0, 0),
(3101, 'Hełm 2', 16, 'mag', 'helmet', 0, 0, 11, 260, 0, 0, 0, 0, 0),
(3102, 'Hełm 3', 26, 'mag', 'helmet', 0, 0, 15, 460, 0, 0, 0, 0, 0),
(3103, 'Hełm 4', 36, 'mag', 'helmet', 0, 0, 20, 800, 0, 0, 0, 0, 0),
(3104, 'Hełm 5', 46, 'mag', 'helmet', 0, 0, 24, 1410, 0, 0, 0, 0, 0),
(3105, 'Hełm 6', 56, 'mag', 'helmet', 0, 0, 29, 2460, 0, 0, 0, 0, 0),
(3106, 'Hełm 7', 66, 'mag', 'helmet', 0, 0, 33, 4310, 0, 0, 0, 0, 0),
(3107, 'Hełm 8', 76, 'mag', 'helmet', 0, 0, 38, 7540, 0, 0, 0, 0, 0),
(3108, 'Hełm 9', 86, 'mag', 'helmet', 0, 0, 42, 13200, 0, 0, 0, 0, 0),
(3109, 'Hełm 10', 96, 'mag', 'helmet', 0, 0, 47, 23100, 0, 0, 0, 0, 0),

(3200, 'Hełm 1', 6, 'łotr', 'helmet', 0, 0, 6, 150, 0, 0, 0, 0, 0),
(3201, 'Hełm 2', 16, 'łotr', 'helmet', 0, 0, 11, 260, 0, 0, 0, 0, 0),
(3202, 'Hełm 3', 26, 'łotr', 'helmet', 0, 0, 15, 460, 0, 0, 0, 0, 0),
(3203, 'Hełm 4', 36, 'łotr', 'helmet', 0, 0, 20, 800, 0, 0, 0, 0, 0),
(3204, 'Hełm 5', 46, 'łotr', 'helmet', 0, 0, 24, 1410, 0, 0, 0, 0, 0),
(3205, 'Hełm 6', 56, 'łotr', 'helmet', 0, 0, 29, 2460, 0, 0, 0, 0, 0),
(3206, 'Hełm 7', 66, 'łotr', 'helmet', 0, 0, 33, 4310, 0, 0, 0, 0, 0),
(3207, 'Hełm 8', 76, 'łotr', 'helmet', 0, 0, 38, 7540, 0, 0, 0, 0, 0),
(3208, 'Hełm 9', 86, 'łotr', 'helmet', 0, 0, 42, 13200, 0, 0, 0, 0, 0),
(3209, 'Hełm 10', 96, 'łotr', 'helmet', 0, 0, 47, 23100, 0, 0, 0, 0, 0),

(3300, 'Hełm 1', 6, 'wojownik', 'helmet', 0, 0, 6, 150, 0, 0, 0, 0, 0),
(3301, 'Hełm 2', 16, 'wojownik', 'helmet', 0, 0, 11, 260, 0, 0, 0, 0, 0),
(3302, 'Hełm 3', 26, 'wojownik', 'helmet', 0, 0, 15, 460, 0, 0, 0, 0, 0),
(3303, 'Hełm 4', 36, 'wojownik', 'helmet', 0, 0, 20, 800, 0, 0, 0, 0, 0),
(3304, 'Hełm 5', 46, 'wojownik', 'helmet', 0, 0, 24, 1410, 0, 0, 0, 0, 0),
(3305, 'Hełm 6', 56, 'wojownik', 'helmet', 0, 0, 29, 2460, 0, 0, 0, 0, 0),
(3306, 'Hełm 7', 66, 'wojownik', 'helmet', 0, 0, 33, 4310, 0, 0, 0, 0, 0),
(3307, 'Hełm 8', 76, 'wojownik', 'helmet', 0, 0, 38, 7540, 0, 0, 0, 0, 0),
(3308, 'Hełm 9', 86, 'wojownik', 'helmet', 0, 0, 42, 13200, 0, 0, 0, 0, 0),
(3309, 'Hełm 10', 96, 'wojownik', 'helmet', 0, 0, 47, 23100, 0, 0, 0, 0, 0),

(4100, 'Buty 1', 6, 'mag', 'shoes', 0, 0, 5, 120, 0, 0, 0, 0, 0),
(4101, 'Buty 2', 16, 'mag', 'shoes', 0, 0, 8, 220, 0, 0, 0, 0, 0),
(4102, 'Buty 3', 26, 'mag', 'shoes', 0, 0, 12, 390, 0, 0, 0, 0, 0),
(4103, 'Buty 4', 36, 'mag', 'shoes', 0, 0, 16, 700, 0, 0, 0, 0, 0),
(4104, 'Buty 5', 46, 'mag', 'shoes', 0, 0, 19, 1260, 0, 0, 0, 0, 0),
(4105, 'Buty 6', 56, 'mag', 'shoes', 0, 0, 23, 2270, 0, 0, 0, 0, 0),
(4106, 'Buty 7', 66, 'mag', 'shoes', 0, 0, 26, 4080, 0, 0, 0, 0, 0),
(4107, 'Buty 8', 76, 'mag', 'shoes', 0, 0, 30, 7347, 0, 0, 0, 0, 0),

(4200, 'Buty 1', 6, 'łotr', 'shoes', 0, 0, 5, 120, 0, 0, 0, 0, 0),
(4201, 'Buty 2', 16, 'łotr', 'shoes', 0, 0, 8, 220, 0, 0, 0, 0, 0),
(4202, 'Buty 3', 26, 'łotr', 'shoes', 0, 0, 12, 390, 0, 0, 0, 0, 0),
(4203, 'Buty 4', 36, 'łotr', 'shoes', 0, 0, 16, 700, 0, 0, 0, 0, 0),
(4204, 'Buty 5', 46, 'łotr', 'shoes', 0, 0, 19, 1260, 0, 0, 0, 0, 0),
(4205, 'Buty 6', 56, 'łotr', 'shoes', 0, 0, 23, 2270, 0, 0, 0, 0, 0),
(4206, 'Buty 7', 66, 'łotr', 'shoes', 0, 0, 26, 4080, 0, 0, 0, 0, 0),
(4207, 'Buty 8', 76, 'łotr', 'shoes', 0, 0, 30, 7347, 0, 0, 0, 0, 0),

(4300, 'Buty 1', 6, 'wojownik', 'shoes', 0, 0, 5, 120, 0, 0, 0, 0, 0),
(4301, 'Buty 2', 16, 'wojownik', 'shoes', 0, 0, 8, 220, 0, 0, 0, 0, 0),
(4302, 'Buty 3', 26, 'wojownik', 'shoes', 0, 0, 12, 390, 0, 0, 0, 0, 0),
(4303, 'Buty 4', 36, 'wojownik', 'shoes', 0, 0, 16, 700, 0, 0, 0, 0, 0),
(4304, 'Buty 5', 46, 'wojownik', 'shoes', 0, 0, 19, 1260, 0, 0, 0, 0, 0),
(4305, 'Buty 6', 56, 'wojownik', 'shoes', 0, 0, 23, 2270, 0, 0, 0, 0, 0),
(4306, 'Buty 7', 66, 'wojownik', 'shoes', 0, 0, 26, 4080, 0, 0, 0, 0, 0),
(4307, 'Buty 8', 76, 'wojownik', 'shoes', 0, 0, 30, 7347, 0, 0, 0, 0, 0),

(3, 'Kij C', 12,  'mag', 'weapon', 10, 14, 0, 300),
(4, 'Zbroja A', 1,  'mag', 'armor', 0, 0, 10, 300),
(5, 'Zbroja B', 15,  'mag', 'armor', 0, 0, 20, 400);
(6, 'Zbroja X', 10,  'wojownik', 'armor', 0, 0, 20, 400),
(7, 'Hełm', 1,  'mag', 'helmet', 0, 0, 10, 100);
-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `re_xp` int(11) NOT NULL,
  `re_cash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `locations`
--

INSERT INTO `locations` (`id`, `name`, `type`, `x`, `y`, `re_xp`, `re_cash`) VALUES
(1, 'Arso', 'wioska', 1, 1, 5, 20),
(2, 'Mura', 'miasteczko', 2, 3, 10, 30),
(3, 'Ankun', 'miasto', 4, 2, 15, 50);
(4, 'Rodos', 'stolica', 4, 4, 20, 80);
(5, 'Patora', 'lochy', 2, 6, 30, 100);
(6, 'Deanos', 'przeklęta wieża', 5, 6, 50, 130);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `title` text COLLATE utf8_polish_ci NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tavern`
--

CREATE TABLE IF NOT EXISTS `tavern` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `hours` int(11) NOT NULL,
  `is_int` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `trips`
--

CREATE TABLE IF NOT EXISTS `trips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT '1',
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `dis` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(40) CHARACTER SET utf8 NOT NULL,
  `login` varchar(25) CHARACTER SET utf8 NOT NULL,
  `class` varchar(25) CHARACTER SET utf8 NOT NULL,
  `status` int(100) NOT NULL DEFAULT '0',
  `avatar` int(1) NOT NULL DEFAULT '0',
  `guild` int(11) NOT NULL DEFAULT '0',
  `guild_priv` int(11) NOT NULL DEFAULT '0',
  `energy` int(100) NOT NULL DEFAULT '100',
  `ap` int(100) NOT NULL DEFAULT '10',
  `lvl` int(100) NOT NULL DEFAULT '1',
  `xp` int(100) NOT NULL DEFAULT '0',
  `max_xp` int(100) NOT NULL DEFAULT '30',
  `all_xp` int(100) NOT NULL DEFAULT '0',
  `cash` int(100) NOT NULL DEFAULT '100',
  `diamonds` int(100) NOT NULL DEFAULT '5',
  `sta` int(100) NOT NULL DEFAULT '1',
  `str` int(100) NOT NULL DEFAULT '1',
  `dex` int(100) NOT NULL DEFAULT '1',
  `intell` int(100) NOT NULL DEFAULT '1',
  `luck` int(100) NOT NULL DEFAULT '1',
  `sp` int(100) NOT NULL DEFAULT '10',
  `pos_x` int(11) NOT NULL DEFAULT '4',
  `pos_y` int(11) NOT NULL DEFAULT '1',
  `last_comment` int(11) NOT NULL DEFAULT '0',
  `last_action` int(11) NOT NULL DEFAULT '0',
  `prest` int(100) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `hours` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

--
-- Struktura tabeli dla tabeli `monsters`
--

CREATE TABLE IF NOT EXISTS `monsters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL,
  `lid` int(11) NOT NULL DEFAULT 0,
  `lvl` int(100) NOT NULL DEFAULT '1',
  `class` varchar(25) CHARACTER SET utf8 NOT NULL,
  `sta` int(100) NOT NULL DEFAULT '1',
  `str` int(100) NOT NULL DEFAULT '1',
  `dex` int(100) NOT NULL DEFAULT '1',
  `intell` int(100) NOT NULL DEFAULT '1',
  `luck` int(100) NOT NULL DEFAULT '1',
  `xp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------