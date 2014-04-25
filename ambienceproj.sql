-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Apr 2014 um 17:34
-- Server Version: 5.6.16
-- PHP-Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `ambienceproj`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ambience`
--

CREATE TABLE IF NOT EXISTS `ambience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format_id` int(11) NOT NULL,
  `filename` varchar(50) CHARACTER SET utf8 NOT NULL,
  `size` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `picture` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `date_added` date NOT NULL,
  `originator` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Daten für Tabelle `ambience`
--

INSERT INTO `ambience` (`id`, `format_id`, `filename`, `size`, `length`, `name`, `user_id`, `location_id`, `date`, `time`, `description`, `category_id`, `picture`, `rating`, `date_added`, `originator`) VALUES
(13, 36, '13_birds_various_28_01_birds__various_count.wav', 29520914, 150, 'BIRDS VARIOUS', 2, 6, '2005-08-04', '01:00:00', 'BIRDS, VARIOUS COUNTRY AMBIENCE, EARLY MORNING, RESIDENTIAL', 2, '13_birds_various_28_01_birds__various_count.jpg', NULL, '2014-04-22', 'NetMixPro'),
(14, 36, '14_city_heavy_17_city__heavy_traffic_and_ci.wav', 46492132, 242, 'City Heavy', 2, 8, '2005-05-02', '01:00:00', 'CITY, HEAVY TRAFFIC AND CITY RUMBLE FROM BALCONY AMBIENCE', 1, '14_city_heavy_17_city__heavy_traffic_and_ci.jpg', NULL, '2014-04-22', 'NetMixPro'),
(16, 36, '16_city_medium_06_city__medium_traffic_and_.wav', 49466226, 244, 'City Medium', 2, 0, '2005-05-02', '12:00:00', 'CITY, MEDIUM TRAFFIC AND PEDESTRIANS, CITY RUMBLE AMBIENCE', 1, '16_city_medium_06_city__medium_traffic_and_.jpg', NULL, '2014-04-22', 'NetMixPro'),
(17, 37, '17_country_ambience_06_country__ambience_co.wav', 6770716, 69, 'Country Ambience', 2, 9, '2005-05-02', '01:00:00', 'COUNTRY, AMBIENCE COUNTRY: BIRDS, COWS, ROOSTER, TRACTOR IN B/G', 2, '17_country_ambience_06_country__ambience_co.jpg', NULL, '2014-04-22', 'NetMixPro'),
(18, 36, '18_country_day_01_country__day_by_river__cr.wav', 48097674, 245, 'Country Day', 2, 0, '2005-05-03', '01:00:00', 'COUNTRY, DAY BY RIVER, CRICKETS, FROG AMBIENCE', 2, '18_country_day_01_country__day_by_river__cr.jpg', NULL, '2014-04-22', 'NetMixPro'),
(19, 36, '19_park_city_03_park__city_city_rumble__fou.wav', 49044920, 242, 'PARK CITY', 2, 10, '2005-05-03', '01:00:00', 'PARK, CITY CITY RUMBLE, FOUNTAIN, PEDESTRIANS AMBIENCE', 1, '19_park_city_03_park__city_city_rumble__fou.jpg', NULL, '2014-04-22', 'NetMixPro'),
(20, 36, '20_mountain_day_11_mountain__day_wind_throu.wav', 48144882, 245, 'Mountain Day', 2, 11, '2005-05-03', '01:00:00', 'MOUNTAIN, DAY WIND THROUGH TREES, BIRD CHIRPS AND CAWS AMBIENCE', 1, '20_mountain_day_11_mountain__day_wind_throu.jpg', NULL, '2014-04-22', 'NetMixPro'),
(60, 36, '60_city_medium_06_city__medium_traffic_and_.wav', 49466226, 244, 'Walk Of Fame', 2, 7, '2005-05-02', '12:00:00', 'CITY, MEDIUM TRAFFIC AND PEDESTRIANS, CITY RUMBLE AMBIENCE', 1, '60_city_medium_06_city__medium_traffic_and_.jpg', NULL, '2014-04-22', 'NetMixPro');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'City'),
(3, 'Forrest'),
(5, 'Office'),
(2, 'Residential'),
(4, 'Room');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ambience_id` int(11) NOT NULL,
  `inhalt` varchar(500) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `format`
--

CREATE TABLE IF NOT EXISTS `format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codec` varchar(50) CHARACTER SET utf8 NOT NULL,
  `bitdepth` int(11) DEFAULT NULL,
  `samplerate` int(11) NOT NULL,
  `bitrate` int(11) DEFAULT NULL,
  `channels` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Daten für Tabelle `format`
--

INSERT INTO `format` (`id`, `codec`, `bitdepth`, `samplerate`, `bitrate`, `channels`) VALUES
(1, 'riff', 24, 48000, 2304000, 2),
(2, 'mp3', NULL, 44100, 262080, 2),
(3, 'riff', 24, 96000, 4608000, 2),
(4, 'riff', 16, 16000, 256000, 1),
(5, 'mp3', NULL, 44100, 128000, 2),
(35, 'mp4', 16, 44100, 65628, 2),
(36, 'riff', 16, 48000, 1536000, 2),
(37, 'riff', 16, 48000, 768000, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `land` varchar(40) CHARACTER SET utf8 NOT NULL,
  `latitude` decimal(18,14) NOT NULL,
  `longitude` decimal(18,14) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `location`
--

INSERT INTO `location` (`id`, `name`, `land`, `latitude`, `longitude`) VALUES
(6, 'São Paulo', 'Brasilien', '-23.55051990000000', '-46.63330939999997'),
(7, 'Hollywood', 'USA', '26.01120140000000', '-80.14949009999998'),
(8, 'New York City', 'USA', '40.71435280000000', '-74.00597310000000'),
(9, 'Hamburg', 'Deutschland', '53.55108460000000', '9.99368179999999'),
(10, 'New Orleans', 'USA', '29.95106579999999', '-90.07153230000000'),
(11, 'Amberg', 'Deutschland', '49.44031980000000', '11.86334450000004'),
(12, 'Heimbach', 'Deutschland', '50.63289390000000', '6.48394580000002');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ambience_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`ambience_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `melder_id` int(11) NOT NULL,
  `gemeldet_ambience_id` int(11) DEFAULT NULL,
  `gemeldet_kommentar_id` int(11) DEFAULT NULL,
  `gemeldet_user_id` int(11) DEFAULT NULL,
  `grund_kategorie` varchar(30) CHARACTER SET utf8 NOT NULL,
  `grund_freitext` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pass` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `about` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `picture` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `rights` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `name`, `pass`, `email`, `about`, `picture`, `rights`) VALUES
(2, 'Cogan', 'bdfd3e131a520d683a17ee328b649f41', 'marco@lunaarte.de', NULL, NULL, 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
