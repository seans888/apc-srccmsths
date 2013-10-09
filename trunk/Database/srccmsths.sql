-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2013 at 04:47 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `srccmsths`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

CREATE TABLE IF NOT EXISTS `applicant` (
  `applicant_no` int(11) NOT NULL AUTO_INCREMENT,
  `learners_no` char(12) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  PRIMARY KEY (`applicant_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `applicant`
--

INSERT INTO `applicant` (`applicant_no`, `learners_no`, `first_name`, `last_name`, `middle_name`, `date_of_birth`, `gender`, `year`) VALUES
(3, '2011-100095', 'Joshua', 'Dimapilis', 'Coralde', '1995-02-10', 'M', 2011),
(4, '2011-100094', 'Kimberly', 'Elizondo', 'Belda', '1990-06-27', 'F', 2011),
(5, '2011-100074', 'Trixia Marie', 'Urquiza', 'Ambagan', '1995-01-07', 'F', 2011);

-- --------------------------------------------------------

--
-- Table structure for table `applicant_has_requirement`
--

CREATE TABLE IF NOT EXISTS `applicant_has_requirement` (
  `Applicant_applicant_no` int(11) NOT NULL,
  `Requirement_requirement_no` int(11) NOT NULL,
  `submitted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Applicant_applicant_no`,`Requirement_requirement_no`),
  KEY `fk_Applicant_has_Requirement_Requirement1_idx` (`Requirement_requirement_no`),
  KEY `fk_Applicant_has_Requirement_Applicant_idx` (`Applicant_applicant_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `applicant_has_requirement`
--

INSERT INTO `applicant_has_requirement` (`Applicant_applicant_no`, `Requirement_requirement_no`, `submitted`) VALUES
(3, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `dept_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`dept_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_no`, `name`) VALUES
(1, 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `exam_no` int(11) NOT NULL AUTO_INCREMENT,
  `score` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `Applicant_applicant_no` int(11) NOT NULL,
  PRIMARY KEY (`exam_no`,`Applicant_applicant_no`),
  KEY `fk_Exam_Applicant1_idx` (`Applicant_applicant_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE IF NOT EXISTS `grades` (
  `quarter` char(1) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `letter_equiv` char(2) DEFAULT NULL,
  `SubjectStudentSection_Subject_subject_no` int(11) NOT NULL,
  `SubjectStudentSection_StudentSection_Student_student_no` char(11) NOT NULL,
  `SubjectStudentSection_StudentSection_Section_section_no` int(11) NOT NULL,
  PRIMARY KEY (`quarter`,`SubjectStudentSection_Subject_subject_no`,`SubjectStudentSection_StudentSection_Student_student_no`,`SubjectStudentSection_StudentSection_Section_section_no`),
  KEY `fk_Grades_SubjectStudentSection1_idx` (`SubjectStudentSection_Subject_subject_no`,`SubjectStudentSection_StudentSection_Student_student_no`,`SubjectStudentSection_StudentSection_Section_section_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interviewee`
--

CREATE TABLE IF NOT EXISTS `interviewee` (
  `interview_no` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `Exam_exam_no` int(11) NOT NULL,
  `Exam_Applicant_applicant_no` int(11) NOT NULL,
  PRIMARY KEY (`interview_no`,`Exam_exam_no`,`Exam_Applicant_applicant_no`),
  KEY `fk_Interview_Exam1_idx` (`Exam_exam_no`,`Exam_Applicant_applicant_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `person_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`person_id`, `first_name`, `middle_name`, `last_name`, `gender`) VALUES
(1, 'Root', 'Super', 'User', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `requirement`
--

CREATE TABLE IF NOT EXISTS `requirement` (
  `requirement_no` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `document` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`requirement_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `requirement`
--

INSERT INTO `requirement` (`requirement_no`, `type`, `year`, `document`) VALUES
(1, 'A', 2013, 'Report Card'),
(2, 'B', 2013, 'Certificate of Good Moral Character'),
(3, 'A', 2011, 'Diploma');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `section_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `Teacher_teacher_no` int(11) NOT NULL,
  `Teacher_Department_dept_no` int(11) NOT NULL,
  PRIMARY KEY (`section_no`,`Teacher_teacher_no`,`Teacher_Department_dept_no`),
  KEY `fk_Section_Teacher1_idx` (`Teacher_teacher_no`,`Teacher_Department_dept_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `student_no` char(11) NOT NULL,
  `year_level` varchar(45) DEFAULT NULL,
  `Interviewee_interview_no` int(11) NOT NULL,
  `Interviewee_Exam_exam_no` int(11) NOT NULL,
  `Interviewee_Exam_Applicant_applicant_no` int(11) NOT NULL,
  PRIMARY KEY (`student_no`,`Interviewee_interview_no`,`Interviewee_Exam_exam_no`,`Interviewee_Exam_Applicant_applicant_no`),
  KEY `fk_Student_Interviewee1_idx` (`Interviewee_interview_no`,`Interviewee_Exam_exam_no`,`Interviewee_Exam_Applicant_applicant_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studentsection`
--

CREATE TABLE IF NOT EXISTS `studentsection` (
  `Student_student_no` char(11) NOT NULL,
  `Section_section_no` int(11) NOT NULL,
  `year` year(4) DEFAULT NULL,
  PRIMARY KEY (`Student_student_no`,`Section_section_no`),
  KEY `fk_Student_has_Section_Section1_idx` (`Section_section_no`),
  KEY `fk_Student_has_Section_Student1_idx` (`Student_student_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `subject_no` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  PRIMARY KEY (`subject_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_no`, `code`, `name`, `year`) VALUES
(1, 'INPROLA', 'Introduction to Programming Languages', 2013);

-- --------------------------------------------------------

--
-- Table structure for table `subjectstudentsection`
--

CREATE TABLE IF NOT EXISTS `subjectstudentsection` (
  `Subject_subject_no` int(11) NOT NULL,
  `StudentSection_Student_student_no` char(11) NOT NULL,
  `StudentSection_Section_section_no` int(11) NOT NULL,
  PRIMARY KEY (`Subject_subject_no`,`StudentSection_Student_student_no`,`StudentSection_Section_section_no`),
  KEY `fk_Subject_has_Student_has_Section_Subject1_idx` (`Subject_subject_no`),
  KEY `fk_SubjectStudentSection_StudentSection1_idx` (`StudentSection_Student_student_no`,`StudentSection_Section_section_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE IF NOT EXISTS `system_log` (
  `entry_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `datetime` int(11) NOT NULL,
  `action` varchar(50000) NOT NULL,
  `module` varchar(255) NOT NULL,
  PRIMARY KEY (`entry_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=196 ;

--
-- Dumping data for table `system_log`
--

INSERT INTO `system_log` (`entry_id`, `ip_address`, `user`, `datetime`, `action`, `module`) VALUES
(1, '::1', 'root', 1376293719, 'Logged in', '/srccmsths/login.php'),
(2, '::1', 'root', 1376293737, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(3, '::1', 'root', 1376293751, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(4, '::1', 'root', 1376294294, 'Pressed submit button', '/srccmsths/applicant/add_applicant.php'),
(5, '::1', 'root', 1376294296, 'Query executed: <br> INSERT INTO applicant(applicant_no, learners_no, first_name, last_name, middle_name, date_of_birth, gender, year) VALUES('''', ''102938918749'', ''Trixia Marie'', ''Urquiza'', ''Ambagan'', ''1995-01-07'', ''M'', ''2013'')', '/srccmsths/applicant/add_applicant.php'),
(6, '::1', 'root', 1376294300, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(7, '::1', 'root', 1376294360, 'Pressed cancel button', '/srccmsths/applicant/listview_applicant.php'),
(8, '::1', 'root', 1376294375, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(9, '::1', 'root', 1376294379, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(10, '::1', 'root', 1376362865, 'Logged in', '/srccmsths/login.php'),
(11, '::1', 'root', 1376362921, 'Module Access', '/srccmsths/sysadmin/listview_system_settings.php'),
(12, '::1', 'root', 1376362937, 'Module Access', '/srccmsths/sysadmin/edit_system_settings.php'),
(13, '::1', 'root', 1376362942, 'Module Access', '/srccmsths/sysadmin/edit_system_settings.php'),
(14, '::1', 'root', 1376362952, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(15, '::1', 'root', 1376362967, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(16, '::1', 'root', 1376363262, 'Module Access', '/srccmsths/exam/listview_exam.php'),
(17, '::1', 'root', 1376363273, 'Module Access', '/srccmsths/exam/listview_exam.php'),
(18, '::1', 'root', 1376363286, 'Module Access', '/srccmsths/exam/add_exam.php'),
(19, '::1', 'root', 1376363290, 'Module Access', '/srccmsths/exam/add_exam.php'),
(20, '::1', 'root', 1376363390, 'Query executed: <br> UPDATE user SET `password`=''$2a$13$0687d81a54f9fa1c29c26u2jdx8A0b9OHdaXrXpqCtCTuNElcahDq'', `salt`=''0687d81a54f9fa1c29c2610089703756e31c253b'', `iteration`=''13'', `method`=''BLOWFISH'' WHERE username=''root''', '/srccmsths/change_password.php'),
(21, '::1', 'root', 1376363398, 'Logged out', '/srccmsths/end.php'),
(22, '::1', 'root', 1376363417, 'Logged in', '/srccmsths/login.php'),
(23, '::1', 'root', 1376363442, 'Logged out', '/srccmsths/end.php'),
(24, '::1', 'root', 1376614303, 'Logged in', '/srccmsths/login.php'),
(25, '::1', 'root', 1376614327, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(26, '::1', 'root', 1376614364, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(27, '::1', 'root', 1376614395, 'Pressed submit button', '/srccmsths/applicant/add_applicant.php'),
(28, '::1', 'root', 1376614397, 'Query executed: <br> INSERT INTO applicant(applicant_no, learners_no, first_name, last_name, middle_name, date_of_birth, gender, year) VALUES('''', ''109408922897'', ''Joshua'', ''Dimapilis'', ''Coralde'', ''1995-02-10'', ''M'', ''2013'')', '/srccmsths/applicant/add_applicant.php'),
(29, '::1', 'root', 1376614401, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(30, '::1', 'root', 1376827315, 'Logged in', '/srccmsths/login.php'),
(31, '::1', 'root', 1376827324, 'Logged in', '/srccmsths/login.php'),
(32, '::1', 'root', 1376827413, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(33, '::1', 'root', 1376827425, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(34, '::1', 'root', 1376827436, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(35, '::1', 'root', 1376827479, 'Module Access', '/srccmsths/requirement/add_requirement.php'),
(36, '::1', 'root', 1376827496, 'Module Access', '/srccmsths/student/listview_student.php'),
(37, '::1', 'root', 1376827507, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(38, '::1', 'root', 1376827526, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(39, '::1', 'root', 1376827530, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(40, '::1', 'root', 1376827563, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(41, '::1', 'root', 1376827574, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(42, '::1', 'root', 1376827803, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(43, '::1', 'root', 1376828035, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(44, '::1', 'root', 1377790428, 'Logged in', '/srccmsths/login.php'),
(45, '::1', 'root', 1377790469, 'Module Access', '/srccmsths/sysadmin/listview_system_settings.php'),
(46, '::1', 'root', 1377790482, 'Module Access', '/srccmsths/teachersubject/listview_teacher_has_subject.php'),
(47, '::1', 'root', 1377790523, 'Module Access', '/srccmsths/sysadmin/module_control.php'),
(48, '::1', 'root', 1377790529, 'Module Access', '/srccmsths/sysadmin/listview_system_settings.php'),
(49, '::1', 'root', 1377790561, 'Pressed cancel button', '/srccmsths/sysadmin/listview_system_settings.php'),
(50, '::1', 'root', 1377790578, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(51, '::1', 'root', 1377790608, 'Module Access', '/srccmsths/sysadmin/listview_user_role.php'),
(52, '::1', 'root', 1377790635, 'Module Access', '/srccmsths/sysadmin/listview_user_role.php'),
(53, '::1', 'root', 1377790649, 'Pressed cancel button', '/srccmsths/sysadmin/listview_user_role.php'),
(54, '::1', 'root', 1377790728, 'Logged in', '/srccmsths/login.php'),
(55, '::1', 'root', 1377790792, 'Module Access', '/srccmsths/index.php'),
(56, '::1', 'root', 1377790950, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(57, '::1', 'root', 1377791123, 'Module Access', '/srccmsths/teacher/add_teacher.php'),
(58, '::1', 'root', 1377791154, 'Pressed submit button', '/srccmsths/teacher/add_teacher.php'),
(59, '::1', 'root', 1377791181, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(60, '::1', 'root', 1377791204, 'Module Access', '/srccmsths/student/listview_student.php'),
(61, '::1', 'root', 1377791218, 'Module Access', '/srccmsths/student/add_student.php'),
(62, '::1', 'root', 1377791244, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(63, '::1', 'root', 1377791266, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(64, '::1', 'root', 1377791290, 'Module Access', '/srccmsths/applicant/delete_applicant.php'),
(65, '::1', 'root', 1377791296, 'Pressed delete button', '/srccmsths/applicant/delete_applicant.php'),
(66, '::1', 'root', 1377791298, 'Query executed: <br> DELETE FROM applicant WHERE applicant_no = ''1''', '/srccmsths/applicant/delete_applicant.php'),
(67, '::1', 'root', 1377791302, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(68, '::1', 'root', 1377791335, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(69, '::1', 'root', 1377791350, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(70, '::1', 'root', 1377791752, 'Module Access', '/srccmsths/applicant/delete_applicant.php'),
(71, '::1', 'root', 1377791759, 'Pressed delete button', '/srccmsths/applicant/delete_applicant.php'),
(72, '::1', 'root', 1377791761, 'Query executed: <br> DELETE FROM applicant WHERE applicant_no = ''2''', '/srccmsths/applicant/delete_applicant.php'),
(73, '::1', 'root', 1377791765, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(74, '::1', 'root', 1377791838, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(75, '::1', 'root', 1377791858, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(76, '::1', 'root', 1377792104, 'Pressed submit button', '/srccmsths/applicant/add_applicant.php'),
(77, '::1', 'root', 1377792106, 'Query executed: <br> INSERT INTO applicant(applicant_no, learners_no, first_name, last_name, middle_name, date_of_birth, gender, year) VALUES('''', ''2011-100095'', ''Joshua'', ''Dimapilis'', ''Coralde'', ''1995-02-10'', ''M'', ''2011'')', '/srccmsths/applicant/add_applicant.php'),
(78, '::1', 'root', 1377792110, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(79, '::1', 'root', 1377792268, 'Logged out', '/srccmsths/end.php'),
(80, '::1', 'root', 1377792537, 'Logged in', '/srccmsths/login.php'),
(81, '::1', 'root', 1377792664, 'Module Access', '/srccmsths/sysadmin/listview_user.php'),
(82, '::1', 'root', 1377793091, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(83, '::1', 'root', 1377793106, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(84, '::1', 'root', 1377793127, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(85, '::1', 'root', 1377793318, 'Pressed submit button', '/srccmsths/applicant/add_applicant.php'),
(86, '::1', 'root', 1377793320, 'Query executed: <br> INSERT INTO applicant(applicant_no, learners_no, first_name, last_name, middle_name, date_of_birth, gender, year) VALUES('''', ''2011-100094'', ''Kimberly'', ''Elizondo'', ''Belda'', ''1990-06-27'', ''F'', ''2011'')', '/srccmsths/applicant/add_applicant.php'),
(87, '::1', 'root', 1377793325, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(88, '::1', 'root', 1377793879, 'Module Access', '/srccmsths/applicant/add_applicant.php'),
(89, '::1', 'root', 1377794011, 'Pressed submit button', '/srccmsths/applicant/add_applicant.php'),
(90, '::1', 'root', 1377794013, 'Query executed: <br> INSERT INTO applicant(applicant_no, learners_no, first_name, last_name, middle_name, date_of_birth, gender, year) VALUES('''', ''2011-100074'', ''Trixia Marie'', ''Urquiza'', ''Ambagan'', ''1995-01-07'', ''F'', ''2011'')', '/srccmsths/applicant/add_applicant.php'),
(91, '::1', 'root', 1377794017, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(92, '::1', 'root', 1377794217, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(93, '::1', 'root', 1377794228, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(94, '::1', 'root', 1377794239, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(95, '::1', 'root', 1377794247, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(96, '::1', 'root', 1377794371, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(97, '::1', 'root', 1377794394, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(98, '::1', 'root', 1377823059, 'Logged in', '/srccmsths/login.php'),
(99, '::1', 'root', 1377823076, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(100, '::1', 'root', 1377823872, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(101, '::1', 'root', 1377823891, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(102, '::1', 'root', 1377823910, 'Module Access', '/srccmsths/sysadmin/listview_user.php'),
(103, '::1', 'root', 1377823923, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(104, '::1', 'root', 1377823942, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(105, '::1', 'root', 1377823972, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(106, '::1', 'root', 1377823989, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(107, '::1', 'root', 1377824027, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(108, '::1', 'root', 1377824089, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(109, '::1', 'root', 1377824136, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(110, '::1', 'root', 1377824167, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(111, '::1', 'root', 1377824185, 'Module Access', '/srccmsths/requirement/add_requirement.php'),
(112, '::1', 'root', 1377824316, 'Pressed submit button', '/srccmsths/requirement/add_requirement.php'),
(113, '::1', 'root', 1377824318, 'Query executed: <br> INSERT INTO requirement(requirement_no, type, year, document) VALUES('''', ''A'', ''2013'', ''Report Card'')', '/srccmsths/requirement/add_requirement.php'),
(114, '::1', 'root', 1377824322, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(115, '::1', 'root', 1377824352, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(116, '::1', 'root', 1377824363, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(117, '::1', 'root', 1377824518, 'Logged in', '/srccmsths/login.php'),
(118, '::1', 'root', 1377824535, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(119, '::1', 'root', 1377824548, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(120, '::1', 'root', 1377824681, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(121, '::1', 'root', 1377824690, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(122, '::1', 'root', 1377824692, 'Query executed: <br> INSERT INTO applicant_has_requirement(Applicant_applicant_no, Requirement_requirement_no, submitted) VALUES(''3'', ''1'', ''1'')', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(123, '::1', 'root', 1377824696, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(124, '::1', 'root', 1377824721, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(125, '::1', 'root', 1377824739, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(126, '::1', 'root', 1377824748, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(127, '::1', 'root', 1377824756, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(128, '::1', 'root', 1377833429, 'Logged in', '/srccmsths/login.php'),
(129, '::1', 'root', 1377833723, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(130, '::1', 'root', 1377833735, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(131, '::1', 'root', 1377833746, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(132, '::1', 'root', 1377833773, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(133, '::1', 'root', 1377833784, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(134, '::1', 'root', 1377833820, 'Module Access', '/srccmsths/teacher/add_teacher.php'),
(135, '::1', 'root', 1377833908, 'Pressed submit button', '/srccmsths/teacher/add_teacher.php'),
(136, '::1', 'root', 1377833926, 'Module Access', '/srccmsths/department/listview_department.php'),
(137, '::1', 'root', 1377833944, 'Module Access', '/srccmsths/department/add_department.php'),
(138, '::1', 'root', 1377833958, 'Pressed submit button', '/srccmsths/department/add_department.php'),
(139, '::1', 'root', 1377833960, 'Query executed: <br> INSERT INTO department(dept_no, name) VALUES('''', ''IT'')', '/srccmsths/department/add_department.php'),
(140, '::1', 'root', 1377833964, 'Module Access', '/srccmsths/department/listview_department.php'),
(141, '::1', 'root', 1377833983, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(142, '::1', 'root', 1377833997, 'Module Access', '/srccmsths/teacher/add_teacher.php'),
(143, '::1', 'root', 1377834012, 'Pressed submit button', '/srccmsths/teacher/add_teacher.php'),
(144, '::1', 'root', 1377834014, 'Query executed: <br> INSERT INTO teacher(teacher_no, last_name, first_name, middle_name, Department_dept_no) VALUES('''', ''Balmes'', ''Irene'', ''Laqui'', ''1'')', '/srccmsths/teacher/add_teacher.php'),
(145, '::1', 'root', 1377834018, 'Module Access', '/srccmsths/teacher/listview_teacher.php'),
(146, '::1', 'root', 1377834048, 'Module Access', '/srccmsths/subject/listview_subject.php'),
(147, '::1', 'root', 1377834076, 'Module Access', '/srccmsths/subject/add_subject.php'),
(148, '::1', 'root', 1377834103, 'Pressed submit button', '/srccmsths/subject/add_subject.php'),
(149, '::1', 'root', 1377834105, 'Query executed: <br> INSERT INTO subject(subject_no, code, name, year) VALUES('''', ''INPROLA'', ''Introduction to Programming Languages'', ''2013'')', '/srccmsths/subject/add_subject.php'),
(150, '::1', 'root', 1377834109, 'Module Access', '/srccmsths/subject/listview_subject.php'),
(151, '::1', 'root', 1377834153, 'Module Access', '/srccmsths/sysadmin/listview_user_links.php'),
(152, '::1', 'root', 1377834202, 'Module Access', '/srccmsths/sysadmin/listview_person.php'),
(153, '::1', 'root', 1377834223, 'Module Access', '/srccmsths/sysadmin/listview_system_settings.php'),
(154, '::1', 'root', 1377834340, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(155, '::1', 'root', 1377834356, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(156, '::1', 'root', 1377834381, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(157, '::1', 'root', 1377834391, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(158, '::1', 'root', 1377834754, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(159, '::1', 'root', 1377834766, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(160, '::1', 'root', 1377834857, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(161, '::1', 'root', 1377835262, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(162, '::1', 'root', 1377835280, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(163, '::1', 'root', 1377835295, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(164, '::1', 'root', 1377835327, 'Module Access', '/srccmsths/requirement/add_requirement.php'),
(165, '::1', 'root', 1377835371, 'Pressed submit button', '/srccmsths/requirement/add_requirement.php'),
(166, '::1', 'root', 1377835373, 'Query executed: <br> INSERT INTO requirement(requirement_no, type, year, document) VALUES('''', ''B'', ''2013'', ''Certificate of Good Moral Character'')', '/srccmsths/requirement/add_requirement.php'),
(167, '::1', 'root', 1377835377, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(168, '::1', 'root', 1377835413, 'Module Access', '/srccmsths/requirement/add_requirement.php'),
(169, '::1', 'root', 1377835635, 'Pressed submit button', '/srccmsths/requirement/add_requirement.php'),
(170, '::1', 'root', 1377835637, 'Query executed: <br> INSERT INTO requirement(requirement_no, type, year, document) VALUES('''', ''A'', ''2011'', ''Diploma'')', '/srccmsths/requirement/add_requirement.php'),
(171, '::1', 'root', 1377835641, 'Module Access', '/srccmsths/requirement/listview_requirement.php'),
(172, '::1', 'root', 1377835861, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(173, '::1', 'root', 1377835904, 'Module Access', '/srccmsths/applicantrequirement/delete_applicant_has_requirement.php'),
(174, '::1', 'root', 1377835911, 'Pressed delete button', '/srccmsths/applicantrequirement/delete_applicant_has_requirement.php'),
(175, '::1', 'root', 1377835913, 'Query executed: <br> DELETE FROM applicant_has_requirement WHERE Applicant_applicant_no = ''3'' AND Requirement_requirement_no = ''1''', '/srccmsths/applicantrequirement/delete_applicant_has_requirement.php'),
(176, '::1', 'root', 1377835917, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(177, '::1', 'root', 1377835955, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(178, '::1', 'root', 1377835979, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(179, '::1', 'root', 1377835981, 'Query executed: <br> INSERT INTO applicant_has_requirement(Applicant_applicant_no, Requirement_requirement_no, submitted) VALUES(''3'', ''2'', ''1'')', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(180, '::1', 'root', 1377835985, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(181, '::1', 'root', 1377836041, 'Module Access', '/srccmsths/student/listview_student.php'),
(182, '::1', 'root', 1377836057, 'Module Access', '/srccmsths/student/add_student.php'),
(183, '::1', 'root', 1377836104, 'Pressed submit button', '/srccmsths/student/add_student.php'),
(184, '::1', 'root', 1377836110, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(185, '::1', 'root', 1377836134, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(186, '::1', 'root', 1377836141, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(187, '::1', 'root', 1377836181, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(188, '::1', 'root', 1377836217, 'Module Access', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(189, '::1', 'root', 1377836262, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(190, '::1', 'root', 1377836275, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(191, '::1', 'root', 1377836280, 'Pressed submit button', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php'),
(192, '::1', 'root', 1377836305, 'Module Access', '/srccmsths/applicant/listview_applicant.php'),
(193, '::1', 'root', 1377836407, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(194, '::1', 'root', 1377836577, 'Module Access', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php'),
(195, '::1', 'root', 1380078222, 'Logged in', '/srccmsths/login.php');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE IF NOT EXISTS `system_settings` (
  `setting` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`setting`)
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

CREATE TABLE IF NOT EXISTS `system_skins` (
  `skin_id` int(11) NOT NULL AUTO_INCREMENT,
  `skin_name` varchar(255) NOT NULL,
  `header` varchar(255) NOT NULL,
  `footer` varchar(255) NOT NULL,
  `css` varchar(255) NOT NULL,
  PRIMARY KEY (`skin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `system_skins`
--

INSERT INTO `system_skins` (`skin_id`, `skin_name`, `header`, `footer`, `css`) VALUES
(1, 'Cobalt Default', 'skins/default_header.php', 'skins/default_footer.php', 'cobalt.css');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_no` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(45) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `Department_dept_no` int(11) NOT NULL,
  PRIMARY KEY (`teacher_no`,`Department_dept_no`),
  KEY `fk_Teacher_Department1_idx` (`Department_dept_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_no`, `last_name`, `first_name`, `middle_name`, `Department_dept_no`) VALUES
(2, 'Balmes', 'Irene', 'Laqui', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_has_subject`
--

CREATE TABLE IF NOT EXISTS `teacher_has_subject` (
  `Teacher_teacher_no` int(11) NOT NULL,
  `Teacher_Department_dept_no` int(11) NOT NULL,
  `Subject_subject_no` int(11) NOT NULL,
  `year` year(4) DEFAULT NULL,
  PRIMARY KEY (`Teacher_teacher_no`,`Teacher_Department_dept_no`,`Subject_subject_no`),
  KEY `fk_Teacher_has_Subject_Subject1_idx` (`Subject_subject_no`),
  KEY `fk_Teacher_has_Subject_Teacher1_idx` (`Teacher_teacher_no`,`Teacher_Department_dept_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `iteration` int(11) NOT NULL,
  `method` varchar(255) NOT NULL,
  `person_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `skin_id` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `salt`, `iteration`, `method`, `person_id`, `user_type_id`, `skin_id`) VALUES
('root', '$2a$13$0687d81a54f9fa1c29c26u2jdx8A0b9OHdaXrXpqCtCTuNElcahDq', '0687d81a54f9fa1c29c2610089703756e31c253b', 13, 'BLOWFISH', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_links`
--

CREATE TABLE IF NOT EXISTS `user_links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `target` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `descriptive_title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  `passport_group_id` int(11) NOT NULL,
  `show_in_tasklist` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `status` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `icon` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=88 ;

--
-- Dumping data for table `user_links`
--

INSERT INTO `user_links` (`link_id`, `name`, `target`, `descriptive_title`, `description`, `passport_group_id`, `show_in_tasklist`, `status`, `icon`) VALUES
(1, 'Module Control', '/srccmsths/sysadmin/module_control.php', 'Module Control', 'Enable and/or disable system modules', 2, 'Yes', 'On', 'modulecontrol.png'),
(2, 'Set User Passports', '/srccmsths/sysadmin/set_user_passports.php', 'Set User Passports', 'Change the passport settings of system users', 2, 'Yes', 'On', 'passport.png'),
(3, 'Security Monitor', '/srccmsths/sysadmin/security_monitor.php', 'Security Monitor', 'Examine the system log', 2, 'Yes', 'On', 'security3.png'),
(4, 'Add person', '/srccmsths/sysadmin/add_person.php', 'Add person', '', 2, 'No', 'On', 'form.png'),
(5, 'Edit person', '/srccmsths/sysadmin/edit_person.php', 'Edit person', '', 2, 'No', 'On', 'form.png'),
(6, 'View person', '/srccmsths/sysadmin/listview_person.php', 'Manage person', '', 2, 'Yes', 'On', 'persons.png'),
(7, 'Delete person', '/srccmsths/sysadmin/delete_person.php', 'Delete person', '', 2, 'No', 'On', 'form.png'),
(8, 'Add user', '/srccmsths/sysadmin/add_user.php', 'Add user', '', 2, 'No', 'On', 'form.png'),
(9, 'Edit user', '/srccmsths/sysadmin/edit_user.php', 'Edit user', '', 2, 'No', 'On', 'form.png'),
(10, 'View user', '/srccmsths/sysadmin/listview_user.php', 'Manage user', '', 2, 'Yes', 'On', 'card.png'),
(11, 'Delete user', '/srccmsths/sysadmin/delete_user.php', 'Delete user', '', 2, 'No', 'On', 'form.png'),
(12, 'Add user types', '/srccmsths/sysadmin/add_user_types.php', 'Add user types', '', 2, 'No', 'On', 'form.png'),
(13, 'Edit user types', '/srccmsths/sysadmin/edit_user_types.php', 'Edit user types', '', 2, 'No', 'On', 'form.png'),
(14, 'View user types', '/srccmsths/sysadmin/listview_user_types.php', 'Manage user types', '', 2, 'Yes', 'On', 'user_type2.png'),
(15, 'Delete user types', '/srccmsths/sysadmin/delete_user_types.php', 'Delete user types', '', 2, 'No', 'On', 'form.png'),
(16, 'Add user role', '/srccmsths/sysadmin/add_user_role.php', 'Add user role', '', 2, 'No', 'On', 'form.png'),
(17, 'Edit user role', '/srccmsths/sysadmin/edit_user_role.php', 'Edit user role', '', 2, 'No', 'On', 'form.png'),
(18, 'View user role', '/srccmsths/sysadmin/listview_user_role.php', 'Manage user roles', '', 2, 'Yes', 'On', 'roles.png'),
(19, 'Delete user role', '/srccmsths/sysadmin/delete_user_role.php', 'Delete user role', '', 2, 'No', 'On', 'form.png'),
(20, 'Add system settings', '/srccmsths/sysadmin/add_system_settings.php', 'Add system settings', '', 2, 'No', 'On', 'form.png'),
(21, 'Edit system settings', '/srccmsths/sysadmin/edit_system_settings.php', 'Edit system settings', '', 2, 'No', 'On', 'form.png'),
(22, 'View system settings', '/srccmsths/sysadmin/listview_system_settings.php', 'Manage system settings', '', 2, 'Yes', 'On', 'system_settings.png'),
(23, 'Delete system settings', '/srccmsths/sysadmin/delete_system_settings.php', 'Delete system settings', '', 2, 'No', 'On', 'form.png'),
(24, 'Add user links', '/srccmsths/sysadmin/add_user_links.php', 'Add user links', '', 2, 'No', 'On', 'form.png'),
(25, 'Edit user links', '/srccmsths/sysadmin/edit_user_links.php', 'Edit user links', '', 2, 'No', 'On', 'form.png'),
(26, 'View user links', '/srccmsths/sysadmin/listview_user_links.php', 'Manage user links', '', 2, 'Yes', 'On', 'links.png'),
(27, 'Delete user links', '/srccmsths/sysadmin/delete_user_links.php', 'Delete user links', '', 2, 'No', 'On', 'form.png'),
(28, 'Add user passport groups', '/srccmsths/sysadmin/add_user_passport_groups.php', 'Add user passport groups', '', 2, 'No', 'On', 'form.png'),
(29, 'Edit user passport groups', '/srccmsths/sysadmin/edit_user_passport_groups.php', 'Edit user passport groups', '', 2, 'No', 'On', 'form.png'),
(30, 'View user passport groups', '/srccmsths/sysadmin/listview_user_passport_groups.php', 'Manage user passport groups', '', 2, 'Yes', 'On', 'passportgroup.png'),
(31, 'Delete user passport groups', '/srccmsths/sysadmin/delete_user_passport_groups.php', 'Delete user passport groups', '', 2, 'No', 'On', 'form.png'),
(32, 'Add applicant', '/srccmsths/applicant/add_applicant.php', 'Add applicant', '', 1, 'No', 'On', 'form3.png'),
(33, 'Edit applicant', '/srccmsths/applicant/edit_applicant.php', 'Edit applicant', '', 1, 'No', 'On', 'form3.png'),
(34, 'View applicant', '/srccmsths/applicant/listview_applicant.php', 'Manage applicant', '', 1, 'Yes', 'On', 'form3.png'),
(35, 'Delete applicant', '/srccmsths/applicant/delete_applicant.php', 'Delete applicant', '', 1, 'No', 'On', 'form3.png'),
(36, 'Add applicant has requirement', '/srccmsths/applicantrequirement/add_applicant_has_requirement.php', 'Add applicant has requirement', '', 1, 'No', 'On', 'form3.png'),
(37, 'Edit applicant has requirement', '/srccmsths/applicantrequirement/edit_applicant_has_requirement.php', 'Edit applicant has requirement', '', 1, 'No', 'On', 'form3.png'),
(38, 'View applicant has requirement', '/srccmsths/applicantrequirement/listview_applicant_has_requirement.php', 'Manage applicant has requirement', '', 1, 'Yes', 'On', 'form3.png'),
(39, 'Delete applicant has requirement', '/srccmsths/applicantrequirement/delete_applicant_has_requirement.php', 'Delete applicant has requirement', '', 1, 'No', 'On', 'form3.png'),
(40, 'Add department', '/srccmsths/department/add_department.php', 'Add department', '', 1, 'No', 'On', 'form3.png'),
(41, 'Edit department', '/srccmsths/department/edit_department.php', 'Edit department', '', 1, 'No', 'On', 'form3.png'),
(42, 'View department', '/srccmsths/department/listview_department.php', 'Manage department', '', 1, 'Yes', 'On', 'form3.png'),
(43, 'Delete department', '/srccmsths/department/delete_department.php', 'Delete department', '', 1, 'No', 'On', 'form3.png'),
(44, 'Add exam', '/srccmsths/exam/add_exam.php', 'Add exam', '', 1, 'No', 'On', 'form3.png'),
(45, 'Edit exam', '/srccmsths/exam/edit_exam.php', 'Edit exam', '', 1, 'No', 'On', 'form3.png'),
(46, 'View exam', '/srccmsths/exam/listview_exam.php', 'Manage exam', '', 1, 'Yes', 'On', 'form3.png'),
(47, 'Delete exam', '/srccmsths/exam/delete_exam.php', 'Delete exam', '', 1, 'No', 'On', 'form3.png'),
(48, 'Add grades', '/srccmsths/grades/add_grades.php', 'Add grades', '', 1, 'No', 'On', 'form3.png'),
(49, 'Edit grades', '/srccmsths/grades/edit_grades.php', 'Edit grades', '', 1, 'No', 'On', 'form3.png'),
(50, 'View grades', '/srccmsths/grades/listview_grades.php', 'Manage grades', '', 1, 'Yes', 'On', 'form3.png'),
(51, 'Delete grades', '/srccmsths/grades/delete_grades.php', 'Delete grades', '', 1, 'No', 'On', 'form3.png'),
(52, 'Add interviewee', '/srccmsths/interviewee/add_interviewee.php', 'Add interviewee', '', 1, 'No', 'On', 'form3.png'),
(53, 'Edit interviewee', '/srccmsths/interviewee/edit_interviewee.php', 'Edit interviewee', '', 1, 'No', 'On', 'form3.png'),
(54, 'View interviewee', '/srccmsths/interviewee/listview_interviewee.php', 'Manage interviewee', '', 1, 'Yes', 'On', 'form3.png'),
(55, 'Delete interviewee', '/srccmsths/interviewee/delete_interviewee.php', 'Delete interviewee', '', 1, 'No', 'On', 'form3.png'),
(56, 'Add requirement', '/srccmsths/requirement/add_requirement.php', 'Add requirement', '', 1, 'No', 'On', 'form3.png'),
(57, 'Edit requirement', '/srccmsths/requirement/edit_requirement.php', 'Edit requirement', '', 1, 'No', 'On', 'form3.png'),
(58, 'View requirement', '/srccmsths/requirement/listview_requirement.php', 'Manage requirement', '', 1, 'Yes', 'On', 'form3.png'),
(59, 'Delete requirement', '/srccmsths/requirement/delete_requirement.php', 'Delete requirement', '', 1, 'No', 'On', 'form3.png'),
(60, 'Add section', '/srccmsths/section/add_section.php', 'Add section', '', 1, 'No', 'On', 'form3.png'),
(61, 'Edit section', '/srccmsths/section/edit_section.php', 'Edit section', '', 1, 'No', 'On', 'form3.png'),
(62, 'View section', '/srccmsths/section/listview_section.php', 'Manage section', '', 1, 'Yes', 'On', 'form3.png'),
(63, 'Delete section', '/srccmsths/section/delete_section.php', 'Delete section', '', 1, 'No', 'On', 'form3.png'),
(64, 'Add student', '/srccmsths/student/add_student.php', 'Add student', '', 1, 'No', 'On', 'form3.png'),
(65, 'Edit student', '/srccmsths/student/edit_student.php', 'Edit student', '', 1, 'No', 'On', 'form3.png'),
(66, 'View student', '/srccmsths/student/listview_student.php', 'Manage student', '', 1, 'Yes', 'On', 'form3.png'),
(67, 'Delete student', '/srccmsths/student/delete_student.php', 'Delete student', '', 1, 'No', 'On', 'form3.png'),
(68, 'Add studentsection', '/srccmsths/studentsection/add_studentsection.php', 'Add studentsection', '', 1, 'No', 'On', 'form3.png'),
(69, 'Edit studentsection', '/srccmsths/studentsection/edit_studentsection.php', 'Edit studentsection', '', 1, 'No', 'On', 'form3.png'),
(70, 'View studentsection', '/srccmsths/studentsection/listview_studentsection.php', 'Manage studentsection', '', 1, 'Yes', 'On', 'form3.png'),
(71, 'Delete studentsection', '/srccmsths/studentsection/delete_studentsection.php', 'Delete studentsection', '', 1, 'No', 'On', 'form3.png'),
(72, 'Add subject', '/srccmsths/subject/add_subject.php', 'Add subject', '', 1, 'No', 'On', 'form3.png'),
(73, 'Edit subject', '/srccmsths/subject/edit_subject.php', 'Edit subject', '', 1, 'No', 'On', 'form3.png'),
(74, 'View subject', '/srccmsths/subject/listview_subject.php', 'Manage subject', '', 1, 'Yes', 'On', 'form3.png'),
(75, 'Delete subject', '/srccmsths/subject/delete_subject.php', 'Delete subject', '', 1, 'No', 'On', 'form3.png'),
(76, 'Add subjectstudentsection', '/srccmsths/subjectstudentsection/add_subjectstudentsection.php', 'Add subjectstudentsection', '', 1, 'No', 'On', 'form3.png'),
(77, 'Edit subjectstudentsection', '/srccmsths/subjectstudentsection/edit_subjectstudentsection.php', 'Edit subjectstudentsection', '', 1, 'No', 'On', 'form3.png'),
(78, 'View subjectstudentsection', '/srccmsths/subjectstudentsection/listview_subjectstudentsection.php', 'Manage subjectstudentsection', '', 1, 'Yes', 'On', 'form3.png'),
(79, 'Delete subjectstudentsection', '/srccmsths/subjectstudentsection/delete_subjectstudentsection.php', 'Delete subjectstudentsection', '', 1, 'No', 'On', 'form3.png'),
(80, 'Add teacher', '/srccmsths/teacher/add_teacher.php', 'Add teacher', '', 1, 'No', 'On', 'form3.png'),
(81, 'Edit teacher', '/srccmsths/teacher/edit_teacher.php', 'Edit teacher', '', 1, 'No', 'On', 'form3.png'),
(82, 'View teacher', '/srccmsths/teacher/listview_teacher.php', 'Manage teacher', '', 1, 'Yes', 'On', 'form3.png'),
(83, 'Delete teacher', '/srccmsths/teacher/delete_teacher.php', 'Delete teacher', '', 1, 'No', 'On', 'form3.png'),
(84, 'Add teacher has subject', '/srccmsths/teachersubject/add_teacher_has_subject.php', 'Add teacher has subject', '', 1, 'No', 'On', 'form3.png'),
(85, 'Edit teacher has subject', '/srccmsths/teachersubject/edit_teacher_has_subject.php', 'Edit teacher has subject', '', 1, 'No', 'On', 'form3.png'),
(86, 'View teacher has subject', '/srccmsths/teachersubject/listview_teacher_has_subject.php', 'Manage teacher has subject', '', 1, 'Yes', 'On', 'form3.png'),
(87, 'Delete teacher has subject', '/srccmsths/teachersubject/delete_teacher_has_subject.php', 'Delete teacher has subject', '', 1, 'No', 'On', 'form3.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_passport`
--

CREATE TABLE IF NOT EXISTS `user_passport` (
  `username` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `link_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`,`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `user_passport`
--

INSERT INTO `user_passport` (`username`, `link_id`) VALUES
('root', 1),
('root', 2),
('root', 3),
('root', 4),
('root', 5),
('root', 6),
('root', 7),
('root', 8),
('root', 9),
('root', 10),
('root', 11),
('root', 12),
('root', 13),
('root', 14),
('root', 15),
('root', 16),
('root', 17),
('root', 18),
('root', 19),
('root', 20),
('root', 21),
('root', 22),
('root', 23),
('root', 24),
('root', 25),
('root', 26),
('root', 27),
('root', 28),
('root', 29),
('root', 30),
('root', 31),
('root', 32),
('root', 33),
('root', 34),
('root', 35),
('root', 36),
('root', 37),
('root', 38),
('root', 39),
('root', 40),
('root', 41),
('root', 42),
('root', 43),
('root', 44),
('root', 45),
('root', 46),
('root', 47),
('root', 48),
('root', 49),
('root', 50),
('root', 51),
('root', 52),
('root', 53),
('root', 54),
('root', 55),
('root', 56),
('root', 57),
('root', 58),
('root', 59),
('root', 60),
('root', 61),
('root', 62),
('root', 63),
('root', 64),
('root', 65),
('root', 66),
('root', 67),
('root', 68),
('root', 69),
('root', 70),
('root', 71),
('root', 72),
('root', 73),
('root', 74),
('root', 75),
('root', 76),
('root', 77),
('root', 78),
('root', 79),
('root', 80),
('root', 81),
('root', 82),
('root', 83),
('root', 84),
('root', 85),
('root', 86),
('root', 87),
('root', 88),
('root', 89),
('root', 90),
('root', 91),
('root', 92),
('root', 93),
('root', 94),
('root', 95),
('root', 96),
('root', 97),
('root', 98),
('root', 99),
('root', 100),
('root', 101),
('root', 102),
('root', 103),
('root', 104),
('root', 105),
('root', 106),
('root', 107),
('root', 108),
('root', 109),
('root', 110),
('root', 111),
('root', 112),
('root', 113),
('root', 114),
('root', 115);

-- --------------------------------------------------------

--
-- Table structure for table `user_passport_groups`
--

CREATE TABLE IF NOT EXISTS `user_passport_groups` (
  `passport_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `passport_group` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`passport_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_passport_groups`
--

INSERT INTO `user_passport_groups` (`passport_group_id`, `passport_group`, `icon`) VALUES
(1, 'Default', 'blue_folder3.png'),
(2, 'Sysadmin', 'preferences-system.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_role_links`
--

CREATE TABLE IF NOT EXISTS `user_role_links` (
  `role_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `user_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) NOT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`user_type_id`, `user_type`) VALUES
(1, 'System Admin'),
(2, 'Staff');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicant_has_requirement`
--
ALTER TABLE `applicant_has_requirement`
  ADD CONSTRAINT `fk_Applicant_has_Requirement_Applicant` FOREIGN KEY (`Applicant_applicant_no`) REFERENCES `applicant` (`applicant_no`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Applicant_has_Requirement_Requirement1` FOREIGN KEY (`Requirement_requirement_no`) REFERENCES `requirement` (`requirement_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `fk_Exam_Applicant1` FOREIGN KEY (`Applicant_applicant_no`) REFERENCES `applicant` (`applicant_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_Grades_SubjectStudentSection1` FOREIGN KEY (`SubjectStudentSection_Subject_subject_no`, `SubjectStudentSection_StudentSection_Student_student_no`, `SubjectStudentSection_StudentSection_Section_section_no`) REFERENCES `subjectstudentsection` (`Subject_subject_no`, `StudentSection_Student_student_no`, `StudentSection_Section_section_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `interviewee`
--
ALTER TABLE `interviewee`
  ADD CONSTRAINT `fk_Interview_Exam1` FOREIGN KEY (`Exam_exam_no`, `Exam_Applicant_applicant_no`) REFERENCES `exam` (`exam_no`, `Applicant_applicant_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `fk_Section_Teacher1` FOREIGN KEY (`Teacher_teacher_no`, `Teacher_Department_dept_no`) REFERENCES `teacher` (`teacher_no`, `Department_dept_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_Student_Interviewee1` FOREIGN KEY (`Interviewee_interview_no`, `Interviewee_Exam_exam_no`, `Interviewee_Exam_Applicant_applicant_no`) REFERENCES `interviewee` (`interview_no`, `Exam_exam_no`, `Exam_Applicant_applicant_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `studentsection`
--
ALTER TABLE `studentsection`
  ADD CONSTRAINT `fk_Student_has_Section_Section1` FOREIGN KEY (`Section_section_no`) REFERENCES `section` (`section_no`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Student_has_Section_Student1` FOREIGN KEY (`Student_student_no`) REFERENCES `student` (`student_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subjectstudentsection`
--
ALTER TABLE `subjectstudentsection`
  ADD CONSTRAINT `fk_SubjectStudentSection_StudentSection1` FOREIGN KEY (`StudentSection_Student_student_no`, `StudentSection_Section_section_no`) REFERENCES `studentsection` (`Student_student_no`, `Section_section_no`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Subject_has_Student_has_Section_Subject1` FOREIGN KEY (`Subject_subject_no`) REFERENCES `subject` (`subject_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_Teacher_Department1` FOREIGN KEY (`Department_dept_no`) REFERENCES `department` (`dept_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `teacher_has_subject`
--
ALTER TABLE `teacher_has_subject`
  ADD CONSTRAINT `fk_Teacher_has_Subject_Subject1` FOREIGN KEY (`Subject_subject_no`) REFERENCES `subject` (`subject_no`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Teacher_has_Subject_Teacher1` FOREIGN KEY (`Teacher_teacher_no`, `Teacher_Department_dept_no`) REFERENCES `teacher` (`teacher_no`, `Department_dept_no`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
