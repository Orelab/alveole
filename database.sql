-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 28 Septembre 2016 à 11:17
-- Version du serveur :  5.7.15-0ubuntu0.16.04.1
-- Version de PHP :  7.0.8-0ubuntu0.16.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `alveole`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `fk_user` int(11) DEFAULT NULL,
  `controller` text,
  `view` text,
  `request` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `bill`
--

CREATE TABLE `bill` (
  `id` int(11) NOT NULL,
  `fk_project` int(11) DEFAULT NULL,
  `fk_step` int(11) DEFAULT NULL,
  `recurrence` text,
  `recend` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `repartition` text,
  `fk_document` int(11) DEFAULT NULL,
  `date` text,
  `term` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `title` text,
  `description` text,
  `start` int(11) DEFAULT NULL,
  `end` int(11) DEFAULT NULL,
  `recurrence` text,
  `interval` int(11) DEFAULT NULL,
  `recend` int(11) DEFAULT NULL,
  `fk_step` int(11) DEFAULT NULL,
  `fk_user` int(11) DEFAULT NULL,
  `allDay` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `configuration`
--

CREATE TABLE `configuration` (
  `key` varchar(50) NOT NULL,
  `value` text,
  `type` varchar(10) NOT NULL DEFAULT 'hidden'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `configuration`
--

INSERT INTO `configuration` (`key`, `value`, `type`) VALUES
('allow_self_registration', '1', 'boolean'),
('notification_on_registration', '1', 'boolean'),
('currency', '&euro;', 'text'),
('default_language', 'en_EN.utf8', 'text'),
('harddrive', '500', 'text'),
('show_warning', '1', 'boolean'),
('user1', '{"language":"en_EN.utf8","wallpaper":"rotate","mbox":{},"signature":""}', 'hidden'),
('userdefault', '{"language":"en_EN.utf8","mbox":[],"signature":""}', 'hidden'),
('userpref1', '{"caltag17":"true","caltag18":"true","caltag19":"true","mailall":"true","mailto":"true","mailsubject":"false","mailunread":"true","mailsearch":"","mailfrom":"true","caltag51":"true","caltag52":"true","caltag60":"true","contactsearch":"","caltag61":"true","biprojectstart":"2016-02-15","biprojectend":"2016-10-15","mainsearchengine":"","documentsearch":"","projectsearch":"","caltag65":"true","caltag66":"true","caltag67":"true","caltag68":"true","caltag71":"true","caltag85":"true","caltag75":"true","caltag86":"true","caltag91":"true","caltag92":"true"}', 'hidden'),
('version_database', '1.0', 'readonly'),
('version_program', '1.0', 'readonly')
('email_server', '', 'hidden')
('email_user', '', 'hidden')
('email_password', '', 'hidden')
('email_security', '', 'hidden')
('email_port', '', 'hidden');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `mail` text NOT NULL,
  `group` text NOT NULL,
  `fk_owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `contact`
--

INSERT INTO `contact` (`id`, `name`, `surname`, `mail`, `group`, `fk_owner`) VALUES
(4, 'Aurélien', 'Chirot', 'aurelien.chirot@idee-lab.fr', '', 1),
(5, 'Marcel', 'Robert', 'marcel@robe.rt', '', 1),
(6, 'La', 'Trala', 'contact@funworks.fr', '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `contact_meta`
--

CREATE TABLE `contact_meta` (
  `id` int(11) NOT NULL,
  `fk_contact` int(11) NOT NULL,
  `key` varchar(20) NOT NULL,
  `value` text NOT NULL,
  `field` varchar(20) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `contact_meta`
--

INSERT INTO `contact_meta` (`id`, `fk_contact`, `key`, `value`, `field`, `order`) VALUES
(5, 4, 'mémo', 'blablabla', 'textarea', 6),
(8, 4, 'url', 'http://www.idee-lab.fr', 'url', 3),
(9, 4, 'téléphone', '02519875421', 'text', 1),
(11, 4, 'anniversaire', '13-04-1981', 'date', 5),
(12, 4, 'entreprise', 'Idée Lab', 'text', 2),
(13, 4, 'email secondaire', 'aurelien.chirot@gmail.com', 'email', 4),
(14, 0, 'entreprise', 'gfhfgh', 'text', 0),
(15, 0, 'entreprise', 'dffdg', 'text', 0),
(16, 5, 'company', 'Idée Lab', 'text', 0),
(19, 6, 'entreprise', 'ilfw', 'text', 0),
(20, 5, 'url', 'funworks.fr', 'url', 0),
(21, 5, 'url', 'kanope-scae.com', 'url', 0),
(22, 5, 'mobile', '+33600000000', 'text', 0);

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `name` text,
  `path` text,
  `online_date` int(11) DEFAULT NULL,
  `last_update` int(11) DEFAULT NULL,
  `fk_step` int(11) DEFAULT NULL,
  `file_type` text,
  `file_name` text,
  `ressource` text,
  `fk_ressource` int(11) DEFAULT NULL,
  `fk_owner` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `download`
--

CREATE TABLE `download` (
  `id` int(11) NOT NULL,
  `fk_document` int(11) DEFAULT NULL,
  `date` text,
  `ip` text,
  `request` text,
  `fk_user` int(11) DEFAULT NULL,
  `latitude` text,
  `longitude` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `licence`
--

CREATE TABLE `licence` (
  `fk_project` int(11) NOT NULL,
  `fk_plugin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `fk_ressource` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `text` text,
  `fk_document` text,
  `fk_step` int(11) DEFAULT NULL,
  `ressource` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `mail`
--

CREATE TABLE `mail` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `fk_user` int(11) DEFAULT NULL,
  `fk_config` tinytext NOT NULL,
  `msgno` int(11) DEFAULT NULL,
  `subject` text,
  `body` text,
  `attachment` text,
  `seen` int(11) DEFAULT NULL,
  `from` text,
  `to` text,
  `cc` text,
  `date` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `debug` text,
  `source` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `memo`
--

CREATE TABLE `memo` (
  `id` int(11) NOT NULL,
  `fk_user` text,
  `task` text,
  `priority` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `fk_bill` int(11) DEFAULT NULL,
  `fk_date` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `plugin`
--

CREATE TABLE `plugin` (
  `id` int(11) NOT NULL,
  `name` text,
  `slug` text,
  `text` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` text,
  `url` text,
  `description` text,
  `apikey` text,
  `date` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `fk_owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `fk_project` int(11) DEFAULT NULL,
  `fk_user` int(11) DEFAULT NULL,
  `fk_step` int(11) DEFAULT NULL,
  `date` text,
  `on` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `share`
--

CREATE TABLE `share` (
  `fk_document` int(11) DEFAULT NULL,
  `fk_user` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `group` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(25) DEFAULT NULL,
  `misc` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`id`, `group`, `name`, `order`, `readonly`, `color`, `misc`) VALUES
(1, 'job', 'administrateur', 0, 1, '', ''),
(2, 'job', 'chef de projet', 2, 0, '', '{"amount":450}'),
(3, 'job', 'développeur', 4, 0, '', '{"amount":450}'),
(4, 'job', 'graphiste', 3, 0, '', '{"amount":450}'),
(5, 'job', 'client', 1, 1, '', ''),
(6, 'job', 'prescripteur', 5, 0, '', ''),
(7, 'event', 'important', 0, 0, '', ''),
(8, 'event', 'urgent', 1, 0, '', ''),
(9, 'event', 'prospection', 2, 0, '', ''),
(10, 'event', 'développement', 3, 0, '', ''),
(11, 'event', 'réunion', 4, 0, '', ''),
(13, 'ticket', 'ouvert', 1, 1, '#FFE2E2', ''),
(14, 'ticket', 'en cours', 2, 1, '#FFE2E2', ''),
(15, 'ticket', 'chiffrage', 3, 0, '#FFFFDC', ''),
(16, 'ticket', 'résolu', 5, 1, '#E2FFE3', ''),
(17, 'calendar', 'pro', 0, 0, '#00890A', '{"owner":1}'),
(18, 'calendar', 'personnel', 1, 0, '#0D0089', '{"owner":1}'),
(19, 'calendar', 'facture', 2, 1, '#890000', '{"readonly":true}'),
(20, 'log', 'devis', 1, 0, '', ''),
(21, 'log', 'proposition', 0, 0, '', ''),
(22, 'log', 'maquette', 3, 0, '', ''),
(23, 'log', 'recette', 4, 0, '', ''),
(24, 'log', 'garantie / SAV', 5, 0, '', ''),
(25, 'log', 'hébergement', 6, 0, '', ''),
(26, 'log', 'compte-rendu', 2, 0, '', ''),
(35, 'memo', 'prioritaire', 1, 0, '#FFE2E2', ''),
(36, 'memo', 'normal', 0, 1, '#FFFFDC', ''),
(37, 'memo', 'non prioritaire', 2, 0, '#E2FFE5', ''),
(53, 'document', 'modèle', 0, 0, '', ''),
(54, 'document', 'publicité', 0, 0, '', ''),
(55, 'document', 'comptabilité', 0, 0, '', ''),
(56, 'document', 'code source', 0, 0, '', ''),
(57, 'document', 'plaquette', 0, 0, '', ''),
(58, 'document', 'tarifs', 0, 0, '', ''),
(59, 'ticket', 'information', 4, 1, '#E7E2FF', '');

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `fk_parent` int(11) DEFAULT NULL,
  `fk_step` int(11) DEFAULT NULL,
  `fk_user` int(11) DEFAULT NULL,
  `fk_document` int(11) DEFAULT NULL,
  `fk_project` int(11) DEFAULT NULL,
  `date` text,
  `text` text,
  `price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `trace`
--

CREATE TABLE `trace` (
  `id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `trace` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `uname` text,
  `usurname` text,
  `email` text,
  `group` text,
  `fk_project` int(11) DEFAULT NULL,
  `md5pass` text,
  `can_connect` int(11) DEFAULT NULL,
  `business` text,
  `phone` text,
  `address` text,
  `text` text,
  `activation_key` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `version`
--

CREATE TABLE `version` (
  `id` int(11) NOT NULL,
  `fk_plugin` int(11) DEFAULT NULL,
  `version` text,
  `file` text,
  `date` int(11) DEFAULT NULL,
  `tested` text,
  `detail` text,
  `fk_document` int(11) DEFAULT NULL,
  `requires` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `webmail_contacts`
--

CREATE TABLE `webmail_contacts` (
  `email` varchar(100) NOT NULL,
  `name` text,
  `fk_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_owner` (`fk_owner`);

--
-- Index pour la table `contact_meta`
--
ALTER TABLE `contact_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact` (`fk_contact`);

--
-- Index pour la table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `licence`
--
ALTER TABLE `licence`
  ADD KEY `fk_project` (`fk_project`),
  ADD KEY `fk_plugin` (`fk_plugin`);

--
-- Index pour la table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`fk_user`),
  ADD KEY `uid` (`uid`),
  ADD KEY `from` (`from`(767)),
  ADD KEY `to` (`to`(767)),
  ADD KEY `seen` (`seen`);

--
-- Index pour la table `memo`
--
ALTER TABLE `memo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plugin`
--
ALTER TABLE `plugin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `share`
--
ALTER TABLE `share`
  ADD KEY `fk_document` (`fk_document`),
  ADD KEY `fk_user` (`fk_user`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `trace`
--
ALTER TABLE `trace`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `webmail_contacts`
--
ALTER TABLE `webmail_contacts`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `contact_meta`
--
ALTER TABLE `contact_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT pour la table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `download`
--
ALTER TABLE `download`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `mail`
--
ALTER TABLE `mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `memo`
--
ALTER TABLE `memo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `plugin`
--
ALTER TABLE `plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT pour la table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `trace`
--
ALTER TABLE `trace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `version`
--
ALTER TABLE `version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
