<?php
require_once 'user_passport_groups_dd.php';
class user_passport_groups extends data_abstraction
{
    var $fields = array();
    var $tables='user_passport_groups';

    function user_passport_groups()
    {
        $this->fields = user_passport_groups_dd::load_dictionary();
        $this->relations = user_passport_groups_dd::load_relationships();
        $this->subclasses = user_passport_groups_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('passport_group_id, passport_group, icon');
        $this->set_values("'$passport_group_id', '$passport_group', '$icon'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("passport_group = '$passport_group', icon = '$icon'");
        $this->set_where("passport_group_id = '$passport_group_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("passport_group_id = '$passport_group_id'");
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
        $this->set_where("passport_group_id = '$passport_group_id'");
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
        $this->set_where("passport_group_id = '$passport_group_id' AND (passport_group_id != '$passport_group_id')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }

}
