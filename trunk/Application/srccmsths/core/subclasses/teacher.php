<?php
require_once 'teacher_dd.php';
class teacher extends data_abstraction
{
    var $fields = array();
    var $tables='teacher';

    function teacher()
    {
        $this->fields = teacher_dd::load_dictionary();
        $this->relations = teacher_dd::load_relationships();
        $this->subclasses = teacher_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('teacher_no, last_name, first_name, middle_name, Department_dept_no');
        $this->set_values("'$teacher_no', '$last_name', '$first_name', '$middle_name', '$Department_dept_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("last_name = '$last_name', first_name = '$first_name', middle_name = '$middle_name', Department_dept_no = '$Department_dept_no'");
        $this->set_where("teacher_no = '$teacher_no' AND Department_dept_no = '$orig_Department_dept_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("teacher_no = '$teacher_no' AND Department_dept_no = '$Department_dept_no'");
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
        $this->set_where("teacher_no = '$teacher_no' AND Department_dept_no = '$Department_dept_no'");
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
        $this->set_where("teacher_no = '$teacher_no' AND Department_dept_no = '$Department_dept_no' AND (teacher_no != '$teacher_no' OR Department_dept_no != '$orig_Department_dept_no')");
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

            $teacher_no = str_replace('"',"''", $teacher_no);
            $last_name = str_replace('"',"''", $last_name);
            $first_name = str_replace('"',"''", $first_name);
            $middle_name = str_replace('"',"''", $middle_name);
            $Department_dept_no = str_replace('"',"''", $Department_dept_no);

            $csv_contents .= '"' . $teacher_no . '","' . $last_name . '","' . $first_name . '","' . $middle_name . '","' . $Department_dept_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
