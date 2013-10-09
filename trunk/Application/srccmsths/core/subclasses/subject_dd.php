<?php
class subject_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'subject_no' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'none',
                                              'label'=>'Subject No',
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
                        'code' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'45',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Code',
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
                        'name' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'45',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Name',
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
                        'year' => array('value'=>'',
                                              'data_type'=>'year',
                                              'length'=>'4',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Year',
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
        $subclasses = array('html_file'=>'subject_html.php',
                            'html_class'=>'subject_html',
                            'data_file'=>'subject.php',
                            'data_class'=>'subject');
        return $subclasses;
    }

}