-- --------------------------------------------------------
-- Host:                         192.168.0.115
-- Server version:               5.5.41-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table local_api.fileentries
CREATE TABLE IF NOT EXISTS `fileentries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table local_api.fileentries: ~5 rows (approximately)
/*!40000 ALTER TABLE `fileentries` DISABLE KEYS */;
INSERT INTO `fileentries` (`id`, `filename`, `mime`, `original_filename`, `created_at`, `updated_at`) VALUES
	(1, 'phpkrTpyz.jpg', 'image/jpeg', '544f6323576133a7378b49e1.jpg', '2015-05-05 10:26:57', '2015-05-05 10:26:57'),
	(2, 'phpjNnZPK.html', 'text/html', 'file.html', '2015-05-05 10:29:35', '2015-05-05 10:29:35'),
	(3, 'phpXesdGB.jpg', 'image/jpeg', 'fear-1-001-300x271.jpg', '2015-05-05 12:07:35', '2015-05-05 12:07:35'),
	(4, 'phpx8lkcv.jpg', 'image/jpeg', 'bungee_jump_face_cam_400x300.jpg', '2015-05-05 15:48:12', '2015-05-05 15:48:12'),
	(5, 'phpEh2V2K.jpg', 'image/jpeg', 'bungee_jump_face_cam_400x300.jpg', '2015-05-05 15:48:28', '2015-05-05 15:48:28');
/*!40000 ALTER TABLE `fileentries` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
