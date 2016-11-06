CREATE TABLE `au_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB;

CREATE TABLE `au_boundry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grouping` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `au_boundry_user_id` (`create_by`),
  CONSTRAINT `au_boundry_user_id` FOREIGN KEY (`create_by`) REFERENCES `au_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `au_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `create_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `au_criteria_user_id_idx` (`create_by`),
  CONSTRAINT `au_criteria_user_id` FOREIGN KEY (`create_by`) REFERENCES `au_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `au_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `create_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `au_year_create_by_idx` (`create_by`),
  CONSTRAINT `au_year_create_by` FOREIGN KEY (`create_by`) REFERENCES `au_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `au_boundrylimit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boundry_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `lower_limit` int(11) NOT NULL,
  `upper_limit` int(11) NOT NULL,
  `create_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `au_boundrylimit_boundry_id_idx` (`boundry_id`),
  KEY `au_boundrylimit_year_id_idx` (`year_id`),
  KEY `au_boundrylimit_create_by_idx` (`create_by`),
  CONSTRAINT `au_boundrylimit_boundry_id` FOREIGN KEY (`boundry_id`) REFERENCES `au_boundry` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `au_boundrylimit_create_by` FOREIGN KEY (`create_by`) REFERENCES `au_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `au_boundrylimit_year_id` FOREIGN KEY (`year_id`) REFERENCES `au_year` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `au_sessionlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `last_active` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `au_sessionLog_user_id` FOREIGN KEY (`user_id`) REFERENCES `au_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `serverlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_uri` varchar(250) NOT NULL,
  `query_string` varchar(250) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `script_start_time` datetime NOT NULL,
  `script_end_time` datetime NOT NULL,
  `script_load_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
