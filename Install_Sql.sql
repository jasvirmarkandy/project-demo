
CREATE DATABASE IF NOT EXISTS `shorten_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `shorten_db`;

CREATE TABLE IF NOT EXISTS `url_shorten` (
 `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `url` tinytext NOT NULL,
 `short_code` varchar(50) NOT NULL,
 `added_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;