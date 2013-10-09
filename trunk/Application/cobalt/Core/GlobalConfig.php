<?php
/*
 * GlobalConfig.php
 * FRIDAY, November 28, 2006 
 * SCV2 global configuration file.
 * JV Roig
 */

$fullPathToCore = dirname(__FILE__) . "/";
define("FULLPATH_CORE",	$fullPathToCore);

$loginPage = '/cobalt/index.php';
define("LOGIN_PAGE", $loginPage);

$homePage = '/cobalt/main.php';
define("HOME_PAGE", $homePage);

$targetPage = $homePage;
define("INDEX_TARGET", $targetPage);

$showSQL = TRUE;
define("SHOW_SQL_ERRORS", $showSQL);
?>
