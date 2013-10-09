<?php
$baseDirectory = 'srccmsths';
define("BASE_DIRECTORY", $baseDirectory);

$fullPathTocore = dirname(__FILE__) . "/";
define("FULLPATH_CORE", $fullPathTocore);

$loginPage = '/srccmsths/login.php';
define("LOGIN_PAGE", $loginPage);

$homePage = '/srccmsths/main.php';
define("HOME_PAGE", $homePage);

$targetPage = '/srccmsths/start.php';
define("INDEX_TARGET", $targetPage);

$tmpHyperlinkPath = '/' . $baseDirectory . '/tmp';
define("TMP_HYPERLINK_PATH", $tmpHyperlinkPath);

$tmpDirectory = 'C:\xampp\htdocs' . $tmpHyperlinkPath;
define("TMP_DIRECTORY", $tmpDirectory);

$mb_encoding = 'utf-8';
define("MULTI_BYTE_ENCODING", $mb_encoding);

/*
//Uncomment this only if you want to use file-based logging
$logFile = 'C:\xampp\htdocs/srccmsths/core/system_log.php';
define("LOG_FILE", $logFile);
*/

$debug_mode = TRUE;
define("DEBUG_MODE", $debug_mode);