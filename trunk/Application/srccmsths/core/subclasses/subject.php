<?php
require_once 'subject_dd.php';
class subject extends data_abstraction
{
    var $fields = array();
    var $tables='subject';

    function subject()
    {
        $this->fields = subject_dd::load_dictionary();
        $this->relations = subject_dd::load_relationships();
        $this->subclasses = subject_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('subject_no, code, name, year');
        $this->set_values("'$subject_no', '$code', '$name', '$year'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("code = '$code', name = '$name', year = '$year'");
        $this->set_where("subject_no = '$subject_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("subject_no = '$subject_no'");
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
        $this->set_where("subject_no = '$subject_no'");
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
        $this->set_where("subject_no = '$subject_no' AND (subject_no != '$subject_no')");
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

            $subject_no = str_replace('"',"''", $subject_no);
            $code = str_replace('"',"''", $code);
            $name = str_replace('"',"''", $name);
            $year = str_replace('"',"''", $year);

            $csv_contents .= '"' . $subject_no . '","' . $code . '","' . $name . '","' . $year . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
