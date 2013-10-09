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

INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('1', 'Module Control', '/srccmsths/sysadmin/module_control.php', 'Module Control', 'Enable and/or disable system modules','2','Yes','On','modulecontrol.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('2', 'Set User Passports', '/srccmsths/sysadmin/set_user_passports.php', 'Set User Passports', 'Change the passport settings of system users','2','Yes','On','passport.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('3', 'Security Monitor', '/srccmsths/sysadmin/security_monitor.php', 'Security Monitor', 'Examine the system log','2','Yes','On','security3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('4', 'Add person', '/srccmsths/sysadmin/add_person.php', 'Add person', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('5', 'Edit person', '/srccmsths/sysadmin/edit_person.php', 'Edit person', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('6', 'View person', '/srccmsths/sysadmin/listview_person.php', 'Manage person', '', '2', 'Yes', 'On','persons.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('7', 'Delete person', '/srccmsths/sysadmin/delete_person.php', 'Delete person', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('8', 'Add user', '/srccmsths/sysadmin/add_user.php', 'Add user', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('9', 'Edit user', '/srccmsths/sysadmin/edit_user.php', 'Edit user', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('10', 'View user', '/srccmsths/sysadmin/listview_user.php', 'Manage user', '', '2', 'Yes', 'On','card.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('11', 'Delete user', '/srccmsths/sysadmin/delete_user.php', 'Delete user', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('12', 'Add user types', '/srccmsths/sysadmin/add_user_types.php', 'Add user types', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('13', 'Edit user types', '/srccmsths/sysadmin/edit_user_types.php', 'Edit user types', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('14', 'View user types', '/srccmsths/sysadmin/listview_user_types.php', 'Manage user types', '', '2', 'Yes', 'On','user_type2.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('15', 'Delete user types', '/srccmsths/sysadmin/delete_user_types.php', 'Delete user types', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('16','Add user role', '/srccmsths/sysadmin/add_user_role.php', 'Add user role','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('17','Edit user role', '/srccmsths/sysadmin/edit_user_role.php', 'Edit user role','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('18','View user role', '/srccmsths/sysadmin/listview_user_role.php', 'Manage user roles','','2','Yes','On','roles.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('19','Delete user role', '/srccmsths/sysadmin/delete_user_role.php', 'Delete user role','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('20','Add system settings', '/srccmsths/sysadmin/add_system_settings.php', 'Add system settings','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('21','Edit system settings', '/srccmsths/sysadmin/edit_system_settings.php', 'Edit system settings','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('22','View system settings', '/srccmsths/sysadmin/listview_system_settings.php', 'Manage system settings','','2','Yes','On','system_settings.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('23','Delete system settings', '/srccmsths/sysadmin/delete_system_settings.php', 'Delete system settings','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('24','Add user links', '/srccmsths/sysadmin/add_user_links.php', 'Add user links','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('25','Edit user links', '/srccmsths/sysadmin/edit_user_links.php', 'Edit user links','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('26','View user links', '/srccmsths/sysadmin/listview_user_links.php', 'Manage user links','','2','Yes','On','links.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('27','Delete user links', '/srccmsths/sysadmin/delete_user_links.php', 'Delete user links','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('28','Add user passport groups', '/srccmsths/sysadmin/add_user_passport_groups.php', 'Add user passport groups','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('29','Edit user passport groups', '/srccmsths/sysadmin/edit_user_passport_groups.php', 'Edit user passport groups','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('30','View user passport groups', '/srccmsths/sysadmin/listview_user_passport_groups.php', 'Manage user passport groups','','2','Yes','On','passportgroup.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('31','Delete user passport groups', '/srccmsths/sysadmin/delete_user_passport_groups.php', 'Delete user passport groups','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add applicant', '/srccmsths/applicant/add_applicant.php', 'Add applicant','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit applicant', '/srccmsths/applicant/edit_applicant.php', 'Edit applicant','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View applicant', '/srccmsths/applicant/listview_applicant.php', 'Manage applicant','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete applicant', '/srccmsths/applicant/delete_applicant.php', 'Delete applicant','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add applicant has requirement', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php', 'Add applicant has requirement','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit applicant has requirement', '/srccmsths/applicantrequirement/edit_applicant_has_requirement.php', 'Edit applicant has requirement','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View applicant has requirement', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php', 'Manage applicant has requirement','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete applicant has requirement', '/srccmsths/applicantrequirement/delete_applicant_has_requirement.php', 'Delete applicant has requirement','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add department', '/srccmsths/department/add_department.php', 'Add department','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit department', '/srccmsths/department/edit_department.php', 'Edit department','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View department', '/srccmsths/department/listview_department.php', 'Manage department','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete department', '/srccmsths/department/delete_department.php', 'Delete department','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add exam', '/srccmsths/exam/add_exam.php', 'Add exam','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit exam', '/srccmsths/exam/edit_exam.php', 'Edit exam','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View exam', '/srccmsths/exam/listview_exam.php', 'Manage exam','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete exam', '/srccmsths/exam/delete_exam.php', 'Delete exam','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add grades', '/srccmsths/grades/add_grades.php', 'Add grades','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit grades', '/srccmsths/grades/edit_grades.php', 'Edit grades','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View grades', '/srccmsths/grades/listview_grades.php', 'Manage grades','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete grades', '/srccmsths/grades/delete_grades.php', 'Delete grades','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add interviewee', '/srccmsths/interviewee/add_interviewee.php', 'Add interviewee','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit interviewee', '/srccmsths/interviewee/edit_interviewee.php', 'Edit interviewee','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View interviewee', '/srccmsths/interviewee/listview_interviewee.php', 'Manage interviewee','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete interviewee', '/srccmsths/interviewee/delete_interviewee.php', 'Delete interviewee','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add requirement', '/srccmsths/requirement/add_requirement.php', 'Add requirement','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit requirement', '/srccmsths/requirement/edit_requirement.php', 'Edit requirement','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View requirement', '/srccmsths/requirement/listview_requirement.php', 'Manage requirement','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete requirement', '/srccmsths/requirement/delete_requirement.php', 'Delete requirement','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add section', '/srccmsths/section/add_section.php', 'Add section','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit section', '/srccmsths/section/edit_section.php', 'Edit section','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View section', '/srccmsths/section/listview_section.php', 'Manage section','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete section', '/srccmsths/section/delete_section.php', 'Delete section','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add student', '/srccmsths/student/add_student.php', 'Add student','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit student', '/srccmsths/student/edit_student.php', 'Edit student','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View student', '/srccmsths/student/listview_student.php', 'Manage student','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete student', '/srccmsths/student/delete_student.php', 'Delete student','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add studentsection', '/srccmsths/studentsection/add_studentsection.php', 'Add studentsection','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit studentsection', '/srccmsths/studentsection/edit_studentsection.php', 'Edit studentsection','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View studentsection', '/srccmsths/studentsection/listview_studentsection.php', 'Manage studentsection','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete studentsection', '/srccmsths/studentsection/delete_studentsection.php', 'Delete studentsection','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add subject', '/srccmsths/subject/add_subject.php', 'Add subject','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit subject', '/srccmsths/subject/edit_subject.php', 'Edit subject','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View subject', '/srccmsths/subject/listview_subject.php', 'Manage subject','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete subject', '/srccmsths/subject/delete_subject.php', 'Delete subject','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add subjectstudentsection', '/srccmsths/subjectstudentsection/add_subjectstudentsection.php', 'Add subjectstudentsection','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit subjectstudentsection', '/srccmsths/subjectstudentsection/edit_subjectstudentsection.php', 'Edit subjectstudentsection','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View subjectstudentsection', '/srccmsths/subjectstudentsection/listview_subjectstudentsection.php', 'Manage subjectstudentsection','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete subjectstudentsection', '/srccmsths/subjectstudentsection/delete_subjectstudentsection.php', 'Delete subjectstudentsection','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add teacher', '/srccmsths/teacher/add_teacher.php', 'Add teacher','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit teacher', '/srccmsths/teacher/edit_teacher.php', 'Edit teacher','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View teacher', '/srccmsths/teacher/listview_teacher.php', 'Manage teacher','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete teacher', '/srccmsths/teacher/delete_teacher.php', 'Delete teacher','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Add teacher has subject', '/srccmsths/teachersubject/add_teacher_has_subject.php', 'Add teacher has subject','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Edit teacher has subject', '/srccmsths/teachersubject/edit_teacher_has_subject.php', 'Edit teacher has subject','','1','No','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'View teacher has subject', '/srccmsths/teachersubject/listview_teacher_has_subject.php', 'Manage teacher has subject','','1','Yes','On','form3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES(null,'Delete teacher has subject', '/srccmsths/teachersubject/delete_teacher_has_subject.php', 'Delete teacher has subject','','1','No','On','form3.png');
INSERT INTO `user_passport`(username, link_id) VALUES('root','1');
INSERT INTO `user_passport`(username, link_id) VALUES('root','2');
INSERT INTO `user_passport`(username, link_id) VALUES('root','3');
INSERT INTO `user_passport`(username, link_id) VALUES('root','4');
INSERT INTO `user_passport`(username, link_id) VALUES('root','5');
INSERT INTO `user_passport`(username, link_id) VALUES('root','6');
INSERT INTO `user_passport`(username, link_id) VALUES('root','7');
INSERT INTO `user_passport`(username, link_id) VALUES('root','8');
INSERT INTO `user_passport`(username, link_id) VALUES('root','9');
INSERT INTO `user_passport`(username, link_id) VALUES('root','10');
INSERT INTO `user_passport`(username, link_id) VALUES('root','11');
INSERT INTO `user_passport`(username, link_id) VALUES('root','12');
INSERT INTO `user_passport`(username, link_id) VALUES('root','13');
INSERT INTO `user_passport`(username, link_id) VALUES('root','14');
INSERT INTO `user_passport`(username, link_id) VALUES('root','15');
INSERT INTO `user_passport`(username, link_id) VALUES('root','16');
INSERT INTO `user_passport`(username, link_id) VALUES('root','17');
INSERT INTO `user_passport`(username, link_id) VALUES('root','18');
INSERT INTO `user_passport`(username, link_id) VALUES('root','19');
INSERT INTO `user_passport`(username, link_id) VALUES('root','20');
INSERT INTO `user_passport`(username, link_id) VALUES('root','21');
INSERT INTO `user_passport`(username, link_id) VALUES('root','22');
INSERT INTO `user_passport`(username, link_id) VALUES('root','23');
INSERT INTO `user_passport`(username, link_id) VALUES('root','24');
INSERT INTO `user_passport`(username, link_id) VALUES('root','25');
INSERT INTO `user_passport`(username, link_id) VALUES('root','26');
INSERT INTO `user_passport`(username, link_id) VALUES('root','27');
INSERT INTO `user_passport`(username, link_id) VALUES('root','28');
INSERT INTO `user_passport`(username, link_id) VALUES('root','29');
INSERT INTO `user_passport`(username, link_id) VALUES('root','30');
INSERT INTO `user_passport`(username, link_id) VALUES('root','31');
INSERT INTO `user_passport`(username, link_id) VALUES('root','32');
INSERT INTO `user_passport`(username, link_id) VALUES('root','33');
INSERT INTO `user_passport`(username, link_id) VALUES('root','34');
INSERT INTO `user_passport`(username, link_id) VALUES('root','35');
INSERT INTO `user_passport`(username, link_id) VALUES('root','36');
INSERT INTO `user_passport`(username, link_id) VALUES('root','37');
INSERT INTO `user_passport`(username, link_id) VALUES('root','38');
INSERT INTO `user_passport`(username, link_id) VALUES('root','39');
INSERT INTO `user_passport`(username, link_id) VALUES('root','40');
INSERT INTO `user_passport`(username, link_id) VALUES('root','41');
INSERT INTO `user_passport`(username, link_id) VALUES('root','42');
INSERT INTO `user_passport`(username, link_id) VALUES('root','43');
INSERT INTO `user_passport`(username, link_id) VALUES('root','44');
INSERT INTO `user_passport`(username, link_id) VALUES('root','45');
INSERT INTO `user_passport`(username, link_id) VALUES('root','46');
INSERT INTO `user_passport`(username, link_id) VALUES('root','47');
INSERT INTO `user_passport`(username, link_id) VALUES('root','48');
INSERT INTO `user_passport`(username, link_id) VALUES('root','49');
INSERT INTO `user_passport`(username, link_id) VALUES('root','50');
INSERT INTO `user_passport`(username, link_id) VALUES('root','51');
INSERT INTO `user_passport`(username, link_id) VALUES('root','52');
INSERT INTO `user_passport`(username, link_id) VALUES('root','53');
INSERT INTO `user_passport`(username, link_id) VALUES('root','54');
INSERT INTO `user_passport`(username, link_id) VALUES('root','55');
INSERT INTO `user_passport`(username, link_id) VALUES('root','56');
INSERT INTO `user_passport`(username, link_id) VALUES('root','57');
INSERT INTO `user_passport`(username, link_id) VALUES('root','58');
INSERT INTO `user_passport`(username, link_id) VALUES('root','59');
INSERT INTO `user_passport`(username, link_id) VALUES('root','60');
INSERT INTO `user_passport`(username, link_id) VALUES('root','61');
INSERT INTO `user_passport`(username, link_id) VALUES('root','62');
INSERT INTO `user_passport`(username, link_id) VALUES('root','63');
INSERT INTO `user_passport`(username, link_id) VALUES('root','64');
INSERT INTO `user_passport`(username, link_id) VALUES('root','65');
INSERT INTO `user_passport`(username, link_id) VALUES('root','66');
INSERT INTO `user_passport`(username, link_id) VALUES('root','67');
INSERT INTO `user_passport`(username, link_id) VALUES('root','68');
INSERT INTO `user_passport`(username, link_id) VALUES('root','69');
INSERT INTO `user_passport`(username, link_id) VALUES('root','70');
INSERT INTO `user_passport`(username, link_id) VALUES('root','71');
INSERT INTO `user_passport`(username, link_id) VALUES('root','72');
INSERT INTO `user_passport`(username, link_id) VALUES('root','73');
INSERT INTO `user_passport`(username, link_id) VALUES('root','74');
INSERT INTO `user_passport`(username, link_id) VALUES('root','75');
INSERT INTO `user_passport`(username, link_id) VALUES('root','76');
INSERT INTO `user_passport`(username, link_id) VALUES('root','77');
INSERT INTO `user_passport`(username, link_id) VALUES('root','78');
INSERT INTO `user_passport`(username, link_id) VALUES('root','79');
INSERT INTO `user_passport`(username, link_id) VALUES('root','80');
INSERT INTO `user_passport`(username, link_id) VALUES('root','81');
INSERT INTO `user_passport`(username, link_id) VALUES('root','82');
INSERT INTO `user_passport`(username, link_id) VALUES('root','83');
INSERT INTO `user_passport`(username, link_id) VALUES('root','84');
INSERT INTO `user_passport`(username, link_id) VALUES('root','85');
INSERT INTO `user_passport`(username, link_id) VALUES('root','86');
INSERT INTO `user_passport`(username, link_id) VALUES('root','87');
INSERT INTO `user_passport`(username, link_id) VALUES('root','88');
INSERT INTO `user_passport`(username, link_id) VALUES('root','89');
INSERT INTO `user_passport`(username, link_id) VALUES('root','90');
INSERT INTO `user_passport`(username, link_id) VALUES('root','91');
INSERT INTO `user_passport`(username, link_id) VALUES('root','92');
INSERT INTO `user_passport`(username, link_id) VALUES('root','93');
INSERT INTO `user_passport`(username, link_id) VALUES('root','94');
INSERT INTO `user_passport`(username, link_id) VALUES('root','95');
INSERT INTO `user_passport`(username, link_id) VALUES('root','96');
INSERT INTO `user_passport`(username, link_id) VALUES('root','97');
INSERT INTO `user_passport`(username, link_id) VALUES('root','98');
INSERT INTO `user_passport`(username, link_id) VALUES('root','99');
INSERT INTO `user_passport`(username, link_id) VALUES('root','100');
INSERT INTO `user_passport`(username, link_id) VALUES('root','101');
INSERT INTO `user_passport`(username, link_id) VALUES('root','102');
INSERT INTO `user_passport`(username, link_id) VALUES('root','103');
INSERT INTO `user_passport`(username, link_id) VALUES('root','104');
INSERT INTO `user_passport`(username, link_id) VALUES('root','105');
INSERT INTO `user_passport`(username, link_id) VALUES('root','106');
INSERT INTO `user_passport`(username, link_id) VALUES('root','107');
INSERT INTO `user_passport`(username, link_id) VALUES('root','108');
INSERT INTO `user_passport`(username, link_id) VALUES('root','109');
INSERT INTO `user_passport`(username, link_id) VALUES('root','110');
INSERT INTO `user_passport`(username, link_id) VALUES('root','111');
INSERT INTO `user_passport`(username, link_id) VALUES('root','112');
INSERT INTO `user_passport`(username, link_id) VALUES('root','113');
INSERT INTO `user_passport`(username, link_id) VALUES('root','114');
INSERT INTO `user_passport`(username, link_id) VALUES('root','115');
