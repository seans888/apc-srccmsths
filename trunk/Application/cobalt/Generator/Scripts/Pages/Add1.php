<?php
//We need the "add_" method; following the convention of the class generator, the name would be 'add_' + "$class_name".
$add_method = 'add';
$show_in_tasklist = 'No';

//Now let's get the number of fields we have, we'll get to use this information a lot.
$field_count = count($field);

//Check to see if some fields are set to use date controls
//If one or more are, then we need to add something to the processing part later
$Date_Controls_Script='';
$Date_Controls_To_POST='';
for($a=0;$a<$field_count;$a++)
{
	if($field[$a]['Control_Type']=='date controls')
	{
        $field_name = $field[$a]['Field_Name'];
        if(strtoupper($field_name) != 'DATE')
        {
            if(strlen($field_name) <=20) $prepend = $field_name;
            else $prepend = substr($field_name,0,20);
            $dc_year[$a] = $prepend . '_year';
            $dc_month[$a] = $prepend . '_month';
            $dc_day[$a] = $prepend . '_day';
        }
        else
        {
            $dc_year[$a] = 'year';
            $dc_month[$a] = 'month';
            $dc_day[$a] = 'day';
        }

        $Date_Controls_Script .= "\r\n        \$$field_name = \$$dc_year[$a] . '-' . \$$dc_month[$a] . '-' . \$$dc_day[$a];";

        if($field[$a]['Data_Type'] != 'unix timestamp')
            $Date_Controls_To_POST .= "\r\n        \$_POST['$field_name'] = \$$field_name;";
        else
            $Date_Controls_To_POST .= "\r\n        \$_POST['$field_name'] = strtotime(\$$field_name);";
    }
}


//We need to check if the current table is a parent table in a 1-M relationship.
//If it is, we need to go find its child table(s).
//For each child table, we need to create a loop that will call the add_ method of
//the subclass of that child table.

$Child_Table_Add_Script = '';
$mysqli->real_query("SELECT a.Child_Field_ID FROM `table_relations` a, `table_fields` b, `table` c WHERE a.Relation='ONE-to-MANY' AND a.Parent_Field_ID = b.Field_ID AND b.Table_ID = c.Table_ID AND c.Table_ID='$Table_ID'");
if($result = $mysqli->store_result())
{
    $num_child_tables = $result->num_rows;

    for($a=0; $a<$num_child_tables; $a++)
    {
        $data = $result->fetch_row();
        $Child_Field_ID = $data[0];

         
        $mysqli2->real_query("SELECT a.Table_Name, a.Table_ID FROM `table` a, `table_fields` b WHERE b.Field_ID='$Child_Field_ID' AND b.Table_ID=a.Table_ID");
        if($result2 = $mysqli2->store_result())
            $data = $result2->fetch_row();
        else die("Error getting child table name and ID: " . $mysqli2->error);

        $Child_Table_Name = $data[0];
        $Child_Table_ID = $data[1];
        $Child_Classfile = $Child_Table_Name . '.php';
        $Child_Num_Particulars = 'num_' . $Child_Table_Name; //The specialized '$numParticulars' variable.
        $Child_Particulars_Count = $Child_Table_Name . '_count' ; //The specialized '$particularsCount' variable.
        $Child_Table_Add_Method = 'add';

        //We need to see if the child field has any field designated as 'date controls', so we can assemble the date value for it.
        $Child_Date_Field_Assembly = '';
         
        $mysqli2->real_query("SELECT Field_Name FROM `table_fields` WHERE Table_ID='$Child_Table_ID' AND Control_Type='date controls'");
        if($result2 = $mysqli2->store_result())
        {
            $num_date_child_fields = $result2->num_rows;
            for($b=0; $b<$num_date_child_fields; $b++)
            {
                $data2 = $result2->fetch_assoc();
                extract($data2);

                $Child_Date_Field_Assembly .= '$cf_' . $Child_Table_Name . '_' . $Field_Name . '[$a] = $cf_' . $Child_Table_Name . '_' . $Field_Name . '_year[$a] . \'-\' . $cf_' . $Child_Table_Name . '_' . $Field_Name . '_month[$a] . \'-\' . $cf_' . $Child_Table_Name . '_' . $Field_Name . '_day[$a];' . 
"\r\n               ";
            }        
        }

        $Child_Table_Add_Script.=<<<EOD
            require_once 'subclasses/$Child_Classfile';
            {$dbh_name} = new $Child_Table_Name;
            for(\$a=0; \$a<\$$Child_Particulars_Count;\$a++)
            {
                $Child_Date_Field_Assembly
                \$param = array(

EOD;
        //Now let's get the fields of the child table.
        //Although all we need right now is the field name and attribute, we also get a few more fields
        //and store all info in an array to avoid having to re-query later on when we need the child fields again
        //along with their relevant information.
        $Child_Table_Fields_Info = array('Field_Name'=>array(),
                                         'Field_ID'=>array(),
                                         'Control_Type'=>array(),
                                         'Label'=>array());
         
        $mysqli2->real_query("SELECT Field_ID AS 'Child_Field_ID', Field_Name, Attribute, Control_Type, Label FROM `table_fields` WHERE Table_ID='$Child_Table_ID'");
        if($result2 = $mysqli2->store_result())
        {
            $num_child_fields = $result2->num_rows;
            for($b=0; $b<$num_child_fields; $b++)
            {
                $data2 = $result2->fetch_assoc();
                extract($data2);

                $Field_Var='';

                if($Attribute=='primary key' && strtoupper($Field_Name) == 'AUTO_ID')
                {
                    //Do nothing... ignore all auto_id's.
                }
                else 
                {
                    //All non-primary-key fields will be stored in an array
                    if($Control_Type!='none')
                    {
                        //The "cf_" prepended is needed so that the field name of the child field will never collide with a similarly
                        //named field in the parent.
                        //The "$Child_Table_Name" prepended is needed so that the field name of the child field will never collide
                        //with a similarly named field in another child of the parent.
                        $Field_Var = '$cf_' . $Child_Table_Name . '_'  . $Field_Name . '[$a]';
                        $Child_Table_Fields_Info['Field_Name'][] = 'cf_' . $Child_Table_Name . '_' . $Field_Name;
                        $Child_Table_Fields_Info['Field_ID'][] = $Child_Field_ID;
                        $Child_Table_Fields_Info['Control_Type'][] = $Control_Type;
                        $Child_Table_Fields_Info['Label'][] = $Label;
                    }
                    else //This means it's a primary and/or foreign key, no "cf_" needed in the name
                    {
                        $Field_Var = '$' . $Field_Name;
                    }
            }

            if($Field_Var!='')
            {
                $Child_Table_Add_Script.=<<<EOD
                           '$Field_Name'=>$Field_Var,

EOD;
                }
            }
        }
        else die("Oops... we got an error! ". $mysqli2->error);
        $result2->close();

        $Child_Table_Add_Script = substr($Child_Table_Add_Script, 0, strlen($Child_Table_Add_Script) - 2); //Removed the last newline and comma.


        $Child_Table_Add_Script.=<<<EOD

                              );
                {$dbh_name}->$Child_Table_Add_Method(\$param);
            }


EOD;

        //After finishing 1 full run of a child table creating its entire add script, now we create the multifield control for it.
        require_once 'Multifield_script.php';

        $child_field_count = count($Child_Table_Fields_Info['Field_Name']);

        $child_field_labels = '';
        $child_field_controls = '';
        $child_field_parameters = '';
        $multifield_setup = '';
        for($b=0; $b<$child_field_count; $b++)
        {
            //Set Field Labels
            $child_field_labels .= "'" . ucwords($Child_Table_Fields_Info['Label'][$b]) . "',";

            $USE_MULTIFIELD_SETUP = ''; //This will contain a value if a control type is a radio button or a drop-down list.
            switch($Child_Table_Fields_Info['Control_Type'][$b])
            {
                case "textbox":
                case "textarea":
                case "special textbox":
                    $child_field_controls .= "'draw_text_field_mf',";
                    $child_field_parameters .=<<<EOD
                                                    array('{$Child_Table_Fields_Info[Field_Name][$b]}','text'),

EOD;
                    break;
                case "date controls":
                    $child_field_controls .= "'draw_date_field_mf',";
                    $child_field_parameters .=<<<EOD
                                                    array('{$Child_Table_Fields_Info[Field_Name][$b]}_year','{$Child_Table_Fields_Info[Field_Name][$b]}_month','{$Child_Table_Fields_Info[Field_Name][$b]}_day'),

EOD;
                    break;
                case "radio buttons":
                    $USE_MULTIFIELD_SETUP = 'Predefined List';
                    $child_field_controls .= "'draw_select_field_mf',";
                    break;

                case "drop-down list":
                     
                    $mysqli2->real_query("SELECT List_ID FROM table_fields_list WHERE Field_ID='{$Child_Table_Fields_Info[Field_ID][$b]}'");
                    if($result2 = $mysqli2->store_result())
                    {
                        if($result2->num_rows > 0) 
                        {
                            $options = $Child_Table_Fields_Info['Field_Name'][$b] . '_array_options';
                            $USE_MULTIFIELD_SETUP = 'Predefined List';
                            $child_field_controls .= "'draw_select_field_mf',";
                            $child_field_parameters .=<<<EOD
                                                    array(\$$options, '{$Child_Table_Fields_Info[Field_Name][$b]}'),

EOD;
                        }
                        else 
                        {
                            $USE_MULTIFIELD_SETUP = 'SQL Generated';
                            $child_field_controls .= "'draw_select_field_from_query_mf',";

                            //The names of the variables '$query', '$list_value', and '$list_items' need to be 'specialized' for this field,
                            //so that the script will work despite having many of this type of control, otherwise, many controls of the same type
                            //will end up depending on the same variable, which obviously won't work as expected.
                            $query = $Child_Table_Fields_Info['Field_Name'][$b] . '_query';
                            $list_value = $Child_Table_Fields_Info['Field_Name'][$b] . '_list_value';
                            $list_items = $Child_Table_Fields_Info['Field_Name'][$b] . '_list_items';

                            $child_field_parameters .=<<<EOD
                                                    array(\$$query, \$$list_value, \$$list_items,'{$Child_Table_Fields_Info[Field_Name][$b]}'),

EOD;
                        }
                    }
                    else die('Error checking for a predefined list while determining child field control type: ' . $mysqli2->error);
                default: break;
            }

            if($USE_MULTIFIELD_SETUP!='')
            {
                $multifield_setup .= multifield_setup($USE_MULTIFIELD_SETUP, 
                                                        $Child_Table_Fields_Info['Field_Name'][$b],
                                                        $Child_Table_Fields_Info['Field_ID'][$b]);
            }
        }
        $child_field_labels = substr($child_field_labels, 0, strlen($child_field_labels) - 1); //Removed last comma.
        $child_field_controls = substr($child_field_controls, 0, strlen($child_field_controls) - 1); //Removed last comma.
        $child_field_parameters = substr($child_field_parameters, 0, strlen($child_field_parameters) - 2); //Removed last newline and comma.

        //The heading for the dynamic fieldset is simply the table name, with all underscores (if any) removed.
        //Also, make sure the start of each word is uppercase.
        $multifield_heading = str_replace('_',' ',$Child_Table_Name);
        $multifield_heading = ucwords($multifield_heading);

        $multifield_controls.=<<<EOD

$multifield_setup

\$multifield_settings = array(
                             'field_labels' => array($child_field_labels),
                             'field_controls' => array($child_field_controls),
                             'field_parameters' => array(
$child_field_parameters
                                                        )
                            );
\$html->draw_multifield_auto('$multifield_heading', \$multifield_settings, '$Child_Num_Particulars', '$Child_Particulars_Count');	

EOD;
        unset($Child_Table_Fields_Info);
    }
}
else die("Error in main query: " . $mysqli->error);
$result->close();


//If there's one or more child table, we need to see if the primary key of the
//parent table is an auto_increment field (simply, Control_Type=='None' and Attribute=='Primary')
//so we can add the fetch_auto_id_script part later in the processing part of the page.
$fetch_auto_id_script = '';
if($Child_Table_Add_Script != '')
{
    for($a=0;$a<$field_count;$a++)
    {
        if($field[$a]['Control_Type']=='none' && $field[$a]['Attribute']=='primary key')
        {
            $fetch_auto_id_script = "\${$field[$a]['Field_Name']} = {$dbh_name}->auto_id;";
        }
    }
}

$script_content=<<<EOD

if(isset(\$_GET['filter_field_used']) && isset(\$_GET['filter_used']) && isset(\$_GET['page_from']))
{
    \$page_from = htmlentities(\$_GET['page_from']);
    \$filter_used = htmlentities(\$_GET['filter_used']);
    \$filter_field_used = htmlentities(\$_GET['filter_field_used']);
}

if(xsrf_guard())
{
    extract(\$_POST);
    if(\$_POST['cancel']) 
    {
        log_action('Pressed cancel button', \$_SERVER[PHP_SELF]);
        header("location: $List_View_Page?filter_field=\$filter_field_used&filter=\$filter_used&page_from=\$page_from");
    }

    if(\$_POST['submit'])
    {
        log_action('Pressed submit button', \$_SERVER[PHP_SELF]);
$Date_Controls_Script
$Date_Controls_To_POST
        require_once 'validation_class.php';
        require_once 'subclasses/$class_file';
        {$dbh_name} = new $class_name;
        \$validator = new validation;

        \$message .= {$dbh_name}->sanitize(\$_POST);
        extract(\$_POST);

        if({$dbh_name}->check_uniqueness(\$_POST)) ;
        else \$message = "Record already exists with the same primary identifiers!";

        if(\$message=="")
        {
            {$dbh_name}->$add_method(\$_POST);
            $fetch_auto_id_script
$Child_Table_Add_Script
            header("location: $List_View_Page?filter_field=\$filter_field_used&filter=\$filter_used&page_from=\$page_from");
        }
    }
}
EOD;

//Now let's start working on the body of the module, the forms section.

$script_content.=<<<EOD

require_once 'subclasses/$html_subclass_file';
\$html = new $html_subclass_name;
\$html->draw_header('$module_link_name', \$message, \$message_type);
\$html->draw_listview_referrer_info(\$filter_field_used, \$filter_used, \$page_from);

EOD;

//If this table has one or more child tables in a 1-M relationship, we need to draw the dynamic fieldset for it.
//We simply just check if $Child_Table_Add_Script contains something to know that this table does have
//child tables.
if($Child_Table_Add_Script=='')
{
    $script_content.=<<<EOD

\$html->draw_controls('add');

EOD;
}

if($Child_Table_Add_Script!='')
{
    $script_content.=<<<EOD
\$html->draw_controls('off',TRUE,'Add Record',TRUE);
$multifield_controls

\$html->draw_controls_multifield_end();

EOD;
}
