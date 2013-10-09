<?php
require_once 'exam_dd.php';
class exam extends data_abstraction
{
    var $fields = array();
    var $tables='exam';

    function exam()
    {
        $this->fields = exam_dd::load_dictionary();
        $this->relations = exam_dd::load_relationships();
        $this->subclasses = exam_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('exam_no, score, date, Applicant_applicant_no');
        $this->set_values("'$exam_no', '$score', '$date', '$Applicant_applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("score = '$score', date = '$date', Applicant_applicant_no = '$Applicant_applicant_no'");
        $this->set_where("exam_no = '$exam_no' AND Applicant_applicant_no = '$orig_Applicant_applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("exam_no = '$exam_no' AND Applicant_applicant_no = '$Applicant_applicant_no'");
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
        $this->set_where("exam_no = '$exam_no' AND Applicant_applicant_no = '$Applicant_applicant_no'");
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
        $this->set_where("exam_no = '$exam_no' AND Applicant_applicant_no = '$Applicant_applicant_no' AND (exam_no != '$exam_no' OR Applicant_applicant_no != '$orig_Applicant_applicant_no')");
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

            $exam_no = str_replace('"',"''", $exam_no);
            $score = str_replace('"',"''", $score);
            $date = str_replace('"',"''", $date);
            $Applicant_applicant_no = str_replace('"',"''", $Applicant_applicant_no);

            $csv_contents .= '"' . $exam_no . '","' . $score . '","' . $date . '","' . $Applicant_applicant_no . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
