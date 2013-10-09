<?php
class user_role_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'role_id' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'none',
                                              'label'=>'Role ID',
                                              'extra'=>'',
                                              'in_listview'=>'no',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'role' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'255',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Role',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_alphanum_set',
                                              'char_set_allow_space'=>'true',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'description' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'255',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Description',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_alphanum_set',
                                              'char_set_allow_space'=>'true',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array(''))
                       );
        return $fields;
    }

    static function load_relationships()
    {
        $relations = array();

        return $relations;
    }

    static function load_subclass_info()
    {
        $subclasses = array('html_file'=>'user_role_html.php',
                            'html_class'=>'user_role_html',
                            'data_file'=>'user_role.php',
                            'data_class'=>'user_role');
        return $subclasses;
    }

}
