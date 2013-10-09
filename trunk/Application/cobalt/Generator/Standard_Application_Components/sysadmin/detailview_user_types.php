<?php
//******************************************************************
//This file was generated by Cobalt, a rapid application development 
//framework developed by JV Roig (jvroig@jvroig.com).
//
//Cobalt on the web: http://cobalt.jvroig.com
//******************************************************************
require_once 'path.php';
init_cobalt('View user types');
if(!isset($_POST['form_key'])) log_action("Module Access", $_SERVER['PHP_SELF']);

if(isset($_GET['user_type_id']))
{
    extract($_GET);
    require_once 'subclasses/user_types.php';
    $dbh_user_types = new user_types;
    $dbh_user_types->set_where("user_type_id='$user_type_id'");
    if($result = $dbh_user_types->make_query())
    {
        $data = $result->fetch_assoc();
        extract($data);
    }
}
elseif(xsrf_guard())
{
    extract($_POST);
    if($_POST['cancel']) 
    {
        log_action('Pressed cancel button', $_SERVER[PHP_SELF]);
        header("location: listview_user_types.php?filter_field=$filter_field_used&filter=$filter_used&page_from=$page_from");
    }
}
require_once 'subclasses/user_types_html.php';
$html = new user_types_html;
$html->draw_header('Detail View: user types', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from);
$html->detail_view = TRUE;
$html->draw_controls('view');
$html->draw_footer();
