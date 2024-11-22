-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 09:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pp`
--

-- --------------------------------------------------------

--
-- Table structure for table `dance_groups`
--

CREATE TABLE `dance_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picture_url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dance_groups`
--

INSERT INTO `dance_groups` (`id`, `name`, `picture_url`, `description`) VALUES
(1, 'The Jabbawockeez', 'https://wallpapers.com/images/hd/jabbawockeez-369rtfh0wij54jwa.jpg', 'Known for their iconic white masks and synchronized movements, this group gained worldwide recognition after winning the first season of America\'s Best Dance Crew.'),
(2, 'The Kinjaz', 'https://store.kinjaz.com/cdn/shop/files/Tops-placeholder_1000x.png?v=1613571968', 'Combining hip-hop with martial arts and intricate choreography, The Kinjaz have made a name with their precision and creative storytelling, seen in shows like World of Dance.'),
(3, 'Quest Crew', 'https://img.haikudeck.com/mg/72C14BF1-EF33-420A-BC9B-01A3C086E0A7.jpg', 'Another champion of America\'s Best Dance Crew, Quest Crew is known for its high-energy, acrobatic style and has collaborated with artists like LMFAO.'),
(4, 'Super Cr3w', 'https://images1.fanpop.com/images/photos/2100000/Super-Cr3w-super-cr3w-2144108-590-365.jpg', 'This B-boy group from Las Vegas won the second season of America\'s Best Dance Crew and is celebrated for their athleticism and creativity in hip-hop dance.'),
(5, 'The Royal Family', 'https://res.cloudinary.com/dwzhqvxaz/image/upload/v1662111475/Titles/The%20Royal%20Family%20Returns/TheRoyalFamilyReturns_Prod_1920x1080.jpg', 'This New Zealand-based crew, led by choreographer Parris Goebel, is known for its powerful performances and has worked with artists like Rihanna and Justin Bieber.'),
(6, 'Les Twins', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAgUE69AsGSihRhDau9w4tBa69WldEBsrQ2Q&s', 'French twin brothers Laurent and Larry Bourgeois are renowned for their unique freestyle hip-hop style and have toured with Beyoncé as well as won World of Dance.');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dance_group_id` int(11) NOT NULL,
  `rating` tinyint(4) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `dance_group_id`, `rating`, `comment`, `created_at`) VALUES
(6, 1, 1, 3, 'nice', '2024-11-13 13:56:30'),
(7, 1, 1, 3, 'nice', '2024-11-13 14:00:13'),
(8, 1, 1, 3, 'nice', '2024-11-13 14:00:46'),
(9, 2, 1, 2, 'wow', '2024-11-13 14:01:45'),
(10, 2, 2, 5, 'cold frezz', '2024-11-13 14:02:13'),
(11, 2, 3, 3, 'basic', '2024-11-13 14:02:23'),
(12, 3, 3, NULL, '', '2024-11-15 07:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `first_login` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `first_login`) VALUES
(1, 'joshua', '$2y$10$dYVwCpD0Ua5qd.KDI.QhZ.HYUmh.q00iLe9Hl3wWfhisFMxaTSs9O', '2024-11-13 21:24:01', 1),
(2, 'pacx', '$2y$10$IeCihX14FDtL/p9fSKPFNuc8FhqsJOPp0AcWCoyCt2oSYx7kGunhO', '2024-11-13 22:01:26', 1),
(3, 'tabique', '$2y$10$y3Kn8bijn.frakDEEcW1I.eePK2ZePsuekEi917mkhyicltROdmNS', '2024-11-15 15:12:14', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dance_groups`
--
ALTER TABLE `dance_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dance_group_id` (`dance_group_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dance_groups`
--
ALTER TABLE `dance_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`dance_group_id`) REFERENCES `dance_groups` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
