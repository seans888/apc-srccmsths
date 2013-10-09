<?php
class system_settings_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'setting' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'255',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Setting',
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
                        'value' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'255',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Value',
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
        $subclasses = array('html_file'=>'system_settings_html.php',
                            'html_class'=>'system_settings_html',
                            'data_file'=>'system_settings.php',
                            'data_class'=>'system_settings');
        return $subclasses;
    }

}
