-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Mai 2014 um 19:30
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
  `name` varchar(80) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `picture` varchar(50) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `date_added` date NOT NULL,
  `originator` varchar(100) DEFAULT NULL,
  `downloaded` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Daten für Tabelle `ambience`
--

INSERT INTO `ambience` (`id`, `format_id`, `filename`, `size`, `length`, `name`, `user_id`, `location_id`, `date`, `time`, `description`, `category_id`, `picture`, `rating`, `date_added`, `originator`, `downloaded`) VALUES
(13, 36, '13_birds_various_28_01_birds__various_count.wav', 29520914, 150, 'BIRDS VARIOUS', 4, 52, '2012-09-04', '01:00:00', 'BIRDS, VARIOUS COUNTRY AMBIENCE, EARLY MORNING, RESIDENTIAL', 2, '13_birds_various_28_01_birds__various_count.jpg', 1.5, '2014-04-22', 'NetMixPro', 2),
(14, 36, '14_city_heavy_17_city__heavy_traffic_and_ci.wav', 46492132, 242, 'City Heavy', 4, 53, '2005-05-02', '01:00:00', 'CITY, HEAVY TRAFFIC AND CITY RUMBLE FROM BALCONY AMBIENCE', 1, '14_city_heavy_17_city__heavy_traffic_and_ci.jpg', NULL, '2014-04-22', 'NetMixPro', 0),
(16, 36, '16_city_medium_06_city__medium_traffic_and_.wav', 49466226, 244, 'City Medium', 4, 54, '2005-05-02', '12:00:00', 'CITY, MEDIUM TRAFFIC AND PEDESTRIANS, CITY RUMBLE AMBIENCE', 1, '16_city_medium_06_city__medium_traffic_and_.jpg', 4, '2014-04-22', 'NetMixPro', 0),
(17, 37, '17_country_ambience_06_country__ambience_co.wav', 6770716, 69, 'Country Ambience', 4, 55, '2005-05-02', '01:00:00', 'COUNTRY, AMBIENCE COUNTRY: BIRDS, COWS, ROOSTER, TRACTOR IN B/G', 2, '17_country_ambience_06_country__ambience_co.jpg', NULL, '2014-04-22', 'NetMixPro', 0),
(19, 36, '19_park_city_03_park__city_city_rumble__fou.wav', 49044920, 242, 'PARK CITY', 4, 50, '2005-05-03', '01:00:00', 'PARK, CITY CITY RUMBLE, FOUNTAIN, PEDESTRIANS AMBIENCE', 1, '19_park_city_03_park__city_city_rumble__fou.jpg', NULL, '2014-04-22', 'NetMixPro', 0),
(20, 36, '20_mountain_day_11_mountain__day_wind_throu.wav', 48144882, 245, 'Mountain Day', 4, 51, '2005-05-03', '01:00:00', 'MOUNTAIN, DAY WIND THROUGH TREES, BIRD CHIRPS AND CAWS AMBIENCE', 1, '20_mountain_day_11_mountain__day_wind_throu.jpg', NULL, '2014-04-22', 'NetMixPro', 0),
(60, 36, '60_city_medium_06_city__medium_traffic_and_.wav', 49466226, 244, 'Walk Of Fame', 4, 61, '2005-05-02', '12:00:00', 'CITY, MEDIUM TRAFFIC AND PEDESTRIANS, CITY RUMBLE AMBIENCE', 1, '60_city_medium_06_city__medium_traffic_and_.jpg', NULL, '2014-04-22', 'NetMixPro', 0),
(81, 36, '81_city_heavy_17_city__heavy_traffic_and_ci.wav', 46492132, 242, 'City Heavy 17 CITY, HEAVY TRAFFIC AND CITY RUMBLE ', 3, 68, '2005-05-02', '01:00:00', 'CITY, HEAVY TRAFFIC AND CITY RUMBLE FROM BALCONY AMBIENCE', 1, '81_city_heavy_17_city__heavy_traffic_and_ci.jpg', NULL, '2014-05-11', 'NetMixPro', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(0, ''),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

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
  `continent` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `land` varchar(40) NOT NULL,
  `latitude` decimal(18,14) NOT NULL,
  `longitude` decimal(18,14) NOT NULL,
  `countrycode` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

--
-- Daten für Tabelle `location`
--

INSERT INTO `location` (`id`, `continent`, `name`, `land`, `latitude`, `longitude`, `countrycode`) VALUES
(1, 'dummy', 'dummy', 'dummy', '0.00000000000000', '0.00000000000000', 'dummy'),
(50, 'Europe', 'Marseille', 'Frankreich', '43.29648200000000', '5.36978000000000', 'FR'),
(51, 'North America', 'Dallas', 'USA', '32.78013990000000', '-96.80045110000000', 'US'),
(52, 'Europe', 'Amberg', 'Deutschland', '49.44031980000000', '11.86334450000000', 'DE'),
(53, 'North America', 'New York City', 'USA', '40.71435280000000', '-74.00597310000000', 'US'),
(54, 'Europe', 'Toky', 'Ukraine', '49.63416670000000', '26.22138890000000', 'UA'),
(55, 'Europe', 'Gambais', 'Frankreich', '48.77384300000000', '1.67579700000000', 'FR'),
(56, 'Europe', 'Nürnberg', 'Deutschland', '49.45203000000000', '11.07675000000000', 'DE'),
(57, 'North America', 'Dallas', 'USA', '32.78013990000000', '-96.80045110000000', 'US'),
(61, 'Europe', 'Brüssel', 'Belgien', '50.85033960000000', '4.35171030000000', 'BE'),
(68, 'Asia', 'Tokio', 'Japan', '35.68948750000000', '139.69170640000000', 'JP'),
(72, 'Europe', 'Nürnberg', 'Deutschland', '49.45203000000000', '11.07675000000000', 'DE'),
(73, 'Europe', 'Amberg', 'Deutschland', '49.44031980000000', '11.86334450000000', 'DE'),
(74, 'Europe', 'Xanten', 'Deutschland', '51.65710810000000', '6.44865040000000', 'DE'),
(75, 'Europe', 'Aschaffenburg', 'Deutschland', '49.98066250000000', '9.13555539999990', 'DE'),
(76, 'North America', 'Kansas CIty', 'USA', '39.09972650000000', '-94.57856670000000', 'US');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `ambience_id`, `rating`) VALUES
(1, 4, 14, 4),
(2, 3, 14, 3),
(3, 5, 14, 5),
(4, 5, 13, 1),
(5, 5, 81, 5),
(6, 4, 13, 2),
(7, 4, 16, 4);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pass` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) NOT NULL,
  `about` varchar(1000) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `rights` varchar(20) NOT NULL,
  `emailShown` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `name`, `pass`, `email`, `about`, `picture`, `rights`, `emailShown`) VALUES
(3, 'Marco', 'f5888d0bb58d611107e11f7cbc41c97a', 'marco@exvision.de', 'Lorem Ipsum', '3_Marco.jpg', 'admin', 0),
(4, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@ambie.nce', 'Do you see any Teletubbies in here? Do you see a slender plastic tag clipped to my shirt with my name printed on it? Do you see a little Asian child with a blank expression on his face sitting outside on a mechanical helicopter that shakes when you put quarters in it? No? Well, that''s what you see at a toy store. And you must think you''re in a toy store, because you''re here shopping for an infant named Jeb.', '', 'admin', 0),
(5, 'sebastian', 'c2d628ba98ed491776c9335e988e2e3b', 'sebastian@sebastian.de', 'wort tulip glass saccharification aerobic. grainy, all-malt krausen additive primary fermentation; mash length lagering aau alcohol goblet. shelf life; enzymes cold filter krausen, " trappist acid rest grainy hydrometer enzymes balthazar." balthazar, malt lauter sparge specific gravity lambic length. draft (draught) carboy grainy alpha acid seidel, units of bitterness? grainy filter ale anaerobic balthazar; carboy terminal gravity wort alpha acid. units of bitterness bottle conditioning; mead kr', NULL, 'user', 0),
(6, 'markus', '23c496d2ee2494b3f380a2bd7380b811', 'markus@markus.de', 'Zombie ipsum brains reversus ab cerebellum viral inferno, brein nam rick mend grimes malum cerveau cerebro. De carne cerebro lumbering animata cervello corpora quaeritis. Summus thalamus brains sit​​, morbo basal ganglia vel maleficia? De braaaiiiins apocalypsi gorger omero prefrontal cortex undead survivor fornix dictum mauris. Hi brains mindless mortuis limbic cortex soulless creaturas optic nerve, imo evil braaiinns stalking monstra hypothalamus adventus resi hippocampus dentevil vultus brain', NULL, 'user', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
