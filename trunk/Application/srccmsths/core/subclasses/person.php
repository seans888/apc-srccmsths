<?php
require_once 'person_dd.php';
class person extends data_abstraction
{
    var $fields = array();
    var $tables='person';

    function person()
    {
        $this->fields = person_dd::load_dictionary();
        $this->relations = person_dd::load_relationships();
        $this->subclasses = person_dd::load_subclass_info();
    }

    function add($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('person_id, first_name, middle_name, last_name, gender');
        $this->set_values("'$person_id', '$first_name', '$middle_name', '$last_name', '$gender'");
        $this->make_query(TRUE,$loq_query);
    }

    function edit($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', gender = '$gender'");
        $this->set_where("person_id = '$person_id'");
        $this->make_query(TRUE,$loq_query);
    }

    function del($param, $loq_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("person_id = '$person_id'");
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
        $this->set_where("person_id = '$person_id'");
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
        $this->set_where("person_id = '$person_id' AND (person_id != '$person_id')");
        $this->make_query(TRUE,$loq_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this->is_unique;
    }

}
