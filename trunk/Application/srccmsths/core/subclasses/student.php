<?php
require_once 'student_dd.php';
class student extends data_abstraction
{
    var $fields = array();
    var $tables='student';

    function student()
    {
        $this->fields = student_dd::load_dictionary();
        $this->relations = student_dd::load_relationships();
        $this->subclasses = student_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('student_no, year_level, Interviewee_interview_no, Interviewee_Exam_exam_no, Interviewee_Exam_Applicant_applicant_no');
        $this->set_values("'$student_no', '$year_level', '$Interviewee_interview_no', '$Interviewee_Exam_exam_no', '$Interviewee_Exam_Applicant_applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("student_no = '$student_no', year_level = '$year_level', Interviewee_interview_no = '$Interviewee_interview_no', Interviewee_Exam_exam_no = '$Interviewee_Exam_exam_no', Interviewee_Exam_Applicant_applicant_no = '$Interviewee_Exam_Applicant_applicant_no'");
        $this->set_where("student_no = '$orig_student_no' AND Interviewee_interview_no = '$orig_Interviewee_interview_no' AND Interviewee_Exam_exam_no = '$orig_Interviewee_Exam_exam_no' AND Interviewee_Exam_Applicant_applicant_no = '$orig_Interviewee_Exam_Applicant_applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("student_no = '$student_no' AND Interviewee_interview_no = '$Interviewee_interview_no' AND Interviewee_Exam_exam_no = '$Interviewee_Exam_exam_no' AND Interviewee_Exam_Applicant_applicant_no = '$Interviewee_Exam_Applicant_applicant_no'");
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
        $this->set_where("student_no = '$student_no' AND Interviewee_interview_no = '$Interviewee_interview_no' AND Interviewee_Exam_exam_no = '$Interviewee_Exam_exam_no' AND Interviewee_Exam_Applicant_applicant_no = '$Interviewee_Exam_Applicant_applicant_no'");
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
        $this->set_where("student_no = '$student_no' AND Interviewee_interview_no = '$Interviewee_interview_no' AND Interviewee_Exam_exam_no = '$Interviewee_Exam_exam_no' AND Interviewee_Exam_Applicant_applicant_no = '$Interviewee_Exam_Applicant_applicant_no' AND (student_no != '$orig_student_no' OR Interviewee_interview_no != '$orig_Interviewee_interview_no' OR Interviewee_Exam_exam_no != '$orig_Interviewee_Exam_exam_no' OR Interviewee_Exam_Applicant_applicant_no != '$orig_Interviewee_Exam_Applicant_applicant_no')");
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

            $student_no = str_replace('"',"''", $student_no);
            $year_level = str_replace('"',"''", $year_level);
            $Interviewee_interview_no = str_replace('"',"''", $Interviewee_interview_no);
            $Interviewee_Exam_exam_no = str_replace('"',"''", $Interviewee_Exam_exam_no);
            $Interviewee_Exam_Applicant_applicant_no = str_replace('"',"''", $Interviewee_Exam_Applicant_applicant_no);

            $csv_contents .= '"' . $student_no . '","' . $year_level . '","' . $Interviewee_interview_no . '","' . $Interviewee_Exam_exam_no . '","' . $Interviewee_Exam_Applicant_applicant_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
