<?php
require '../Core/SCV2_Core.php';
init_SCV2();

if($_POST['formKey'] == $_SESSION['formKey'])
{
    if($_POST['Cancel']) header("location: " . HOME_PAGE);

    if($_POST['Submit'])
    {
        extract($_POST);

        if($errMsg=="")
        {
            //******************
            //The magic is here!
            //******************

            //Set variables needed for file creations.
            //****GUIDE****
            //** $SCV2_path = full path to the SCV2 main directory in your htdocs / www directory
            //** $SCV2_core_path = full path to the SCV2 generator core files, which all Synergy projects require.
            //** $project_path = full path to the SCV2 projects directory (where all resulting project files are generated) 
            //**                plus the name of the project as the subdirectory
            //** $project_core_path = the full path to the generated core files of each project; simply $project_path/Core/


            //Connection for main source file (this one).
            $mysqli = connect_DB();

            //Connections available for all block depths for the createModule and createClass functions.
            $mysqli_con1 = connect_DB();
            $mysqli_con2 = connect_DB();
            $mysqli_con3 = connect_DB();
            
            $mysqli->real_query("SELECT Base_Directory FROM project WHERE Project_ID='$_SESSION[Project_ID]'");
            if($result=$mysqli->use_result())
            {
                $data  = $result->fetch_assoc();
                extract($data);
                $SCV2_path = substr(FULLPATH_CORE,0,-5); //"-5" = remove "Core/" from FULLPATH_CORE
                $SCV2_core_path = $SCV2_path . 'Generator/Core_Files/';

                //Check if "Generator/Projects" folder is writable
                clearstatcache();
                if(is_writable($SCV2_path . 'Generator/Projects'))
                {
                    $SCV2_core_path = $SCV2_path . 'Generator/Core_Files/';

                    //Creating the base directory...
                    $project_path = $SCV2_path . 'Generator/Projects/' . $Base_Directory . '/';

                    //Note that it may be necessary to create multiple directories (actually, nested subdirectories), 
                    //depending on what the user specified as the project's base directory.
                    $subdirectory = explode ("/", $Base_Directory);
                    $subdirectory_count = count($subdirectory);
                    $current_directory = $SCV2_path . 'Generator/Projects/';

                    for($a=0;$a<$subdirectory_count;$a++)
                    {
                        $current_directory .= $subdirectory[$a] . '/';

                        //Delete existing old project, if any.
                        if(file_exists($current_directory)) obliterate_dir($current_directory);

                        //For each subdirectory, check if it exists. If it doesn't exist, create it.
                        if(!file_exists($current_directory)) 
                        {
                            mkdir(substr($current_directory,0,-1),0777);
                            chmod(substr($current_directory,0,-1),0777);
                        }
                    }

                    //Creating the tmp folder inside the base directory
                    $tmp_folder = $project_path . 'tmp/';
                    mkdir(substr($tmp_folder,0,-1), 0777);
                    chmod(substr($tmp_folder,0,-1), 0777);

                    //Creating the Core folder inside the base directory...
                    $project_core_path = $project_path . "core/";
                    if(!file_exists($project_core_path))
                    { 
                        mkdir(substr($project_core_path,0,-1), 0777);
                        chmod($project_core_path, 0777);
                    }

                    //Creating the Subclasses folder inside the Core folder inside the base directory...
                    $subclass_path = $project_path . 'core/subclasses/';
                    if(!file_exists($subclass_path)) 
                    {
                        mkdir(substr($subclass_path,0,-1),0777);
                        chmod(substr($subclass_path,0,-1),0777);
                    }


                    $webroot = substr($SCV2_path,0,-7); //"-7" = remove "cobalt/" from the path to get the webroot
                    $Fullpath_New_System = $webroot . $Base_Directory;

                    $path_array = array('Fullpath_New_System' => $Fullpath_New_System,
                                        'SCV2_path' => $SCV2_path, 
                                        'SCV2_core_path' => $SCV2_core_path, 
                                        'project_path' => $project_path, 
                                        'project_core_path' => $project_core_path, 
                                        'Base_Directory' => $Base_Directory);
                }
                else
                {
                    $errMsg = 'The "Projects" folder (cobalt/Generator/Projects) is not writeable. <br />Please make this folder writeable to proceed.';
                    drawHeader();
                    drawPageTitle("System Generation Failed", 
                                  $errMsg . '<br><input type=submit name=BACK value=BACK>','error');
                    drawFooter();
                    die();
                }

            }
            else die($mysqli->error);
            $result->close();

            //Startup tasks 
            //Load all functions we need for a minimal system generation (user-defined modules only, no standard/admin apps)
            require_once $SCV2_path . 'Generator/Scripts/Create_Modules.php';
            require_once $SCV2_path . 'Generator/Scripts/Create_Class.php';
            require_once $SCV2_path . 'Generator/Scripts/Create_Directory_Index.php';
            require_once $SCV2_path . 'Generator/Scripts/Generic/Standard_Header.php';
            require_once $SCV2_path . 'Generator/Scripts/Generic/Standard_Footer.php';
            //... then create the index.php files for the base directory, Core, and Core/Subclasses folders...
            createDirectoryIndex($project_path);
            createDirectoryIndex($project_core_path);
            createDirectoryIndex($subclass_path);
            //... finally create the main path file located in the base directory
            $pathfile_content=<<<EOD
<?php 
\$path_to_core = '$Fullpath_New_System/core';

\$path = '.' . PATH_SEPARATOR . \$path_to_core;
set_include_path(\$path);

require_once \$path_to_core . '/cobalt_core.php';
EOD;
            $filename = $project_path . '/path.php';
            if(file_exists($filename)) unlink($filename);
            $newfile=fopen($filename,"ab");
            fwrite($newfile, $pathfile_content);
            chmod($filename, 0777);
 
            //Step 1: If chosen by the user, generate the core files, standard app components and admin components.
            if($GenerateFiles=='YES PLEASE')
            {
                //Load additional functions needed for creating the standard application components and system admin components.
                require_once $SCV2_path . 'Generator/Scripts/Create_Application_Components.php';
                require_once $SCV2_path . 'Generator/Scripts/Create_Admin_Components.php';

                function copyCoreFile($file, $SCV2_core_path, $project_core_path)
                {
                    $source = $SCV2_core_path . $file;
                    $destination = $project_core_path . $file;

                    if (file_exists($source)) copy($source, $destination);
                    else echo "The source file '$source' does not exist";

                    chmod($destination, 0777);
                }

                copyCoreFile('html_class.php', $SCV2_core_path, $project_core_path);
                copyCoreFile('validation_class.php', $SCV2_core_path, $project_core_path);
                copyCoreFile('char_set_class.php', $SCV2_core_path, $project_core_path);
                copyCoreFile('paged_result_class.php', $SCV2_core_path, $project_core_path);

                //The Data Abstraction Class is a little more complicated than just a file copy.
                //It is divided into two parts because we need to insert the default database connection
                //settings between the two parts.

                //=> Get part 1:
                $Data_Abstraction_Class = file_get_contents($SCV2_path . '/Generator/Core_Files/data_abstraction_class_part1.php');

                //=> Query for the default database connection settings of this project.
                 
                $mysqli->real_query("SELECT a.Hostname, a.Username, a.Password, a.Database 
                                        FROM `database_connection` a, `project` b 
                                        WHERE a.DB_Connection_ID = b.Database_Connection_ID AND 
                                              b.Project_ID = '$_SESSION[Project_ID]'");
                if($result = $mysqli->use_result())
                {
                    $data = $result->fetch_assoc();
                    if(is_array($data)) extract($data);

                    $Data_Abstraction_Class.=<<<EOD
    var \$db_host='$Hostname';
    var \$db_user='$Username';
    var \$db_pass='$Password';
    var \$db_use='$Database';

EOD;
                }
                else die($mysqli->error);
                $result->close();


                //=> Get part 2:
                $Data_Abstraction_Class .= file_get_contents($SCV2_path . '/Generator/Core_Files/data_abstraction_class_part2.php');

                //Finally, create the file 'Data_Abstraction_Class.php'.
                $filename = $project_path .  'core/data_abstraction_class.php';
                if(file_exists($filename)) unlink($filename);
                $newfile=fopen($filename,"ab");
                fwrite($newfile, $Data_Abstraction_Class);
                chmod($filename, 0777);

                createStdAppComponents($path_array);
                createAdminComponents($path_array);
            }

            //Step 2: Loop through each element of $classFile, generate those that were left checked.
            if(is_array($classFile))
            {
                foreach($classFile as $class) createClass($class, $subclass_path, $mysqli_con1, $mysqli_con2, $mysqli_con3);
            }

            //Before creating the modules, we also should delete the user links query file for the access control list if it
            //already exists, because that means that is from a previous generation. We most certainly don't want the old entries.
            $filename = $project_path . '/user_links.sql';
            if(file_exists($filename)) unlink($filename);

            //Now that any existing ACL query file is deleted, we start a new one 
            $ACL_Query=<<<EOD
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('1', 'Module Control', '/$Base_Directory/sysadmin/module_control.php', 'Module Control', 'Enable and/or disable system modules','2','Yes','On','modulecontrol.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('2', 'Set User Passports', '/$Base_Directory/sysadmin/set_user_passports.php', 'Set User Passports', 'Change the passport settings of system users','2','Yes','On','passport.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('3', 'Security Monitor', '/$Base_Directory/sysadmin/security_monitor.php', 'Security Monitor', 'Examine the system log','2','Yes','On','security3.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('4', 'Add person', '/$Base_Directory/sysadmin/add_person.php', 'Add person', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('5', 'Edit person', '/$Base_Directory/sysadmin/edit_person.php', 'Edit person', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('6', 'View person', '/$Base_Directory/sysadmin/listview_person.php', 'Manage person', '', '2', 'Yes', 'On','persons.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('7', 'Delete person', '/$Base_Directory/sysadmin/delete_person.php', 'Delete person', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('8', 'Add user', '/$Base_Directory/sysadmin/add_user.php', 'Add user', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('9', 'Edit user', '/$Base_Directory/sysadmin/edit_user.php', 'Edit user', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('10', 'View user', '/$Base_Directory/sysadmin/listview_user.php', 'Manage user', '', '2', 'Yes', 'On','card.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('11', 'Delete user', '/$Base_Directory/sysadmin/delete_user.php', 'Delete user', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('12', 'Add user types', '/$Base_Directory/sysadmin/add_user_types.php', 'Add user types', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('13', 'Edit user types', '/$Base_Directory/sysadmin/edit_user_types.php', 'Edit user types', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('14', 'View user types', '/$Base_Directory/sysadmin/listview_user_types.php', 'Manage user types', '', '2', 'Yes', 'On','user_type2.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('15', 'Delete user types', '/$Base_Directory/sysadmin/delete_user_types.php', 'Delete user types', '', '2', 'No', 'On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('16','Add user role', '/$Base_Directory/sysadmin/add_user_role.php', 'Add user role','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('17','Edit user role', '/$Base_Directory/sysadmin/edit_user_role.php', 'Edit user role','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('18','View user role', '/$Base_Directory/sysadmin/listview_user_role.php', 'Manage user roles','','2','Yes','On','roles.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('19','Delete user role', '/$Base_Directory/sysadmin/delete_user_role.php', 'Delete user role','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('20','Add system settings', '/$Base_Directory/sysadmin/add_system_settings.php', 'Add system settings','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('21','Edit system settings', '/$Base_Directory/sysadmin/edit_system_settings.php', 'Edit system settings','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('22','View system settings', '/$Base_Directory/sysadmin/listview_system_settings.php', 'Manage system settings','','2','Yes','On','system_settings.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('23','Delete system settings', '/$Base_Directory/sysadmin/delete_system_settings.php', 'Delete system settings','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('24','Add user links', '/$Base_Directory/sysadmin/add_user_links.php', 'Add user links','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('25','Edit user links', '/$Base_Directory/sysadmin/edit_user_links.php', 'Edit user links','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('26','View user links', '/$Base_Directory/sysadmin/listview_user_links.php', 'Manage user links','','2','Yes','On','links.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('27','Delete user links', '/$Base_Directory/sysadmin/delete_user_links.php', 'Delete user links','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('28','Add user passport groups', '/$Base_Directory/sysadmin/add_user_passport_groups.php', 'Add user passport groups','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('29','Edit user passport groups', '/$Base_Directory/sysadmin/edit_user_passport_groups.php', 'Edit user passport groups','','2','No','On','form.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('30','View user passport groups', '/$Base_Directory/sysadmin/listview_user_passport_groups.php', 'Manage user passport groups','','2','Yes','On','passportgroup.png');
INSERT INTO `user_links`(link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, `status`, icon) VALUES('31','Delete user passport groups', '/$Base_Directory/sysadmin/delete_user_passport_groups.php', 'Delete user passport groups','','2','No','On','form.png');

EOD;
            $filename = $project_path . '/user_links.sql';
            $newfile=fopen($filename,"ab");
            fwrite($newfile, $ACL_Query);
            chmod($filename, 0777);

            //Step 3: Loop through each element of $tableModules, generate everything that was left checked.
            $numModules = 0; //Just to count how many modules were created; will be used in ACL SQL file generation.

            if(!is_array($tableModules)) $tableModules=array(); //just so no ugly PHP warning message will appear in case no modules where submitted
            foreach($tableModules as $module)
            {
                 
                $mysqli->real_query("SELECT Page_ID FROM table_pages WHERE Table_ID='$module'");
                if($result=$mysqli->use_result())
                {
                    while($data  = $result->fetch_assoc())
                    {
                        extract($data);
                        createModule($module, $Page_ID, $path_array, $mysqli_con1, $mysqli_con2);
                        $numModules++;
                    }
                }
            }


            //Let's create the SQL file for assigning the root user permission to access all modules
            $root_permissions=<<<EOD
INSERT INTO `user_passport`(username, link_id) VALUES('root','1');
INSERT INTO `user_passport`(username, link_id) VALUES('root','2');
INSERT INTO `user_passport`(username, link_id) VALUES('root','3');
INSERT INTO `user_passport`(username, link_id) VALUES('root','4');
INSERT INTO `user_passport`(username, link_id) VALUES('root','5');
INSERT INTO `user_passport`(username, link_id) VALUES('root','6');
INSERT INTO `user_passport`(username, link_id) VALUES('root','7');
INSERT INTO `user_passport`(username, link_id) VALUES('root','8');
INSERT INTO `user_passport`(username, link_id) VALUES('root','9');
INSERT INTO `user_passport`(username, link_id) VALUES('root','10');
INSERT INTO `user_passport`(username, link_id) VALUES('root','11');
INSERT INTO `user_passport`(username, link_id) VALUES('root','12');
INSERT INTO `user_passport`(username, link_id) VALUES('root','13');
INSERT INTO `user_passport`(username, link_id) VALUES('root','14');
INSERT INTO `user_passport`(username, link_id) VALUES('root','15');
INSERT INTO `user_passport`(username, link_id) VALUES('root','16');
INSERT INTO `user_passport`(username, link_id) VALUES('root','17');
INSERT INTO `user_passport`(username, link_id) VALUES('root','18');
INSERT INTO `user_passport`(username, link_id) VALUES('root','19');
INSERT INTO `user_passport`(username, link_id) VALUES('root','20');
INSERT INTO `user_passport`(username, link_id) VALUES('root','21');
INSERT INTO `user_passport`(username, link_id) VALUES('root','22');
INSERT INTO `user_passport`(username, link_id) VALUES('root','23');
INSERT INTO `user_passport`(username, link_id) VALUES('root','24');
INSERT INTO `user_passport`(username, link_id) VALUES('root','25');
INSERT INTO `user_passport`(username, link_id) VALUES('root','26');
INSERT INTO `user_passport`(username, link_id) VALUES('root','27');
INSERT INTO `user_passport`(username, link_id) VALUES('root','28');
INSERT INTO `user_passport`(username, link_id) VALUES('root','29');
INSERT INTO `user_passport`(username, link_id) VALUES('root','30');
INSERT INTO `user_passport`(username, link_id) VALUES('root','31');

EOD;

            for($a=0; $a < $numModules; $a++)
            {
                $link = $a + 32;
                $root_permissions.=<<<EOD
INSERT INTO `user_passport`(username, link_id) VALUES('root','$link');

EOD;
            }
            $filename = $project_path . '/root_permissions.sql';
            if(file_exists($filename)) unlink($filename);
            $newfile=fopen($filename,"ab");
            fwrite($newfile, $root_permissions);
            chmod($filename, 0777);


            if($GenerateFiles=='YES PLEASE')
            {
                //Create a 'new_system.sql' file, simply 
                //cruizer_base.sql + user_links.sql + root_permissions.sql
                $content = file_get_contents($project_path. '/cruizer_base.sql');
                $content.= file_get_contents($project_path. '/user_links.sql');
                $content.= file_get_contents($project_path. '/root_permissions.sql');
                $filename = $project_path . '/new_system.sql';
                if(file_exists($filename)) unlink($filename);
                $newfile=fopen($filename,"ab");
                fwrite($newfile, $content);
                chmod($filename, 0777);
            }

            //Woohoo! We're done here. Hopefully, everything went ok. Woot!
            //Now, just draw a success screen.
            drawHeader();
            drawPageTitle("System Generation Completed Successfully", 
                          'Your system generation request was a complete and resounding success, zero errors were encountered<br><input type=submit name=BACK value=BACK>','system');
            drawFooter();
            die();
        }
    }
}
else
{
    $Data_Abstraction = TRUE;
    $HTML_Class = TRUE;
    $Validation_Class = TRUE;
    $Character_Set_Class = TRUE;
    $Paged_Result_Class = TRUE;
}

drawHeader();
drawPageTitle('Generate Project','All checked files will be generated when you click the "GENERATE!" button. <br> You may uncheck files as necessary.','info');
displayTip('Tables that form the "many" part in a One-to-Many relationship usually don\'t need Modules. <br />
            Uncheck their Modules checkbox to help keep your resulting project uncluttered, but leave their
            Subclass checked.');
?>

<script language="JavaScript" type="text/JavaScript">
function checkAll(field)
{
    if(field.length > 1)
    {
        for (i = 0; i < field.length; i++)
            field[i].checked = true ;
    }
    else field.checked = true;
}
function uncheckAll(field)
{
    if(field.length > 1)
    {
        for (i = 0; i < field.length; i++)
            field[i].checked = false ;
    }
    else field.checked = false;
}
</script>

<div class="container_mid_huge2">
<fieldset class="top">
CODE GENERATOR - Create Project Files
</fieldset>

<fieldset class="middle">
<table class="listView" border="1" width="900">
<?php 
echo '<tr class="listRowHead">'
        .'<td width="320"> Table </td>'
        .'<td width="290"> Subclass </td>'
        .'<td width="290"> Modules </td>'
    .'</tr>'
    .'<tr class="listRowOdd"><td>&nbsp;</td>
          <td align=center>
              <input type=button name=CHECK value="CHECK ALL" class=button1 onClick=\'checkAll(checkfield_s);\'>
              <input type=button name=UNCHECK value="UNCHECK ALL" class=button1 onClick=\'uncheckAll(checkfield_s);\'>
          </td>
          <td align=center>
              <input type=button name=CHECK value="CHECK ALL" class=button1 onClick=\'checkAll(checkfield_m);\'>
              <input type=button name=UNCHECK value="UNCHECK ALL" class=button1 onClick=\'uncheckAll(checkfield_m);\'>
          </td>
      </tr>';

$mysqli = connect_DB(); 
$mysqli->real_query("SELECT a.Table_ID, a.Table_Name 
                        FROM `table` a, `database_connection` b 
                        WHERE a.Project_ID='$_SESSION[Project_ID]' AND 
                              a.DB_Connection_ID = b.DB_Connection_ID
                        ORDER BY a.Table_Name");

if($result = $mysqli->store_result())
{
    $a=0;
    while($data = $result->fetch_array())
    {
        extract($data);
        if($a%2==0) $class='listRowEven';
        else $class='listRowOdd';
        echo '<tr class="'. $class . '">'
                .'<td>' . $Table_Name . '</td>'
                .'<td align=center> <input type=checkbox ID=checkfield_s name=classFile[' . $a . '] value="' . $Table_ID . '" checked> </td>'
                .'<td align=center> <input type=checkbox ID=checkfield_m name=tableModules[' . $a . '] value="' . $Table_ID . '" checked> </td>'
            .'</tr>';
        $a++;
    }
}
else die($mysqli->error);

if($a%2==0) $class='listRowEven';
else $class='listRowOdd';
echo '<tr class="' . $class . '">'
        .'<td colspan=2> Generate standard application components, <br> core files, and system administration components </td>'
        .'<td align=center> <input type=checkbox ID=checkfield_m name=GenerateFiles value="YES PLEASE" checked>  </td>'
    .'</tr>';
?>
</table>
</fieldset>

<fieldset class="bottom">
<?php
drawSubmitCancel(FALSE,1,'Submit','GENERATE!');
?>
</fieldset>
</div>
<?php
drawFooter();
?>
