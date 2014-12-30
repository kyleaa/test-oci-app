<?php 
  require_once('APIObject.php');
  require_once('User.php');
  
  class API_User extends APIObject {
  
    public function __construct($id) {
      if ($id == '$self'):
        $this->object = $_SESSION['user'];
      else:
        $this->object = new User($id);
      endif;
  
      $this->accessible_properties = array(
          'password' => 'w',
          'name'     => 'rw',
          'servers'  => 'rw'
      );
      
      parent::__construct();
    }
    
    public function isAuthorized($mode) {
      if ( $this->object->getID() == $_SESSION['user']->getID() && $mode != 'd') {
        return true;
      } else {
        return false;
      }
    }
    
  }
?>