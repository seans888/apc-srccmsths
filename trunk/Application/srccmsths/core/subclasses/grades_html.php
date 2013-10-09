<?php
require_once 'grades_dd.php';
class grades_html extends html
{
    function grades_html()
    {
        $this->fields = grades_dd::load_dictionary();
        $this->relations = grades_dd::load_relationships();
        $this->subclasses = grades_dd::load_subclass_info();
    }
}
