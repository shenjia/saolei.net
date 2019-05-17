
-- Donate --

CREATE TABLE IF NOT EXISTS `donate` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL default 0,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Distribution --

CREATE TABLE IF NOT EXISTS `distribution` (
  `id` bigint(20) NOT NULL auto_increment,
  `size` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sum_time` varchar(255) NOT NULL,
  `sum_3bvs` varchar(255) NOT NULL,
  `beg_time` varchar(255) NOT NULL,
  `beg_3bvs` varchar(255) NOT NULL,
  `int_time` varchar(255) NOT NULL,
  `int_3bvs` varchar(255) NOT NULL,
  `exp_time` varchar(255) NOT NULL,
  `exp_3bvs` varchar(255) NOT NULL,
  `create_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- News --

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` smallint(6) NOT NULL,
  `user` bigint(20) NOT NULL,
  `user_score` int(10) NOT NULL,
  `reference` bigint(20) NOT NULL default 0,
  `details_data` varchar(200) NOT NULL,
  `create_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Comment --

CREATE TABLE IF NOT EXISTS `comment` (
  `id` bigint(20) NOT NULL auto_increment,
  `video` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `user_score` int(10) NOT NULL,
  `content` text,
  `status` smallint(6) NOT NULL default 0,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  INDEX (`video`, `create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;