-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  jeu. 11 mars 2021 à 07:39
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
-- Base de données :  `todolist`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210207105410', '2021-02-07 10:54:38', 257),
('DoctrineMigrations\\Version20210208192210', '2021-02-08 19:22:36', 87);

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB25A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `is_done`, `user_id`) VALUES
(4, '2019-12-19 16:52:17', 'Tache n°0', 'Est aliquam architecto corrupti id consectetur reiciendis veritatis aut omnis voluptate rem est.', 0, NULL),
(5, '2020-01-07 06:28:21', 'Tache n°1', 'Eius ut quia dolor voluptatem voluptatibus deserunt quia suscipit inventore sit animi.', 0, NULL),
(6, '2020-06-05 17:54:12', 'Tache n°2', 'Delectus optio cumque veritatis est laborum minus voluptate necessitatibus hic autem.', 0, NULL),
(8, '2019-12-24 12:21:18', 'Tache n°4', 'Nemo sit odit laborum ut nam sunt asperiores provident praesentium.', 1, NULL),
(9, '2019-10-08 14:46:18', 'Tache n°5', 'Molestiae voluptatem ipsam et distinctio illum saepe numquam.', 1, NULL),
(12, '2020-05-14 21:28:35', 'Tache n°8', 'Non occaecati dolorem aut ut est totam fugiat et et nihil.', 1, NULL),
(13, '2019-06-15 10:26:33', 'Tache n°9', 'Laborum nulla rerum placeat aut quod reiciendis et iste.', 1, NULL),
(16, '2020-05-03 13:08:43', 'Tache n°12', 'Est ratione dolores eius praesentium et beatae provident eaque cumque asperiores eligendi a.', 0, NULL),
(18, '2019-11-06 17:18:37', 'Tache n°14', 'Nemo repellendus molestiae illum consequuntur exercitationem voluptatum in sapiente omnis deleniti porro.', 0, NULL),
(19, '2020-09-19 21:24:19', 'Tache n°15', 'Quod ut veritatis delectus et quod et omnis esse.', 1, NULL),
(20, '2019-12-15 23:06:20', 'Tache n°16', 'Dignissimos possimus consequatur quibusdam aut reprehenderit est.', 1, NULL),
(21, '2020-10-06 18:39:03', 'Tache n°17', 'Ipsam explicabo reiciendis laborum qui enim perspiciatis eius pariatur dolores necessitatibus laborum aut ea.', 1, NULL),
(22, '2020-03-30 21:09:55', 'Tache n°18', 'Placeat similique expedita voluptas quia aut magnam quia nihil doloribus eius et architecto.', 1, NULL),
(23, '2019-06-16 01:24:53', 'Tache n°19', 'Pariatur amet quis eos id commodi sequi est.', 0, NULL),
(24, '2021-02-07 11:00:33', 'test nouvelle tache', 'blablablablablo', 0, 3),
(27, '2021-02-25 09:18:38', 'test nouvelle tache 2', 'stghstfghsxfg', 0, 3),
(28, '2021-03-06 08:49:53', 'Je teste une tache', 'pour le cache', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `roles`) VALUES
(3, 'matanna', '$2y$13$zl4HnvVeY.ywEvOZ6JpWmeqiRFvLp36eAr9NvIr0etw8GnbLZKUAm', 'matanna@orange.fr', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(4, 'bibiche23', '$2y$13$IxmGqQrHfXu9KPBhhT7XuuydbaD5w01DU8H01WrCLKdEInlndLpvm', 'bibiche23@free.fr', NULL),
(5, 'matanna23', '$2y$13$EFyWuW3KCOyR7SuD5m49uOOKMf/4D/xCpF1INi1t0VraY2b0zKfTy', 'matanna23@orange.fr', 'N;'),
(6, 'lilou44', '$2y$13$jNQyGeaFx7WtZB5J4n06C.FpKmvE8cTgHgfwyAPt2ONtKeaPEHjn6', 'lilou44@orange.fr', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(7, 'mya54', '$2y$13$Ly2tyJLQLoXAtXpoKSagGO9LoqRRUnpQ0Ao25.NZR4ngoiMgXWSl.', 'mya54@orange.fr', 'N;');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
