<?php
require_once 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title> SRCCMSTHS Enrollment and Grading System - Powered by Cobalt</title>
    <link href="/srccmsths/css/<?php echo $_SESSION['css'];?>" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
    <body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="HeaderBanner">
    <tr>
        <td> SRCCMSTHS Enrollment and Grading System</td>
    </tr>
</table>

<?php require_once 'core/header_menu.php';