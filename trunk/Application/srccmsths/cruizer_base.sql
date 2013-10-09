-- 
-- Table structure for table `person`
-- 

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  PRIMARY KEY  (`person_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `person`
-- 

INSERT INTO `person` (`person_id`, `first_name`, `middle_name`, `last_name`, `gender`) VALUES 
(1, 'Root', 'Super', 'User', 'Male');

-- --------------------------------------------------------

-- 
-- Table structure for table `system_log`
-- 

CREATE TABLE `system_log` (
  `entry_id` bigint(20) NOT NULL auto_increment,
  `ip_address` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `datetime` int(11) NOT NULL,
  `action` varchar(50000) NOT NULL,
  `module` varchar(255) NOT NULL,
  PRIMARY KEY  (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `system_log`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `system_settings`
-- 

CREATE TABLE `system_settings` (
  `setting` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `system_settings`
-- 

INSERT INTO `system_settings` (`setting`, `value`) VALUES 
('Security Level', 'Red Alert');

-- --------------------------------------------------------

-- 
-- Table structure for table `system_skins`
-- 

CREATE TABLE `system_skins` (
  `skin_id` int(11) NOT NULL auto_increment,
  `skin_name` varchar(255) NOT NULL,
  `header` varchar(255) NOT NULL,
  `footer` varchar(255) NOT NULL,
  `css` varchar(255) NOT NULL,
  PRIMARY KEY  (`skin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `system_skins`
-- 

INSERT INTO `system_skins` (`skin_id`, `skin_name`, `header`, `footer`, `css`) VALUES 
(1, 'Cobalt Default', 'skins/default_header.php', 'skins/default_footer.php', 'cobalt.css');

-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE `user` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `iteration` int(11) NOT NULL,
  `method` varchar(255) NOT NULL,
  `person_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `skin_id` int(11) NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `user`
-- 

INSERT INTO `user` (`username`, `password`, `salt`, `iteration`, `method`, `person_id`, `user_type_id`, `skin_id`) VALUES 
('root', 'e43de37614c7ed43f569d508a45f56841d741584', '4cc8f5c547a701399b70b32a02ea7eca5a7b19c6', '178239', 'SHA1', '1', 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `user_links`
-- 

CREATE TABLE `user_links` (
  `link_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `target` varchar(255) collate latin1_general_ci NOT NULL,
  `descriptive_title` varchar(255) collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `passport_group_id` int(11) NOT NULL,
  `show_in_tasklist` varchar(255) collate latin1_general_ci NOT NULL,
  `status` varchar(255) collate latin1_general_ci NOT NULL,
  `icon` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `user_links`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_passport`
-- 

CREATE TABLE `user_passport` (
  `username` varchar(255) collate latin1_general_ci NOT NULL,
  `link_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`username`,`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `user_passport`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_passport_groups`
-- 

CREATE TABLE `user_passport_groups` (
  `passport_group_id` int(11) NOT NULL auto_increment,
  `passport_group` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,  
  PRIMARY KEY  (`passport_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `user_passport_groups`
-- 

INSERT INTO `user_passport_groups` (`passport_group_id`, `passport_group`,`icon`) VALUES 
(1, 'Default','blue_folder3.png'),
(2, 'Sysadmin','preferences-system.png');


-- --------------------------------------------------------

-- 
-- Table structure for table `user_role`
-- 

 CREATE TABLE `user_role` (
`role_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`role` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;

-- 
-- Dumping data for table `user_role`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_role_links`
-- 

CREATE TABLE `user_role_links` (
`role_id` INT NOT NULL ,
`link_id` INT NOT NULL ,
PRIMARY KEY ( `role_id` , `link_id` )
) ENGINE = MYISAM ;

-- 
-- Dumping data for table `user_role_links`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_types`
-- 

CREATE TABLE `user_types` (
  `user_type_id` int(11) NOT NULL auto_increment,
  `user_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`user_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `user_types`
-- 

INSERT INTO `user_types` (`user_type_id`, `user_type`) VALUES 
(1, 'System Admin'),
(2, 'Staff');

