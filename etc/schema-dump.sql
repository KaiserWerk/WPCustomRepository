-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 24. Mai 2018 um 16:50
-- Server-Version: 5.6.38
-- PHP-Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `db_wpcr`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `api_request`
--

CREATE TABLE `api_request` (
  `id` int(11) NOT NULL,
  `user_entry_id` int(11) DEFAULT NULL,
  `action` varchar(30) DEFAULT NULL,
  `request_headers` text,
  `association_slug` varchar(15) DEFAULT NULL,
  `response` text,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `license`
--

CREATE TABLE `license` (
  `id` int(11) NOT NULL,
  `license_user` varchar(255) NOT NULL,
  `license_key` varchar(200) NOT NULL,
  `license_host` varchar(100) NOT NULL,
  `plugin_slug` varchar(100) NOT NULL,
  `valid_until` datetime NOT NULL,
  `renewals` int(11) NOT NULL DEFAULT '0',
  `auto_renewal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `license`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mail_sent`
--

CREATE TABLE `mail_sent` (
  `id` int(11) NOT NULL,
  `confirmation_token` varchar(120) DEFAULT NULL,
  `recipient` varchar(255) NOT NULL,
  `sent_at` datetime NOT NULL,
  `token_used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mail_tracked`
--

CREATE TABLE `mail_tracked` (
  `id` int(11) NOT NULL,
  `mail_entry_id` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `opened_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin`
--

CREATE TABLE `plugin` (
  `id` int(11) NOT NULL,
  `plugin_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `version` varchar(15) NOT NULL,
  `url` varchar(255) NOT NULL,
  `requires` varchar(15) NOT NULL,
  `tested` varchar(15) NOT NULL,
  `rating5` int(11) NOT NULL,
  `rating4` int(11) NOT NULL,
  `rating3` int(11) NOT NULL,
  `rating2` int(11) NOT NULL,
  `rating1` int(11) NOT NULL,
  `downloaded` bigint(20) NOT NULL,
  `last_updated` datetime NOT NULL,
  `added` datetime NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `section_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `plugin`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin_section`
--

CREATE TABLE `plugin_section` (
  `id` int(11) NOT NULL,
  `plugin_entry_id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `section_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `plugin_section`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `apikey` varchar(50) NOT NULL,
  `confirmation_token` varchar(150) DEFAULT NULL,
  `confirmation_token_validity` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `sex` varchar(1) NOT NULL DEFAULT 'f',
  `locale` varchar(5) NOT NULL DEFAULT 'en',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `first_name`, `last_name`, `password`, `email`, `apikey`, `confirmation_token`, `confirmation_token_validity`, `last_login`, `sex`, `locale`, `admin`, `locked`, `created_at`) VALUES
(1, 'admin', 'Ad', 'Min', '$2y$12$WsU6SHxD.Umc7ca7ZD.ggu5g1A52lsEdZnFBbLfxtvbjNxDTkI5ye', 't@r-k.mx', '', NULL, NULL, '2018-05-24 14:57:40', 'm', 'en', 1, 0, '2018-01-31 15:30:35');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `api_request`
--
ALTER TABLE `api_request`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `license`
--
ALTER TABLE `license`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_key` (`license_key`);

--
-- Indizes für die Tabelle `mail_sent`
--
ALTER TABLE `mail_sent`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `mail_tracked`
--
ALTER TABLE `mail_tracked`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `plugin`
--
ALTER TABLE `plugin`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `plugin_section`
--
ALTER TABLE `plugin_section`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `apikey` (`apikey`),
  ADD UNIQUE KEY `confirmation_token` (`confirmation_token`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `api_request`
--
ALTER TABLE `api_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `license`
--
ALTER TABLE `license`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `mail_sent`
--
ALTER TABLE `mail_sent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `mail_tracked`
--
ALTER TABLE `mail_tracked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `plugin`
--
ALTER TABLE `plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `plugin_section`
--
ALTER TABLE `plugin_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
