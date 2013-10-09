<?php
require 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');

if(xsrf_guard())
{
    if($_POST['cancel'])
    {
        header("location: main.php");
        exit();
    }
    if($_POST['Submit'])
    {
        extract($_POST);

        $data_con = new data_abstraction;
        $data_con->set_query_type('UPDATE');
        $data_con->set_table('user');
        $data_con->set_update("skin_id='$skin_id'");
        $data_con->set_where("username='$_SESSION[user]'");
        $data_con->make_query();
        $data_con->close_db();

        //If the update went ok, we should update the session variables for this.
        $data_con = new data_abstraction;
        $data_con->set_fields('skin_name, header, footer, css');
        $data_con->set_table('system_skins');
        $data_con->set_where("skin_id='$skin_id'");
        $result = $data_con->make_query();
        $numrows = $data_con->num_rows;
        $data_con->close_db();
        
        if($numrows==1)
        {
            $data = $result->fetch_assoc();
            extract($data);
            $_SESSION['header'] = $header;
            $_SESSION['footer'] = $footer;
            $_SESSION['skin'] = $skin_name;
            $_SESSION['css'] = $css;
        }

        ?>
        <script type="text/javascript">
        window.top.frames['header_frame'].location="header.php";
        window.top.frames['menu_frame'].location="menus.php";
        window.top.frames['content_frame'].location="change_skin.php";
        </script>
        <?php
    }
}

$html = new html;
$html->draw_header('Change Skin',$error_message);

$html->display_info('Changing the System Skin does not affect functionality.<br>All changes are merely aesthetic.');

echo '<div class="container_mid">
    <fieldset class="top"> Skin (UI Theme) Management
    </fieldset>
    <fieldset class="middle">
    <table class="input_form">';
echo '<tr><td>System Skin: <select name="skin_id">';

$data_con = new data_abstraction;
$data_con->set_fields('skin_id AS new_skin_id, skin_name');
$data_con->set_table('system_skins');
$data_con->set_order('skin_name');
$result = $data_con->make_query();
$numrows = $data_con->num_rows;
if($data_con->error) echo die($data_con->error);
$data_con->close_db();

for($a=0;$a<$numrows;$a++)
{
	$data = $result->fetch_assoc();
	extract($data);
	$selected='';
	if($skin_name==$_SESSION['skin']) $selected='selected';
	echo "<option value='$new_skin_id' $selected> $skin_name </option>";
}
echo '</select></td></tr>';
echo '</table>';
echo '</fieldset>
    <fieldset class="bottom">';

$html->draw_submit_cancel();

echo '</fieldset>';
echo '</div>';

$html->draw_footer();
