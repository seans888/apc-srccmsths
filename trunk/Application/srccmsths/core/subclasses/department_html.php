<?php
require_once 'department_dd.php';
class department_html extends html
{
    function department_html()
    {
        $this->fields = department_dd::load_dictionary();
        $this->relations = department_dd::load_relationships();
        $this->subclasses = department_dd::load_subclass_info();
    }
}
