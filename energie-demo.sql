-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Út 27.Jún 2017, 16:02
-- Verzia serveru: 5.7.18-0ubuntu0.16.10.1
-- Verzia PHP: 7.0.15-0ubuntu0.16.10.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `energie-demo`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `ee`
--

CREATE TABLE `ee` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `ee`
--

INSERT INTO `ee` (`id`, `datum`, `rok`, `stav`, `inicial`, `poznamka`) VALUES
(1, '2017-03-31', 2017, 1840, 1, ''),
(2, '2017-04-03', 2017, 1862, 0, ''),
(3, '2017-04-09', 2017, 1895, 0, ''),
(4, '2017-04-14', 2017, 1924, 0, ''),
(5, '2017-04-16', 2017, 1943, 0, ''),
(6, '2017-04-24', 2017, 1999, 0, ''),
(7, '2017-05-01', 2017, 2056, 0, ''),
(8, '2017-05-08', 2017, 2106, 0, ''),
(9, '2017-05-15', 2017, 2154, 0, ''),
(10, '2017-05-22', 2017, 2207, 0, ''),
(11, '2017-05-30', 2017, 2269, 0, ''),
(12, '2017-06-05', 2017, 2318, 0, ''),
(13, '2017-06-12', 2017, 2369, 0, ''),
(14, '2017-06-19', 2017, 2416, 0, ''),
(15, '2017-06-27', 2017, 2462, 0, '');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `plyn`
--

CREATE TABLE `plyn` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `plyn`
--

INSERT INTO `plyn` (`id`, `datum`, `rok`, `stav`, `inicial`, `poznamka`) VALUES
(1, '2017-03-31', 2017, 530, 1, ''),
(2, '2017-04-03', 2017, 535, 0, ''),
(3, '2017-04-09', 2017, 542, 0, ''),
(4, '2017-04-14', 2017, 546, 0, ''),
(5, '2017-04-16', 2017, 548, 0, ''),
(6, '2017-04-24', 2017, 560, 0, ''),
(7, '2017-05-01', 2017, 566, 0, ''),
(8, '2017-05-08', 2017, 571, 0, ''),
(9, '2017-05-15', 2017, 574, 0, ''),
(10, '2017-05-22', 2017, 574, 0, ''),
(11, '2017-05-30', 2017, 576, 0, ''),
(12, '2017-06-05', 2017, 576, 0, ''),
(13, '2017-06-12', 2017, 576, 0, ''),
(14, '2017-06-19', 2017, 576, 0, ''),
(15, '2017-06-27', 2017, 577, 0, 'Od vcera kotol zohrieva vodu');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `pouzivatelia`
--

CREATE TABLE `pouzivatelia` (
  `id` int(11) NOT NULL,
  `meno` text COLLATE utf8_slovak_ci NOT NULL,
  `heslo` text COLLATE utf8_slovak_ci NOT NULL,
  `email` text COLLATE utf8_slovak_ci NOT NULL,
  `skupina` tinyint(4) NOT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL,
  `lastSession` text COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `pouzivatelia`
--

INSERT INTO `pouzivatelia` (`id`, `meno`, `heslo`, `email`, `skupina`, `poznamka`, `lastSession`) VALUES
(1, 'demo', '52678bb28a1aae22b76f4ad56c6478ba', 'demo', 1, '', '570lpnvd35ep98ikgp0mr2bj07');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `tempStat`
--

CREATE TABLE `tempStat` (
  `id` int(11) NOT NULL,
  `user` varchar(10) COLLATE utf8_slovak_ci NOT NULL,
  `stavPlyn` int(11) NOT NULL,
  `sumDniPlyn` int(11) NOT NULL,
  `stavEE` int(11) NOT NULL,
  `sumDniEE` int(11) NOT NULL,
  `stavVoda` int(11) NOT NULL,
  `sumDniVoda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `tempStat`
--

INSERT INTO `tempStat` (`id`, `user`, `stavPlyn`, `sumDniPlyn`, `stavEE`, `sumDniEE`, `stavVoda`, `sumDniVoda`) VALUES
(1, 'demo', 47, 88, 622, 88, 0, 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `udajePlatieb`
--

CREATE TABLE `udajePlatieb` (
  `id` int(11) NOT NULL,
  `druh` text COLLATE utf8_slovak_ci NOT NULL,
  `zakCislo` bigint(12) NOT NULL,
  `platba` text COLLATE utf8_slovak_ci NOT NULL,
  `tarifa` text COLLATE utf8_slovak_ci NOT NULL,
  `ucty` text COLLATE utf8_slovak_ci NOT NULL,
  `VS` bigint(12) NOT NULL,
  `KS` int(11) NOT NULL,
  `EIC` text COLLATE utf8_slovak_ci NOT NULL,
  `odberMiesto` bigint(12) NOT NULL,
  `miestoSpotreby` bigint(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `udajePlatieb`
--

INSERT INTO `udajePlatieb` (`id`, `druh`, `zakCislo`, `platba`, `tarifa`, `ucty`, `VS`, `KS`, `EIC`, `odberMiesto`, `miestoSpotreby`) VALUES
(1, 'Plyn', 321354, '33e', 'DD2', 'SK91 1100 0000 0026 2184 4555', 3210241, 321, '3.543', 3541357, 354131),
(2, 'EE', 64321, '31e', 'DD2', 'VUB SK91 0200 0000 0000 0070 2432', 65413, 684321, '24ZSS0000000000D', 354, 6843),
(3, 'Koncesie', 943576321, '4,64e', 'mesaÄne', 'SK86 8180 0000 0070 0033 3333', 987654321, 558, '0', 0, 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `voda`
--

CREATE TABLE `voda` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `ee`
--
ALTER TABLE `ee`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `plyn`
--
ALTER TABLE `plyn`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `pouzivatelia`
--
ALTER TABLE `pouzivatelia`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indexy pre tabuľku `tempStat`
--
ALTER TABLE `tempStat`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexy pre tabuľku `udajePlatieb`
--
ALTER TABLE `udajePlatieb`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `voda`
--
ALTER TABLE `voda`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `ee`
--
ALTER TABLE `ee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pre tabuľku `plyn`
--
ALTER TABLE `plyn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pre tabuľku `pouzivatelia`
--
ALTER TABLE `pouzivatelia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pre tabuľku `udajePlatieb`
--
ALTER TABLE `udajePlatieb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pre tabuľku `voda`
--
ALTER TABLE `voda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
