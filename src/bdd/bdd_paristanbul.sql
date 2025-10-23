-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 23, 2025 at 09:42 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdd_paristanbul`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int NOT NULL,
  `nom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `prenom` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `email` varchar(150) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `langues` text CHARACTER SET latin1 COLLATE latin1_bin,
  `adresse` text CHARACTER SET latin1 COLLATE latin1_bin,
  `telephone` varchar(20) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `permis` tinyint(1) DEFAULT NULL,
  `experiences` text CHARACTER SET latin1 COLLATE latin1_bin,
  `lettre_motivation` text CHARACTER SET latin1 COLLATE latin1_bin,
  `ref_offre` int DEFAULT NULL,
  `date_candidature` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT 'En attente',
  `lien_cv` text CHARACTER SET latin1 COLLATE latin1_bin
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `candidatures`
--

INSERT INTO `candidatures` (`id`, `nom`, `prenom`, `email`, `date_naissance`, `langues`, `adresse`, `telephone`, `permis`, `experiences`, `lettre_motivation`, `ref_offre`, `date_candidature`, `statut`, `lien_cv`) VALUES
(33, 'Dupont', 'Alain', 'alaindupont@gmail.fr', NULL, '', '', '07000000', 0, '', 'présentez', NULL, '2025-09-17 10:06:49', 'Nouveau', 'telechargement/candidatures/cv_dupont_alain_de93d673.pdf'),
(34, 'q', 'r', 'r@q', NULL, '', '', '070000000', 0, '', 'tttttttttttttttttt', NULL, '2025-09-17 12:51:05', 'Nouveau', 'telechargement/candidatures/cv_q_r_a90e042e.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int NOT NULL,
  `nom_categorie` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `categories`
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
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id_contact` int NOT NULL,
  `nom_complet` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `sujet` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `message` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `nom_complet`, `sujet`, `email`, `message`) VALUES
(1, '', '', '', ''),
(2, '', '', '', ''),
(3, 'a', 'Problème technique', 'a@a', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');

-- --------------------------------------------------------

--
-- Table structure for table `magasins`
--

CREATE TABLE `magasins` (
  `id_magasin` int NOT NULL,
  `ville_magasin` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `rue` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `cp` varchar(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `num_tel` varchar(12) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `horaire_ouverture` time NOT NULL,
  `horaire_fermeture` time NOT NULL,
  `jours_ouverture` text CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `video_magasin` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `magasins`
--

INSERT INTO `magasins` (`id_magasin`, `ville_magasin`, `rue`, `image`, `cp`, `num_tel`, `horaire_ouverture`, `horaire_fermeture`, `jours_ouverture`, `video_magasin`) VALUES
(1, 'VILLIERS-LE-BEL (3 Avenue des entrepreneurs)', '3 avenue des entrepreneurs', 'https://lh3.googleusercontent.com/p/AF1QipNiJsLmv7leKihgS9702f4AfFGlJFkL-AwDIm5Y=s1360-w1360-h1020-rw', '95400', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(2, 'VILLIERS-LE-BEL (117 Avenue Pierre Semard)', '117 Avenue Pierre Semard', 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4noD-VS3HNTGmqeuYwwjOJQlpRFaxmPYqhifKiaM73QQjSVhYR805NHopOFOLvfOPWdV71iyWXCPtzGw0uNy2oH5VwL2lqua30eDUXQ_TH8PsPwzIMOqH3r6jtabKAKXasq-6UVJrA=s1360-w1360-h1020-rw', '95400', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(3, 'DRANCY', '83 avenue Marceau', 'https://lh3.googleusercontent.com/p/AF1QipNOplOHugu7afGp_UFRnQCp7wvMxmX_J1YfMB6l=s1360-w1360-h1020-rw', '93700', '+33 7 49 82 ', '08:30:00', '20:30:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(4, 'BONDY', '116 Av. Gallieni', 'https://lh3.googleusercontent.com/p/AF1QipNiJsLmv7leKihgS9702f4AfFGlJFkL-AwDIm5Y=s1360-w1360-h1020-rw', '93140', '+33 7 49 82 ', '08:30:00', '20:30:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(5, 'VILLEMOMBLE', '68 ALLEE DU PLATEAU', 'https://lh3.googleusercontent.com/p/AF1QipNN-w6AxxhK77amcKRviAFHpiZJXVPZDfmHEhFZ=s1360-w1360-h1020-rw', '93250', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(6, 'NOGENT-SUR-OISE', '171 Rue Jean Monnet', 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nq_ScmqZ_2pDcLF0NXsmFu3duFAPeBME0zF-UYzsWSNCYvEs59mX1O6iLilC2cMIdYLMmcIVZOzQ-Ui8ErAvn-318HsSw0ryCp7vg25mR395iAPIOqlnjV2Lnbjm29VczXjchI=s1360-w1360-h1020-rw', '60180', '+33 7 49 82 ', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL),
(7, 'VERT-SAINT-DENIS', 'La Fontaine ronde', 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nq_ScmqZ_2pDcLF0NXsmFu3duFAPeBME0zF-UYzsWSNCYvEs59mX1O6iLilC2cMIdYLMmcIVZOzQ-Ui8ErAvn-318HsSw0ryCp7vg25mR395iAPIOqlnjV2Lnbjm29VczXjchI=s1360-w1360-h1020-rw', '77240', '07 49 82 61', '08:30:00', '20:00:00', 'Lundi, Mardi, Mercredi, Jeudi, Vendredi, samedi, dimanche', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_optin_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offres_emplois`
--

CREATE TABLE `offres_emplois` (
  `id_offre` int NOT NULL,
  `secteur_activite` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `titre_poste` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `ville` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `departement` char(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `type_contrat` char(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `detail_poste` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `offres_emplois`
--

INSERT INTO `offres_emplois` (`id_offre`, `secteur_activite`, `titre_poste`, `ville`, `departement`, `type_contrat`, `detail_poste`) VALUES
(7, 'informatique', 'developpeur web', 'VILLIERS-LE-BEL (117 Avenue Pierre Semard)', '95', 'Stage', 'venez faire votre alternance chez Paristanbul');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int NOT NULL,
  `nom` tinytext CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `prenom` tinytext CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `mdp` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `role` text CHARACTER SET latin1 COLLATE latin1_bin
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mdp`, `role`) VALUES
(9, 'Quashie', 'Romario', 'r@r', '$2y$12$EkJGpKMWTPWxtVR7.B2pr.JW18tG3pmh1FUeUf.ddgcXFBvJA.BUm', 'user'),
(10, 'r', 'r', 'Lucas@gmail.com', '$2y$10$sCNAMyfnBt8ZIyn6Str0ZuMMKrgmKv9et0/FHH2g/kaqAN0f9yEZW', 'user'),
(11, 'r', 'r', 'Lucas2@gmail.com', '$2y$10$SnyjLn7pwkolVnWZrFy2XehMRFxQJPOgGJRZMpaES4BTzfmPYLs5y', 'user'),
(12, 'r', 'r', 'Lucas23@gmail.com', '$2y$10$0KHq3UuAsBwdgdztAR4g1O5a91xqqtEFn5diDVLs/2ijZDppNPKTC', 'user'),
(13, 'l', 'reda', 'lucas22@gmail.com', '$2y$10$ZiuZp1GrCuw13HaP1FRbA.SslGIbQx5CXzM8riY0tgjdWLBBnqkG6', 'user'),
(14, 'l', 'reda', 'lucas21@gmail.com', '$2y$10$MUWnKn0X7m8CG/5fDBxAJOYaorG89GL3vwEtaZbeM32cz1R0gx9F2', 'user'),
(15, 'fef', 'redz', 'lucas19@gmail.com', '$2y$10$J2yNhz8B.9qnzYenFGYp9OdrK4E1u/OgD9f4YUoHZCaQlNxruhuq6', 'user'),
(16, 'l', 'reda', 'reda21@gmail.com', '$2y$10$4dc./hb3flZM48Tdt5/84u40XXhOmsUNydBEdEMTRkzc4LcCSwzzC', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_offre` (`ref_offre`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id_contact`);

--
-- Indexes for table `magasins`
--
ALTER TABLE `magasins`
  ADD PRIMARY KEY (`id_magasin`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_news_email` (`email`);

--
-- Indexes for table `offres_emplois`
--
ALTER TABLE `offres_emplois`
  ADD PRIMARY KEY (`id_offre`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id_contact` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `magasins`
--
ALTER TABLE `magasins`
  MODIFY `id_magasin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offres_emplois`
--
ALTER TABLE `offres_emplois`
  MODIFY `id_offre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
