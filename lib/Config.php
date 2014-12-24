<?php

    class Config {
        
        protected $_mongo_db;
        
        public function __get($attr) {
            switch($attr):
                case 'mongo_db':
                    if(!isset($this->_mongo_db)):
                        $m = new MongoClient(str_replace('tcp://','mongodb://',getenv('MONGODB_PORT')));
                        $this->_mongo_db = $m->local;
                    endif;
                    
                    return $this->_mongo_db;
                break;
            endswitch;
            return null;
        }
    
    }
?>