<?php
class char_set
{
    var $allowed_chars = array();
    var $allow_space=TRUE;

    //The functions below are used to generate useful character sets that can be used in
    //tandem with the filtering functions. For example, generate_alphanum_set() is used to create
    //an array that contains alphanumeric characters (a-z, A-z, 0-9), plus anything else that
    //you may want to add (like a dash '-' or an underscore '_'), then use the generated char set
    //as the $char_set parameter to data_filter().

    function add_allowed_chars($allow)
    {
        $add_chars = explode(" ",$allow);
        $num_chars=count($add_chars);
        for($a=0;$a<$num_chars;$a++)
        {
            $this->allowed_chars[] = $add_chars[$a];
        }
    }

    //This function generates a set of alphanumeric characters, and allows additional characters
    //to be added to the set, specified in $allow. Note that the each character you want to
    //allow must be separated by a space. For example, if you with to allow a dash and an underscore,
    //the syntax would be generate_alphanum_set("- _");. This is true for all other functions that follow.
    //If you don't want a space character in the data set, set the $allow_space property to FALSE;
    function generate_alphanum_set($allow=null)
    {
        $this->allowed_chars = array(
            '0','1','2','3','4','5','6','7','8','9',
            'A','B','C','D','E','F','G','H','I','J','K','L','M',
            'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m',
            'n','o','p','q','r','s','t','u','v','w','x','y','z',
            "\n", "\r"
        );

        if($this->allow_space) 
        {
            $this->allowed_chars[] = ' ';
        }
        if($allow === null) 
        {
            //If $allow is left at default value, don't add anything into the array.
        }
        else
        {
            $this->add_allowed_chars($allow);
        }
    }

    //This function generates a set of numeric characters.
    function generate_num_set($allow=null)
    {
        $this->allowed_chars = array(
            '0','1','2','3','4','5','6','7','8','9'
        );

        if($this->allow_space) 
        {
            $this->allowed_chars[] = ' ';
        }
        if($allow === null) 
        {
            //If $allow is left at default value, don't add anything into the array.
        }
        else
        {
            $this->add_allowed_chars($allow);
        }
    }

    //This function generates an alphabetical set of characters.
    function generate_alpha_set($allow=null)
    {
        $this->allowed_chars = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M',
            'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m',
            'n','o','p','q','r','s','t','u','v','w','x','y','z',
            "\n", "\r"
        );

        if($this->allow_space) 
        {
            $this->allowed_chars[] = ' ';
        }
        if($allow === null) 
        {
            //If $allow is left at default value, don't add anything into the array.
        }
        else
        {
            $this->add_allowed_chars($allow);
        }
    }
}
?>
