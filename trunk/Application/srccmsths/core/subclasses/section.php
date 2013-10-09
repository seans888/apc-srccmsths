<?php
require_once 'section_dd.php';
class section extends data_abstraction
{
    var $fields = array();
    var $tables='section';

    function section()
    {
        $this->fields = section_dd::load_dictionary();
        $this->relations = section_dd::load_relationships();
        $this->subclasses = section_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('section_no, name, year, Teacher_teacher_no, Teacher_Department_dept_no');
        $this->set_values("'$section_no', '$name', '$year', '$Teacher_teacher_no', '$Teacher_Department_dept_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("name = '$name', year = '$year', Teacher_teacher_no = '$Teacher_teacher_no', Teacher_Department_dept_no = '$Teacher_Department_dept_no'");
        $this->set_where("section_no = '$section_no' AND Teacher_teacher_no = '$orig_Teacher_teacher_no' AND Teacher_Department_dept_no = '$orig_Teacher_Department_dept_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("section_no = '$section_no' AND Teacher_teacher_no = '$Teacher_teacher_no' AND Teacher_Department_dept_no = '$Teacher_Department_dept_no'");
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
        $this->set_where("section_no = '$section_no' AND Teacher_teacher_no = '$Teacher_teacher_no' AND Teacher_Department_dept_no = '$Teacher_Department_dept_no'");
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
        $this->set_where("section_no = '$section_no' AND Teacher_teacher_no = '$Teacher_teacher_no' AND Teacher_Department_dept_no = '$Teacher_Department_dept_no' AND (section_no != '$section_no' OR Teacher_teacher_no != '$orig_Teacher_teacher_no' OR Teacher_Department_dept_no != '$orig_Teacher_Department_dept_no')");
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

            $section_no = str_replace('"',"''", $section_no);
            $name = str_replace('"',"''", $name);
            $year = str_replace('"',"''", $year);
            $Teacher_teacher_no = str_replace('"',"''", $Teacher_teacher_no);
            $Teacher_Department_dept_no = str_replace('"',"''", $Teacher_Department_dept_no);

            $csv_contents .= '"' . $section_no . '","' . $name . '","' . $year . '","' . $Teacher_teacher_no . '","' . $Teacher_Department_dept_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
