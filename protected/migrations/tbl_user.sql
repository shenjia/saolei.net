
-- User --

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL auto_increment,
  `chinese_name` char(4) NOT NULL,
  `english_name` char(32) NOT NULL,
  `sex` tinyint(1) NOT NULL default '1',
  `avatar` char(255) NOT NULL default '',
  `area` char(3) NOT NULL default '',
  `status` smallint(6) NOT NULL default 0,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  INDEX (`area`, `sex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user_info` (
  `id` bigint(20) NOT NULL auto_increment,
  `qq` varchar(20) NOT NULL default '',
  `nickname` varchar(32) NOT NULL default '',
  `birthday` bigint(20) NOT NULL default 0,
  `mouse` varchar(60) NOT NULL default '',
  `pad` varchar(60) NOT NULL default '',
  `self_intro` varchar(150),
  `interest` varchar(150),
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user_auth` (
  `id` bigint(20) NOT NULL auto_increment,
  `username` char(64) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL,
  `role` smallint(6) NOT NULL default 0,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user_scores` (
  `id` bigint(20) NOT NULL auto_increment,
  `beg_time` int(10) NULL,
  `beg_3bvs` int(10) NULL,
  `int_time` int(10) NULL,
  `int_3bvs` int(10) NULL,
  `exp_time` int(10) NULL,
  `exp_3bvs` int(10) NULL,
  `sum_time` int(10) NULL,
  `sum_3bvs` int(10) NULL,
  `beg_time_video` bigint(20) NULL,
  `beg_3bvs_video` bigint(20) NULL,
  `int_time_video` bigint(20) NULL,
  `int_3bvs_video` bigint(20) NULL,
  `exp_time_video` bigint(20) NULL,
  `exp_3bvs_video` bigint(20) NULL,
  `beg_time_date` bigint(20) NULL,
  `beg_3bvs_date` bigint(20) NULL,
  `int_time_date` bigint(20) NULL,
  `int_3bvs_date` bigint(20) NULL,
  `exp_time_date` bigint(20) NULL,
  `exp_3bvs_date` bigint(20) NULL,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  INDEX (`beg_time`),
  INDEX (`beg_3bvs`),
  INDEX (`int_time`),
  INDEX (`int_3bvs`),
  INDEX (`exp_time`),
  INDEX (`exp_3bvs`),
  INDEX (`sum_time`),
  INDEX (`sum_3bvs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user_scores_nf` (
  `id` bigint(20) NOT NULL auto_increment,
  `beg_time` int(10) NULL,
  `beg_3bvs` int(10) NULL,
  `int_time` int(10) NULL,
  `int_3bvs` int(10) NULL,
  `exp_time` int(10) NULL,
  `exp_3bvs` int(10) NULL,
  `sum_time` int(10) NULL,
  `sum_3bvs` int(10) NULL,
  `beg_time_video` bigint(20) NULL,
  `beg_3bvs_video` bigint(20) NULL,
  `int_time_video` bigint(20) NULL,
  `int_3bvs_video` bigint(20) NULL,
  `exp_time_video` bigint(20) NULL,
  `exp_3bvs_video` bigint(20) NULL,
  `beg_time_date` bigint(20) NULL,
  `beg_3bvs_date` bigint(20) NULL,
  `int_time_date` bigint(20) NULL,
  `int_3bvs_date` bigint(20) NULL,
  `exp_time_date` bigint(20) NULL,
  `exp_3bvs_date` bigint(20) NULL,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  INDEX (`beg_time`),
  INDEX (`beg_3bvs`),
  INDEX (`int_time`),
  INDEX (`int_3bvs`),
  INDEX (`exp_time`),
  INDEX (`exp_3bvs`),
  INDEX (`sum_time`),
  INDEX (`sum_3bvs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user_stat` (
  `id` bigint(20) NOT NULL auto_increment,
  `login_times` bigint(20) NOT NULL default 0,
  `login_time` bigint(20) NOT NULL default 0,
  `login_ip` char(40) NULL,
  `points` int(10) NOT NULL default 0,
  `clicks` int(10) NOT NULL default 0,
  `beg_videos` int(10) NOT NULL default 0,
  `int_videos` int(10) NOT NULL default 0,
  `exp_videos` int(10) NOT NULL default 0,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user_sig` (
  `id` bigint(20) NOT NULL auto_increment,
  `user` bigint(20) NOT NULL,
  `signature` varchar(50) COLLATE utf8_bin NOT NULL,
  `create_time` bigint(20) NOT NULL default 0,
  `update_time` bigint(20) NOT NULL default 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX (`signature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;