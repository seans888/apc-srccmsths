<?php
//This file creates the system administration components needed to make a complete
//working application (from login to logout) using the generated classes and modules

function createAdminComponents($path_array)
{
    extract($path_array);

    //First, make sure the 'Sysadmin' folder is created.
    $sysadmin_folder = $project_path . 'sysadmin/';
    if(!file_exists($sysadmin_folder)) mkdir(substr($sysadmin_folder,0,-1), 0777);
    chmod($sysadmin_folder, 0777);

    function copy_admin_component($file, $SCV2_path, $project_path)
    {
        $source = $SCV2_path . 'Generator/Standard_Application_Components/'. $file;
        $destination = $project_path . $file;

        if (file_exists($source)) copy($source, $destination);
        else echo "The source file '$source' does not exist";

        chmod($destination, 0777);
    }

    //Administrative Tools
    copy_admin_component('sysadmin/module_control.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/set_user_passports.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/set_user_passports2.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/set_user_passports3.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/security_monitor.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/security_monitor2.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/security_monitor3.php', $SCV2_path, $project_path);


    //User Management Modules
    copy_admin_component('sysadmin/add_person.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_person.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_person.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_person.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_person.php', $SCV2_path, $project_path);

    copy_admin_component('sysadmin/add_system_settings.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_system_settings.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_system_settings.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_system_settings.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_system_settings.php', $SCV2_path, $project_path);

    copy_admin_component('sysadmin/add_user.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_user.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_user.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_user.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_user.php', $SCV2_path, $project_path);

    copy_admin_component('sysadmin/add_user_links.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_user_links.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_user_links.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_user_links.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_user_links.php', $SCV2_path, $project_path);

    copy_admin_component('sysadmin/add_user_passport_groups.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_user_passport_groups.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_user_passport_groups.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_user_passport_groups.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_user_passport_groups.php', $SCV2_path, $project_path);

    copy_admin_component('sysadmin/add_user_role.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_user_role.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_user_role.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_user_role.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_user_role.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/role_permissions.php', $SCV2_path, $project_path);

    copy_admin_component('sysadmin/add_user_types.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/edit_user_types.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/delete_user_types.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/detailview_user_types.php', $SCV2_path, $project_path);
    copy_admin_component('sysadmin/listview_user_types.php', $SCV2_path, $project_path);


    //subclasses
    copy_admin_component('core/subclasses/person.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/person_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/person_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/system_settings.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/system_settings_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/system_settings_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/system_skins.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/system_skins_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/system_skins_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_links.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_links_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_links_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_passport_groups.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_passport_groups_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_passport_groups_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_role.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_role_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_role_html.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_role_links.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_types.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_types_dd.php', $SCV2_path, $project_path);
    copy_admin_component('core/subclasses/user_types_html.php', $SCV2_path, $project_path);

    //**********************************************************
    //LAST: CREATE THE INDEX.PHP FILE FOR THE SYSADMIN DIRECTORY
    //**********************************************************
    createDirectoryIndex($project_path . 'sysadmin/');
}
