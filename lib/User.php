<?php

    require_once('pbkdf2.php');
    require_once('MongoObjectBase.php');
        
    class User extends MongoObjectBase {
        
        public function __construct($username) {
            $this->mongo_collection = 'users';
            $this->loadById($username);
        }
            
        public function setPassword($input) {
            $new_password = create_hash($input);
            $this->mongo_data['password'] = $new_password;
        }    
        public function getPassword() { return $this->mongo_data['password']; }
        
        public static function Authenticate($username, $password) {
            $user_target = new User($username);
            return validate_password($password, $user_target->getPassword());
        }

    }
    
?>