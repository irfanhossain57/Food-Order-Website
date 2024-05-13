-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 12:21 AM
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
-- Database: `food_order`
--

-- --------------------------------------------------------

--
-- Table structure for table `added_to`
--

CREATE TABLE `added_to` (
  `Food_id` varchar(20) NOT NULL,
  `Cart_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `add_ons`
--

CREATE TABLE `add_ons` (
  `Add_ons_id` varchar(99) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Price` float NOT NULL,
  `Food_Type` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_ons`
--

INSERT INTO `add_ons` (`Add_ons_id`, `Name`, `Price`, `Food_Type`) VALUES
('add1', 'tomato', 20, 'burger'),
('add2', 'cheese', 50, 'pizza'),
('add3', 'chicken ', 100, 'pizza'),
('add4', 'egg', 50, 'burger'),
('add5', 'olives', 30, 'pizza');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_id` bigint(20) UNSIGNED NOT NULL,
  `Usename` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Cart_id` varchar(20) NOT NULL,
  `Total_price` float NOT NULL,
  `Customer_id` varchar(20) NOT NULL,
  `Resturant_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `Cart_item_id` varchar(20) NOT NULL,
  `cart_id` varchar(20) NOT NULL,
  `Items` varchar(100) NOT NULL,
  `Amount` float NOT NULL,
  `Price` double NOT NULL,
  `Flag` varchar(11) NOT NULL,
  `Resturant_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collects_from`
--

CREATE TABLE `collects_from` (
  `Delivary_man_id` varchar(20) NOT NULL,
  `Resturant_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contains`
--

CREATE TABLE `contains` (
  `Food_id` varchar(20) NOT NULL,
  `Resturant_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_id` varchar(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Phone` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_id`, `Name`, `Phone`, `Username`, `Password`) VALUES
('1', 'Alif', 1612345678, 'alifrahman', 'qwerty123'),
('10012', 'Irfan ', 1734006005, 'Irfan Hossain12', 'adfdfa23'),
('10056', 'Saadman zaman', 1734006007, 'saadman@45', 'sdnckjdbc7');

-- --------------------------------------------------------

--
-- Table structure for table `customer_city`
--

CREATE TABLE `customer_city` (
  `Customer_id` varchar(20) NOT NULL,
  `City` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_city`
--

INSERT INTO `customer_city` (`Customer_id`, `City`, `location`) VALUES
('1', 'Badda', 'H#6,R#9,Block-G,Madha Badda, Dhaka.'),
('1', 'Banani', 'H#3, R#5, Block-H, Banani, Dhaka.'),
('1', 'Mirpur', 'H#8,R#1, Block-C, Mirpur-1,Dhaka-1216.');

-- --------------------------------------------------------

--
-- Table structure for table `customer_location`
--

CREATE TABLE `customer_location` (
  `Customer_id` varchar(20) NOT NULL,
  `Location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivary_man`
--

CREATE TABLE `delivary_man` (
  `Delivary_man_id` varchar(20) NOT NULL,
  `Rating` double(2,1) NOT NULL,
  `Phone` int(11) NOT NULL,
  `Email` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivary_man_email`
--

CREATE TABLE `delivary_man_email` (
  `Delivary_man_id` varchar(20) NOT NULL,
  `Email` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_item`
--

CREATE TABLE `food_item` (
  `Food_id` varchar(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `Price` float NOT NULL,
  `resturant_name` varchar(99) NOT NULL,
  `Food_Type` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_item`
--

INSERT INTO `food_item` (`Food_id`, `Name`, `Description`, `Price`, `resturant_name`, `Food_Type`) VALUES
('food1', 'Classic Burger', 'Classic chicken burger with signature sauce', 250, 'Mad Chef', 'burger'),
('food10', 'Slider Burger', 'Tiny Burgers', 100, 'Pizza Burg', 'burger'),
('food2', 'classic Pizza', 'Hand Tossed Pizza with Italian Topping', 500, 'Mad Chef', 'pizza'),
('food3', 'Cheese Burger', 'Classic burger with cheese', 300, 'Pizza Burg', 'burger'),
('food4', 'Italian Basil Pizza', 'Classic italian Pizza', 769, 'Pizza Burg', 'pizza'),
('food5', 'BBQ Meat pizza', 'Topped with beef & chicken both with freshly cut vegetables, cheese & in a soft pizza dough', 700, 'Pizza Burg', 'pizza'),
('food6', 'Sausage Pizza', 'Sausage, Mushroom, Spice.', 900, 'Pizza Burg', 'pizza'),
('food7', 'Vanilla Milkshake', 'Thick & creamy delicious drink with vanilla extracts', 250, 'Mad Chef', 'beverages'),
('food71', 'Iced Lemon Tea', 'A refreshing drink to boost up your energy', 200, 'Pizza Burg', 'beverages'),
('food8', 'Hot Chocolate', 'Prepared with steamed milk & chocolate sauce', 300, 'Mad Chef', 'beverages'),
('food9', 'Espresso', 'A hot shot of espresso, sugar, hot water & creamy milk', 120, 'Mad Chef', 'beverages');

-- --------------------------------------------------------

--
-- Table structure for table `include`
--

CREATE TABLE `include` (
  `Add_ons_id` varchar(99) NOT NULL,
  `Food_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manages`
--

CREATE TABLE `manages` (
  `Admin_id` bigint(20) UNSIGNED NOT NULL,
  `Resturant_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Customer_id` varchar(20) NOT NULL,
  `Food_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_id` varchar(20) NOT NULL,
  `Date` date NOT NULL,
  `Time` time(6) NOT NULL,
  `Mobile_Banking` tinyint(1) NOT NULL,
  `Cash_on_delivary` tinyint(1) NOT NULL,
  `Credit_card` tinyint(1) NOT NULL,
  `Customer_id` varchar(20) NOT NULL,
  `cart_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `Order_id` int(20) NOT NULL,
  `Customer_id` varchar(20) NOT NULL,
  `Items` varchar(100) NOT NULL,
  `Amount` int(20) NOT NULL,
  `Price` int(20) NOT NULL,
  `Payment_method` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resturant`
--

CREATE TABLE `resturant` (
  `Resturant_id` varchar(20) NOT NULL,
  `Name` varchar(35) NOT NULL,
  `Rating` decimal(2,1) NOT NULL,
  `City` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resturant`
--

INSERT INTO `resturant` (`Resturant_id`, `Name`, `Rating`, `City`) VALUES
('rest1', 'Mad Chef', 7.7, 'Badda'),
('rest2', 'Pizza Burg', 8.8, 'Gulshan'),
('rest3', 'Burger King', 9.9, 'banani'),
('rest4', 'Steakout', 4.1, 'Gulshan'),
('rest5', 'Arax', 8.7, 'Mohakhali'),
('rest6', 'Takeout', 7.2, 'Badda'),
('rest7', 'Tasty Treat', 7.8, 'Gulshan');

-- --------------------------------------------------------

--
-- Table structure for table `selects_from`
--

CREATE TABLE `selects_from` (
  `Customer_id` varchar(20) NOT NULL,
  `Resturant_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `added_to`
--
ALTER TABLE `added_to`
  ADD KEY `Cart_id` (`Cart_id`),
  ADD KEY `Food_id` (`Food_id`);

--
-- Indexes for table `add_ons`
--
ALTER TABLE `add_ons`
  ADD PRIMARY KEY (`Add_ons_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_id`),
  ADD UNIQUE KEY `Admin_id` (`Admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_id`),
  ADD KEY `Customer_id` (`Customer_id`),
  ADD KEY `Resturant_id` (`Resturant_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`Cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `test` (`Resturant_id`);

--
-- Indexes for table `contains`
--
ALTER TABLE `contains`
  ADD KEY `Food_id` (`Food_id`),
  ADD KEY `Resturant_id` (`Resturant_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_id`);

--
-- Indexes for table `customer_city`
--
ALTER TABLE `customer_city`
  ADD PRIMARY KEY (`Customer_id`,`City`,`location`),
  ADD KEY `Customer_id` (`Customer_id`);

--
-- Indexes for table `customer_location`
--
ALTER TABLE `customer_location`
  ADD KEY `Customer_id` (`Customer_id`);

--
-- Indexes for table `delivary_man`
--
ALTER TABLE `delivary_man`
  ADD PRIMARY KEY (`Delivary_man_id`);

--
-- Indexes for table `delivary_man_email`
--
ALTER TABLE `delivary_man_email`
  ADD KEY `Delivary_man_id` (`Delivary_man_id`);

--
-- Indexes for table `food_item`
--
ALTER TABLE `food_item`
  ADD PRIMARY KEY (`Food_id`);

--
-- Indexes for table `include`
--
ALTER TABLE `include`
  ADD KEY `Food_id` (`Food_id`),
  ADD KEY `Add_ons_id` (`Add_ons_id`);

--
-- Indexes for table `manages`
--
ALTER TABLE `manages`
  ADD UNIQUE KEY `Admin_id` (`Admin_id`),
  ADD KEY `Resturant_id` (`Resturant_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD KEY `Customer_id` (`Customer_id`),
  ADD KEY `Food_id` (`Food_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `Customer_id` (`Customer_id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`Order_id`,`Customer_id`),
  ADD KEY `customer_id` (`Customer_id`);

--
-- Indexes for table `resturant`
--
ALTER TABLE `resturant`
  ADD PRIMARY KEY (`Resturant_id`);

--
-- Indexes for table `selects_from`
--
ALTER TABLE `selects_from`
  ADD KEY `Customer_id` (`Customer_id`),
  ADD KEY `Resturant_id` (`Resturant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manages`
--
ALTER TABLE `manages`
  MODIFY `Admin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `added_to`
--
ALTER TABLE `added_to`
  ADD CONSTRAINT `added_to_ibfk_1` FOREIGN KEY (`Cart_id`) REFERENCES `cart` (`Cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `added_to_ibfk_2` FOREIGN KEY (`Food_id`) REFERENCES `food_item` (`Food_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`Resturant_id`) REFERENCES `resturant` (`Resturant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `dsd` FOREIGN KEY (`cart_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test` FOREIGN KEY (`Resturant_id`) REFERENCES `resturant` (`Resturant_id`);

--
-- Constraints for table `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `contains_ibfk_1` FOREIGN KEY (`Food_id`) REFERENCES `food_item` (`Food_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contains_ibfk_2` FOREIGN KEY (`Resturant_id`) REFERENCES `resturant` (`Resturant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_city`
--
ALTER TABLE `customer_city`
  ADD CONSTRAINT `customer_city_ibfk_1` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_location`
--
ALTER TABLE `customer_location`
  ADD CONSTRAINT `customer_location_ibfk_1` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivary_man_email`
--
ALTER TABLE `delivary_man_email`
  ADD CONSTRAINT `delivary_man_email_ibfk_1` FOREIGN KEY (`Delivary_man_id`) REFERENCES `delivary_man` (`Delivary_man_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `include`
--
ALTER TABLE `include`
  ADD CONSTRAINT `include_ibfk_1` FOREIGN KEY (`Food_id`) REFERENCES `food_item` (`Food_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `include_ibfk_2` FOREIGN KEY (`Add_ons_id`) REFERENCES `add_ons` (`Add_ons_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `manages`
--
ALTER TABLE `manages`
  ADD CONSTRAINT `manages_ibfk_1` FOREIGN KEY (`Admin_id`) REFERENCES `admin` (`Admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `manages_ibfk_2` FOREIGN KEY (`Resturant_id`) REFERENCES `resturant` (`Resturant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`Food_id`) REFERENCES `food_item` (`Food_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`Cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `customer_fk` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selects_from`
--
ALTER TABLE `selects_from`
  ADD CONSTRAINT `selects_from_ibfk_1` FOREIGN KEY (`Customer_id`) REFERENCES `customer` (`Customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selects_from_ibfk_2` FOREIGN KEY (`Resturant_id`) REFERENCES `resturant` (`Resturant_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
