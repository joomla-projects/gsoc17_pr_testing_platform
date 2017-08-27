-- MySQL dump 10.13  Distrib 5.6.30, for debian-linux-gnu (x86_64)
--
-- ------------------------------------------------------
-- Server version	5.6.36-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `#__accessgroups`
--

DROP TABLE IF EXISTS `#__accessgroups`;

CREATE TABLE `#__accessgroups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_view` int(11) NOT NULL,
  `can_create` int(11) NOT NULL,
  `can_manage` int(11) NOT NULL,
  `can_edit` int(11) NOT NULL,
  `can_editown` int(11) NOT NULL,
  `system` int(11) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `#__accessgroups_fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `#__tracker_projects` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `#__accessgroups`
--

INSERT INTO `#__accessgroups` (`group_id`, `project_id`, `title`, `can_view`, `can_create`, `can_manage`, `can_edit`, `can_editown`, `system`) VALUES
(1, 1, 'Public', 1, 0, 0, 0, 0, 1),
(2, 1, 'User', 1, 1, 0, 0, 1, 1),
(3, 1, 'JSST', 1, 1, 0, 1, 0, 0),
(4, 1, 'JSST Managers', 1, 1, 1, 1, 0, 0),
(5, 1, 'JBS', 1, 1, 0, 1, 1, 0),
(6, 1, 'Maintainers', 1, 1, 1, 1, 1, 0);


--
-- Table structure for table `#__activities`
--

DROP TABLE IF EXISTS `#__activities`;

CREATE TABLE `#__activities` (
  `activities_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `gh_comment_id` int(11) unsigned DEFAULT NULL COMMENT 'The GitHub comment id',
  `issue_number` int(11) unsigned NOT NULL COMMENT 'THE issue number (ID)',
  `project_id` int(11) NOT NULL COMMENT 'The Project id',
  `user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'The user name',
  `event` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The event type',
  `text` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'The event text',
  `text_raw` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'The raw event text',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`activities_id`),
  KEY `issue_number` (`issue_number`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `#__activities_fk_issue_number` FOREIGN KEY (`issue_number`) REFERENCES `#__issues` (`issue_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `#__activities_fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `#__tracker_projects` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `#__activity_types`
--

DROP TABLE IF EXISTS `#__activity_types`;

CREATE TABLE `#__activity_types` (
 `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
 `event` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The event type, referenced by the #__activities.event column',
 `activity_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `activity_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `activity_points` tinyint(4) DEFAULT NULL COMMENT 'Weighting for each type of activity',
 PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `#__activity_types`
--

INSERT INTO `#__activity_types` (`type_id`, `event`, `activity_group`, `activity_description`, `activity_points`)
VALUES
  (1, 'open', 'Tracker', 'Create a new item on the tracker.', 3),
  (2, 'close', 'Tracker', 'Close an issue on the tracker.', 1),
  (3, 'comment', 'Tracker', 'Add a comment to an issue.', 1),
  (4, 'reopen', 'Tracker', 'Reopens an issue.', 1),
  (5, 'assign', 'Tracker', 'Assign an issue to a user', 1),
  (6, 'merge', 'Tracker', 'Merge a Pull Request', 2),
  (7, 'test_item', 'Test', 'Test an issue.', 5),
  (8, 'add_code', 'Code', 'Add a pull request to the tracker.', 5);

  --
  -- Table structure for table `#__articles`
  --

DROP TABLE IF EXISTS `#__articles`;

CREATE TABLE `#__articles` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The article path',
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The article title',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'The article alias.',
  `text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The article text.',
  `text_md` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The raw article text.',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The created date.',
  `is_file` int(1) unsigned NOT NULL COMMENT 'If the text is present as a file (for different handling)',
  PRIMARY KEY (`article_id`),
  KEY `alias` (`alias`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `#__articles`
--

INSERT INTO `#__articles` (`title`, `alias`, `text`, `text_md`, `created_date`) VALUES
('PR Testing Platform Project', 'about', '<p>This is a platform where you can select a Pull Request you want to test, request a test selecting the PHP version you want and a Joomla! instance will be prepared for you!</p>', 'This is a platform where you can select a Pull Request you want to test, request a test selecting the PHP version you want and a Joomla! instance will be prepared for you!', '2013-10-01 00:00:00');


--
-- Table structure for table `#__issue_category_map`
--

DROP TABLE IF EXISTS `#__issue_category_map`;

CREATE TABLE `#__issue_category_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `issue_id` int(11) unsigned NOT NULL COMMENT 'PK of the issue in issue table',
  `category_id` int(11) unsigned NOT NULL COMMENT 'Category id',
  PRIMARY KEY (`id`),
  KEY `issue_id` (`issue_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `#__issue_category_map_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `#__issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `#__issue_category_map_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `#__issues_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__issues`
--

DROP TABLE IF EXISTS `#__issues`;

CREATE TABLE `#__issues` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `issue_number` int(11) unsigned DEFAULT NULL COMMENT 'THE issue number (ID)',
  `foreign_number` int(11) unsigned DEFAULT NULL COMMENT 'Foreign tracker id',
  `project_id` int(11) unsigned DEFAULT NULL COMMENT 'Project id',
  `milestone_id` int(11) unsigned DEFAULT NULL COMMENT 'Milestone id if applicable',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Issue title',
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Issue description',
  `description_raw` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The raw issue description (markdown)',
  `priority` tinyint(4) NOT NULL DEFAULT '3' COMMENT 'Issue priority',
  `status` int(11) unsigned NOT NULL DEFAULT '1' COMMENT 'Issue status',
  `opened_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Issue open date',
  `opened_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Opened by username',
  `closed_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Issue closed date',
  `closed_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Issue closed by username',
  `closed_sha` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The GitHub SHA where the issue has been closed',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Issue modified date',
  `modified_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Issue modified by username',
  `rel_number` int(11) unsigned DEFAULT NULL COMMENT 'Relation issue number',
  `rel_type` int(11) unsigned DEFAULT NULL COMMENT 'Relation type',
  `has_code` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If the issue has code attached - aka a pull request',
  `pr_head_user` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pull request head user',
  `pr_head_ref` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pull request head ref',
  `pr_head_sha` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pull request head SHA',
  `labels` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Comma separated list of label IDs',
  `build` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Build on which the issue is reported',
  `easy` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag whether an item is an easy test',
  `merge_state` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The merge state',
  `gh_merge_status` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The GitHub merge status (JSON encoded)',
  `commits` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Commits of the PR',
  `mergeable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag a PR as mergeable or not if there are conflicts',
  PRIMARY KEY (`id`),
  UNIQUE KEY `issue_project_index` (`issue_number`,`project_id`),
  KEY `status` (`status`),
  KEY `issue_number` (`issue_number`),
  KEY `project_id` (`project_id`),
  KEY `milestone_id` (`milestone_id`,`project_id`),
  KEY `#__issues_fk_rel_type` (`rel_type`),
  CONSTRAINT `#__issues_fk_milestone` FOREIGN KEY (`milestone_id`) REFERENCES `#__tracker_milestones` (`milestone_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `#__issues_fk_rel_type` FOREIGN KEY (`rel_type`) REFERENCES `#__issues_relations_types` (`id`),
  CONSTRAINT `#__issues_fk_status` FOREIGN KEY (`status`) REFERENCES `#__status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `#__issues_categories`
--

DROP TABLE IF EXISTS `#__issues_categories`;

CREATE TABLE `#__issues_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `project_id` int(11) NOT NULL COMMENT 'The id of the project',
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The title of the category',
  `alias` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The alias of the category',
  `color` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The hex value of the category',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `#__issues_categories_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `#__tracker_projects` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `#__issues_relations_types`
--

DROP TABLE IF EXISTS `#__issues_relations_types`;

CREATE TABLE `#__issues_relations_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data `#__issues_relations_types`
--

INSERT INTO `#__issues_relations_types` (`id`, `name`) VALUES
(1, 'duplicate_of'),
(2, 'related_to'),
(3, 'not_before'),
(4, 'pr_for');

--
-- Table structure for table `#__issues_tests`
--

DROP TABLE IF EXISTS `#__issues_tests`;

CREATE TABLE `#__issues_tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `item_id` int(11) NOT NULL COMMENT 'Item ID',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User name',
  `result` smallint(6) NOT NULL COMMENT 'Test result (1=success, 2=failure)',
  `sha` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The GitHub SHA where the issue has been tested against',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__issues_voting`
--

DROP TABLE IF EXISTS `#__issues_voting`;

CREATE TABLE `#__issues_voting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `issue_number` int(11) unsigned NOT NULL COMMENT 'Foreign key to #__issues.id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign key to #__users.id',
  `experienced` tinyint(2) unsigned NOT NULL COMMENT 'Flag indicating whether the user has experienced the issue',
  `score` int(11) unsigned NOT NULL COMMENT 'User score for importance of issue',
  PRIMARY KEY (`id`),
  KEY `#__issues_voting_fk_issue_id` (`issue_number`),
  KEY `#__issues_voting_fk_user_id` (`user_id`),
  CONSTRAINT `#__issues_voting_fk_issue_id` FOREIGN KEY (`issue_number`) REFERENCES `#__issues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `#__issues_voting_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__migrations`
--
DROP TABLE IF EXISTS `#__migrations`;

CREATE TABLE `#__migrations` (
  `version` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Applied migration versions',
  KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__migrations` (`version`) VALUES
('20160611001'),
('20160612001'),
('20160612002');

--
-- Table structure for table `#__status`
--

DROP TABLE IF EXISTS `#__status`;

CREATE TABLE `#__status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Dumping data for table `#__status`
--

INSERT INTO `#__status` (`id`, `status`, `closed`) VALUES
(1, 'New', 0),
(2, 'Confirmed', 0),
(3, 'Pending', 0),
(4, 'Ready to Commit', 0),
(5, 'Fixed in Code Base', 1),
(6, 'Needs Review', 0),
(7, 'Information Required', 0),
(8, 'Closed - Unconfirmed Report', 1),
(9, 'Closed - No Reply', 1),
(10, 'Closed', 1),
(11, 'Expected Behaviour', 1),
(12, 'Known Issue', 1),
(13, 'Duplicate Report', 1),
(14, 'Discussion', 1);


--
-- Table structure for table `#__tracker_labels`
--

DROP TABLE IF EXISTS `#__tracker_labels`;

CREATE TABLE `#__tracker_labels` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `project_id` int(11) NOT NULL COMMENT 'Project ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label name',
  `color` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label color',
  PRIMARY KEY (`label_id`),
  KEY `name` (`name`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `#__tracker_labels_fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `#__tracker_projects` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__tracker_milestones`
--

DROP TABLE IF EXISTS `#__tracker_milestones`;

CREATE TABLE `#__tracker_milestones` (
  `milestone_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `milestone_number` int(11) NOT NULL COMMENT 'Milestone number from Github',
  `project_id` int(11) NOT NULL COMMENT 'Project ID',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Milestone title',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Milestone description',
  `state` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label state: open | closed',
  `due_on` datetime DEFAULT NULL COMMENT 'Date the milestone is due on.',
  PRIMARY KEY (`milestone_id`),
  KEY `name` (`title`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `#__tracker_milestones_fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `#__tracker_projects` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__tracker_projects`
--

DROP TABLE IF EXISTS `#__tracker_projects`;

CREATE TABLE `#__tracker_projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Project title',
  `alias` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Project URL alias',
  `gh_user` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'GitHub user',
  `gh_project` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'GitHub project',
  `ext_tracker_link` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A tracker link format (e.g. http://tracker.com/issue/%d)',
  `short_title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Project short title',
  `gh_editbot_user` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'GitHub editbot username',
  `gh_editbot_pass` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'GitHub editbot password',
  PRIMARY KEY (`project_id`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data `#__tracker_projects`
--

INSERT INTO `#__tracker_projects` (`project_id`, `title`, `alias`, `gh_user`, `gh_project`, `ext_tracker_link`, `short_title`) VALUES
(1, 'Joomla! Test Repo 1', 'testrepo1', 'TestOrg11111111', 'testrepo1', '', 'Test Repo 1');


--
-- Table structure for table `#__user_accessgroup_map`
--

DROP TABLE IF EXISTS `#__user_accessgroup_map`;

CREATE TABLE `#__user_accessgroup_map` (
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__accessgroups.id',
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `#__user_accessgroup_map_fk_group_id` (`group_id`),
  CONSTRAINT `#__user_accessgroup_map_fk_group_id` FOREIGN KEY (`group_id`) REFERENCES `#__accessgroups` (`group_id`),
  CONSTRAINT `#__user_accessgroup_map_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__users`
--

DROP TABLE IF EXISTS `#__users`;

CREATE TABLE `#__users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'The users name',
  `username` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'The users username',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'The users e-mail',
  `block` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'If the user is blocked',
  `sendEmail` tinyint(4) DEFAULT '0' COMMENT 'If the users recieves e-mail',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The register date',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The last visit date',
  `params` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Parameters',
  PRIMARY KEY (`id`),
  KEY `idx_block` (`block`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `idx_name` (`name`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__instances`
--

DROP TABLE IF EXISTS `#__instances`;

CREATE TABLE `#__instances` (
  `instance_id` varchar(100) NOT NULL DEFAULT '0' COMMENT 'PK',
  `php_version` int(11) NOT NULL DEFAULT '0' COMMENT 'PHP Version used in the instance',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'User ID',
  `pr_id` int(11) unsigned NOT NULL COMMENT 'Pull Request ID',
  `requested_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date and time when instance was requested',
  `target_branch` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Target branch for this instance',
  PRIMARY KEY (`instance_id`),
  CONSTRAINT `#__instances_fk_pr_id` FOREIGN KEY (`pr_id`) REFERENCES `#__issues` (`issue_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `#__instances_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
