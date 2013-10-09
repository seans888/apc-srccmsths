<?php
require_once 'studentsection_dd.php';
class studentsection_html extends html
{
    function studentsection_html()
    {
        $this->fields = studentsection_dd::load_dictionary();
        $this->relations = studentsection_dd::load_relationships();
        $this->subclasses = studentsection_dd::load_subclass_info();
    }
}
