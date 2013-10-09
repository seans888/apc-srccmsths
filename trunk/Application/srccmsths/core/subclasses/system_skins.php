<?php
require_once 'system_skins_dd.php';
class system_skins extends data_abstraction
{
    var $fields = array();
    var $tables='system_skins';

    function system_skins()
    {
        $this->fields = system_skins_dd::load_dictionary();
        $this->relations = system_skins_dd::load_relationships();
        $this->subclasses = system_skins_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('skin_id, skin_name, header, footer, css');
        $this->set_values("'$skin_id', '$skin_name', '$header', '$footer', '$css'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("skin_name = '$skin_name', header = '$header', footer = '$footer', css = '$css'");
        $this->set_where("skin_id = '$skin_id'");
        $this->make_query(TRUE,$loq_query);
    }
    
    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("skin_id = '$skin_id'");
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
        $this->set_where("skin_id = '$skin_id'");
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
        $this->set_where("skin_id = '$skin_id' AND (skin_id != '$skin_id')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;
        
        return $this->is_unique;
    }

}
