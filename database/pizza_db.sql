-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2024 at 07:46 AM
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
-- Database: `pizza_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(8, 'FadhilHensem', '8cb2237d0679ca88db6464eac60da96345513964');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(12, 6, 6, 'Mushroom Sensation', 25, 1, 'pizza-5.jpg'),
(16, 7, 10, 'Beef Peperoni', 45, 4, 'pizza-11.jpg'),
(17, 7, 3, 'Mushroom Carbonara Pizza', 40, 1, 'pizza-2.jpg'),
(19, 6, 15, 'Pizza sedap', 25, 3, 'home-img-3.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(2, 3, 'zad', '01212125254', 'credit card', 'flat no.taman shamelin, kuala lumpur - ', 'Mushroom Carbonara Pizza ( 40 x 2 ) - Cheezy Pizza ( 20 x 2 ) - Minced Beef Pizza ( 55 x 1 ) - ', 175, '2024-03-25', 'completed'),
(3, 4, 'FadhilAmin', '01212125254', 'Ewallet', 'flat no.taman shamelin, kuala lumpur - ', 'Mushroom Carbonara Pizza ( 40 x 2 ) - Mushroom Sensation ( 25 x 1 ) - ', 105, '2024-03-25', 'completed'),
(5, 6, 'Zurina', '0123456789', 'online banking', 'flat no.uptm, kl - ', 'Beef Peperoni ( 45 x 4 ) - Veggie Pizza Delight ( 35 x 1 ) - ', 215, '2024-04-03', 'completed'),
(6, 7, 'Amirul', '0123456789', 'cash on delivery', 'flat no.taman shamelin, kuala lumpur - ', 'Beef Peperoni ( 45 x 4 ) - Mushroom Carbonara Pizza ( 40 x 1 ) - ', 220, '2024-04-05', 'completed'),
(7, 8, 'anwar', '0123456789', 'Ewallet', 'flat no.taman shamelin, kuala lumpur - ', 'Beef Peperoni ( 45 x 2 ) - ', 90, '2024-04-10', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`) VALUES
(2, 'Cheezy Pizza', 20, 'pizza-1.jpg'),
(3, 'Mushroom Carbonara Pizza', 40, 'pizza-2.jpg'),
(4, 'Salted Egg Pizza', 45, 'pizza-3.jpg'),
(5, 'Smoked Mushroom Pizza', 30, 'pizza-4.jpg'),
(6, 'Mushroom Sensation', 25, 'pizza-5.jpg'),
(7, 'Minced Beef Pizza', 55, 'pizza-6.jpg'),
(8, 'Veggie Pizza', 20, 'pizza-7.jpg'),
(9, 'Veggie Pizza Delight', 35, 'pizza-9.jpg'),
(10, 'Beef Peperoni', 45, 'pizza-11.jpg'),
(12, 'Peperoni Pizza', 30, 'home-img-1.png'),
(14, 'Capsicum Pizza', 20, 'home-img-2.png'),
(15, 'Pizza sedap', 25, 'home-img-3.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`) VALUES
(3, 'zadnemesis', 'aidilaizzad@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(4, 'FadhilAmin', 'fadhilamin815@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(6, 'zurina', 'zurina@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(7, 'Amirul', 'amirulhafiz@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(8, 'Anwar', 'anwardaniel@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
