<?php
$start = microtime(true);
define('PROCESS_START_TIME', $start);
session_start();

if($_SESSION['logged'] == 'Logged')
{
    header("location: main.php");
}

require_once 'core/html_class.php';
require_once 'core/data_abstraction_class.php';
require_once 'core/validation_class.php';
require_once 'core/cobalt_core.php';
require_once 'core/global_config.php';

if($_POST['form_key'] === $_SESSION['cobalt_form_keys'][$_SERVER['PHP_SELF']])
{
    if($_POST['Submit'])
    {
        $error_message = '';
        extract($_POST);

        require_once 'core/cobalt_core.php';
        init_cobalt();
        $data_con = new data_abstraction;
        $mysqli = $data_con->connect_db();
        $clean_username = $mysqli->real_escape_string($username);
        $clean_password = cobalt_password_hash('RECREATE', $mysqli->real_escape_string($password), $clean_username);
        $mysqli->real_query("SELECT `username`, `password`, `skin_id` FROM `user` WHERE `username`='$clean_username' AND `password`='$clean_password'");
        if($result = $mysqli->use_result())
        {
            if($data = $result->fetch_assoc())
            {
                $result->close();
                extract($data);
                
                $data_con = new data_abstraction;
                $data_con->set_fields('skin_name, header, footer, css');
                $data_con->set_table('system_skins');
                $data_con->set_where("skin_id='$skin_id'");
                $result = $data_con->make_query();
                $numrows = $data_con->num_rows;
                $data_con->close_db();

                $_SESSION['logged'] = 'Logged';
                $_SESSION['user'] = $username;
                
                if($numrows==1)
                {
                    $data = $result->fetch_assoc();
                    extract($data);
                    $_SESSION['header'] = $header;
                    $_SESSION['footer'] = $footer;
                    $_SESSION['skin'] = $skin_name;
                    $_SESSION['css'] = $css;
                }

                log_action('Logged in', $_SERVER[PHP_SELF]);
                header("location: start.php");
                exit();
            }
            else $error_message = "Check username and password.";
        }
        else die($mysqli->error);
        $data_con->close_db();
    }
}

$html = new html;
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
    <title> SRCCMSTHS Enrollment and Grading System - Powered by Cobalt</title>
    <link href="css/login.css" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=<?php echo MULTI_BYTE_ENCODING; ?>" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.getElementById('username').focus();">
<br /><br /><br /><br />
<br /><br /><br /><br />
<?php
echo '<form method="POST" action="' . $_SERVER[PHP_SELF] . '">';
$form_key = generate_token();
$form_identifier = $_SERVER['PHP_SELF'];
$_SESSION['cobalt_form_keys'][$form_identifier] = $form_key;
echo '<input type=hidden name=form_key value="' . $form_key .'">';
?>
<div class="vertical_center">
    <div class="container">
    <fieldset class="container">
        <fieldset class="top">
                SRCCMSTHS Enrollment and Grading System
        </fieldset>
        <fieldset class="middle">
            <div class="container_error">
                <?php $html->display_errors($error_message);?>
            </div>

            <table border="0" width="100%" cellspacing="1">
            <tr>
                <td align="left">&nbsp;Username: </td>
            </tr>
            <tr>
                <td align="center">
                    <?php $html->draw_text_field('','username',FALSE,'text',FALSE, 'id="username" size="37"'); ?>
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
                    <?php $html->draw_text_field('','password',FALSE,'password',FALSE,'size="37"'); ?>
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