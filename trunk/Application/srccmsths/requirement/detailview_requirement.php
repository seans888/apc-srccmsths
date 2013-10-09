<?php
//******************************************************************
//This file was generated by Cobalt, a rapid application development 
//framework developed by JV Roig (jvroig@jvroig.com).
//
//Cobalt on the web: http://cobalt.jvroig.com
//******************************************************************
require_once 'path.php';
init_cobalt('View requirement');
if(!isset($_POST['form_key'])) log_action("Module Access", $_SERVER['PHP_SELF']);

if(isset($_GET['requirement_no']))
{
    extract($_GET);

    $page_from = htmlentities($_GET['page_from']);
    $filter_used = htmlentities($_GET['filter_used']);
    $filter_field_used = htmlentities($_GET['filter_field_used']);

    require_once 'subclasses/requirement.php';
    $dbh_requirement = new requirement;
    $dbh_requirement->set_where("requirement_no='$requirement_no'");
    if($result = $dbh_requirement->make_query())
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
        header("location: listview_requirement.php?filter_field=$filter_field_used&filter=$filter_used&page_from=$page_from");
    }
}
require_once 'subclasses/requirement_html.php';
$html = new requirement_html;
$html->draw_header('Detail View: requirement', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from);
$html->detail_view = TRUE;

$html->draw_controls('view');

$html->draw_footer();