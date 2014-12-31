<?php

  set_include_path(get_include_path() . PATH_SEPARATOR . dirname(getcwd()) .DIRECTORY_SEPARATOR .'lib' );
	require_once('Config.php');
	require_once('User.php');
	require_once('Utilities.php');
  session_start();
  
  $config = new Config();
  
  if(!isset($_SESSION['user'])):
      // Not Authenticated
      
      if(isset($_POST['password']) && isset($_POST['username'])):
          if ( User::Authenticate($_POST['username'],$_POST['password']) ):
              $_SESSION['username'] = $_POST['username'];
              $_SESSION['user'] = new User($_SESSION['username']);
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
          include('..' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'login.php');
      endif;
  else:
  	if(isset($_GET['signout'])) { session_destroy(); Utilities::reloadPage(false);}  
    include('..' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'sample.php');
  endif;
  
?>