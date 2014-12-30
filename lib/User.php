<?php

    require_once('pbkdf2.php');
    require_once('MongoObjectBase.php');
        
    class User extends MongoObjectBase {
        
        public function __construct($username) {
            $this->mongo_collection = 'users';
            $this->loadById($username);
            if(!isset($this->mongo_data['servers'])) $this->mongo_data['servers'] = array();
        }
        
        public function getName() { return $this->mongo_data['name']; }
        public function setName($name) { $this->mongo_data['name'] = $name; }
            
        public function setPassword($input) {
            $new_password = create_hash($input);
            $this->mongo_data['password'] = $new_password;
        }    
        public function getPassword() { return $this->mongo_data['password']; }
        
        
        public function getServers() { return $this->mongo_data['servers']; }
        
        public function setServers($array) {
          /* Remove any existing servers that aren't in the array */
          foreach($this->mongo_data['servers'] as $alias=>$server):
            if(!isset($array[$alias])) $this->setServer($alias,array());
          endforeach;
          /* Update servers on the user */
          foreach($array as $alias=>$server):
            $this->setServer($alias, $server);
          endforeach;
        }
        
        public function setServer($key,$data) {
          if($data === null || count($data) == 0):
            unset($this->mongo_data['servers'][$key]);
          else:
            $this->ensureRequiredFields($data,array('connect','username','password','max_connections'));
            $data['password'] = $this->encrypt($data['password']);
          	$this->mongo_data['servers'][$key] = $data;
          endif;
        }
        
        public function getServer($key) {
        	return $this->mongo_data['servers'][$key];
        }
        
        public function encrypt($password) { 
        	$key = $_SESSION['encryption_key'];
        	$key_size = strlen($key);
        	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        	$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$password, MCRYPT_MODE_CBC, $iv);
        	$ciphertext = $iv . $ciphertext;
        	return base64_encode($ciphertext);
        }
        
        public function decrypt($ciphertext) {
        	$key = $_SESSION['encryption_key'];
        	$ciphertext_dec = base64_decode($ciphertext);
        	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        	$iv_dec = substr($ciphertext_dec, 0, $iv_size);
        	$ciphertext_dec = substr($ciphertext_dec, $iv_size);
        	return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
        }
        
		public function getEncryptionKey($password) {
			if(!isset($this->mongo_data['salt'])):
				$temp = array();
				$temp['salt'] = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
				$this->updateSubset($temp);
			endif;
			return base64_encode(pbkdf2("sha256", $password, $this->mongo_data['salt'], 1000, 24, true));
		}
		
		public static function Authenticate($username, $password) {
		    $user_target = new User($username);
		    return validate_password($password, $user_target->getPassword());
		}
		
    }
    
?>