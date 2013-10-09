<?php
require_once 'applicant_has_requirement_dd.php';
class applicant_has_requirement_html extends html
{
    function applicant_has_requirement_html()
    {
        $this->fields = applicant_has_requirement_dd::load_dictionary();
        $this->relations = applicant_has_requirement_dd::load_relationships();
        $this->subclasses = applicant_has_requirement_dd::load_subclass_info();
    }
}
