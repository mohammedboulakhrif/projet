-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 17 août 2024 à 03:44
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
-- Base de données : `mon_projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_category` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `placed_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `address`, `city`, `country`, `zip`, `placed_on`, `status`) VALUES
(1, 2, 'lyna', '974937503', 'laval', 'montreal', 'canada', 'h23h83', '2024-08-11 16:20:14', 'pending'),
(2, 2, 'simo', 'l', 'simo', 'simo', 'France', 'lalalala', '2024-08-11 16:20:30', 'pending'),
(3, 1, 'simo', 'dp', 'simo', 'simo', 'France', 'lalalala', '2024-08-15 22:55:26', 'pending'),
(4, 2, 'simo', '06635482739', 'simo', 'simo', 'France', 'lalalala', '2024-08-16 00:06:31', 'pending'),
(5, 2, 'simo', '06635482739', 'simo', 'simo', 'France', 'lalalala', '2024-08-16 00:29:06', 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `product_name`, `product_image`) VALUES
(1, 1, 5, 1, 149.99, 'Armani', '../uploads/armani.jpg'),
(2, 1, 3, 1, 200.00, 'Sauvage ', '../uploads/dior.jpg'),
(3, 1, 8, 1, 287.99, 'Dior', '../uploads/Homme.jpg'),
(4, 2, 2, 1, 300.99, 'Myway', '../uploads/myway.jpg'),
(5, 3, 3, 1, 200.00, 'Sauvage ', '../uploads/dior.jpg'),
(6, 3, 2, 1, 300.99, 'Myway', '../uploads/myway.jpg'),
(7, 4, 2, 2, 299.99, 'Myway', '../uploads/myway.jpg'),
(8, 4, 1, 1, 149.99, 'YVL', '../uploads/yvl.jpg'),
(9, 5, 2, 1, 299.99, 'Myway', '../uploads/myway.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `prix`, `category`) VALUES
(1, 'YVL', 'Un parfum boisé et épicé qui incarne la force et l\'élégance', '../uploads/yvl.jpg', 5.00, 'femme'),
(2, 'Myway', 'Un parfum frais et aquatique qui évoque la liberté et l\'aventure', '../uploads/myway.jpg', 299.99, 'femme'),
(3, 'Sauvage ', 'Un parfum chaleureux et mystérieux, mêlant des notes de cannelle', '../uploads/dior.jpg', 200.00, 'homme'),
(4, 'Burberrry', 'Un parfum frais et pétillant, avec des notes de mandarine', '../uploads/Burberry.jpg', 166.99, 'femme'),
(5, 'Armani', 'Un parfum floral et délicat qui incarne la grâce et la féminité.', '../uploads/armani.jpg', 149.99, 'homme'),
(7, 'Chanel', 'Un parfum gourmand et voluptueux, avec des notes de vanille', '../uploads/Chanel.jpg', 197.66, 'femme'),
(8, 'Dior', 'Un parfum frais et vivifiant, avec des notes de pin, de citron vert', '../uploads/Homme.jpg', 287.99, 'homme'),
(11, 'Valanteno', 'Un parfum frais et vert, inspiré par la nature sauvage', '../uploads/Valentino.jpg', 210.99, 'homme');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`) VALUES
(1, 'simo@gmail.com', '$2y$10$9j2URr/19sosSg4sQDbkYODgNEuFXivQfCWH712Su/bo9VEm/N/Si', 'admin'),
(2, 'lyna@gmail.com', '$2y$10$Sc0.eLY.MJ8bIIvWcw91ZecJDRCvJ2wjqajUysV0GvYFiS5kbzTFi', 'user'),
(3, 'balou@gmail.com', '$2y$10$9ppUxe9aWzw/IdttBwBoH.73uEaXyAN9Yw22AR6Fs/av70Bo4HUji', 'user'),
(4, 'tine@gmail.com', '$2y$10$R/8SKTAXk3NucJetv1lnI.x6gtDbDVxik4h6UgfDw4jeNnB6y4p..', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
