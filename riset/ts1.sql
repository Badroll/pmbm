-- --------------------------------------------------------
-- Host:                         76.13.16.241
-- Server version:               8.0.45-0ubuntu0.22.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             12.17.0.7280
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

USE `sigk1116_sigap`;

-- Dumping structure for table sigap.__job_faileds
CREATE TABLE IF NOT EXISTS `__job_faileds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sigap.__job_faileds: ~0 rows (approximately)

-- Dumping structure for table sigap.__jobs
CREATE TABLE IF NOT EXISTS `__jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sigap.__jobs: ~0 rows (approximately)

-- Dumping structure for table sigap._reference
CREATE TABLE IF NOT EXISTS `_reference` (
  `R_CATEGORY` varchar(100) NOT NULL,
  `R_ID` varchar(100) NOT NULL,
  `R_INFO` varchar(100) NOT NULL,
  `R_ORDER` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`R_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sigap._reference: 10 rows
INSERT INTO `_reference` (`R_CATEGORY`, `R_ID`, `R_INFO`, `R_ORDER`) VALUES
	('JENIS_AGENDA', 'JENIS_AGENDA_KUNJUNGAN_KELUAR', 'Kunjungan Keluar', 5),
	('JENIS_AGENDA', 'JENIS_AGENDA_KUNJUNGAN_TAMU', 'Kunjungan Tamu', 4),
	('JENIS_AGENDA', 'JENIS_AGENDA_RAPAT_INTERNAL', 'Rapat Internal', 2),
	('JENIS_AGENDA', 'JENIS_AGENDA_UNDANGAN', 'Undangan', 3),
	('JENIS_AGENDA', 'JENIS_AGENDA_ZOOM', 'Zoom Meeting', 1),
	('ROLE', 'ROLE_ADMIN', 'Admin', 1),
	('ROLE', 'ROLE_AJUDAN', 'Ajudan', 4),
	('ROLE', 'ROLE_HUMAS', 'Humas', 5),
	('ROLE', 'ROLE_KAKANWIL', 'Kakanwil', 6),
	('ROLE', 'ROLE_SEKRETARIS', 'Sekretaris', 2);

-- Dumping structure for table sigap._setting
CREATE TABLE IF NOT EXISTS `_setting` (
  `S_ID` varchar(50) NOT NULL,
  `S_VALUE` varchar(100) NOT NULL,
  `S_INFO` varchar(100) NOT NULL,
  PRIMARY KEY (`S_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sigap._setting: 0 rows

-- Dumping structure for table sigap._user
CREATE TABLE IF NOT EXISTS `_user` (
  `U_ID` int NOT NULL AUTO_INCREMENT,
  `U_USERNAME` varchar(50) NOT NULL,
  `U_PHONE` varchar(100) NOT NULL,
  `U_EMAIL` varchar(100) DEFAULT NULL,
  `U_ROLE` varchar(50) NOT NULL,
  `U_PASSWORD` varchar(100) NOT NULL,
  `U_ACCOUNT_STATUS` varchar(50) NOT NULL,
  `U_LOGIN_TOKEN` varchar(100) NOT NULL,
  `U_LOGIN_TOKEN_EXPIRED` datetime DEFAULT NULL,
  `U_LOGIN_LAST` datetime DEFAULT NULL,
  PRIMARY KEY (`U_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=821 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sigap._user: 6 rows
INSERT INTO `_user` (`U_ID`, `U_USERNAME`, `U_PHONE`, `U_EMAIL`, `U_ROLE`, `U_PASSWORD`, `U_ACCOUNT_STATUS`, `U_LOGIN_TOKEN`, `U_LOGIN_TOKEN_EXPIRED`, `U_LOGIN_LAST`) VALUES
	(815, '081234567890', '081234567890', NULL, 'ROLE_ADMIN', '$2y$10$kecQaM3J5bdAkYjq1dqhP.wLViy/FpqnO3Ma5sdmEC9lar4s.2zTW', 'ACCOUNT_STATUS_ACTIVE', '5aad25e4-7173-4ba1-bb28-f9f0ca4a0e27', '2026-06-25 12:23:24', '2026-05-26 12:23:24'),
	(816, '085254453316', '085254453316', '', 'ROLE_HUMAS', '$2y$10$wwPYSuSx.zOiqLdpJiFB7efvxvJ699A1U2zH9639s168GIkYYTa0a', 'ACCOUNT_STATUS_ACTIVE', '600c34ac-53d1-4457-b0c0-927ccb1962c1', '2026-06-24 02:32:52', '2026-05-25 02:32:52'),
	(817, '082247738508', '082247738508', '', 'ROLE_SEKRETARIS', '$2y$10$JnO58t2A/8LRhRcftc4Tm.Fjekji.2cavnAnsZtsQmMwayCPR8J9m', 'ACCOUNT_STATUS_ACTIVE', 'df4708b3-8d87-41a5-b82c-0c5efe43dd57', '2026-06-24 17:11:40', '2026-05-25 17:11:40'),
	(818, '08138101975', '08138101975', '', 'ROLE_KAKANWIL', '$2y$10$1bx23K2M6.MRZk26IEAIkOkT69LlSXaiYNurXGufxzuDL5cByw.UK', 'ACCOUNT_STATUS_ACTIVE', '', NULL, NULL),
	(819, '082399110111', '082399110111', '', 'ROLE_AJUDAN', '$2y$10$D9gnv33nUTuedD6vd9.18efOiWGhsktfNUO5XyK9ikKFfL7ffM5w.', 'ACCOUNT_STATUS_ACTIVE', '15fbd29a-b432-4b8e-b79f-c3993940fb08', '2026-06-24 14:36:18', '2026-05-25 14:36:18'),
	(820, '082248135645', '082248135645', '', 'ROLE_SEKRETARIS', '$2y$10$jLHe03u1U5w2bv8o.Zwgpul13.bWXsWjsO./yrISTpFimSDKO68TG', 'ACCOUNT_STATUS_ACTIVE', 'ba4db821-9f16-43c2-a8e8-c90d5bdf40ce', '2026-06-24 14:42:52', '2026-05-25 14:42:52');

-- Dumping structure for table sigap.agenda
CREATE TABLE IF NOT EXISTS `agenda` (
  `AGD_ID` int NOT NULL AUTO_INCREMENT,
  `AGD_JENIS` varchar(50) NOT NULL,
  `AGD_NAMA` varchar(255) NOT NULL,
  `AGD_WAKTU` datetime NOT NULL,
  `AGD_TEMPAT` varchar(255) DEFAULT NULL,
  `AGD_IS_HUMAS` enum('Y','N') DEFAULT NULL,
  `AGD_DRESSCODE` varchar(255) DEFAULT NULL,
  `AGD_LINK_ZOOM` varchar(255) DEFAULT NULL,
  `AGD_UNDANGAN` varchar(255) DEFAULT NULL,
  `AGD_HADIR` enum('Y','N') DEFAULT NULL,
  `U_ID` int NOT NULL COMMENT 'user yg create (mengajukan)',
  PRIMARY KEY (`AGD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table sigap.agenda: ~6 rows (approximately)
INSERT INTO `agenda` (`AGD_ID`, `AGD_JENIS`, `AGD_NAMA`, `AGD_WAKTU`, `AGD_TEMPAT`, `AGD_IS_HUMAS`, `AGD_DRESSCODE`, `AGD_LINK_ZOOM`, `AGD_UNDANGAN`, `AGD_HADIR`, `U_ID`) VALUES
	(4, 'JENIS_AGENDA_RAPAT_INTERNAL', 'Apel Pagi Pegawai', '2026-05-25 08:00:00', 'Di Aula Kanwil Ditjenpas Maluku', NULL, NULL, NULL, NULL, 'N', 815),
	(5, 'JENIS_AGENDA_KUNJUNGAN_TAMU', 'pengecekkan', '2026-05-25 12:00:00', NULL, 'Y', NULL, NULL, NULL, NULL, 815),
	(6, 'JENIS_AGENDA_KUNJUNGAN_KELUAR', 'KI', '2026-05-26 12:00:00', NULL, 'Y', 'batik', NULL, NULL, 'N', 815),
	(9, 'JENIS_AGENDA_KUNJUNGAN_KELUAR', 'kunjungan ke LPP', '2026-05-29 12:00:00', NULL, 'Y', 'Tactical', NULL, NULL, 'Y', 817),
	(10, 'JENIS_AGENDA_RAPAT_INTERNAL', 'Rapat Koordinasi Satopspatnal', '2026-05-27 06:00:00', 'Ruang Rapat Kakanwil', NULL, NULL, NULL, NULL, NULL, 820),
	(11, 'JENIS_AGENDA_ZOOM', 'Undangan Rapat Analisis dan Evaluasi Bidang Pemasyarakatan', '2026-05-26 12:00:00', NULL, 'Y', NULL, 'https://tinyurl.com/ZoomAnevMei2026', 'undangan/gy8roCm5UxDKhkSylzFjwBhCGv5ncEEqyMeqSzLq.pdf', 'Y', 817);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
