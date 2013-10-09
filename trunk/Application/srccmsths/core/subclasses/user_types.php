<?php
require_once 'user_types_dd.php';
class user_types extends data_abstraction
{
    var $fields = array();

    var $tables='user_types';

    function user_types()
    {
        $this->fields = user_types_dd::load_dictionary();
        $this->relations = user_types_dd::load_relationships();
        $this->subclasses = user_types_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('user_type_id, user_type');
        $this->set_values("'$user_type_id', '$user_type'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);        
        $this->set_query_type('UPDATE');
        $this->set_update("user_type = '$user_type'");
        $this->set_where("user_type_id = '$user_type_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("user_type_id = '$user_type_id'");
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
        $this->set_where("user_type_id = '$user_type_id'");
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
        $this->set_where("user_type_id = '$user_type_id' AND (user_type_id != '$user_type_id')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }

}
