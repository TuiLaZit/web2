-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 06:59 AM
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
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `IdBN` int(11) NOT NULL,
  `IdSP` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Address` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `IdPos` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhaphang`
--

CREATE TABLE `nhaphang` (
  `IdNhapHang` int(11) NOT NULL,
  `IdSP` int(11) NOT NULL,
  `ImportPrice` int(11) NOT NULL,
  `ImportDate` date NOT NULL,
  `ImportQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `IdSP` int(11) NOT NULL,
  `IdGRP` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
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
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`IdBN`),
  ADD KEY `BN_SP` (`IdSP`);

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
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `IdBN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  MODIFY `IdCTHD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `IdHD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `IdKH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `IdNV` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhaphang`
--
ALTER TABLE `nhaphang`
  MODIFY `IdNhapHang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhom`
--
ALTER TABLE `nhom`
  MODIFY `IdGRP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `IdSP` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banner`
--
ALTER TABLE `banner`
  ADD CONSTRAINT `BN_SP` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`);

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
-- Constraints for table `nhaphang`
--
ALTER TABLE `nhaphang`
  ADD CONSTRAINT `NhapHang_SanPham` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`);

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `SP_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
