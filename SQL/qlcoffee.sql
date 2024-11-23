-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 01:35 PM
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
('HD2411232ed34', 4, 1, 0.00),
('HD241123517b8', 1, 1, 0.00),
('HD241123517b8', 3, 1, 0.00),
('HD241123517b8', 4, 1, 0.00),
('HD24112369144', 1, 1, 0.00),
('HD24112369144', 2, 1, 0.00),
('HD24112369144', 3, 1, 0.00),
('HD2411239d692', 3, 1, 0.00),
('HD2411239d692', 4, 1, 0.00),
('HD241123a8829', 1, 2, 0.00),
('HD241123a8829', 3, 11, 0.00),
('HD241123f386e', 4, 1, 0.00);

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
('HD1108408e2a42', '2024-11-08 19:28:56', 90000.00, 52, 1, 2, 1),
('HD1109eb42f84b', '2024-11-09 07:37:08', 208000.00, 45, 6, 2, 0),
('HD24112302d25', '2024-11-23 18:49:20', 85000.00, 45, 1, 0, 0),
('HD241123049c9', '2024-11-23 18:01:20', 358000.00, 46, 1, 0, 0),
('HD24112304f0e', '2024-11-23 19:07:54', 100000.00, 50, 1, 3, 1),
('HD2411232ed34', '2024-11-23 18:40:04', 85000.00, 45, 1, 0, 0),
('HD241123517b8', '2024-11-23 19:14:38', 109000.00, 47, 1, 2, 1),
('HD24112369144', '2024-11-23 17:09:34', 124000.00, 49, 1, 0, 0),
('HD2411239d692', '2024-11-23 19:12:59', 85000.00, 51, 1, 3, 0),
('HD241123a8829', '2024-11-23 18:01:57', 598000.00, 51, 1, 0, 0),
('HD241123f386e', '2024-11-23 19:05:30', 35000.00, 46, 1, 0, 0);

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
  `offer` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `CustomerName`, `Gender`, `Address`, `PhoneNumber`, `Email`, `BillID`, `offer`) VALUES
(2, 'Nguyễn Văn Anh', 'Nam', '123 Đường Hai Bà Trưng, Nha Trang', '0816774376', 'nguyenvana@gmail.com', 'HD1108408e2a42', 'Thành viên VIP'),
(3, 'Nguyễn Thị Quỳnh Như', 'Nữ', 'Chung cư Napoleon, Nha Trang', '0998323142', 'quoclam010305@gmail.com', NULL, 'Thành viên VIP');

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
(18, 'Nước ngọt', 'Nước ngọt các loại'),
(19, 'Nước giải khát', 'hihi');

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
(1, 7, 'Cà phê đen', 'Cà phê đen.png', 24000.00),
(2, 7, 'Capuchino', 'Capuchino.png', 50000.00),
(3, 7, 'Americano', 'Americano.png', 50000.00),
(4, 7, 'Cà phê sữa dừa', 'Cà Phê Sữa Dừa.jpg', 35000.00),
(6, 8, 'Ép Thơm', 'Ép Thơm.png', 25000.00),
(10, 8, 'Ép Cà Chua', 'Ép Cà Chua.jpg', 29000.00),
(11, 9, 'Sinh tố dâu', 'Ép Dâu.jpg', 30000.00),
(12, 8, 'Ép Kiwi', 'Ép Kiwi.jpeg', 55000.00),
(13, 8, 'Ép Lựu', 'Ép lựu.jpg', 35000.00),
(14, 18, 'String Dâu', 'Sting.jpg', 27000.00);

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
('1', 200.00, 500.00, 700.00, 1, 0.00, '2024-11-08 06:23:36', '2024-11-08 06:23:36', 2);

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
(1, 'Quản Lý', 'Quản lý cửa hàng'),
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
(2, 'Nguyễn Ngọc Quỳnh Như', '092932832', 'quynhnhu.jpg', 'Nữ', 'quynhnhu', 'quynhnhu', 'Quynhnhu@1607', 3, '', ''),
(6, 'Lê Minh Thành', '0788432643', 'lam.png', 'Nam', 'minhthanh', 'minhthanh', '1234', 1, '', ''),
(7, 'Lữ Vũ Phúc', '0788432643', '2.jpg', 'Male', 'luvuphuc', 'vuphuc', 'Luvuphuc@1234', 1, '', '');

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
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drinkcategories`
--
ALTER TABLE `drinkcategories`
  MODIFY `DrinkCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `drinks`
--
ALTER TABLE `drinks`
  MODIFY `DrinkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `TableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `usercategories`
--
ALTER TABLE `usercategories`
  MODIFY `UserCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
