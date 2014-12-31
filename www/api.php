<?php 
  set_include_path(get_include_path() . PATH_SEPARATOR . dirname(getcwd()) .DIRECTORY_SEPARATOR .'lib');
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
    $api_mode = APIRouter::determineMode($_SERVER['REQUEST_METHOD']);
    
    if(!$api_object->isAuthorized($api_mode)) throw new Exception('Not Authorized');
    
    switch($api_mode):
      case 'r':
        $response = $api_object->toArray();
        break;
      case 'w':
        $data = json_decode(file_get_contents("php://input"),true);
        $api_object->updateFromArray($data);
        $response = $api_object->toArray();
        break;
      case 'd':
        $api_object->delete();
        $response['_api']['status'] = 'ok';
      
    endswitch;
  } catch (Exception $e) {
    $error[] = $e->getMessage();
    $error[] = $e->getTraceAsString();
  }
  
  /* Add some debugging info to the API response */
  //$response['_api']['mode'] = APIRouter::determineMode($_SERVER['REQUEST_METHOD']);
  //$response['_api']['debug'] = $api_object->getDebug();
  
  
  if(isset($error)) $response['error'] = $error;
  echo json_encode($response);

?>