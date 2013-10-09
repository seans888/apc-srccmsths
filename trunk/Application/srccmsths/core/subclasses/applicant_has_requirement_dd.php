<?php
class applicant_has_requirement_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'Applicant_applicant_no' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Applicant Applicant No',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'Requirement_requirement_no' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Requirement Requirement No',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'submitted' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'1',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Submitted',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'',
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
        $subclasses = array('html_file'=>'applicant_has_requirement_html.php',
                            'html_class'=>'applicant_has_requirement_html',
                            'data_file'=>'applicant_has_requirement.php',
                            'data_class'=>'applicant_has_requirement');
        return $subclasses;
    }

}