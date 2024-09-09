-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 31.14.141.149
-- Creato il: Ago 01, 2024 alle 13:20
-- Versione del server: 10.5.8-MariaDB
-- Versione PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `test`;

-- --------------------------------------------------------

--
-- Struttura della tabella `pagine`
--

CREATE TABLE `pagine` (
  `id_page` int(11) NOT NULL,
  `nome` tinytext CHARACTER SET utf8 NOT NULL,
  `url` tinytext CHARACTER SET utf8 NOT NULL,
  `pos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `pagine`
--

INSERT INTO `pagine` (`id_page`, `nome`, `url`, `pos`) VALUES
(1, 'Home', 'index', 1),
(2, 'Contatti', 'contatti', 2),
(6, 'Links', 'links', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `testi`
--

CREATE TABLE `testi` (
  `id_par` int(11) NOT NULL,
  `titolo` tinytext DEFAULT NULL,
  `testo` text DEFAULT NULL,
  `imm` tinytext DEFAULT NULL,
  `pos_par` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `testi`
--

INSERT INTO `testi` (`id_par`, `titolo`, `testo`, `imm`, `pos_par`, `page_id`) VALUES
(1, 'Benvenuti nel nostro sito', '<p>Il nostro sito offre una vasta gamma di risorse educative e materiali informativi. Abbiamo articoli, video e guide su vari argomenti, che vanno dalla tecnologia alla salute, passando per l&#39;arte e la cultura.</p>\r\n\r\n<p>La nostra missione &egrave; fornire contenuti di alta qualit&agrave; che possano aiutare le persone a migliorare le loro competenze e conoscenze. Crediamo nell&#39;importanza dell&#39;educazione continua e ci impegniamo a rendere l&#39;apprendimento accessibile a tutti.</p>\r\n\r\n<p>E aggiungo un&#39;altra riga di testo</p>\r\n', 'ricette.jpg', 1, 1),
(2, 'Scrivici', '<p>Per contatti o richieste siete pregati di scegliere una delle strutture di seguito elencate e di compilare il relativo modulo di contatti</p>\r\n<p>Se hai domande o suggerimenti, non esitare a contattarci. Siamo sempre felici di ricevere feedback dai nostri utenti e di migliorare costantemente i nostri servizi. Grazie per aver visitato il nostro sito e speriamo che troviate le nostre risorse utili e interessanti.</p>', 'integratori.jpg', 1, 2),
(3, 'Google', '<p><a href=\"https://www.google.it\" target=\"_blank\">Vai al sito di Google</a></p>\r\n', NULL, 1, 6),
(4, 'Apple', '<p><a href=\"https://www.apple.it\" target=\"_blank\">Vai al sito Apple</a></p>\r\n\r\n', NULL, 2, 6),
(5, 'Linkedin', '<p><a href=\"https://it.linkedin.com/\" target=\"_blank\">Vai al sito Linkedin </a></p>\r\n', NULL, 3, 6);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `pagine`
--
ALTER TABLE `pagine`
  ADD PRIMARY KEY (`id_page`);

--
-- Indici per le tabelle `testi`
--
ALTER TABLE `testi`
  ADD PRIMARY KEY (`id_par`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `pagine`
--
ALTER TABLE `pagine`
  MODIFY `id_page` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `testi`
--
ALTER TABLE `testi`
  MODIFY `id_par` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
