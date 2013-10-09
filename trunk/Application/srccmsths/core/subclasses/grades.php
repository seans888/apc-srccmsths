<?php
require_once 'grades_dd.php';
class grades extends data_abstraction
{
    var $fields = array();
    var $tables='grades';

    function grades()
    {
        $this->fields = grades_dd::load_dictionary();
        $this->relations = grades_dd::load_relationships();
        $this->subclasses = grades_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('quarter, grade, letter_equiv, SubjectStudentSection_Subject_subject_no, SubjectStudentSection_StudentSection_Student_student_no, SubjectStudentSection_StudentSection_Section_section_no');
        $this->set_values("'$quarter', '$grade', '$letter_equiv', '$SubjectStudentSection_Subject_subject_no', '$SubjectStudentSection_StudentSection_Student_student_no', '$SubjectStudentSection_StudentSection_Section_section_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("quarter = '$quarter', grade = '$grade', letter_equiv = '$letter_equiv', SubjectStudentSection_Subject_subject_no = '$SubjectStudentSection_Subject_subject_no', SubjectStudentSection_StudentSection_Student_student_no = '$SubjectStudentSection_StudentSection_Student_student_no', SubjectStudentSection_StudentSection_Section_section_no = '$SubjectStudentSection_StudentSection_Section_section_no'");
        $this->set_where("quarter = '$orig_quarter' AND SubjectStudentSection_Subject_subject_no = '$orig_SubjectStudentSection_Subject_subject_no' AND SubjectStudentSection_StudentSection_Student_student_no = '$orig_SubjectStudentSection_StudentSection_Student_student_no' AND SubjectStudentSection_StudentSection_Section_section_no = '$orig_SubjectStudentSection_StudentSection_Section_section_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("quarter = '$quarter' AND SubjectStudentSection_Subject_subject_no = '$SubjectStudentSection_Subject_subject_no' AND SubjectStudentSection_StudentSection_Student_student_no = '$SubjectStudentSection_StudentSection_Student_student_no' AND SubjectStudentSection_StudentSection_Section_section_no = '$SubjectStudentSection_StudentSection_Section_section_no'");
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
        $this->set_where("quarter = '$quarter' AND SubjectStudentSection_Subject_subject_no = '$SubjectStudentSection_Subject_subject_no' AND SubjectStudentSection_StudentSection_Student_student_no = '$SubjectStudentSection_StudentSection_Student_student_no' AND SubjectStudentSection_StudentSection_Section_section_no = '$SubjectStudentSection_StudentSection_Section_section_no'");
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
        $this->set_where("quarter = '$quarter' AND SubjectStudentSection_Subject_subject_no = '$SubjectStudentSection_Subject_subject_no' AND SubjectStudentSection_StudentSection_Student_student_no = '$SubjectStudentSection_StudentSection_Student_student_no' AND SubjectStudentSection_StudentSection_Section_section_no = '$SubjectStudentSection_StudentSection_Section_section_no' AND (quarter != '$orig_quarter' OR SubjectStudentSection_Subject_subject_no != '$orig_SubjectStudentSection_Subject_subject_no' OR SubjectStudentSection_StudentSection_Student_student_no != '$orig_SubjectStudentSection_StudentSection_Student_student_no' OR SubjectStudentSection_StudentSection_Section_section_no != '$orig_SubjectStudentSection_StudentSection_Section_section_no')");
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

            $quarter = str_replace('"',"''", $quarter);
            $grade = str_replace('"',"''", $grade);
            $letter_equiv = str_replace('"',"''", $letter_equiv);
            $SubjectStudentSection_Subject_subject_no = str_replace('"',"''", $SubjectStudentSection_Subject_subject_no);
            $SubjectStudentSection_StudentSection_Student_student_no = str_replace('"',"''", $SubjectStudentSection_StudentSection_Student_student_no);
            $SubjectStudentSection_StudentSection_Section_section_no = str_replace('"',"''", $SubjectStudentSection_StudentSection_Section_section_no);

            $csv_contents .= '"' . $quarter . '","' . $grade . '","' . $letter_equiv . '","' . $SubjectStudentSection_Subject_subject_no . '","' . $SubjectStudentSection_StudentSection_Student_student_no . '","' . $SubjectStudentSection_StudentSection_Section_section_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
