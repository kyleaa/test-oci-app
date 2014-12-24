<?php
    
    class MongoObjectBase {
    
        protected $mongo_collection, $mongo_data, $mongo_data_orig;
        private $_collection;
        
        protected function LoadByID($id) {
            global $config;
            $this->_collection = $config->mongo_db->{$this->mongo_collection};
            $this->mongo_data = $this->_collection->findOne(array('_id'=> $id));
            $this->mongo_data_orig = $this->mongo_data;
        }
        
        public function update() {
            $this->_collection->update(array('_id' => $this->mongo_data['_id']), $this->mongo_data);
            $this->mongo_data_orig = $this->mongo_data;
        }
    }
    
?>