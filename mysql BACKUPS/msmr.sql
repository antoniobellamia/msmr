-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 28, 2025 alle 16:09
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `msmr`
--
CREATE DATABASE IF NOT EXISTS `msmr` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `msmr`;

-- --------------------------------------------------------

--
-- Struttura della tabella `abbonamento`
--
-- Creazione: Apr 28, 2025 alle 14:07
-- Ultimo aggiornamento: Apr 28, 2025 alle 13:56
--

DROP TABLE IF EXISTS `abbonamento`;
CREATE TABLE IF NOT EXISTS `abbonamento` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `creato` date NOT NULL DEFAULT current_timestamp(),
  `scaduto` tinyint(1) NOT NULL DEFAULT 0,
  `tariffa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utente` (`id_utente`),
  KEY `tariffa` (`tariffa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `corriere`
--
-- Creazione: Apr 28, 2025 alle 14:07
-- Ultimo aggiornamento: Apr 28, 2025 alle 13:56
--

DROP TABLE IF EXISTS `corriere`;
CREATE TABLE IF NOT EXISTS `corriere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `tipo` enum('admin','dipendente') NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `copertura` enum('Nord-ovest','Nord-est','Centro','Sud','Isole','Assoluta') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `corriere`
--

INSERT INTO `corriere` (`id`, `username`, `password`, `tipo`, `telefono`, `copertura`) VALUES
(1, 'corryCapo', '1a1dc91c907325c69271ddf0c944bc72', 'admin', '+0390000000000', 'Assoluta');

-- --------------------------------------------------------

--
-- Struttura della tabella `info`
--
-- Creazione: Apr 28, 2025 alle 13:46
--

DROP TABLE IF EXISTS `info`;
CREATE TABLE IF NOT EXISTS `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_stato` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `info`
--

INSERT INTO `info` (`id`, `nome_stato`) VALUES
(1, 'Ordine ricevuto'),
(2, 'Ritirato dal corriere'),
(3, 'In transito'),
(4, 'In lavorazione'),
(5, 'In attesa di sdoganamento'),
(6, 'Problema con la consegna'),
(7, 'Spedizione rifiutata. Verrà restituita al venditor'),
(8, 'In arrivo'),
(9, 'In consegna'),
(10, 'Tentativo di Consegna Fallito'),
(11, 'Consegnato'),
(12, 'Spedizione annullata dal venditore'),
(13, 'Reso effettuato. Verrà restituito al venditore');

-- --------------------------------------------------------

--
-- Struttura della tabella `magazzino`
--
-- Creazione: Apr 28, 2025 alle 13:46
--

DROP TABLE IF EXISTS `magazzino`;
CREATE TABLE IF NOT EXISTS `magazzino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cod_istat` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`) USING BTREE,
  UNIQUE KEY `cod_istat` (`cod_istat`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `magazzino`
--

INSERT INTO `magazzino` (`id`, `nome`, `cod_istat`) VALUES
(1, 'Magazzino Centrale di Milano', '015146'),
(2, 'Magazzino di San Fiorano', '098047'),
(3, 'Magazzino di Torino', '001272'),
(4, 'Magazzino di Genova', '010025'),
(5, 'Magazzino di Aosta', '007003'),
(6, 'Magazzino Centrale di Verona', '023091'),
(7, 'Magazzino di Bologna', '037006'),
(8, 'Magazzino di Padova', '028060'),
(9, 'Magazzino di Trieste', '032006'),
(10, 'Magazzino di Trento', '022205'),
(11, 'Magazzino Centrale di Roma', '058091'),
(12, 'Magazzino di Firenze', '048017'),
(13, 'Magazzino di Ancona', '042002'),
(14, 'Magazzino di Perugia', '054039'),
(15, 'Magazzino di Arezzo', '051002'),
(16, 'Magazzino Centrale di Napoli', '063049'),
(17, 'Magazzino di Bari', '072006'),
(18, 'Magazzino di Foggia', '071024'),
(19, 'Magazzino di Laterza', '073009'),
(20, 'Magazzino di Irsina', '077013'),
(21, 'Magazzino Centrale di Palermo', '082053'),
(22, 'Magazzino di Catania', '087015'),
(23, 'Magazzino Centrale di Cagliari', '092009'),
(24, 'Magazzino di Sassari', '090064'),
(25, 'Magazzino di Trapani', '081021');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--
-- Creazione: Apr 28, 2025 alle 14:03
-- Ultimo aggiornamento: Apr 28, 2025 alle 13:48
--

DROP TABLE IF EXISTS `ordine`;
CREATE TABLE IF NOT EXISTS `ordine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(50) NOT NULL DEFAULT 'Ordine#',
  `descrizione` text DEFAULT NULL,
  `istr_consegna` text DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  `id_utente_mitt` int(11) NOT NULL,
  `id_utente_dest` int(11) NOT NULL,
  `note_corriere` text DEFAULT NULL,
  `id_corriere` int(11) DEFAULT NULL,
  `isReso` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_utente_mitt` (`id_utente_mitt`),
  KEY `id_utente_dest` (`id_utente_dest`),
  KEY `id_corriere` (`id_corriere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `stato`
--
-- Creazione: Apr 28, 2025 alle 14:07
-- Ultimo aggiornamento: Apr 28, 2025 alle 13:48
--

DROP TABLE IF EXISTS `stato`;
CREATE TABLE IF NOT EXISTS `stato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stato` enum('Ordine ricevuto','Ritirato dal corriere','In transito','In lavorazione','In attesa di sdoganamento','Problema con la consegna','Spedizione rifiutata. Verrà restituita al venditore','In arrivo','In consegna','Tentativo di Consegna Fallito','Consegnato','Spedizione annullata dal venditore','Reso effettuato. Verrà restituito al venditore') NOT NULL DEFAULT 'Ordine ricevuto',
  `informazioni` text DEFAULT 'Nessuna informazione.',
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `id_ordine` int(11) NOT NULL,
  `id_magaz` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_ordine` (`id_ordine`),
  KEY `id_magaz` (`id_magaz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `tariffe`
--
-- Creazione: Apr 28, 2025 alle 13:46
--

DROP TABLE IF EXISTS `tariffe`;
CREATE TABLE IF NOT EXISTS `tariffe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `spedizioni` int(11) NOT NULL COMMENT '-1 = INFINITO',
  `prezzo` int(11) UNSIGNED NOT NULL,
  `mesi` int(11) NOT NULL DEFAULT 12,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tariffe`
--

INSERT INTO `tariffe` (`id`, `nome`, `spedizioni`, `prezzo`, `mesi`) VALUES
(1, 'Basic', 1, 6, 12),
(2, 'Standard', 20, 80, 6),
(3, 'Unlimited', -1, 500, 12);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--
-- Creazione: Apr 28, 2025 alle 14:07
-- Ultimo aggiornamento: Apr 28, 2025 alle 14:03
--

DROP TABLE IF EXISTS `utente`;
CREATE TABLE IF NOT EXISTS `utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `tipo` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `cod_istat` varchar(12) DEFAULT NULL,
  `cap` varchar(10) DEFAULT NULL,
  `indirizzo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `cod_istat` (`cod_istat`,`cap`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `username`, `password`, `telefono`, `cognome`, `nome`, `tipo`, `cod_istat`, `cap`, `indirizzo`) VALUES
(1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', '+0390000000000', 'Bellamia', 'Antonio', 'admin', '073009', '74014', 'Via NullPointer, 0');

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `abbonamento`
--
ALTER TABLE `abbonamento`
  ADD CONSTRAINT `abbonamento_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `abbonamento_ibfk_2` FOREIGN KEY (`tariffa`) REFERENCES `tariffe` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `magazzino`
--
ALTER TABLE `magazzino`
  ADD CONSTRAINT `magazzino_ibfk_1` FOREIGN KEY (`cod_istat`) REFERENCES `geografia`.`gi_cap` (`codice_istat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`id_utente_mitt`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordine_ibfk_2` FOREIGN KEY (`id_utente_dest`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordine_ibfk_3` FOREIGN KEY (`id_corriere`) REFERENCES `corriere` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `stato`
--
ALTER TABLE `stato`
  ADD CONSTRAINT `stato_ibfk_1` FOREIGN KEY (`id_ordine`) REFERENCES `ordine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stato_ibfk_2` FOREIGN KEY (`id_magaz`) REFERENCES `magazzino` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`cod_istat`,`cap`) REFERENCES `geografia`.`gi_cap` (`codice_istat`, `cap`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Eventi
--
DROP EVENT IF EXISTS `aggiorna_abbonamenti_scaduti`$$
CREATE DEFINER=`root`@`localhost` EVENT `aggiorna_abbonamenti_scaduti` ON SCHEDULE EVERY 1 DAY STARTS '2025-04-25 17:25:07' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE abbonamento a
  JOIN tariffe t ON a.tariffa = t.id
  SET a.scaduto = 1
  WHERE a.scaduto = 0
    AND DATE_ADD(a.creato, INTERVAL t.mesi MONTH) < CURDATE()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
