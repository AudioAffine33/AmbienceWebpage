-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 21. Apr 2014 um 14:31
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Daten für Tabelle `format`
--

INSERT INTO `format` (`id`, `codec`, `bitdepth`, `samplerate`, `bitrate`, `channels`) VALUES
(1, 'riff', 24, 48000, 2304000, 2),
(2, 'mp3', NULL, 44100, 262080, 2),
(3, 'riff', 24, 96000, 4608000, 2),
(4, 'riff', 16, 16000, 256000, 1),
(5, 'mp3', NULL, 44100, 128000, 2),
(35, 'mp4', 16, 44100, 65628, 2);

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
