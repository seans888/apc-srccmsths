<?php
require_once 'user_links_dd.php';
class user_links extends data_abstraction
{
    var $fields = array();
    var $tables='user_links';

    function user_links()
    {
        $this->fields = user_links_dd::load_dictionary();
        $this->relations = user_links_dd::load_relationships();
        $this->subclasses = user_links_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('link_id, name, target, descriptive_title, description, passport_group_id, show_in_tasklist, status, icon');
        $this->set_values("'$link_id', '$name', '$target', '$descriptive_title', '$description', '$passport_group_id', '$show_in_tasklist', '$status', '$icon'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("name = '$name', target = '$target', descriptive_title = '$descriptive_title', description = '$description', passport_group_id = '$passport_group_id', show_in_tasklist = '$show_in_tasklist', status = '$status', icon = '$icon'");
        $this->set_where("link_id = '$link_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("link_id = '$link_id'");
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
        $this->set_where("link_id = '$link_id'");
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
        $this->set_where("link_id = '$link_id' AND (link_id != '$link_id')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }

}
