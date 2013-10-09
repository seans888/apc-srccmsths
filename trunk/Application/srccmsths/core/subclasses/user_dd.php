<?php
class user_dd
{
    static function load_dictionary()
    {
        $fields = array(
                        'username' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'15',
                                              'attribute'=>'primary key',
                                              'control_type'=>'textbox',
                                              'label'=>'Username',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_alphanum_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'password' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'200',
                                              'attribute'=>'required',
                                              'control_type'=>'password',
                                              'label'=>'Password',
                                              'extra'=>'',
                                              'in_listview'=>'no',
                                              'char_set_method'=>'generate_alphanum_set',
                                              'char_set_allow_space'=>'true',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'',
                                              'list_settings'=>array('')),
                        'person_id' => array('value'=>'',
                                              'data_type'=>'varchar',
                                              'length'=>'20',
                                              'attribute'=>'foreign key',
                                              'control_type'=>'drop-down list',
                                              'label'=>'Person',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_alphanum_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'sql generated',
                                              'list_settings'=>array('query' => "SELECT person.person_id AS `queried_person_id`, person.first_name, person.middle_name, person.last_name FROM person ORDER BY person.last_name, person.first_name, person.middle_name",
                                                                     'list_value' => 'queried_person_id',
                                                                     'list_items' => array('last_name', 'first_name', 'middle_name'),
                                                                     'list_separators' => array(', '))),
                        'user_type_id' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'6',
                                              'attribute'=>'foreign key',
                                              'control_type'=>'drop-down list',
                                              'label'=>'User Type',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'\' / - ( ) + = . , ! ? # % & * ; : _ "',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'sql generated',
                                              'list_settings'=>array('query' => "SELECT user_types.user_type_id AS `queried_user_type_id`, user_types.user_type FROM user_types ORDER BY user_types.user_type",
                                                                     'list_value' => 'queried_user_type_id',
                                                                     'list_items' => array('user_type'),
                                                                     'list_separators' => array())),
                        'skin_id' => array('value'=>'',
                                              'data_type'=>'integer',
                                              'length'=>'11',
                                              'attribute'=>'foreign key',
                                              'control_type'=>'drop-down list',
                                              'label'=>'Skin',
                                              'extra'=>'',
                                              'in_listview'=>'yes',
                                              'char_set_method'=>'generate_num_set',
                                              'char_set_allow_space'=>'false',
                                              'extra_chars_allowed'=>'',
                                              'trim'=>'trim',
                                              'valid_set'=>array(),
                                              'date_elements'=>array('','',''),
                                              'book_list_generator'=>'',
                                              'list_type'=>'sql generated',
                                              'list_settings'=>array('query' => "SELECT system_skins.skin_id AS `queried_skin_id`, system_skins.skin_name FROM system_skins ORDER BY system_skins.skin_name",
                                                                     'list_value' => 'queried_skin_id',
                                                                     'list_items' => array('skin_name'),
                                                                     'list_separators' => array()))
                       );
        return $fields;
    }

    static function load_relationships()
    {
        $relations = array('1'=>array('type'=>'1-1',
                                      'table'=>'person',
                                      'link_parent'=>'person_id',
                                      'link_child'=>'person_id',
                                      'link_subtext'=>array('last_name','first_name','middle_name'),
                                      'where_clause'=>''),

                           '2'=>array('type'=>'1-1',
                                      'table'=>'user_types',
                                      'link_parent'=>'user_type_id',
                                      'link_child'=>'user_type_id',
                                      'link_subtext'=>array('user_type'),
                                      'where_clause'=>''),

                           '3'=>array('type'=>'1-1',
                                      'table'=>'system_skins',
                                      'link_parent'=>'skin_id',
                                      'link_child'=>'skin_id',
                                      'link_subtext'=>array('skin_name'),
                                      'where_clause'=>'')
                           );

        return $relations;
    }

    static function load_subclass_info()
    {
        $subclasses = array('html_file'=>'user_html.php',
                            'html_class'=>'user_html',
                            'data_file'=>'user.php',
                            'data_class'=>'user');
        return $subclasses;
    }

}
