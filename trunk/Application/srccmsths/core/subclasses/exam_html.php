<?php
require_once 'exam_dd.php';
class exam_html extends html
{
    function exam_html()
    {
        $this->fields = exam_dd::load_dictionary();
        $this->relations = exam_dd::load_relationships();
        $this->subclasses = exam_dd::load_subclass_info();
    }
}
