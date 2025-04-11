-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 04:34 PM
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
  `IdSP` int(11) NOT NULL
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
  `Date` date NOT NULL,
  `ExpectDate` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `PTTT` tinyint(1) NOT NULL DEFAULT 1
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
  `PNumber` char(10) NOT NULL,
  `AddressLine` varchar(200) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1,
  `Ward` varchar(45) NOT NULL,
  `Provinces` varchar(45) NOT NULL,
  `District` varchar(45) NOT NULL
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
  `IMG` varchar(100) DEFAULT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhom`
--

INSERT INTO `nhom` (`IdGRP`, `Name`, `Company`, `Info`, `IMG`, `Status`) VALUES
(5, 'NJZ', 'không có', 'NewJeans, còn được gọi là NJZ, là một nhóm nhạc nữ Hàn Quốc. Nhóm bao gồm 5 thành viên: Minji, Hanni, Danielle, Haerin và Hyein.', '250207-njz-group-logo-v0-llgg14o29mhe1.webp', 1),
(6, 'Big Bang', 'YG', 'Big Bang (cách điệu là BIGBANG), là một nhóm nhạc nam Hàn Quốc được thành lập bởi YG Entertainment, chính thức ra mắt năm 2006. Nhóm gồm 5 thành viên G-Dragon, T.O.P, Taeyang, Daesung và Seungri.', '67ef4e42ecd3f.png', 1),
(7, 'IVE', 'Starship Ent.', 'IVE là một nhóm nhạc nữ Hàn Quốc được thành lập và quản lý bởi Starship Entertainment. Nhóm bao gồm 6 thành viên: Gaeul, Yujin, Rei, Wonyoung, Liz và Leeseo. ', '67ef4e6352df6.jpg', 1),
(8, 'OIIA cat', 'không có', 'He is a cat that says \"OIIA\" while spinning.', '67f0bc0c8fd23.png', 1),
(9, 'BTS', 'Hybe', 'BTS, còn được gọi là Bangtan Boys, là một nhóm nhạc nam Hàn Quốc do Big Hit Entertainment thành lập vào năm 2010 và bắt đầu quản lý vào năm 2013. Nhóm bao gồm 7 thành viên: Jin, Suga, J-Hope, RM, Jimi', '67f0bc59e7ce7.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `IdSP` int(11) NOT NULL,
  `IdGRP` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Price` int(11) NOT NULL,
  `IMG` varchar(50) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Info` varchar(200) NOT NULL,
  `ReleaseDate` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`IdSP`, `IdGRP`, `Name`, `Type`, `Price`, `IMG`, `Quantity`, `Info`, `ReleaseDate`, `Status`) VALUES
(5, 5, 'Binky Bong', 'Light Stick', 1000000, 'binky.jpg', 100, 'Binky Bong là lightstick chính thức của NewJeans . Nó được tiết lộ lần đầu vào ngày 29 tháng 10 năm 2022 (100 ngày sau khi ra mắt) và được bán vào ngày 30 tháng 3 năm 2023.', '2024-03-30', 1),
(11, 5, 'NewJeans ‘OMG’ Message Card Hanni ver', 'Album', 360000, 'hanniomg.jpeg', 100, 'OMG is the first single album by the South Korean girl group NewJeans. It was released on January 2, 2023. A pre-release single \"Ditto\" was released on December 19, 2022.', '2022-12-19', 1),
(12, 5, 'NewJeans ‘OMG’ Message Card Minji ver', 'Album', 360000, 'minjiomg.jpeg', 100, 'OMG is the first single album by the South Korean girl group NewJeans. It was released on January 2, 2023. A pre-release single \"Ditto\" was released on December 19, 2022.', '2023-01-02', 1),
(13, 5, 'NewJeans ‘OMG’ Message Card Danielle ver', 'Album', 360000, 'danomg.jpeg', 100, 'OMG is the first single album by the South Korean girl group NewJeans. It was released on January 2, 2023. A pre-release single \"Ditto\" was released on December 19, 2022.', '2023-01-02', 1),
(14, 5, 'NewJeans ‘OMG’ Message Card Haerin ver', 'Album', 360000, 'haeomg.jpeg', 100, 'OMG is the first single album by the South Korean girl group NewJeans. It was released on January 2, 2023. A pre-release single \"Ditto\" was released on December 19, 2022.', '2023-01-02', 1),
(15, 5, 'NewJeans ‘OMG’ Message Card Hyein ver', 'Album', 360000, 'hyeinomg.jpg', 100, 'OMG is the first single album by the South Korean girl group NewJeans. It was released on January 2, 2023. A pre-release single \"Ditto\" was released on December 19, 2022.', '2023-01-02', 1),
(16, 5, 'ALBUM NEWJEANS - HOW SWEET Hanni ver', 'Album', 600000, 'hannihs.webp', 100, 'How Sweet is the second single album by NewJeans. It was released on May 24,2024 at 1PM KST.The music video for the title track“How Sweet” was released on the same day,at 4PM KST.', '2024-05-24', 1),
(17, 5, 'ALBUM NEWJEANS - HOW SWEET Minji ver', 'Album', 500000, 'minjihs.jpg', 100, 'How Sweet is the second single album by NewJeans. It was released on May 24,2024 at 1PM KST.The music video for the title track“How Sweet” was released on the same day,at 4PM KST.', '2024-05-24', 1),
(18, 5, 'ALBUM NEWJEANS - HOW SWEET Danielle ver', 'Album', 450000, 'danhs.jpg', 100, 'How Sweet is the second single album by NewJeans. It was released on May 24,2024 at 1PM KST.The music video for the title track“How Sweet” was released on the same day,at 4PM KST.', '2024-05-24', 1),
(19, 5, 'ALBUM NEWJEANS - HOW SWEET Haerin ver', 'Album', 500000, 'haehs.jpg', 100, 'How Sweet is the second single album by NewJeans. It was released on May 24,2024 at 1PM KST.The music video for the title track“How Sweet” was released on the same day,at 4PM KST.', '2024-05-24', 1),
(20, 5, 'ALBUM NEWJEANS - HOW SWEET Hyein ver', 'Album', 400000, 'hyeinhs.jpg', 100, 'How Sweet is the second single album by NewJeans. It was released on May 24,2024 at 1PM KST.The music video for the title track“How Sweet” was released on the same day,at 4PM KST.', '2024-05-24', 1);

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
  ADD KEY `CTKM_SP` (`IdSP`);

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
  ADD KEY `SP_GRP` (`IdGRP`);

--
-- Indexes for table `vanchuyen`
--
ALTER TABLE `vanchuyen`
  ADD PRIMARY KEY (`IDDVVC`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `IdBN` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `IdGRP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `IdSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `vanchuyen`
--
ALTER TABLE `vanchuyen`
  MODIFY `IDDVVC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banner`
--
ALTER TABLE `banner`
  ADD CONSTRAINT `BN_SP` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`);

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
  ADD CONSTRAINT `CTKM_SP` FOREIGN KEY (`IdSP`) REFERENCES `sanpham` (`IdSP`);

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
  ADD CONSTRAINT `SP_GRP` FOREIGN KEY (`IdGRP`) REFERENCES `nhom` (`IdGRP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
