-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 17, 2025 at 04:29 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PTUD_Final`
--
CREATE DATABASE IF NOT EXISTS `PTUD_Final` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `PTUD_Final`;

-- --------------------------------------------------------

--
-- Table structure for table `anh_san_pham`
--

DROP TABLE IF EXISTS `anh_san_pham`;
CREATE TABLE `anh_san_pham` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `san_pham_id` bigint(20) UNSIGNED NOT NULL,
  `url_anh` varchar(500) NOT NULL,
  `thu_tu_hien_thi` int(11) NOT NULL DEFAULT 0,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anh_san_pham`
--

INSERT INTO `anh_san_pham` (`id`, `san_pham_id`, `url_anh`, `thu_tu_hien_thi`, `tao_luc`) VALUES
(1, 27, 'https://placehold.co/600x800?text=ao+khoac+bomber+1', 1, '2025-12-16 10:13:20'),
(2, 28, 'https://placehold.co/600x800?text=ao+khoac+denim+jacket+1', 1, '2025-12-16 10:13:20'),
(3, 19, 'https://placehold.co/600x800?text=ao+thun+basic+cotton+1', 1, '2025-12-16 10:13:20'),
(4, 20, 'https://placehold.co/600x800?text=ao+thun+oversize+street+1', 1, '2025-12-16 10:13:20'),
(5, 21, 'https://placehold.co/600x800?text=ao+thun+polo+minimal+1', 1, '2025-12-16 10:13:20'),
(6, 29, 'https://placehold.co/600x800?text=non+luoi+trai+classic+1', 1, '2025-12-16 10:13:20'),
(7, 24, 'https://placehold.co/600x800?text=quan+jeans+slim+fit+1', 1, '2025-12-16 10:13:20'),
(8, 25, 'https://placehold.co/600x800?text=quan+kaki+regular+1', 1, '2025-12-16 10:13:20'),
(9, 26, 'https://placehold.co/600x800?text=quan+short+linen+1', 1, '2025-12-16 10:13:20'),
(10, 23, 'https://placehold.co/600x800?text=so+mi+ke+caro+1', 1, '2025-12-16 10:13:20'),
(11, 22, 'https://placehold.co/600x800?text=so+mi+oxford+trang+1', 1, '2025-12-16 10:13:20'),
(12, 30, 'https://placehold.co/600x800?text=tui+tote+canvas+1', 1, '2025-12-16 10:13:20'),
(16, 27, 'https://placehold.co/600x800?text=ao+khoac+bomber+2', 2, '2025-12-16 10:13:20'),
(17, 28, 'https://placehold.co/600x800?text=ao+khoac+denim+jacket+2', 2, '2025-12-16 10:13:20'),
(18, 19, 'https://placehold.co/600x800?text=ao+thun+basic+cotton+2', 2, '2025-12-16 10:13:20'),
(19, 20, 'https://placehold.co/600x800?text=ao+thun+oversize+street+2', 2, '2025-12-16 10:13:20'),
(20, 21, 'https://placehold.co/600x800?text=ao+thun+polo+minimal+2', 2, '2025-12-16 10:13:20'),
(21, 29, 'https://placehold.co/600x800?text=non+luoi+trai+classic+2', 2, '2025-12-16 10:13:20'),
(22, 24, 'https://placehold.co/600x800?text=quan+jeans+slim+fit+2', 2, '2025-12-16 10:13:20'),
(23, 25, 'https://placehold.co/600x800?text=quan+kaki+regular+2', 2, '2025-12-16 10:13:20'),
(24, 26, 'https://placehold.co/600x800?text=quan+short+linen+2', 2, '2025-12-16 10:13:20'),
(25, 23, 'https://placehold.co/600x800?text=so+mi+ke+caro+2', 2, '2025-12-16 10:13:20'),
(26, 22, 'https://placehold.co/600x800?text=so+mi+oxford+trang+2', 2, '2025-12-16 10:13:20'),
(27, 30, 'https://placehold.co/600x800?text=tui+tote+canvas+2', 2, '2025-12-16 10:13:20'),
(31, 27, 'https://placehold.co/600x800?text=ao+khoac+bomber+3', 3, '2025-12-16 10:13:20'),
(32, 28, 'https://placehold.co/600x800?text=ao+khoac+denim+jacket+3', 3, '2025-12-16 10:13:20'),
(33, 19, 'https://placehold.co/600x800?text=ao+thun+basic+cotton+3', 3, '2025-12-16 10:13:20'),
(34, 20, 'https://placehold.co/600x800?text=ao+thun+oversize+street+3', 3, '2025-12-16 10:13:20'),
(35, 21, 'https://placehold.co/600x800?text=ao+thun+polo+minimal+3', 3, '2025-12-16 10:13:20'),
(36, 29, 'https://placehold.co/600x800?text=non+luoi+trai+classic+3', 3, '2025-12-16 10:13:20'),
(37, 24, 'https://placehold.co/600x800?text=quan+jeans+slim+fit+3', 3, '2025-12-16 10:13:20'),
(38, 25, 'https://placehold.co/600x800?text=quan+kaki+regular+3', 3, '2025-12-16 10:13:20'),
(39, 26, 'https://placehold.co/600x800?text=quan+short+linen+3', 3, '2025-12-16 10:13:20'),
(40, 23, 'https://placehold.co/600x800?text=so+mi+ke+caro+3', 3, '2025-12-16 10:13:20'),
(41, 22, 'https://placehold.co/600x800?text=so+mi+oxford+trang+3', 3, '2025-12-16 10:13:20'),
(42, 30, 'https://placehold.co/600x800?text=tui+tote+canvas+3', 3, '2025-12-16 10:13:20'),
(1001, 31, 'https://placehold.co/600x800?text=Hoodie+Basic+1', 1, '2025-12-16 17:00:58'),
(1002, 31, 'https://placehold.co/600x800?text=Hoodie+Basic+2', 2, '2025-12-16 17:00:58'),
(1003, 31, 'https://placehold.co/600x800?text=Hoodie+Basic+3', 3, '2025-12-16 17:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE `chi_tiet_don_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `don_hang_id` bigint(20) UNSIGNED NOT NULL,
  `san_pham_id` bigint(20) UNSIGNED NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `don_gia` decimal(12,2) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `thanh_tien` decimal(12,2) NOT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_gio_hang`
--

DROP TABLE IF EXISTS `chi_tiet_gio_hang`;
CREATE TABLE `chi_tiet_gio_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gio_hang_id` bigint(20) UNSIGNED NOT NULL,
  `san_pham_id` bigint(20) UNSIGNED NOT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1,
  `don_gia` decimal(12,2) NOT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc_san_pham`
--

DROP TABLE IF EXISTS `danh_muc_san_pham`;
CREATE TABLE `danh_muc_san_pham` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `duong_dan` varchar(255) NOT NULL,
  `mo_ta` varchar(500) DEFAULT NULL,
  `trang_thai` enum('HOAT_DONG','NGUNG_HOAT_DONG') NOT NULL DEFAULT 'HOAT_DONG',
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danh_muc_san_pham`
--

INSERT INTO `danh_muc_san_pham` (`id`, `ten_danh_muc`, `duong_dan`, `mo_ta`, `trang_thai`, `tao_luc`, `cap_nhat_luc`) VALUES
(1, 'Áo thun', 'ao-thun', 'Các loại áo thun nam nữ', 'HOAT_DONG', '2025-12-15 15:25:08', '2025-12-15 15:25:08'),
(2, 'Hoodie', 'hoodie', 'Áo hoodie', 'HOAT_DONG', '2025-12-15 15:25:08', '2025-12-16 10:16:38'),
(3, 'Quần', 'quan', 'Quần jeans, quần tây, quần short', 'HOAT_DONG', '2025-12-15 15:25:08', '2025-12-15 15:25:08'),
(4, 'Áo khoác', 'ao-khoac', 'Áo khoác thời trang', 'HOAT_DONG', '2025-12-15 15:25:08', '2025-12-15 15:25:08'),
(5, 'Áo sơ mi', 'ao-so-mi', 'Áo sơ mi công sở và casual', 'HOAT_DONG', '2025-12-16 10:35:07', '2025-12-16 10:44:05'),
(6, 'Phụ kiện', 'phu-kien', 'Phụ kiện thời trang', 'HOAT_DONG', '2025-12-15 15:25:08', '2025-12-16 10:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

DROP TABLE IF EXISTS `don_hang`;
CREATE TABLE `don_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_don_hang` varchar(50) NOT NULL,
  `nguoi_dung_id` bigint(20) UNSIGNED NOT NULL,
  `trang_thai` enum('CHO_XU_LY','DANG_XU_LY','HOAN_TAT','HUY') NOT NULL DEFAULT 'CHO_XU_LY',
  `phuong_thuc_thanh_toan` enum('COD') NOT NULL DEFAULT 'COD',
  `trang_thai_thanh_toan` enum('CHUA_THANH_TOAN','DA_THANH_TOAN') NOT NULL DEFAULT 'CHUA_THANH_TOAN',
  `tam_tinh` decimal(12,2) NOT NULL,
  `phi_van_chuyen` decimal(12,2) NOT NULL DEFAULT 0.00,
  `giam_gia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tong_tien` decimal(12,2) NOT NULL,
  `nguoi_nhan` varchar(255) NOT NULL,
  `sdt_nguoi_nhan` varchar(50) NOT NULL,
  `dia_chi_giao_hang` varchar(500) NOT NULL,
  `ghi_chu` varchar(500) DEFAULT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

DROP TABLE IF EXISTS `gio_hang`;
CREATE TABLE `gio_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nguoi_dung_id` bigint(20) UNSIGNED NOT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gio_hang`
--

INSERT INTO `gio_hang` (`id`, `nguoi_dung_id`, `tao_luc`, `cap_nhat_luc`) VALUES
(1, 1, '2025-12-15 15:50:16', '2025-12-15 15:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `kich_co`
--

DROP TABLE IF EXISTS `kich_co`;
CREATE TABLE `kich_co` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_kich_co` varchar(50) NOT NULL,
  `duong_dan` varchar(255) NOT NULL,
  `thu_tu` int(11) NOT NULL DEFAULT 0,
  `trang_thai` enum('HOAT_DONG','NGUNG') NOT NULL DEFAULT 'HOAT_DONG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kich_co`
--

INSERT INTO `kich_co` (`id`, `ten_kich_co`, `duong_dan`, `thu_tu`, `trang_thai`) VALUES
(1, 'S', 's', 1, 'HOAT_DONG'),
(2, 'M', 'm', 2, 'HOAT_DONG'),
(3, 'L', 'l', 3, 'HOAT_DONG'),
(4, 'XL', 'xl', 4, 'HOAT_DONG');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_trang_thai_don_hang`
--

DROP TABLE IF EXISTS `lich_su_trang_thai_don_hang`;
CREATE TABLE `lich_su_trang_thai_don_hang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `don_hang_id` bigint(20) UNSIGNED NOT NULL,
  `tu_trang_thai` enum('CHO_XU_LY','DANG_XU_LY','HOAN_TAT','HUY') DEFAULT NULL,
  `den_trang_thai` enum('CHO_XU_LY','DANG_XU_LY','HOAN_TAT','HUY') NOT NULL,
  `nguoi_thay_doi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ghi_chu` varchar(500) DEFAULT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mau_sac`
--

DROP TABLE IF EXISTS `mau_sac`;
CREATE TABLE `mau_sac` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_mau` varchar(100) NOT NULL,
  `ma_mau` varchar(20) DEFAULT NULL,
  `duong_dan` varchar(255) NOT NULL,
  `trang_thai` enum('HOAT_DONG','NGUNG') NOT NULL DEFAULT 'HOAT_DONG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mau_sac`
--

INSERT INTO `mau_sac` (`id`, `ten_mau`, `ma_mau`, `duong_dan`, `trang_thai`) VALUES
(1, 'Đen', '#000000', 'den', 'HOAT_DONG'),
(2, 'Trắng', '#ffffff', 'trang', 'HOAT_DONG'),
(3, 'Xám', '#d3d3d3', 'xam', 'HOAT_DONG'),
(4, 'Be', '#f5e6d3', 'be', 'HOAT_DONG'),
(5, 'Xanh pastel', '#6b8ca9', 'xanh-pastel', 'HOAT_DONG'),
(6, 'Hồng pastel', '#f5c6cb', 'hong-pastel', 'HOAT_DONG');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE `nguoi_dung` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `mat_khau_bam` varchar(255) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `so_dien_thoai` varchar(50) DEFAULT NULL,
  `vai_tro` enum('NGUOI_DUNG','QUAN_TRI') NOT NULL DEFAULT 'NGUOI_DUNG',
  `trang_thai` enum('HOAT_DONG','KHOA','NGUNG_HOAT_DONG') NOT NULL DEFAULT 'HOAT_DONG',
  `lan_dang_nhap_gan_nhat` datetime DEFAULT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `email`, `mat_khau_bam`, `ho_ten`, `ngay_sinh`, `so_dien_thoai`, `vai_tro`, `trang_thai`, `lan_dang_nhap_gan_nhat`, `tao_luc`, `cap_nhat_luc`) VALUES
(1, 'pbui@gmail.com', '$2y$10$v4aGJkF5HvvydRRKVuzF4e9WZbL8fbrIeIXu581v6btdcoZZPe.JK', NULL, NULL, NULL, 'NGUOI_DUNG', 'HOAT_DONG', '2025-12-15 15:47:13', '2025-12-15 15:10:44', '2025-12-15 15:47:13'),
(2, 'pbui01@gmail.com', '$2y$10$Afa70cZ9MwsV2SidzsaLE.P0SDO3XZ0jojl8kclLlhRCcxrWDLTRy', 'Bùi Phát', NULL, '0909119189', 'NGUOI_DUNG', 'HOAT_DONG', NULL, '2025-12-15 16:38:01', '2025-12-15 16:38:01'),
(3, 'pbui02@gmail.com', '$2y$10$iH6Zaq8lvbwNE1egPMqBEu3zXm.5jNa/tYz3Mqq6sqD23/zc8689G', 'Bùi Phát', '2005-10-12', '0909119189', 'NGUOI_DUNG', 'HOAT_DONG', '2025-12-16 22:13:15', '2025-12-16 08:56:05', '2025-12-16 22:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `phien_dang_nhap`
--

DROP TABLE IF EXISTS `phien_dang_nhap`;
CREATE TABLE `phien_dang_nhap` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nguoi_dung_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `het_han_luc` datetime NOT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `thu_hoi_luc` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

DROP TABLE IF EXISTS `san_pham`;
CREATE TABLE `san_pham` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `danh_muc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `duong_dan` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia_ban` decimal(12,2) NOT NULL,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `trang_thai` enum('DANG_BAN','NGUNG_BAN','DA_GO') NOT NULL DEFAULT 'DANG_BAN',
  `anh_dai_dien_url` varchar(500) DEFAULT NULL,
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`id`, `danh_muc_id`, `ten_san_pham`, `duong_dan`, `mo_ta`, `gia_ban`, `so_luong_ton`, `trang_thai`, `anh_dai_dien_url`, `tao_luc`, `cap_nhat_luc`) VALUES
(19, 1, 'Áo thun Basic Cotton', 'ao-thun-basic-cotton', 'Áo thun cotton mềm, form basic dễ phối.', 199000.00, 120, 'DANG_BAN', 'https://placehold.co/600x800?text=Ao+thun+Basic', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(20, 1, 'Áo thun Oversize Street', 'ao-thun-oversize-street', 'Áo thun oversize phong cách streetwear.', 259000.00, 80, 'DANG_BAN', 'https://placehold.co/600x800?text=Ao+thun+Oversize', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(21, 1, 'Áo thun Polo Minimal', 'ao-thun-polo-minimal', 'Polo tối giản, lịch sự, dễ mặc.', 299000.00, 60, 'DANG_BAN', 'https://placehold.co/600x800?text=Polo+Minimal', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(22, 5, 'Sơ mi Oxford Trắng', 'so-mi-oxford-trang', 'Sơ mi Oxford, thoáng, đứng form.', 349000.00, 50, 'DANG_BAN', 'https://placehold.co/600x800?text=So+mi+Oxford', '2025-12-16 10:13:20', '2025-12-16 10:36:00'),
(23, 5, 'Sơ mi Kẻ Caro', 'so-mi-ke-caro', 'Sơ mi caro casual, dễ phối quần jeans.', 319000.00, 40, 'DANG_BAN', 'https://placehold.co/600x800?text=So+mi+Caro', '2025-12-16 10:13:20', '2025-12-16 10:36:00'),
(24, 3, 'Quần Jeans Slim Fit', 'quan-jeans-slim-fit', 'Jeans slim fit co giãn nhẹ, tôn dáng.', 499000.00, 70, 'DANG_BAN', 'https://placehold.co/600x800?text=Quan+Jeans+Slim', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(25, 3, 'Quần Kaki Regular', 'quan-kaki-regular', 'Kaki regular, lịch sự cho đi làm.', 429000.00, 55, 'DANG_BAN', 'https://placehold.co/600x800?text=Quan+Kaki+Regular', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(26, 3, 'Quần Short Linen', 'quan-short-linen', 'Short linen mát, hợp đi chơi/du lịch.', 249000.00, 90, 'DANG_BAN', 'https://placehold.co/600x800?text=Quan+Short+Linen', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(27, 4, 'Áo khoác Bomber', 'ao-khoac-bomber', 'Bomber basic, giữ ấm vừa phải.', 699000.00, 30, 'DANG_BAN', 'https://placehold.co/600x800?text=Ao+khoac+Bomber', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(28, 4, 'Áo khoác Denim Jacket', 'ao-khoac-denim-jacket', 'Denim jacket cá tính, phối nhiều style.', 749000.00, 25, 'DANG_BAN', 'https://placehold.co/600x800?text=Denim+Jacket', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(29, 6, 'Nón lưỡi trai Classic', 'non-luoi-trai-classic', 'Nón classic, form chuẩn, dễ phối.', 159000.00, 150, 'DANG_BAN', 'https://placehold.co/600x800?text=Non+Classic', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(30, 6, 'Túi Tote Canvas', 'tui-tote-canvas', 'Tote canvas dày dặn, đựng laptop 13-14\".', 189000.00, 100, 'DANG_BAN', 'https://placehold.co/600x800?text=Tui+Tote+Canvas', '2025-12-16 10:13:20', '2025-12-16 10:30:49'),
(31, 2, 'Hoodie Oversize Basic', 'hoodie-oversize-basic', 'Hoodie nỉ ấm, form rộng dễ phối.', 399000.00, 60, 'DANG_BAN', 'https://placehold.co/600x800?text=Hoodie+Basic', '2025-12-16 10:36:11', '2025-12-16 10:36:11'),
(32, 2, 'Hoodie Zip Street', 'hoodie-zip-street', 'Hoodie khoá kéo phong cách streetwear.', 459000.00, 40, 'DANG_BAN', 'https://placehold.co/600x800?text=Hoodie+Zip', '2025-12-16 10:36:11', '2025-12-16 10:36:11');

-- --------------------------------------------------------

--
-- Table structure for table `sku_san_pham`
--

DROP TABLE IF EXISTS `sku_san_pham`;
CREATE TABLE `sku_san_pham` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `san_pham_id` bigint(20) UNSIGNED NOT NULL,
  `ma_sku` varchar(80) NOT NULL,
  `kich_co_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mau_sac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gia_ban` decimal(12,2) NOT NULL,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `trang_thai` enum('DANG_BAN','NGUNG_BAN','DA_GO') NOT NULL DEFAULT 'DANG_BAN',
  `tao_luc` datetime NOT NULL DEFAULT current_timestamp(),
  `cap_nhat_luc` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sku_san_pham`
--

INSERT INTO `sku_san_pham` (`id`, `san_pham_id`, `ma_sku`, `kich_co_id`, `mau_sac_id`, `gia_ban`, `so_luong_ton`, `trang_thai`, `tao_luc`, `cap_nhat_luc`) VALUES
(1, 19, 'SP19-S-DEN', 1, 1, 199000.00, 20, 'DANG_BAN', '2025-12-16 12:38:08', '2025-12-16 12:38:08'),
(2, 19, 'SP19-M-DEN', 2, 1, 199000.00, 25, 'DANG_BAN', '2025-12-16 12:38:08', '2025-12-16 12:38:08'),
(3, 19, 'SP19-L-DEN', 3, 1, 199000.00, 15, 'DANG_BAN', '2025-12-16 12:38:08', '2025-12-16 12:38:08'),
(4, 19, 'SP19-M-TRANG', 2, 2, 199000.00, 10, 'DANG_BAN', '2025-12-16 12:38:08', '2025-12-16 12:38:08'),
(2001, 31, 'SP31-S-DEN', 1, 1, 399000.00, 10, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58'),
(2002, 31, 'SP31-S-TRANG', 1, 2, 399000.00, 5, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58'),
(2003, 31, 'SP31-M-DEN', 2, 1, 399000.00, 8, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58'),
(2004, 31, 'SP31-M-TRANG', 2, 2, 399000.00, 6, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58'),
(2005, 31, 'SP31-M-BE', 2, 4, 419000.00, 7, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58'),
(2006, 31, 'SP31-L-DEN', 3, 1, 399000.00, 4, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58'),
(2007, 31, 'SP31-L-BE', 3, 4, 419000.00, 3, 'DANG_BAN', '2025-12-16 17:00:58', '2025-12-16 17:00:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anh_san_pham`
--
ALTER TABLE `anh_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_anh_san_pham_san_pham` (`san_pham_id`);

--
-- Indexes for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ctdh_don_hang` (`don_hang_id`),
  ADD KEY `idx_ctdh_san_pham` (`san_pham_id`);

--
-- Indexes for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ctgh_gio_san_pham` (`gio_hang_id`,`san_pham_id`),
  ADD KEY `idx_ctgh_gio_hang` (`gio_hang_id`),
  ADD KEY `idx_ctgh_san_pham` (`san_pham_id`);

--
-- Indexes for table `danh_muc_san_pham`
--
ALTER TABLE `danh_muc_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_danh_muc_duong_dan` (`duong_dan`),
  ADD KEY `idx_danh_muc_trang_thai` (`trang_thai`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_don_hang_ma` (`ma_don_hang`),
  ADD KEY `idx_don_hang_nguoi_dung_tao_luc` (`nguoi_dung_id`,`tao_luc`),
  ADD KEY `idx_don_hang_trang_thai` (`trang_thai`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_gio_hang_nguoi_dung` (`nguoi_dung_id`);

--
-- Indexes for table `kich_co`
--
ALTER TABLE `kich_co`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_size_duong_dan` (`duong_dan`);

--
-- Indexes for table `lich_su_trang_thai_don_hang`
--
ALTER TABLE `lich_su_trang_thai_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ls_don_hang` (`don_hang_id`),
  ADD KEY `idx_ls_nguoi_thay_doi` (`nguoi_thay_doi_id`);

--
-- Indexes for table `mau_sac`
--
ALTER TABLE `mau_sac`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_mau_duong_dan` (`duong_dan`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nguoi_dung_email` (`email`),
  ADD KEY `idx_nguoi_dung_vai_tro` (`vai_tro`),
  ADD KEY `idx_nguoi_dung_trang_thai` (`trang_thai`);

--
-- Indexes for table `phien_dang_nhap`
--
ALTER TABLE `phien_dang_nhap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_phien_token` (`token`),
  ADD KEY `idx_phien_nguoi_dung` (`nguoi_dung_id`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_san_pham_duong_dan` (`duong_dan`),
  ADD KEY `idx_san_pham_trang_thai` (`trang_thai`),
  ADD KEY `idx_san_pham_tao_luc` (`tao_luc`),
  ADD KEY `idx_san_pham_ten` (`ten_san_pham`),
  ADD KEY `idx_san_pham_danh_muc` (`danh_muc_id`);

--
-- Indexes for table `sku_san_pham`
--
ALTER TABLE `sku_san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sku_code` (`ma_sku`),
  ADD UNIQUE KEY `uq_sp_size_color` (`san_pham_id`,`kich_co_id`,`mau_sac_id`),
  ADD KEY `idx_sku_sp` (`san_pham_id`),
  ADD KEY `idx_sku_size` (`kich_co_id`),
  ADD KEY `idx_sku_color` (`mau_sac_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anh_san_pham`
--
ALTER TABLE `anh_san_pham`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `danh_muc_san_pham`
--
ALTER TABLE `danh_muc_san_pham`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kich_co`
--
ALTER TABLE `kich_co`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lich_su_trang_thai_don_hang`
--
ALTER TABLE `lich_su_trang_thai_don_hang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mau_sac`
--
ALTER TABLE `mau_sac`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phien_dang_nhap`
--
ALTER TABLE `phien_dang_nhap`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sku_san_pham`
--
ALTER TABLE `sku_san_pham`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2008;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anh_san_pham`
--
ALTER TABLE `anh_san_pham`
  ADD CONSTRAINT `fk_anh_san_pham_san_pham` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_don_hang` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctdh_san_pham` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `fk_ctgh_gio_hang` FOREIGN KEY (`gio_hang_id`) REFERENCES `gio_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctgh_san_pham` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_don_hang_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `fk_gio_hang_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lich_su_trang_thai_don_hang`
--
ALTER TABLE `lich_su_trang_thai_don_hang`
  ADD CONSTRAINT `fk_ls_don_hang` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ls_nguoi_thay_doi` FOREIGN KEY (`nguoi_thay_doi_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `phien_dang_nhap`
--
ALTER TABLE `phien_dang_nhap`
  ADD CONSTRAINT `fk_phien_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_san_pham_danh_muc` FOREIGN KEY (`danh_muc_id`) REFERENCES `danh_muc_san_pham` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sku_san_pham`
--
ALTER TABLE `sku_san_pham`
  ADD CONSTRAINT `fk_sku_color` FOREIGN KEY (`mau_sac_id`) REFERENCES `mau_sac` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sku_size` FOREIGN KEY (`kich_co_id`) REFERENCES `kich_co` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sku_sp` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
