<?php
require_once 'subjectstudentsection_dd.php';
class subjectstudentsection extends data_abstraction
{
    var $fields = array();
    var $tables='subjectstudentsection';

    function subjectstudentsection()
    {
        $this->fields = subjectstudentsection_dd::load_dictionary();
        $this->relations = subjectstudentsection_dd::load_relationships();
        $this->subclasses = subjectstudentsection_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('Subject_subject_no, StudentSection_Student_student_no, StudentSection_Section_section_no');
        $this->set_values("'$Subject_subject_no', '$StudentSection_Student_student_no', '$StudentSection_Section_section_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("Subject_subject_no = '$Subject_subject_no', StudentSection_Student_student_no = '$StudentSection_Student_student_no', StudentSection_Section_section_no = '$StudentSection_Section_section_no'");
        $this->set_where("Subject_subject_no = '$orig_Subject_subject_no' AND StudentSection_Student_student_no = '$orig_StudentSection_Student_student_no' AND StudentSection_Section_section_no = '$orig_StudentSection_Section_section_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("Subject_subject_no = '$Subject_subject_no' AND StudentSection_Student_student_no = '$StudentSection_Student_student_no' AND StudentSection_Section_section_no = '$StudentSection_Section_section_no'");
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
        $this->set_where("Subject_subject_no = '$Subject_subject_no' AND StudentSection_Student_student_no = '$StudentSection_Student_student_no' AND StudentSection_Section_section_no = '$StudentSection_Section_section_no'");
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
        $this->set_where("Subject_subject_no = '$Subject_subject_no' AND StudentSection_Student_student_no = '$StudentSection_Student_student_no' AND StudentSection_Section_section_no = '$StudentSection_Section_section_no' AND (Subject_subject_no != '$orig_Subject_subject_no' OR StudentSection_Student_student_no != '$orig_StudentSection_Student_student_no' OR StudentSection_Section_section_no != '$orig_StudentSection_Section_section_no')");
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

            $Subject_subject_no = str_replace('"',"''", $Subject_subject_no);
            $StudentSection_Student_student_no = str_replace('"',"''", $StudentSection_Student_student_no);
            $StudentSection_Section_section_no = str_replace('"',"''", $StudentSection_Section_section_no);

            $csv_contents .= '"' . $Subject_subject_no . '","' . $StudentSection_Student_student_no . '","' . $StudentSection_Section_section_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
