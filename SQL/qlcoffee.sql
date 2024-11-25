-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 07:54 AM
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
(3, 'BH_QuanLy', NULL),
(3, 'HD_Xem', NULL),
(3, 'HD_XemCT', NULL),
(3, 'KC', NULL),
(3, 'LDU_QuanLy', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `billinfos`
--

CREATE TABLE `billinfos` (
  `BillID` varchar(50) NOT NULL,
  `DrinkID` int(11) NOT NULL,
  `DrinkCount` int(11) NOT NULL,
  `DrinkPrice` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billinfos`
--

INSERT INTO `billinfos` (`BillID`, `DrinkID`, `DrinkCount`, `DrinkPrice`) VALUES
('HD2411230d43e', 1, 1, 24000.00),
('HD2411230d43e', 2, 1, 50000.00),
('HD2411230d43e', 3, 1, 50000.00),
('HD2411230d43e', 6, 1, 25000.00),
('HD2411230d43e', 10, 1, 29000.00),
('HD2411230d43e', 11, 1, 30000.00),
('HD241123156f7', 1, 1, 24000.00),
('HD241123156f7', 2, 1, 50000.00),
('HD241123156f7', 3, 1, 50000.00),
('HD241123156f7', 4, 1, 35000.00),
('HD2411233b8f1', 1, 1, 24000.00),
('HD2411233b8f1', 2, 1, 50000.00),
('HD2411233b8f1', 3, 1, 50000.00),
('HD241123e215f', 1, 1, 24000.00),
('HD241123e215f', 2, 1, 50000.00),
('HD241123e215f', 3, 1, 50000.00),
('HD2411240554a', 3, 1, 50000.00),
('HD2411240554a', 4, 1, 35000.00),
('HD241124202df', 6, 1, 25000.00),
('HD241124202df', 10, 1, 29000.00),
('HD241124968c5', 1, 1, 24000.00),
('HD241124aed33', 1, 2, 24000.00),
('HD241124aed33', 2, 3, 50000.00),
('HD241124aed33', 3, 3, 50000.00),
('HD241124aed33', 4, 1, 35000.00),
('HD241124aed33', 6, 1, 25000.00),
('HD241124aed33', 10, 1, 29000.00),
('HD241124aed33', 11, 1, 30000.00),
('HD241124b4003', 2, 1, 50000.00),
('HD2411250e3f5', 2, 1, 50000.00),
('HD2411250e3f5', 3, 1, 50000.00),
('HD24112543686', 2, 1, 50000.00),
('HD24112544e82', 2, 1, 50000.00),
('HD2411255689d', 1, 1, 24000.00),
('HD24112556dd0', 2, 1, 50000.00),
('HD24112556dd0', 3, 1, 50000.00),
('HD24112574a5d', 3, 1, 50000.00),
('HD24112574a5d', 30, 1, 40000.00),
('HD241125886d6', 2, 1, 50000.00),
('HD241125c09ff', 3, 1, 50000.00),
('HD241125c09ff', 10, 1, 29000.00);

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `BillID` varchar(50) NOT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `TotalAmount` decimal(18,2) DEFAULT NULL,
  `TableID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CustomerID` int(11) NOT NULL,
  `Status` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`BillID`, `CreateDate`, `TotalAmount`, `TableID`, `UserID`, `CustomerID`, `Status`) VALUES
('HD2411230d43e', '2024-11-24 01:49:48', 208000.00, 46, 1, 3, 0),
('HD241123156f7', '2024-11-24 01:43:31', 159000.00, 54, 1, 2, 1),
('HD2411233b8f1', '2024-11-24 01:43:24', 124000.00, 51, 1, 3, 1),
('HD241123e215f', '2024-11-24 00:49:54', 124000.00, 48, 1, 3, 1),
('HD2411240554a', '2024-11-24 11:23:53', 85000.00, NULL, 1, 2, 1),
('HD241124202df', '2024-11-24 12:32:41', 54000.00, 47, 1, 9, 1),
('HD241124968c5', '2024-11-24 11:31:32', 24000.00, 46, 1, 2, 1),
('HD241124aed33', '2024-11-24 11:47:52', 467000.00, 50, 1, 8, 1),
('HD241124b4003', '2024-11-24 12:45:02', 50000.00, 46, 1, 3, 1),
('HD2411250e3f5', '2024-11-25 11:18:45', 100000.00, 45, 1, 9, 1),
('HD24112543686', '2024-11-25 11:16:14', 50000.00, 50, 1, 3, 1),
('HD24112544e82', '2024-11-25 11:19:05', 50000.00, 46, 1, 2, 1),
('HD2411255689d', '2024-11-25 11:12:31', 24000.00, 45, 1, 7, 1),
('HD24112556dd0', '2024-11-25 11:13:42', 100000.00, 49, 1, 2, 1),
('HD24112574a5d', '2024-11-25 11:02:37', 90000.00, 49, 1, 7, 1),
('HD241125886d6', '2024-11-25 11:16:27', 50000.00, 50, 1, 2, 1),
('HD241125c09ff', '2024-11-25 11:12:52', 79000.00, 45, 1, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerName` varchar(255) NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `BillID` varchar(50) DEFAULT NULL,
  `Offer` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `CustomerName`, `Gender`, `Address`, `PhoneNumber`, `Email`, `BillID`, `Offer`) VALUES
(2, 'Nguyễn Văn Anh', 'Nam', '123 Đường Hai Bà Trưng, Nha Trang', '0816774376', 'nguyenvana@gmail.com', NULL, 'Thành viên VIP'),
(3, 'Nguyễn Thị Quỳnh Như', 'Nữ', 'Chung cư Napoleon, Nha Trang', '0998323142', 'quoclam010305@gmail.com', NULL, 'Thành viên VIP'),
(7, 'Lê Thanh Trúc', 'Nam', '24 Củ Chi, Nha Trang, Khánh Hòa', '0887632123', 'thanhtruc@gmail.com', NULL, 'Thành viên thường'),
(8, 'Cao Tuấn', 'Nam', 'Chung cư Napoleon, Nha Trang', '09983213312', 'caotuan@gmail.com', NULL, 'Thành viên thường'),
(9, 'Lê Phương', 'Nữ', 'Chung cư Napoleon, Nha Trang', '0988672321', 'lephuong@gmail.com', NULL, 'Thành viên VIP');

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
(7, 'Cà phê', 'Cà phê các loại 1'),
(8, 'Nước ép', 'Nước ép các loại'),
(9, 'Sinh Tố', 'Sinh tố các loại'),
(40, 'Trà giải nhiệt', 'Trà mát mẻ mùa hè'),
(41, 'Bánh ngọt', 'Bánh ngọt các loại thưởng thức trà chiều');

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
(1, 7, 'Cà phê đen', 'Cf_den.png', 24000.00),
(2, 7, 'Capuchino', 'Capuchino.png', 50000.00),
(3, 7, 'Americano', 'Americano.png', 50000.00),
(4, 7, 'Cà phê sữa dừa', 'Cf_milk_coconut.jpg', 35000.00),
(6, 8, 'Ép Thơm', 'Ep_thom.png', 25000.00),
(10, 8, 'Ép Cà Chua', 'Ep_cachua.jpg', 29000.00),
(11, 9, 'Sinh tố dâu', 'st_dau.jpg', 30000.00),
(12, 8, 'Ép Kiwi', 'Ep_Kiwi.jpeg', 55000.00),
(13, 8, 'Ép Lựu', 'Ep_luu.jpg', 35000.00),
(23, 9, 'Sinh tố xoài', 'Yogurt_mango.jpg', 37000.00),
(24, 40, 'Trà đào', 'tra_dao.jpeg', 35000.00),
(25, 40, 'Trà dâu', 'st_dau.jpg', 38000.00),
(26, 9, 'Sinh tố bơ', 'st_bo.jpg', 27000.00),
(27, 9, 'Sinh tố việt quất', 'vietquat.jpg', 40000.00),
(28, 8, 'Ép ổi', 'Ep_oi.jpg', 29000.00),
(29, 41, 'Bánh sầu riêng', 'banh_sau_rieng.png', 30000.00),
(30, 41, 'Cupcake', 'CupCake.png', 40000.00);

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

--
-- Dumping data for table `shiftreports`
--

INSERT INTO `shiftreports` (`ShiftReportID`, `Revenue`, `FirstAmount`, `LastAmount`, `BillCount`, `UncollectedAmount`, `FirstTime`, `LastTime`, `UserID`) VALUES
('KC24002', 2000000.00, 700000.00, 1300000.00, 30, 70000.00, '2024-11-26 08:00:00', '2024-11-26 16:00:00', 2),
('KC24003', 1800000.00, 600000.00, 1200000.00, 25, 60000.00, '2024-11-27 08:00:00', '2024-11-27 16:00:00', 1),
('KC24004', 2200000.00, 750000.00, 1450000.00, 35, 75000.00, '2024-11-28 08:00:00', '2024-11-28 16:00:00', 1),
('KC24005', 2500000.00, 800000.00, 1700000.00, 40, 80000.00, '2024-11-29 08:00:00', '2024-11-29 16:00:00', 1),
('KC24006', 2100000.00, 720000.00, 1500000.00, 32, 72000.00, '2024-11-30 08:00:00', '2024-11-30 16:00:00', 2),
('KC24008', 2300000.00, 770000.00, 1550000.00, 36, 77000.00, '2024-12-02 08:00:00', '2024-12-02 16:00:00', 2),
('KC24009', 2600000.00, 820000.00, 1800000.00, 42, 82000.00, '2024-12-03 08:00:00', '2024-12-03 16:00:00', 1),
('KC24011', 2800000.00, 880000.00, 2000000.00, 50, 88000.00, '2024-12-05 08:00:00', '2024-12-05 16:00:00', 1);

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
(45, 'Bàn 1', b'0000000000'),
(46, 'Bàn 2', b'0000000000'),
(47, 'Bàn 3', b'0000000000'),
(48, 'Bàn 4', b'0000000000'),
(49, 'Bàn 5', b'0000000000'),
(50, 'Bàn 6', b'0000000000'),
(51, 'Bàn 7', b'0000000000'),
(52, 'Bàn 8', b'0000000000'),
(53, 'Bàn 9', b'0000000000'),
(54, 'Bàn 10', b'0000000000'),
(55, 'Bàn 11', b'0000000000'),
(56, 'Bàn 12', b'0000000000');

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
(1, 'Quản Lý', 'Quản lý cửa hàng Nha Trang'),
(3, 'Thu ngân', 'Người chịu trách nhiệm thu tiền và giao dịch');

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
  `UserCategoryID` int(11) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `PhoneNumber`, `UserImage`, `Gender`, `Username`, `AccountName`, `Password`, `UserCategoryID`, `Email`, `Address`) VALUES
(1, 'Cao Nguyễn Quốc Lâm', '815597300', '1.jpg', 'Nam', 'quoclam', 'quoclam', 'Quoclam@1607', 1, 'quoclam010305@gmail.com', 'Chung cư Bình Phú II - Vĩnh Hòa - TP. Nha Trang - Khánh Hòa'),
(2, 'Nguyễn Ngọc Quỳnh Như', '092932832', 'quynhnhu.jpg', 'Nữ', 'quynhnhu', 'quynhnhu', 'Quynhnhu@1607', 3, '', '');

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
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `BillID` (`BillID`);

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
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `drinkcategories`
--
ALTER TABLE `drinkcategories`
  MODIFY `DrinkCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `drinks`
--
ALTER TABLE `drinks`
  MODIFY `DrinkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `TableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `usercategories`
--
ALTER TABLE `usercategories`
  MODIFY `UserCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`BillID`) REFERENCES `bills` (`BillID`);

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
