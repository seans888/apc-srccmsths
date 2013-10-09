<?php
require_once 'applicant_has_requirement_dd.php';
class applicant_has_requirement extends data_abstraction
{
    var $fields = array();
    var $tables='applicant_has_requirement';

    function applicant_has_requirement()
    {
        $this->fields = applicant_has_requirement_dd::load_dictionary();
        $this->relations = applicant_has_requirement_dd::load_relationships();
        $this->subclasses = applicant_has_requirement_dd::load_subclass_info();
    }

    function add($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('Applicant_applicant_no, Requirement_requirement_no, submitted');
        $this->set_values("'$Applicant_applicant_no', '$Requirement_requirement_no', '$submitted'");
        $this->make_query(TRUE,$log_query);
    }

    function edit($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('UPDATE');
        $this->set_update("Applicant_applicant_no = '$Applicant_applicant_no', Requirement_requirement_no = '$Requirement_requirement_no', submitted = '$submitted'");
        $this->set_where("Applicant_applicant_no = '$orig_Applicant_applicant_no' AND Requirement_requirement_no = '$orig_Requirement_requirement_no'");
        $this->make_query(TRUE,$log_query);
    }

    function del($param, $log_query=TRUE)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('DELETE');
        $this->set_where("Applicant_applicant_no = '$Applicant_applicant_no' AND Requirement_requirement_no = '$Requirement_requirement_no'");
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
        $this->set_where("Applicant_applicant_no = '$Applicant_applicant_no' AND Requirement_requirement_no = '$Requirement_requirement_no'");
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
        $this->set_where("Applicant_applicant_no = '$Applicant_applicant_no' AND Requirement_requirement_no = '$Requirement_requirement_no' AND (Applicant_applicant_no != '$orig_Applicant_applicant_no' OR Requirement_requirement_no != '$orig_Requirement_requirement_no')");
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

            $Applicant_applicant_no = str_replace('"',"''", $Applicant_applicant_no);
            $Requirement_requirement_no = str_replace('"',"''", $Requirement_requirement_no);
            $submitted = str_replace('"',"''", $submitted);

            $csv_contents .= '"' . $Applicant_applicant_no . '","' . $Requirement_requirement_no . '","' . $submitted . '"' . "\r\n";
        }

        return $csv_contents;
    }
}
