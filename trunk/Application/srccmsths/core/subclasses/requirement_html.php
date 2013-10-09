<?php
require_once 'requirement_dd.php';
class requirement_html extends html
{
    function requirement_html()
    {
        $this->fields = requirement_dd::load_dictionary();
        $this->relations = requirement_dd::load_relationships();
        $this->subclasses = requirement_dd::load_subclass_info();
    }
}
