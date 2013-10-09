<?php
require_once 'subjectstudentsection_dd.php';
class subjectstudentsection_html extends html
{
    function subjectstudentsection_html()
    {
        $this->fields = subjectstudentsection_dd::load_dictionary();
        $this->relations = subjectstudentsection_dd::load_relationships();
        $this->subclasses = subjectstudentsection_dd::load_subclass_info();
    }
}
