-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 22. Apr 2014 um 10:14
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
  `filename` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `picture` varchar(50) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `date_added` date NOT NULL,
  `originator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Daten für Tabelle `ambience`
--

INSERT INTO `ambience` (`id`, `format_id`, `filename`, `size`, `length`, `name`, `user_id`, `location`, `date`, `time`, `description`, `category_id`, `picture`, `rating`, `date_added`, `originator`) VALUES
(13, 36, '13_birds_various_28_01_birds__various_count.wav', 29520914, 150, 'BIRDS VARIOUS', 2, NULL, '2005-08-04', '01:00:00', 'BIRDS, VARIOUS COUNTRY AMBIENCE, EARLY MORNING, RESIDENTIAL', 2, '13_birds_various_28_01_birds__various_count.jpg', NULL, '2014-04-22', 'NetMixPro'),
(14, 36, '14_city_heavy_17_city__heavy_traffic_and_ci.wav', 46492132, 242, 'City Heavy', 2, 'Timesquare', '2005-05-02', '01:00:00', 'CITY, HEAVY TRAFFIC AND CITY RUMBLE FROM BALCONY AMBIENCE', 1, '14_city_heavy_17_city__heavy_traffic_and_ci.jpg', NULL, '2014-04-22', 'NetMixPro'),
(15, 36, '15_city_light_01_city__light_traffic_and_pe.wav', 46773410, 244, 'City Light', 2, 'New York', '2005-05-02', '12:00:00', 'CITY, LIGHT TRAFFIC AND PEDESTRIANS, CITY RUMBLE AMBIENCE', 1, '15_city_light_01_city__light_traffic_and_pe.jpg', NULL, '2014-04-22', 'NetMixPro'),
(16, 36, '16_city_medium_06_city__medium_traffic_and_.wav', 49466226, 244, 'City Medium', 2, 'Tokyo', '2005-05-02', '12:00:00', 'CITY, MEDIUM TRAFFIC AND PEDESTRIANS, CITY RUMBLE AMBIENCE', 1, '16_city_medium_06_city__medium_traffic_and_.jpg', NULL, '2014-04-22', 'NetMixPro'),
(17, 37, '17_country_ambience_06_country__ambience_co.wav', 6770716, 69, 'Country Ambience', 2, NULL, '2005-05-02', '01:00:00', 'COUNTRY, AMBIENCE COUNTRY: BIRDS, COWS, ROOSTER, TRACTOR IN B/G', 2, '17_country_ambience_06_country__ambience_co.jpg', NULL, '2014-04-22', 'NetMixPro'),
(18, 36, '18_country_day_01_country__day_by_river__cr.wav', 48097674, 245, 'Country Day', 2, NULL, '2005-05-03', '01:00:00', 'COUNTRY, DAY BY RIVER, CRICKETS, FROG AMBIENCE', 2, '18_country_day_01_country__day_by_river__cr.jpg', NULL, '2014-04-22', 'NetMixPro'),
(19, 36, '19_park_city_03_park__city_city_rumble__fou.wav', 49044920, 242, 'PARK CITY', 2, NULL, '2005-05-03', '01:00:00', 'PARK, CITY CITY RUMBLE, FOUNTAIN, PEDESTRIANS AMBIENCE', 1, '19_park_city_03_park__city_city_rumble__fou.jpg', NULL, '2014-04-22', 'NetMixPro'),
(20, 36, '20_mountain_day_11_mountain__day_wind_throu.wav', 48144882, 245, 'Mountain Day', 2, NULL, '2005-05-03', '01:00:00', 'MOUNTAIN, DAY WIND THROUGH TREES, BIRD CHIRPS AND CAWS AMBIENCE', 1, '20_mountain_day_11_mountain__day_wind_throu.jpg', NULL, '2014-04-22', 'NetMixPro');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
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
  `inhalt` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `format`
--

CREATE TABLE IF NOT EXISTS `format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codec` varchar(50) NOT NULL,
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
  `grund_kategorie` varchar(30) NOT NULL,
  `grund_freitext` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `about` varchar(500) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `rights` varchar(20) NOT NULL,
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
