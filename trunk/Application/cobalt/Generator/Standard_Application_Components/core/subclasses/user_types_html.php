<?php
require_once 'user_types_dd.php';
class user_types_html extends html
{
    function user_types_html()
    {
        $this->fields = user_types_dd::load_dictionary();
        $this->relations = user_types_dd::load_relationships();
        $this->subclasses = user_types_dd::load_subclass_info();
    }
}
