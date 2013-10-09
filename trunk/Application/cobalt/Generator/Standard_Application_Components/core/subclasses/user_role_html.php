<?php
require_once 'user_role_dd.php';
class user_role_html extends html
{
    function user_role_html()
    {
        $this->fields = user_role_dd::load_dictionary();
        $this->relations = user_role_dd::load_relationships();
        $this->subclasses = user_role_dd::load_subclass_info();
    }
}
