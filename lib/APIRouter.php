<?php 
  
  class APIRouter {

    public static function fetchTargetObject($request) {
      $class = 'API_' . ucwords($request[0]);
      @$id = $request[1];
      if (file_exists('../lib/api/'.$class.'.php')):
        require_once('../lib/api/'.$class.'.php');
        return new $class($id);
      else:
        throw new Exception('No route exists to requested object.');
      endif;
      
    }
    
    public static function determineMode($http_method) {
      switch($http_method):
        case 'GET': 
          return 'r';
        case 'PUT':
        case 'POST':
        case 'DELETE':
          return 'rw';
        default:
          throw new Exception('Invalid http method');
      endswitch;
    }
    
  }
?>