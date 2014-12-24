<?php

	class Query {
	
		protected $sql, $mode, $log, $session_suspend;
		protected $affected_rows, $insert_id;
		protected $resultset;
		private $executed;
	
		function __construct($sql,$mode = 'mysql',$bind = null){
			$this->sql = $sql;
			$this->mode = $mode;
			$this->log = true;
			$this->session_suspend = false;
			$this->executed = false;
			$this->affected_rows = 0;
			if($bind != null && is_array($bind)) $this->bindAssoc($bind);
		}
		
		private function disableLogging() {
			$this->log = false;
		}
		
		public function suspendSession() {
			$this->session_suspend = true;
		}

		protected function fetchRowOCI() {
			$errors = array();
			if(!$this->executed) $this->executeQuery();
			return oci_fetch_assoc($this->resultset);
		}
		
		protected function fetchRowMySQL() {
			if(!isset($this->resultset)) $this->executeQuery();
			return mysql_fetch_assoc($this->resultset);
		}
		
		public function fetchRow() {
			switch($this->mode):
				case 'oracle': return $this->fetchRowOCI(); break;
				case 'mysql': return $this->fetchRowMySQL(); break;
				default: throw new Exception("Invalid query mode definition");
			endswitch;
		}
		
		public function fetchAll() {
			$rs = array();
			while($row = $this->fetchRow()) {$rs[] = $row;} 
			return $rs;
		}
		
		public function getResultset() {
			return $this->resultset;
		}
		
		protected function parseOCI() {
			global $oracle_db;
			
			if($oracle_db == null) {
				$path = getcwd();
				chdir(dirname(__FILE__));;
				include_once("../lib/remote/oci_connect.php");
				chdir($path);
			}
			
			if(!$oracle_db) throw new InfosourceUnavailableException("This query requires UB InfoSource, which is currently unavailable.");
			$this->resultset = oci_parse($oracle_db,$this->sql);
			if(oci_error($this->resultset)){ 
				foreach(oci_error($this->resultset) as $key => $therror) if($key == 'message') $errors[] = "$therror";
				throw new Exception("Oracle error in query parse: " . implode(", ",$errors));
			}
		}
		
		protected function executeOCI() {
			if(!isset($this->resultset)) $this->parseOCI();
			if(!@oci_execute($this->resultset)){ 
				foreach(oci_error($this->resultset) as $key => $therror) if($key == 'message') $errors[] = "$therror";
				throw new Exception("Oracle error in query execution: " . implode(", ",$errors));
			}
			
			$this->executed = true;
		}
		
		public function reexecuteOCI() {
			if(!@oci_execute($this->resultset)){ 
				foreach(oci_error($this->resultset) as $key => $therror) if($key == 'message') $errors[] = "$therror";
				throw new Exception("Oracle error in query execution: " . implode(", ",$errors));
			}
			
			$this->executed = true;
		}
		
		protected function executeMySQL() {
			$this->resultset = mysql_query($this->sql);
			if(mysql_error()){ 
				throw new Exception("MySQL error in query execution: " . mysql_error());
			}
			$this->affected_rows = mysql_affected_rows();
			$this->insert_id = mysql_insert_id();
		}
		
		public function executeQuery() {
			$start = microtime(true);
			if($this->session_suspend) session_write_close();
			try {
				switch($this->mode):
					case 'oracle': $ret = $this->executeOCI(); break;
					case 'mysql': $ret = $this->executeMySQL(); break;
					default: throw new Exception("Invalid query mode definition");
				endswitch;
			} catch (Exception $e) {
				if($this->session_suspend) session_start();
				throw $e;
			}
			if($this->session_suspend) session_start();
			return $ret;
			
		}
		
		public function getAffectedRows() {return $this->affected_rows; }
		public function getInsertID() {return $this->insert_id; }
		
		public function getSQL() {return $this->sql; }
		public function setSQL($newsql) { $this->sql = $newsql; unset($this->resultset); }
		
		public function bindAssoc(&$array) {
			foreach(array_keys($array) as $key) $this->bindVar($key,$array[$key]);
		}
		
		public function bindVar($varname,&$value) { 
			$varname = strtolower($varname);
			switch($this->mode):
				case 'oracle': return $this->bindVarOCI($varname,$value); break;
				case 'mysql': return $this->bindVarMySQL($varname,$value); break;
			endswitch;
			
			$this->executed = false;
		}
		
		private function bindVarOCI($varname,&$value) { if(!isset($this->resultset)) $this->parseOCI(); return oci_bind_by_name($this->resultset,$varname,$value); }
		private function bindVarMySQL($varname,$value) { 
			if(is_null($value)) $value = 'null';
			else $value = "'".mysql_real_escape_string($value)."'";
			
			$ct = 0;
			$this->sql = str_replace(':'.$varname,$value,$this->sql,$ct);
			return $ct > 0;
		}
		
		
		public static function execute($sql,$mode = 'mysql',$bind=null) {
			$query = new Query($sql,$mode,$bind);
			$query->executeQuery();
			return $query->getAffectedRows();
		}

		public static function executeSilent($sql,$mode = 'mysql',$bind=null) {
			$query = new Query($sql,$mode);
			$query->disableLogging();
			$query->bindAssoc($bind);
			$query->executeQuery();
			return $query->getAffectedRows();
		}
		
		public static function executeFetchFirst($sql,$mode = 'mysql',$bind=null) {
			$query = new Query($sql,$mode,$bind);
			$query->executeQuery();
			return $query->fetchRow();
		}
		public static function executeSingleton($sql,$mode = 'mysql',$bind=null) {
			$query = new Query($sql,$mode,$bind);
			$query->executeQuery();
			$row = $query->fetchRow();
			if(!is_array($row)) throw new Exception('Singleton Query - no value found');
			if(count($row)!=1) throw new Exception('Singleton Query - multiple columns selected');
			return array_pop($row);
		}
		
		public static function executeFetchAll($sql,$mode = 'mysql',$bind=null) {
			$query = new Query($sql,$mode,$bind);
			$query->executeQuery();
			return $query->fetchAll();
		}
	}
	
?>
