<?php 
  require_once('APIObject.php');
  require_once('User.php');
  
  class API_User extends APIObject {
  
    public function __construct($id) {
      $this->object = new User($id);
      $this->accessible_properties = array(
          'password' => 'w',
          'name'     => 'rw'
      );
    }
    
    public function isAuthorized($mode) {
      if ( $this->object->getID() == $_SESSION['user']->getID() ) {
        return true;
      } else {
        return false;
      }
    }
    
  }
?>