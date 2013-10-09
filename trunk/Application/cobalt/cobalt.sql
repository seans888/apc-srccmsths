-- phpMyAdmin SQL Dump
-- version 3.4.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2011 at 01:06 AM
-- Server version: 5.1.58
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cobalt`
--

-- --------------------------------------------------------

--
-- Table structure for table `database_connection`
--

CREATE TABLE IF NOT EXISTS `database_connection` (
  `DB_Connection_ID` smallint(3) NOT NULL AUTO_INCREMENT,
  `Project_ID` smallint(6) NOT NULL,
  `DB_Connection_Name` varchar(255) NOT NULL,
  `Hostname` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Database` varchar(255) NOT NULL,
  PRIMARY KEY (`DB_Connection_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `Page_ID` tinyint(2) NOT NULL AUTO_INCREMENT,
  `Page_Name` varchar(255) NOT NULL,
  `Generator` varchar(255) NOT NULL,
  `Description` tinytext NOT NULL,
  PRIMARY KEY (`Page_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`Page_ID`, `Page_Name`, `Generator`, `Description`) VALUES
(1, 'Add1', 'Add1.php', 'Standard input form'),
(2, 'Edit1', 'Edit1.php', 'Standard edit form'),
(3, 'DetailView1', 'DetailView1.php', 'Detail View of a record'),
(4, 'ListView', 'ListView1.php', 'List View of a table'),
(5, 'Delete1', 'Delete1.php', 'Standard record deletion page'),
(6, 'CSVExport1', 'CSVExport1.php', 'Standard export data to CSV module');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `Project_ID` smallint(3) NOT NULL,
  `Project_Name` varchar(255) NOT NULL,
  `Client_Name` varchar(255) NOT NULL,
  `Project_Description` text NOT NULL,
  `Base_Directory` varchar(255) NOT NULL,
  `Database_Connection_ID` smallint(6) NOT NULL,
  PRIMARY KEY (`Project_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `security_log`
--

CREATE TABLE IF NOT EXISTS `security_log` (
  `Log_ID` smallint(1) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `DateTime` int(12) NOT NULL,
  `Action` tinytext NOT NULL,
  `Module` varchar(255) NOT NULL,
  `Project_Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Log_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE IF NOT EXISTS `table` (
  `Table_ID` mediumint(5) NOT NULL,
  `Project_ID` smallint(3) NOT NULL,
  `DB_Connection_ID` smallint(3) NOT NULL,
  `Table_Name` varchar(255) NOT NULL,
  `Remarks` tinytext NOT NULL,
  PRIMARY KEY (`Table_ID`),
  KEY `DB_Connection_ID` (`DB_Connection_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Cool';

-- --------------------------------------------------------

--
-- Table structure for table `table_fields`
--

CREATE TABLE IF NOT EXISTS `table_fields` (
  `Field_ID` mediumint(6) NOT NULL,
  `Table_ID` smallint(3) NOT NULL,
  `Field_Name` varchar(255) NOT NULL,
  `Data_Type` varchar(255) NOT NULL,
  `Length` smallint(3) NOT NULL,
  `Attribute` varchar(255) NOT NULL,
  `Control_Type` varchar(255) NOT NULL,
  `Label` varchar(255) NOT NULL,
  `In_Listview` varchar(255) NOT NULL,
  PRIMARY KEY (`Field_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_book_list`
--

CREATE TABLE IF NOT EXISTS `table_fields_book_list` (
  `Field_ID` mediumint(6) NOT NULL,
  `Book_List_Generator` varchar(255) NOT NULL,
  PRIMARY KEY (`Field_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_list`
--

CREATE TABLE IF NOT EXISTS `table_fields_list` (
  `Field_ID` int(6) NOT NULL,
  `List_ID` tinyint(3) NOT NULL,
  PRIMARY KEY (`Field_ID`,`List_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_list_source_select`
--

CREATE TABLE IF NOT EXISTS `table_fields_list_source_select` (
  `Field_ID` mediumint(6) NOT NULL,
  `Select_Field_ID` mediumint(6) NOT NULL,
  `Display` varchar(255) NOT NULL,
  PRIMARY KEY (`Field_ID`,`Select_Field_ID`,`Display`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_list_source_where`
--

CREATE TABLE IF NOT EXISTS `table_fields_list_source_where` (
  `Field_ID` mediumint(6) NOT NULL,
  `Where_Field_ID` mediumint(6) NOT NULL,
  `Where_Field_Operand` varchar(255) NOT NULL,
  `Where_Field_Value` varchar(255) NOT NULL,
  `Where_Field_Connector` varchar(255) NOT NULL,
  PRIMARY KEY (`Field_ID`,`Where_Field_ID`,`Where_Field_Operand`,`Where_Field_Value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_predefined_list`
--

CREATE TABLE IF NOT EXISTS `table_fields_predefined_list` (
  `List_ID` smallint(3) NOT NULL,
  `List_Name` varchar(255) NOT NULL,
  `Remarks` tinytext NOT NULL,
  PRIMARY KEY (`List_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_fields_predefined_list`
--

INSERT INTO `table_fields_predefined_list` (`List_ID`, `List_Name`, `Remarks`) VALUES
(1, 'Sex', 'Standard M/F list'),
(2, 'YorN', 'Y or N'),
(3, 'TRUE-FALSE', 'True or False'),
(4, 'On-Off', 'On or Off'),
(5, 'Yes-No', 'Yes or No');

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_predefined_list_items`
--

CREATE TABLE IF NOT EXISTS `table_fields_predefined_list_items` (
  `List_ID` smallint(3) NOT NULL,
  `Number` tinyint(4) NOT NULL,
  `List_Item` varchar(255) NOT NULL,
  PRIMARY KEY (`List_ID`,`Number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_fields_predefined_list_items`
--

INSERT INTO `table_fields_predefined_list_items` (`List_ID`, `Number`, `List_Item`) VALUES
(1, 1, 'Male'),
(1, 2, 'Female'),
(2, 1, 'Y'),
(2, 2, 'N'),
(3, 1, 'TRUE'),
(3, 2, 'FALSE'),
(4, 1, 'On'),
(4, 2, 'Off'),
(5, 1, 'Yes'),
(5, 2, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `table_fields_secondary_validation`
--

CREATE TABLE IF NOT EXISTS `table_fields_secondary_validation` (
  `Field_ID` mediumint(6) NOT NULL,
  `Validation_Routine` varchar(255) NOT NULL,
  PRIMARY KEY (`Field_ID`,`Validation_Routine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_pages`
--

CREATE TABLE IF NOT EXISTS `table_pages` (
  `Table_ID` smallint(3) NOT NULL,
  `Page_ID` smallint(3) NOT NULL,
  `Path_Filename` varchar(255) NOT NULL,
  PRIMARY KEY (`Table_ID`,`Page_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_relations`
--

CREATE TABLE IF NOT EXISTS `table_relations` (
  `Relation_ID` mediumint(9) NOT NULL,
  `Project_ID` smallint(6) NOT NULL,
  `Relation` varchar(255) NOT NULL,
  `Parent_Field_ID` mediumint(9) NOT NULL,
  `Child_Field_ID` mediumint(9) NOT NULL,
  `Parent2_Field_ID` mediumint(9) NOT NULL,
  `Label` varchar(255) NOT NULL,
  `Child_Field_Subtext` varchar(255) NOT NULL,
  PRIMARY KEY (`Relation_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
