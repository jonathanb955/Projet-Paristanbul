-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 26 mai 2025 à 09:01
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

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
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(255) COLLATE latin1_bin NOT NULL,
  `ref_sous-categorie` int NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `magasins`
--

DROP TABLE IF EXISTS `magasins`;
CREATE TABLE IF NOT EXISTS `magasins` (
  `id_magasin` int NOT NULL AUTO_INCREMENT,
  `ville_magasin` text COLLATE latin1_bin NOT NULL,
  `rue` varchar(255) COLLATE latin1_bin NOT NULL,
  `image` varchar(255) COLLATE latin1_bin NOT NULL,
  `cp` varchar(10) COLLATE latin1_bin NOT NULL,
  `num_tel` varchar(12) COLLATE latin1_bin NOT NULL,
  `horaire_ouverture` time NOT NULL,
  `horaire_fermeture` time NOT NULL,
  `jours_ouverture` text COLLATE latin1_bin NOT NULL,
  `video_magasin` varchar(255) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id_magasin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` varchar(255) COLLATE latin1_bin NOT NULL,
  `nom_produit` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `origine` int NOT NULL,
  `date_peremption` date NOT NULL,
  `marque` text COLLATE latin1_bin NOT NULL,
  `quantite-unite` varchar(50) COLLATE latin1_bin NOT NULL,
  `ref_sous-categorie` int NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `ref_sous-categorie` (`ref_sous-categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `sous-categories`
--

DROP TABLE IF EXISTS `sous-categories`;
CREATE TABLE IF NOT EXISTS `sous-categories` (
  `id_sous-categorie` int NOT NULL AUTO_INCREMENT,
  `nom_sous-categorie` varchar(255) COLLATE latin1_bin NOT NULL,
  `ref_categorie` int NOT NULL,
  PRIMARY KEY (`id_sous-categorie`),
  KEY `ref_categorie` (`ref_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` tinytext COLLATE latin1_bin NOT NULL,
  `prenom` tinytext COLLATE latin1_bin NOT NULL,
  `email` varchar(255) COLLATE latin1_bin NOT NULL,
  `mdp` varchar(50) COLLATE latin1_bin NOT NULL,
  `role` text COLLATE latin1_bin,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`ref_sous-categorie`) REFERENCES `sous-categories` (`id_sous-categorie`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `sous-categories`
--
ALTER TABLE `sous-categories`
  ADD CONSTRAINT `sous-categories_ibfk_1` FOREIGN KEY (`ref_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
