<?php
require_once 'user_role_dd.php';
class user_role extends data_abstraction
{
    var $fields = array();
    var $tables='user_role';
    var $role='';

    function user_role()
    {
        $this->fields = user_role_dd::load_dictionary();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('description, role, role_id');
        $this->set_values("'$description', '$role', '$role_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);        
        $this->set_query_type('UPDATE');
        $this->set_update("description = '$description', role = '$role'");
        $this->set_where("role_id = '$role_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("role_id = '$role_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function select($loq_query=TRUE)
    {
        $this->set_query_type('SELECT');
        $result = $this->make_query(TRUE,$loq_query);
        
        return $result;
    }
    
    function check_uniqueness($param)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('SELECT');
        $this->set_where("role_id = '$role_id'");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }
    
    function check_uniqueness_for_editing($param)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('SELECT');
        $this->set_where("role_id = '$role_id' AND (role_id != '$role_id')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }

    function get_role_name($role_id)
    {
        $this->set_fields('role');
        $this->set_where("role_id = '$role_id'");
        if($result = $this->make_query(TRUE,$loq_query))
        {
            for($a=0; $a<$this->num_rows; $a++)
            {
                $data = $result->fetch_row();
                $this->role = $data[0];
            }
        }
    }
}
