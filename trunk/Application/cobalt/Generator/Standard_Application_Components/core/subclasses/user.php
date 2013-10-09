<?php
require_once 'user_dd.php';
class user extends data_abstraction
{
    var $fields = array();
    var $tables='user';

    function user()
    {
        $this->fields = user_dd::load_dictionary();
        $this->relations = user_dd::load_relationships();
        $this->subclasses = user_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('username, password, salt, iteration, method, person_id, user_type_id, skin_id');
        $this->set_values("'$username', '$password', '$salt', '$iteration', '$method', '$person_id', '$user_type_id', '$skin_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("username = '$username', 
                           password = '$password', 
                           salt = '$salt',
                           iteration = '$iteration',
                           method = '$method',
                           person_id = '$person_id', 
                           user_type_id = '$user_type_id', 
                           skin_id = '$skin_id'");
        $this->set_where("username = '$Orig_username'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("username = '$username'");
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
        $this->set_where("username = '$username'");
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
        $this->set_where("username = '$username' AND (username != '$Orig_username')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }
    
}
