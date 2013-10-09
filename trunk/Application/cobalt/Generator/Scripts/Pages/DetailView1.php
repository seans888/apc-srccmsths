<?php
$class_name_nospace = str_replace('_',' ',$class_name);
$show_in_tasklist = 'No';

//Now let's get the number of fields we have, we'll get to use this information a lot.
$field_count = count($field);

//All primary key fields are aggregated in $Primary_Keys array.
$Primary_Keys= array();

//$Get_Primary_Keys is a string aggregate of primary keys that will be used to check if the proper keys have been passed; the string 
//of primary keys will be formatted as follows: $Get_Primary_Keys = "isset($PK1) && isset($PK2) && isset($PK3)".
$Get_Primary_Keys = '';

//$Where_Primary_Keys is a string aggregate of primary keys that will be used as the WHERE clause in the main SELECT statement
//of the Edit function (querying the data of the chosen record to edit). This will be formatted as follows:
//$Where_Primary_Keys = "$PK1='$$PK1' AND $PK2='$$PK2' AND $PK3='$$PK3'";
$Where_Primary_Keys = '';

for($a=0;$a<$field_count;$a++)
{
    if($field[$a]['Attribute']=='primary key' || $field[$a]['Attribute']=='primary&foreign key') 
    {
        $Primary_Keys[] = $field[$a]['Field_Name'];
        $Get_Primary_Keys .= "isset(\$_GET['{$field[$a]['Field_Name']}']) && ";

        $Where_Primary_Keys .= "{$field[$a][Field_Name]}='\${$field[$a][Field_Name]}' AND ";
    }
}
$Get_Primary_Keys = substr($Get_Primary_Keys, 0, strlen($Get_Primary_Keys)-4); //Removed the last spaces and ampersand.
$Where_Primary_Keys = substr($Where_Primary_Keys, 0, strlen($Where_Primary_Keys)-5); //Removed the last space and 'AND' keyword.

//Check to see if some fields are set to use Date Controls
//If one or more are, then we need to add something to the processing part later
$Date_Controls_Explode='';
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

        if($field[$a]['Data_Type'] != 'unix timestamp')
        {
            $Date_Controls_Explode.=<<<EOD
        
        \$data = explode('-',\$$field_name);
        \$$dc_year[$a] = \$data[0];
        \$$dc_month[$a] = \$data[1];
        \$$dc_day[$a] = \$data[2];
EOD;
        }
    }
}


//We need to check if the current table is a parent table in a 1-M relationship.
//If it is, we need to go find its child table(s).
//For each child table, we need to create a loop that will select all records of
//that child table for the current value of the primary key.

$Child_Table_Select_Script = '';
 
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

        //Now let's get the fields of the child table.
        //Although all we need right now is the field name and attribute, we also get a few more fields
        //and store all info in an array to avoid having to re-query later on when we need the child fields again
        //along with their relevant information.
        $Child_Table_Fields_Info = array('Field_Name'=>array(),
                                         'Field_ID'=>array(),
                                         'Control_Type'=>array(),
                                         'Label'=>array());
        $Child_Table_Where_Clause='';
        $Child_Table_Set_Fields='';
        $Child_Table_Fields_Assignment='';
         
        $mysqli2->real_query("SELECT Field_ID AS 'Child_Field_ID', Field_Name, Attribute, Control_Type, Label FROM `table_fields` WHERE Table_ID='$Child_Table_ID'");
        if($result2 = $mysqli2->store_result())
        {
            $num_child_fields = $result2->num_rows;

            $inner_cntr = 0;
            for($b=0; $b<$num_child_fields; $b++)
            {
                $data2 = $result2->fetch_assoc();
                extract($data2);

                if($Attribute=='primary key' && strtoupper($Field_Name) == 'AUTO_ID')
                {
                    //Do nothing... ignore all auto_id's.
                }
                else 
                {
                    if($Control_Type!='none')
                    {
                        $Child_Table_Set_Fields .= $Field_Name . ', ';

                        if($Control_Type == 'date controls')
                        {
                        
                        
                            $Child_Table_Fields_Assignment.=<<<EOD
            \$data_temp_cf_date = explode('-',\$data[$inner_cntr]);
            \$cf_{$Child_Table_Name}_{$Field_Name}_year[\$a] = \$data_temp_cf_date[0];
            \$cf_{$Child_Table_Name}_{$Field_Name}_month[\$a] = \$data_temp_cf_date[1];
            \$cf_{$Child_Table_Name}_{$Field_Name}_day[\$a] = \$data_temp_cf_date[2];

EOD;
                        }
                        else
                        {
                            $Child_Table_Fields_Assignment.=<<<EOD
            \$cf_{$Child_Table_Name}_{$Field_Name}[\$a] = \$data[$inner_cntr];

EOD;
                        }
                        $inner_cntr++;

                        //All non-primary-key fields will be stored in an array
                        //The "cf_" prepended is needed so that the field name of the child field will never collide with a similarly
                        //named field in the parent.
                        //The "$Child_Table_Name" prepended is needed so that the field name of the child field will never collide
                        //with a similarly named field in another child of the parent.
                        $Child_Table_Fields_Info['Field_Name'][] = 'cf_' . $Child_Table_Name . '_' . $Field_Name;
                        $Child_Table_Fields_Info['Field_ID'][] = $Child_Field_ID;
                        $Child_Table_Fields_Info['Control_Type'][] = $Control_Type;
                        $Child_Table_Fields_Info['Label'][] = $Label;
                    }
                    else //This means it's a foreign key
                    {
                        $Child_Table_Where_Clause .= $Field_Name . '=\'$' . $Field_Name . '\' AND ';
                    }
                }
            }
        }
        else die("Oops... we got an error! ". $mysqli2->error);
        $result2->close();

        $Child_Table_Where_Clause = substr($Child_Table_Where_Clause, 0, strlen($Child_Table_Where_Clause) - 5); //Removed the last 'AND' along with its spaces.
        $Child_Table_Set_Fields = substr($Child_Table_Set_Fields, 0, strlen($Child_Table_Set_Fields) - 2); //Removed the last space and comma.
        $Child_Table_Fields_Assignment = substr($Child_Table_Fields_Assignment, 0, strlen($Child_Table_Fields_Assignment) - 1); //Removed the last newline.				

        $Child_Table_Select_Script.=<<<EOD
    require_once 'subclasses/$Child_Classfile';
    {$dbh_name} = new $Child_Table_Name;
    {$dbh_name}->set_fields('$Child_Table_Set_Fields');
    {$dbh_name}->set_where("$Child_Table_Where_Clause");
    if(\$result = {$dbh_name}->make_query())
    {
        \$$Child_Num_Particulars = {$dbh_name}->num_rows;
        for(\$a=0; \$a<\$$Child_Num_Particulars; \$a++)
        {
            \$data = \$result->fetch_row();
$Child_Table_Fields_Assignment
        }
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
                     
                    $mysqli2->real_query("SELECT List_ID FROM table_fields_list WHERE Field_ID='$Child_Table_Fields_Info[Field_ID]'");
                    if($result2 = $mysqli2->store_result())
                    {
                        if($result2->num_rows > 0) 
                        {
                            $USE_MULTIFIELD_SETUP = 'Predefined List';
                            $child_field_controls .= "'draw_select_field_mf',";
                            $child_field_parameters .=<<<EOD
                                                        array(\$options, '{$Child_Table_Fields_Info[Field_Name][$b]}'),

EOD;
                        }
                        else 
                        {
                            $USE_MULTIFIELD_SETUP = 'SQL Generated';
                            $child_field_controls .= "'draw_select_field_from_query_MF',";

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
\$html->detail_view = TRUE;
\$html->draw_multifield_auto('$multifield_heading', \$multifield_settings, '$Child_Num_Particulars');

EOD;

    }
}
else die("Error in main query: " . $mysqli->error);
$result->close();


$script_content=<<<EOD

if($Get_Primary_Keys)
{
    extract(\$_GET);

    \$page_from = htmlentities(\$_GET['page_from']);
    \$filter_used = htmlentities(\$_GET['filter_used']);
    \$filter_field_used = htmlentities(\$_GET['filter_field_used']);

    require_once 'subclasses/$class_file';
    {$dbh_name} = new $class_name;
    {$dbh_name}->set_where("$Where_Primary_Keys");
    if(\$result = {$dbh_name}->make_query())
    {
        \$data = \$result->fetch_assoc();
        extract(\$data);
$Date_Controls_Explode
    }

$Child_Table_Select_Script
}
elseif(xsrf_guard())
{
    extract(\$_POST);
    if(\$_POST['cancel']) 
    {
        log_action('Pressed cancel button', \$_SERVER[PHP_SELF]);
        header("location: $List_View_Page?filter_field=\$filter_field_used&filter=\$filter_used&page_from=\$page_from");
    }
}
EOD;

//Now let's start working on the body of the module, the forms section.

$script_content.=<<<EOD

require_once 'subclasses/$html_subclass_file';
\$html = new $html_subclass_name;
\$html->draw_header('Detail View: $class_name_nospace', \$message, \$message_type);
\$html->draw_listview_referrer_info(\$filter_field_used, \$filter_used, \$page_from);
\$html->detail_view = TRUE;

EOD;

//If this table has one or more child tables in a 1-M relationship, we need to draw the dynamic fieldset for it.
//We simply just check if $Child_Table_Add_Script contains something to know that this table does have
//child tables.
if($Child_Table_Select_Script=='')
{
    $script_content.=<<<EOD

\$html->draw_controls('view');

EOD;
}

if($Child_Table_Select_Script!='')
{
    $script_content.=<<<EOD
\$html->draw_controls('off',TRUE,'Detail View',TRUE,'container_mid_huge');
$multifield_controls

\$html->draw_controls_multifield_end('view');

EOD;
}
