-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 20. Jun 2018 um 12:18
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
  `target` varchar(100) DEFAULT NULL,
  `method` varchar(10) NOT NULL,
  `request_headers` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `license`
--

CREATE TABLE `license` (
  `id` int(11) NOT NULL,
  `license_user` varchar(255) NOT NULL,
  `license_key` varchar(200) NOT NULL,
  `license_host` varchar(100) DEFAULT NULL,
  `plugin_slug` varchar(100) NOT NULL,
  `valid_until` datetime NOT NULL,
  `renewals` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user_entry_id` int(11) DEFAULT NULL,
  `login_status` varchar(50) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `url` varchar(255) NOT NULL,
  `rating5` int(11) NOT NULL,
  `rating4` int(11) NOT NULL,
  `rating3` int(11) NOT NULL,
  `rating2` int(11) NOT NULL,
  `rating1` int(11) NOT NULL,
  `last_updated` datetime NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `banner_low` varchar(50) NOT NULL,
  `banner_high` varchar(50) NOT NULL,
  `section_description` text NOT NULL,
  `section_installation` text,
  `section_faq` text,
  `section_screenshots` text,
  `section_changelog` text,
  `section_other_notes` text,
  `license_enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin_version`
--

CREATE TABLE `plugin_version` (
  `id` int(11) NOT NULL,
  `plugin_entry_id` int(11) NOT NULL,
  `version` varchar(15) NOT NULL,
  `requires_php` varchar(10) NOT NULL,
  `requires` varchar(10) NOT NULL,
  `tested` varchar(10) NOT NULL,
  `downloaded` int(11) NOT NULL DEFAULT '0',
  `active_installations` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `theme`
--

CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `theme_name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `author` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `section_description` text,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `theme_version`
--

CREATE TABLE `theme_version` (
  `id` int(11) NOT NULL,
  `theme_entry_id` int(11) NOT NULL,
  `version` varchar(10) NOT NULL,
  `requires_php` varchar(10) NOT NULL,
  `requires` varchar(10) NOT NULL,
  `tested` varchar(10) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `confirmation_token` varchar(150) DEFAULT NULL,
  `confirmation_token_validity` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `sex` varchar(1) NOT NULL DEFAULT 'm',
  `locale` varchar(5) NOT NULL DEFAULT 'en',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `first_name`, `last_name`, `password`, `email`, `confirmation_token`, `confirmation_token_validity`, `last_login`, `sex`, `locale`, `admin`, `locked`, `created_at`) VALUES
(1, 'admin', 'Robin', 'Kaiser', '$2y$12$GicGrkBOWhUV/CSewj2Gm.SSRW5ciOzdgYjG.2CRjjXOyz0.Vt65i', 't@r-k.mx', NULL, NULL, '2018-06-20 12:15:23', 'm', 'en', 1, 0, '2018-06-19 15:30:35');

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
-- Indizes für die Tabelle `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indizes für die Tabelle `plugin_version`
--
ALTER TABLE `plugin_version`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indizes für die Tabelle `theme_version`
--
ALTER TABLE `theme_version`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
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
-- AUTO_INCREMENT für Tabelle `login`
--
ALTER TABLE `login`
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
-- AUTO_INCREMENT für Tabelle `plugin_version`
--
ALTER TABLE `plugin_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `theme_version`
--
ALTER TABLE `theme_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
