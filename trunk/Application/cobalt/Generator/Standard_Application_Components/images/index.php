<?php	
//***SCV2 Copyright ***************************************************
//This file was generated by SCV2, a rapid application development tool 
//for PHP/MySQL developed by JV Roig (jv_crow_spirit@yahoo.com).
//
//YOU MAY FREELY USE, MODIFY, AND REDISTRIBUTE THIS FILE!
//
//You may use, redistribute, and make any changes to the following
//code AS LONG AS YOU KEEP THIS COPYRIGHT NOTICE INTACT, in each file
//created using SCV2, as well as in any documentation derived from,
//or describing, modules or applications created using SCV2.
//*********************************************************************
require_once 'path.php';
init_SCV2('ALLOW_ALL');

if(!isset($_POST['formKey'])) logAction("Module Access", $_SERVER['PHP_SELF']);
header("location: " . INDEX_TARGET);
