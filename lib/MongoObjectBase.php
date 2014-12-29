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
        
        public function updateSubset($js) {
        	$this->mongo_data_orig = array_merge($this->mongo_data_orig, $js);
            $this->_collection->update(array('_id' => $this->mongo_data['_id']), $this->mongo_data_orig);
            $this->mongo_data = array_merge($this->mongo_data, $js);
        }
        
        public function getID() {
          return $this->mongo_data['_id'];
        }
    }
    
?>