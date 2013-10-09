<?php

//This file creates the standard application components needed to make a complete
//working application (from login to logout) using the generated classes and modules

function createStdAppComponents($path_array)
{
    extract($path_array);

    //*********************************************************	
    //FIRST ORDER OF BUSINESS: GENERATE THE GLOBAL CONFIG FILE
    //*********************************************************

    //Step 1: Set the login page, home page, and target page. We need the base directory for these.
    $login_page = '/' . $Base_Directory . '/login.php';
    $home_page = '/' . $Base_Directory . '/main.php';
    $target_page = '/' . $Base_Directory . '/start.php';

    //Step 2: Set the full path to the base directory. We just use the SCV2 path variable and use substr() to remove
    //the Cobalt directory
    $full_path_to_webroot = substr($SCV2_path,0,-8);
    $full_path_to_base_directory = $full_path_to_webroot . '/' . $Base_Directory;

    //Step 3: Create the config file contents.	
    $global_config_file =<<<EOD
<?php
\$baseDirectory = '$Base_Directory';
define("BASE_DIRECTORY", \$baseDirectory);

\$fullPathTocore = dirname(__FILE__) . "/";
define("FULLPATH_CORE", \$fullPathTocore);

\$loginPage = '$login_page';
define("LOGIN_PAGE", \$loginPage);

\$homePage = '$home_page';
define("HOME_PAGE", \$homePage);

\$targetPage = '$target_page';
define("INDEX_TARGET", \$targetPage);

\$tmpHyperlinkPath = '/' . \$baseDirectory . '/tmp';
define("TMP_HYPERLINK_PATH", \$tmpHyperlinkPath);

\$tmpDirectory = '$full_path_to_webroot' . \$tmpHyperlinkPath;
define("TMP_DIRECTORY", \$tmpDirectory);

\$mb_encoding = 'utf-8';
define("MULTI_BYTE_ENCODING", \$mb_encoding);

/*
//Uncomment this only if you want to use file-based logging
\$logFile = '$full_path_to_base_directory/core/system_log.php';
define("LOG_FILE", \$logFile);
*/

\$debug_mode = TRUE;
define("DEBUG_MODE", \$debug_mode);
EOD;

    //Step 4: Write the config file. We need the project core path for this.

    $filename = $project_core_path .  'global_config.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $global_config_file);
    fclose($newfile);
    chmod($filename, 0777);


    //***********************************************************************************************
    //CREATE THE SYSTEM CORE INITIALIZATION FILE (hahaha, this sounds cooler than it actually is )
    //***********************************************************************************************

    $init_core_contents = file_get_contents($SCV2_path . '/Generator/Standard_Application_Components/core/cobalt_core.php');
    $filename = $project_path .  'core/cobalt_core.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $init_core_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //******************************************************************************
    //CREATE THE LOGIN PAGE - WE NEED THE BASE DIRECTORY FOR THE SUCCESS REDIRECTION
    //                                      AND WE ALSO NEED THE PROJECT DESCRIPTION
    //******************************************************************************

    $mysqli = connect_db();
    $mysqli->real_query("SELECT Project_Description FROM `project` WHERE Project_ID='$_SESSION[Project_ID]'");
    if($result = $mysqli->use_result())
    {
        $data = $result->fetch_assoc();
        extract($data);
        $Project_Description = nl2br($Project_Description);
    }
    else die($mysqli->error);
    $result->close();


$login_contents=<<<EOD
<?php
\$start = microtime(true);
define('PROCESS_START_TIME', \$start);
session_start();

if(\$_SESSION['logged'] == 'Logged')
{
    header("location: main.php");
}

require_once 'core/html_class.php';
require_once 'core/data_abstraction_class.php';
require_once 'core/validation_class.php';
require_once 'core/cobalt_core.php';
require_once 'core/global_config.php';

if(\$_POST['form_key'] === \$_SESSION['cobalt_form_keys'][\$_SERVER['PHP_SELF']])
{
    if(\$_POST['Submit'])
    {
        \$error_message = '';
        extract(\$_POST);

        require_once 'core/cobalt_core.php';
        init_cobalt();
        \$data_con = new data_abstraction;
        \$mysqli = \$data_con->connect_db();
        \$clean_username = \$mysqli->real_escape_string(\$username);
        \$clean_password = cobalt_password_hash('RECREATE', \$mysqli->real_escape_string(\$password), \$clean_username);
        \$mysqli->real_query("SELECT `username`, `password`, `skin_id` FROM `user` WHERE `username`='\$clean_username' AND `password`='\$clean_password'");
        if(\$result = \$mysqli->use_result())
        {
            if(\$data = \$result->fetch_assoc())
            {
                \$result->close();
                extract(\$data);
                
                \$data_con = new data_abstraction;
                \$data_con->set_fields('skin_name, header, footer, css');
                \$data_con->set_table('system_skins');
                \$data_con->set_where("skin_id='\$skin_id'");
                \$result = \$data_con->make_query();
                \$numrows = \$data_con->num_rows;
                \$data_con->close_db();

                \$_SESSION['logged'] = 'Logged';
                \$_SESSION['user'] = \$username;
                
                if(\$numrows==1)
                {
                    \$data = \$result->fetch_assoc();
                    extract(\$data);
                    \$_SESSION['header'] = \$header;
                    \$_SESSION['footer'] = \$footer;
                    \$_SESSION['skin'] = \$skin_name;
                    \$_SESSION['css'] = \$css;
                }

                log_action('Logged in', \$_SERVER[PHP_SELF]);
                header("location: start.php");
                exit();
            }
            else \$error_message = "Check username and password.";
        }
        else die(\$mysqli->error);
        \$data_con->close_db();
    }
}

\$html = new html;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <script language="JavaScript" type="text/javascript">
    if (top.location != location)
    {
        top.location.href = document.location.href ;
    }
    </script>
    <title> $_SESSION[Project_Name] - Powered by Cobalt</title>
    <link href="css/login.css" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.getElementById('username').focus();">
<br /><br /><br /><br />
<br /><br /><br /><br />
<?php
echo '<form method="POST" action="' . \$_SERVER[PHP_SELF] . '">';
\$form_key = generate_token();
\$form_identifier = \$_SERVER['PHP_SELF'];
\$_SESSION['cobalt_form_keys'][\$form_identifier] = \$form_key;
echo '<input type=hidden name=form_key value="' . \$form_key .'">';
?>
<div class="vertical_center">
    <div class="container">
    <fieldset class="container">
        <fieldset class="top">
                $_SESSION[Project_Name]
        </fieldset>
        <fieldset class="middle">
            <div class="container_error">
                <?php \$html->display_errors(\$error_message);?>
            </div>

            <table border="0" width="100%" cellspacing="1">
            <tr>
                <td align="left">&nbsp;Username: </td>
            </tr>
            <tr>
                <td align="center">
                    <?php \$html->draw_text_field('','username',FALSE,'text',FALSE, 'id="username" size="37"'); ?>
                </td>
            </tr>
            <tr>
                <td align="left"><br></td>
            </tr>
            <tr>
                <td align="left">&nbsp;Password: </td>
            </tr>
            <tr>
                <td align="center">
                    <?php \$html->draw_text_field('','password',FALSE,'password',FALSE,'size="37"'); ?>
                </td>
            </tr>
            </table>
        </fieldset>
        <fieldset class="bottom">
            <input type=submit value="LOG IN" name="Submit">
        </fieldset>
    </fieldset>
    </div>
</div>
</form>
</body>
</html>
EOD;


    $filename = $project_path .  'login.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $login_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //*********************************************************************************************************
    //CREATE THE DEFAULT HEADER: WE NEED THE BASE DIRECTORY FOR THE CSS PATH, AND THE PROJECT NAME AS THE TITLE
    //*********************************************************************************************************

    //First, let's make sure the Skins folder exists.
    $skins_folder = $project_core_path . 'skins/';
    if(!file_exists($skins_folder)) mkdir(substr($skins_folder,0,-1), 0777);
    chmod(substr($skins_folder,0,-1),0777);

    //Create the index.php file for the Skins folder.
    createDirectoryIndex($skins_folder);


$header_contents=<<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title> $_SESSION[Project_Name] - Powered by Cobalt</title>
    <link href="/$Base_Directory/css/<?php echo \$_SESSION['css'];?>" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
<body>
EOD;

    $filename = $project_path .  'core/skins/default_header.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $header_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //***********************************************************************************************************
    //CREATE THE PRINTABLE HEADER: WE NEED THE BASE DIRECTORY FOR THE CSS PATH, AND THE PROJECT NAME AS THE TITLE
    //***********************************************************************************************************

$header_contents=<<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title> $_SESSION[Project_Name] - Powered by Cobalt</title>
    <link href="/$Base_Directory/css/report.css" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
<body>
EOD;

    $filename = $project_path .  'core/skins/printview_header.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $header_contents);
    fclose($newfile);
    chmod($filename, 0777);

    //***********************************************************************************************************
    //CREATE THE FRAME HEADER: WE NEED THE BASE DIRECTORY FOR THE CSS PATH, AND THE PROJECT NAME AS THE TITLE
    //***********************************************************************************************************

$header_contents=<<<EOD
<?php
require_once 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title> $_SESSION[Project_Name] - Powered by Cobalt</title>
    <link href="/$Base_Directory/css/<?php echo \$_SESSION['css'];?>" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
    <body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="HeaderBanner">
    <tr>
        <td> $_SESSION[Project_Name]</td>
    </tr>
</table>

<?php require_once 'core/header_menu.php';
EOD;

    $filename = $project_path .  'header.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $header_contents);
    fclose($newfile);
    chmod($filename, 0777);



    //*************************************************************
    //CREATE THE HEADER MENU: WE NEED THE BASE DIRECTORY LINK PATHS
    //*************************************************************

$header_contents=<<<EOD
<div class='HeaderMenu'>
    <table width="100%">
    <tr>
        <td class="menu" width="100" align="left"> <a href='#' class="menu">  HELP  </a> </td>
        <td class="menu" width="100" align="left"> <a target="content_frame" href='/$Base_Directory/main.php' class="menu">  HOME  </a> </td>
        <td class="menu" width="100" align="left"> <a target="content_frame" href='/$Base_Directory/change_password.php' class="menu">  PASSWORD  </a> </td>
        <td class="menu" width="100" align="left"> <a target="content_frame" href='/$Base_Directory/change_skin.php' class="menu">  SKIN  </a> </td>
        <td class="menu" width="100" align="left"> <a target="content_frame" href='/$Base_Directory/about.php' class="menu">  ABOUT  </a> </td>
        <td align="right"> You are logged in as <span class="text-info"><?php echo \$_SESSION['user'];?></span></td>
        <td class="menu" width="75"> <a target="_parent" onClick="return confirm('Are you sure you wish to logout?')" href='/$Base_Directory/end.php' class="menu">  [LOGOUT]  </a> </td>
    </tr>
    </table>
</div>
EOD;

    $filename = $project_path .  'core/header_menu.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $header_contents);
    fclose($newfile);
    chmod($filename, 0777);



    //************************************************************************
    //CREATE THE DEFAULT FOOTER: WE NEED THE BASE DIRECTORY FOR THE IMAGE PATH
    //************************************************************************

    $footer_contents=<<<EOD
<?php
// Uncomment the code below if you want resource usage reporting
/*
\$cpu_time = microtime(true) - PROCESS_START_TIME;
\$ram_used = memory_get_usage() / 1024;
\$max_ram_used = memory_get_peak_usage() / 1024;

echo '<hr>';
echo '<table align=center border=1 cellpadding=1 cellspacing=1 class=printTextSmall>'
    ."<tr><td><img src=/$Base_Directory/images/cobalt_poweredby.png></td>"
    .'    <td> CPU Time: ' . \$cpu_time . ' seconds <br>'
    .'         RAM usage: ' . number_format(\$ram_used,0,'.',',') .'KB <br> '
    .'         Max RAM usage: ' . number_format(\$max_ram_used,0,'.',',') .'KB </td></tr>'
    .'</table>';
*/
?>
</body>
</html>
EOD;

    $filename = $project_path .  'core/skins/default_footer.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $footer_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //*************************************************************
    //CREATE THE PRINTABLE FOOTER: DON'T NEED ANYTHING AT ALL! 
    //*************************************************************

    $footer_contents=<<<EOD
</body>
</html>
EOD;

    $filename = $project_path .  'core/skins/printview_footer.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $footer_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //***********************************************************************************************************
    //CREATE THE FRAMESET PAGE: WE NEED THE BASE DIRECTORY FOR THE CSS PATH, AND THE PROJECT NAME AS THE TITLE
    //***********************************************************************************************************

$header_contents=<<<EOD
<?php
require_once 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title> Project: $_SESSION[Project_Name] - Powered by Cobalt</title>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
<frameset rows="120,*" frameborder="1">
    <frame src="header.php" name="header_frame" frameborder="1">
    <frameset cols="200,*" frameborder="1">
        <frame frameborder="1" src="menus.php" name="menu_frame">
        <frame frameborder="1" src="main.php" name="content_frame">
    </frameset>
</frameset>
EOD;

    $filename = $project_path .  'start.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $header_contents);
    fclose($newfile);
    chmod($filename, 0777);

    //****************************************************************************************
    //CREATE THE CHANGE PASSWORD MODULE: WE NEED THE BASE DIRECTORY FOR THE CANCEL REDIRECTION FIXME: not true anymore, fix this if you've got time.
    //****************************************************************************************

    $change_password_contents=<<<EOD
<?php
require 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');

if(xsrf_guard())
{
    if(\$_POST['cancel'])
    {
        header("location: main.php");
        exit();
    }

EOD;
    $body = file_get_contents($SCV2_path . '/Generator/Standard_Application_Components/change_password.php');
    $change_password_contents.=$body;

    $filename = $project_path .  'change_password.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $change_password_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //************************************************************************************
    //CREATE THE CHANGE SKIN MODULE: WE NEED THE BASE DIRECTORY FOR THE CANCEL REDIRECTION FIXME: not true anymore, fix this if you've got time
    //************************************************************************************


    $change_skin_contents=<<<EOD
<?php
require 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');

if(xsrf_guard())
{
    if(\$_POST['cancel'])
    {
        header("location: main.php");
        exit();
    }

EOD;
    $body = file_get_contents($SCV2_path . '/Generator/Standard_Application_Components/change_skin.php');
    $change_skin_contents.=$body;

    $filename = $project_path .  'change_skin.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $change_skin_contents);
    fclose($newfile);
    chmod($filename, 0777);




    //********************************************************************
    //CREATE THE ABOUT PAGE: WE NEED THE PROJECT NAME AND DESCRIPTION HERE
    //********************************************************************

$about_contents=<<<EODD
<?php
//******************************************************************
//This file was generated by Cobalt, a rapid application development 
//framework developed by JV Roig (jvroig@jvroig.com).
//
//Cobalt on the web: http://cobalt.jvroig.com
//******************************************************************
require_once 'path.php';
init_cobalt('ALLOW_ALL');

if(!isset(\$_POST['form_key'])) log_action("Module Access", \$_SERVER['PHP_SELF']);

\$html = new html;
\$html->draw_header('About PROJECT $_SESSION[Project_Name]', \$message, \$message_type);
\$msg=<<<EOD
$Project_Description 
<br /><br /><b>PROJECT $_SESSION[Project_Name] is powered by Cobalt</b>
EOD;
\$html->display_info(\$msg);

\$html->draw_page_title('About Cobalt');
\$msg=<<<EOD
Cobalt is a web-based code generator and framework using PHP and Oracle Database created by JV Roig.
It makes web-based systems maintainable, scalable, secure and efficient, and makes the life of developers a lot easier. <br><br>

<a href="http://cobalt.jvroig.com/download.php" target="_blank">Download Cobalt</a> |
<a href="http://cobalt.jvroig.com/faq.php" target="_blank">Cobalt FAQ</a>
EOD;
\$html->display_message(\$msg);
\$html->draw_footer();
EODD;

    $filename = $project_path .  'about.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $about_contents);
    fclose($newfile);
    chmod($filename, 0777);


    //********************************************************************
    //CREATE THE LOGOUT CLEANUP PAGE: WE SIMPLY NEED THE PROJECT NAME HERE
    //********************************************************************

$end_contents=<<<EOD
<?php
require 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');

//Create a log entry that user logged out.
log_action('Logged out', \$_SERVER[PHP_SELF]);

/********** Start of session cleanup. **********/
//First, unset all session variables.

\$_SESSION = array();

//Second, delete the session cookie.
if(isset(\$_COOKIE[session_name()]))
{
    setcookie (session_name(), "", time() - 86400);
}

//Third and last step, destroy the session.
session_destroy();
/********** End of session cleanup. **********/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head><title> Logged out</title>
<link href="css/cobalt.css" rel="stylesheet" type="text/css">
</head>
<body>
<br><br><br><br><br>
<div class="container_mid_large">
<?php
\$message = "You have been logged out of the system. <br><br>
            All session files and cookies containing your personal and 
            account information that you used during this session have
            been deleted successfully. <br><br>
            <a href=\"/$Base_Directory/index.php\"> Click here</a> to return to Project $_SESSION[Project_Name]";

\$html_writer = new html;
\$html_writer->display_info(\$message);
?>
</div>
</body>
</html>
EOD;

    $filename = $project_path .  'end.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");

    fwrite($newfile, $end_contents);
    fclose($newfile);
    chmod($filename, 0777);




    //**********************************************************
    //NEXT: CREATE THE REMAINING STANDARD APPLICATION COMPONENTS
    //**********************************************************

    //This is actually easier than it sounds, because the remaining components don't have any configuration left to setup.
    //All we have to do is copy them from our source files to their respective destination.

    //First, make sure the 'css', 'images', 'icons' and 'javascript' folders are created.
    $css_folder = $project_path . "css/";
    if(!file_exists($css_folder)) mkdir(substr($css_folder,0,-1), 0777);
    chmod($css_folder, 0777);

    $images_folder = $project_path . "images/";
    if(!file_exists($images_folder)) mkdir(substr($images_folder,0,-1), 0777);
    chmod($images_folder, 0777);

    $icons_folder = $project_path . "images/icons/";
    if(!file_exists($icons_folder)) mkdir(substr($icons_folder,0,-1), 0777);
    chmod($icons_folder, 0777);

    $javascript_folder = $project_path . "javascript/";
    if(!file_exists($javascript_folder)) mkdir(substr($javascript_folder,0,-1), 0777);
    chmod($javascript_folder, 0777);

    //Create the index.php file for the css, Images, icons and javascript folders
    createDirectoryIndex($images_folder);
    createDirectoryIndex($icons_folder);
    createDirectoryIndex($css_folder);
    createDirectoryIndex($javascript_folder);

    function copyStdAppComponent($file, $SCV2_path, $project_path)
    {
        $source = $SCV2_path . 'Generator/Standard_Application_Components/'. $file;
        $destination = $project_path . $file;

        if (file_exists($source)) copy($source, $destination);
        else echo "The source file '$source' does not exist";

        chmod($destination, 0777);
    }

    copyStdAppComponent('main.php',$SCV2_path, $project_path);
    copyStdAppComponent('menus.php',$SCV2_path, $project_path);
    copyStdAppComponent('images/head-banner.jpg', $SCV2_path, $project_path);
    copyStdAppComponent('images/delete.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/edit.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/view.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/announcement.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/announcement2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/biz.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/biz_nature.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/biz_type.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder4.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder5.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder6.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder7.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder8.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/blue_folder9.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/car.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/card.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/clinic.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/Community.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/complainant.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/complaint.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/consultation.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/directory.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/doc.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/doc2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/download.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/download2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/download3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/dtr.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/education.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/form.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/form2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/form3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/group_bullet.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/group_bullet2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/group_bullet3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/group_bullet4.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/home.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/info.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/info1.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/links.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/Lock.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/logout.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/meds.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/modern_clock_chris_kemps_01.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/modulecontrol.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/MyDTR.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/occupation.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/ok.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/online_application.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/online_approval.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/passport.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/passport2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/passportgroup.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/persons.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/persons2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/persons3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/pitr_red_menu_icon_set_1_small.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/preferences-system.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/profile.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/red_folder.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/reports.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/reports2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/reports3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/reports4.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/reports5.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/respondent.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/roles.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/scales-of-justice-glass-effect.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/scroll.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/security.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/security2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/security3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/streetsign.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/system_settings.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/tip.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/tip2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/upload.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/upload_red.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/user_type.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/user_type2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/users.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/warn1.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/warn2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/yellow_folder.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/yellow_folder2.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/yellow_folder3.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/yellow_folder4.png', $SCV2_path, $project_path);
    copyStdAppComponent('images/icons/yellow_folder5.png', $SCV2_path, $project_path);
    copyStdAppComponent('css/cobalt.css', $SCV2_path, $project_path);
    copyStdAppComponent('css/login.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Default.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Default_blue.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Default_dark.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Default_red.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Emerald.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Pink.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Ruby.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Sapphire.css', $SCV2_path, $project_path);
//  copyStdAppComponent('css/Space.css', $SCV2_path, $project_path);
    copyStdAppComponent('css/report.css', $SCV2_path, $project_path);
    copyStdAppComponent('javascript/submitenter.php', $SCV2_path, $project_path);
    copyStdAppComponent('javascript/highlightrow.php', $SCV2_path, $project_path);
    copyStdAppComponent('cruizer_base.sql', $SCV2_path, $project_path);
}
