-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 07:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlcoffee`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorizations`
--

CREATE TABLE `authorizations` (
  `UserCategoryID` int(50) NOT NULL,
  `FunctionID` varchar(255) NOT NULL,
  `AuthorizationDescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authorizations`
--

INSERT INTO `authorizations` (`UserCategoryID`, `FunctionID`, `AuthorizationDescription`) VALUES
(1, 'BCKC_Xem', NULL),
(1, 'BCKC_XemCT', NULL),
(1, 'BCKC_Xoa', NULL),
(1, 'BH_QuanLy', NULL),
(1, 'B_ThayDoi', NULL),
(1, 'DU_Sua', NULL),
(1, 'DU_Them', NULL),
(1, 'DU_Xem', NULL),
(1, 'DU_Xoa', NULL),
(1, 'HD_Xem', NULL),
(1, 'HD_XemCT', NULL),
(1, 'HD_Xoa', NULL),
(1, 'LDU_QuanLy', NULL),
(1, 'LMH_QuanLy', NULL),
(1, 'LND_QuanLy', NULL),
(1, 'MH_Sua', NULL),
(1, 'MH_Them', NULL),
(1, 'MH_Xem', NULL),
(1, 'MH_Xoa', NULL),
(1, 'ND_Sua', NULL),
(1, 'ND_Them', NULL),
(1, 'ND_Xem', NULL),
(1, 'ND_Xoa', NULL),
(1, 'PQ', NULL),
(3, 'BH_QuanLy', NULL),
(3, 'HD_Xem', NULL),
(3, 'HD_XemCT', NULL),
(3, 'KC', NULL),
(3, 'LDU_QuanLy', NULL),
(4, 'LMH_QuanLy', NULL),
(4, 'MH_Sua', NULL),
(4, 'MH_Them', NULL),
(4, 'MH_Xem', NULL),
(4, 'MH_Xoa', NULL),
(4, 'ThongKe', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `billinfos`
--

CREATE TABLE `billinfos` (
  `BillID` int(11) NOT NULL,
  `DrinkID` int(11) NOT NULL,
  `DrinkCount` int(11) NOT NULL,
  `DrinkPrice` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `BillID` int(11) NOT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `TotalAmount` decimal(18,2) DEFAULT NULL,
  `TableID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drinkcategories`
--

CREATE TABLE `drinkcategories` (
  `DrinkCategoryID` int(11) NOT NULL,
  `DrinkCategoryName` varchar(255) DEFAULT NULL,
  `DrinkCategoryDescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drinkcategories`
--

INSERT INTO `drinkcategories` (`DrinkCategoryID`, `DrinkCategoryName`, `DrinkCategoryDescription`) VALUES
(7, 'Cà phê', 'Cà phê các loại'),
(8, 'Nước ép', 'Nước ép các loại'),
(9, 'Sinh Tố', 'Sinh tố các loại');

-- --------------------------------------------------------

--
-- Table structure for table `drinks`
--

CREATE TABLE `drinks` (
  `DrinkID` int(11) NOT NULL,
  `DrinkCategoryID` int(11) DEFAULT NULL,
  `DrinkName` varchar(255) DEFAULT NULL,
  `DrinkImage` varchar(255) DEFAULT NULL,
  `DrinkPrice` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drinks`
--

INSERT INTO `drinks` (`DrinkID`, `DrinkCategoryID`, `DrinkName`, `DrinkImage`, `DrinkPrice`) VALUES
(1, 7, 'Cà phê đen', 'Cà phê đen.png', 22000.00),
(2, 7, 'Capuchino', 'Capuchino.png', 47000.00),
(3, 7, 'Americano', 'Americano.png', 74000.00),
(4, 7, 'Cà phê sữa dừa', 'Cà Phê Sữa Dừa.jpg', 35000.00),
(6, 8, 'Ép Thơm', 'Ép Thơm.png', 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `funcitions`
--

CREATE TABLE `funcitions` (
  `FunctionID` varchar(255) NOT NULL,
  `FunctionName` varchar(255) NOT NULL,
  `FunctionGroup` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `funcitions`
--

INSERT INTO `funcitions` (`FunctionID`, `FunctionName`, `FunctionGroup`) VALUES
('BCKC_Xem', 'Xem danh sách báo cáo kết ca', 'Quản lý báo cáo kết ca'),
('BCKC_XemCT', 'Xem chi tiết báo cáo kết ca', 'Quản lý báo cáo kết ca'),
('BCKC_Xoa', 'Xóa báo cáo kết ca', 'Quản lý báo cáo kết ca'),
('BH_QuanLy', 'Quản lý bán hàng', 'Quản lý bán hàng'),
('B_ThayDoi', 'Thay đổi số bàn', 'Quản lý bàn'),
('DU_Sua', 'Chỉnh sửa đồ uống', 'Quản lý đồ uống'),
('DU_Them', 'Thêm đồ uống', 'Quản lý đồ uống'),
('DU_Xem', 'Xem danh sách đồ uống', 'Quản lý đồ uống'),
('DU_Xoa', 'Xóa đồ uống', 'Quản lý đồ uống'),
('HD_Xem', 'Xem danh sách hóa đơn', 'Quản lý hóa đơn'),
('HD_XemCT', 'Xem chi tiết hóa đơn', 'Quản lý hóa đơn'),
('HD_Xoa', 'Xóa hóa đơn', 'Quản lý hóa đơn'),
('KC', 'Kết ca', 'Kết ca'),
('LDU_QuanLy', 'Quản lý loại đồ uống', 'Quản lý loại đồ uống'),
('LMH_QuanLy', 'Quản lý loại mặt hàng', 'Quản lý loại mặt hàng'),
('LND_QuanLy', 'Quản lý loại người dùng', 'Quản lý loại người dùng'),
('MH_Sua', 'Chỉnh sửa mặt hàng', 'Quản lý mặt hàng'),
('MH_Them', 'Thêm mặt hàng', 'Quản lý mặt hàng'),
('MH_Xem', 'Xem danh sách mặt hàng', 'Quản lý mặt hàng'),
('MH_Xoa', 'Xóa mặt hàng', 'Quản lý mặt hàng'),
('ND_Sua', 'Chỉnh sửa người dùng', 'Quản lý người dùng'),
('ND_Them', 'Thêm người dùng', 'Quản lý người dùng'),
('ND_Xem', 'Xem danh sách người dùng', 'Quản lý người dùng'),
('ND_Xoa', 'Xóa người dùng', 'Quản lý người dùng'),
('PQ', 'Phân quyền', 'Phân quyền'),
('ThongKe', 'Thống kê các mặt hàng sắp hết', 'Thống kê');

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `GoodsID` int(11) NOT NULL,
  `GoodsCategoryID` int(11) NOT NULL,
  `GoodsName` varchar(255) NOT NULL,
  `GoodsCount` int(11) NOT NULL,
  `GoodsUnit` varchar(255) NOT NULL,
  `GoodsPrice` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goodscategories`
--

CREATE TABLE `goodscategories` (
  `GoodsCategoryID` int(11) NOT NULL,
  `GoodsCategoryName` varchar(255) NOT NULL,
  `GoodsCategoryDescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shiftreports`
--

CREATE TABLE `shiftreports` (
  `ShiftReportID` varchar(128) NOT NULL,
  `Revenue` decimal(18,2) NOT NULL,
  `FirstAmount` decimal(18,2) NOT NULL,
  `LastAmount` decimal(18,2) NOT NULL,
  `BillCount` int(11) NOT NULL,
  `UncollectedAmount` decimal(18,2) NOT NULL,
  `FirstTime` datetime NOT NULL,
  `LastTime` datetime NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `TableID` int(11) NOT NULL,
  `TableName` varchar(255) NOT NULL,
  `Status` bit(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`TableID`, `TableName`, `Status`) VALUES
(21, 'Bàn 1', b'0000000000'),
(22, 'Bàn 2', b'0000000000'),
(23, 'Bàn 3', b'0000000000'),
(24, 'Bàn 4', b'0000000000'),
(25, 'Bàn 5', b'0000000000'),
(26, 'Bàn 6', b'0000000000'),
(27, 'Bàn 7', b'0000000000'),
(28, 'Bàn 8', b'0000000000'),
(29, 'Bàn 9', b'0000000000'),
(30, 'Bàn 10', b'0000000000');

-- --------------------------------------------------------

--
-- Table structure for table `usercategories`
--

CREATE TABLE `usercategories` (
  `UserCategoryID` int(11) NOT NULL,
  `UserCategoryName` varchar(255) NOT NULL,
  `UserCategoryDescription` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usercategories`
--

INSERT INTO `usercategories` (`UserCategoryID`, `UserCategoryName`, `UserCategoryDescription`) VALUES
(1, 'Quản Lý', 'Được phép truy cập'),
(3, 'Thu ngân', 'Người chịu trách nhiệm thu tiền và giao dịch'),
(4, 'Khách hàng', 'Khách hàng sử dụng dịch vụ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `PhoneNumber` varchar(255) DEFAULT NULL,
  `UserImage` varchar(255) DEFAULT NULL,
  `Gender` varchar(255) DEFAULT NULL,
  `Username` varchar(255) DEFAULT NULL,
  `AccountName` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `UserCategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `PhoneNumber`, `UserImage`, `Gender`, `Username`, `AccountName`, `Password`, `UserCategoryID`) VALUES
(1, 'Quốc Lâm', '815597300', '1.jpg', 'Nam', 'quoclam', 'quoclam', '1234', 1),
(2, 'Nguyễn Ngọc Quỳnh Như', '092932832', 'nhu.jpg', 'Nữ', 'quynhnhu', 'quynhnhu', '1234', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorizations`
--
ALTER TABLE `authorizations`
  ADD PRIMARY KEY (`UserCategoryID`,`FunctionID`),
  ADD KEY `FunctionID` (`FunctionID`);

--
-- Indexes for table `billinfos`
--
ALTER TABLE `billinfos`
  ADD PRIMARY KEY (`BillID`,`DrinkID`),
  ADD KEY `DrinkID` (`DrinkID`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`BillID`),
  ADD KEY `TableID` (`TableID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `drinkcategories`
--
ALTER TABLE `drinkcategories`
  ADD PRIMARY KEY (`DrinkCategoryID`);

--
-- Indexes for table `drinks`
--
ALTER TABLE `drinks`
  ADD PRIMARY KEY (`DrinkID`),
  ADD KEY `DrinkCategoryID` (`DrinkCategoryID`);

--
-- Indexes for table `funcitions`
--
ALTER TABLE `funcitions`
  ADD PRIMARY KEY (`FunctionID`);

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`GoodsID`),
  ADD KEY `GoodsCategoryID` (`GoodsCategoryID`);

--
-- Indexes for table `goodscategories`
--
ALTER TABLE `goodscategories`
  ADD PRIMARY KEY (`GoodsCategoryID`);

--
-- Indexes for table `shiftreports`
--
ALTER TABLE `shiftreports`
  ADD PRIMARY KEY (`ShiftReportID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`TableID`);

--
-- Indexes for table `usercategories`
--
ALTER TABLE `usercategories`
  ADD PRIMARY KEY (`UserCategoryID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `UserCategoryID` (`UserCategoryID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billinfos`
--
ALTER TABLE `billinfos`
  MODIFY `BillID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `BillID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drinkcategories`
--
ALTER TABLE `drinkcategories`
  MODIFY `DrinkCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `drinks`
--
ALTER TABLE `drinks`
  MODIFY `DrinkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `TableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `usercategories`
--
ALTER TABLE `usercategories`
  MODIFY `UserCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authorizations`
--
ALTER TABLE `authorizations`
  ADD CONSTRAINT `authorizations_ibfk_1` FOREIGN KEY (`UserCategoryID`) REFERENCES `usercategories` (`UserCategoryID`),
  ADD CONSTRAINT `authorizations_ibfk_2` FOREIGN KEY (`FunctionID`) REFERENCES `funcitions` (`FunctionID`);

--
-- Constraints for table `billinfos`
--
ALTER TABLE `billinfos`
  ADD CONSTRAINT `billinfos_ibfk_1` FOREIGN KEY (`BillID`) REFERENCES `bills` (`BillID`),
  ADD CONSTRAINT `billinfos_ibfk_2` FOREIGN KEY (`DrinkID`) REFERENCES `drinks` (`DrinkID`);

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`TableID`) REFERENCES `tables` (`TableID`),
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `drinks`
--
ALTER TABLE `drinks`
  ADD CONSTRAINT `drinks_ibfk_1` FOREIGN KEY (`DrinkCategoryID`) REFERENCES `drinkcategories` (`DrinkCategoryID`);

--
-- Constraints for table `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`GoodsCategoryID`) REFERENCES `goodscategories` (`GoodsCategoryID`);

--
-- Constraints for table `shiftreports`
--
ALTER TABLE `shiftreports`
  ADD CONSTRAINT `shiftreports_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`UserCategoryID`) REFERENCES `usercategories` (`UserCategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
