<?php
class html
{
    var $fields = array();
    var $exception = array();
    var $relations = array();
    var $subclasses =array();
 
    var $with_form = FALSE;
    var $detail_view = FALSE;
    var $form_name = 'MyForm';
    var $form_id = 'MyForm';
    var $lst_fields = '';
    var $lst_field_labels = '';
    var $arr_fields = array();
    var $arr_field_labels = array();
    var $mf_col_align = array(); //to specify the alignment of the columns in a multifield (left, right, center)
    var $year_set = '';
    var $date_control_year_start='1950';
    var $date_control_year_end='2050';
    var $container_style_default='container_mid';
    var $container_style_multifield='container_mid_huge';

    function display_errors($error_message)
    {
        if($error_message!="")
        {
            echo '<div class="messageError">';
            echo "<table border=0 width=100%>";
            echo "<tr><td width='60' valign='top'>";
            echo "<img src='/" . BASE_DIRECTORY . "/images/icons/warn2.png'>";
            echo "</td>";
            echo "<td>";
            echo $error_message;
            echo "</td></tr></table></div>";
        }
    }

    function display_info($message)
    {
        if($message!="")
        {
            echo '<div class="messageInfo">';
            echo "<table border=0 width=100%>";
            echo "<tr><td width='60' valign='top'>";
            echo "<img src='/" . BASE_DIRECTORY . "/images/icons/info.png'>";
            echo "</td>";
            echo "<td>";
            echo "$message";
            echo "</td></tr></table></div>";
        }
    }

    function display_message($message)
    {
        if($message!="")
        {
            echo '<div class="messageSystem">';
            echo "<table border=0 width=100%>";
            echo "<tr><td width='60' valign='top'>";
            echo "<img src='/" . BASE_DIRECTORY . "/images/icons/ok.png'>";
            echo "</td>";
            echo "<td>";
            echo "$message";
            echo "</td></tr></table></div>";
        }
    }

    function display_tip($message)
    {
        if($message!="")
        {
            echo '<div class="messageTip">';
            echo "<table border=0 width=100%>";
            echo "<tr><td width='60' valign='top'>";
            echo "<img src='/" . BASE_DIRECTORY . "/images/icons/tip.png'>";
            echo "</td>";
            echo "<td>";
            echo "$message";
            echo "</td></tr></table></div>";
        }
    }

    function draw_controls($type='', $table_tags=TRUE, $title='', $has_multi_field=FALSE)
    {
        if($has_multi_field)
        {
            $container_class = $this->container_style_multifield;
        }
        else
        {
            $container_class = $this->container_style_default;
        }

        if(trim($type)=='') 
        {
            $type='add';
        }
        else
        {
            $type = strtolower($type);
        }

        if($title == '')
        {
            $title=strtoupper($type) . ' RECORD';
        }

        if($table_tags==TRUE) 
        {
            echo '<div class="'. $container_class . '">' . "\r\n";
            $this->draw_fieldset_header($title);
            $this->draw_fieldset_body_start();
        }
        
        foreach($this->fields as $field_name=>$field_struct)
        {
            if(!in_array($field_name,$this->exception))
            {
                $this->draw_field($field_name, TRUE);
            }
        }

        if($has_multi_field)
        {
            //do nothing
        }
        else
        {
            if($table_tags==TRUE)
            {
                $this->draw_fieldset_body_end();
                $this->draw_fieldset_footer_start();
            }
            
            switch($type)
            {
                case 'off'      : break;
                case 'view'     : $this->draw_button('SPECIAL','button1','cancel','BACK',TRUE,2); break;
                case 'delete'   : $this->draw_submit_cancel(TRUE,2,'delete','DELETE'); break;
                case 'add'      : 
                case 'edit'     : 
                default         : $this->draw_submit_cancel(); break;
            }
            
            if($table_tags==TRUE)
            {
                $this->draw_fieldset_footer_end();
                echo '</div>' . "\r\n";
            }
        }
    }

    function draw_controls_multifield_end($button_set='submit')
    {
        echo '</table>
              </fieldset>
              <fieldset class="bottom">';

        switch($button_set)
        {
            case 'view'   : $this->draw_button('SPECIAL','button1','cancel','BACK',TRUE,2); break;
            case 'delete' : $this->draw_submit_cancel(TRUE,2,'delete','DELETE'); break;
            case 'submit' : 
            default       : $this->draw_submit_cancel(); break;
        }

        echo '</fieldset>
              </div>';
    }

    function draw_date_field($draw_table_tags=TRUE, $label='Date', $date_year='year', $date_month='month', $date_day='day', $year_set='', $detail_view=FALSE)
    {
        if($draw_table_tags) echo '<tr><td class="label">' . $label . ':</td><td>' . "\r\n";

        if($year_set=='')
        {
            if($this->year_set == '')
            {
               for($a=$this->date_control_year_start; $a<$this->date_control_year_end; $a++)
                {
                    make_list_array($year_set, $a);
                }
            }
            else
            {
                $year_set == $this->year_set;
            }
        }

        $array_year = array('items'  => $year_set,
                            'values' => $year_set);

        $array_month = array('items'  => array('January','February','March','April','May','June','July','August','September','October','November','December'),
                             'values' => array('01','02','03','04','05','06','07','08','09','10','11','12'));

        $array_day = array('items'  => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16',
                                             '17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'),
                           'values' => array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16',
                                             '17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'));

        global $$date_year, $$date_month, $$date_day;

        if($$date_year=='')
        {
            $date = date('m-j-Y');
            $date = explode('-', $date);
            $$date_month = $date[0];
            $$date_day = $date[1];
            $$date_year = $date[2];
        }

        if($detail_view == FALSE)
        {
            $this->draw_select_field($array_month, '', $date_month, FALSE);
            $this->draw_select_field($array_day, '', $date_day, FALSE);
            $this->draw_select_field($array_year, '', $date_year, FALSE);
        }
        else
        {
            echo '<p class="detail_view">' . $$date_year . '-' . $$date_month . '-' . $$date_day . '</p>' . "\r\n";
        }

        if($draw_table_tags) echo '</td></tr>'. "\r\n";
    }

    function draw_date_field_mf($param, $cntr)
    {
        $detail_view = $this->detail_view;

        $date_year  = $param[0];
        $date_month = $param[1];
        $date_day   = $param[2];
        $year_set  = $param[3];

        if($date_year=='')  $date_year='year';
        if($date_month=='') $date_month='month';
        if($date_day=='')   $date_day='day';

        if($year_set=='')
        {
            if($this->year_set == '')
            {
               for($a=$this->date_control_year_start; $a<$this->date_control_year_end; $a++)
                {
                    make_list_array($year_set, $a);
                }
            }
            else
            {
                $year_set == $this->year_set;
            }
        }

        $array_year = array('items'  => $year_set,
                            'values' => $year_set);

        $array_month = array('items'  => array('January','February','March','April','May','June','July','August','September','October','November','December'),
                             'values' => array('01','02','03','04','05','06','07','08','09','10','11','12'));

        $array_day = array('items'  => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16',
                                             '17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'),
                           'values' => array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16',
                                             '17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'));

        global $$date_year, $$date_month, $$date_day;

        if(${$date_year}[$cntr]=='')
        {
            $date = date('m-j-Y');
            $date = explode('-', $date);
            ${$date_month}[$cntr] = $date[0];
            ${$date_day}[$cntr] = $date[1];
            ${$date_year}[$cntr] = $date[2];
        }

        if($detail_view == FALSE)
        {
            $param = array($array_month, $date_month); 
            $this->draw_select_field_mf($param, $cntr);

            $param = array($array_day, $date_day); 
            $this->draw_select_field_mf($param, $cntr);

            $param = array($array_year, $date_year); 
            $this->draw_select_field_mf($param, $cntr);
        }
        else
        {
            echo ${$date_year}[$cntr] . '-' . ${$date_month}[$cntr] . '-' . ${$date_day}[$cntr];
        }
    }

    function draw_field($field_name, $draw_table_tags=TRUE)
    {
        $field_struct = $this->fields[$field_name];
        $extra = $field_struct['extra'];
        
        switch($field_struct['control_type'])
        {
            case 'date controls'    :   $this->draw_date_field($draw_table_tags, $field_struct['label'], $field_struct['date_elements'][0], $field_struct['date_elements'][1], $field_struct['date_elements'][2], $this->year_set, $this->detail_view);
                                        break;
         
            case 'radio buttons'    :   if($this->detail_view==FALSE)
                                        {
                                            $this->draw_radio_buttons($field_struct['list_settings'], $field_struct['label'], $field_name, $draw_table_tags, $extra);
                                        }
                                        else
                                        {
                                            $this->draw_text_field($field_struct['label'], $field_name, TRUE, 'text', $draw_table_tags, $extra);
                                        }
                                        break;

            case 'drop-down list'   :   if($field_struct['list_type']=='predefined')
                                        {
                                            if($this->detail_view==FALSE)
                                            {
                                                $this->draw_select_field($field_struct['list_settings'], $field_struct['label'], $field_name, $draw_table_tags, $extra);
                                            }
                                            else
                                            {
                                                $this->draw_text_field($field_struct['label'], $field_name, TRUE, 'text', $draw_table_tags, $extra);
                                            }
                                        }
                                        elseif($field_struct['list_type']=='sql generated')
                                        {
                                            $this->draw_select_field_from_query($field_struct['list_settings']['query'], $field_struct['list_settings']['list_value'], $field_struct['list_settings']['list_items'], $field_struct['label'], $field_name, $this->detail_view, $draw_table_tags, $field_struct['list_settings']['list_separators'], $extra);
                                        }
                                        elseif($field_struct['list_type']=='relationship')
                                        {
                                            require_once 'subclasses/' . $this->subclasses['data_file'];
                                            $data_con = new $this->subclasses['data_class'];
                                            $data_con->create_query_from_relationship($field_name, $query, $list_value, $list_items);
                                            $this->draw_select_field_from_query($query, $list_value, $list_items, $field_struct['label'], $field_name, $this->detail_view, $draw_table_tags, $field_struct['list_settings']['list_separators'], $extra);
                                        }
                                        break;

            case 'password'         :   if($field_struct['length'] > 0) $extra .= ' maxlength="' . $field_struct['length'] . '" ';
                                        $this->draw_text_field($field_struct['label'], $field_name, $this->detail_view, $field_struct['control_type'], $draw_table_tags, $extra);
                                        break;

            case 'textbox'          :   $field_struct['control_type'] = 'text';
            case 'textarea'         :   if($field_struct['length'] > 0) $extra .= ' maxlength="' . $field_struct['length'] . '" ';
                                        $this->draw_text_field($field_struct['label'], $field_name, $this->detail_view, $field_struct['control_type'], $draw_table_tags, $extra);
                                        break;

            default                 :   break;
        }
    }

    function draw_fieldset_header($title)
    {
        echo '<fieldset class="top">' . $title . "\r\n";
        echo '</fieldset>' . "\r\n";
    }

    function draw_fieldset_body_start()
    {
        echo '<fieldset class="middle">' . "\r\n";
        echo '<table class="input_form">' . "\r\n";
    }

    function draw_fieldset_body_end()
    {
        echo '</table>' . "\r\n";
        echo '</fieldset>' . "\r\n";
    }

    function draw_fieldset_footer_start()
    {
        echo '<fieldset class="bottom">' . "\r\n";
    }

    function draw_fieldset_footer_end()
    {
        echo '</fieldset>' . "\r\n";    
    }

    function draw_footer()
    {
        if($this->with_form == TRUE) echo '</form>' . "\r\n";
        if($_SESSION['footer']=='') $_SESSION['footer']='skins/default_footer.php';
        require_once $_SESSION['footer'];
    }

    function draw_footer_printable()
    {
        require_once 'skins/printview_footer.php';
    }

    function draw_header($page_title=null, $message=null, $message_type=null, $draw_form=TRUE, $upload=FALSE)
    {
        if($_SESSION['header']=='') $_SESSION['header']='skins/default_header.php';
        require_once $_SESSION['header'];

        if($draw_form==TRUE) 
        {
            $this->with_form = TRUE;
            
			if($upload)
			{
    			echo '<form enctype="multipart/form-data" method="POST" action="' . $_SERVER[PHP_SELF] . '" name="' . $this->form_name . '" id="'. $this->form_id . '">' . "\r\n";
			}
			else
			{
    			echo '<form method="POST" action="' . $_SERVER[PHP_SELF] . '" name="' . $this->form_name . '" id="'. $this->form_id . '">' . "\r\n";
            }

            $form_key = generate_token();
            $form_identifier = $_SERVER['PHP_SELF'];
            $_SESSION['cobalt_form_keys'][$form_identifier] = $form_key;
            echo '<input type=hidden name=form_key value="' . $form_key .'">' . "\r\n";

            //We don't want to accumulate an unlimited amount of Cobalt Form Keys, so once we exceed ten, we remove the oldest one.
            if(count($_SESSION['cobalt_form_keys']) > 10)
            {
                array_shift($_SESSION['cobalt_form_keys']);
            }

            if($page_title==null)
            {
                //no title
            }
            else
            {
                $this->draw_page_title($page_title);
            }

            if(strtoupper($message_type)=='SYSTEM') $this->display_message($message);
            else $this->display_errors($message);
        }
    }

    function draw_header_printable()
    {
        require_once 'skins/printview_header.php';
    }

    function draw_listview_referrer_info($filter_field, $filter, $page)
    {
        $filter = urldecode($filter);
        $filter_field = urldecode($filter_field);
        echo '<input type="hidden" name="filter_field_used" value="' . $filter_field . '">' . "\r\n";
        echo '<input type="hidden" name="filter_used" value="' . $filter . '">' . "\r\n";
        echo '<input type="hidden" name="page_from" value="' . $page . '">' . "\r\n";    
    }

    function draw_page_title($title)
    {
        echo '<br><span class=pageHeader>'. $title .'</span>' . "\r\n";
    }

    function draw_radio_buttons($arrayItems, $label, $form_control_name=null, $draw_table_tags=TRUE, $extra='')
    {
        if($form_control_name==null) $form_control_name=$label;
        global $$form_control_name;

        if($draw_table_tags) echo '<tr><td class="label">' . $label . ':</td><td>' . "\r\n";
        else echo $label;

        $numItems = count($arrayItems['items']);
        for($a=0;$a<$numItems;$a++)
        {
            $mark="";
            $ending='';
            if($arrayItems['per_line']==TRUE) $ending="<br>";
            if((string) $arrayItems['values'][$a] == (string) $$form_control_name) $mark="checked";
            echo '<input type="radio" id="' . $form_control_name . '[' . $a . ']" name="' . $form_control_name . '" value="' . cobalt_htmlentities($arrayItems['values'][$a]) . '" ' . $mark .' ' . $extra . '><label for="' . $form_control_name . '[' . $a . ']">' . $arrayItems['items'][$a] . '</label>' . $ending . "\r\n";
        }

        if($draw_table_tags) echo '</td>' . "\r\n";
    }

    function draw_select_field($options, $label, $form_control_name='', $draw_table_tags=TRUE, $extra='')
    {
        if($form_control_name=='') $form_control_name=$label;
        global $$form_control_name;

        if($draw_table_tags) echo '<tr><td class="label">' . $label . ':</td><td>' . "\r\n";
        else echo $label;

        echo "<select name='$form_control_name' $extra><option></option>\r\n";

        $num_options = count($options['items']);
        for($a=0;$a<$num_options;$a++)
        {
            $selected='';
            if((string) $$form_control_name == (string) $options['values'][$a]) $selected='selected';
            echo '<option value="'. cobalt_htmlentities($options['values'][$a]) . '" ' 
                    . $selected . '> ' . $options['items'][$a] . '</option>' . "\r\n";
        }

        echo "</select>\r\n";

        if($draw_table_tags) echo '</td>' . "\r\n";
    }

    function draw_select_field_mf($param, $cntr)
    {
        $options = $param[0];
        $form_control_name = $param[1];
        $extra = $param[2];

        global $$form_control_name;

        echo "<select name='$form_control_name" . "[$cntr]' $extra><option></option>\r\n";

        $num_options = count($options['items']);
        for($a=0;$a<$num_options;$a++)
        {
            $selected='';
            if((string) ${$form_control_name}[$cntr] == (string) $options['values'][$a]) $selected='selected';
            echo '<option value="'. cobalt_htmlentities($options['values'][$a]) . '" ' 
                    . $selected . '> ' . $options['items'][$a] . '</option>' . "\r\n";
        }

        echo "</select>\r\n";
    }

    function draw_select_field_from_query($query, $list_value, $list_items, $label, $form_control_name='', $detail_view=FALSE, $draw_table_tags=TRUE, $list_separators='', $extra='')
    {
        if($form_control_name=='') $form_control_name=$label;
        global $$form_control_name;

        if($draw_table_tags) echo '<tr><td class="label">' . $label . ':</td><td>' . "\r\n";
        else echo $label;

        $num_display=count($list_items);

        if($detail_view != TRUE) echo "<select name='$form_control_name' $extra><option></option>\r\n";

        $data_con = new data_abstraction;
        $data_con->query = $query;
        if($result = $data_con->execute_query())
        {
            while($data = $result->fetch_assoc())
            {
                extract($data);

                $selected = '';
                if((string) $$form_control_name == (string) $$list_value) $selected='selected';

                $dropdown_item_entry='';
                for($a=0; $a<$num_display; $a++)
                {
                    if($list_separators[$a]=='') $list_separators[$a] = ' ';
                    $dropdown_item_entry .= ${$list_items[$a]} . $list_separators[$a];
                }

                if($detail_view != TRUE)
                {
                    echo '<option value="' . cobalt_htmlentities($$list_value) . '" ' . $selected . '>';
                    echo "$dropdown_item_entry </option>\r\n";
                }
                else
                {
                    if(trim($dropdown_item_entry)=='')
                    {
                        $dropdown_item_entry = '&nbsp;';
                    }
                    if($selected=='selected') echo '<p class="detail_view">' . nl2br($dropdown_item_entry) . '</p>' . "\r\n";
                }
            }
        }

        if($detail_view != TRUE) echo "</select>\r\n";

        if($draw_table_tags) echo '</td>' . "\r\n";
    }

    function draw_select_field_from_query_mf($param, $cntr)
    {
        $detail_view = $this->detail_view;

        //$query, $list_value, $list_items, $form_control_name='', $extra=''
        $query = $param[0];
        $list_value = $param[1];
        $list_items = $param[2];
        $form_control_name = $param[3];
        $extra = $param[4];
        $list_separators = $param[5];

        //The query may have the "{[ ]}" marking, which means get the current value (using cntr) of the variable which is named
        //inside the {[ ]}
        //For example, a query with "WHERE myfield = '{[status]}'" in it means the actual query to be executed should be:
        //  WHERE myfield = '$status[$cntr]'
        while($start_replace = strpos($query, '{[', 0))
        {
            $end_replace = strpos($query, ']}', $start_replace);
            if($end_replace > $start_replace)
            {
                $query_part1 = substr($query, 0, $start_replace);
                $query_part2 = substr($query, $end_replace+2, strlen($query));
                $var_length = $end_replace - ($start_replace+2);
                $variable = substr($query, $start_replace+2, $var_length);
                global $$variable;
                $query = $query_part1 . ${$variable}[$cntr] . $query_part2;
            }
        }

        global $$form_control_name;

        $num_display=count($list_items);

        if($detail_view != TRUE) echo "<select name='$form_control_name" . "[$cntr]' $extra><option></option>\r\n";

        $data_con = new data_abstraction;
        $data_con->query = $query;
        if($result = $data_con->execute_query())
        {
            while($data = $result->fetch_assoc())
            {
                extract($data);

                $selected = '';
                if((string) ${$form_control_name}[$cntr] == (string) $$list_value) $selected='selected';

                $dropdown_item_entry='';
                for($a=0; $a<$num_display; $a++)
                {
                    if($list_separators[$a]=='') $list_separators[$a] = ' ';
                    $dropdown_item_entry .= ${$list_items[$a]} . $list_separators[$a];
                }

                if($detail_view != TRUE)
                {
                    echo '<option value="' . cobalt_htmlentities($$list_value) . '" ' . $selected . '>' . $dropdown_item_entry . '</option>' . "\r\n";
                }
                else
                {
                    if(trim($dropdown_item_entry)=='')
                    {
                        $dropdown_item_entry = '&nbsp;';
                    }
                    if($selected=='selected') echo '<p class="detail_view">' . nl2br($dropdown_item_entry) . '</p>' . "\r\n";
                }
            }
        }
        else die($data_con->error);

        if($detail_view != TRUE) echo "</select>\r\n";
    }

    function draw_text_field($label, $tf_control_name='', $detail_view=FALSE, $control_type='', $draw_table_tags=TRUE, $extra='')
    {
        if($tf_control_name=='') $tf_control_name=$label;
        if($control_type=='') $control_type='text';

        global $$tf_control_name;

        $control_type = strtolower($control_type);

        if($draw_table_tags) echo '<tr><td class="label">' . $label . ':</td><td>' . "\r\n";
        else echo $label . "\r\n";

        $value = cobalt_htmlentities($$tf_control_name, ENT_QUOTES);
        if($detail_view==FALSE) 
        {
            if($control_type=='textarea') echo "<textarea name='$tf_control_name' $extra rows='5' cols='30'>" . $value . "</textarea>\r\n";
            else echo "<input type='$control_type' name='$tf_control_name' value='" . $value . "' $extra>\r\n";
        }
        else 
        {
            if(trim($value)=='')
            {
                $value = '&nbsp;';
            }
            echo '<p class="detail_view">' . nl2br($value) . '</p>' . "\r\n";
        }

        if($draw_table_tags) echo '</td></tr>' . "\r\n";
    }

    function draw_text_field_mf($param, $cntr)
    {
        $detail_view = $this->detail_view;

        $form_control_name = $param[0];
        $control_type = $param[1];
        $extra = $param[2];
        $html_flag = $param[3];

        if($control_type=='') $control_type='text';

        global $$form_control_name;

        $control_type = strtolower($control_type);

        if($html_flag!='OFF') $value = cobalt_htmlentities(${$form_control_name}[$cntr], ENT_QUOTES);
        else $value = ${$form_control_name}[$cntr];
        if($detail_view==FALSE) 
        {
            if($control_type=='textarea') echo "<textarea name='$form_control_name" . "[$cntr]' rows='5' cols='30' $extra>" . $value . "</textarea>\r\n";
            else echo "<input type='$control_type' name='$form_control_name" ."[$cntr]' value='" . $value . "' $extra>\r\n";
        }
        else 
        {
            if(trim($value)=='')
            {
                $value = '&nbsp;';
            }
            echo '<p class="detail_view">' . nl2br($value) . '</p>' . "\r\n";
        }
    }


    function draw_multifield_auto($label, $arr_multi_field, $num_particulars_var=null, $particulars_count_var=null, $particular_button_var=null, $draw_table_tags=TRUE)
    {
        if($num_particulars_var==null) $num_particulars_var='num_particulars';
        if($particulars_count_var==null) $particulars_count_var='particulars_count';
        if($particularButton==null) $particularButton='particular_button';

        global $$num_particulars_var, $$particulars_count_var;

        if($draw_table_tags) echo '<tr><td colspan="2"><hr></td></tr>'  . "\r\n" . '
                                    <tr><td colspan="2" align="center">' . $label . '<br>' . "\r\n";
        else echo "<hr>" . $label . "<br>\r\n";

        if($$num_particulars_var>0) ;
        else $$num_particulars_var=$$particulars_count_var;

        if($this->detail_view==FALSE)
        {
            if($$num_particulars_var!=0)	 echo "<input type=hidden name='" . $particulars_count_var . "' value=". $$num_particulars_var . ">\r\n";
            else  echo "<input type=hidden name='" . $particulars_count_var . "' value=1>\r\n";
        }

        if($$num_particulars_var<1) $$num_particulars_var=1;
        echo '<table border=1 cellpadding=2 cellspacing=0><tr><td>&nbsp;</td>' . "\r\n";

        //Count how many fields need to be drawn,
        //then loop the <td></td> tags with the corresponding labels.
        $numTDPairs = count($arr_multi_field['field_labels']);
        for($a=0;$a<$numTDPairs;$a++)
        {
            echo '<td><p class="detail_view">' . $arr_multi_field['field_labels'][$a] . '</p></td>' . "\r\n";
        }
        echo '</tr>' . "\r\n";

        for($a=0;$a<$$num_particulars_var;$a++)
        {
            echo '<tr><td>&nbsp' . ($a + 1) . '&nbsp;</td>' . "\r\n";

            for($b=0;$b<$numTDPairs;$b++)
            {
                if($this->mf_col_align[$b] == '') $this->mf_col_align=='left';
                echo '<td align="' . $this->mf_col_align[$b] . '">';

                global ${$arr_multi_field['field_parameters'][$b]};

                $this->$arr_multi_field['field_controls'][$b]($arr_multi_field['field_parameters'][$b], $a);

                echo '</td>' . "\r\n";
            }

            echo '</tr>' . "\r\n";
        }
        echo "</table>\r\n";

        if($this->detail_view==FALSE)
        {
            echo '<br> Change # of items to: 
                      <input type=text size=2 maxlength=2 name="' . $num_particulars_var . '"> 
                      <input type=submit name="' . $particularButton . '" value=GO class=button1>' . "\r\n";
            if($draw_table_tags) echo '</td></tr><tr><td colspan="2"><hr></td></tr>' . "\r\n";
            else echo "<hr>\r\n";
        }
        else echo "<hr>\r\n";
    }


    function draw_button($type=null, $button_class="button1", $button_name=null, $button_label=null, $draw_table_tags=FALSE, $colspan="2", $extra='')
    {
        if($draw_table_tags==TRUE) echo "<tr><td align=center colspan=$colspan>\r\n";
        $button_type='submit'; //This is the default. This will only change if $type is set to "BUTTON"

        $type = strtolower($type);

        switch($type)
        {
            case "cancel":
                $button_name="cancel";
                $button_label="CANCEL";
                break;
            case "back":
                $button_name="back";
                $button_label="BACK";
                break;
            case "delete":
                $button_name="delete";
                $button_label="DELETE";
                break;
            case "go":
                $button_name="go";
                $button_label="GO";
                break;
            case "special":
                break;
            case "button":
                $button_type='button';
                break;
            default:
                $button_name="submit";
                $button_label="SUBMIT";
        }
        echo "<input type=$button_type name='$button_name' value='$button_label' class='$button_class' $extra >&nbsp;";

        if($draw_table_tags==TRUE) echo '</td></tr>' . "\r\n";
    }

    function draw_submit_cancel($draw_table_tags=TRUE, $colspan="2", $submit_name="submit", $submit_label="SUBMIT", $submit_class="submit", $cancel_name="cancel", $cancel_label="CANCEL", $cancel_class="cancel")
    {
        if($draw_table_tags==TRUE) echo "<tr><td align=center colspan=$colspan>\r\n";

        echo "<input type=submit name='" . $submit_name . "' value='" . $submit_label . "' class='" . $submit_class. "'>&nbsp;\r\n";
        echo "<input type=submit name='" . $cancel_name . "' value='" . $cancel_label . "' class='" . $cancel_class. "'>\r\n";

        if($draw_table_tags==TRUE) echo '</td></tr>' . "\r\n";
    }

    function get_listview_fields()
    {
        //Get the table name
        require_once 'subclasses/' . $this->subclasses['data_file'];
        $obj = new $this->subclasses['data_class'];
        $table_name = $obj->tables;
        
        foreach($this->fields as $field_name=>$field_struct)
        {
            if($field_struct['in_listview'] == 'yes') 
            {
                $make_filter_label=TRUE;
                if($field_struct['attribute']=='foreign key' || $field_struct['attribute']=='primary&foreign key')
                {
                    //find the relationship information for this field
                    foreach($this->relations as $key=>$rel)
                    {
                        if(strip_back_quote_smart($rel['link_child']) == $field_name)
                        {
                            require_once 'subclasses/' . strip_back_quote_smart($rel['table']) . '.php';
                            $class = strip_back_quote_smart($rel['table']);
                            $data_con = new $class;
                            $database = $data_con->db_use;

                            $temp_field_name = '';
                            $filter_field_name = '';
                            $arr_subtexts = array();
                            $arr_subtext_labels = array();
                            $subtext_cntr=0;
                            foreach($rel['link_subtext'] as $subtext)
                            {
                                if($temp_field_name != '') $temp_field_name .= ', ';
                                if($filter_field_name != '') $filter_field_name .= ', ';
                                $temp_field_name .= back_quote_smart($database) . '.' . back_quote_smart($rel['table']) . '.' . back_quote_smart($subtext) . ' AS ' . back_quote_smart($database . '_' . $rel['table'] . '_' . $subtext);
                                $filter_field_name .= back_quote_smart($database) . '.' . back_quote_smart($rel['table']) . '.' . back_quote_smart($subtext);
                                $arr_subtexts[] = $database . '_' . $rel['table'] . '_' . $subtext;
                                $arr_subtext_labels[] = $data_con->fields[$subtext]['label'];
                                $subtext_cntr++;
                            }

                            if($subtext_cntr>1)
                            {
                                foreach($arr_subtext_labels as $new_filter_label)
                                {
                                    make_list_array($this->arr_filter_field_labels, $new_filter_label);
                                }
                                $make_filter_label=FALSE;
                            }

                            $related_field_name = $temp_field_name;
                            make_list($this->lst_fields, back_quote_smart($related_field_name), ',', FALSE);
                            make_list($this->lst_filter_fields, back_quote_smart($filter_field_name), ',', FALSE);
                            make_list_array($this->arr_fields, $arr_subtexts);

                            if($field_struct['attribute']=='primary&foreign key')
                            {
                                //if foreign key is also a primary key, we also need the original field aside from the subtext field
                                $orig_field_name = back_quote_smart($table_name) . '.' . back_quote_smart($field_name);
                                make_list($this->lst_fields, back_quote_smart($orig_field_name), ',', FALSE);
                            }
                        }
                    }
                }
                else
                {
                    make_list($this->lst_fields, back_quote_smart($table_name). '.' .  back_quote_smart($field_name), ',', FALSE);
                    make_list($this->lst_filter_fields, back_quote_smart($table_name). '.' .  back_quote_smart($field_name), ',', FALSE);
                    make_list_array($this->arr_fields, $field_name);
                }                
                
                make_list($this->lst_field_labels, $field_struct['label'], ',');
                make_list_array($this->arr_field_labels, $field_struct['label']);
                
                if($make_filter_label)
                {
                    make_list_array($this->arr_filter_field_labels, $field_struct['label']);
                }
            }
            elseif($field_struct['attribute'] == 'primary key')
            {
                make_list($this->lst_fields, back_quote_smart($table_name) . '.' .  back_quote_smart($field_name), ',', FALSE);
            }
        }
    }
}

