-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 sep. 2025 à 14:20
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_paristanbul`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

DROP TABLE IF EXISTS `candidatures`;
CREATE TABLE IF NOT EXISTS `candidatures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `prenom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `telephone` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ville` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `cv` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `lettre_motivation` text CHARACTER SET latin1 COLLATE latin1_bin,
  `ref_offre` int DEFAULT NULL,
  `date_candidature` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT 'En attente',
  PRIMARY KEY (`id`),
  KEY `ref_offre` (`ref_offre`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id`, `nom`, `prenom`, `email`, `telephone`, `ville`, `cv`, `lettre_motivation`, `ref_offre`, `date_candidature`, `statut`) VALUES
(1, 'aaa', 'aaa', 'aaa@aaaa', '07', 'a@a', '', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 0, '2025-09-04 00:00:00', 'en attente'),
(2, 'Quashie', 'romario', 'r@q', '07', 'Bondy', '68b95d37cfb47_Note.docx', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 0, '0000-00-00 00:00:00', 'en attente'),
(3, 'b', 'a', 'a@b', '076', 'Bondy', '', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 0, '0000-00-00 00:00:00', 'en attente'),
(4, 'c', 'c', 'c@c', '07', 'Paris', '', 'vvvvvvvvvvvvvvvvvvvvvvvvvv', 0, '0000-00-00 00:00:00', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'BOISSONS'),
(2, 'VIANDES'),
(3, 'PRODUITS FRAIS'),
(4, 'SURGELÉS'),
(5, 'PRODUITS SECS'),
(6, 'EMBALLAGES'),
(7, 'HYGIENES');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id_contact` int NOT NULL AUTO_INCREMENT,
  `nom_complet` varchar(255) COLLATE latin1_bin NOT NULL,
  `sujet` varchar(255) COLLATE latin1_bin NOT NULL,
  `email` varchar(255) COLLATE latin1_bin NOT NULL,
  `message` varchar(255) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `nom_complet`, `sujet`, `email`, `message`) VALUES
(1, '', '', '', ''),
(2, '', '', '', ''),
(3, 'a', 'Problème technique', 'a@a', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');

-- --------------------------------------------------------

--
-- Structure de la table `magasins`
--

DROP TABLE IF EXISTS `magasins`;
CREATE TABLE IF NOT EXISTS `magasins` (
  `id_magasin` int NOT NULL AUTO_INCREMENT,
  `ville_magasin` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `rue` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `cp` varchar(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `num_tel` varchar(12) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `horaire_ouverture` time NOT NULL,
  `horaire_fermeture` time NOT NULL,
  `jours_ouverture` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `video_magasin` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id_magasin`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `magasins`
--

INSERT INTO `magasins` (`id_magasin`, `ville_magasin`, `rue`, `image`, `cp`, `num_tel`, `horaire_ouverture`, `horaire_fermeture`, `jours_ouverture`, `video_magasin`) VALUES
(1, 'VILLIERS-LE-BEL', '3 avenue des entrepreneurs', 'https://lh3.googleusercontent.com/p/AF1QipNiJsLmv7leKihgS9702f4AfFGlJFkL-AwDIm5Y=s1360-w1360-h1020-rw', '95400', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(2, 'VILLIERS-LE-BEL', '117 Avenue Pierre Semard', 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4noD-VS3HNTGmqeuYwwjOJQlpRFaxmPYqhifKiaM73QQjSVhYR805NHopOFOLvfOPWdV71iyWXCPtzGw0uNy2oH5VwL2lqua30eDUXQ_TH8PsPwzIMOqH3r6jtabKAKXasq-6UVJrA=s1360-w1360-h1020-rw', '95400', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(3, 'DRANCY', '83 avenue Marceau', 'https://lh3.googleusercontent.com/p/AF1QipNOplOHugu7afGp_UFRnQCp7wvMxmX_J1YfMB6l=s1360-w1360-h1020-rw', '93700', '+33 7 49 82 ', '08:30:00', '20:30:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(4, 'BONDY', '116 Av. Gallieni', 'https://lh3.googleusercontent.com/p/AF1QipNiJsLmv7leKihgS9702f4AfFGlJFkL-AwDIm5Y=s1360-w1360-h1020-rw', '93140', '+33 7 49 82 ', '08:30:00', '20:30:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(5, 'VILLEMOMBLE', '68 ALLEE DU PLATEAU', 'https://lh3.googleusercontent.com/p/AF1QipNN-w6AxxhK77amcKRviAFHpiZJXVPZDfmHEhFZ=s1360-w1360-h1020-rw', '93250', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(6, 'NOGENT-SUR-OISE', '171 Rue Jean Monnet', 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nq_ScmqZ_2pDcLF0NXsmFu3duFAPeBME0zF-UYzsWSNCYvEs59mX1O6iLilC2cMIdYLMmcIVZOzQ-Ui8ErAvn-318HsSw0ryCp7vg25mR395iAPIOqlnjV2Lnbjm29VczXjchI=s1360-w1360-h1020-rw', '60180', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `offres_emplois`
--

DROP TABLE IF EXISTS `offres_emplois`;
CREATE TABLE IF NOT EXISTS `offres_emplois` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `secteur_activite` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `titre_poste` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ville` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `departement` char(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `type_contrat` char(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `detail_poste` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id_offre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` tinytext CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `prenom` tinytext CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `mdp` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `role` text CHARACTER SET latin1 COLLATE latin1_bin,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mdp`, `role`) VALUES
(3, 'bj', 'aaa', '23@gmail.com', '$2y$10$K7BXdoyz3LnTgY.uQ8fAe.1kthODnZm4NtNpXDQ2Yxo32nL2imPVu', 'admin'),
(4, 'ef', 'zee', '45@gmail.com', '$2y$10$QCeroI4FSuCHLpr3Z8MeeuGwnM4vaIptxhZ8lGFg6MqGFKeFz4Z92', 'admin'),
(5, 'lakhledj', 'reda', 'r@gmail.com', '$2y$10$FIxcB1g7Mmp6wXjuAw2uo.Lrgp.I9zwNJx12hpEjeCBC.GhX40WCm', 'utilisateur');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
