<?php
class user_role_links extends data_abstraction
{
    var $fields = array(
                        'link_id' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Link ID',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'extra_chars_allowed'=>'',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'role_id' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Role ID',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'extra_chars_allowed'=>'',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array(''))
                      );

    var $tables='user_role_links';
    var $arr_link=array();
    var $arr_link_name=array();

    function add($param)
    {
        $this->escape_arguments($param);
        extract($param);
        $this->set_query_type('INSERT');
        $this->set_fields('link_id, role_id');
        $this->set_values("'$link_id', '$role_id'");
        $this->make_query();
    }
    
    function del($role_id)
    {
        $this->escape_arguments($role_id);
        $this->set_query_type('DELETE');
        $this->set_where("role_id = '$role_id'");
        $this->make_query();
    }
    
    function get_user_role_links($role_id)
    {
        $this->set_table("$this->tables a, user_links b");
        $this->set_fields('a.link_id, b.descriptive_title');
        $this->set_where("a.role_id='$role_id' AND a.link_id=b.link_id");
        $this->set_order("b.descriptive_title");
        if($result = $this->make_query())
        {
            for($a=0; $a<$this->num_rows; $a++)
            {
                $data = $result->fetch_row();
                $this->arr_link[] = $data[0];
                $this->arr_link_name[] = $data[1];
            }
            $result->close();
        }
    }
}
