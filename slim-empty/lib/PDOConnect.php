<?php
class PDOConnect{
	var $host;
	var $username;
	var $password;
	var $db;
	var $conn;
	var $ret=array('c'=>0,'e'=>'','v'=>array());
	var $rsc;
	var $setName='utf8';

	function __construct($dbName){
		if($dbName=='dbname'){				
			$this->host='127.0.0.1';			
			$this->username='user';			
			$this->password='pwd#mis2023';
			$this->db='db';
		}
		// if($dbName=='db'){				
		// 	$this->host='127.0.0.1';			
		// 	$this->username='root';			
		// 	$this->password='1234';
		// 	$this->db='blood_request';
		// }
		if($dbName=='db'){				
			$this->host='127.0.0.1';			
			$this->username='root';			
			$this->password='00000000';
			$this->db='blood_request';
		}
	}
	
	
	public function Open(){
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		} catch (Exception $e) {
			return $e;
		}
		
		if($this->setName!=''){
			$this->conn->query("set names '".$this->setName."'");
		}
		return $this->conn;
	}	
	public function Close(){
		$this->conn=null;
	}
}
?>