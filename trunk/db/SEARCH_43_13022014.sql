CREATE TABLE IF NOT EXISTS `clues_track_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `page` varchar(255) NOT NULL,
  `page_value` varchar(255) NOT NULL,
  `server` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `useragent` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

