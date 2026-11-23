-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2026 at 02:23 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `sparepart_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Oli & Pelumas', 'oli-pelumas', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(2, 'Sistem Pengereman', 'sistem-pengereman', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(3, 'Komponen CVT', 'komponen-cvt', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(4, 'Kelistrikan & Aki', 'kelistrikan-aki', '2026-11-22 06:53:43', '2026-11-22 06:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_01_000001_create_categories_table', 1),
(5, '2026_01_01_000002_create_motorcycles_table', 1),
(6, '2026_01_01_000003_create_spareparts_table', 1),
(7, '2026_01_01_000004_create_carts_table', 1),
(8, '2026_01_01_000005_create_orders_table', 1),
(9, '2026_01_01_000006_create_order_items_table', 1),
(10, '2026_11_14_000001_add_customer_note_to_orders_table', 1),
(11, '2026_11_21_000002_add_customer_profile_fields_to_users_table', 1),
(12, '2026_11_21_000003_add_booking_time_to_orders_table', 1),
(13, '2026_11_21_000004_create_sparepart_reviews_table', 1),
(14, '2026_11_21_000005_update_sparepart_reviews_for_orders', 1);

-- --------------------------------------------------------

--
-- Table structure for table `motorcycles`
--

CREATE TABLE `motorcycles` (
  `id` bigint UNSIGNED NOT NULL,
  `brand` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `production_year` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `motorcycles`
--

INSERT INTO `motorcycles` (`id`, `brand`, `model_name`, `production_year`, `created_at`, `updated_at`) VALUES
(1, 'Honda', 'Honda Vario 150', '2018-2022', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(2, 'Honda', 'Honda Beat FI / Street', '2020-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(3, 'Honda', 'Honda PCX 160', '2021-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(4, 'Yamaha', 'Yamaha NMAX 155', '2020-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(5, 'Yamaha', 'Yamaha Aerox 155', '2021-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(6, 'Honda', 'Honda Scoopy Prestige', '2021-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(7, 'Yamaha', 'Yamaha Fazzio Hybrid 125', '2022-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43'),
(8, 'Suzuki', 'Suzuki Nex II 115', '2019-2024', '2026-11-22 06:53:43', '2026-11-22 06:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('beli_saja','pasang_bengkel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beli_saja',
  `booking_date` date DEFAULT NULL,
  `booking_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_fee` int NOT NULL DEFAULT '0',
  `customer_note` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','diproses','selesai','batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `invoice_number`, `order_type`, `booking_date`, `booking_time`, `service_fee`, `customer_note`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'INV-20261101-DONE', 'pasang_bengkel', '2026-11-17', '09:00 - 11:00', 100000, 'Servis rutin CVT dan ganti oli.', 'selesai', '2026-11-08 06:53:45', '2026-11-08 06:53:45'),
(2, 4, 'INV-20261102-DONE', 'pasang_bengkel', '2026-11-10', '13:00 - 15:00', 75000, 'Sekalian bersihkan area rumah roller Aerox.', 'selesai', '2026-11-09 06:53:45', '2026-11-09 06:53:45'),
(3, 5, 'INV-20261103-DONE', 'pasang_bengkel', '2026-11-12', '09:00 - 11:00', 50000, 'Ganti oli rutin Scoopy.', 'selesai', '2026-11-11 06:53:45', '2026-11-11 06:53:45'),
(4, 3, 'INV-20261105-DONE', 'beli_saja', NULL, NULL, 0, 'Packing rapi ya min.', 'selesai', '2026-11-13 06:53:45', '2026-11-13 06:53:45'),
(5, 6, 'INV-20261106-DONE', 'beli_saja', NULL, NULL, 0, 'Kirim via ekspedisi reguler.', 'selesai', '2026-11-14 06:53:45', '2026-11-14 06:53:45'),
(6, 7, 'INV-20261108-DONE', 'pasang_bengkel', '2026-11-18', '15:30 - 17:30', 50000, 'Pasang jam sore ya.', 'selesai', '2026-11-16 06:53:45', '2026-11-16 06:53:45'),
(7, 2, 'INV-20261109-ABCD', 'pasang_bengkel', '2026-11-24', '09:00 - 11:00', 50000, 'Tolong sekalian cek tekanan ban depan.', 'pending', '2026-11-17 06:53:45', '2026-11-17 06:53:45'),
(8, 3, 'INV-20261110-BTL1', 'pasang_bengkel', '2026-11-25', '15:30 - 17:30', 25000, 'Batal karena ada jadwal mendadak.', 'batal', '2026-11-18 06:53:45', '2026-11-18 06:53:45'),
(9, 4, 'INV-20261112-BTL2', 'beli_saja', NULL, NULL, 0, 'Salah pilih tipe kampas rem.', 'batal', '2026-11-19 06:53:45', '2026-11-19 06:53:45'),
(10, 3, 'INV-20261115-PRCS', 'pasang_bengkel', '2026-11-23', '13:00 - 15:00', 50000, 'Motor NMAX warna hitam, mohon cek kelistrikan sekalian.', 'diproses', '2026-11-20 06:53:45', '2026-11-20 06:53:45'),
(11, 6, 'INV-20261116-PRCS', 'pasang_bengkel', '2026-11-23', '13:00 - 15:00', 50000, 'Datang tepat jam 13.00 WIB.', 'diproses', '2026-11-20 18:53:45', '2026-11-20 18:53:45'),
(12, 5, 'INV-20261117-PRCS', 'beli_saja', NULL, NULL, 0, 'Tolong sertakan stiker garansi.', 'diproses', '2026-11-21 10:53:45', '2026-11-21 10:53:45'),
(13, 7, 'INV-20261119-PEND', 'pasang_bengkel', '2026-11-23', '09:00 - 11:00', 25000, 'Ganti oli Fazzio sesi pagi.', 'pending', '2026-11-21 22:53:45', '2026-11-21 22:53:45'),
(14, 4, 'INV-20261120-PEND', 'beli_saja', NULL, NULL, 0, 'Bubble wrap tebal untuk busi.', 'pending', '2026-11-22 04:53:45', '2026-11-22 04:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `sparepart_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `unit_price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `sparepart_id`, `qty`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 1, 55000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(2, 1, 11, 1, 45000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(3, 1, 13, 1, 85000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(4, 1, 14, 1, 145000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(5, 2, 10, 1, 72000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(6, 2, 2, 1, 115000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(7, 2, 6, 1, 165000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(8, 3, 9, 1, 55000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(9, 3, 1, 1, 64000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(10, 4, 10, 1, 72000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(11, 4, 12, 1, 38000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(12, 4, 15, 1, 285000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(13, 4, 16, 1, 95000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(14, 5, 11, 1, 45000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(15, 5, 3, 1, 89000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(16, 5, 8, 1, 92000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(17, 6, 2, 1, 115000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(18, 6, 8, 1, 92000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(19, 7, 9, 1, 55000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(20, 7, 11, 1, 45000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(21, 8, 13, 1, 85000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(22, 9, 3, 1, 89000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(23, 10, 10, 1, 72000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(24, 10, 15, 1, 285000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(25, 11, 2, 1, 115000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(26, 11, 3, 1, 89000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(27, 12, 1, 1, 64000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(28, 12, 12, 1, 38000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(29, 13, 2, 1, 115000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(30, 14, 6, 1, 165000, '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(31, 14, 16, 1, 95000, '2026-11-22 06:53:45', '2026-11-22 06:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spareparts`
--

CREATE TABLE `spareparts` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `part_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spareparts`
--

INSERT INTO `spareparts` (`id`, `category_id`, `part_code`, `name`, `brand`, `price`, `stock`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'OLI-SHL-AX7M', 'Shell Advance AX7 Scooter 10W-40 0.8L', 'Shell', 64000, 22, 'spareparts/oli-ahm-mpx2.png', '2026-11-22 06:33:45', '2026-11-22 06:33:45'),
(2, 1, 'OLI-MTL-5W40', 'Motul Scooter Power LE 5W-40 1L', 'Motul', 115000, 14, 'spareparts/oli-yamalube-matic.png', '2026-11-22 06:34:45', '2026-11-22 06:34:45'),
(3, 2, 'REM-ELG-SC01', 'Kampas Rem Cakram Ceramic Elig', 'Elig', 89000, 12, 'spareparts/kampas-rem-nissin.png', '2026-11-22 06:35:45', '2026-11-22 06:35:45'),
(4, 2, 'REM-YGP-54P', 'Kampas Rem Belakang Original YGP', 'Yamaha Genuine Parts', 42000, 2, 'spareparts/kampas-rem-belakang-ahm.png', '2026-11-22 06:36:45', '2026-11-22 06:36:45'),
(5, 3, 'CVT-BRT-10G', 'Super Roller BRT 10gr Set', 'BRT', 98000, 0, 'spareparts/roller-cvt-tdr.png', '2026-11-22 06:37:45', '2026-11-22 06:37:45'),
(6, 3, 'CVT-YGP-B65', 'V-Belt Kit Original Yamaha B65', 'Yamaha Genuine Parts', 165000, 9, 'spareparts/vbelt-kit-ahm.png', '2026-11-22 06:38:45', '2026-11-22 06:38:45'),
(7, 4, 'AKI-MTZ-MTZ6S', 'Aki Motobatt Gel MTZ6S', 'Motobatt', 335000, 3, 'spareparts/aki-gs-astra.png', '2026-11-22 06:39:45', '2026-11-22 06:39:45'),
(8, 4, 'LIS-DNS-IU22', 'Busi Denso Iridium Power IU22', 'Denso', 92000, 16, 'spareparts/busi-ngk-iridium.png', '2026-11-22 06:40:45', '2026-11-22 06:40:45'),
(9, 1, 'OLI-AHM-MPX2', 'Oli AHM MPX2 0.8L', 'AHM', 55000, 25, 'spareparts/oli-ahm-mpx2.png', '2026-11-22 06:45:45', '2026-11-22 06:45:45'),
(10, 1, 'OLI-YML-SM1L', 'Yamalube Super Matic 1L', 'Yamalube', 72000, 20, 'spareparts/oli-yamalube-matic.png', '2026-11-22 06:46:45', '2026-11-22 06:46:45'),
(11, 2, 'REM-NSN-DPN', 'Kampas Rem Depan Cakram Nissin', 'Nissin', 45000, 15, 'spareparts/kampas-rem-nissin.png', '2026-11-22 06:47:45', '2026-11-22 06:47:45'),
(12, 2, 'REM-AHM-BLK', 'Kampas Rem Belakang Tromol AHM', 'AHM', 38000, 18, 'spareparts/kampas-rem-belakang-ahm.png', '2026-11-22 06:48:45', '2026-11-22 06:48:45'),
(13, 3, 'CVT-TDR-12G', 'Roller CVT TDR 12gr', 'TDR Racing', 85000, 3, 'spareparts/roller-cvt-tdr.png', '2026-11-22 06:49:45', '2026-11-22 06:49:45'),
(14, 3, 'CVT-AHM-VBELT', 'V-Belt Kit AHM', 'AHM', 145000, 10, 'spareparts/vbelt-kit-ahm.png', '2026-11-22 06:50:45', '2026-11-22 06:50:45'),
(15, 4, 'AKI-GSA-GTZ6V', 'Aki GS Astra GTZ6V', 'GS Astra', 285000, 8, 'spareparts/aki-gs-astra.png', '2026-11-22 06:51:45', '2026-11-22 06:51:45'),
(16, 4, 'LIS-NGK-CR7H', 'Busi NGK Iridium CR7HIX', 'NGK', 95000, 4, 'spareparts/busi-ngk-iridium.png', '2026-11-22 06:52:45', '2026-11-22 06:52:45');

-- --------------------------------------------------------

--
-- Table structure for table `sparepart_motorcycle`
--

CREATE TABLE `sparepart_motorcycle` (
  `id` bigint UNSIGNED NOT NULL,
  `sparepart_id` bigint UNSIGNED NOT NULL,
  `motorcycle_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sparepart_motorcycle`
--

INSERT INTO `sparepart_motorcycle` (`id`, `sparepart_id`, `motorcycle_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 6),
(4, 1, 8),
(5, 2, 3),
(6, 2, 4),
(7, 2, 5),
(8, 2, 7),
(9, 3, 3),
(10, 3, 4),
(11, 3, 5),
(12, 4, 7),
(13, 4, 5),
(14, 5, 2),
(15, 5, 6),
(16, 5, 7),
(17, 5, 8),
(18, 6, 4),
(19, 6, 5),
(20, 7, 1),
(21, 7, 3),
(22, 7, 6),
(23, 7, 4),
(24, 8, 1),
(25, 8, 3),
(26, 8, 4),
(27, 8, 5),
(28, 8, 8),
(29, 9, 1),
(30, 9, 2),
(31, 9, 3),
(32, 9, 6),
(33, 10, 4),
(34, 10, 5),
(35, 10, 7),
(36, 11, 1),
(37, 11, 2),
(38, 11, 3),
(39, 11, 6),
(40, 12, 1),
(41, 12, 2),
(42, 12, 6),
(43, 13, 1),
(44, 13, 3),
(45, 13, 4),
(46, 13, 5),
(47, 14, 1),
(48, 14, 2),
(49, 14, 6),
(50, 15, 1),
(51, 15, 3),
(52, 15, 4),
(53, 15, 5),
(54, 15, 6),
(55, 16, 1),
(56, 16, 2),
(57, 16, 3),
(58, 16, 4),
(59, 16, 5),
(60, 16, 6),
(61, 16, 7),
(62, 16, 8);

-- --------------------------------------------------------

--
-- Table structure for table `sparepart_reviews`
--

CREATE TABLE `sparepart_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `sparepart_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sparepart_reviews`
--

INSERT INTO `sparepart_reviews` (`id`, `user_id`, `sparepart_id`, `order_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 1, 5, 'Oli MPX2 asli segel AHM, tarikan mesin langsung halus.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(2, 2, 11, 1, 5, 'Kampas rem Nissin pakem dan tidak bunyi berdecit.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(3, 2, 13, 1, 4, 'Akselerasi bawah Vario jadi lebih responsif setelah pasang roller 12gr.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(4, 2, 14, 1, 5, 'V-Belt kit original lengkap dengan roller, mekanik pasang rapi tanpa antre.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(5, 3, 10, 4, 5, 'Yamalube Super Matic cocok banget untuk NMAX harian, pengiriman cepat.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(6, 3, 12, 4, 4, 'Kualitas kampas rem tromol bagus, pemasangan presisi.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(7, 3, 15, 4, 5, 'Aki GS Astra langsung tokcer saat starter, garansi toko jelas.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(8, 3, 16, 4, 5, 'Busi NGK Iridium bikin pembakaran lebih stabil dan irit BBM.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(9, 4, 10, 2, 5, 'Oli Yamalube selalu aman dipakai turing jauh dengan Aerox.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(10, 4, 2, 2, 5, 'Motul 5W-40 bikin suhu mesin jauh lebih adem di kemacetan.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(11, 4, 6, 2, 4, 'V-Belt B65 original Yamaha, getaran tarikan awal hilang.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(12, 5, 9, 3, 4, 'Oli MPX2 harga bersahabat dan pemasangan di bengkel cepat.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(13, 5, 1, 3, 5, 'Shell AX7 cocok di Scoopy, mesin terasa enteng.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(14, 6, 11, 5, 5, 'Kampas cakram Nissin PNP di PCX tanpa ubahan.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(15, 6, 3, 5, 5, 'Kampas ceramic Elig minim debu di pelek dan pakem saat basah.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(16, 6, 8, 5, 4, 'Busi Denso Iridium langsung nyala sekali tekan tombol starter.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(17, 7, 2, 6, 4, 'Motul terasa halus di Fazzio, botol tersegel rapi.', '2026-11-22 06:53:45', '2026-11-22 06:53:45'),
(18, 7, 8, 6, 5, 'Pemasangan busi cepat dan rapi oleh mekanik bengkel.', '2026-11-22 06:53:45', '2026-11-22 06:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motorcycle_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `role`, `remember_token`, `created_at`, `updated_at`, `address`, `motorcycle_id`) VALUES
(1, 'Admin MotoPart', 'admin@spareshop.web.id', NULL, '$2y$12$QFMdCNSIAQQT.FQiXzlYTOSQ/HckCn036pzKCrFPhPO7xI3vN.xO6', '081234567890', 'admin', NULL, '2026-11-22 06:53:43', '2026-11-22 06:53:43', NULL, NULL),
(2, 'Budi Santoso', 'budi@spareshop.web.id', NULL, '$2y$12$UY.phXomUE5siI2SgIRfOOoFs6S3qBm4IS9EypY28BWuIeofshVsi', '089876543210', 'customer', NULL, '2026-11-22 06:53:44', '2026-11-22 06:53:44', 'Jl. Kebon Jeruk Raya No. 14, Jakarta Barat', 1),
(3, 'Siti Rahmawati', 'siti@spareshop.web.id', NULL, '$2y$12$PbDFOP9dmQEf7fJM4L59suzssJ1cfDTsWGob.CPj/phF4UJHGEfZe', '081355667788', 'customer', NULL, '2026-11-22 06:53:44', '2026-11-22 06:53:44', 'Jl. Kemang Timur No. 22, Jakarta Selatan', 4),
(4, 'Andi Pratama', 'andi@spareshop.web.id', NULL, '$2y$12$ynmiiZOoVrw7wy4owSkl0OMnWBtmneE3yFeJfPYTkag4/Sni2r0Om', '081288990011', 'customer', NULL, '2026-11-22 06:53:44', '2026-11-22 06:53:44', 'Jl. Margonda Raya No. 88, Depok', 5),
(5, 'Rina Kusuma', 'rina@spareshop.web.id', NULL, '$2y$12$8bhhB.0T3cjh3bGJIHJH9uXiz8MUBr/cHtBDJPPaZnVUyV02rzyUO', '085711223344', 'customer', NULL, '2026-11-22 06:53:44', '2026-11-22 06:53:44', 'Jl. Buah Batu No. 45, Bandung', 6),
(6, 'Dimas Saputra', 'dimas@spareshop.web.id', NULL, '$2y$12$ruTE2Dg.aevN8eksxNviG.63B2XO9pHmuMzIUVr0moM4Smzjk5fka', '082144556677', 'customer', NULL, '2026-11-22 06:53:44', '2026-11-22 06:53:44', 'Jl. Kaliurang Km 7, Sleman', 3),
(7, 'Maya Putri', 'maya@spareshop.web.id', NULL, '$2y$12$FA.2yI5yp5x8ICoVkNMJru4K7rWWlI9Z2kRANHB.NKG4yekmoa1iy', '087899001122', 'customer', NULL, '2026-11-22 06:53:45', '2026-11-22 06:53:45', 'Jl. Darmo Permai III No. 10, Surabaya', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_sparepart_id_foreign` (`sparepart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `motorcycles`
--
ALTER TABLE `motorcycles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_sparepart_id_foreign` (`sparepart_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `spareparts_part_code_unique` (`part_code`),
  ADD KEY `spareparts_category_id_foreign` (`category_id`);

--
-- Indexes for table `sparepart_motorcycle`
--
ALTER TABLE `sparepart_motorcycle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sparepart_motorcycle_sparepart_id_foreign` (`sparepart_id`),
  ADD KEY `sparepart_motorcycle_motorcycle_id_foreign` (`motorcycle_id`);

--
-- Indexes for table `sparepart_reviews`
--
ALTER TABLE `sparepart_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sparepart_reviews_order_id_sparepart_id_unique` (`order_id`,`sparepart_id`),
  ADD KEY `sparepart_reviews_sparepart_id_foreign` (`sparepart_id`),
  ADD KEY `sparepart_reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_motorcycle_id_foreign` (`motorcycle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `motorcycles`
--
ALTER TABLE `motorcycles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `spareparts`
--
ALTER TABLE `spareparts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sparepart_motorcycle`
--
ALTER TABLE `sparepart_motorcycle`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sparepart_reviews`
--
ALTER TABLE `sparepart_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD CONSTRAINT `spareparts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sparepart_motorcycle`
--
ALTER TABLE `sparepart_motorcycle`
  ADD CONSTRAINT `sparepart_motorcycle_motorcycle_id_foreign` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sparepart_motorcycle_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sparepart_reviews`
--
ALTER TABLE `sparepart_reviews`
  ADD CONSTRAINT `sparepart_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sparepart_reviews_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sparepart_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_motorcycle_id_foreign` FOREIGN KEY (`motorcycle_id`) REFERENCES `motorcycles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
