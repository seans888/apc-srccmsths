<?php
require_once 'person_dd.php';
class person_html extends html
{
    function person_html()
    {
        $this->fields = person_dd::load_dictionary();
        $this->relations = person_dd::load_relationships();
        $this->subclasses = person_dd::load_subclass_info();
    }
}
