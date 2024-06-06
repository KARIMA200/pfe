-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 04 juin 2024 à 15:43
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
-- Base de données : `ecommerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `clics_utilisateurs`
--

CREATE TABLE `clics_utilisateurs` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clics_utilisateurs`
--

INSERT INTO `clics_utilisateurs` (`id`, `user_email`, `comment_id`) VALUES
(12, 'coco@gmail.com', 42),
(13, 'khokha@gmail.com', 42),
(14, 'khokha@gmail.com', 43),
(15, 'khokha@gmail.com', 55);

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `prenom`, `pays`, `ville`, `adresse`, `telephone`, `email`, `password`, `session_id`, `user_image`) VALUES
(42, 'chougri', 'karima', 'maroc', 'casa', '0607901058', '000000000', 'cli@gmail.com', '0000', NULL, 'images/clients.jpeg'),
(43, 'chougri', 'karima', '55', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'c@gmail.com', '0000', NULL, 'vendeur.jpeg'),
(44, 'so', 'so', 'mmmm', 'so', 'so', '78888888886', 'so@gmail.com', '677', NULL, '1.png'),
(45, 'so', 'so', '000', 'so', 'so', '0000', 'soso@gmail.com', '0000', NULL, 'ad1.png'),
(46, 'chougri', 'karima', 'tud', 'casa', '0607901058', '000000000', 'latifa@gmail.com', '0000', NULL, 'client.jpeg'),
(47, 'chougri', 'karima', 'tf', 'casa', '0607901058', '000000000', 'diosa@gmail.com', '0000', NULL, 'client.jpeg'),
(48, 'chougri', 'karima', 'maroc', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'karima.chougri@gmail.com', '0000', NULL, 'client.jpeg'),
(49, 'chougri', 'karima', 'maroc', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', '77@gmail.com', '0000', NULL, 'client.jpeg'),
(50, 'chougri', 'karima', 'ghgg', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'o@gmail.com', '0000', NULL, 'client.jpeg'),
(51, 'chougri', 'karima', 'mroc', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'driss@gmail.com', '0000', NULL, 'client.jpeg'),
(52, 'chougri', 'karima', 'mafrance', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'mohamed@gmail.com', '0000', NULL, 'pardefaut.jpg'),
(53, 'mimi', 'mimi', 'maroc', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'mimi@gmail.com', '$2y$10$E4btmdgpgxd2aFrhQB2T9OmhTKdkd0TKGFp0saPI9.HKHQgt7/ZmW', NULL, 'ma.jpg'),
(54, 'chougri', 'karima', '67', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'popo@gmail.com', '$2y$10$Ws2Pam/lmsk.2nx3SAeb7e6K/eJBs73E.lKqUARF657.Z9xQXDtWK', NULL, 'ma.jpg'),
(55, 'chougri', 'karima', '777', 'casa', '0607901058', '000000000', 'xx@gmail.com', '$2y$10$ik0GymwFzNE2a112uFRPL.2dbT94Ji5odCmZgTJhCwQidf8b7Oex.', NULL, 'client.jpeg'),
(56, 'chougri', 'karima', 'chomarokkkk', 'casa', '0607901058', '000000000', 'tabon@gmail.com', '$2y$10$adQiuGAAyehkGtB72PAMEu44Mi6dfPVRdAneGp8s3xq8R2Q/219nW', NULL, 'pardefaut.jpg'),
(57, 'chougri', 'karima', 'maroc', 'casa', '0607901058', '000000000', 'hiba@gmail.com', '$2y$10$WnSFHksJBlqpQBVvP1/M8OziNEXFhnTm8Z4tuRzXsVfbmaBK.To9u', NULL, 'client.jpeg'),
(58, 'chougri', 'karima', 'maroc', 'casa', '0607901058', '000000000', 'zok@gmail.com', '$2y$10$uurW0WyBVxODonWf.QM5ROmc9Y6p84aQJbiTeeAJB9XTjK8B9care', NULL, 'client.jpeg'),
(59, 'chougri', 'karima', 'hmc', 'casa', '0607901058', '000000000', 'z@gmail.com', '$2y$10$AroXPohna/r8FX1Ok2/yM.N.tnA/cEFgYD.vVS4xy2HdCuN75vaY2', NULL, 'client.jpeg'),
(60, 'chougri', 'karima', 'kjxsh', 'casa', '0607901058', '000000000', 'mama@gmail.com', '$2y$10$ikCNeo/wOaVesfH0sEe5weG/zOBZSDGWeWWK4dArR2oQInrtulDGK', NULL, 'commente.png'),
(61, 'ents', 'cli', 'awrfyg', 'qwyuf', 'qwd8e7otr', 'qs68tr', 'coco@gmail.com', '$2y$10$1fC4sd/fkp4dPxgzI1kJauA7N4.qrrwlMkY9iLE0By2e6LjwdKkNi', NULL, '20240522023104.soso@gmail.com.jpeg'),
(62, 'chougri', 'yones', 'dg', 'casa', '0607901058', '000000000', 'yones@gmail.com', '$2y$10$vVFUCNkgwFVz5aGQDzcA6.u1W1TWNxN.OlB/PQIAIefo7Il2r8xea', NULL, 'commente.png');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut_commande` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `email`, `date_commande`, `statut_commande`) VALUES
(1, 'yones@gmail.com', '2024-06-02 22:53:38', NULL),
(2, 'yones@gmail.com', '2024-06-02 23:05:13', NULL),
(3, 'yones@gmail.com', '2024-06-02 23:08:27', NULL),
(4, 'yones@gmail.com', '2024-06-02 23:48:30', NULL),
(5, 'yones@gmail.com', '2024-06-02 23:49:06', NULL),
(6, 'yones@gmail.com', '2024-06-02 23:51:45', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande_details`
--

CREATE TABLE `commande_details` (
  `id_detail` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_details`
--

INSERT INTO `commande_details` (`id_detail`, `id_commande`, `id_produit`, `quantite`) VALUES
(4, 6, 53, NULL),
(5, 6, 51, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT current_timestamp(),
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `nombre_clics` int(11) NOT NULL DEFAULT 0,
  `nombre_reponses` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `produit_id`, `email`, `commentaire`, `date_commentaire`, `nom`, `prenom`, `image`, `nombre_clics`, `nombre_reponses`) VALUES
(42, 54, 'coco@gmail.com', 'hh', '2024-06-02 12:39:48', 'ents', 'cli', '20240522023104.soso@gmail.com.jpeg', 2, 3),
(43, 54, 'coco@gmail.com', 'ana coco\r\n', '2024-06-02 15:36:06', 'ents', 'cli', '20240522023104.soso@gmail.com.jpeg', 1, 1),
(44, 51, 'coco@gmail.com', 'hh', '2024-06-02 18:29:00', 'ents', 'cli', '20240522023104.soso@gmail.com.jpeg', 0, 0),
(45, 53, 'coco@gmail.com', 'hh', '2024-06-02 18:35:31', 'ents', 'cli', '20240522023104.soso@gmail.com.jpeg', 0, 0),
(46, 55, NULL, 'hhh', '2024-06-03 00:56:01', NULL, NULL, NULL, 0, 0),
(47, 51, 'coco@gmail.com', 'hh', '2024-06-03 00:56:24', 'ents', 'cli', '20240522023104.soso@gmail.com.jpeg', 0, 0),
(48, 49, 'khokha@gmail.com', 'hh', '2024-06-03 01:02:26', 'chougri', 'karima', 'commente.png', 0, 0),
(49, 49, 'khokha@gmail.com', 'mlk', '2024-06-03 01:03:04', 'chougri', 'karima', 'commente.png', 0, 0),
(50, 49, 'khokha@gmail.com', 'mlk', '2024-06-03 01:07:42', 'chougri', 'karima', 'commente.png', 0, 0),
(51, 49, 'khokha@gmail.com', 'jj', '2024-06-03 01:07:55', 'chougri', 'karima', 'commente.png', 0, 0),
(52, 49, 'khokha@gmail.com', 'jj', '2024-06-03 01:15:09', 'chougri', 'karima', 'commente.png', 0, 0),
(53, 49, 'khokha@gmail.com', 'hy', '2024-06-03 01:15:22', 'chougri', 'karima', 'commente.png', 0, 0),
(54, 49, 'khokha@gmail.com', 'hy', '2024-06-03 01:15:56', 'chougri', 'karima', 'commente.png', 0, 0),
(55, 56, 'khokha@gmail.com', 'hh', '2024-06-03 01:22:09', 'chougri', 'karima', 'commente.png', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `utilisateur1` varchar(255) NOT NULL,
  `utilisateur2` varchar(255) NOT NULL,
  `dernier_message` text DEFAULT NULL,
  `date_dernier_message` datetime DEFAULT NULL,
  `utilisateur1_delete` tinyint(1) DEFAULT 0,
  `utilisateur2_delete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `conversations`
--

INSERT INTO `conversations` (`id`, `utilisateur1`, `utilisateur2`, `dernier_message`, `date_dernier_message`, `utilisateur1_delete`, `utilisateur2_delete`) VALUES
(32, 'khokha@gmail.com', 'khokh@gmail.com', 'hello', '2024-05-30 00:54:44', 1, 0),
(33, 'khokh@gmail.com', 'khokh@gmail.com', 'hy', '2024-05-31 14:51:57', 0, 0),
(34, 'zok@gmail.com', 'khokh@gmail.com', 'images/20240530232909.zok@gmail.com.jpg', '2024-05-30 23:29:09', 0, 0),
(35, 'zok@gmail.com', 'khokha@gmail.com', 'comoestats', '2024-05-30 01:52:24', 1, 0),
(36, 'khokha@gmail.com', 'khokha@gmail.com', 'hhhh', '2024-05-30 01:53:19', 1, 0),
(37, 'trma@gmail.com', 'khokh@gmail.com', 'uploads/20240531152501.khokh@gmail.com.wav', '2024-05-31 15:25:01', 0, 0),
(38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 15:20:02', 1, 1),
(39, 'vovo@gmail.com', 'khokh@gmail.com', 'hello', '2024-06-01 21:19:53', 0, 0),
(40, 'coco@gmail.com', 'khokha@gmail.com', 'uploads/20240601215517.coco@gmail.com.wav', '2024-06-01 21:55:17', 0, 0),
(41, 'coco@gmail.com', 'khokh@gmail.com', 'helllo', '2024-06-02 13:37:11', 0, 0),
(42, 'khokha@gmail.com', 'vovo@gmail.com', 'uploads/20240602134057.khokha@gmail.com.wav', '2024-06-02 13:40:57', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`id`, `product_id`, `user_email`) VALUES
(32, 53, 'vovo@gmail.com'),
(33, 53, 'coco@gmail.com'),
(34, 52, 'vovo@gmail.com'),
(35, 46, 'vovo@gmail.com'),
(36, 51, 'coco@gmail.com'),
(37, 50, 'coco@gmail.com'),
(38, 47, 'coco@gmail.com'),
(39, 48, 'coco@gmail.com'),
(40, 49, 'coco@gmail.com'),
(41, 47, 'khokh@gmail.com'),
(42, 46, 'khokh@gmail.com'),
(43, 46, 'coco@gmail.com'),
(44, 48, 'khokha@gmail.com'),
(45, 49, 'khokha@gmail.com'),
(46, 54, 'khokha@gmail.com'),
(47, 54, 'coco@gmail.com'),
(48, 55, 'coco@gmail.com'),
(49, 56, 'khokha@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `expediteur` varchar(255) DEFAULT NULL,
  `destinataire` varchar(255) DEFAULT NULL,
  `contenu` varchar(255) NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `expediteur`, `destinataire`, `contenu`, `date_envoi`, `lu`, `type`) VALUES
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'hbiba', '2024-05-31 23:19:25', 0, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'oui', '2024-05-31 23:19:49', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-05-31 23:25:59', 1, NULL),
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'cc', '2024-05-31 23:28:18', 0, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-05-31 23:29:52', 1, NULL),
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'cc', '2024-05-31 23:38:27', 0, NULL),
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'tt', '2024-05-31 23:43:43', 0, NULL),
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'tt\r\n', '2024-05-31 23:44:56', 0, NULL),
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'cc', '2024-05-31 23:45:43', 0, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'ccc', '2024-05-31 23:46:35', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'ccc', '2024-05-31 23:46:56', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'ccc', '2024-06-01 00:41:01', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 00:47:11', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 00:55:17', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cccc', '2024-06-01 13:00:54', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cccc', '2024-06-01 13:21:15', 1, NULL),
(0, 38, 'vovo@gmail.com', 'coco@gmail.com', 'cc', '2024-06-01 13:24:35', 1, NULL),
(0, 38, 'vovo@gmail.com', 'coco@gmail.com', 'rr', '2024-06-01 13:29:21', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 13:39:10', 1, NULL),
(0, 38, 'vovo@gmail.com', 'coco@gmail.com', 'cc', '2024-06-01 13:41:07', 1, NULL),
(0, 38, 'vovo@gmail.com', 'coco@gmail.com', 'cc', '2024-06-01 13:41:16', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 13:41:44', 1, NULL),
(0, 38, 'vovo@gmail.com', 'coco@gmail.com', 'cc', '2024-06-01 13:42:01', 1, NULL),
(0, 38, 'vovo@gmail.com', 'coco@gmail.com', 'cc\r\n', '2024-06-01 13:45:09', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 13:45:28', 1, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 13:45:45', 0, NULL),
(0, 38, 'coco@gmail.com', 'vovo@gmail.com', 'cc', '2024-06-01 15:20:02', 0, NULL),
(0, 39, 'vovo@gmail.com', 'khokh@gmail.com', 'hello', '2024-06-01 21:19:53', 0, NULL),
(0, 40, 'coco@gmail.com', 'khokha@gmail.com', 'mlk', '2024-06-01 21:55:01', 0, NULL),
(0, 40, 'coco@gmail.com', 'khokha@gmail.com', 'uploads/20240601215517.coco@gmail.com.wav', '2024-06-01 21:55:17', 0, NULL),
(0, 41, 'coco@gmail.com', 'khokh@gmail.com', 'images/20240601215544.coco@gmail.com_coco@gmail.com.jpeg', '2024-06-01 21:55:44', 1, NULL),
(0, 41, 'khokh@gmail.com', 'coco@gmail.com', 'helllo', '2024-06-02 13:36:52', 0, NULL),
(0, 41, 'khokh@gmail.com', 'coco@gmail.com', 'helllo', '2024-06-02 13:37:04', 0, NULL),
(0, 41, 'khokh@gmail.com', 'coco@gmail.com', 'helllo', '2024-06-02 13:37:11', 0, NULL),
(0, 42, 'khokha@gmail.com', 'vovo@gmail.com', 'uploads/20240602134057.khokha@gmail.com.wav', '2024-06-02 13:40:57', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_1` varchar(255) NOT NULL,
  `user_2` varchar(255) NOT NULL,
  `notification` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0,
  `product_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_1`, `user_2`, `notification`, `date`, `lu`, `product_id`, `comment_id`) VALUES
(45, 'khokha@gmail.com', 'coco@gmail.com', 'chougri karima a ajouté une favori sur votre commentaire', '2024-06-02 12:42:44', 1, NULL, 42),
(46, 'coco@gmail.com', 'coco@gmail.com', 'ents cli a répondu sur votre commentaire', '2024-06-02 12:59:23', 1, NULL, 42),
(48, 'khokha@gmail.com', 'coco@gmail.com', 'chougri karima a ajouté une favori sur votre commentaire', '2024-06-02 15:36:20', 1, NULL, 43),
(49, 'khokha@gmail.com', 'coco@gmail.com', 'chougri karima a répondu sur votre commentaire', '2024-06-02 17:25:08', 1, NULL, 43),
(52, 'coco@gmail.com', 'khokha@gmail.com', 'ents cli a ajouté une favorie sur votre produit.', '2024-06-03 00:25:50', 1, 55, NULL),
(53, 'khokh@gmail.com', 'coco@gmail.com', 'cli ents a ajouté un commentaire sur votre produit.', '2024-06-03 00:56:24', 0, NULL, NULL),
(54, 'khokha@gmail.com', 'khokha@gmail.com', 'karima chougri a ajouté un commentaire sur votre produit.', '2024-06-03 01:22:09', 0, NULL, NULL),
(55, 'khokha@gmail.com', 'khokha@gmail.com', 'chougri karima a ajouté une favorie sur votre produit.', '2024-06-03 01:22:17', 0, 56, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `product_id`, `user_id`, `quantite`) VALUES
(12, 53, 62, NULL),
(13, 51, 62, NULL),
(14, 49, 61, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `score` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `categorie` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `stock`, `score`, `image`, `categorie`) VALUES
(46, 'tv', 'kyr', 0.00, 65, 0, 'client.jpeg', 'vetements'),
(47, 'hello', 'lutf', 64.00, 76465, 0, 'commente.png', 'vetements'),
(48, 'dygf', '645', 465.00, 6465, 0, 'client.jpeg', 'vetements'),
(49, 'ma9la', 'ild7ye', 54.00, 77, 0, 'commente.png', 'baute et sante'),
(50, 'sosobadri', 'a,jf', 9999.00, 7667, 0, 'client.jpeg', 'chaussures'),
(51, 'kas', 'aildyf', 0.00, 546, 0, 'favorie.jpeg', 'vetements'),
(52, 'yoyo', 'asdliufy', 65.00, 877, 0, 'commente.png', 'vetements'),
(53, 'maskara', 'liztg', 53.00, 3, 0, 'favorie.jpeg', 'vetements'),
(54, 'hiba', 'mjhc', 99999999.99, 32, 0, 'jns.jpeg', 'baute et sante'),
(55, 'khokha', 'hdsc', 0.00, 89, 0, 'commente.png', 'vetements'),
(56, 'cahier', ',juyf', 0.00, 98, 0, 'commente.png', 'vetements');

-- --------------------------------------------------------

--
-- Structure de la table `produits_vendeurs`
--

CREATE TABLE `produits_vendeurs` (
  `id` int(11) NOT NULL,
  `vendeur_id` int(11) DEFAULT NULL,
  `produit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits_vendeurs`
--

INSERT INTO `produits_vendeurs` (`id`, `vendeur_id`, `produit_id`) VALUES
(1, 22, 46),
(2, 22, 47),
(3, 22, 48),
(4, 21, 50),
(5, 22, 51),
(6, 22, 52),
(7, 23, 53),
(10, 21, 56);

-- --------------------------------------------------------

--
-- Structure de la table `produit_vendeurs`
--

CREATE TABLE `produit_vendeurs` (
  `id` int(11) NOT NULL,
  `vendeur_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reponses_commentaires`
--

CREATE TABLE `reponses_commentaires` (
  `id` int(11) NOT NULL,
  `commentaire_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `reponse` text DEFAULT NULL,
  `date_reponse` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reponses_commentaires`
--

INSERT INTO `reponses_commentaires` (`id`, `commentaire_id`, `email`, `reponse`, `date_reponse`) VALUES
(16, 42, 'coco@gmail.com', 'hhh', '2024-06-02 12:56:40'),
(17, 42, 'coco@gmail.com', 'malk asahby', '2024-06-02 12:58:58'),
(18, 42, 'coco@gmail.com', 'hh', '2024-06-02 12:59:23'),
(19, 43, 'khokha@gmail.com', 'ok', '2024-06-02 17:25:08');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `type` enum('vendeur','client') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `mot_de_passe`, `type`) VALUES
(9, 'vend@gmail.com', '', 'vendeur'),
(10, 'cli@gmail.com', '', 'client'),
(11, 'v@gmail.com', '', 'vendeur'),
(12, 'c@gmail.com', '', 'client'),
(13, 'so@gmail.com', '', 'client'),
(14, 'soso@gmail.com', '', 'client'),
(15, 'latifa@gmail.com', '', 'client'),
(16, 'diosa@gmail.com', '', 'client'),
(17, 'karima.chougri@gmail.com', '', 'client'),
(18, '77@gmail.com', '', 'client'),
(19, 'o@gmail.com', '', 'client'),
(20, 'driss@gmail.com', '', 'client'),
(21, 'mohamed@gmail.com', '', 'client'),
(22, 'mimi@gmail.com', '', 'client'),
(23, 'popo@gmail.com', '', 'client'),
(24, 'xx@gmail.com', '', 'client'),
(25, 'tabon@gmail.com', '', 'client'),
(26, 'hiba@gmail.com', '', 'client'),
(27, 'zok@gmail.com', '', 'client'),
(28, 'z@gmail.com', '', 'client'),
(29, 'trma@gmail.com', '', 'vendeur'),
(30, 'khokha@gmail.com', '', 'vendeur'),
(31, 'khokh@gmail.com', '', 'vendeur'),
(32, 'mama@gmail.com', '', 'client'),
(33, 'vovo@gmail.com', '', 'vendeur'),
(34, 'coco@gmail.com', '', 'client'),
(35, 'yones@gmail.com', '', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `vendeurs`
--

CREATE TABLE `vendeurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vendeurs`
--

INSERT INTO `vendeurs` (`id`, `nom`, `prenom`, `pays`, `ville`, `adresse`, `telephone`, `email`, `password`, `session_id`, `user_image`) VALUES
(18, 'chougri', 'vendeurs', 'maroc', 'casablanca', 'salam2groupe w rue 85 n30ahl loughlam', '0712162282', 'vend@gmail.com', '0000', NULL, 'client.jpeg'),
(19, 'chougri', 'karima', 'maroc', 'casa', '0607901058', '000000000', 'v@gmail.com', '0000', NULL, 'client.jpeg'),
(20, 'chougri', 'karima', 'wdw', 'casa', '0607901058', '000000000', 'trma@gmail.com', '$2y$10$BFUmLn6LYYU7vqZG0rqQfeoxcR1820yarU9cbqCl6GWO2y2j9y3/6', NULL, 'client.jpeg'),
(21, 'chougri', 'karima', 'maroc', 'casa', '0607901058', '000000000', 'khokha@gmail.com', '$2y$10$2Br9aw406z/zO6uQMGK6.O3SzL4LIhZZABjePMiWdsw70WfwEi24.', NULL, 'commente.png'),
(22, 'chougri', 'karima', 'maroc', 'casa', '0607901058', '000000000', 'khokh@gmail.com', '$2y$10$Y0outd6x933FQouDpGsVsupzOnz6LdZie9fqFhBqeOqUeFCRipupW', NULL, 'pardefaut.jpg'),
(23, 'deur', 'ven', 'sliyf', 'q', 'qe6r', 'qsty', 'vovo@gmail.com', '$2y$10$ercG.SoopCrVnhasWdpCfeKQYeljVDVVDHjIJOFR8jo/W5R5sQIX.', NULL, 'vendeur.jpeg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clics_utilisateurs`
--
ALTER TABLE `clics_utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email_clients` (`email`),
  ADD UNIQUE KEY `uc_session_id` (`session_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Index pour la table `commande_details`
--
ALTER TABLE `commande_details`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit_id` (`produit_id`),
  ADD KEY `email` (`email`);

--
-- Index pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur1` (`utilisateur1`),
  ADD KEY `utilisateur2` (`utilisateur2`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_email` (`user_email`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD KEY `expediteur` (`expediteur`),
  ADD KEY `destinataire` (`destinataire`),
  ADD KEY `fk_conversation_id` (`conversation_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_1` (`user_1`),
  ADD KEY `user_2` (`user_2`),
  ADD KEY `fk_comment_id` (`comment_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits_vendeurs`
--
ALTER TABLE `produits_vendeurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendeur_id` (`vendeur_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `produit_vendeurs`
--
ALTER TABLE `produit_vendeurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendeur_id` (`vendeur_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `reponses_commentaires`
--
ALTER TABLE `reponses_commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commentaire_id` (`commentaire_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email_vendeurs` (`email`),
  ADD UNIQUE KEY `uc_session_id` (`session_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clics_utilisateurs`
--
ALTER TABLE `clics_utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commande_details`
--
ALTER TABLE `commande_details`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `produits_vendeurs`
--
ALTER TABLE `produits_vendeurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `produit_vendeurs`
--
ALTER TABLE `produit_vendeurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reponses_commentaires`
--
ALTER TABLE `reponses_commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `vendeurs`
--
ALTER TABLE `vendeurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `clics_utilisateurs`
--
ALTER TABLE `clics_utilisateurs`
  ADD CONSTRAINT `clics_utilisateurs_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `utilisateurs` (`email`),
  ADD CONSTRAINT `clics_utilisateurs_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `commentaires` (`id`);

--
-- Contraintes pour la table `commande_details`
--
ALTER TABLE `commande_details`
  ADD CONSTRAINT `commande_details_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `commande_details_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`email`) REFERENCES `utilisateurs` (`email`);

--
-- Contraintes pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`utilisateur1`) REFERENCES `utilisateurs` (`email`),
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`utilisateur2`) REFERENCES `utilisateurs` (`email`);

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`user_email`) REFERENCES `utilisateurs` (`email`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_conversation_id` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`expediteur`) REFERENCES `utilisateurs` (`email`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`destinataire`) REFERENCES `utilisateurs` (`email`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_comment_id` FOREIGN KEY (`comment_id`) REFERENCES `commentaires` (`id`),
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_1`) REFERENCES `utilisateurs` (`email`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_2`) REFERENCES `utilisateurs` (`email`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`),
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `clients` (`id`);

--
-- Contraintes pour la table `produits_vendeurs`
--
ALTER TABLE `produits_vendeurs`
  ADD CONSTRAINT `fk_vendeur_id` FOREIGN KEY (`vendeur_id`) REFERENCES `vendeurs` (`id`),
  ADD CONSTRAINT `produits_vendeurs_ibfk_1` FOREIGN KEY (`vendeur_id`) REFERENCES `vendeurs` (`id`),
  ADD CONSTRAINT `produits_vendeurs_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `produit_vendeurs`
--
ALTER TABLE `produit_vendeurs`
  ADD CONSTRAINT `produit_vendeurs_ibfk_1` FOREIGN KEY (`vendeur_id`) REFERENCES `vendeurs` (`id`),
  ADD CONSTRAINT `produit_vendeurs_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `reponses_commentaires`
--
ALTER TABLE `reponses_commentaires`
  ADD CONSTRAINT `reponses_commentaires_ibfk_1` FOREIGN KEY (`commentaire_id`) REFERENCES `commentaires` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
