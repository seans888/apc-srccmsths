<?php
require_once 'user_links_dd.php';
class user_links_html extends html
{
    function user_links_html()
    {
        $this->fields = user_links_dd::load_dictionary();
        $this->relations = user_links_dd::load_relationships();
        $this->subclasses = user_links_dd::load_subclass_info();
    }
}
