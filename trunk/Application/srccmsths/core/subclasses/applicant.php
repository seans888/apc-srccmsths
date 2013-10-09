<?php
require_once 'applicant_dd.php';
class applicant extends data_abstraction
{
    var $fields = array();
    var $tables='applicant';

    function applicant()
    {
        $this->fields = applicant_dd::load_dictionary();
        $this->relations = applicant_dd::load_relationships();
        $this->subclasses = applicant_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('applicant_no, learners_no, first_name, last_name, middle_name, date_of_birth, gender, year');
        $this->set_values("'$applicant_no', '$learners_no', '$first_name', '$last_name', '$middle_name', '$date_of_birth', '$gender', '$year'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("learners_no = '$learners_no', first_name = '$first_name', last_name = '$last_name', middle_name = '$middle_name', date_of_birth = '$date_of_birth', gender = '$gender', year = '$year'");
        $this->set_where("applicant_no = '$applicant_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("applicant_no = '$applicant_no'");
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
        $this->set_where("applicant_no = '$applicant_no'");
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
        $this->set_where("applicant_no = '$applicant_no' AND (applicant_no != '$applicant_no')");
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

            $applicant_no = str_replace('"',"''", $applicant_no);
            $learners_no = str_replace('"',"''", $learners_no);
            $first_name = str_replace('"',"''", $first_name);
            $last_name = str_replace('"',"''", $last_name);
            $middle_name = str_replace('"',"''", $middle_name);
            $date_of_birth = str_replace('"',"''", $date_of_birth);
            $gender = str_replace('"',"''", $gender);
            $year = str_replace('"',"''", $year);

            $csv_contents .= '"' . $applicant_no . '","' . $learners_no . '","' . $first_name . '","' . $last_name . '","' . $middle_name . '","' . $date_of_birth . '","' . $gender . '","' . $year . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
