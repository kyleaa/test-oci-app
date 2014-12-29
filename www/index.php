<?php

	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(getcwd()) .'/lib/');
	require_once('Config.php');
	require_once('User.php');
	require_once('Utilities.php');
  session_start();
  
  $config = new Config();
  
  if(!isset($_SESSION['user'])):
      // Not Authenticated
      
      if(isset($_POST['password']) && isset($_POST['username'])):
          if ( User::Authenticate($_POST['username'],$_POST['password']) ):
              $_SESSION['user'] = new User($_POST['username']);
              $_SESSION['encryption_key'] = $_SESSION['user']->getEncryptionKey($_POST['password']);
              Utilities::reloadPage();
          else:
              echo 'failure';
              $user = new User($_POST['username']);
              $user->setName('Kyle A Anderson');
              $user->setPassword($_POST['password']);
              $user->update();
          endif;
      else:
          include('../pages/login.php');
      endif;
  else:
  	if(isset($_GET['signout'])) { session_destroy(); Utilities::reloadPage(false);}
  
      include('../pages/sample.php');
  endif;
    
?>