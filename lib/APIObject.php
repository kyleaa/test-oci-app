<?php 
  
  class APIObject {
  
    protected $object;
    protected $accessible_properties;
  
    public function isAuthorized($mode) {
      return true;
    }
    
    public function toArray() {
      $return = array();
      foreach($this->accessible_properties as $prop => $mode):
        
        if($mode == 'r' || $mode == 'rw'):
          $getter = APIObject::getterName($prop);
          if(is_callable(array($this->object,$getter))):
            $return[$prop] = $this->object->$getter();
          else:
            throw new Exception('Invalid API definition - property ' . $prop . ' has no getter method.');
          endif;
        endif;
        
      endforeach;
      
      $return['_id'] = $this->object->getID();
      ksort($return);
      return $return;
    }
    
    private static function getterName($property) {
      return 'get' . ucwords($property);
    }
    
  }

?>