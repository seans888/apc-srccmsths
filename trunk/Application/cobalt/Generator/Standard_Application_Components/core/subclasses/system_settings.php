<?php
require_once 'system_settings_dd.php';
class system_settings extends data_abstraction
{
    var $fields = array();
    var $tables='system_settings';

    function system_settings()
    {
        $this->fields = system_settings_dd::load_dictionary();
        $this->relations = system_settings_dd::load_relationships();
        $this->subclasses = system_settings_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('setting, value');
        $this->set_values("'$setting', '$value'");
        $this->make_query(TRUE,$loq_query);
    }

    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("setting = '$setting', value = '$value'");
        $this->set_where("setting = '$Orig_setting'");
        $this->make_query(TRUE,$loq_query);
    }

    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("setting = '$setting'");
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
        $this->set_where("setting = '$setting'");
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
        $this->set_where("setting = '$setting' AND (setting != '$Orig_setting')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this->is_unique;
    }

}
