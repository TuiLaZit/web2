-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 06:01 PM
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
-- Database: `web_kpop`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitiethoadon`
--

CREATE TABLE `chitiethoadon` (
  `IdCTHD` int(11) NOT NULL,
  `IdHD` int(11) NOT NULL,
  `IdSP` int(11) NOT NULL,
  `Quantity` smallint(6) NOT NULL,
  `Price` int(11) NOT NULL,
  `SumPrice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiethoadon`
--

INSERT INTO `chitiethoadon` (`IdCTHD`, `IdHD`, `IdSP`, `Quantity`, `Price`, `SumPrice`) VALUES
(1, 1, 13, 1, 1500000, 1500000),
(2, 1, 17, 2, 120000, 240000),
(3, 2, 40, 1, 1500000, 1500000),
(4, 2, 48, 1, 450000, 450000),
(5, 2, 69, 1, 600000, 600000),
(6, 3, 18, 1, 225000, 225000),
(7, 3, 17, 3, 120000, 360000),
(8, 4, 7, 1, 1260000, 1260000),
(9, 5, 3, 5, 450000, 2250000),
(10, 5, 6, 10, 345000, 3450000),
(11, 6, 13, 1, 1500000, 1500000),
(12, 7, 26, 6, 450000, 2700000),
(13, 8, 34, 1, 450000, 450000),
(14, 9, 42, 1, 225000, 225000),
(15, 10, 61, 1, 525000, 525000),
(16, 11, 50, 1, 1800000, 1800000),
(17, 12, 48, 1, 450000, 450000),
(18, 13, 54, 1, 450000, 450000),
(19, 14, 59, 1, 900000, 900000),
(20, 15, 64, 6, 450000, 2700000),
(21, 16, 62, 2, 600000, 1200000),
(22, 17, 14, 4, 240000, 960000),
(23, 18, 40, 1, 1500000, 1500000),
(24, 19, 13, 1, 1500000, 1500000),
(25, 20, 5, 1, 390000, 390000),
(26, 21, 4, 1, 420000, 420000),
(27, 22, 19, 2, 280000, 560000),
(28, 23, 7, 1, 1260000, 1260000),
(29, 24, 32, 1, 1800000, 1800000),
(30, 25, 69, 1, 600000, 600000),
(31, 26, 67, 1, 1800000, 1800000),
(32, 27, 15, 1, 150000, 150000),
(33, 28, 26, 1, 450000, 450000),
(34, 29, 26, 1, 450000, 450000),
(35, 30, 49, 1, 1800000, 1800000),
(36, 31, 15, 1, 150000, 150000),
(37, 32, 14, 1, 240000, 240000),
(38, 33, 40, 1, 1500000, 1500000);

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `IdHD` int(11) NOT NULL,
  `IdKH` int(11) NOT NULL,
  `Total` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  `ExpectDate` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `PTTT` tinyint(1) NOT NULL DEFAULT 1,
  `AddressLine` varchar(200) NOT NULL,
  `Ward` varchar(45) NOT NULL,
  `Provinces` varchar(45) NOT NULL,
  `District` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`IdHD`, `IdKH`, `Total`, `Date`, `ExpectDate`, `Status`, `PTTT`, `AddressLine`, `Ward`, `Provinces`, `District`) VALUES
(1, 1, 1740000, '2025-03-02', '2025-05-05', 3, 2, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(2, 1, 2550000, '2024-05-16', '2024-05-19', 2, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(3, 1, 585000, '2024-05-16', '2024-05-19', 1, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(4, 1, 1260000, '2024-05-16', '2024-05-19', 1, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(5, 1, 5700000, '2025-02-16', '2025-02-19', 3, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(6, 1, 1500000, '2025-02-16', '2025-02-19', 3, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(7, 1, 2700000, '2025-01-16', '2025-01-19', 3, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(8, 1, 450000, '2025-05-02', '2025-05-05', 3, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(9, 1, 225000, '2025-05-12', '2025-05-15', 3, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(10, 1, 525000, '2025-04-16', '2025-04-19', 3, 1, 'abc', 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(11, 2, 1800000, '2025-04-16', '2025-04-19', 3, 1, 'cda', 'Thị trấn Dầu Giây', 'Tỉnh Đồng Nai', 'Huyện Thống Nhất'),
(12, 2, 450000, '2025-01-16', '2025-01-19', 3, 1, 'cda', 'Thị trấn Dầu Giây', 'Tỉnh Đồng Nai', 'Huyện Thống Nhất'),
(13, 2, 450000, '2024-05-16', '2024-05-19', 1, 1, 'cda', 'Thị trấn Dầu Giây', 'Tỉnh Đồng Nai', 'Huyện Thống Nhất'),
(14, 3, 900000, '2025-03-16', '2025-03-19', 3, 1, '123', 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(15, 3, 2700000, '2025-02-16', '2025-02-19', 3, 1, '123', 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(16, 3, 1200000, '2025-05-10', '2025-05-13', 3, 1, '123', 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(17, 4, 960000, '2025-05-10', '2025-05-13', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(18, 4, 1500000, '2025-03-16', '2025-03-19', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(19, 4, 1500000, '2025-05-16', '2025-05-19', 1, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(20, 4, 390000, '2025-05-16', '2025-05-19', 2, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(21, 4, 420000, '2025-05-16', '2025-05-19', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(22, 4, 560000, '2025-05-16', '2025-05-19', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(23, 4, 1260000, '2024-12-16', '2024-12-19', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(24, 4, 1800000, '2025-05-16', '2025-05-19', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(25, 5, 600000, '2025-05-16', '2025-05-19', 2, 1, '888', 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(26, 5, 1800000, '2025-05-16', '2025-05-19', 2, 1, '888', 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(27, 6, 150000, '2025-05-16', '2025-05-19', 2, 1, 'u i a ', 'Phường 11', 'Thành phố Hồ Chí Minh', 'Quận Bình Thạnh'),
(28, 6, 450000, '2025-05-16', '2025-05-19', 1, 1, 'u i a ', 'Phường 11', 'Thành phố Hồ Chí Minh', 'Quận Bình Thạnh'),
(29, 6, 450000, '2025-01-16', '2025-01-19', 3, 1, 'u i a ', 'Phường 11', 'Thành phố Hồ Chí Minh', 'Quận Bình Thạnh'),
(30, 6, 1800000, '2025-03-20', '2025-03-23', 3, 1, 'u i a ', 'Phường 11', 'Thành phố Hồ Chí Minh', 'Quận Bình Thạnh'),
(31, 4, 150000, '2025-05-16', '2025-05-19', 3, 1, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(32, 4, 240000, '2025-05-16', '2025-05-19', 3, 2, '220 Thoại Ngọc Hầu', 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(33, 2, 1500000, '2025-05-16', '2025-05-19', 1, 1, '123', 'Phường Phúc Xá', 'Thành phố Hà Nội', 'Quận Ba Đình');

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `IdKH` int(11) NOT NULL,
  `Account` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `PNumber` char(10) NOT NULL,
  `AddressLine` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `Ward` varchar(45) NOT NULL,
  `Provinces` varchar(45) NOT NULL,
  `District` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`IdKH`, `Account`, `Email`, `Password`, `Name`, `PNumber`, `AddressLine`, `Status`, `Ward`, `Provinces`, `District`) VALUES
(1, 'vitcovandinh', 'toibingu@gmail.com', 'p929OPCGNyoQVusMGw2bxg==', 'Đặng Thế Vinh', '0912345678', 'abc', 1, 'Phường Phổ Văn', 'Tỉnh Quảng Ngãi', 'Thị xã Đức Phổ'),
(2, 'pt287', 'pt2874@gmail.com', 'nVH9/MxCX9cVAUvDlkY7bg==', 'Trần Đăng Phát', '0909999001', 'cda', 1, 'Thị trấn Dầu Giây', 'Tỉnh Đồng Nai', 'Huyện Thống Nhất'),
(3, 'kendrick', 'nnt@gmail.com', 'JIPA509Qw/3mZGGQPku/Sw==', 'Nguyễn Ngọc Tuấn', '0990000888', '123', 1, 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(4, 'TuiLaZit', 'tr.trungviet04@gmail.com', 'uBo61muTndRb6cPD0J50JQ==', 'Trần Trung Việt', '0937024435', '220 Thoại Ngọc Hầu', 1, 'Phường Phú Thạnh', 'Thành phố Hồ Chí Minh', 'Quận Tân Phú'),
(5, 'ainuhukhong', 'ndq@gmail.com', '/0Bx0JmsBD7j/+vcVEsG5A==', 'Nguyễn Đại Quốc', '0888888888', '888', 1, 'Xã Bà Điểm', 'Thành phố Hồ Chí Minh', 'Huyện Hóc Môn'),
(6, 'sugarcane', 'nmp@gmail.com', 'a5cmwMidtUAvam4Hk+OlQg==', 'Ngô Minh Phát', '0907453231', 'u i a ', 1, 'Phường 11', 'Thành phố Hồ Chí Minh', 'Quận Bình Thạnh');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `IdNV` int(11) NOT NULL,
  `Account` varchar(50) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `PNumber` char(10) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `IdPos` varchar(20) NOT NULL,
  `Address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`IdNV`, `Account`, `Password`, `Name`, `PNumber`, `Status`, `IdPos`, `Address`) VALUES
(1, 'Admin', 'e52Mpw7zHyp7zFVExAxkSg==', 'Trần Trung Việt', '0937024435', 1, 'Admin', 'Tân phú, TPHCM');

-- --------------------------------------------------------

--
-- Table structure for table `nhaphang`
--

CREATE TABLE `nhaphang` (
  `IdNhapHang` int(11) NOT NULL,
  `IdSP` int(11) NOT NULL,
  `ImportPrice` int(11) NOT NULL,
  `ImportDate` date NOT NULL,
  `ImportQuantity` int(11) NOT NULL,
  `ProductName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhaphang`
--

INSERT INTO `nhaphang` (`IdNhapHang`, `IdSP`, `ImportPrice`, `ImportDate`, `ImportQuantity`, `ProductName`) VALUES
(1, 1, 400000, '2025-05-15', 50, 'NewJeans \'Supernatural\' NJ X MURAKAMI Drawstring Bag ver.'),
(2, 2, 300000, '2025-05-15', 50, ''),
(3, 3, 300000, '2025-05-15', 50, ''),
(4, 4, 300000, '2025-05-15', 50, ''),
(5, 5, 300000, '2025-05-15', 100, ''),
(6, 6, 300000, '2025-05-15', 100, ''),
(7, 7, 900000, '2025-05-15', 50, ''),
(8, 12, 900000, '2025-05-15', 10, ''),
(9, 9, 400000, '2025-05-15', 50, ''),
(10, 10, 400000, '2025-05-15', 100, ''),
(11, 11, 400000, '2025-05-15', 50, ''),
(12, 14, 200000, '2025-05-15', 10, ''),
(13, 8, 100000, '2025-05-15', 20, ''),
(14, 13, 1000000, '2025-05-15', 10, ''),
(15, 15, 100000, '2025-05-15', 10, ''),
(16, 16, 500000, '2025-05-15', 40, ''),
(17, 18, 150000, '2025-05-15', 10, 'Dazed & Confused (2024.11 / D type)'),
(18, 17, 100000, '2025-05-16', 20, 'NewJeans Supernatural Fan Minji ver'),
(19, 23, 100000, '2025-05-16', 50, 'Binky Bong CHARM SET'),
(20, 22, 150000, '2025-05-16', 50, 'billboard Artist (H type)'),
(21, 19, 200000, '2025-05-16', 50, 'NewJeans x MURAKAMI BUNNIES CAMP 2024 TOKYO DOME LIGHT STICK STRAP'),
(22, 20, 500000, '2025-05-16', 100, 'NewJeans ‘OMG’ Weverse Albums ver'),
(23, 21, 450000, '2025-05-16', 50, 'NewJeans \'How Sweet\' Weverse Albums ver. (Set)'),
(24, 24, 150000, '2025-05-16', 50, 'Light Stick Parts (NJ & TOKKI)'),
(25, 29, 300000, '2025-05-16', 50, 'IVE \'I\'VE IVE\' (Ver. 1)'),
(26, 30, 300000, '2025-05-16', 50, 'IVE \'I\'VE IVE\' (Ver. 2) '),
(27, 31, 300000, '2025-05-16', 10, 'IVE \'I\'VE IVE\' (Ver. 3)'),
(28, 35, 300000, '2025-05-16', 50, 'IVE \'I\'VE MINE\' (Baddie ver.)'),
(29, 33, 300000, '2025-05-16', 50, 'IVE \'I\'VE MINE\' (Either Way ver.)'),
(30, 36, 300000, '2025-05-16', 50, 'IVE \'I\'VE MINE\' (Loved IVE ver.)'),
(31, 34, 300000, '2025-05-16', 20, 'IVE \'I\'VE MINE\' (Off The Records ver.)'),
(32, 37, 300000, '2025-05-16', 20, 'IVE \'LOVE DIVE\' (Ver. 1)'),
(33, 38, 300000, '2025-05-16', 20, 'IVE \'LOVE DIVE\' (Ver. 2)'),
(34, 39, 300000, '2025-05-16', 30, 'IVE \'LOVE DIVE\' (Ver. 3)'),
(35, 25, 300000, '2025-05-16', 100, 'IVE \'Switch\' (Loved IVE ver.)'),
(36, 28, 300000, '2025-05-16', 50, 'IVE \'Switch\' (On ver.)'),
(37, 27, 300000, '2025-05-16', 50, 'IVE \'Switch\' (Off ver.)'),
(38, 26, 300000, '2025-05-16', 50, 'IVE \'Switch\' (Spin-Off ver.)'),
(39, 41, 150000, '2025-05-16', 30, 'IVE \'SWITCH\' Digipack GAEUL Ver.'),
(40, 45, 150000, '2025-05-16', 50, 'IVE \'SWITCH\' Digipack Leeseo Ver'),
(41, 44, 150000, '2025-05-16', 20, 'IVE \'SWITCH\' Digipack Liz Ver'),
(42, 43, 150000, '2025-05-16', 50, 'IVE \'SWITCH\' Digipack Rei Ver'),
(43, 46, 150000, '2025-05-16', 100, 'IVE \'SWITCH\' Digipack Wonyoung Ver'),
(44, 42, 150000, '2025-05-16', 60, 'IVE \'SWITCH\' Digipack Yujin Ver'),
(45, 40, 1000000, '2025-05-16', 10, 'IVE - THE 1ST WORLD TOUR [SHOW WHAT I HAVE] ENCORE BLU-RAY'),
(46, 32, 1200000, '2025-05-16', 20, 'IVE Official Light Stick'),
(47, 53, 300000, '2025-05-16', 50, 'BIGBANG \'ALIVE\' Album'),
(48, 56, 300000, '2025-05-16', 50, 'BIGBANG \'Bigbang Vol. 1\' Album'),
(49, 55, 400000, '2025-05-16', 100, 'BIGBANG \'Remember\' Album'),
(50, 54, 300000, '2025-05-16', 20, 'BIGBANG \'Tonight\' Mini Album'),
(51, 49, 1200000, '2025-05-16', 20, 'BIGBANG 10th Anniversary Stadium Tour 2016 DVD'),
(52, 52, 400000, '2025-05-16', 10, 'BIGBANG 2015 World Tour \'Made\' DVD'),
(53, 50, 1200000, '2025-05-16', 20, 'BIGBANG 2017 Concert Last Dance in Seoul DVD'),
(54, 47, 1300000, '2025-05-16', 30, 'BIGBANG Light Stick Ver. 4'),
(55, 48, 300000, '2025-05-16', 50, 'BIGBANG MADE Album'),
(56, 51, 400000, '2025-05-16', 20, 'BIGBANG Special Edition \'Still Alive\' Album'),
(57, 72, 400000, '2025-05-16', 50, 'BLACKPINK \'BLACKPINK 2019 World Tour [IN YOUR AREA] Seoul DVD\''),
(58, 73, 400000, '2025-05-16', 10, 'BLACKPINK \'BLACKPINK 2021 The Show DVD\''),
(59, 74, 400000, '2025-05-16', 10, 'BLACKPINK \'BLACKPINK Summer Diary in Hawaii\''),
(60, 76, 1000000, '2025-05-16', 10, 'BLACKPINK \'BLACKPINK Welcoming Collection 2019\''),
(61, 75, 1200000, '2025-05-16', 10, 'BLACKPINK \'BLACKPINK Welcoming Collection 2022\''),
(62, 69, 400000, '2025-05-16', 10, 'BLACKPINK \'BORN PINK\' Album'),
(63, 70, 300000, '2025-05-16', 10, 'BLACKPINK \'Kill This Love\' Mini Album'),
(64, 71, 300000, '2025-05-16', 10, 'BLACKPINK \'Square Up\' Mini Album'),
(65, 68, 500000, '2025-05-16', 10, 'BLACKPINK \'THE ALBUM\''),
(66, 67, 1200000, '2025-05-16', 10, 'BLACKPINK Official Light Stick Ver. 2'),
(67, 66, 1000000, '2025-05-16', 10, 'BTS \'2021 Muster Sowoozoo\' Blu-ray'),
(68, 59, 600000, '2025-05-16', 10, 'BTS \'BE\' Album (Deluxe Edition)'),
(69, 62, 400000, '2025-05-16', 20, 'BTS \'Butter\' Single Album'),
(70, 60, 300000, '2025-05-16', 20, 'BTS \'Love Yourself: Tear\' Album'),
(71, 58, 300000, '2025-05-16', 50, 'BTS \'MAP OF THE SOUL: 7\' Album'),
(72, 65, 1000000, '2025-05-16', 30, 'BTS \'Memories of 2021\' DVD'),
(73, 61, 350000, '2025-05-16', 20, 'BTS \'Proof\' Anthology Album'),
(74, 57, 1200000, '2025-05-16', 100, 'BTS Official Light Stick Ver. 4 (Army Bomb'),
(75, 64, 300000, '2025-05-16', 100, 'BTS \'The Most Beautiful Moment in Life Pt. 2\' Album'),
(76, 63, 300000, '2025-05-16', 60, 'BTS \'Wings\' Album');

-- --------------------------------------------------------

--
-- Table structure for table `nhom`
--

CREATE TABLE `nhom` (
  `IdGRP` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Company` varchar(100) DEFAULT NULL,
  `Info` varchar(200) NOT NULL,
  `IMG` varchar(256) DEFAULT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhom`
--

INSERT INTO `nhom` (`IdGRP`, `Name`, `Company`, `Info`, `IMG`, `Status`) VALUES
(1, 'NJZ', 'không có ', 'Nhóm gồm 5 thành viên: Minji, Hanni, Danielle, Haerin, Hyein và debut vào ngày 22/7/2022', '68257810aa35c.png', 1),
(2, 'IVE', 'Starship Ent.', 'Nhóm gồm có 6 thành viên: Gaeul, Yujin, Rei, Wonyoung, Liz và Leeseo với Yujin là nhóm trưởng, Yujin và Wonyoung là cựu thành viên của nhóm Iz*one.', '68257887c5760.png', 1),
(3, 'Big Bang', 'YG Ent.', 'Big Bang là một nhóm nhạc nam Hàn Quốc được thành lập bởi YG Entertainment vào năm 2006. Nhóm ban đầu gồm năm thành viên: G-Dragon, Taeyang, T.O.P, Daesung và Seungri. Tuy nhiên, Seungri đã rời khỏi n', '682579269e9d8.png', 1),
(4, 'BTS', 'Big Hit Ent.', 'BTS (방탄소년단) là một nhóm nhạc nam Hàn Quốc được thành lập bởi Big Hit Entertainment (nay là HYBE Labels) vào năm 2013. Nhóm gồm 7 thành viên: RM, Jin, Suga, J-Hope, Jimin, V và Jung Kook\r\n', '68257958a4813.png', 1),
(5, 'BlackPink', 'YG Ent.', 'BLACKPINK là một nhóm nhạc nữ Hàn Quốc được thành lập bởi YG Entertainment vào năm 2016. Nhóm gồm 4 thành viên: Jisoo, Jennie, Rosé và Lisa\r\n', '68257a0a9aa2b.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `IdSP` int(11) NOT NULL,
  `IdGRP` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Ratio` int(11) NOT NULL,
  `Price` int(11) NOT NULL,
  `IMG` varchar(256) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0,
  `Info` varchar(200) NOT NULL,
  `ReleaseDate` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`IdSP`, `IdGRP`, `Name`, `Type`, `Ratio`, `Price`, `IMG`, `Quantity`, `Info`, `ReleaseDate`, `Status`) VALUES
(1, 1, 'NewJeans \'Supernatural\' NJ X MURAKAMI Drawstring Bag ver.', 'Album', 150, 600000, 'supnadrawstring.jpg', 50, 'NewJeans \'Supernatural\' NJ X MURAKAMI Drawstring Bag ver. là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, hợp tác với nghệ sĩ Takashi Murakami.\r\n', '2024-05-07', 1),
(2, 1, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Hanni ver', 'Album', 170, 510000, 'hannisupna.jpg', 50, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Hanni ver. là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, hợp tác với nghệ sĩ Takashi Murakami.\r\n', '2024-07-05', 1),
(3, 1, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Minji ver', 'Album', 150, 450000, 'minjisupna.jpg', 45, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Minji ver. là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, hợp tác với nghệ sĩ Takashi Murakami.\r\n', '2024-07-05', 1),
(4, 1, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Danielle ver', 'Album', 140, 420000, 'Danisupna.jpg', 49, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Danielle ver. là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, hợp tác với nghệ sĩ Takashi Murakami.\r\n', '2024-07-05', 1),
(5, 1, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Haerin ver', 'Album', 130, 390000, 'haerinsupna.jpg', 99, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Haerin ver. là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, hợp tác với nghệ sĩ Takashi Murakami.\r\n', '2024-07-05', 1),
(6, 1, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Hyein ver', 'Album', 115, 345000, 'hyein supna.jpg', 90, 'NewJeans \'Supernatural\' NJ X MURAKAMI Cross Bag Hyein ver. là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, hợp tác với nghệ sĩ Takashi Murakami.\r\n', '2024-07-05', 1),
(7, 1, 'Binky Bong', 'Merch', 140, 1260000, 'binkybong.jpg', 48, 'Binky Bong là lightstick chính thức của NewJeans, được thiết kế theo hình dáng chú thỏ, tượng trưng cho fandom Bunnies của nhóm.\r\n', '2023-03-30', 1),
(8, 1, 'Binky Bong Heart-Star Parts', 'Merch', 120, 120000, 'binkyhearteye.jpg', 20, 'Binky Bong\'s Heart-Star Parts for decorate', '2023-11-20', 1),
(9, 1, '[SIZE M] BUNNIES CAMP 2024 TOKYO DOME T-SHIRTS (BLUE)', 'Collab', 150, 600000, 'bunniescampshirt.png', 50, 'BUNNIES CAMP 2024 TOKYO DOME T-SHIRTS (BLUE) là mẫu áo chính thức của sự kiện fan meeting Bunnies Camp 2024 của NewJeans tại Tokyo Dome.\r\n', '2024-07-02', 1),
(10, 1, '[SIZE L] BUNNIES CAMP 2024 TOKYO DOME T-SHIRTS (BLUE)', 'Collab', 150, 600000, 'bunniescampshirt.png', 100, 'BUNNIES CAMP 2024 TOKYO DOME T-SHIRTS (BLUE) là mẫu áo chính thức của sự kiện fan meeting Bunnies Camp 2024 của NewJeans tại Tokyo Dome.\r\n', '2024-07-02', 1),
(11, 1, '[SIZE XL] BUNNIES CAMP 2024 TOKYO DOME T-SHIRTS (BLUE)', 'Collab', 140, 560000, 'bunniescampshirt.png', 50, 'BUNNIES CAMP 2024 TOKYO DOME T-SHIRTS (BLUE) là mẫu áo chính thức của sự kiện fan meeting Bunnies Camp 2024 của NewJeans tại Tokyo Dome.\r\n', '2024-07-02', 1),
(12, 1, 'Bunnies MEMBERSHIP', 'Membership', 150, 1350000, 'membership.png', 10, 'Bunnies MEMBERSHIP là fanclub chính thức của NewJeans, mang đến nhiều quyền lợi đặc biệt cho người hâm mộ.\r\n', '2024-07-27', 1),
(13, 1, 'NewJeans 2025 SEASON\'S GREETINGS', 'Seasons Greetings', 150, 1500000, 'ssgtnjs.jpg', 7, 'NewJeans 2025 SEASON\'S GREETINGS là bộ sản phẩm đặc biệt dành cho người hâm mộ, mang chủ đề \"The Fairy Association\", hé lộ những câu chuyện thú vị về các thành viên.\r\n', '2025-01-15', 1),
(14, 1, 'Dazed & Confused (2024.11 / B type)', 'Magazine', 120, 240000, 'dazedminji.png', 5, 'Dazed & Confused (2024.11 / B type) là phiên bản tạp chí đặc biệt có sự xuất hiện của NewJeans, mang đến những hình ảnh và nội dung độc quyền.\r\n', '2024-10-30', 1),
(15, 1, 'Dazed & Confused (2024.11 / C type)', 'Magazine', 150, 150000, 'dazedminjiC.png', 8, 'Dazed & Confused (2024.11 / C type) là phiên bản tạp chí đặc biệt có sự xuất hiện của NewJeans, mang đến những hình ảnh và nội dung độc quyền.\r\n', '2024-10-30', 1),
(16, 1, 'NewJeans \'Supernatural\' Weverse Albums ver. (Set)', 'Album', 150, 750000, 'supnaweverse.png', 40, 'NewJeans \'Supernatural\' Weverse Albums ver. (Set) là phiên bản đặc biệt của đĩa đơn Nhật Bản đầu tiên của nhóm, gồm 3 phiên bản album Weverse.\r\n', '2024-06-21', 1),
(17, 1, 'NewJeans Supernatural Fan Minji ver', 'Collab', 120, 120000, 'minji fan.png', 15, 'Supernatural fan make for the Tokyo Dome Fan Tour', '2024-07-12', 1),
(18, 1, 'Dazed & Confused (2024.11 / D type)', 'Magazine', 150, 225000, 'dazedminji D.png', 9, 'Dazed & Confused (2024.11 / D type) là phiên bản tạp chí đặc biệt có sự xuất hiện của NewJeans, mang đến những hình ảnh và nội dung độc quyền.', '2024-10-30', 1),
(19, 1, 'NewJeans x MURAKAMI BUNNIES CAMP 2024 TOKYO DOME LIGHT STICK STRAP', 'Collab', 140, 280000, 'ltwrp.png', 48, 'NewJeans x MURAKAMI BUNNIES CAMP 2024 TOKYO DOME LIGHT STICK STRAP', '2024-07-02', 1),
(20, 1, 'NewJeans ‘OMG’ Weverse Albums ver', 'Album', 150, 750000, 'omgweverse.png', 100, 'NewJeans \'OMG\' Weverse Albums ver. là phiên bản album kỹ thuật số đặc biệt của nhóm, đi kèm với các photocard và nội dung độc quyền trên Weverse.\r\n', '2023-01-02', 1),
(21, 1, 'NewJeans \'How Sweet\' Weverse Albums ver. (Set)', 'Album', 130, 585000, 'hswv.png', 50, 'NewJeans \'How Sweet\' Weverse Albums ver. (Set) là phiên bản album kỹ thuật số đặc biệt của nhóm, gồm 3 phiên bản album Weverse.\r\n', '2024-05-24', 1),
(22, 1, 'billboard Artist (H type)', 'Magazine', 130, 195000, 'bbHtype.png', 50, 'Billboard Artist (H type) là phiên bản tạp chí đặc biệt có sự xuất hiện của NewJeans, mang đến những hình ảnh và nội dung độc quyền.\r\n- \r\n', '2024-10-25', 1),
(23, 1, 'Binky Bong CHARM SET', 'Merch', 140, 140000, 'charmset.png', 50, 'Binky Bong CHARM SET', '2023-05-30', 1),
(24, 1, 'Light Stick Parts (NJ & TOKKI)', 'Merch', 150, 225000, 'njzparts.png', 50, 'Light Stick Parts (NJ & TOKKI)', '2023-05-27', 1),
(25, 2, 'IVE \'Switch\' (Loved IVE ver.)', 'Album', 140, 420000, 'switchpink.jpg', 100, 'IVE \'Switch\' (Loved IVE ver.) là phiên bản màu hồng đặc biệt của album Switch, EP thứ hai của nhóm nhạc nữ IVE.\r\n', '2024-04-30', 1),
(26, 2, 'IVE \'Switch\' (Spin-Off ver.)', 'Album', 150, 450000, 'switchblue.jpg', 42, 'IVE \'Switch\' (Spin-Off ver.) là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, mang đến một góc nhìn mới về phong cách âm nhạc của nhóm.\r\n\r\n', '2024-04-30', 1),
(27, 2, 'IVE \'Switch\' (Off ver.)', 'Album', 150, 450000, 'switch off.jpg', 50, 'IVE \'Switch\' (Off ver.) là một phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, mang đến một phong cách độc đáo và mới mẻ.\r\n', '2024-04-29', 1),
(28, 2, 'IVE \'Switch\' (On ver.)', 'Album', 150, 450000, 'switchon.jpg', 50, 'IVE \'Switch\' (On ver.) là một phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đa dạng và mới mẻ.\r\n- \r\n', '2024-04-29', 1),
(29, 2, 'IVE \'I\'VE IVE\' (Ver. 1)', 'Album', 150, 450000, 'iveihave1st.jpg', 50, 'IVE \'I\'VE IVE\' (Ver. 1) là phiên bản đầu tiên của album phòng thu đầu tiên của nhóm nhạc nữ IVE, mang đến một phong cách âm nhạc đa dạng và mạnh mẽ.\r\n', '2023-04-11', 1),
(30, 2, 'IVE \'I\'VE IVE\' (Ver. 2) ', 'Album', 150, 450000, 'iveihave2nd.jpg', 50, 'IVE \'I\'VE IVE\' (Ver. 2) là phiên bản thứ hai của album phòng thu đầu tiên của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đa dạng và mạnh mẽ.\r\n', '2023-04-11', 1),
(31, 2, 'IVE \'I\'VE IVE\' (Ver. 3)', 'Album', 150, 450000, 'iveihave3rd.jpg', 10, 'IVE \'I\'VE IVE\' (Ver. 3) là phiên bản thứ ba của album phòng thu đầu tiên của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đa dạng và mạnh mẽ.\r\n', '2023-04-11', 1),
(32, 2, 'IVE Official Light Stick', 'Merch', 150, 1800000, 'ivelightstick.jpg', 19, 'IVE Official Light Stick là lightstick chính thức của nhóm nhạc nữ IVE, được thiết kế với phong cách hiện đại và sang trọng.\r\n', '2023-03-24', 1),
(33, 2, 'IVE \'I\'VE MINE\' (Either Way ver.)', 'Album', 150, 450000, 'ivemineew.jpg', 50, 'IVE \'I\'VE MINE\' (Either Way ver.) là phiên bản đặc biệt của EP đầu tiên của nhóm nhạc nữ IVE, mang đến một phong cách âm nhạc đa dạng và sâu sắc.\r\n', '2023-10-13', 1),
(34, 2, 'IVE \'I\'VE MINE\' (Off The Records ver.)', 'Album', 150, 450000, 'ive-i-ve-mine-off-the-record-ver.jpg', 19, 'IVE \'I\'VE MINE\' (Off The Records ver.) là phiên bản đặc biệt của EP đầu tiên của nhóm nhạc nữ IVE, mang đến một phong cách âm nhạc đa dạng và sâu sắc.\r\n', '2024-10-13', 1),
(35, 2, 'IVE \'I\'VE MINE\' (Baddie ver.)', 'Album', 150, 450000, 's-l400.jpg', 50, 'IVE \'I\'VE MINE\' (Baddie ver.) là phiên bản đặc biệt của EP đầu tiên của nhóm nhạc nữ IVE, mang đến phong cách mạnh mẽ và cá tính.\r\n', '2023-10-13', 1),
(36, 2, 'IVE \'I\'VE MINE\' (Loved IVE ver.)', 'Album', 150, 450000, '$_57.jpg', 50, 'IVE \'I\'VE MINE\' (Loved IVE ver.) là phiên bản đặc biệt của EP đầu tiên của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đa dạng và mạnh mẽ.\r\n', '2023-10-13', 1),
(37, 2, 'IVE \'LOVE DIVE\' (Ver. 1)', 'Album', 150, 450000, 'lovedive1.jpg', 20, 'IVE \'LOVE DIVE\' (Ver. 1) là phiên bản đầu tiên của single album thứ hai của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đầy cuốn hút.\r\n', '2022-04-06', 1),
(38, 2, 'IVE \'LOVE DIVE\' (Ver. 2)', 'Album', 150, 450000, 'lovedive2.jpg', 20, 'IVE \'LOVE DIVE\' (Ver. 2) là phiên bản thứ hai của single album thứ hai của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đầy cuốn hút.\r\n- \r\n', '2022-04-06', 1),
(39, 2, 'IVE \'LOVE DIVE\' (Ver. 3)', 'Album', 130, 390000, 'lovedive3.jpeg', 30, 'IVE \'LOVE DIVE\' (Ver. 3) là phiên bản thứ ba của single album thứ hai của nhóm nhạc nữ IVE, mang đến phong cách âm nhạc đầy cuốn hút.\r\n', '2022-06-04', 1),
(40, 2, 'IVE - THE 1ST WORLD TOUR [SHOW WHAT I HAVE] ENCORE BLU-RAY', 'Bluray', 150, 1500000, 'swihencorebluray.jpg', 7, 'IVE - THE 1ST WORLD TOUR [SHOW WHAT I HAVE] ENCORE BLU-RAY là phiên bản Blu-ray đặc biệt ghi lại các buổi diễn encore của nhóm tại KSPO Dome, Seoul vào ngày 10-11 tháng 8, 2024\r\n', '2025-04-30', 1),
(41, 2, 'IVE \'SWITCH\' Digipack GAEUL Ver.', 'Digipack', 150, 225000, 'gaeuldigiswitch.jpg', 30, 'IVE \'SWITCH\' Digipack GAEUL Ver. là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, tập trung vào thành viên Gaeul.\r\n', '2024-04-30', 1),
(42, 2, 'IVE \'SWITCH\' Digipack Yujin Ver', 'Digipack', 150, 225000, 'yujinswitch.png', 59, 'IVE \'SWITCH\' Digipack Yujin Ver. là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, tập trung vào thành viên An Yujin.\r\n', '2024-04-29', 1),
(43, 2, 'IVE \'SWITCH\' Digipack Rei Ver', 'Digipack', 150, 225000, 'ReiSwitch.jpg', 50, 'IVE \'SWITCH\' Digipack Rei Ver. là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, tập trung vào thành viên Rei.\r\n', '2024-04-30', 1),
(44, 2, 'IVE \'SWITCH\' Digipack Liz Ver', 'Digipack', 150, 225000, 'lizswitch.jpg', 20, 'IVE \'SWITCH\' Digipack Liz Ver. là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, tập trung vào thành viên Liz.\r\n', '2024-04-30', 1),
(45, 2, 'IVE \'SWITCH\' Digipack Leeseo Ver', 'Digipack', 150, 225000, 'leeseo.jpeg', 50, 'IVE \'SWITCH\' Digipack Leeseo Ver. là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, tập trung vào thành viên Leeseo.\r\n', '2024-04-30', 1),
(46, 2, 'IVE \'SWITCH\' Digipack Wonyoung Ver', 'Digipack', 150, 225000, 'wy.jpg', 100, 'IVE \'SWITCH\' Digipack Wonyoung Ver. là phiên bản đặc biệt của EP thứ hai của nhóm nhạc nữ IVE, tập trung vào thành viên Jang Wonyoung.\r\n', '2024-04-29', 1),
(47, 3, 'BIGBANG Light Stick Ver. 4', 'Merch', 150, 1950000, 'bigbanglt.jpg', 30, 'Lightstick chính thức của nhóm với thiết kế vương miện đặc trưng.', '2016-04-10', 1),
(48, 3, 'BIGBANG MADE Album', 'Album', 150, 450000, 'made.jpg', 48, 'Album phòng thu cuối cùng trước khi các thành viên nhập ngũ, gồm các hit như \"FXXK IT\" và \"LAST DANCE\".', '2016-12-12', 1),
(49, 3, 'BIGBANG 10th Anniversary Stadium Tour 2016 DVD', 'DVD', 150, 1800000, '0to10.jpg', 19, 'DVD ghi lại tour diễn kỷ niệm 10 năm của nhóm tại Nhật Bản.', '2017-05-20', 1),
(50, 3, 'BIGBANG 2017 Concert Last Dance in Seoul DVD', 'DVD', 150, 1800000, 'XL.jpg', 19, 'DVD ghi lại concert cuối cùng trước khi các thành viên nhập ngũ.', '2018-07-06', 1),
(51, 3, 'BIGBANG Special Edition \'Still Alive\' Album', 'Album', 150, 600000, '600x600bf-60.jpg', 20, 'Phiên bản đặc biệt của album \"Alive\" với ca khúc mới \"Monster\".', '2012-06-03', 1),
(52, 3, 'BIGBANG 2015 World Tour \'Made\' DVD', 'DVD', 150, 600000, '00001342535.jpg', 10, 'DVD ghi lại tour diễn toàn cầu của nhóm.', '2016-07-06', 1),
(53, 3, 'BIGBANG \'ALIVE\' Album', 'Album', 180, 540000, 'imag0080.jpg', 50, 'Album mang tính biểu tượng với các ca khúc như \"Fantastic Baby\" và \"Blue\".', '2012-02-29', 1),
(54, 3, 'BIGBANG \'Tonight\' Mini Album', 'Album', 150, 450000, 'ab67616d00001e0269182adbf1f19b0df7f8ec13.jpg', 19, 'Mini album với ca khúc chủ đề \"Tonight\".', '2011-02-24', 1),
(55, 3, 'BIGBANG \'Remember\' Album', 'Album', 150, 600000, '600x600bf-60 (1).jpg', 100, 'Album với ca khúc nổi tiếng \"Sunset Glow\".', '2008-11-05', 1),
(56, 3, 'BIGBANG \'Bigbang Vol. 1\' Album', 'Album', 150, 450000, '81I+j2+l5RL._UF1000,1000_QL80_.jpg', 50, 'Album đầu tay của nhóm với ca khúc \"We Belong Together\".', '2006-12-22', 1),
(57, 4, 'BTS Official Light Stick Ver. 4 (Army Bomb', 'Merch', 150, 1800000, 'army-bomb-special-lightstick-bts-ver-4-official-chinh-hang-mots.jpg', 100, 'Lightstick chính thức của BTS với khả năng đồng bộ ánh sáng tại concert.', '2018-12-03', 1),
(58, 4, 'BTS \'MAP OF THE SOUL: 7\' Album', 'Album', 150, 450000, 'BTS_-_Map_of_the_Soul_7.png', 50, 'Album phòng thu với các ca khúc nổi tiếng như \"ON\" và \"Black Swan\"', '2020-02-21', 1),
(59, 4, 'BTS \'BE\' Album (Deluxe Edition)', 'Album', 150, 900000, 'ad2a6da4567db54b50edee745b8a146b.jpg', 9, 'Album mang thông điệp về hy vọng và kết nối trong thời kỳ đại dịch.', '2020-11-20', 1),
(60, 4, 'BTS \'Love Yourself: Tear\' Album', 'Album', 150, 450000, 'Love_Yourself_Tear_Cover.jpeg', 20, 'Album với ca khúc chủ đề \"Fake Love\", mang thông điệp về tình yêu bản thân.', '2018-05-18', 1),
(61, 4, 'BTS \'Proof\' Anthology Album', 'Album', 150, 525000, '41MoPTfLk9L.jpg', 19, 'Album tổng hợp các ca khúc nổi bật trong sự nghiệp của nhóm.', '2022-06-10', 1),
(62, 4, 'BTS \'Butter\' Single Album', 'Album', 150, 600000, 'ab67616d0000b273ed656680374294d5217193fa.jpg', 18, 'Album single với ca khúc \"Butter\" và \"Permission to Dance\".', '2021-07-09', 1),
(63, 4, 'BTS \'Wings\' Album', 'Album', 150, 450000, 'Wings_BTS_album.jpg', 60, 'Album mang phong cách trưởng thành với ca khúc \"Blood Sweat & Tears\".', '2016-10-10', 1),
(64, 4, 'BTS \'The Most Beautiful Moment in Life Pt. 2\' Album', 'Album', 150, 450000, 'BTS_-_The_Most_Beautiful_Moment_In_Life,_Part_2_Cover.jpg', 94, 'Album với ca khúc \"Run\", đánh dấu sự phát triển của nhóm.', '2015-11-30', 1),
(65, 4, 'BTS \'Memories of 2021\' DVD', 'DVD', 150, 1500000, '20220824_CAai2NiNWqlkHgMr1m99boVa.jpg', 30, 'DVD ghi lại những khoảnh khắc đáng nhớ của BTS trong năm 2021.', '2022-07-22', 1),
(66, 4, 'BTS \'2021 Muster Sowoozoo\' Blu-ray', 'Bluray', 150, 1500000, 'vn-11134207-7r98o-lktosslei9ffad.jpg', 10, 'Blu-ray ghi lại sự kiện Muster đặc biệt của BTS.', '2022-02-07', 1),
(67, 5, 'BLACKPINK Official Light Stick Ver. 2', 'Merch', 150, 1800000, 'blackpink-lightstick-officiel-version-01.jpg', 9, 'Lightstick chính thức của BLACKPINK với thiết kế hình búa màu hồng đặc trưng.', '2020-02-02', 1),
(68, 5, 'BLACKPINK \'THE ALBUM\'', 'Album', 150, 750000, '20201003_PqFMr91EaIAIYPFiWeHzFrzp.jpg', 10, 'Album phòng thu đầu tiên của nhóm với các ca khúc nổi tiếng như \"Lovesick Girls\" và \"How You Like That\".', '2022-10-02', 1),
(69, 5, 'BLACKPINK \'BORN PINK\' Album', 'Album', 150, 600000, 'Born_Pink_Digital.jpeg', 8, 'Album phòng thu thứ hai với ca khúc chủ đề \"Shut Down\" và \"Pink Venom\".', '2022-09-16', 1),
(70, 5, 'BLACKPINK \'Kill This Love\' Mini Album', 'Album', 150, 450000, '7857d94ae8dd84a42197597515314a4f.jpg', 10, 'Mini album với ca khúc chủ đề \"Kill This Love\", đánh dấu sự trở lại mạnh mẽ của nhóm.', '2019-04-05', 1),
(71, 5, 'BLACKPINK \'Square Up\' Mini Album', 'Album', 150, 450000, '08144227view.jpg', 10, 'Mini album với ca khúc \"Ddu-Du Ddu-Du\", giúp nhóm đạt nhiều thành tích quốc tế.', '2018-06-15', 1),
(72, 5, 'BLACKPINK \'BLACKPINK 2019 World Tour [IN YOUR AREA] Seoul DVD\'', 'DVD', 150, 600000, 'GD00051983.default.1.jpg', 50, 'DVD ghi lại concert của nhóm tại Seoul trong tour diễn toàn cầu.', '2020-06-02', 1),
(73, 5, 'BLACKPINK \'BLACKPINK 2021 The Show DVD\'', 'DVD', 150, 600000, '61f44a5c-e5f3-49b9-b79d-02edc2963716-global-town-1628674480472.jpeg', 10, 'DVD ghi lại concert trực tuyến đặc biệt của nhóm.', '2021-02-21', 1),
(74, 5, 'BLACKPINK \'BLACKPINK Summer Diary in Hawaii\'', 'DVD', 150, 600000, 'b2762a8b24004a0a337dde3ff7af9a08.jpg', 10, 'Photobook và DVD ghi lại chuyến đi của nhóm tại Hawaii.', '2019-09-12', 1),
(75, 5, 'BLACKPINK \'BLACKPINK Welcoming Collection 2022\'', 'Welcoming Collection', 150, 1800000, 'BLACKPINK2022WelcomeCollection1.jpg', 10, 'Bộ sưu tập đặc biệt dành cho fan với photobook, DVD và nhiều vật phẩm khác.', '2022-03-02', 1),
(76, 5, 'BLACKPINK \'BLACKPINK Welcoming Collection 2019\'', 'Welcoming Collection', 150, 1500000, '5f8a18d27bdf4cd4758a195a81b69e71.jpg', 10, 'Bộ sưu tập đặc biệt dành cho fan với photobook, DVD và nhiều vật phẩm khác.', '2019-03-02', 1),
(77, 3, 'Test', 'Test', 110, 0, 'UIA.png', 0, 'Testing', '2025-05-16', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD PRIMARY KEY (`IdCTHD`),
  ADD KEY `CTHD_HD` (`IdHD`),
  ADD KEY `CTHD_SP` (`IdSP`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`IdHD`),
  ADD KEY `HD_KH` (`IdKH`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`IdKH`),
  ADD UNIQUE KEY `ACCOUNT` (`Account`),
  ADD UNIQUE KEY `PNumber` (`PNumber`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`IdNV`),
  ADD UNIQUE KEY `Account` (`Account`);

--
-- Indexes for table `nhaphang`
--
ALTER TABLE `nhaphang`
  ADD PRIMARY KEY (`IdNhapHang`),
  ADD KEY `NhapHang_SanPham` (`IdSP`);

--
-- Indexes for table `nhom`
--
ALTER TABLE `nhom`
  ADD PRIMARY KEY (`IdGRP`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`IdSP`),
  ADD KEY `SP_GRP` (`IdGRP`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  MODIFY `IdCTHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `IdHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `IdKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `IdNV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nhaphang`
--
ALTER TABLE `nhaphang`
  MODIFY `IdNhapHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `nhom`
--
ALTER TABLE `nhom`
  MODIFY `IdGRP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `IdSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD CONSTRAINT `CTHD_HD` FOREIGN KEY (`IdHD`) REFERENCES `hoadon` (`IdHD`),
  ADD CONSTRAINT `CTHD_SP` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`);

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `HD_KH` FOREIGN KEY (`IdKH`) REFERENCES `khachhang` (`IdKH`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `SP_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
