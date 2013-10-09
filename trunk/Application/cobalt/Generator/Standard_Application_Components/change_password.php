    if($_POST['submit'])
    {
        extract($_POST);

        require 'core/validation_class.php';
        $validator = new validation;
        $error_message = $validator->check_if_null('Old Password', $old_password, 'New Password', $password1, 'Confirm Password', $password2);

        $data_con = new data_abstraction;
        $data_con->set_fields('password');
        $data_con->set_table('user');
        $data_con->set_where("username='$_SESSION[user]'");
        $result = $data_con->make_query();
        $data_con->close_db();
        $data = $result->fetch_assoc();
        $result->close();

        //Hash old password using default Cobalt password hashing technique
        $hashed_old_password = cobalt_password_hash('RECREATE', $old_password, $_SESSION['user']);

        if($hashed_old_password != $data['password']) $error_message.="The password you entered in 'Old Password' does not match the password in your records. <BR>";
        if($password1 != $password2) $error_message.="New passwords do not match. <BR>";	

        if($error_message=="")
        {
            //Hash the password using default Cobalt password hashing technique
            $hashed_password = cobalt_password_hash('NEW',$password1, $_SESSION['user'], $new_salt, $new_iteration, $new_method);

            $data_con = new data_abstraction;
            $data_con->set_query_type('UPDATE');
            $data_con->set_table('user');
            $data_con->set_update("`password`='$hashed_password', `salt`='$new_salt', `iteration`='$new_iteration', `method`='$new_method'");
            $data_con->set_where("username='$_SESSION[user]'");
            $data_con->make_query();
            $error_message = 'Your password has been successfully updated! You can <a href=Main.php> click here </a> to go back to your control center or use the menu above.';
            $msg_type='SYSTEM';

            $old_password = '';
            $password1   = '';
            $password2   = '';
        }
    }
}

$html = new html;
$html->draw_header('Change Password',$error_message,$msg_type);

echo '<div class="container_mid">
    <fieldset class="top"> Password Management
    </fieldset>
    <fieldset class="middle">
    <table class="input_form">';
$html->draw_text_field('Old Password','old_password',FALSE,'password');
$html->draw_text_field('New Password','password1',FALSE,'password');
$html->draw_text_field('Confirm New Password','password2',FALSE,'password');

echo '</table>
    </fieldset>
    <fieldset class="bottom">';

$html->draw_submit_cancel();
echo '</fieldset>';
echo '</div>';

$html->draw_footer();
