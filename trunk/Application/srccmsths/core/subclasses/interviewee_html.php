<?php
require_once 'interviewee_dd.php';
class interviewee_html extends html
{
    function interviewee_html()
    {
        $this->fields = interviewee_dd::load_dictionary();
        $this->relations = interviewee_dd::load_relationships();
        $this->subclasses = interviewee_dd::load_subclass_info();
    }
}
