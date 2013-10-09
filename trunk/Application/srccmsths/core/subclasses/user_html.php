<?php
require_once 'user_dd.php';
class user_html extends html
{
    function user_html()
    {
        $this->fields = user_dd::load_dictionary();
        $this->relations = user_dd::load_relationships();
        $this->subclasses = user_dd::load_subclass_info();
    }
}
