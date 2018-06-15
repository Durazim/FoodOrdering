-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2018 at 03:05 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `rid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `location` varchar(30) NOT NULL,
  `openTime` time NOT NULL,
  `closeTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`rid`, `bid`, `phone`, `location`, `openTime`, `closeTime`) VALUES
(5, 1, '17123122', 'manama', '07:45:00', '10:47:00'),
(5, 2, '17123121', 'sitra', '17:51:00', '20:51:00'),
(6, 1, '17558558', 'muharraq', '14:03:00', '21:03:00'),
(6, 2, '17558559', 'manama', '15:03:00', '18:07:00'),
(7, 1, '66333940', 'muharraq', '20:42:00', '20:42:00'),
(7, 2, '66454447', 'riffa', '12:00:00', '23:45:00'),
(9, 1, '17422222', 'sitra', '11:00:00', '22:45:00'),
(9, 2, '17522222', 'riffa', '11:00:00', '22:45:00'),
(10, 1, '17669993', 'ras rumman', '09:00:00', '23:50:00'),
(10, 2, '17669994', 'riffa', '09:00:00', '23:50:00'),
(11, 1, '17888999', 'karrana', '08:45:55', '23:45:00'),
(11, 2, '17888991', 'dair', '08:45:55', '23:45:00'),
(12, 1, '13225566', 'ras rumman', '08:30:00', '23:59:00'),
(13, 1, '13456223', 'manama', '06:00:00', '20:00:00'),
(14, 1, '17986532', 'arad', '07:00:00', '21:30:50'),
(15, 1, '17753159', 'muharraq', '08:55:00', '21:00:00'),
(16, 1, '17654632', 'dair', '13:30:00', '23:00:00'),
(17, 1, '17555858', 'duraz', '14:00:00', '23:00:00'),
(18, 1, '17556985', 'duraz', '09:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CID` int(11) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(35) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `password` varchar(32) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `dob` date NOT NULL,
  `registerDate` date NOT NULL,
  `profileImage` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CID`, `firstName`, `lastName`, `username`, `email`, `phone`, `password`, `gender`, `dob`, `registerDate`, `profileImage`) VALUES
(46, 'Mahmood', 'Durazi', 'asd', 'a@a.com', '34', 'a59f54575e8633b48cd37db17ffa829b', 'Male', '2018-04-24', '2018-04-24', 'profile.png'),
(49, 'ahmed', 'albosta', 'bosta11', 'akwawali@gmail.com', '66355635', '827ccb0eea8a706c4c34a16891f84e7b', 'Male', '1997-06-29', '2018-04-26', 'profile.png'),
(50, 'mahmood', 'durazi', 'durazi', 'all2@gmail.com', '38020077', '827ccb0eea8a706c4c34a16891f84e7b', 'Male', '1990-01-01', '2018-04-26', 'profile.png'),
(51, 'hh', 'hh', 'hhh', 'hh@f.hhh', '12345678', '5c8e06d2e6b8b6486b5cbc95dd698350', 'Male', '2018-05-07', '2018-05-07', 'profile.png');

-- --------------------------------------------------------

--
-- Table structure for table `customeraddress`
--

CREATE TABLE `customeraddress` (
  `AID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Building` varchar(20) NOT NULL,
  `Block` int(11) NOT NULL,
  `Road` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customeraddress`
--

INSERT INTO `customeraddress` (`AID`, `CID`, `City`, `Building`, `Block`, `Road`) VALUES
(31, 46, '1', '1', 1, '1'),
(32, 49, 'rasrumman', '57', 603, '601');

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

CREATE TABLE `dish` (
  `rid` int(11) NOT NULL,
  `DID` int(11) NOT NULL,
  `DishName` varchar(20) NOT NULL,
  `Price` float NOT NULL,
  `Description` varchar(50) NOT NULL,
  `Type` varchar(25) NOT NULL,
  `Image` varchar(20) NOT NULL,
  `Rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`rid`, `DID`, `DishName`, `Price`, `Description`, `Type`, `Image`, `Rating`) VALUES
(5, 6, 'vegetable pasta', 2.2, 'Italian pasta with fresh vegetables', 'pasta', '5a.jpg', 5),
(5, 7, 'double meet burger', 1.8, 'double meet burger with fresh bread', 'burger', '5b.jpg', 3),
(6, 8, 'paparoni pizza', 3.5, 'a very delicious pizza with italian sauce', 'meal', '6a.jpg', 0),
(6, 9, 'pizzahut special', 1.9, 'small vegetable with italian sauce', 'meal', '6b.jpg', 0),
(7, 10, 'chicken steak', 2.8, 'a spicy spanish steak , for spicy peoples', 'meal', '7a.jpg', 0),
(7, 11, 'mix grills', 2.5, 'a mixture of well grilled grills', 'grills', '7b.jpg', 0),
(9, 12, 'cheese fingers', 1.2, 'an italian cheese sticks fingers', 'grills', '9a.jpg', 0),
(9, 13, 'chicken BBQ', 3.4, 'a chicken pizza with mushroom and BBQ ', 'grills', '9b.jpg', 0),
(10, 13, 'double king', 2.3, 'a double meat burger', 'grills', '10a.jpg', 0),
(10, 14, 'chicken zinger', 1.8, 'chicken zinger with spicy sauce', 'grills', '10b.jpg', 0),
(11, 14, 'dominos five', 4.5, 'pizza with vegetables and mushroom', 'grills', '11a.jpg', 0),
(11, 15, 'chicken pizza', 4, 'chicken pizza with mayonnaise ', 'grills', '11b.jpg', 0),
(12, 16, 'elevation tower', 6.8, '5-floor tower of sweet burgers ', 'grills', '12a.jpg', 0),
(12, 17, 'elevation brace', 5.2, 'double burger with nice sauce', 'grills', '12b.jpg', 0),
(13, 18, 'chicken zinger', 2.5, 'well cooked chicken zinger with potato', 'grills', '13a.jpg', 0),
(13, 19, 'big M', 3, 'huge meat burger with soft vegetables', 'grills', '13b.jpg', 0),
(14, 20, 'mighty chicken', 2.3, 'an up-size chicken with up-size potato', 'grills', '14a.jpg', 0),
(14, 21, 'hot dog', 1.8, 'hot dog with up-size pepsi', 'grills', '14b.jpg', 0),
(15, 22, 'family broasted', 8.5, '20 piece of fresh chicken broasted ', 'grills', '15a.jpg', 0),
(15, 23, 'chicken twister', 0.6, 'small piece of bread with fresh chicken', 'grills', '15b.jpg', 0),
(16, 24, 'arayes', 1.2, 'pieces of meat inside fresh tortilla', 'grills', '16a.jpg', 1),
(16, 25, 'chilo kabab', 2.4, '2 fingers of meat kabab with white rice', 'grills', '16b.jpg', 0),
(17, 26, 'chicken pasta', 3.2, 'red sauce and spicy pasta with chicken', 'grills', '17a.jpg', 0),
(17, 27, 'vegetable pasta', 3.5, 'white sauce pasta with vegetables', 'grills', '17b.jpg', 0),
(18, 28, 'chilo kabab', 3, 'meat kabab with white rice', 'grills', '18a.jpg', 0),
(18, 29, 'chicken masala', 2.6, 'indian masala with white rice', 'grills', '18b.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dishrating`
--

CREATE TABLE `dishrating` (
  `DID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dishrating`
--

INSERT INTO `dishrating` (`DID`, `CID`, `rating`) VALUES
(7, 49, 4),
(7, 46, 2),
(24, 46, 1),
(6, 46, 5);

-- --------------------------------------------------------

--
-- Table structure for table `dishreviews`
--

CREATE TABLE `dishreviews` (
  `commid` int(11) NOT NULL,
  `DID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dishreviews`
--

INSERT INTO `dishreviews` (`commid`, `DID`, `CID`, `comment`, `date`) VALUES
(1, 7, 49, 'best burger ever', '2018-05-11');

-- --------------------------------------------------------

--
-- Table structure for table `orderdishes`
--

CREATE TABLE `orderdishes` (
  `oid` int(11) NOT NULL,
  `did` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderdishes`
--

INSERT INTO `orderdishes` (`oid`, `did`, `qty`) VALUES
(33, 24, 1),
(33, 25, 1),
(34, 25, 1),
(35, 24, 1),
(36, 24, 1),
(37, 25, 1),
(38, 24, 1),
(39, 24, 1),
(40, 24, 1),
(40, 25, 4),
(41, 16, 1),
(42, 24, 1),
(43, 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderslist`
--

CREATE TABLE `orderslist` (
  `oid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `ostatus` varchar(50) NOT NULL,
  `ototalprice` float NOT NULL,
  `otime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderslist`
--

INSERT INTO `orderslist` (`oid`, `rid`, `cid`, `bid`, `ostatus`, `ototalprice`, `otime`) VALUES
(33, 16, 46, 1, 'Payment successful', 3.6, '2018-05-12 10:20:54'),
(34, 16, 46, 1, 'Payment successful', 2.4, '2018-05-12 10:22:46'),
(35, 16, 46, 1, 'Payment successful', 1.2, '2018-05-12 10:24:02'),
(36, 16, 46, 1, 'Payment successful', 1.2, '2018-05-12 10:27:39'),
(37, 16, 46, 1, 'Payment successful', 2.4, '2018-05-12 10:29:41'),
(38, 16, 46, 1, 'Payment successful', 1.2, '2018-05-12 13:00:12'),
(39, 16, 46, 1, 'Payment successful', 1.2, '2018-05-12 13:27:38'),
(40, 16, 46, 1, 'Payment successful', 10.8, '1970-01-01 04:00:00'),
(41, 12, 46, 1, 'Payment successful', 6.8, '2018-05-12 14:10:27'),
(42, 16, 46, 1, 'Payment successful', 1.2, '2018-05-12 15:16:01'),
(43, 16, 46, 1, 'Payment successful', 1.7, '2018-05-12 15:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `restaurantrating`
--

CREATE TABLE `restaurantrating` (
  `rid` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurantrating`
--

INSERT INTO `restaurantrating` (`rid`, `CID`, `rating`) VALUES
(7, 49, 0),
(7, 50, 5),
(8, 49, 3),
(8, 50, 4);

-- --------------------------------------------------------

--
-- Table structure for table `restaurantreviews`
--

CREATE TABLE `restaurantreviews` (
  `commid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `rid` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `restaurantname` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `ownerName` varchar(50) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(35) NOT NULL,
  `rating` float DEFAULT NULL,
  `accepted` varchar(1) NOT NULL,
  `registerDate` date NOT NULL,
  `profileImage` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`rid`, `username`, `restaurantname`, `password`, `ownerName`, `phone`, `email`, `rating`, `accepted`, `registerDate`, `profileImage`) VALUES
(5, '3lines', '3 lines', 'b91b88868f7341126b5308ac3baff033', 'Ali', '17035888', '3lines@me.com', 0, 'y', '2018-04-24', '3lines.jpg'),
(6, 'pizzahut', 'pizza hut', 'b91b88868f7341126b5308ac3baff033', 'Jasim', '17444545', 'pizhut@food.com', 0, 'y', '2018-04-25', 'Pizzahut.jpg'),
(7, 'alabraj', 'alabraj', 'b91b88868f7341126b5308ac3baff033', 'ahmed', '17506070', 'abraj@abraj.com', 3, 'y', '2018-04-26', 'alabraj.jpg'),
(9, 'papajohns', 'papa johns', 'b91b88868f7341126b5308ac3baff033', 'jawad', '17507090', 'papa@jawad.com', 0, 'y', '2018-04-27', 'papajohns.jpg'),
(10, 'burgerking', 'burger king', 'b91b88868f7341126b5308ac3baff033', 'salah', '17785855', 'burking@gmail.com', 0, 'y', '2018-05-07', 'burgerking.jpg'),
(11, 'dominos', 'dominos pizza', 'b91b88868f7341126b5308ac3baff033', 'Ali', '17669852', 'dominos@pizza.org', 0, 'y', '2018-05-07', 'dominos.jpg'),
(12, 'elevation', 'elevation burger', 'b91b88868f7341126b5308ac3baff033', 'jaffar', '17455222', 'elev@food.com', 0, 'y', '2018-05-07', 'elevation.jpg'),
(13, 'hardeez', 'hardeez', 'b91b88868f7341126b5308ac3baff033', 'kadhim', '17889552', 'hardeez@food.com', 0, 'y', '2018-04-04', 'hardeez.jpg'),
(14, 'jasmis', 'jasmis', 'b91b88868f7341126b5308ac3baff033', 'sultan', '17896321', 'jasmis@food.org', 0, 'y', '2018-05-05', 'jasmis.jpg'),
(15, 'kfc', 'kfc', 'b91b88868f7341126b5308ac3baff033', 'abdulsamad', '17555552', 'kfc.del@gmail.com', 0, 'y', '2018-04-27', 'kfc.jpg'),
(16, 'marmariz', 'marmariz', 'b91b88868f7341126b5308ac3baff033', 'ahmed', '17456258', 'marmariz@food.org', 0, 'y', '2018-05-09', 'marmariz.jpg'),
(17, 'yumyumtree', 'yum yum tree', 'a59f54575e8633b48cd37db17ffa829b', 'faisal', '17447669', 'yumyum@food.org', 0, 'y', '2018-05-04', 'YumYumTree.jpg'),
(18, 'zyara', 'zyara', 'b91b88868f7341126b5308ac3baff033', 'tariq', '17777555', 'zyara@food.com', 0, 'y', '2018-05-07', 'zyara.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `restaurantstypes`
--

CREATE TABLE `restaurantstypes` (
  `tid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurantstypes`
--

INSERT INTO `restaurantstypes` (`tid`, `rid`, `type`) VALUES
(1, 7, 'grills'),
(2, 10, 'burger'),
(3, 11, 'pizza'),
(4, 11, 'american'),
(5, 12, 'burger'),
(6, 13, 'fast food'),
(7, 14, 'fast food'),
(8, 15, 'fast food'),
(9, 16, 'grills'),
(10, 16, 'french'),
(11, 9, 'pizza'),
(12, 9, 'italian'),
(13, 5, 'italian'),
(14, 5, 'pasta'),
(15, 5, 'burger'),
(16, 6, 'italian'),
(17, 6, 'pizza'),
(18, 17, 'pasta'),
(19, 18, 'bahrini'),
(20, 18, 'grills');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`rid`,`bid`),
  ADD KEY `bid` (`bid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customeraddress`
--
ALTER TABLE `customeraddress`
  ADD PRIMARY KEY (`AID`),
  ADD UNIQUE KEY `CID` (`CID`,`City`,`Building`,`Block`,`Road`);

--
-- Indexes for table `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`DID`,`rid`),
  ADD KEY `rid` (`rid`);

--
-- Indexes for table `dishreviews`
--
ALTER TABLE `dishreviews`
  ADD PRIMARY KEY (`commid`);

--
-- Indexes for table `orderdishes`
--
ALTER TABLE `orderdishes`
  ADD PRIMARY KEY (`oid`,`did`),
  ADD KEY `did` (`did`);

--
-- Indexes for table `orderslist`
--
ALTER TABLE `orderslist`
  ADD PRIMARY KEY (`oid`,`rid`,`cid`,`bid`),
  ADD KEY `rid` (`rid`),
  ADD KEY `cid` (`cid`),
  ADD KEY `bid` (`bid`);

--
-- Indexes for table `restaurantreviews`
--
ALTER TABLE `restaurantreviews`
  ADD PRIMARY KEY (`commid`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`rid`),
  ADD UNIQUE KEY `restaurantname` (`restaurantname`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `restaurantstypes`
--
ALTER TABLE `restaurantstypes`
  ADD PRIMARY KEY (`rid`,`type`),
  ADD UNIQUE KEY `tid` (`tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `customeraddress`
--
ALTER TABLE `customeraddress`
  MODIFY `AID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `dish`
--
ALTER TABLE `dish`
  MODIFY `DID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `dishreviews`
--
ALTER TABLE `dishreviews`
  MODIFY `commid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orderslist`
--
ALTER TABLE `orderslist`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `restaurantreviews`
--
ALTER TABLE `restaurantreviews`
  MODIFY `commid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `restaurantstypes`
--
ALTER TABLE `restaurantstypes`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `branch_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `restaurants` (`rid`);

--
-- Constraints for table `customeraddress`
--
ALTER TABLE `customeraddress`
  ADD CONSTRAINT `customeraddress_ibfk_1` FOREIGN KEY (`CID`) REFERENCES `customer` (`CID`);

--
-- Constraints for table `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `dish_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `restaurants` (`rid`);

--
-- Constraints for table `orderdishes`
--
ALTER TABLE `orderdishes`
  ADD CONSTRAINT `orderdishes_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orderslist` (`oid`),
  ADD CONSTRAINT `orderdishes_ibfk_2` FOREIGN KEY (`did`) REFERENCES `dish` (`DID`);

--
-- Constraints for table `orderslist`
--
ALTER TABLE `orderslist`
  ADD CONSTRAINT `orderslist_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `restaurants` (`rid`),
  ADD CONSTRAINT `orderslist_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `customer` (`CID`),
  ADD CONSTRAINT `orderslist_ibfk_3` FOREIGN KEY (`bid`) REFERENCES `branch` (`bid`);

--
-- Constraints for table `restaurantstypes`
--
ALTER TABLE `restaurantstypes`
  ADD CONSTRAINT `restaurantstypes_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `restaurants` (`rid`),
  ADD CONSTRAINT `restaurantstypes_ibfk_2` FOREIGN KEY (`rid`) REFERENCES `restaurants` (`rid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
