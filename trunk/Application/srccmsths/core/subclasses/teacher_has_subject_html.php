<?php
require_once 'teacher_has_subject_dd.php';
class teacher_has_subject_html extends html
{
    function teacher_has_subject_html()
    {
        $this->fields = teacher_has_subject_dd::load_dictionary();
        $this->relations = teacher_has_subject_dd::load_relationships();
        $this->subclasses = teacher_has_subject_dd::load_subclass_info();
    }
}
