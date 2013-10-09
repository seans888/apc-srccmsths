<?php
class user_passport_groups_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'passport_group_id' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'6',
                                              'attribute'=>'primary key',
                                              'control_type'=>'none',
                                              'label'=>'Passport Group ID',
                                              'extra'=>'',
                                              'in_listview'=>'no',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'passport_group' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'255',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Passport Group',
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
                        'icon' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'255',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Icon',
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
        $subclasses = array('html_file'=>'user_passport_groups_html.php',
                            'html_class'=>'user_passport_groups_html',
                            'data_file'=>'user_passport_groups.php',
                            'data_class'=>'user_passport_groups');
        return $subclasses;
    }

}
