<?php
require_once 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<link href="css/<?php echo $_SESSION[css];?>" rel="stylesheet" type="text/css">
</head>
<body>
<?php

$num_links = 0;
$menu_links = array();

$data_con = new data_abstraction;
$data_con->connect_db();
$data_con->set_fields('a.link_id, a.descriptive_title, a.target, a.description, c.passport_group');
$data_con->set_table('user_links a, user_passport b, user_passport_groups c');
$data_con->set_where("a.link_id=b.link_id AND b.username='$_SESSION[user]' AND a.passport_group_id=c.passport_group_id AND a.show_in_tasklist='Yes' AND a.status='On'");
$data_con->set_order('c.passport_group, a.descriptive_title');
if($result = $data_con->make_query())
{
    while($data = $result->fetch_assoc())
    {
        extract($data);

        $menu_links[$passport_group]['title'][] = $descriptive_title;
        $menu_links[$passport_group]['target'][] = $target;
        $menu_links[$passport_group]['link_id'][] = $link_id;
        $menu_links[$passport_group]['description'][] = $description;
        $num_links++;
    }
    $result->close();
}
else die("Fatal error: cannot retrieve modules");
$data_con->close_db();

$current_group=='';
foreach($menu_links as $group => $link_info)
{
    if($current_group=='') 
    {
        $current_group = $group;
        menuGroupWindowHeader($group);
    }
    for($a=0; $a<count($link_info['title']); $a++)
    {
        if($current_group!= $group)
        {
            menuGroupWindowFooter();
            menuGroupWindowHeader($group);
            $current_group = $group;
        }

        if($a%2==0) $class='listRowEven';
        else $class='listRowOdd';
        echo "<tr class=\"$class\"><td><a href='{$link_info[target][$a]}' target='content_frame' class='sidebar'> {$link_info[title][$a]} </a></td></tr>";
    }
}
menuGroupWindowFooter();

function menuGroupWindowHeader($group)
{
    echo '<table width="180" border="1" class="listView">
          <tr class="listRowHead"><td>' . $group . '</td></tr>';
}

function menuGroupWindowFooter()
{
	echo "</table>";
}
