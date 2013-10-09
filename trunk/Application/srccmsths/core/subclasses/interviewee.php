<?php
require_once 'interviewee_dd.php';
class interviewee extends data_abstraction
{
    var $fields = array();
    var $tables='interviewee';

    function interviewee()
    {
        $this->fields = interviewee_dd::load_dictionary();
        $this->relations = interviewee_dd::load_relationships();
        $this->subclasses = interviewee_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('interview_no, status, date, Exam_exam_no, Exam_Applicant_applicant_no');
        $this->set_values("'$interview_no', '$status', '$date', '$Exam_exam_no', '$Exam_Applicant_applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("status = '$status', date = '$date', Exam_exam_no = '$Exam_exam_no', Exam_Applicant_applicant_no = '$Exam_Applicant_applicant_no'");
        $this->set_where("interview_no = '$interview_no' AND Exam_exam_no = '$orig_Exam_exam_no' AND Exam_Applicant_applicant_no = '$orig_Exam_Applicant_applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("interview_no = '$interview_no' AND Exam_exam_no = '$Exam_exam_no' AND Exam_Applicant_applicant_no = '$Exam_Applicant_applicant_no'");
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
        $this->set_where("interview_no = '$interview_no' AND Exam_exam_no = '$Exam_exam_no' AND Exam_Applicant_applicant_no = '$Exam_Applicant_applicant_no'");
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
        $this->set_where("interview_no = '$interview_no' AND Exam_exam_no = '$Exam_exam_no' AND Exam_Applicant_applicant_no = '$Exam_Applicant_applicant_no' AND (interview_no != '$interview_no' OR Exam_exam_no != '$orig_Exam_exam_no' OR Exam_Applicant_applicant_no != '$orig_Exam_Applicant_applicant_no')");
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

            $interview_no = str_replace('"',"''", $interview_no);
            $status = str_replace('"',"''", $status);
            $date = str_replace('"',"''", $date);
            $Exam_exam_no = str_replace('"',"''", $Exam_exam_no);
            $Exam_Applicant_applicant_no = str_replace('"',"''", $Exam_Applicant_applicant_no);

            $csv_contents .= '"' . $interview_no . '","' . $status . '","' . $date . '","' . $Exam_exam_no . '","' . $Exam_Applicant_applicant_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
