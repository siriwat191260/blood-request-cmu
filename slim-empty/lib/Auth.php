<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use \Firebase\JWT\JWT;
class Auth{
	var $jwt = null; // JWT config
	var $controller_name;
	var $controller_cf;
	var $method = '';
	var $jwt_token = '';
	var $e = null;
	var $gr_auth = 2; // 1 = group or role, 2 = group and role

	function __construct($jwt, $controller_cf){
		$this->jwt = $jwt;
		// $this->controller_name = $controller_name;
		// $config=new Config();
		$this->controller_cf = $controller_cf;
	}
	function grAuth($method, $tt){
		$g =  $this->checkGroupAuth($method, $tt);
		$r =  $this->checkRoleAuth($method, $tt);
		if($this->gr_auth == 2){
			return $g && $r;
		}else{
			return $g || $r;
		}
	}
	function checkMethodAuth($method, $data, $return_data = 0){
		/*
		$config=new Config();
		$cf=$config->cf;
		*/
		$this->method = $method;
		$auth = ['valid'=>false];
		$tt = ''; // tt is token type 
		if(isset($data['tt'])){
			$tt = $data['tt'];
		}
		if($this->controller_cf['mt']['services'][$method]["auth"]){
			if($data!=''){
				
				
				$auth=$this->checkAuth($tt, false); // first step check JWT
				if(!$auth['valid']){
					return $this->returnCheck($auth, $return_data);
				}

				if(isset($data['check_tickets'])){
					if(isset($data['check_tickets']['uid'])){
						$uid = $auth['token_data'][$data['check_tickets']['uid']];
						$type = $data['check_tickets']['type'];
						$uid_type = $data['check_tickets']['uid'];
						$code = $data[$type];
						$data_check = ["uid"=>$uid, "uid_type"=>$uid_type, "type"=>$type, "code"=>$code];
						$auth['valid'] = $this->checkTickets($data_check);
					}
				}else{
					$keys=array_keys($data);

					if(isset($auth['token_data'][$keys[0]])){
						/*
						if($data[$keys[0]]!=$auth['token_data'][$keys[0]] && !$this->checkGroupAuth($method, $tt) && !$this->checkRoleAuth($method, $tt)){
							$auth['valid'] = false;
						}
						*/
						if($data[$keys[0]]!=$auth['token_data'][$keys[0]] && !$this->grAuth($method, $tt)){
							$auth['valid'] = false;
						}
					}else{
						// $auth['valid'] = $this->checkGroupAuth($method, $tt) && $this->checkRoleAuth($method, $tt);
						$auth['valid'] = $this->grAuth($method, $tt);
					}
				}
			}else{
				
				$auth['valid'] = $this->checkGroupAuth($method, $tt) && $this->checkRoleAuth($method, $tt);
				if($auth['valid']){
					$payload = $this->getPayloadData($this->jwt_token);
					$auth['token_data'] = $this->getDataFromPayload($payload);
				}else{
					$auth['token_data'] = $this->e;
				}
			}
		}
		return $this->returnCheck($auth, $return_data);
	}
	function returnCheck($auth, $return_data){
		if($return_data == 1){
			return $auth;
		}
		return $auth['valid'];
	}
	function checkGroupAuth($method, $tt=''){
		/*
		$config=new Config();
		$cf=$config->cf;
		*/
		if($this->controller_cf['mt']['services'][$method]["auth"]){
			
			$auth=$this->checkAuth($tt, false);
			
			if($auth['valid']){
				
				if(isset($this->controller_cf['mt']['services'][$method]["groups"])){
					if(!in_array($auth['token_data']['group'], $this->controller_cf['mt']['services'][$method]["groups"])){
						// $pid = $auth['token_data']['pid'];
						
						if(isset($auth['token_data']['groups'])){
							$groups = $auth['token_data']['groups'];
							if($this->checkGroupIntersectAuth($method, $groups)){
								return true;
							}else{
								return false;
							}
						}else{
							return false;
						}
						
					}
				}else{
					return true;
				}
			}else{
				if(isset($auth['token_data'])){
					$this->e = $auth['token_data'];
				}
				return false;
			}
		}
		return true;
	}
	function checkRoleAuth($method, $tt=''){
		/*
		$config=new Config();
		$cf=$config->cf;
		*/
		if($this->controller_cf['mt']['services'][$method]["auth"]){
			$auth=$this->checkAuth($tt, false);
			if($auth['valid']){
				if(isset($this->controller_cf['mt']['services'][$method]["roles"])){
					if(!in_array($auth['token_data']['role'], $this->controller_cf['mt']['services'][$method]["roles"])){
						return false;
					}
				}else{
					return true;
				}
			}else{
				if($auth['token_data']){
					$this->e = $auth['token_data'];
				}
				return false;
			}
		}
		return true;
	}
	function checkTickets($data){
		$status = false;
		$uid = $data['uid'];
		$uid_type = $data['uid_type'];
		$type = $data['type'];
		$code = $data['code'];
		$rs = json_decode(file_get_contents("http://xxx"), true);
		if(isset($rs['s'])){
			$status = $rs['s'];
		}
		return $status;
	}
	function getUserGroups($pid){
		$groups = [];
		$user = json_decode(file_get_contents("http://xxx"), true);
		if(isset($user['groups'])){
			$groups = $user['groups'];
		}
		return $groups;
	}
	function checkGroupIntersectAuth($method, $groups){
		/*
		$config=new Config();
		$cf=$config->cf;
		*/
		if(count(array_intersect($groups, $this->controller_cf['mt']['services'][$method]["groups"])) == 0){
			return false;
		}
				
		return true;
	}
	function checkAuth($tt, $app = false){
		$cf = $this->controller_cf;
		$auth = ['valid'=>false];
		$header=$this->getRequestHeader();
		
		if(isset($header["Authorization"])){
			$headerAuth=$header["Authorization"];
		}else if(isset($header["authorization"])){
			$headerAuth=$header["authorization"];
		}else{
			return $auth;
		}
		
		$token = $this->getToken($headerAuth);
		$this->jwt_token = $token;
		$app_id = $this->getAppFromToken($this->jwt_token);
		if($app_id != ''){
			if($this->method != ''){
				$accept_app = $this->checkControllerAcceptApps($app_id);
				/*
				if(!$accept_app){
					$accept_app = $this->checkControllerAcceptAppsArrayKeys($app_id);
				}
				*/
				if($accept_app){
					$accept_app = $this->checkMethodAcceptApps($app_id);
				}
				
				if($accept_app){
					$tt = $app_id;
				}else{
					return $auth;
				}
			}else{
				return $auth;
			}
		}
		
		if($tt!='' && !$accept_app){
			if(in_array($tt,$this->jwt['acceptToken'])){
				$this->jwt = $cf['jwt'][$tt];
			}
		}

		if($tt !='' && $accept_app){
			$this->jwt = $cf['jwt'][$tt];
		}
		
		$key = base64_decode($this->jwt['secret']);
		$token_data=[];
		try{
			$decoded = JWT::decode($this->jwt_token, $key, array('HS256'));
			$valid=true;
			$token_data=$this->getTokenData($decoded);
			$auth['valid'] = true;
			$auth['token_data'] = $token_data;
		} catch (Exception $e) {
			$valid = false;
			$auth['token_data'] = ['e'=>$e->getMessage()];
		}
		
		return $auth;
	}
	/*
	function checkControllerAcceptApps($app_id){
		if(isset($this->controller_cf['mt']['services'][$this->method]["acceptApps"])){
			return $this->checkMethodAcceptApps($app_id);
		}
		if(isset($this->controller_cf['cf']["acceptApps"])){
			return in_array($app_id, $this->controller_cf['cf']["acceptApps"]);
		}
		return true;
	}
	*/
	function checkControllerAcceptApps($app_id){
		$accept_app = $this->controller_cf['mt']['cf']["acceptApps"] ?? [];
		if(count($accept_app) > 0){
			return in_array($app_id, $accept_app);
		}
		return true;
	}
	function checkControllerAcceptAppsArrayKeys($app_id){
		if(isset($this->controller_cf['cf']["acceptApps"])){
			return array_key_exists($app_id, $this->controller_cf['cf']["acceptApps"]);
		}
		return true;
	}
	/*
	function checkMethodAcceptApps($app_id){
		return in_array($app_id, $this->controller_cf['mt']['services'][$this->method]["acceptApps"]);
	}
	*/
	function checkMethodAcceptApps($app_id){
		$accept_app = $this->controller_cf['mt']['services'][$this->method]["acceptApps"] ?? [];
		if(count($accept_app) > 0){
			return in_array($app_id, $accept_app);
		}
		return true;
	}
	function checkAuthManual($token){
		$this->jwt_token = $token;
		$key = base64_decode($this->jwt['secret']);
		
		$valid=false;
		$token_data=[];
		try{
			$decoded = JWT::decode($token, $key, array('HS256'));
			$valid=true;
			$token_data=$this->getTokenData($decoded);
		} catch (Exception $e) {
			$token_data = ['e'=>$e->getMessage()];
			header('HTTP/1.0 401 Unauthorized');
		}
		return ["valid"=>$valid,"token_data"=>$token_data];
	}
	function setAuth($data){
		$key = base64_decode($this->jwt['secret']);
		$issuedAt   = time();
		$notBefore  = $issuedAt - 1000; // server time difference 1000 sec.
		$uuid		= uniqid($this->setPrefix($data));            
		$expire     = $notBefore + $this->jwt['tokenTime'];            
		$iss = $this->jwt['serverName'];
		
		$data = [
			'iat'  => $issuedAt,         
			'jti'  => $uuid,  
			'iss'  => $iss,       
			'nbf'  => $notBefore,        
			'exp'  => $expire,           
			'data' => $data
		];
		return JWT::encode($data, $key);
	}
	function setPrefix($data){
		$pf='';
		if(isset($this->jwt['uuidPrefix'])) {
			if($this->jwt['uuidPrefix']!=''){
				if(isset($data[$this->jwt['uuidPrefix']])){
					$pf = $data[$this->jwt['uuidPrefix']];
				}
			}
		}
		if(isset($this->jwt['prefixSeparator'])) {
			return $pf.$this->jwt['prefixSeparator'];
		}else {
			return $pf;
		}
	}
	function getDataFromPayload($payload){
		if($payload != ''){
			try{
				$token_data = json_decode($payload, true);
				if(isset($token_data['data'])){
					return $token_data['data'];
				}
			} catch (Exception $e) {
				return [];
			}
		}
		return [];
	}
	function getAppFromToken($token){
		$payloadData = $this->getPayloadData($token);
		$data = $this->getDataFromPayload($payloadData);
		if(isset($data['app'])){
			return $data['app'];
		}
		return '';
	}
	function getPayloadData($token){
		$payload = $this->extractPayload($token);
		if($payload != ''){
			return base64_decode($payload);
		}
		return '';
	}
	function extractPayload($token){
		$t = explode('.', $token);
		if(count($t)==3){
			return $t[1];
		}
		return '';
	}
	
	function getHeader($key){
		$header=$this->getRequestHeader();
		$value = '';
		if(isset($header[$key])){
			$value=$header[$key];
		}else if($key == "Authorization" && isset($header["authorization"])){
			$value=$header["authorization"];
		}
		return $value;
	}
	function getToken($auth, $str="Bearer"){
		return str_replace("$str ", "", $auth);
	}
	function getTokenData($d){
		return (array) $d->data;
	}

	function getRequestHeader(){
		if (!function_exists('apache_request_headers')) { // apache_request_headers replicement for nginx
			foreach($_SERVER as $key=>$value) { 
				if (substr($key,0,5)=="HTTP_") { 
					$key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5))))); 
					$out[$key]=$value; 
				}else{
					$out[$key]=$value; 
				}
			} 
			return $out; 	
		}else{
			return apache_request_headers();
		}
	}
	function getJWTFromHeader(){
		$jwt = '';
		$bearer = $this->getHeader('Authorization');
		if($bearer != ''){
			$jwt = $this->getToken($bearer);
		}
		return $jwt;
	}
}
?>