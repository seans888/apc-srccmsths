<?php
require_once 'applicant_dd.php';
class applicant_html extends html
{
    function applicant_html()
    {
        $this->fields = applicant_dd::load_dictionary();
        $this->relations = applicant_dd::load_relationships();
        $this->subclasses = applicant_dd::load_subclass_info();
    }
}
