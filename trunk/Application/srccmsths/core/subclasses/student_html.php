<?php
require_once 'student_dd.php';
class student_html extends html
{
    function student_html()
    {
        $this->fields = student_dd::load_dictionary();
        $this->relations = student_dd::load_relationships();
        $this->subclasses = student_dd::load_subclass_info();
    }
}
