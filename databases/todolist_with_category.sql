-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  jeu. 11 mars 2021 à 09:20
-- Version du serveur :  8.0.18
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `todolist_with_category`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(6, 'Deserunt.'),
(7, 'Assumenda.'),
(8, 'Quibusdam.'),
(9, 'Harum.'),
(10, 'Dolores.');

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB25A76ED395` (`user_id`),
  KEY `IDX_527EDB2512469DE2` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `user_id`, `category_id`, `created_at`, `title`, `content`, `is_done`) VALUES
(40, NULL, NULL, '2020-03-22 01:25:03', 'Tache n°0', 'Repellendus iure ab animi voluptates possimus et unde.', 1),
(41, NULL, NULL, '2021-02-03 00:02:16', 'Tache n°1', 'Cumque voluptatem nihil ullam amet repudiandae eveniet voluptatem ut.', 1),
(42, NULL, NULL, '2019-12-20 14:12:40', 'Tache n°2', 'Quidem animi repellendus ut tempore explicabo quasi.', 1),
(43, NULL, NULL, '2020-12-22 18:51:20', 'Tache n°3', 'Quia iusto mollitia maxime id distinctio a corporis sunt animi ipsam.', 0),
(44, NULL, NULL, '2019-03-12 15:10:30', 'Tache n°4', 'Dolore quos eos perspiciatis et odio minima cumque rem qui rerum sint exercitationem.', 0),
(45, NULL, NULL, '2020-07-13 02:13:41', 'Tache n°5', 'Dignissimos qui et pariatur eius incidunt voluptas qui quia ipsum aut sunt.', 1),
(46, NULL, NULL, '2021-01-13 13:41:26', 'Tache n°6', 'Harum deleniti quia ipsam dolores aut aut est et nulla odit cum.', 1),
(47, NULL, NULL, '2019-10-23 08:55:59', 'Tache n°7', 'Et ex vitae quae earum modi qui.', 0),
(48, NULL, NULL, '2019-07-01 11:26:59', 'Tache n°8', 'Dignissimos quasi iusto aut nostrum consequatur quo maiores neque non alias aut et possimus.', 1),
(49, NULL, NULL, '2021-01-22 07:03:48', 'Tache n°9', 'Error natus dolores voluptas veritatis dolorem laudantium enim perferendis.', 1),
(50, NULL, NULL, '2019-12-29 17:02:21', 'Tache n°10', 'Minus voluptatibus quo eaque et fuga adipisci porro.', 1),
(51, NULL, NULL, '2020-02-10 09:51:16', 'Tache n°11', 'Est voluptates qui expedita tempore debitis hic nobis deserunt tempora.', 0),
(52, NULL, NULL, '2020-03-27 00:54:38', 'Tache n°12', 'Aut hic culpa id vel mollitia totam.', 0),
(53, NULL, NULL, '2020-02-07 08:17:25', 'Tache n°13', 'Ut et aut voluptatem ipsam quisquam a suscipit amet placeat.', 1),
(54, NULL, NULL, '2019-12-13 14:59:32', 'Tache n°14', 'Voluptas voluptatem minus ratione ipsum fuga qui.', 0),
(55, NULL, NULL, '2021-02-04 23:59:01', 'Tache n°15', 'Consequatur fugiat minima alias unde recusandae voluptatem omnis doloribus repellat corporis id.', 1),
(56, NULL, NULL, '2019-11-15 07:28:21', 'Tache n°16', 'Impedit esse aliquam perferendis laborum at quaerat aut.', 1),
(57, NULL, NULL, '2020-12-15 23:34:32', 'Tache n°17', 'Nihil nam eos voluptas voluptas est et iste qui totam accusamus enim.', 1),
(58, NULL, NULL, '2020-06-09 00:32:27', 'Tache n°18', 'Molestiae dolor reprehenderit veniam in aperiam dolorem aut.', 0),
(59, NULL, NULL, '2020-05-25 20:59:26', 'Tache n°19', 'Veritatis omnis tempore molestias voluptas fugit dicta quisquam sed laudantium aperiam.', 1),
(60, 3, 9, '2021-03-11 08:53:27', 'test nouvelle tache', 'Nouvelle tache avec une categorie', 0),
(61, 3, 6, '2021-03-11 09:19:22', 'test nouvelle tache 2', 'test nouvelle tache avec nouvelle categorie', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `roles`) VALUES
(3, 'matanna', '$2y$13$zl4HnvVeY.ywEvOZ6JpWmeqiRFvLp36eAr9NvIr0etw8GnbLZKUAm', 'matanna@orange.fr', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(4, 'bibiche23', '$2y$13$SFQrWjRUfW5k.ZFd2EnKf.gzvXqQ9k/ioS1f6P6xrD6FE6mBEk82S', 'bibiche23@free.fr', 'a:1:{i:0;s:9:\"ROLE_USER\";}');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB2512469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
