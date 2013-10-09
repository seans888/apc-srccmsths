<?php
require_once 'requirement_dd.php';
class requirement extends data_abstraction
{
    var $fields = array();
    var $tables='requirement';

    function requirement()
    {
        $this->fields = requirement_dd::load_dictionary();
        $this->relations = requirement_dd::load_relationships();
        $this->subclasses = requirement_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('requirement_no, type, year, document');
        $this->set_values("'$requirement_no', '$type', '$year', '$document'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("type = '$type', year = '$year', document = '$document'");
        $this->set_where("requirement_no = '$requirement_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("requirement_no = '$requirement_no'");
        $this->make_query(TRUE,$log_query);
    }

    function select($log_query=TRUE)
    {
        $this->set_query_type('SELECT');
        $result = $this->make_query(TRUE,$log_query);
        return $result;
    }

    function check_uniqueness($param)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('SELECT');
        $this->set_where("requirement_no = '$requirement_no'");
        $this->make_query(TRUE,$log_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this->is_unique;
    }

    function check_uniqueness_for_editing($param)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('SELECT');
        $this->set_where("requirement_no = '$requirement_no' AND (requirement_no != '$requirement_no')");
        $this->make_query(TRUE,$log_query);
        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this->is_unique;
    }

    function export_to_csv()
    {
        $result = $this->select();
        while($data = $result->fetch_assoc())
        {
            extract($data);

            $requirement_no = str_replace('"',"''", $requirement_no);
            $type = str_replace('"',"''", $type);
            $year = str_replace('"',"''", $year);
            $document = str_replace('"',"''", $document);

            $csv_contents .= '"' . $requirement_no . '","' . $type . '","' . $year . '","' . $document . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
