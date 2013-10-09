<?php
class grades_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'quarter' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'1',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Quarter',
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
                        'grade' => array('value'=>'',
                                              'data_type'=>'decimal',
                                              'length'=>'5',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Grade',
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
                        'letter_equiv' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'2',
                                              'attribute'=>'required',
                                              'control_type'=>'textbox',
                                              'label'=>'Letter Equiv',
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
                        'SubjectStudentSection_Subject_subject_no' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'SubjectStudentSection Subject Subject No',
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
                        'SubjectStudentSection_StudentSection_Student_student_no' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'SubjectStudentSection StudentSection Student Student No',
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
                        'SubjectStudentSection_StudentSection_Section_section_no' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'SubjectStudentSection StudentSection Section Section No',
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
        $subclasses = array('html_file'=>'grades_html.php',
                            'html_class'=>'grades_html',
                            'data_file'=>'grades.php',
                            'data_class'=>'grades');
        return $subclasses;
    }

}