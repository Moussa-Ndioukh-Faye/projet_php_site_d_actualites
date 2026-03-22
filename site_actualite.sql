-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 22 mars 2026 à 22:19
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site_actualite`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description_courte` varchar(500) NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` datetime DEFAULT current_timestamp(),
  `id_categorie` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `description_courte`, `contenu`, `date_publication`, `id_categorie`, `id_auteur`) VALUES
(1, 'Nouvelle loi sur le climat', 'Le parlement adopte une loi historique pour l\'environnement', 'Après des débats houleux, le parlement a adopté hier soir la loi sur le climat. Cette législation prévoit une réduction de 40% des émissions de CO2 d\'ici 2030...', '2026-03-19 21:58:01', 1, 1),
(2, 'Le PSG remporte la ligue des champions', 'Victoire historique du club parisien en finale', 'Dans un match haletant, le PSG s\'est imposé 3-2 face au Bayern Munich. Mbappé, auteur d\'un doublé, a été élu homme du match...', '2026-03-19 21:58:01', 2, 2),
(3, 'iPhone 15 : les premières images fuient', 'Des photos du prochain smartphone Apple circulent sur la toile', 'À deux mois de la sortie officielle, des images du futur iPhone 15 ont été publiées sur les réseaux sociaux. Le design semble évoluer avec un nouveau module photo...', '2026-03-19 21:58:01', 3, 3),
(4, 'le futur de ce monde', 'En terme de technologie falilou.org est...', 'En terme de technologie falilou.org est la meilleur organisation qui developpent des produits luxureux et formidables en son sein pour vous.', '2026-03-20 02:26:55', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`) VALUES
(1, 'Politique', 'Actualités politiques nationales et internationales'),
(2, 'Sport', 'Toute l\'actualité sportive : football, basket, tennis...'),
(3, 'Technologie', 'Innovations, high-tech, intelligence artificielle'),
(5, 'Économie', 'Finance, entreprises, marchés'),
(6, 'Santé', 'Médecine, bien-être, recherches médicales'),
(8, 'Culture', 'Cinéma, musique, littérature, arts');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('editeur','administrateur') NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `login`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'Diouf', 'Falilou', 'falilou.d', '$2y$10$VWZBpwXxtha44Y2X09kyK.W/luxZeCSHfNjt1gtx5OJqbPI1gG0JG', 'administrateur', '2026-03-19 21:57:37'),
(2, 'Dubois', 'Pierre', 'pierre.d', '$2y$10$XuMqe.9YB8dx4HjFh4ucMu.5AOY/yGANKBXhOVxOUG8U4JHV1qCN2', 'editeur', '2026-03-19 21:57:37'),
(3, 'Bernard', 'Claire', 'claire.b', '$2y$10$snCqOc4vFWk4UjXij4HEkeED4LVLbQWaXeXiOOCtN3V1/KABehaoy', 'editeur', '2026-03-19 21:57:37');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_auteur` (`id_auteur`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`id_auteur`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
