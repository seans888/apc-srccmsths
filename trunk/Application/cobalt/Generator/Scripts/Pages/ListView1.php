<?php
$class_name_nospace = str_replace('_',' ',$class_name);
$show_in_tasklist = 'Yes';

//Now let's get the number of fields we have.
$field_count = count($field);

//$Listview_Fields is a string aggregate of all fields that will be selected and shown on the listview,
//formatted as follows: $Listview_Fields = "$Field1, $Field2, $Field3"
$Listview_Fields = '';

//$Filter_config_values is same as above, formatted differently for use as drop-down config values,
//formatted as follows: $Filter_config_values = "'$Field1', '$Field2', '$Field3'"
$Filter_config_values = '';

//$Filter_config_items is same as above,
//formatted as follows: $Filter_config_items = "'$Field1', '$Field2', '$Field3'"
//Difference with above is that each field has underscores replaced with spaces, the then ucwords() is used to
//try to capitalize them properly for a better output.
$Filter_config_items = '';

//$Listview_Fields_Var is a string aggregate similar to $Listview_Fields, except that we need to print 
//the dollar sign as well. Format = "$$Field1, $$Field2, $$Field3"
$Listview_Fields_Var = '';

//$Listview_Fields_Var_Clean contains the code that will pass the ListView fields to cobalt_htmlentities() - in other words, "cleaning"
//the output to make it safe.
$Listview_Fields_Var_Clean = '';

//$Listview_Columns is a string aggregate of all fields that will be used as column labels in the listview table,
//formatted as follows: $Listview_Columns = "<td>$FieldLabel1</td><td>$FieldLabel2</td><td>$FieldLabel3</td>"
$Listview_Columns = '';

//$Primary_Keys is used to send the primary keys of a record to the
//edit, detail view or delete page, assigned to the anchor tag in the operation links of each record. The format is:
//$Primary_Keys = "$PK1=$$PK1&$PK2=$$PK2&$PK3=$$PK3";
$Primary_Keys='';

//$data_con_Settings is a string aggregate composed of several lines that will be used to set the parameters for the main SELECT statement.
//This will allow for a more complex query in case one is needed to handle foreign keys in case the table has one or many such fields.
$data_con_Settings='';

//$data_con_Tables is the argument for this line '$data_con->set_table($data_con_Tables)'.
$data_con_Tables='';


//We just need the primary keys...
for($a=0;$a<$field_count;$a++)
{
    if($field[$a]['Attribute']=='primary key' || $field[$a]['Attribute']=='primary&foreign key') 
    {
        $Primary_Keys .= "{$field[$a][Field_Name]}=\${$field[$a][Field_Name]}&";
    }
}
$Primary_Keys = substr($Primary_Keys, 0, strlen($Primary_Keys)-1); //Removed the last ampersand (&).

//Now we have to get the path and filename of the edit, delete, and detail view pages:
$Edit_Path = '';
$Delete_Path = '';
$DetailView_Path = '';

//Edit page first...
$mysqli->real_query("SELECT a.Path_Filename FROM table_pages a, page b WHERE a.Table_ID='$Table_ID' AND a.Page_ID=b.Page_ID AND b.Page_Name LIKE 'Edit%'");
if($result = $mysqli->store_result())
{
    while($data = $result->fetch_assoc())
        $Edit_Path = basename($data['Path_Filename']);
}
else die($mysqli->error);

if($Edit_Path=='') $Edit_Path = '/' . $Base_Directory . '/edit_' . $class_file;

//Delete page...
$mysqli->real_query("SELECT a.Path_Filename FROM table_pages a, page b WHERE a.Table_ID='$Table_ID' AND a.Page_ID=b.Page_ID AND b.Page_Name LIKE 'Delete%'");
if($result = $mysqli->store_result())
    while($data = $result->fetch_assoc())
        $Delete_Path = basename($data['Path_Filename']);

if($Delete_Path=='') $Delete_Path = '/' . $Base_Directory . '/delete_' . $class_file;

//DetailView page...
$mysqli->real_query("SELECT a.Path_Filename FROM table_pages a, page b WHERE a.Table_ID='$Table_ID' AND a.Page_ID=b.Page_ID AND b.Page_Name LIKE 'DetailView%'");
if($result = $mysqli->store_result())
    while($data = $result->fetch_assoc())
        $DetailView_Path = basename($data['Path_Filename']);

if($DetailView_Path=='') $DetailView_Path = '/' . $Base_Directory . '/detailview_' . $class_file;


//CSV module link
$mysqli->real_query("SELECT a.Path_Filename FROM table_pages a, page b WHERE a.Table_ID='$Table_ID' AND a.Page_ID=b.Page_ID AND b.Page_Name LIKE 'CSVExport%'");
if($result = $mysqli->store_result())
    while($data = $result->fetch_assoc())
        $csv_module_link = basename($data['Path_Filename']);

if($csv_module_link=='') $csv_module_link = 'csv_' . $class_file;

$script_content=<<<EOD

if(isset(\$_GET['filter'])) 
{
    extract(\$_GET);
    if(\$current_page < 1) \$current_page = \$page_from;
}

if(xsrf_guard())
{
    extract(\$_POST);
    \$filter = quote_smart(\$filter);
    if(\$_POST['cancel']) 
    {
        log_action('Pressed cancel button', \$_SERVER[PHP_SELF]);
        header('location: ' . HOME_PAGE);
    }
}

if(trim(\$filter)!='') 
{
    \$enc_filter = urlencode(quote_smart(\$filter));
}
if(trim(\$filter_field)!='')
{
   \$enc_filter_field = urlencode(quote_smart(\$filter_field));
}

require_once 'subclasses/$html_subclass_file';
\$html = new $html_subclass_name;
\$html->get_listview_fields();
\$lst_fields = \$html->lst_fields;
\$arr_fields = \$html->arr_fields;
\$arr_field_labels = \$html->arr_field_labels;
\$lst_filter_fields = \$html->lst_filter_fields;
\$arr_filter_field_labels = \$html->arr_filter_field_labels;

require_once 'subclasses/$class_file';
\$data_con = new $class_name;
\$data_con->get_join_clause();
\$data_con->set_table(\$data_con->join_clause);
\$data_con->set_fields(\$lst_fields);
if(\$filter_field!='') \$data_con->set_where("\$filter_field LIKE '%\$filter%'");

\$data_con->make_query();
\$total_records = \$data_con->num_rows;

require_once 'paged_result_class.php';
\$results_per_page = 50;
\$pager = new paged_result(\$total_records, \$results_per_page);
\$pager->get_page_data(\$result_pager, \$current_page);
\$current_page = \$pager->current_page;
\$data_con->set_limit(\$pager->offset, \$pager->records_per_page);

EOD;

//Now let's start working on the body of the module, the forms section.
//First step is to require the HTML generating class, aptly named "HTML_Class.php", then draw the header and page title.
//The page title is simply "List View: " + the class name / table name.

$script_content.=<<<EOD

\$html = new $html_subclass_name;
\$html->draw_header('List View: $class_name_nospace', \$message, \$message_type);
require_once 'subclasses/../../javascript/submitenter.php';
?>
<fieldset class="container">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td align="left" colspan="2">
    <?php
    \$html->draw_button('SPECIAL','button1','cancel','BACK');
    if(check_link('$add_module_link_name')) echo "&nbsp; &nbsp; <a href='$add_module_link?filter_field_used=\$enc_filter_field&filter_used=\$enc_filter&page_from=\$current_page'>$add_module_display_name</a>";
    if(check_link('$view_module_link_name')) echo "&nbsp; &nbsp; <a href='$csv_module_link'> Export data </a>";
    ?>
    <br><br>
    </td>
</tr>
<tr class="listRowEven">
    <td align="left">
    &nbsp; &nbsp; Filter: 
    <?php 
    \$config_items = array();
    \$config_values = array();
    foreach(\$arr_filter_field_labels as \$label) \$config_items[] = ucwords(\$label);
    \$data = explode(',', \$lst_filter_fields);
    foreach(\$data as \$field) \$config_values[] = trim(\$field);
    \$config = array('items'=>\$config_items,
                    'values'=>\$config_values);
                    
    \$html->draw_select_field(\$config,'','filter_field',FALSE);
    echo '&nbsp;';
    \$filter = stripslashes(\$filter);
    \$html->draw_text_field('','filter',FALSE,'',FALSE,'onKeyPress="submitenter(this,event)"');
    echo '&nbsp;';
    \$html->draw_button('GO'); ?>

    </td>
    <td align=right>

    <?php echo \$pager->draw_paged_result('onKeyPress="submitenter(this,event)"'); ?>

    </td>
</tr>
<?php echo \$pager->draw_nav_links(\$enc_filter, \$enc_filter_field);?>
<tr>
    <td colspan="2">
    <hr>
    </td>
</tr>
</table>

<table border=1 width=100% class="listView">
<tr class="listRowHead">
    <td class="oper_col">Operations</td>
    <?php
    foreach(\$arr_field_labels as \$label) 
    {
        echo '<td>' . \$label . '</td>';
    }
    ?>
</tr>

<?php
    if(\$result = \$data_con->make_query())
    {
        while(\$row = \$result->fetch_assoc())
        {
            if(\$a%2 == 0) \$class = 'listRowEven';
            else \$class = 'listRowOdd';
            \$a++;
            extract(\$row);
            echo "<tr class=\$class><td align='center'><a href='$DetailView_Path?filter_field_used=\$enc_filter_field&filter_used=\$enc_filter&page_from=\$current_page&$Primary_Keys'><img src='/$Base_Directory/images/view.png' alt='View' title='View'></a>";
            if(check_link('$edit_module_link_name')) echo "&nbsp;&nbsp;<a href='$Edit_Path?filter_field_used=\$enc_filter_field&filter_used=\$enc_filter&page_from=\$current_page&$Primary_Keys'><img src='/$Base_Directory/images/edit.png' alt='Edit' title='Edit'></a>";
            if(check_link('$delete_module_link_name')) echo "&nbsp;&nbsp;<a href='$Delete_Path?filter_field_used=\$enc_filter_field&filter_used=\$enc_filter&page_from=\$current_page&$Primary_Keys'><img src='/$Base_Directory/images/delete.png' alt='Delete' title='Delete'></a>";
            echo '</td>';

            foreach(\$arr_fields as \$field)
            {
                if(is_array(\$field))
                {
                    echo '<td class="listCell">';
                    foreach(\$field as \$subtext)
                    {
                        echo cobalt_htmlentities(\$\$subtext, ENT_QUOTES) . ' ';
                    }
                    echo '</td>';
                }
                else
                {
                    echo '<td class="listCell">' . cobalt_htmlentities(\$\$field, ENT_QUOTES) . '</td>';
                }
            }
            echo "</tr>\\n";
        }
        \$result->close();
    }
    else error_handler('Encountered an error while retrieving records.', \$data_con->error);
    \$data_con->close_db();
?>
</table>
<table border="0" width="100%">
<?php echo \$pager->draw_nav_links(\$enc_filter, \$enc_filter_field);?>
<tr>
    <td colspan="2"><hr><br></td>
</tr>
</table>
<?php 
\$html->draw_button('SPECIAL','button1','cancel','BACK');
if(check_link('$add_module_link_name')) echo "&nbsp; &nbsp; &nbsp;<a href='$add_module_link?filter_field_used=\$enc_filter_field&filter_used=\$enc_filter&page_from=\$current_page'>$add_module_display_name</a>";
echo '</fieldset>';
EOD;
