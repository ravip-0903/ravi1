CREATE TABLE `clues_site_requests` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `referer` varchar(255) NOT NULL,
  `other` varchar(255) NOT NULL,
  `server_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
