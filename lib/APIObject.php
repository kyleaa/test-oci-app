<?php 
  
  class APIObject {
  
    protected $object;
    protected $accessible_properties;
    
    protected $debug;
  
    public function __construct() {
      $this->debug = array();
    }  
    
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
    
    public function updateFromArray($update) {
      $this->debug[] = "update from array called with :" .var_export($update,true);
      foreach($this->accessible_properties as $prop => $mode):
        if(($mode == 'w' || $mode == 'rw') && isset($update[$prop])):
          $setter = APIObject::setterName($prop);
          if(is_callable(array($this->object,$setter))):
             $this->object->$setter($update[$prop]);
             $this->debug[] = "called $setter with parameters " . var_export($update[$prop],true);
          else:
            throw new Exception('Invalid API definition - property ' . $prop . ' has no setter method.');
          endif;
        endif;
      endforeach;
      
      $this->object->update();
    }
    
    private static function getterName($property) {
      return 'get' . str_replace('_','',ucwords($property));
    }
    
    private static function setterName($property) {
      return 'set' . str_replace('_','',ucwords($property));
    }
    
    public function delete() {
      $this->object->delete();
    }
    
    public function getDebug() { return $this->debug; }
    
  }

?>