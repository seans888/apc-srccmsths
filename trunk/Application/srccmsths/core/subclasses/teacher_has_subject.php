<?php
require_once 'teacher_has_subject_dd.php';
class teacher_has_subject extends data_abstraction
{
    var $fields = array();
    var $tables='teacher_has_subject';

    function teacher_has_subject()
    {
        $this->fields = teacher_has_subject_dd::load_dictionary();
        $this->relations = teacher_has_subject_dd::load_relationships();
        $this->subclasses = teacher_has_subject_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('Teacher_teacher_no, Teacher_Department_dept_no, Subject_subject_no, year');
        $this->set_values("'$Teacher_teacher_no', '$Teacher_Department_dept_no', '$Subject_subject_no', '$year'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("Teacher_teacher_no = '$Teacher_teacher_no', Teacher_Department_dept_no = '$Teacher_Department_dept_no', Subject_subject_no = '$Subject_subject_no', year = '$year'");
        $this->set_where("Teacher_teacher_no = '$orig_Teacher_teacher_no' AND Teacher_Department_dept_no = '$orig_Teacher_Department_dept_no' AND Subject_subject_no = '$orig_Subject_subject_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("Teacher_teacher_no = '$Teacher_teacher_no' AND Teacher_Department_dept_no = '$Teacher_Department_dept_no' AND Subject_subject_no = '$Subject_subject_no'");
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
        $this->set_where("Teacher_teacher_no = '$Teacher_teacher_no' AND Teacher_Department_dept_no = '$Teacher_Department_dept_no' AND Subject_subject_no = '$Subject_subject_no'");
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
        $this->set_where("Teacher_teacher_no = '$Teacher_teacher_no' AND Teacher_Department_dept_no = '$Teacher_Department_dept_no' AND Subject_subject_no = '$Subject_subject_no' AND (Teacher_teacher_no != '$orig_Teacher_teacher_no' OR Teacher_Department_dept_no != '$orig_Teacher_Department_dept_no' OR Subject_subject_no != '$orig_Subject_subject_no')");
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

            $Teacher_teacher_no = str_replace('"',"''", $Teacher_teacher_no);
            $Teacher_Department_dept_no = str_replace('"',"''", $Teacher_Department_dept_no);
            $Subject_subject_no = str_replace('"',"''", $Subject_subject_no);
            $year = str_replace('"',"''", $year);

            $csv_contents .= '"' . $Teacher_teacher_no . '","' . $Teacher_Department_dept_no . '","' . $Subject_subject_no . '","' . $year . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
