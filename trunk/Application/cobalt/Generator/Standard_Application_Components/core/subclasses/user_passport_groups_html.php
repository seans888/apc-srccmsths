<?php
require_once 'user_passport_groups_dd.php';
class user_passport_groups_html extends html
{
    function user_passport_groups_html()
    {
        $this->fields = user_passport_groups_dd::load_dictionary();
        $this->relations = user_passport_groups_dd::load_relationships();
        $this->subclasses = user_passport_groups_dd::load_subclass_info();
    }
}
