-- Copyright (c) 2020 Matthew Rossi
--
-- Permission is hereby granted, free of charge, to any person obtaining a copy of
-- this software and associated documentation files (the "Software"), to deal in
-- the Software without restriction, including without limitation the rights to
-- use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
-- the Software, and to permit persons to whom the Software is furnished to do so,
-- subject to the following conditions:
--
-- The above copyright notice and this permission notice shall be included in all
-- copies or substantial portions of the Software.
--
-- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
-- IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
-- FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
-- COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
-- IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
-- CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Giu 23, 2014 alle 08:28
-- Versione del server: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_dottore`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `domanda`
--

CREATE TABLE IF NOT EXISTS `domanda` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domanda` varchar(255) NOT NULL,
  `id_sintomo` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sintomo` (`id_sintomo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `domanda`
--

INSERT INTO `domanda` (`id`, `domanda`, `id_sintomo`) VALUES
(1, 'La sua temperatura corporea e'' alta o normale?', 1),
(2, 'Ha dei dolori allo stomaco?', 2),
(3, 'Qual e'' la sua pressione massima e minima?', 3),
(4, 'Ha una tosse secca?', 4),
(5, 'Ha una tosse grassa?', 5),
(6, 'La sua voce ha la solita tonalita'' o e'' roca?', 16),
(7, 'La gola presenta delle placche?', 6),
(8, 'La gola ha un colore rossastro o ha la solita colorazione?', 7),
(9, 'Ha difficolta'' nella digestione o il tutto avviene normalmente?', 8),
(10, 'Prova dolore al padiglione auricolare?', 9),
(11, 'Ha appetito?', 10),
(12, 'Ha delle bolle biancastre sul corpo?', 11),
(13, 'Ha prurito?', 12),
(14, 'La sua pelle ha il solito colore o presenta degli arrossamenti?', 13),
(15, 'Il suo orecchio e'' gonfio?', 14),
(16, 'Le viene da vomitare?', 15);

-- --------------------------------------------------------

--
-- Struttura della tabella `expreg`
--

CREATE TABLE IF NOT EXISTS `expreg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pattern` varchar(255) NOT NULL,
  `affetto` tinyint(1) NOT NULL,
  `id_domanda` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_domanda` (`id_domanda`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dump dei dati per la tabella `expreg`
--

INSERT INTO `expreg` (`id`, `pattern`, `affetto`, `id_domanda`) VALUES
(1, 'alta', 1, 1),
(2, 'normale', 0, 1),
(3, 'si', 1, 2),
(4, 'no', 0, 2),
(5, 'si', 1, 5),
(6, 'no', 0, 5),
(7, 'roca', 1, 6),
(8, 'normale', 0, 6),
(9, 'si', 1, 7),
(10, 'no', 0, 7),
(11, 'colore rossastro', 1, 8),
(12, 'solita', 0, 8),
(13, 'digestione', 1, 9),
(14, 'normalmente', 0, 9),
(15, 'si', 1, 10),
(16, 'no', 0, 10),
(17, 'si', 1, 11),
(18, 'no', 0, 11),
(19, 'si', 1, 12),
(20, 'no', 0, 12),
(21, 'si', 1, 13),
(22, 'no', 0, 13),
(23, 'arrosamenti', 1, 14),
(24, 'solito', 0, 14),
(25, 'si', 1, 15),
(26, 'no', 0, 15),
(27, 'si', 1, 16),
(28, 'no', 0, 16);

-- --------------------------------------------------------

--
-- Struttura della tabella `identifica`
--

CREATE TABLE IF NOT EXISTS `identifica` (
  `id_sintomo` int(10) unsigned NOT NULL,
  `id_malattia` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_sintomo`,`id_malattia`),
  KEY `identifica_id_malattia_chk` (`id_malattia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `identifica`
--

INSERT INTO `identifica` (`id_sintomo`, `id_malattia`) VALUES
(1, 1),
(2, 1),
(10, 1),
(15, 1),
(2, 2),
(5, 2),
(16, 2),
(1, 3),
(6, 3),
(7, 3),
(2, 4),
(8, 4),
(16, 4),
(1, 5),
(9, 5),
(1, 6),
(10, 6),
(11, 6),
(12, 6),
(11, 7),
(12, 7),
(13, 7),
(14, 7),
(1, 8),
(14, 8),
(15, 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `malattia`
--

CREATE TABLE IF NOT EXISTS `malattia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `descrizione` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `malattia`
--

INSERT INTO `malattia` (`id`, `nome`, `descrizione`) VALUES
(1, 'Influenza', 'Malattia infettiva causata da virus RNA della famiglia degli Orthomyxoviridae'),
(2, 'Bronchite', 'Infiammazione dei bronchi'),
(3, 'Tonsillite', 'E'' un''infiammazione delle tonsille, sindrome in cui troviamo la flogosi della Faringe'),
(4, 'Ulcera', 'Provoca forti dolori allo stomaco'),
(5, 'Otite', 'L''otite e'' un''infiammazione a carico dell''orecchio. Puo'' avere decorso acuto o cronico'),
(6, 'Varicella', 'E'' una malattia esantematica, infettiva ed epidemica'),
(7, 'Eczema', 'E'' per definizione una reazione dermica infiammatoria pruriginosa e non contagiosa'),
(8, 'Parotite', 'Piu'' conosciuta con il nome popolare di orecchioni, e'' una malattia infettiva acuta contagiosa');

-- --------------------------------------------------------

--
-- Struttura della tabella `paziente`
--

CREATE TABLE IF NOT EXISTS `paziente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cf` varchar(16) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `data_nascita` date NOT NULL,
  `luogo_nascita` varchar(50) NOT NULL,
  `indirizzo` varchar(100) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `id_utente` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utente` (`id_utente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `paziente`
--

INSERT INTO `paziente` (`id`, `cf`, `nome`, `cognome`, `data_nascita`, `luogo_nascita`, `indirizzo`, `provincia`, `id_utente`) VALUES
(1, 'CGNNME12B02A794W', 'Nome', 'Cognome', '2012-07-02', 'Roma', 'Via Quattro Novembre, 149', 'Roma', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `presenta`
--

CREATE TABLE IF NOT EXISTS `presenta` (
  `id_visita` int(10) unsigned NOT NULL,
  `id_sintomo` int(10) unsigned NOT NULL,
  `affetto` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_visita`,`id_sintomo`),
  KEY `presenta_id_sintomo_chk` (`id_sintomo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `presenta`
--

INSERT INTO `presenta` (`id_visita`, `id_sintomo`, `affetto`) VALUES
(14, 1, 1),
(14, 2, 0),
(14, 15, 1),
(15, 2, 1),
(15, 8, 0),
(15, 16, 1),
(16, 1, 0),
(16, 2, 1),
(16, 8, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `sintomo`
--

CREATE TABLE IF NOT EXISTS `sintomo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `descrizione` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `sintomo`
--

INSERT INTO `sintomo` (`id`, `nome`, `descrizione`) VALUES
(1, 'Febbre', 'La temperatura supera i 37C'),
(2, 'Mal di stomaco', 'Disturbo gastrico che provoca dolore alla zona addominale alta'),
(3, 'Pressione alta', 'La pressione minima supera i 90 e la massima i 140 battiti al secondo'),
(4, 'Tosse secca', 'I colpi di tosse non indicano la presenza di catarro'),
(5, 'Tosse grassa', 'La tosse mostra la presenza di catarro che rende difficoltosa la respirazione'),
(6, 'Placche', 'Nella gola si formano delle placche di colore giallognolo'),
(7, 'Gola arrossata', 'Il colore della gola diventa rosso vivo'),
(8, 'Difficolta'' digestive', 'Lo stomaco risulta essere lento nella fase digestiva'),
(9, 'Dolore al padiglione auricolare', 'L''orecchia sembra soggetta a forte pressione'),
(10, 'Mancanza di appetito', 'Il paziente non sente il bisogno di mangiare'),
(11, 'Vesciche', 'Il corpo e'' puntellato di vesciche'),
(12, 'Prurito', 'Si percepisce la neccessita'' di grattarsi nelle zone interessate'),
(13, 'Arrossamento', 'Alcune zone epiteliali del corpo del paziente sono irritate'),
(14, 'Gonfiore all''orecchio', 'Le ghiandole sottostanti all''orecchio si gonfiano'),
(15, 'Nausea', 'Senso di malessere allo stoma che porta spesso a vomitare'),
(16, 'Voce roca', 'La voce assume delle tonalita'' basse nei casi piu'' gravi impercettibili');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE IF NOT EXISTS `utente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(36) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `residenza` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `stato` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `username`, `password`, `nome`, `cognome`, `data_nascita`, `residenza`, `email`, `stato`) VALUES
(1, 'admin', 'admin', 'nome', 'cognome', NULL, NULL, 'email@example.com', 'confermato'),
(6, 'test', 'test', 'test', 'test', NULL, NULL, 'test@test.it', 'attesa');

-- --------------------------------------------------------

--
-- Struttura della tabella `visita`
--

CREATE TABLE IF NOT EXISTS `visita` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `id_paziente` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_paziente` (`id_paziente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dump dei dati per la tabella `visita`
--

INSERT INTO `visita` (`id`, `data`, `id_paziente`) VALUES
(14, '2014-06-22', 1),
(15, '2014-06-22', 1),
(16, '2014-06-23', 1);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `domanda`
--
ALTER TABLE `domanda`
  ADD CONSTRAINT `domanda_id_sintomo_chk` FOREIGN KEY (`id_sintomo`) REFERENCES `sintomo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `expreg`
--
ALTER TABLE `expreg`
  ADD CONSTRAINT `expreg_id_domanda_chk` FOREIGN KEY (`id_domanda`) REFERENCES `domanda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `identifica`
--
ALTER TABLE `identifica`
  ADD CONSTRAINT `identifica_id_malattia_chk` FOREIGN KEY (`id_malattia`) REFERENCES `malattia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `identifica_id_sintomo_chk` FOREIGN KEY (`id_sintomo`) REFERENCES `sintomo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `paziente`
--
ALTER TABLE `paziente`
  ADD CONSTRAINT `paziente_id_utente_chk` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `presenta`
--
ALTER TABLE `presenta`
  ADD CONSTRAINT `presenta_id_sintomo_chk` FOREIGN KEY (`id_sintomo`) REFERENCES `sintomo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presenta_id_visita_chk` FOREIGN KEY (`id_visita`) REFERENCES `visita` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `visita`
--
ALTER TABLE `visita`
  ADD CONSTRAINT `visita_id_paziente_chk` FOREIGN KEY (`id_paziente`) REFERENCES `paziente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
