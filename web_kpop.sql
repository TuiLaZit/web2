-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 09:29 AM
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
-- Table structure for table `chamsockhachhang`
--

CREATE TABLE `chamsockhachhang` (
  `IdCSKH` varchar(10) NOT NULL,
  `IdKH` varchar(10) NOT NULL,
  `IdNV` varchar(10) NOT NULL,
  `INFO` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chitiethoadon`
--

CREATE TABLE `chitiethoadon` (
  `IdCTHD` int(11) NOT NULL,
  `IdHD` varchar(10) NOT NULL,
  `IdSP` varchar(10) NOT NULL,
  `Quantity` smallint(6) NOT NULL,
  `Price` int(11) NOT NULL,
  `SumPrice` int(11) DEFAULT NULL,
  `IdCTKM` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chitietkhuyenmai`
--

CREATE TABLE `chitietkhuyenmai` (
  `IdCTKM` int(10) NOT NULL,
  `IdKM` varchar(10) NOT NULL,
  `IdSP` varchar(10) NOT NULL,
  `IdTV` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `IdHD` varchar(20) NOT NULL,
  `IdKH` varchar(10) NOT NULL,
  `IdNV` varchar(10) NOT NULL,
  `Total` int(11) DEFAULT NULL,
  `Date` date NOT NULL DEFAULT current_timestamp(),
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `IdKH` varchar(10) NOT NULL,
  `Account` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Number` int(10) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`IdKH`, `Account`, `Password`, `Name`, `Number`, `Address`, `Status`) VALUES
('KH001', 'dpt2004', '*6C19D970BC7296B6E44', 'Đinh Phúc Thịnh', 561732554, 'Tân Bình,Tp.HCM', 1),
('KH002', 'ltt2004', '*6C19D970BC7296B6E44', 'Lương Thanh Tuấn', 561479536, 'Dầu Giây,Đồng Nai', 1),
('KH003', 'dtv2004', '*6C19D970BC7296B6E44', 'Đặng Thế Vinh', 785425414, 'Tân Phú,Tp.HCM', 1),
('KH004', 'tdk2004', '*6C19D970BC7296B6E44', 'Trần Đăng Kha', 452178541, 'Dầu Giây,Đồng Nai', 1),
('KH005', 'ntbv2004', '*6C19D970BC7296B6E44', 'Nguyễn Trần Bội Vỹ', 781022036, 'Long Khánh,Đồng Nai', 1);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `IdKM` varchar(10) NOT NULL,
  `IdGRP` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `DateStart` date NOT NULL,
  `DateEnd` date NOT NULL,
  `Percent` int(11) NOT NULL,
  `Info` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `IdNV` varchar(10) NOT NULL,
  `Account` varchar(50) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `PNumber` char(10) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `IdPos` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`IdNV`, `Account`, `Password`, `Name`, `PNumber`, `Address`, `Status`, `IdPos`) VALUES
('AD001', 'admin', 'e52Mpw7zHyp7zFVExAxkSg==', 'Trần Trung Việt', '0937024435', '123 Tân Phú, TpHCM', 1, 'ADMIN');

-- --------------------------------------------------------

--
-- Table structure for table `nhom`
--

CREATE TABLE `nhom` (
  `IdGRP` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Company` varchar(100) DEFAULT NULL,
  `Info` varchar(200) NOT NULL,
  `IMG` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `IdSP` varchar(10) NOT NULL,
  `IdGRP` varchar(10) NOT NULL,
  `IdTV` varchar(10) DEFAULT NULL,
  `Name` varchar(50) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Price` int(11) NOT NULL,
  `IMG` varchar(50) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Info` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thanhvien`
--

CREATE TABLE `thanhvien` (
  `IdTV` varchar(10) NOT NULL,
  `IdGRP` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `INFO` varchar(200) DEFAULT NULL,
  `IMG` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vanchuyen`
--

CREATE TABLE `vanchuyen` (
  `IDDVVC` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `ExpectDate` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE `version` (
  `IdVER` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Date` date NOT NULL,
  `IdGRP` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chamsockhachhang`
--
ALTER TABLE `chamsockhachhang`
  ADD PRIMARY KEY (`IdCSKH`),
  ADD KEY `CSKH_KH` (`IdKH`),
  ADD KEY `CSKH_NV` (`IdNV`);

--
-- Indexes for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD PRIMARY KEY (`IdCTHD`),
  ADD KEY `CTHD_HD` (`IdHD`),
  ADD KEY `CTHD_SP` (`IdSP`),
  ADD KEY `CTHD_CTKM` (`IdCTKM`);

--
-- Indexes for table `chitietkhuyenmai`
--
ALTER TABLE `chitietkhuyenmai`
  ADD PRIMARY KEY (`IdCTKM`),
  ADD KEY `CTKM_KM` (`IdKM`),
  ADD KEY `CTKM_SP` (`IdSP`),
  ADD KEY `CTKM_TV` (`IdTV`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`IdHD`),
  ADD KEY `HD_KH` (`IdKH`),
  ADD KEY `HD_NV` (`IdNV`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`IdKH`),
  ADD UNIQUE KEY `ACCOUNT` (`Account`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`IdKM`),
  ADD KEY `KM_GRP` (`IdGRP`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`IdNV`),
  ADD UNIQUE KEY `Account` (`Account`);

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
  ADD KEY `SP_GRP` (`IdGRP`),
  ADD KEY `SP_TV` (`IdTV`);

--
-- Indexes for table `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD PRIMARY KEY (`IdTV`),
  ADD KEY `TV_GRP` (`IdGRP`);

--
-- Indexes for table `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`IdVER`),
  ADD KEY `VER_GRP` (`IdGRP`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  MODIFY `IdCTHD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chitietkhuyenmai`
--
ALTER TABLE `chitietkhuyenmai`
  MODIFY `IdCTKM` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chamsockhachhang`
--
ALTER TABLE `chamsockhachhang`
  ADD CONSTRAINT `CSKH_KH` FOREIGN KEY (`IdKH`) REFERENCES `khachhang` (`IdKH`),
  ADD CONSTRAINT `CSKH_NV` FOREIGN KEY (`IdNV`) REFERENCES `nhanvien` (`IdNV`);

--
-- Constraints for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD CONSTRAINT `CTHD_CTKM` FOREIGN KEY (`IdCTKM`) REFERENCES `chitietkhuyenmai` (`IdCTKM`),
  ADD CONSTRAINT `CTHD_HD` FOREIGN KEY (`IdHD`) REFERENCES `hoadon` (`IdHD`),
  ADD CONSTRAINT `CTHD_SP` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`);

--
-- Constraints for table `chitietkhuyenmai`
--
ALTER TABLE `chitietkhuyenmai`
  ADD CONSTRAINT `CTKM_KM` FOREIGN KEY (`IdKM`) REFERENCES `khuyenmai` (`IdKM`),
  ADD CONSTRAINT `CTKM_SP` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`),
  ADD CONSTRAINT `CTKM_TV` FOREIGN KEY (`IdTV`) REFERENCES `thanhvien` (`IdTV`);

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `HD_KH` FOREIGN KEY (`IdKH`) REFERENCES `khachhang` (`IdKH`),
  ADD CONSTRAINT `HD_NV` FOREIGN KEY (`IdNV`) REFERENCES `nhanvien` (`IdNV`);

--
-- Constraints for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD CONSTRAINT `KM_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `SP_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`),
  ADD CONSTRAINT `SP_TV` FOREIGN KEY (`IdTV`) REFERENCES `thanhvien` (`IdTV`);

--
-- Constraints for table `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD CONSTRAINT `TV_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`);

--
-- Constraints for table `version`
--
ALTER TABLE `version`
  ADD CONSTRAINT `VER_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
