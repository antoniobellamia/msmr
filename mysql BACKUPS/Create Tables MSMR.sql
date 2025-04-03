-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 03, 2025 alle 16:43
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

-- --------------------------------------------------------

--
-- Struttura della tabella `corriere`
--

CREATE TABLE `corriere` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `tipo` enum('admin','dipendente') NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `Copertura` enum('Nord-ovest','Nord-est','Centro','Sud','Isole','Completa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `magazzino`
--

CREATE TABLE `magazzino` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cod_istat` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `mezzo`
--

CREATE TABLE `mezzo` (
  `id` int(11) NOT NULL,
  `targa` varchar(20) NOT NULL,
  `costruttore` varchar(50) DEFAULT NULL,
  `modello` varchar(50) DEFAULT NULL,
  `potenza` int(11) DEFAULT NULL,
  `volume` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `id` int(11) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `istr_consegna` text DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  `id_utente_mitt` int(11) NOT NULL,
  `num_spediz` varchar(50) NOT NULL,
  `id_utente_dest` int(11) NOT NULL,
  `note_corriere` text DEFAULT NULL,
  `id_corriere` int(11) NOT NULL,
  `isReso` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `parcheggi`
--

CREATE TABLE `parcheggi` (
  `id_mag` int(11) NOT NULL,
  `id_mezz` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `stato`
--

CREATE TABLE `stato` (
  `id` int(11) NOT NULL,
  `progressivo` int(11) UNSIGNED NOT NULL,
  `stato` enum('Ordine ricevuto','Ritirato dal corriere','In transito','In lavorazione','In attesa di sdoganamento','Problema con la consegna','Spedizione rifiutata - verrà restituita al venditore','In arrivo','In consegna','Tentativo di Consegna Fallito','Consegnato') NOT NULL DEFAULT 'Ordine ricevuto',
  `informazioni` text DEFAULT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `id_ordine` int(11) NOT NULL,
  `id_magaz` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `uso_mezzi`
--

CREATE TABLE `uso_mezzi` (
  `id_corr` int(11) NOT NULL,
  `id_mezz` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `tipo` enum('mittente','cliente','admin') NOT NULL,
  `cod_istat` varchar(12) NOT NULL,
  `cap` varchar(10) NOT NULL,
  `indirizzo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `corriere`
--
ALTER TABLE `corriere`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indici per le tabelle `magazzino`
--
ALTER TABLE `magazzino`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_istat` (`cod_istat`);

--
-- Indici per le tabelle `mezzo`
--
ALTER TABLE `mezzo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `targa` (`targa`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `num_spediz` (`num_spediz`),
  ADD KEY `id_utente_mitt` (`id_utente_mitt`),
  ADD KEY `id_utente_dest` (`id_utente_dest`),
  ADD KEY `id_corriere` (`id_corriere`);

--
-- Indici per le tabelle `parcheggi`
--
ALTER TABLE `parcheggi`
  ADD PRIMARY KEY (`id_mag`,`id_mezz`,`data`),
  ADD KEY `id_mezz` (`id_mezz`);

--
-- Indici per le tabelle `stato`
--
ALTER TABLE `stato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ordine` (`id_ordine`),
  ADD KEY `id_magaz` (`id_magaz`);

--
-- Indici per le tabelle `uso_mezzi`
--
ALTER TABLE `uso_mezzi`
  ADD PRIMARY KEY (`id_corr`,`id_mezz`,`data`),
  ADD KEY `id_mezz` (`id_mezz`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `cod_istat` (`cod_istat`,`cap`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `corriere`
--
ALTER TABLE `corriere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `magazzino`
--
ALTER TABLE `magazzino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `mezzo`
--
ALTER TABLE `mezzo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `stato`
--
ALTER TABLE `stato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

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
  ADD CONSTRAINT `ordine_ibfk_3` FOREIGN KEY (`id_corriere`) REFERENCES `corriere` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `parcheggi`
--
ALTER TABLE `parcheggi`
  ADD CONSTRAINT `parcheggi_ibfk_1` FOREIGN KEY (`id_mag`) REFERENCES `magazzino` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `parcheggi_ibfk_2` FOREIGN KEY (`id_mezz`) REFERENCES `mezzo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `stato`
--
ALTER TABLE `stato`
  ADD CONSTRAINT `stato_ibfk_1` FOREIGN KEY (`id_ordine`) REFERENCES `ordine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stato_ibfk_2` FOREIGN KEY (`id_magaz`) REFERENCES `magazzino` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `uso_mezzi`
--
ALTER TABLE `uso_mezzi`
  ADD CONSTRAINT `uso_mezzi_ibfk_1` FOREIGN KEY (`id_corr`) REFERENCES `corriere` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `uso_mezzi_ibfk_2` FOREIGN KEY (`id_mezz`) REFERENCES `mezzo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`cod_istat`,`cap`) REFERENCES `geografia`.`gi_cap` (`codice_istat`, `cap`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
