CREATE TABLE IF NOT EXISTS clues_market_category (
  market_category_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  market_id mediumint(8) unsigned NOT NULL,
  main_category_id mediumint(8) unsigned NOT NULL,
  nrh_category_id mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (market_category_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS clues_markets (
  market_id mediumint(8) unsigned NOT NULL ,
  description text DEFAULT NULL,
  slug varchar(255) DEFAULT NULL,
  seo_name varchar(255) DEFAULT NULL,
  PRIMARY KEY (market_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;