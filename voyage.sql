-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 04 mai 2026 à 12:00
-- Version du serveur : 9.1.0
-- Version de PHP : 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `voyage`
--

-- --------------------------------------------------------

--
-- Structure de la table `activité`
--

DROP TABLE IF EXISTS `activité`;
CREATE TABLE IF NOT EXISTS `activité` (
  `id_activite` int NOT NULL AUTO_INCREMENT,
  `id_destination` int DEFAULT NULL,
  `nom` varchar(150) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_activite`),
  KEY `id_destination` (`id_destination`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `activité`
--

INSERT INTO `activité` (`id_activite`, `id_destination`, `nom`, `type`, `prix`) VALUES
(1, 1, 'Tour Eiffel', 'Tourisme', 25.00),
(2, 1, 'Croisière sur la Seine', 'Balade', 50.00),
(3, 2, 'Central Park', 'Balade', 0.00),
(4, 2, 'Empire State Building', 'Tourisme', 30.00),
(5, 3, 'Shibuya Crossing', 'Tourisme', 0.00),
(6, 3, 'Temple Sensō-ji', 'Culturel', 20.00),
(7, 4, 'Buckingham Palace', 'Tourisme', 30.00),
(8, 4, 'Tower Bridge', 'Visite', 10.00),
(9, 5, 'Opéra de Sydney', 'Culturel', 40.00),
(10, 5, 'Blue Mountains', 'Randonnée', 20.00),
(11, 6, 'Colisée', 'Historique', 30.00),
(12, 6, 'Fontaine de Trevi', 'Tourisme', 0.00),
(13, 7, 'Sagrada Familia', 'Culturel', 25.00),
(14, 7, 'Parc Güell', 'Balade', 15.00),
(15, 8, 'Mur de Berlin', 'Historique', 0.00),
(16, 8, 'Île aux Musées', 'Culturel', 20.00);

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_destination` int DEFAULT NULL,
  `note` int DEFAULT NULL,
  `commentaire` text,
  `date_avis` date NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_destination` (`id_destination`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_utilisateur`, `id_destination`, `note`, `commentaire`, `date_avis`) VALUES
(1, 1, 1, 5, 'Superbe voyage à Paris!', '2024-06-12'),
(2, 2, 2, 4, 'New York est incroyable', '2024-07-28'),
(3, 3, 3, 5, 'Tokyo est magique!', '2024-09-18'),
(4, 4, 4, 3, 'Londres était pluvieux...', '2024-10-05'),
(5, 5, 5, 5, 'Sydney est magnifique!', '2024-11-01'),
(6, 6, 6, 1, 'Rome était trop bondée et chère.', '2024-12-10');

-- --------------------------------------------------------

--
-- Structure de la table `destination`
--

DROP TABLE IF EXISTS `destination`;
CREATE TABLE IF NOT EXISTS `destination` (
  `id_destination` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `description` text,
  `climat` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_destination`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `destination`
--

INSERT INTO `destination` (`id_destination`, `nom`, `pays`, `description`, `climat`) VALUES
(1, 'Paris', 'France', 'Ville lumière', 'Tempéré'),
(2, 'New York', 'USA', 'La Grosse Pomme', 'Continental'),
(3, 'Tokyo', 'Japon', 'Ville technologique', 'Humide'),
(4, 'Londres', 'Royaume-Uni', 'Ville historique', 'Océanique'),
(5, 'Sydney', 'Australie', 'Ville portuaire', 'Méditerranéen'),
(6, 'Rome', 'Italie', 'Ville éternelle', 'Méditerranéen'),
(7, 'Barcelone', 'Espagne', 'Ville artistique', 'Méditerranéen'),
(8, 'Berlin', 'Allemagne', 'Capitale historique', 'Tempéré'),
(9, 'Moscou', 'Russie', 'Ville impériale', 'Continental'),
(10, 'Pékin', 'Chine', 'Ville impériale', 'Humide'),
(11, 'Rio de Janeiro', 'Brésil', 'Ville festive', 'Tropical'),
(12, 'Le Caire', 'Égypte', 'Ville des pharaons', 'Désertique'),
(13, 'Bangkok', 'Thaïlande', 'Ville animée', 'Tropical'),
(14, 'Istanbul', 'Turquie', 'Ville transcontinentale', 'Tempéré'),
(15, 'Los Angeles', 'USA', 'Ville du cinéma', 'Méditerranéen');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id_reservation` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_voyage` int DEFAULT NULL,
  `date_reservation` date NOT NULL,
  `statut` enum('confirmée','annulée','en attente') NOT NULL,
  PRIMARY KEY (`id_reservation`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_voyage` (`id_voyage`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` date NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `email`, `mot_de_passe`, `date_inscription`) VALUES
(1, 'Alice Dupont', 'alice@example.com', 'mdp123', '2024-01-15'),
(2, 'Bob Martin', 'bob@example.com', 'securepass', '2024-02-10'),
(3, 'Charlie Lambert', 'charlie@example.com', 'pass123', '2024-03-05'),
(4, 'David Morel', 'david@example.com', 'pass456', '2024-04-12'),
(5, 'Emma Lefevre', 'emma@example.com', 'secure456', '2024-05-18'),
(6, 'Fanny Dubois', 'fanny@example.com', 'motdepasse1', '2024-06-22'),
(7, 'Gabriel Fontaine', 'gabriel@example.com', 'pass789', '2024-07-30'),
(8, 'Hugo Caron', 'hugo@example.com', 'pass999', '2024-08-05');

-- --------------------------------------------------------

--
-- Structure de la table `voyage`
--

DROP TABLE IF EXISTS `voyage`;
CREATE TABLE IF NOT EXISTS `voyage` (
  `id_voyage` int NOT NULL AUTO_INCREMENT,
  `id_destination` int DEFAULT NULL,
  `date_depart` date NOT NULL,
  `date_retour` date NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_voyage`),
  KEY `id_destination` (`id_destination`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `voyage`
--

INSERT INTO `voyage` (`id_voyage`, `id_destination`, `date_depart`, `date_retour`, `prix`) VALUES
(1, 1, '2024-06-01', '2024-06-10', 1200.50),
(2, 2, '2024-07-15', '2024-07-25', 1500.00),
(3, 3, '2024-09-05', '2024-09-15', 1800.75),
(4, 4, '2024-10-10', '2024-10-20', 1350.00),
(5, 5, '2024-11-05', '2024-11-15', 2000.00),
(6, 6, '2024-12-01', '2024-12-10', 2200.75);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activité`
--
ALTER TABLE `activité`
  ADD CONSTRAINT `activité_ibfk_1` FOREIGN KEY (`id_destination`) REFERENCES `destination` (`id_destination`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_voyage`) REFERENCES `voyage` (`id_voyage`);

--
-- Contraintes pour la table `voyage`
--
ALTER TABLE `voyage`
  ADD CONSTRAINT `voyage_ibfk_1` FOREIGN KEY (`id_destination`) REFERENCES `destination` (`id_destination`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
