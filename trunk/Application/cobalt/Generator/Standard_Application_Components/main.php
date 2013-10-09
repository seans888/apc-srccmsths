<?php
require_once 'core/cobalt_core.php';
init_cobalt('ALLOW_ALL');

$html = new html;
$html->draw_header($error_message);
$html->draw_page_title('Welcome to your Control Center');

if(DEBUG_MODE)
{
    $html->display_errors('System is running in DEBUG MODE. Please contact the system administrator ASAP.');
}

$data_con = new data_abstraction;
$data_con->connect_db();
$data_con->set_fields('a.link_id, a.descriptive_title, a.target, a.description, c.passport_group, a.icon as link_icon, c.icon as `group_icon`');
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
        $menu_links[$passport_group]['link_icon'][] = $link_icon;
        $menu_links[$passport_group]['group_icon'][] = $group_icon;
        $num_links++;
    }
    $result->close();
}
else die("Fatal error in the data abstraction class... T_T");
$data_con->close_db();

$current_group=='';
$cntr=0;

//echo '<div class="container_CC">';
if(is_array($menu_links))
{
    echo '<fieldset class="container">';
    foreach($menu_links as $group => $link_info)
    {
        if($current_group=='') 
        {
            $current_group = $group;
            menuGroupWindowHeader($group, $link_info['group_icon'][0]);
        }

        for($a=0; $a<count($link_info['title']); $a++)
        {
            if($current_group!= $group)
            {
                echo '</tr></table></div>';
                $cntr=0;
                menuGroupWindowFooter();
                menuGroupWindowHeader($group, $link_info['group_icon'][$a]);
                $current_group = $group;
            }


            if($cntr==0)
            {
                echo '<div class="container_icons_CC">';
                echo '<table width = "100%">';
                echo '<tr>';
            }
            elseif($cntr <=2)
            {
            
            }
            else
            {
                echo '</tr></table>';
                echo '</div><div class="container_icons_CC">';
                echo '<table width = "100%">';
                echo '<tr>';
                $cntr = 0;
            }
            $cntr++;
            echo "<td width='33%' class=''>
                    <a href='{$link_info[target][$a]}' target='content_frame' class='linkCC'>
                        <img src='images/icons/{$link_info[link_icon][$a]}'><br> 
                        {$link_info[title][$a]}
                    </a>
                  </td>";

        }
        //Just to be sure we have three columns before closing the table
        for($z = $cntr; $z<=2; $z++)
        {
            echo '<td width="33%"> &nbsp; </td>';
        }
    }
    echo '</tr></table></div>';
    echo '</fieldset>';
}
else
{
    $html->display_errors("You have no Control Center privileges in your account. Please contact your system administrator.");
}

menuGroupWindowFooter();

function menuGroupWindowHeader($group, $icon)
{
    echo '<fieldset class="top">';
    echo "<img src='images/icons/$icon'> $group";
    echo '</fieldset>';
    echo '<fieldset class="middle">';
}

function menuGroupWindowFooter()
{
    echo '</fieldset>';
}

$html->draw_footer();
