<?php
function init_cobalt($required_passport=null)
{
    //Start the performance timer
    $start = microtime(true);
    define('PROCESS_START_TIME', $start);

    //Load the global config file and any other class or library files you want to be autoloaded at every page.
    require_once 'global_config.php';
    require_once 'data_abstraction_class.php';
    require_once 'html_class.php';

    //Some PHP versions (more recent) will complain if timezone is not explicitly set (through TZ env or date.timezone config).
    //This code tries to ensure that the verbose warning emitted by PHP will not appear even if this timezone setting is not 
    //explicitly set, by retreiving the TZ value then explicitly setting it through date_default_timezone_set().
    $tz_setting = @date_default_timezone_get();
    date_default_timezone_set($tz_setting);

    //Start session.
    session_start();
    if(!isset($_SESSION['initiated']))
    {
        session_regenerate_id(TRUE);
        $_SESSION['initiated'] = true;
    }

    if($required_passport!=null)
    {
        //Check if logged; if not, redirect to login page defined by global_config.php.
        if($_SESSION['logged'] != "Logged") 
        {
            header("location: " . LOGIN_PAGE);
            exit();
        }

        if($required_passport != 'ALLOW_ALL') check_passport($required_passport);
    }

    //Default database link - for use with quote_smart()
    //and any other functions that rely on MySQL functions
    //which rely on a valid database link being opened at one point.
    $dbh = new data_abstraction;
    $link = mysql_connect($dbh->db_host, $dbh->db_user, $dbh->db_pass);

    //If magic_quotes_gpc is enabled in the server, we have to "clean" the POST data so
    //we always make use of 'virgin' input. This way, all other methods can rely on the fact
    //that all input data will be unescaped when they receive it.
    //OPTIMIZATION TIP: IF YOU CAN SET MAGIC QUOTES OFF IN PHP.INI, do so and then
    //comment out the following if block. This will save a lot of processing.
    if(get_magic_quotes_gpc())
    {
        reverse_magic_quotes($_POST);
    }
    
    mb_internal_encoding(MULTI_BYTE_ENCODING);
}

function check_passport($required_passport)
{
    //Check if '$required_passport' is in the user's passport settings.
    //Not finding it here would mean an illegal access attempt.
    //Similarly, if we find that the module status of '$required_passport' is set to "Off",
    //it also constitutes an illegal access attempt, because modules that are turned off
    //are not displayed in the control center.

    //if $required_passport is not an array, turn it into an array
    if(!is_array($required_passport))
    {
        $required_passport = array($required_passport);
    }

    $lst_passports = '';
    foreach($required_passport as $passport)
    {
        make_list($lst_passports, $passport);
    }
        
    //Find the link IDs of the modules specified in '$required_passport'.
    $arrLinkID = array();
    $data_con = new data_abstraction;
    $data_con->set_fields('link_id, status');
    $data_con->set_table('user_links');
    $data_con->set_where("name IN ($lst_passports)");
    $data_con->exec_fetch();
    $arr_link_id = $data_con->link_id;
    $status = $data_con->status[0];
    $data_con->close_db();

    $lst_link_id = '';
    foreach($arr_link_id as $link_id)
    {
        make_list($lst_link_id, $link_id);
    }

    //Now that we have the link IDs, see if any of the link IDs are in the user's passport.
    //If none are (numrows==0), then we have an illegal access attempt.
    $data_con = new data_abstraction;
    $data_con->set_fields('username');
    $data_con->set_table('user_passport');
    $data_con->set_where("username='$_SESSION[user]' AND link_id IN ($lst_link_id)");
    if($result = $data_con->make_query())
    {
        $numrows = $data_con->num_rows;
    }
    else die("Error checking passport for validity: " . $data_con->error);
    $data_con->close_db();

    if(($numrows==0) || ($status!='On')) //If this evaluates to TRUE, we have an illegal access attempt.
    {
        log_action("HACK ATTEMPT TYPE I - Tried to access '$_SERVER[PHP_SELF]' without sufficient privileges.", $_SERVER['PHP_SELF']);

        //Get the security level. Security level setting determines what to do in a detected illegal access attept.
        $data_con = new data_abstraction;
        $data_con->set_fields('value');
        $data_con->set_table('system_settings');
        $data_con->set_where("setting='Security Level'");
        if($result = $data_con->make_query())
        {
            $data = $result->fetch_assoc();
            $security_level = $data['value'];
        }
        else die("Error getting the security level!" . $data_con->error);
        $data_con->close_db();

        if($security_level=="Red Alert")
        {
            //Just redirect back to home page.
            header("location: " . HOME_PAGE);
            exit();
        }
        elseif($security_level=="Moderate")
        {
            //Just redirect back to home page.
            header("location: " . HOME_PAGE);
            exit();
        }
    }
}

function check_link($Link, $user='')
{
    if($user=='') $user = $_SESSION['user'];
    $in_passport=FALSE;

    //First, find the link ID of the module name specified in '$required_passport'.
    $data_con = new data_abstraction;
    $data_con->set_fields('link_id, status');
    $data_con->set_table('user_links');
    $data_con->set_where("Name='$Link'");
    if($result = $data_con->make_query())
    {
        $data = $result->fetch_assoc();
        if(is_array($data)) extract($data);
        $result->close();
    }
    else die("The query failed while attempting to check the passport: " . $data_con->error);
    $data_con->close_db();

    if($status=='On')
    {
        $data_con = new data_abstraction;
        $data_con->set_fields('link_id');
        $data_con->set_table('user_passport');
        $data_con->set_where("username='$user' AND link_id='$link_id'");
        if($data_con->make_query())
        {
            $numrows = $data_con->num_rows;
        }
        else die("Failed checking $link_id in passport of $user" . $data_con->error);
        if ($numrows==1) $in_passport=TRUE;
    }
    return $in_passport;
}

function log_action($action, $module)
{
    $username = $_SESSION['user'];
    $date = date("m-d-Y");
    $real_time = date("G:i:s");
    $new_date= explode("-", $date);
    $new_time= explode(":", $real_time);

    $timestamp = mktime($new_time[0],$new_time[1],$new_time[2],$new_date[0],$new_date[1],$new_date[2]);
    $date_time = date("l, F d, Y -- h:i:s a");
    $ip_address = get_ip();

/*
//Uncomment this section if you also want file-based logging
//You need to uncomment and set the LOG_FILE setting in GlobalConfig.php as well
$LogContent = <<<EOD
$ip_address -- $username -- $date_time -- $action -- $module;

EOD;

    $newfile=fopen(LOG_FILE,"ab");
    fwrite($newfile, $LogContent);
*/
    $action = addslashes($action);

    $data_con = new data_abstraction;
    $data_con->set_query_type('INSERT');
    $data_con->set_table('system_log');
    $data_con->set_fields('ip_address, user, datetime, action, module');
    $data_con->set_values("'$ip_address', '$username', '$timestamp', '$action', '$module'");
    $data_con->make_query(TRUE,FALSE);
}

function get_ip()
{
    $ip_address = '';
    if(isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif(isset($_SERVER['HTTP_X_FORWARDED']))
    {
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    }
    elseif(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
    {
        $ip_address = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
    {
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    elseif(isset($_SERVER['HTTP_FORWARDED']))
    {
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    }

    if($ip_address == '')
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
        $ip_address .= ' : ' . $_SERVER['REMOTE_ADDR'];
    }

    return $ip_address;
}


function make_list(&$list_var, $new_entry, $delimiter=',', $quotes=TRUE, $quote_string_start="'", $quote_string_end="")
{
    if($list_var != '') $list_var .= $delimiter;
    
    if($quotes==TRUE)
    {
        if($quote_string_end=='') $quote_string_end = $quote_string_start;
        $list_var .= $quote_string_start . $new_entry . $quote_string_end;
    }
    else $list_var .= "$new_entry";
}

function make_list_array(&$array, $new_entry)
{
    if(!is_array($array)) $array = array();
    if(!in_array($new_entry, $array)) $array[] = $new_entry;
}

function back_quote_smart($var)
{
    if(substr($var,0,1) != '`')
    {
        $var = '`' . $var . '`';
    }
    
    return $var;
}

function cobalt_htmlentities($unclean, $flag=ENT_COMPAT) 
{
    $clean = htmlentities($unclean, $flag, MULTI_BYTE_ENCODING);
    return $clean;
}

function cobalt_password_hash($mode, $password, $username, &$salt='', &$iteration='', &$method='')
{
    $min_iteration = 150000; //FIXME: make this a GlobalConfig option, or system_settings entry
    $max_iteration = 160000; //FIXME: make this a GlobalConfig option, or system_settings entry
    $min_blowfish = 13; //FIXME: make this a GlobalConfig option, or system_settings entry
    $max_blowfish = 13; //FIXME: make this a GlobalConfig option, or system_settings entry

    if($mode == 'RECREATE')
    {
        $dbh = new data_abstraction;
        $mysqli = $dbh->connect_db();
        $clean_username = $mysqli->real_escape_string($username);

        $dbh->set_table('user');
        $dbh->set_fields('`salt`,`iteration`,`method`');
        $dbh->set_where("`username`='$clean_username'");
        $dbh->exec_fetch('single');
        if($dbh->num_rows == 1)
        {
            $salt = $dbh->salt;
            $iteration = $dbh->iteration;
            $method = $dbh->method;
        }
        else
        {
            //No result found.
            //We should produce fake data, so that the hashing process still takes place,
            //mitigating probing / timing attacks
            $salt = generate_token();
            $iteration = mt_rand($min_iteration, $max_iteration);
            $method = 'SHA1'; 
        }
        $dbh->close_db();
    }
    elseif($mode == 'NEW')
    {
        $salt = generate_token();

        $methods_available = hash_algos();
        if (CRYPT_BLOWFISH == 1)
        {
            $iteration = mt_rand($min_blowfish, $max_blowfish);
            $method = "BLOWFISH";
        }
        elseif(in_array('sha512', $methods_available))
        {
            $iteration = mt_rand($min_iteration, $max_iteration);
            $method = 'SHA512';
        }
        elseif(in_array('sha256', $methods_available)) 
        {
            $iteration = mt_rand($min_iteration, $max_iteration);
            $method = 'SHA256';
        }
        else
        {
            $iteration = mt_rand($min_iteration, $max_iteration);
            $method = 'SHA1'; 
        }
    }
    else
    {
        die("Cobalt Password Hash Error: Invalid mode specified.");
    }

    //$t_start = microtime(true);
    if($method == 'BLOWFISH')
    {
        $digest = cobalt_password_hash_bcrypt($password, $salt, $iteration);
    }
    elseif($method == 'SHA1' || $method == 'SHA256' || $method == 'SHA512')
    {
        $digest = cobalt_password_hash_sha($password, $salt, $iteration, $method);
    }
    else
    {
        //Error: invalid method supplied
        error_handler("Cobalt encountered an error during password processng.","Cobalt Password Hash Error: Invalid hash method specified.");
    }
    //$t_process = microtime(true) - $t_start;
    //echo "Hash time ($method): $t_process seconds <br />";

    return $digest;
}

function cobalt_password_hash_sha($password, $salt, $iteration, $method)
{
    $method = strtolower($method);
    $digest = hash($method,$password . $salt);
    for($a=0; $a<$iteration; $a++)
    {
        $digest = hash($method ,$digest . $password . $salt);
    }

    return $digest;
}

function cobalt_password_hash_bcrypt($password, $salt, $iteration)
{
    $blowfish_salt_start = '$2a$';
    $blowfish_cost = $iteration;
    $blowfish_key = '$' . $salt . '$';

    $blowfish_salt = $blowfish_salt_start . $blowfish_cost . $blowfish_key;

    $digest = crypt($password . $salt, $blowfish_salt);
    return $digest;
}

function data_dump($data)
{
    if(is_array($data))
    {
        ksort($data);
        echo '******ARRAY DATA****** ';
        foreach($data as $index=>$value)
        {
            if(is_array($value))
            {
                foreach($value as $v_index=>$v_value)
                {
                    if(is_array($v_value))
                    {
                        echo '<br />' . htmlentities($index, ENT_QUOTES) . ' => ['
                                      . htmlentities($v_index, ENT_QUOTES) . ' => array]';
                        
                    }
                    else
                    {
                        echo '<br />' . htmlentities($index, ENT_QUOTES) . ' => ['
                                      . htmlentities($v_index, ENT_QUOTES) . ' => '
                                      . htmlentities($v_value, ENT_QUOTES) . ']';
                    }
                }
            }
            else
            {
                echo '<br />' . htmlentities($index, ENT_QUOTES) . ' => ' . htmlentities($value, ENT_QUOTES);
            }
        }
        echo '<br />******END OF DATA DUMP******<br />';
    }
    else
    {
        echo 'Single data: '. htmlentities($data, ENT_QUOTES) . '<br />';
    }
}

function error_handler($generic_message, $debugging_message)
{
    $error_message = $generic_message;
    if(DEBUG_MODE)
    {
        $error_message .= ' ' . $debugging_message;
    }
    die('An error occured: ' . $error_message);
}

function generate_token($length=64, &$crypto_secure=FALSE)
{
    $token='';
    if(function_exists('openssl_random_pseudo_bytes'))
    {
        //Cryptographically secure random number generation
        $token = sha1(bin2hex(openssl_random_pseudo_bytes($length, $crypto_secure)));
    }
    else
    {
        //Fallback method. Thank you, PHP, for not providing a CSPRNG in the core.
        $token = sha1(uniqid(mt_rand(), true));
    }
    
    return $token;
}

function quote_smart($unclean)
{
    if(get_magic_quotes_gpc()) $unclean = stripslashes($unclean);
    $clean = mysql_real_escape_string($unclean);
    return $clean;
}

function quote_smart_recursive(&$var)
{
    if(is_array($var))
    {
        foreach($var as $key=>$new_var)
        {
            quote_smart_recursive($new_var);
        }
    }
    else
    {
        $var = mysql_real_escape_string($var);
    }
}

function reverse_magic_quotes(&$var)
{
    if(is_array($var))
    {
        foreach($var as $key=>$new_var)
        {
            reverse_magic_quotes($var[$key]);
        }
    }
    else
    {
        $var = stripslashes($var);
    }
}

function strip_back_quote_smart($var)
{
    if(substr($var,0,1) == '`')
    {
        $var = substr($var, 1, -1);
    }
    
    return $var;
}

function set_datecontrol_values(&$year, &$month, &$day, $offset=0, $offset_type='m')
{
    $adjusted_date = date("m-d-Y", mktime(0, 0, 0, 
                                          $month + $m_offset, 
                                          $day + $d_offset, 
                                          $year + $y_offset));
   
    $data = explode('-', $adjusted_date);
    $month = $data[0];
    $day = $data[1];
    $year = $data[2];
}

function xsrf_guard()
{
    $xsrf_passed = FALSE;
    $session_token_exists = FALSE;
    $form_key_validated = FALSE;

    if(isset($_SESSION['cobalt_form_keys'][$_SERVER['PHP_SELF']]))
    {
        $session_token_exists = TRUE;
    }

    if($_POST['form_key'] === $_SESSION['cobalt_form_keys'][$_SERVER['PHP_SELF']])
    {
        $form_key_validated = TRUE;
    }
    
    if($session_token_exists && $form_key_validated)
    {
        $xsrf_passed = TRUE;
    }
    
    return $xsrf_passed;
}
