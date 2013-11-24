
-- Video --

CREATE TABLE IF NOT EXISTS `video` (
  `id` bigint(20) NOT NULL auto_increment,
  `level` char(3) NOT NULL,
  `user` bigint(20) NOT NULL,
  `hash` char(32) NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  `review_user` bigint(20) NULL,
  `review_time` bigint(20) NULL,
  `create_time` bigint(20) NOT NULL,
  `update_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`user`, `level`),
  INDEX (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_info` (
  `id` bigint(20) NOT NULL auto_increment,
  `filepath` varchar(100) NOT NULL,
  `signature` varchar(50) NOT NULL,
  `software` varchar(20) NOT NULL,
  `version` varchar(20) NOT NULL,
  `noflag` boolean NOT NULL,
  `board` varchar(500) NOT NULL,
  `board_3bv` int(10) NOT NULL,
  `real_time` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  `update_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_stat` (
  `id` bigint(20) NOT NULL auto_increment,
  `clicks` int(10) NOT NULL default '0',
  `clicker` char(40) NULL,
  `downloads` int(10) NOT NULL default '0',
  `downloader` char(40) NULL,
  `comments` int(10) NOT NULL default '0',
  `create_time` bigint(20) NOT NULL,
  `update_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_comment` (
  `id` bigint(20) NOT NULL auto_increment,
  `video` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `content` text,
  `status` smallint(6) NOT NULL default '0',
  `create_time` bigint(20) NOT NULL,
  `update_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`video`, `create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_scores_beg` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `score_time` int(10) NOT NULL,
  `score_3bvs` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`score_time`),
  INDEX (`score_3bvs`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_scores_beg_nf` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `score_time` int(10) NOT NULL,
  `score_3bvs` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`score_time`),
  INDEX (`score_3bvs`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_scores_int` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `score_time` int(10) NOT NULL,
  `score_3bvs` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`score_time`),
  INDEX (`score_3bvs`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_scores_int_nf` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `score_time` int(10) NOT NULL,
  `score_3bvs` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`score_time`),
  INDEX (`score_3bvs`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `video_scores_exp` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `score_time` int(10) NOT NULL,
  `score_3bvs` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`score_time`),
  INDEX (`score_3bvs`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `video_scores_exp_nf` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `score_time` int(10) NOT NULL,
  `score_3bvs` int(10) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`score_time`),
  INDEX (`score_3bvs`),
  INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
