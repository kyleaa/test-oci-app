<?php
    session_start();
    
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(getcwd()) .'/lib/');
    
    require_once('Config.php');
    require_once('User.php');
    $config = new Config();
    
    
    if(!isset($_SESSION['user'])):
        // Not Authenticated
        
        if(isset($_POST['password']) && isset($_POST['username'])):
            if ( User::Authenticate($_POST['username'],$_POST['password']) ):
                $_SESSION['user'] = new User($_POST['username']);
            else:
                echo 'failure';
            endif;
        else:
            include('../pages/login.php');
        endif;
    else:
        include('../pages/sample.php');
    endif;
    
?>