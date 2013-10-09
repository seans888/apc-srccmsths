<?php

//This file creates the sub class definition for each table you generate.
//This process is divided into 4 phases:
//	1. Creating the $fields array, which contains the field information for all fields in the table.
//	2. Creating the table-specific connection information using the specified database connection for the table.
//	3. Creating the sub class methods - generic Add, Edit, Delete and Select methods using all specified table fields.
//  4. Define the data dictionary class file -> $Table_Name . '_DD'

//Additional (version 1.0+ specific) This also creates a _HTML subclass for each table, which extends the base
//HTML class instead of the Data_Abstraction class.

function createClass($Table_ID, $subclass_path, $mysqli, $mysqli_2, $inner_db_handle)
{
    //PHASE 1: Creating the $fields array.
    //The structure of the $fields array is pretty simple. This array is a multidimensional array that contains all
    //the fields of the table you are generating, including all necessary field information you entered.
    //Each field name is an index (the first dimension) of $fields. This index then has an array as a value, which holds
    //all the settings for that specific field.
    //Example:
    //$fields = array(
    //               'Name' => array('Value'=>'',
    //                               'Data_Type'=>'Varchar',
    //                               'Length'=>'20',
    //                               'Attribute'=>'Required'),
    //               'Address' => array('Value'=>''.
    //                                  'Data_Type'=>'Text',
    //                                  'Length'=>'N/A',
    //                                  'Attribute'=>'NONE')
    //               );
    //Note that this example merely assumes that there are 2 fields in the table ('Name' and 'Address'), and that each field
    //only has 4 settings/values (Value, Data_Type, Length, Attribute), which certainly isn't the case as there are a few more.

    //Prep: get table info
    $mysqli->real_query("SELECT Table_Name FROM `table` WHERE Table_ID='$Table_ID'");
    if($result = $mysqli->use_result())
    {
        $data = $result->fetch_assoc();
        extract($data);
    }
    else die($mysqli->error);
    $result->close();

    // 1.1 - Create empty class files. If one or both of the files already exist, delete them first.
    //Note: as of 2012-11-18, creation of empty class files have been postponed until right before the fwrite call. 


    // 1.2 - Query all field information.
    // 1.2.1 - Generic field information.
    $mysqli->real_query("SELECT Field_ID, Table_ID, Field_Name, Data_Type, Length, Attribute, Control_Type, Label, In_Listview 
                            FROM table_fields 
                            WHERE Table_ID='$Table_ID'
                        ");

    $field_array = array(); //array for aggregating all field names; useful later in method generation.
    $field_attribute_array = array(); //array for aggregating all field attributes; useful later in method generation.
    $field_control_array = array(); //array for aggregating all field control types; useful later in the edit method generation.
    if($result = $mysqli->store_result())
    {
        $fields = '$fields = array(';

        while($row = $result->fetch_assoc())
        {
            extract($row);

            if($Data_Type == 'integer') 
            {
                $char_set_method = 'generate_num_set';
                $extra_chars_allowed = '';
                $char_set_allow_space = 'false';
            }
            elseif($Data_Type == 'double or float')
            {
                $char_set_method = 'generate_num_set';
                $extra_chars_allowed = ', .';
                $char_set_allow_space = 'false';
            }
            else
            {
                $char_set_method = 'generate_alphanum_set';
                $extra_chars_allowed = '\\\' / - ( ) + = . , ! ? # % & * ; : _ "';
                $char_set_allow_space = 'true';
            }

            $field_array[] = $Field_Name; //aggregate all field names into this array.
            $field_attribute_array[] = $Attribute;
            $field_control_array[] = $Control_Type;
            $fields .= <<<EOD

                        '$Field_Name' => array('value'=>'',
                                              'data_type'=>'$Data_Type',
                                              'length'=>'$Length',
                                              'attribute'=>'$Attribute',
                                              'control_type'=>'$Control_Type',
                                              'label'=>'$Label',
                                              'extra'=>'',
                                              'in_listview'=>'$In_Listview',
                                              'char_set_method'=>'$char_set_method',
                                              'char_set_allow_space'=>'$char_set_allow_space',
                                              'extra_chars_allowed'=>'$extra_chars_allowed',
                                              'trim'=>'trim',
                                              'valid_set'=>array($valid_set)
EOD;

            $BYPASS_LIST_SOURCE = "NOT YET";

            //1.2.2 - Check if necessary to create the necessary date controls elements
            if($Control_Type == 'date controls')
            {
                if(strtoupper($Field_Name) != 'DATE')
                {
                    if(strlen($Field_Name) <=20) $prepend = $Field_Name;
                    else $prepend = substr($Field_Name,0,20);
                    $dc_year = $prepend . '_year';
                    $dc_month = $prepend . '_month';
                    $dc_day = $prepend . '_day';
                }
                else
                {
                    $dc_year = 'year';
                    $dc_month = 'month';
                    $dc_day = 'day';
                }
                $fields .= <<<EOD
,
                                              'date_elements'=>array('$dc_year','$dc_month','$dc_day')
EOD;
            }
            else $fields .= <<<EOD
,
                                              'date_elements'=>array('','','')
EOD;


            // 1.2.3 - Check if "Predefined_List" is applicable .
            if($Control_Type == "radio buttons" || $Control_Type == "drop-down list")
            {
                $mysqli_2->real_query("SELECT List_ID FROM table_fields_list WHERE Field_ID='$Field_ID'");
                if($result_2 = $mysqli_2->store_result())
                {
                    if($result_2->num_rows > 0)
                    {
                        $data = $result_2->fetch_assoc();
                        extract($data);

                        $inner_db_handle->real_query("SELECT List_Item FROM table_fields_predefined_list_items WHERE List_ID='$List_ID'");
                        if($inner_result = $inner_db_handle->use_result())
                        {
                            $list_items = '';
                            while($data = $inner_result->fetch_row())
                            {
                                $list_items .=  "'$data[0]',";
                            }
                            $list_items = substr($list_items,0,strlen($list_items)-1); //Remove the last comma.
                        }
                        else die($inner_db_handle->error);
                        $BYPASS_LIST_SOURCE = "YES, PLEASE BYPASS!";

                        $fields .= <<<EOD
,
                                              'list_type'=>'predefined',
                                              'list_settings'=>array('per_line'=>TRUE,
                                                                     'items'  =>array($list_items),
                                                                     'values' =>array($list_items))
EOD;

                    }
                    $result_2->close();

                }
                else die($mysqli_2->error);
            }
            else $BYPASS_LIST_SOURCE = 'NOPE';

            // 1.2.4 - Check if "Book_List_Generator" is applicable.
            if($Control_Type == "special textbox")
            {
                $mysqli_2->real_query("SELECT Book_List_Generator FROM table_fields_book_list WHERE Field_ID='$Field_ID'");
                if($result_2 = $mysqli_2->use_result())
                {
                    $data = $result_2->fetch_assoc();
                    extract($data);

                    $fields .= <<<EOD
,
                                              'book_list_generator'=>'$Book_List_Generator'
EOD;
                    $result_2->close();
                }
                else die($mysqli_2->error);
            }
            else $fields .= <<<EOD
,
                                              'book_list_generator'=>''
EOD;

            // 1.2.5 - Check if "List_Source_Select/Where" is applicable.
            if($Control_Type == "drop-down list" && $BYPASS_LIST_SOURCE != "YES, PLEASE BYPASS!")
            {
                require_once 'ListFromSQL.php';
                $settings = list_from_SQL_settings($Field_ID);

                $fields .= <<<EOD
,
                                              'list_type'=>'sql generated',
                                              'list_settings'=>array($settings)
EOD;
                
            }
            elseif($BYPASS_LIST_SOURCE == 'NOPE') $fields .= <<<EOD
,
                                              'list_type'=>'',
                                              'list_settings'=>array('')
EOD;

            //Closing parenthesis - remember that each field info is in an array, right? Well,
            //this is the closing parenthesis for that.
            $fields .= '),';
        }
        $result->close();
    }
    else die($mysqli->error);

    $fields = substr($fields,0, strlen($fields)-1);
    $fields .= "\r\n                       );"; //This line prints the closing ');' aligned with the opening '('.


    //PHASE 2: Creating the Database Connection variables.
    //Just query for the Hostname, Username, Password and Database of the connection specified in the current table.
    $mysqli->real_query("SELECT a.DB_Connection_ID, a.Hostname, a.Username, a.Password, a.Database 
                            FROM `database_connection` a, `table` b 
                            WHERE a.DB_Connection_ID = b.DB_Connection_ID AND 
                                  b.Table_ID = '$Table_ID'
                        ");
    if($result = $mysqli->use_result())
    {
        $data = $result->fetch_assoc();
        extract($data);

        //Check if this is the project's default connection;
        //If not, check if some values are the same.
        //All values that are identical will not be overridden by the subclass.
        $mysqli_2->real_query("SELECT a.DB_Connection_ID, a.Hostname, a.Username, a.Password, a.Database 
                                FROM `database_connection` a, `project` b 
                                WHERE a.DB_Connection_ID = b.Database_Connection_ID AND 
                                      b.Project_ID = '$_SESSION[Project_ID]'
                            ");
        if($result_2 = $mysqli_2->use_result())
        {
            $data = $result_2->fetch_row();
            $def_DBCon = $data[0];
            $def_Host = $data[1];
            $def_User = $data[2];
            $def_Pass = $data[3];
            $def_DB = $data[4];
        }
        else die('Error while trying to get the default connection: ' . $mysql2->error);
        $result_2->close();

        if($def_DBCon != $DB_Connection_ID)
        {
            if($def_Host !=  $Hostname)
            {
                $database_connection_variables.=<<<EOD

    var \$db_host='$Hostname';
EOD;
            }


            if($def_User !=  $Username)
            {
                $database_connection_variables.=<<<EOD

    var \$db_user='$Username';
EOD;
            }


            if($def_Pass !=  $Password)
            {
                $database_connection_variables.=<<<EOD

    var \$db_pass='$Password';
EOD;
            }


            if($def_DB !=  $Database)
            {
                $database_connection_variables.=<<<EOD

    var \$db_use='$Database';
EOD;
            }
        }
        $database_connection_variables.=<<<EOD
    var \$tables='$Table_Name';
EOD;
    }
    else die($mysqli->error);
    $result->close();

    //PHASE 3: Creating the subclass methods.
    //Using the fields conveniently aggregated into $fields_array, we simply create a set of generic methods for this subclass,
    //namely Add, Edit, Delete, and Select queries.
    //Primary keys can be found by looking at the $field_attributes_array. If an index contains "Primary Key", then the same index
    //in the $fields_array contains the primary key field.

    $num_fields = count($field_array);

    $edit_field_list='';
    $field_list='';
    $value_list='';
    $edit_primary_key_list='';          //We create a separate primary key list for the edit and delete queries because the edit query
    $delete_primary_key_list='';        //needs to make sure that editable primary key fields are compared with their original values.
    $reverse_edit_primary_key_list='';  //This one is identical to edit_primary_key_list, except that '=' is changed to '!=', and 'AND' is changed to 'OR', for uniqueness checking in edit scenarios
    $temp_auto_id_key='';               //To hold any auto_id keys. If the table has no other identifiers, this will be used. Otherwise, the value here will simply be discarded.
    $temp_reverse_auto_id_key='';       //As above, but for the use of the reverse_edit_primary_key_list var.
    $csv_export_field_list = '';        
    $csv_export_fields_remove_double_quotes = '';
    for($a=0; $a<$num_fields; $a++) 
    {
        $field_list.= $field_array[$a] . ", ";
        $value_list.="'$" . $field_array[$a]. "', ";

        if($field_attribute_array[$a] != "primary key" && $field_attribute_array[$a] != "primary&foreign key")
        {
            $edit_field_list.= $field_array[$a] . " = '$" . $field_array[$a] . "', "; 
        }
        elseif(($field_attribute_array[$a] == "primary key" || $field_attribute_array[$a] == "primary&foreign key") && $field_control_array[$a] != 'none')
        {
            $edit_field_list.= $field_array[$a] . " = '$" . $field_array[$a] . "', "; 

            //Here, for primary key fields that are editable (they have a control specified), they are compared
            //to their original value (value before the edit happened), hence the "$Orig_" prefix to the variable name.
            $edit_primary_key_list .= $field_array[$a] . " = '\$orig_" . $field_array[$a] . "' AND ";
            $reverse_edit_primary_key_list .= $field_array[$a] . " != '\$orig_" . $field_array[$a] . "' OR ";
            $delete_primary_key_list .= $field_array[$a] . " = '$" . $field_array[$a] . "' AND ";
        }
        elseif(($field_attribute_array[$a] == "primary key" || $field_attribute_array[$a] == "primary&foreign key") && $field_control_array[$a] == 'none')
        {
            if(strtoupper($field_array[$a]) == 'AUTO_ID')
            {
                $temp_auto_id_key .= $field_array[$a] . " = '$" . $field_array[$a] . "' AND ";
                $temp_reverse_auto_id_key .= $field_array[$a] . " != '$" . $field_array[$a] . "' OR ";
            }
            else
            {
                $edit_primary_key_list .= $field_array[$a] . " = '$" . $field_array[$a] . "' AND ";
                $reverse_edit_primary_key_list .= $field_array[$a] . " != '$" . $field_array[$a] . "' OR ";
                $delete_primary_key_list .= $field_array[$a] . " = '$" . $field_array[$a] . "' AND ";
            }
        }

        $csv_export_fields_remove_double_quotes.=<<<EOD
            \$$field_array[$a] = str_replace('"',"''", \$$field_array[$a]);

EOD;

        $csv_export_field_list.="\"' . \$$field_array[$a] . '\",";
    }
    //Check if we should use the values (if any) stored in the temp auto id vars.
    //We only need to use them if the main vars are empty.
    if($edit_primary_key_list == '') $edit_primary_key_list = $temp_auto_id_key;
    if($delete_primary_key_list == '') $delete_primary_key_list = $temp_auto_id_key;
    if($reverse_edit_primary_key_list == '') $reverse_edit_primary_key_list = $temp_reverse_auto_id_key;

    //Remove last comma
    $csv_export_field_list = substr($csv_export_field_list,0, strlen($csv_export_field_list)-1);

    //Remove the last comma and whitespace:
    $field_list = substr($field_list,0, strlen($field_list)-2);
    $value_list = substr($value_list,0, strlen($value_list)-2);
    $edit_field_list = substr($edit_field_list,0, strlen($edit_field_list)-2); 

    //Remove the last 'AND' and whitespace:
    $primary_key_list = substr($primary_key_list,0, strlen($primary_key_list)-5);
    $edit_primary_key_list = substr($edit_primary_key_list,0, strlen($edit_primary_key_list)-5);
    $reverse_edit_primary_key_list = substr($reverse_edit_primary_key_list,0, strlen($reverse_edit_primary_key_list)-4);
    $delete_primary_key_list = substr($delete_primary_key_list,0, strlen($delete_primary_key_list)-5);

    //For the methods that check the uniqueness of a new record being added or edited, they use similar
    //primary key lists to the edit and delete primary key lists.
    $check_uniqueness_primary_key_list = $delete_primary_key_list;
    $check_uniqueness_for_editing_primary_key_list = $delete_primary_key_list . ' AND (' . $reverse_edit_primary_key_list . ')';
    
    //PHASE 4: Defining the data dictionary class file (no-brainer)
    $DD_filename = $Table_Name . '_dd.php';
    $DD_classname = $Table_Name . '_dd';

    //END: Before writing to the file itself, aggregate all content into $subclass_content (data abstraction subclass)

    $subclass_content = <<<EOD
<?php
require_once '$DD_filename';
class $Table_Name extends data_abstraction
{
    var \$fields = array();
$database_connection_variables

    function $Table_Name()
    {
        \$this->fields = $DD_classname::load_dictionary();
        \$this->relations = $DD_classname::load_relationships();
        \$this->subclasses = $DD_classname::load_subclass_info();
    }

    function add(\$param, \$log_query=TRUE)
    {
        \$this->escape_arguments(\$param);
        extract(\$param);
        \$this->set_query_type('INSERT');
        \$this->set_fields('$field_list');
        \$this->set_values("$value_list");
        \$this->make_query(TRUE,\$log_query);
    }

    function edit(\$param, \$log_query=TRUE)
    {
        \$this->escape_arguments(\$param);
        extract(\$param);
        \$this->set_query_type('UPDATE');
        \$this->set_update("$edit_field_list");
        \$this->set_where("$edit_primary_key_list");
        \$this->make_query(TRUE,\$log_query);
    }

    function del(\$param, \$log_query=TRUE)
    {
        \$this->escape_arguments(\$param);
        extract(\$param);
        \$this->set_query_type('DELETE');
        \$this->set_where("$delete_primary_key_list");
        \$this->make_query(TRUE,\$log_query);
    }

    function select(\$log_query=TRUE)
    {
        \$this->set_query_type('SELECT');
        \$result = \$this->make_query(TRUE,\$log_query);
        return \$result;
    }

    function check_uniqueness(\$param)
    {
        \$this->escape_arguments(\$param);
        extract(\$param);
        \$this->set_query_type('SELECT');
        \$this->set_where("$check_uniqueness_primary_key_list");
        \$this->make_query(TRUE,\$log_query);
        if(\$this->num_rows > 0) \$this->is_unique = FALSE;
        else \$this->is_unique = TRUE;

        return \$this->is_unique;
    }

    function check_uniqueness_for_editing(\$param)
    {
        \$this->escape_arguments(\$param);
        extract(\$param);
        \$this->set_query_type('SELECT');
        \$this->set_where("$check_uniqueness_for_editing_primary_key_list");
        \$this->make_query(TRUE,\$log_query);
        if(\$this->num_rows > 0) \$this->is_unique = FALSE;
        else \$this->is_unique = TRUE;

        return \$this->is_unique;
    }

    function export_to_csv()
    {
        \$result = \$this->select();
        while(\$data = \$result->fetch_assoc())
        {
            extract(\$data);

$csv_export_fields_remove_double_quotes
            \$csv_contents .= '$csv_export_field_list' . "\\r\\n";
        }

        return \$csv_contents;
    }
}

EOD;

    //Write to the file - data abstraction subclass
    $filename = $subclass_path .  $Table_Name . '.php';
    if(file_exists($filename)) unlink($filename);
    $newfile=fopen($filename,"ab");
    fwrite($newfile, $subclass_content);
    fclose($newfile);
    chmod($filename, 0777);


    //Aggregate all content to $subclass_content, this time for the HTML subclass.
    $Table_Name_HTML = $Table_Name . '_html';

    $subclass_content = <<<EOD
<?php
require_once '$DD_filename';
class $Table_Name_HTML extends html
{
    function $Table_Name_HTML()
    {
        \$this->fields = $DD_classname::load_dictionary();
        \$this->relations = $DD_classname::load_relationships();
        \$this->subclasses = $DD_classname::load_subclass_info();
    }
}

EOD;

    //Write to the file - HTML subclass
    $filename_HTML = $subclass_path .  $Table_Name . '_html.php';
    if(file_exists($filename_HTML)) unlink($filename_HTML);
    $newfile_HTML=fopen($filename_HTML,"ab");
    fwrite($newfile_HTML, $subclass_content);
    fclose($newfile_HTML);
    chmod($filename_HTML, 0777);

    //*******************************************************
    //Define the data dictionary class before writing to file
    
    //Here, we just set the filenames of the two subclasses, this will
    //also be stored by the data dictionary class
    $HTML_subclass_file = $Table_Name_HTML . '.php';
    $Data_subclass_file = $Table_Name . '.php';
    
    //We also need to get the relationships of this table/class.
    //    $relations = array('1'=>array('Type'=>'1-1',
    //                                      'Table'=>'position',
    //                                      'Link_parent'=>'position_id',
    //                                      'Link_child'=>'position_id',
    //                                      'Link_subtext'=>array('position'),
    //                                      'Where_clause'=>''));
    
    $mysqli->real_query("SELECT a.`Relation_ID`, a.`Relation`, a.`Child_Field_ID`, a.`Child_Field_Subtext`,
                                b.`Field_Name`  
                            FROM `table_relations` a, `table_fields` b 
                            WHERE a.`Child_Field_ID` = b.`Field_ID` AND 
                                  b.`Table_ID` = '$Table_ID'");
    if($result = $mysqli->store_result())
    {
        $relations = '$relations = array(';
        $put_comma=FALSE;
        for($a=1; $a<=$result->num_rows; $a++)
        {
            $data = $result->fetch_assoc();
            extract($data);

            $Link_child = $Field_Name;

            if($Relation == 'ONE-to-ONE') $Relation = '1-1';
            elseif($Relation == 'ONE-to-MANY') $Relation = '1-M';

            $arrSubtext = explode(',', $Child_Field_Subtext);
            $Child_Field_Subtext='';
            foreach($arrSubtext as $subtext)
            {
                $subtext = trim($subtext);
                if($Child_Field_Subtext != '') $Child_Field_Subtext .= ',';
                $Child_Field_Subtext .= "'$subtext'";
            }
            
            //Finally, get the parent table&field name
            $mysqli_2->real_query("SELECT b.`Field_Name`, c.`Table_Name`
                                        FROM `table_relations` a, `table_fields` b, `table` c
                                        WHERE a.`Relation_ID` = '$Relation_ID' AND 
                                              a.`Parent_Field_ID` = b.`Field_ID` AND 
                                              b.`Table_ID` = c.`Table_ID`");
            if($result_2 = $mysqli_2->store_result())
            {
                $data = $result_2->fetch_row();
                $Parent_Field = $data[0];
                $Parent_Table = $data[1];
                $result_2->close();
            }
            else
            {
                die($mysqli_2->error);
            }
            
            if($put_comma) $relations .= ",\n                           ";
            $relations .= "'$a'=>array('type'=>'$Relation',
                                      'table'=>'$Parent_Table',
                                      'link_parent'=>'$Parent_Field',
                                      'link_child'=>'$Link_child',
                                      'link_subtext'=>array($Child_Field_Subtext),
                                      'where_clause'=>'')";
            $put_comma = TRUE;
        }
        $relations .= ');';
        $result->close();
    }

    $data_dictionary_class = <<<EOD
<?php
class $DD_classname
{
    static function load_dictionary()
    {
        $fields
        return \$fields;
    }

    static function load_relationships()
    {
        $relations

        return \$relations;
    }

    static function load_subclass_info()
    {
        \$subclasses = array('html_file'=>'$HTML_subclass_file',
                            'html_class'=>'$Table_Name_HTML',
                            'data_file'=>'$Data_subclass_file',
                            'data_class'=>'$Table_Name');
        return \$subclasses;
    }

}
EOD;

    //Write to the file - DD subclass
    $filename_DD = $subclass_path .  $Table_Name . '_dd.php';
    if(file_exists($filename_DD)) unlink($filename_DD);
    $newfile_DD=fopen($filename_DD,"ab");
    fwrite($newfile_DD, $data_dictionary_class);
    fclose($newfile_DD);
    chmod($filename_DD, 0777);

}
