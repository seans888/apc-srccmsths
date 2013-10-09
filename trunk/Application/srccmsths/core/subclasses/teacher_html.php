<?php
require_once 'teacher_dd.php';
class teacher_html extends html
{
    function teacher_html()
    {
        $this->fields = teacher_dd::load_dictionary();
        $this->relations = teacher_dd::load_relationships();
        $this->subclasses = teacher_dd::load_subclass_info();
    }
}
