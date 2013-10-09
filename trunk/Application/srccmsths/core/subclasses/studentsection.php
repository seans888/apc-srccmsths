<?php
require_once 'studentsection_dd.php';
class studentsection extends data_abstraction
{
    var $fields = array();
    var $tables='studentsection';

    function studentsection()
    {
        $this->fields = studentsection_dd::load_dictionary();
        $this->relations = studentsection_dd::load_relationships();
        $this->subclasses = studentsection_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('Student_student_no, Section_section_no, year');
        $this->set_values("'$Student_student_no', '$Section_section_no', '$year'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("Student_student_no = '$Student_student_no', Section_section_no = '$Section_section_no', year = '$year'");
        $this->set_where("Student_student_no = '$orig_Student_student_no' AND Section_section_no = '$orig_Section_section_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("Student_student_no = '$Student_student_no' AND Section_section_no = '$Section_section_no'");
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
        $this->set_where("Student_student_no = '$Student_student_no' AND Section_section_no = '$Section_section_no'");
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
        $this->set_where("Student_student_no = '$Student_student_no' AND Section_section_no = '$Section_section_no' AND (Student_student_no != '$orig_Student_student_no' OR Section_section_no != '$orig_Section_section_no')");
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

            $Student_student_no = str_replace('"',"''", $Student_student_no);
            $Section_section_no = str_replace('"',"''", $Section_section_no);
            $year = str_replace('"',"''", $year);

            $csv_contents .= '"' . $Student_student_no . '","' . $Section_section_no . '","' . $year . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
