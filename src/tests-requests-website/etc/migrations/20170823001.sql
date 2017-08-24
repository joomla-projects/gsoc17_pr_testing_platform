ALTER TABLE `#__instances` ADD `mergeable` TINYINT(1) DEFAULT '0' NOT NULL COMMENT 'Flag a PR as mergeable or not if there are conflicts';
