<?php
//Now let's get the number of fields we have, we'll get to use this information a lot.
$field_count = count($field);

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

        require_once 'validation_class.php';
        require_once 'subclasses/$class_file';
        {$dbh_name} = new $class_name;
        \$validator = new validation;

        extract(\$_POST);

        if(\$message=="")
        {
            log_action("Exported table data to CSV", \$_SERVER['PHP_SELF']);
            \$timestamp = date('Y-m-d');
            \$csv_name = '/' . \$_SESSION['user'] . '_{$class_name}_' . \$timestamp . '.csv';
            \$filename = TMP_DIRECTORY . \$csv_name;
            \$linkname = TMP_HYPERLINK_PATH . \$csv_name;

            \$csv_contents = {$dbh_name}->export_to_csv();

            \$csv_file=fopen(\$filename,"wb");
            fwrite(\$csv_file, \$csv_contents);
            fclose(\$csv_file);
            chmod(\$filename, 0755);

            \$message='CSV file successfully generated: <a href="' . \$linkname . '">Download the CSV file.</a>';
            \$message_type='system';
        }
    }
}
EOD;

//Now let's start working on the body of the module, the forms section.

//We need to get all fields except for those specified as Control Type = None
$csv_text_fields = '';
foreach($field as $current_field)
{
    if($current_field['Control_Type'] == 'none')
    {
        //Not part of the filter fields for CSV export
    }
    else
    {
        $csv_text_fields .= '$html->draw_text_field(\'' . $current_field['Label'] . '\', \''. $current_field['Field_Name'] . '\', FALSE, \'text\', TRUE);' . "\r\n";
    }
}


$page_title = str_replace('_',' ', $class_name);

$script_content.=<<<EOD

require_once 'subclasses/$html_subclass_file';
\$html = new $html_subclass_name;
\$html->draw_header('CSV Exporter: $page_title', \$message, \$message_type);
\$html->draw_listview_referrer_info(\$filter_field_used, \$filter_used, \$page_from);

echo '<div class="container_mid">';
\$html->draw_fieldset_header('CSV Filter Settings');
\$html->draw_fieldset_body_start();

$csv_text_fields

\$html->draw_fieldset_body_end();
\$html->draw_fieldset_footer_start();
\$html->draw_submit_cancel();
\$html->draw_fieldset_footer_end();
echo '</div>';

EOD;
