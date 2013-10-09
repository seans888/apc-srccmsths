<?php
/*
 * SCV2_Core.php
 * FRIDAY, November 24, 2006 
 * SCV2 Core file. Loads config & library files and initializes the session.
 * JV Roig
 */
ini_set('include_path', '.');
function init_SCV2()
{
    //Start the performance timer
    $start = microtime(true);
    define('PROCESS_START_TIME', $start);

    //Load the global config file and library files.
    require_once 'GlobalConfig.php';
    require_once 'SCV2_LibDataAccess.php';
    require_once 'SCV2_LibHTML.php';
    require_once 'SCV2_LibPHP.php';
    require_once 'SCV2_LibSecurity.php';

    //Start session.
    session_start();
}

function obliterate_dir($dir)
{
    if(is_dir($dir))
    {
        if($dh = opendir($dir))
        {
            while (($file = readdir($dh)) !== false)
            {
                if($file != '.' && $file != '..')
                {
                    if(is_dir($dir . '/' . $file))
                    {
                        obliterate_dir($dir . '/' . $file);
                    }
                    else 
                    {
                        unlink($dir . '/' . $file);
                    }
                }
            }
            closedir($dh);
        }
        rmdir($dir);
    }
} 
?>
