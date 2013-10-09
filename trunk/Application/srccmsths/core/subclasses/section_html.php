<?php
require_once 'section_dd.php';
class section_html extends html
{
    function section_html()
    {
        $this->fields = section_dd::load_dictionary();
        $this->relations = section_dd::load_relationships();
        $this->subclasses = section_dd::load_subclass_info();
    }
}
