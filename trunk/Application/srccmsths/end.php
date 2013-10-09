<?php
require 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');

//Create a log entry that user logged out.
log_action('Logged out', $_SERVER[PHP_SELF]);

/********** Start of session cleanup. **********/
//First, unset all session variables.

$_SESSION = array();

//Second, delete the session cookie.
if(isset($_COOKIE[session_name()]))
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
$message = "You have been logged out of the system. <br><br>
            All session files and cookies containing your personal and 
            account information that you used during this session have
            been deleted successfully. <br><br>
            <a href=\"/srccmsths/index.php\"> Click here</a> to return to Project SRCCMSTHS Enrollment and Grading System";

$html_writer = new html;
$html_writer->display_info($message);
?>
</div>
</body>
</html>