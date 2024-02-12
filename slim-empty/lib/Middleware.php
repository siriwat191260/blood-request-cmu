<?php
class Middleware{
	var $log;
	var $log_id = 0;
	var $log_m = 0;
	var $token = ['valid'=>true,'token_data'=>[]];
	var $cf;
	var $cf_m;
	var $jwt_config;
	var $data_check = '';
	var $response;
	var $rs;
	var $total_time = null;
	var $controller_name = 'default';
	var $noti = false;
	var $m_track = false;

	function __construct($cf_controller){

		if(isset($cf_controller['controller'])){
			$this->controller_name = $cf_controller['controller'];
		}
		$config = new Config($this->controller_name);
		$this->cf = $config->cf;

		

		if(!isset($cf_controller['app'])){
			$cf_controller['app'] = $this->cf['jwt_default'];
		}
		
		
		//comment this bc error
		/* $this->jwt_config = $this->cf['jwt'][$cf_controller['app']]; */
		
	}
	public function preCall($p){
		
		// $cf_m = $this->cf['mt'][$this->controller_name]['services'][$p['method']];
		
		//comment this bc error /* $this->cf_m = $this->cf['mt']['services'][$p['method']]; */
		$this->log_m = $this->cf_m['log'] ?? 0;
		$auth=new Auth($this->jwt_config, $this->cf);
		if($this->cf['use_log'] && ($this->log_m > 0)){
			
			$uri = str_replace($this->cf['base_url'],'',$_SERVER['REQUEST_URI']);
			

			$this->log = new Log();
			$header = json_encode($auth->getRequestHeader());
			$this->log_id = $this->log->saveLog($uri, $p, $header);

			if(isset($this->cf_m['noti'])){
				$this->noti = $this->cf_m['noti'];
			}
			
		}
		//comment this bc error
		/* if($this->cf_m['auth'] == true || $this->cf_m['auth'] > 0){
			$auth->gr_auth = 2;
			if($this->cf_m['auth'] > 0){
				$auth->gr_auth = $this->cf_m['auth'];
			}
			$checkAuthManual = $this->cf_m['checkAuthManual'] ?? false;
			if($checkAuthManual){
				$this->token = $auth->checkAuthManual($p['t']);
				
				if(!$this->token['valid']){
					$app = $auth->getAppFromToken($p['t']);
					if(in_array($app, $this->jwt_config['acceptToken'])){
						$this->jwt_config = $this->cf['jwt'][$app];
						$auth=new Auth($this->jwt_config, $this->controller_name);
						$this->token = $auth->checkAuthManual($p['t']);
					}
				}
			}else{
				if(isset($this->cf_m['buckets'])){
					$this->data_check = [];
					$this->data_check['check_tickets'] = $this->cf_m['buckets'];
					$this->data_check[$this->cf_m['buckets']['type']] = $p[$this->cf_m['buckets']['type']];
					
				}
				$this->token = $auth->checkMethodAuth($p['method'], $this->data_check, 1);
			}
		}else{
			$jwt = $auth->getJWTFromHeader();
			if($jwt != ''){
				$payload = $auth->getPayloadData($jwt);
				if($payload != ''){
					$this->token['token_data']=$auth->getTokenData(json_decode($payload));
				}
			}
			
		} */
		if($this->cf['use_log'] && isset($this->cf_m['track'])){
			if($this->cf_m['track']){
				$this->m_track = true;
			}
		}
		
	}
	public function callMethod($p){
		$pre_check_rs = ['code'=> 200, 'data'=> ''];
		if(!$this->token['valid']){
			if(isset($this->token['token_data'])){
				$this->rs = $this->token['token_data'];
			}
			return $this->afterCall(401);
		}

		if(count($this->token['token_data'])){
			if($this->controller_name != 'robot'){
				$p['token_data'] = $this->token['token_data'];
			}
		}
		if(isset($this->cf_m['preCheck'])){
			if($this->cf_m['preCheck']){
				$pre_check_rs = $this->preCallCheck($p);
			}
		}
		if($pre_check_rs['code']!=200){ 
			$this->rs = null;
			return $this->afterCall($pre_check_rs['code']);
		}else if(isset($pre_check_rs['data'])){
			if(!empty($pre_check_rs['data'])){
				$p['not_in'] = implode(",",$pre_check_rs['data']);
			}
		}
		//

		include $_SERVER['DOCUMENT_ROOT']."/services/".$this->controller_name."/".$p['method'].".php";
		// include $p['method'].".php";
		$p['cf'] = $this->cf;
		$call_func = call_user_func($p['method'], $p); 
		$http_code = $call_func['c'] ?? 200 ;
		$http_code = $http_code <= 200 ? 200 : $http_code ; 

		if($this->m_track){
			$gf = new GenFunc();
			$p['f'] = null;
			if(isset($_GET['f'])){
				$p['f'] =$_GET['f'];
			}
			$track = $gf->saveTrack($p);
		}
		if(isset($call_func['call_info'])){
			$this->rs = $call_func['res'];
			$http_code = $call_func['call_info']['http_code'];
			$this->total_time = $call_func['call_info']['total_time'];
		}else{
			$this->rs = $call_func;
		}
		
		if($this->noti){
			$noti = $this->noti($http_code, $p['method']);
		}
		
		if(isset($p['numeric_check'])){
			return $this->afterCall($http_code, true);
		}
		return $this->afterCall($http_code);
	}
	
	public function afterCall($code, $numeric = false){
		
		if($this->log_id > 0 && ($this->log_m > 1)){
			$uid = 0;
			$app = '';
			$uid_key = '';
			if(isset($this->cf['uid_key'])){
				$uid_key = $this->cf['uid_key'];
			}
			
			if(isset($this->token['token_data']['app'])){
				$app = $this->token['token_data']['app'];
				if(isset($this->cf['apps'][$app]['uid_key'])){
					$uid_key = $this->cf['apps'][$app]['uid_key'];
				}
			}

			if(isset($this->token['token_data'][$uid_key])){
				$uid = $this->token['token_data'][$uid_key];
				if($uid_key == 'pid'){
					if(isset($this->token['token_data']['main_pid'])){
						$main_pid = $this->token['token_data']['main_pid'];
						if($main_pid!=''){
							$uid = $main_pid;
						}
					}
				}
			}

			$log_detail['total_time'] = $this->total_time;
			$log_detail['app'] = $app;
			$log_detail['uid'] = $uid;

			if($this->response != ''){
				$log_detail['http_code'] = $this->response->withStatus($code);
				$this->log->updateLog($this->log_id, json_encode($this->rs), $log_detail);
			}else{
				$log_detail['http_code'] = $code;
				$this->log->updateLog($this->log_id, json_encode($this->rs), $log_detail);
				return $this->rs;
			}
		}
		
		if($numeric){
			if($this->response != ''){
				return $this->response->withJSON($this->rs, $code, JSON_NUMERIC_CHECK);
			}else{
				return $this->rs;
			}

		}else{
			if($this->response != ''){
				return $this->response->withStatus($code)->withJSON($this->rs);
			}else{
				return $this->rs;
			}
		}
	}
	public function noti($http_code, $method = ''){
		$alert = false;
		$alert_text = '';
		if($http_code != 200){
			$alert = true;
			if(isset($this->rs['e']))
				$alert_text = $this->rs['e'];
			if($alert_text == ''){
				$alert_text = $method.' return response header => '.$http_code.' [log id:'.$this->log_id.'](is)';
			}
		}
		if(isset($this->rs['c'])){
			if($this->rs['c'] > 0){
				$alert = true;
				$alert_text = $this->rs['e'];
			}
		}elseif(isset($this->rs['_c'])){
			if($this->rs['_c'] > 0){
				$alert = true;
				$alert_text = $this->rs['_e'];
			}
		}elseif(isset($this->rs['code'])){
			if($this->rs['code'] > 0){
				$alert = true;
				$alert_text = $this->rs['msg'];
			}
		}elseif(isset($this->rs['_code'])){
			if($this->rs['_code'] > 0){
				$alert = true;
				$alert_text = $this->rs['_msg'];
			}
		}
		if($alert){
			$text = base64_encode($alert_text.' [log id:'.$this->log_id.']');
			$noti = file_get_contents("https://isuandok.med.cmu.ac.th/iapi/line/services/line_notify.php?text=$text&type=64");
		}
		return $noti;
	}

	//modified for lock location 2023-04-05 by MIS68
	public function preCallCheck($p) {
		$gf = new GenFunc();
		$ret = array(
			"code"=> 200,
			"data"=> ""
		);
		// $cf_m = $this->cf['mt'][$this->controller_name]['services'][$p['method']];
		$cf_m = $this->cf['mt']['services'][$p['method']];

		//check precheck config
		$config_arr = isset($cf_m['preCheck'])?$cf_m['preCheck']:[];
		foreach($config_arr as $key=>$config_row) {
			switch($config_row) {
				case 'pin' :             //เช็ค pin
					$rs = $gf->checkPin($p);
					if(!$rs['result']){
						return ["code" => $rs['status_code'], "data" => null];
					}
					break;
				case 'lock_paper' :       //เช็ค lock paper
					$ret = ["code" => 200, "data" => $gf->checkLock($p)];
					break;
				case 'lock_pic_upload' :  //เช็ค lock pic upload
					$cfl = new Config();
					$ret = ["code" => 200, "data" => $gf->checkLock($p, $cfl->lock_pic_upload)];
					break;
			}
		}
		return $ret;
	}
	//end modified for lock location 2023-04-05 by MIS68
}
?>