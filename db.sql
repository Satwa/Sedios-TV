-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Client :  localhost:3306
-- Généré le :  Sam 24 Janvier 2015 à 18:53
-- Version du serveur :  5.5.34
-- Version de PHP :  5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `sedios`
--

-- --------------------------------------------------------

--
-- Structure de la table `sedios_blog`
--

CREATE TABLE `sedios_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` text NOT NULL,
  `content` longtext NOT NULL,
  `comments` int(11) NOT NULL COMMENT 'Authoriser les com ?',
  `time` bigint(20) NOT NULL,
  `visibility` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_blog_cat`
--

CREATE TABLE `sedios_blog_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `sedios_blog_cat`
--

INSERT INTO `sedios_blog_cat` (`id`, `name`) VALUES
(1, 'Site'),
(2, 'Développement'),
(3, 'Live'),
(4, 'Vidéo'),
(5, 'Passion'),
(6, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `sedios_blog_com`
--

CREATE TABLE `sedios_blog_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_forum_cat`
--

CREATE TABLE `sedios_forum_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` longtext NOT NULL,
  `description` longtext NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_forum_reply`
--

CREATE TABLE `sedios_forum_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `posted` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_forum_thread`
--

CREATE TABLE `sedios_forum_thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `authorize` int(11) NOT NULL DEFAULT '1' COMMENT '1 si on peut répondre 0 si non',
  `cat` int(11) NOT NULL,
  `title` longtext NOT NULL,
  `content` longtext NOT NULL,
  `posted` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_live_channel`
--

CREATE TABLE `sedios_live_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `playerid` text NOT NULL,
  `chatid` text NOT NULL,
  `users` longtext NOT NULL,
  `onair` int(11) NOT NULL COMMENT '1 = oui || 0 = non',
  `progid` int(11) NOT NULL COMMENT 'ID of the program',
  `fb` text NOT NULL,
  `twitter` text NOT NULL,
  `yt` text NOT NULL,
  `streamkey` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_live_show`
--

CREATE TABLE `sedios_live_show` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) NOT NULL,
  `hour` int(11) NOT NULL,
  `title` text NOT NULL,
  `users` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_users`
--

CREATE TABLE `sedios_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `description` longtext NOT NULL,
  `created` int(11) NOT NULL,
  `valid` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL COMMENT '0= Utilisateur, 1= Rédacteur, 2= Streamer, 3=Modo, 4= Admin',
  `token` text NOT NULL COMMENT 'uniqid()+"-"+time()',
  `banned` int(11) DEFAULT '0' COMMENT '1 = oui 0 = non',
  `expire` int(11) NOT NULL COMMENT 'timestamp',
  `lastlogin` text NOT NULL,
  `chatban` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `sedios_users_mp`
--

CREATE TABLE `sedios_users_mp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `message` longtext NOT NULL,
  `posted` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;