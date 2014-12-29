<?php 
  set_include_path(get_include_path() . PATH_SEPARATOR . dirname(getcwd()) .'/lib/');
  require_once('Config.php');
  require_once('User.php');
  require_once('Utilities.php');
  session_start();
  
  $config = new Config();
  
  $request = explode('/',$_REQUEST['request']);
  require_once('APIRouter.php');

  $response = array();

  try {
    $api_object = APIRouter::fetchTargetObject($request);
    if(!$api_object->isAuthorized(APIRouter::determineMode($_SERVER['REQUEST_METHOD']))) throw new Exception('Not Authorized');
    $response = $api_object->toArray();
  } catch (Exception $e) {
    $error[] = $e->getMessage();
  }
  
  if(isset($error)) $response['error'] = $error;
  echo json_encode($response);

?>