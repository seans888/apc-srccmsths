    var $query='';
    var $connection_exists=FALSE;
    var $tables='';
    var $selected_fields='';
    var $values='';
    var $join_clause='';
    var $update_clause='';
    var $where_clause='';
    var $group_by='';
    var $having='';
    var $order_clause='';
    var $limit_clause='';
    var $query_type='SELECT';
    var $affected_eows='';
    var $num_rows='';
    var $error='';
    var $auto_id='';

    function data_abstraction($new_db='', $new_tables='', $new_query_type='')
    {
        if($new_db != '') $this->db_use = $new_db;
        if($new_tables != '') $this->tables = $new_tables;
        if($new_query_type != '') $this->query_type = $new_query_type;
    }

    function close_db()
    {
        if($this->mysqli)
        {
            $this->mysqli->close();
            $this->connection_exists = FALSE;
        }
    }

    function connect_db()
    {
        $this->mysqli = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_use);

        if(mysqli_connect_errno())
        {
            printf("Failed to connect to database: %s\n", mysqli_connect_error());
            exit();
        }

        $this->connection_exists = TRUE;
        return $this->mysqli;
    }

    function create_query_from_relationship($field, &$query, &$list_value, &$list_items)
    {
        foreach($this->relations as $id=>$rel)
        {
            if(back_quote_smart($rel['link_child']) == back_quote_smart($field))
            {
                $list_value = 'queried_' . strip_back_quote_smart($rel['link_parent']);
                $list_items = array();
                foreach($rel['link_subtext'] as $subtext)
                {
                    $list_items[] = strip_back_quote_smart($subtext);
                }

                //**************************************
                //Create the query:
                require_once 'Subclasses/' . strip_back_quote_smart($rel['table']) . '.php';
                $class = strip_back_quote_smart($rel['table']);
                $objDB = new $class;
                $database = $objDB->DB_USE;
                $objDB->close_DB();
                
                $value_field =  back_quote_smart($rel['link_parent']) . ' as `Queried_' . strip_back_quote_smart($rel['link_parent']) . '`';
                $items_field='';
                foreach($rel['link_subtext'] as $subtext)
                {
                    if(back_quote_smart($subtext) != back_quote_smart($rel['link_parent']))
                    {
                        make_list($items_field, strip_back_quote_smart($subtext), $delimiter=',', $quotes=TRUE, '`', '`');
                    }
                }
                $query = "SELECT $value_field";
                if($items_field != '') $query .= ', ' . $items_field;
                $query .= ' FROM ' . back_quote_smart($database) . '.' . back_quote_smart($rel['table']);
                
                if($rel['where_clause'] != '') $query .= ' WHERE ' . $rel['Where_clause'];
                
                if($items_field != '') $query .= " ORDER BY $items_field";
                else $query .= ' ORDER BY ' . back_quote_smart($rel['link_parent']);
            }
        }
    }


    function escape_arguments(&$param)
    {
        if(is_array($param))
        {
        	$keys = array();
        	foreach($param as $index=>$value)
        	{
        	   $keys[] = $index;
            }
            
            for($a=0; $a<count($keys); $a++)
            {
                $param[$keys[$a]] = $this->escape_arguments($param[$keys[$a]]);
            }
        }
        else
        {
            $param = quote_smart($param);
        }
        
        return $param;
    }

    function execute_query($query='')
    {
        if($query != '') $this->query = $query;
        
        if($this->connection_exists == FALSE)
        {
            $this->mysqli = $this->connect_DB();
            $this->mysqli->real_query("SET NAMES 'utf8'");
        }
        $this->mysqli->real_query($this->query) or error_handler('Database error.',$this->mysqli->error);

        if($this->query_type == "SELECT")
        {
            $result = $this->mysqli->store_result();
            $this->error = $this->mysqli->error;
            $this->num_rows = $result->num_rows;
            return $result;
        }
        elseif($this->query_type == "INSERT")
        {
            $this->auto_id = $this->mysqli->insert_id;
            $this->error = $this->mysqli->error;
        }
        else
        {
            $this->affected_rows = $this->mysqli->affected_rows;
        }
    }

    function exec_fetch($result_type='array', $log=FALSE)
    {
        if($result = $this->make_query(TRUE, $log))
        {
            //Valid types are 'single' and 'array'.
            //Default is 'array', and for robustness any other value
            //simply gets treated as 'array';

            if($result->num_rows > 0)
            {
                //Result = single record, no need for arrays to store the result set
                if(strtoupper($result_type)=='SINGLE') 
                {
                    $data = $result->fetch_assoc();
                    if(is_array($data))
                    {
                        foreach($data as $key=>$value)
                        {
                            $this->$key = $value;
                        }
                    }
                }
                else //Result = multiple records, store in arrays
                {
                    for($a=0; $a<$this->num_rows; $a++)
                    {
                        $data = $result->fetch_assoc();
                        if(is_array($data))
                        {
                            foreach($data as $key=>$value)
                            {
                                if(is_array($this->$key)) ;
                                else $this->$key = array();
                                $this->{$key}[] = $value;
                            }
                        }
                    }
                }
            }
        }
    }

    function get_info($value, $field)
    {
        $this->set_where("`$field`='$value'");
        if($result = $this->make_query())
        {
            $data = $result->fetch_assoc();
            if(is_array($data))
            {
                foreach($data as $key=>$value)
                {
                    $this->$key = $value;
                }
            }
        }
        else
        {
            //We probably want an epic fail right here
            die('Error getting record info... most likely, no field was specified as key...');
        }
    }

    function get_join_clause($join_type='LEFT JOIN')
    {
        $this->join_clause = back_quote_smart($this->tables);
        
        foreach($this->relations as $key=>$rel)
        {
            if($rel['type'] == '1-1')
            {
                $this->join_clause .= ' ' . $join_type . ' '
                                    . back_quote_smart($rel['table']) . ' ON ' 
                                    . back_quote_smart($this->tables) . '.' . back_quote_smart($rel['link_child']) . ' = '
                                    . back_quote_smart($rel['table']) . '.' . back_quote_smart($rel['link_parent']);
            }
        }
        
        if($this->join_clause == '') $this->join_clause = $this->tables;
    }

    function make_query($execute=TRUE, $log=TRUE)
    {
        //****Before constructing the actual query, let's get the parameters straightened out.*******
        //Tables: Can never be empty.
        if($this->tables == '') die ("Data Abstraction Error: Please indicate what table(s) you wish to query.");

        //Query type: SELECT, INSERT, UPDATE, or DELETE
        //For robustness, if query type is invalid, it defaults to "SELECT" instead of just dying.
        //Also convert to uppercase to make the query type passed case-insensitive.
        $this->query_type = strtoupper($this->query_type);
        if($this->query_type != "SELECT" && $this->query_type != "INSERT"  && $this->query_type != "UPDATE" && $this->query_type != "DELETE")
            $this->query_type = "SELECT";

        //Fields: Can only be empty for SELECT or DELETE, must have a value for INSERT queries.
        //If empty in a SELECT statement, default to '*'.
        if( $this->selected_fields=="")
        {
            if($this->query_type=="SELECT")  $this->selected_fields='*';
            elseif($this->query_type=="INSERT") 
                die('Data Abstraction Error: Please indicate the field(s) to work with in an INSERT query.');
        }

        //Where clause: Can always be empty, but we generally don't want that in an UPDATE or DELETE query. 
        if($this->where_clause=="" && ($this->query_type=="UPDATE" || $this->query_type=="DELETE")) 
            die('Data Abstraction Error: Please set a WHERE clause for an UPDATE or DELETE query.');

        //Values: Only for INSERT queries, and must not be empty;
        if($this->query_type=='INSERT' && $this->values=='')
            die('Data Abstraction Error: Please set the values to be inserted for an INSERT query.');

        //Update clause: Only used in UPDATE queries and must not be empty.
        if($this->query_type=='UPDATE' && $this->update_clause=='')
            die('Data Abstraction Error: Please set the update clause in an UPDATE query.');

        //Join clause: only for SELECT statements, can be empty;
        //Order clause: only for SELECT statements, can be empty;
        //Limit clause: only for SELECT statements, can be empty;
        //Group By clause: only for SELECT statements, can be empty;
        //Having clause: only for SELECT statements, can be empty;

        $this->query = $this->query_type . ' ';
        switch($this->query_type)
        {
            case "SELECT":	$this->query .=  $this->selected_fields . ' FROM ' . $this->tables;
                            if($this->where_clause != '') $this->query .= ' WHERE ' . $this->where_clause;
                          	if($this->group_by != '') $this->query .= ' GROUP BY ' . $this->group_by . ' ';
                          	if($this->having != '') $this->query .= ' HAVING ' . $this->having . ' ';
                          	if($this->order_clause != '') $this->query .= ' ORDER BY ' . $this->order_clause;
                          	if($this->limit_clause != '') $this->query .= ' LIMIT ' . $this->limit_clause;
                            break;
                            
            case "INSERT":	$this->query .= 'INTO ' . $this->tables . '(' .  $this->selected_fields . ') VALUES(' . $this->values . ')';
                            break;

            case "UPDATE":	$this->query .= $this->tables . ' SET ' . $this->update_clause . ' WHERE ' . $this->where_clause;
                            break;

            case "DELETE":	$this->query .= 'FROM ' . $this->tables;
                            if($this->where_clause != '') $this->query .= ' WHERE ' . $this->where_clause;
                            break;
        }

        if($execute)
        {
            $result = $this->execute_query();
        }

        if($log && $this->query_type != 'SELECT')
        {
            log_action('Query executed: <br> ' . $this->query, $_SERVER[PHP_SELF]);
        }

        return $result;
    }

    function sanitize(&$param)
    {
        $lst_error = '';
        require_once 'validation_class.php';
        require_once 'char_set_class.php';
        $validator = new validation;

        //$attributes_required contains the attributes that make a field
        //eligible for validation to be not null, i.e., if the attribute
        //of a field is in this array, and the control type is not 'None',
        //the field will be checked to ensure it is not blank.
        $attributes_required = array('required','primary key','primary&foreign key','foreign key');

        //Check if some required fields are left blank.
        foreach( $this->fields as $field_name=>$field_details)
        {
            $control_type = $field_details['control_type'];
            $attribute    = $field_details['attribute'];
            $label        = $field_details['label'];
            if($control_type != 'none' && in_array($attribute, $attributes_required))
            {
                $lst_error .= $validator->check_if_null($label, $param[$field_name]);
            }
        }

        foreach($param as $unclean=>$unclean_value)
        {
            if(is_array( $this->fields[$unclean]))
            {
                $length              =  $this->fields[$unclean]['length'];
                $data_type           =  $this->fields[$unclean]['data_type'];
                $attribute           =  $this->fields[$unclean]['attribute'];
                $control_type        =  $this->fields[$unclean]['control_type'];
                $label               =  $this->fields[$unclean]['label'];
                $message             =  $this->fields[$unclean]['error_message'];
                $char_set_method     =  $this->fields[$unclean]['char_set_method'];
                $char_set_allow_space = $this->fields[$unclean]['char_set_allow_space'];
                $extra_chars_allowed =  $this->fields[$unclean]['extra_chars_allowed'];
                $trim                =  $this->fields[$unclean]['trim'];
                $valid_set           =  $this->fields[$unclean]['valid_set'];
                
                //Apply trimming if specified.
                //Triming should be applied to $unclean_value for purposes of further filtering/checking,
                //and then also applied to $param[$unclean] so as to actually affect the POST variable.
                if(strtolower($trim) == 'trim')
                {
                    $unclean_value = trim($unclean_value);
                    $param[$unclean] = trim($unclean_value);
                }
                elseif(strtolower($trim) == 'ltrim')
                {
                    $unclean_value = ltrim($unclean_value);
                    $param[$unclean] = ltrim($unclean_value);
                }
                elseif(strtolower($trim) == 'rtrim')
                {
                    $unclean_value = rtrim($unclean_value);                
                    $param[$unclean] = rtrim($unclean_value);
                }

                //Check length
                if($length > 0)
                {
                    if(strlen($unclean_value) > $length)
                        $lst_error .= "The field '$label' can only accept $length characters.<br>";
                }
                
                $validator = new validation;
                //If there is a set of valid inputs, check if 'unclean' conforms to it.
                if(count($valid_set) > 1)
                {
                    $validator->check_data_set($unclean_value, $valid_set, TRUE);
                    if($validator->validity == FALSE)
                    {
                        $lst_error .= $validator->error_message . $label;
                    } 
                }
                else
                {
                    //If a char set method is given, check 'unclean' for invalid characters
                    if($char_set_method!='')
                    {
                        $cg = new char_set;
                        if(strtolower($char_set_allow_space) == 'true')
                        {
                            $cg->allow_space=TRUE;
                        }
                        else
                        {
                            $cg->allow_space=FALSE;
                        }                        
                        $cg->$char_set_method($extra_chars_allowed);
                        $allowed = $cg->allowed_chars;
                            
                        $validator->field_name = $label;
                        $validator->validate_data($unclean_value, $data_type, $allowed);

                        if($validator->validity == FALSE) 
                        {
                            $cntInvalidChars = count($validator->invalid_chars);
                            if($cntInvalidChars == 1)
                                $lst_error .= "Invalid character found in '$label': " . cobalt_htmlentities($validator->invalid_chars[0]) . '<br>';
                            elseif($cntInvalidChars > 1)
                            {
                                $lst_error .= "Invalid characters found in '$label': ";
                                for($a=0; $a<$cntInvalidChars; $a++) $lst_error .= cobalt_htmlentities($validator->invalid_chars[$a]) . ' ';
                                $lst_error .= '<br>';
                            }
                        }
                    }
                }
            }
        }
        return $lst_error;
    }

    function set_database($new_database)
    {
        $this->db_use = $new_database;
    }

    function set_fields($new_fields)
    {
         $this->selected_fields = $new_fields;
    }

    function set_group_by($group)
    {
        $this->group_by = $group;
    }

    function set_having($having)
    {
        $this->having = $having;
    }

    function set_limit($offset, $limit)
    {
        $this->limit_clause = $offset . ', ' . $limit;
    }

    function set_order($new_order_clause)
    {
        $this->order_clause = $new_order_clause;
    }

    function set_query_type($new_query_type)
    {
        $this->query_type = $new_query_type;
    }

    function set_table($new_table)
    {
        $this->tables = $new_table;
    }

    function set_update($new_update_clause)	
    {
        $this->update_clause = $new_update_clause;
    }

    function set_values($new_values)
    {
        $this->values = $new_values;
    }

    function set_where($new_where_clause)
    {
        $this->where_clause = $new_where_clause;
    }
}
?>
