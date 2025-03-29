-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 02:12 AM
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
  `IdCSKH` int(11) NOT NULL,
  `IdKH` int(11) NOT NULL,
  `IdNV` int(11) NOT NULL,
  `INFO` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
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
  `SumPrice` int(11) DEFAULT NULL,
  `IdCTKM` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chitietkhuyenmai`
--

CREATE TABLE `chitietkhuyenmai` (
  `IdCTKM` int(11) NOT NULL,
  `IdKM` int(11) NOT NULL,
  `IdSP` int(11) NOT NULL,
  `IdTV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `IdHD` int(11) NOT NULL,
  `IdKH` int(11) NOT NULL,
  `IdNV` int(11) NOT NULL,
  `Total` int(11) DEFAULT NULL,
  `IDDVVC` int(11) NOT NULL,
  `Date` date NOT NULL DEFAULT current_timestamp(),
  `ExpectDate` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `IdKH` int(11) NOT NULL,
  `Account` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Number` int(10) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `IdKM` int(11) NOT NULL,
  `IdGRP` int(11) NOT NULL,
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
  `IdNV` int(11) NOT NULL,
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
(1, 'admin', 'e52Mpw7zHyp7zFVExAxkSg==', 'Trần Trung Việt', '0937024435', '123 Tân Phú, TpHCM', 1, 'ADMIN'),
(7, 'ahihi', 'KKk/WHKo/vo1GEc23stJvg==', 'Nguyễn Dương Minh Quan', '0969699699', 'San Francislong, Bình Thạnh', 1, 'Nhân Viên');

-- --------------------------------------------------------

--
-- Table structure for table `nhom`
--

CREATE TABLE `nhom` (
  `IdGRP` int(11) NOT NULL,
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
  `IdSP` int(11) NOT NULL,
  `IdGRP` int(11) NOT NULL,
  `IdTV` int(11) DEFAULT NULL,
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
  `IdTV` int(11) NOT NULL,
  `IdGRP` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `INFO` varchar(200) DEFAULT NULL,
  `IMG` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vanchuyen`
--

CREATE TABLE `vanchuyen` (
  `IDDVVC` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vanchuyen`
--

INSERT INTO `vanchuyen` (`IDDVVC`, `Name`, `Status`) VALUES
(1, 'Giao Hàng Tiết Kiệm', 1);

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE `version` (
  `IdVER` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Date` date NOT NULL,
  `IdGRP` int(11) NOT NULL
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
  ADD KEY `CTHD_CTKM` (`IdCTKM`),
  ADD KEY `CTHD_HD` (`IdHD`),
  ADD KEY `CTHD_SP` (`IdSP`);

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
  ADD KEY `HD_NV` (`IdNV`),
  ADD KEY `HD_DVVC` (`IDDVVC`);

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
-- Indexes for table `vanchuyen`
--
ALTER TABLE `vanchuyen`
  ADD PRIMARY KEY (`IDDVVC`);

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
-- AUTO_INCREMENT for table `chamsockhachhang`
--
ALTER TABLE `chamsockhachhang`
  MODIFY `IdCSKH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  MODIFY `IdCTHD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chitietkhuyenmai`
--
ALTER TABLE `chitietkhuyenmai`
  MODIFY `IdCTKM` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `IdHD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `IdKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `IdKM` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `IdNV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `thanhvien`
--
ALTER TABLE `thanhvien`
  MODIFY `IdTV` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vanchuyen`
--
ALTER TABLE `vanchuyen`
  MODIFY `IDDVVC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `version`
--
ALTER TABLE `version`
  MODIFY `IdVER` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `HD_DVVC` FOREIGN KEY (`IDDVVC`) REFERENCES `vanchuyen` (`IDDVVC`),
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
