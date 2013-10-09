<?php
require_once 'subject_dd.php';
class subject_html extends html
{
    function subject_html()
    {
        $this->fields = subject_dd::load_dictionary();
        $this->relations = subject_dd::load_relationships();
        $this->subclasses = subject_dd::load_subclass_info();
    }
}
