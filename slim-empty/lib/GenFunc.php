<?php
class GenFunc{
	public function decode_unicode($str){
		return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
			return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
		}, $str);
	}
	public function checkHeadacheScore($score){
		$valid = false;
		if(preg_match("/^[1-3]{1}$/", $score)) {
			$valid = true;
		}
		return $valid;
	}
 	public function decode64_jsonen($data){
        $data=base64_decode($data);
        $data=json_decode($data, true);
        return $data;
    }
	public function checkValidLineUid($uid){
		$valid = false;
		if(strlen($uid)==33) {
			$valid = true;
		}
		return $valid;
	}
	public function checkValidMobile($phone){
		$valid = false;
		if(preg_match("/^[0]{1}[0-9]{9}$/", $phone)) {
		$valid = true;
		}
		return ["valid"=>$valid, "code"=>896];
	}
	public function checkValidHN($hn){
		$valid = false;
		if(preg_match("/^[0-9]{7}$/", $hn)) {
		$valid = true;
		}
		return $valid;
	}
	public function checkValidEmail($email){
		$valid = false;
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		$valid = true;
		}
		return ["valid"=>$valid, "code"=>898];
	}
	public function checkValidPid($id){
		if(strlen($id)==13){
			$count=13;
			$totalID=0;
			for($i=0;$i<12;$i++){
				$arrayID[$i]=substr($id,$i,1);
				$totalID=$totalID+($arrayID[$i]*$count);
				$count--;
			}
			$part=$totalID%11;
			
			if($part==0){
				$bits=1;
			}elseif($part==1){
				$bits=0;
			}else{
				$bits=11-$part;
			}
			if($bits==substr($id,12,1)){
				return true;	
			}else{
				return false;
			}		
		}else{
			return false;	
		}	
	}
	public function checkValidSex($sex){
		$valid = true;
		if(filter_var($sex, FILTER_VALIDATE_INT, array("options" => array("min_range"=>0, "max_range"=>2))) === false){
			$valid = false;
		}
		return ["valid"=>$valid, "code"=>897];
	}
	public function Icv($str){
		return iconv('tis-620','utf-8',$str);	
	}
	public function Icv2($str){
		return iconv('utf-8','tis-620',$str);	
	}
	public function ThaiDtm($format,$date){
			
		$year=substr($date,0,4);
		$m=substr($date,5,2);
		$d=substr($date,8,2);
		$Y=$year+543;
		$time=substr($date,11);
		
		if(empty($date)){
			return "-";
		}elseif($year=='0000' or $year=='0001'){
			return $date;
		}else{
			switch ($m) {
				case "01" : {
					$F="มกราคม";
					$M="ม.ค.";				
					$n="1";
				}break;
				case "02" : {				
					$F="กุมภาพันธ์";
					$M="ก.พ.";
					$n="2";
				}break;
				case "03" : {				
					$F="มีนาคม";
					$M="มี.ค.";
					$n="3";
				}break;
				case "04" : {
					$F="เมษายน";
					$M="เม.ย.";
					$n="4";
				}break;
				case "05" : {
					$F="พฤษภาคม";
					$M="พ.ค.";
					$n="5";
				}break;
				case "06" : {
					$F="มิถุนายน";
					$M="มิ.ย.";
					$n="6";
				}break;
				case "07" : {
					$F="กรกฎาคม";
					$M="ก.ค.";
					$n="7";
				}break;
				case "08" : {
					$F="สิงหาคม";
					$M="ส.ค.";
					$n="8";
				}break;
				case "09" : {
					$F="กันยายน";
					$M="ก.ย.";
					$n="9";
				}break;
				case "10" : {
					$F="ตุลาคม";
					$M="ต.ค.";
					$n="10";
				}break;
				case "11" : {
					$F="พฤศจิกายน";
					$M="พ.ย.";
					$n="11";
				}break;
				case "12" : {				
					$F="ธันวาคม";
					$M="ธ.ค.";
					$n="12";
				}break;			
			}
			$arrFormat=str_split($format);
			$thaiDate='';
			
			for($i=0;$i<count($arrFormat);$i++){
				switch($arrFormat[$i]){
					case "d":{
						$thaiDate.=$d;
					}break;
					case "j":{
						$j=$d*1;
						$thaiDate.=$j;
					}break;
					case "m":{
						$thaiDate.=$m;
					}break;
					case "M":{
						$thaiDate.=$M;
					}break;
					case "F":{
						$thaiDate.=$F;
					}break;
					case "n":{
						$thaiDate.=$n;
					}break;
					case "Y":{
						$thaiDate.=$Y;
					}break;
					case "y":{
						$y=substr($Y,2);
						$thaiDate.=$y;
					}break;
					default:{
						$thaiDate.=$arrFormat[$i];
					}
				}
				
			}
			if($time!=''){
				$thaiDate.=" ".$time;
			}		
			return $thaiDate;
		}
	}
	public function check_s($p){
		$hn = $p['paper_id'];
		$s = $p['s'];
		$s_type = $p['s_type'];
		switch($s_type){
			case 'vip-dgc':
				{
					if(md5("$hn~fOr^VIp-parTNer^^") == $s){
						return true;
					}
					return false;
				}
			break;
			default:
			{
				return false;
			}
		}
		
	}
	public function saveTrack($p){
        $uid='';
        if(isset($p['token_data']['main_pid'])){
            $uid=$p['token_data']['main_pid'];
        }
        $m="";
        // $ip=$this->getClientIP(); 
        if(isset($p['method'])){
            $m=$p['method'];	
        }
		$idx = '';
		if(isset($p['hn'])){
            $idx=$p['hn'];	
        }
		$f = null;
		if(isset($p['f'])){
            $f=$p['f'];	
        }
        $conn=new PDOConnect("gateway-isuandok-log");
        $con=$conn->Open();
        $id = 0;

        $sql="insert into tracks (m,idx,uid,f) values(:m, :idx, :uid, :f)";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam("m", $m);
            $stmt->bindParam("idx", $idx);
            $stmt->bindParam("uid", $uid);
            $stmt->bindParam("f", $f);
            $stmt->execute();
            $id = $con->lastInsertId();
        } catch(PDOException $e) {
            $err=true;
            $msg=$e->getMessage();
        }
        return $id;
    }
	public function set_vp_system($system = "smi", $v){
		$config = new Config();
		$cf = $config->cf['vp'][$system];
		$s = base64_decode($cf['s']);
		$r = $cf['r'];
		return $this->vp($s,$r,$v);
	}
	public function check_vp_system($system = "smi", $vp, $target_vp){
		return $this->set_vp_system($system, $vp) == $target_vp;
	}
	public function vp($s,$r,$v){
		return md5(str_replace($r, $v, $s));
	}

	function checkPin($p) {
		$pin = $p['pin'] ?? '';
		$vp = $p['token_data']['vp'] ?? '';
		if($pin == ''){
			return ['result'=> false,'status_code'=> 400];
		}
		if($vp == ''){
			$uid = $p['token_data']['s_uid'];
			return $this->check_pin_smi($uid,$pin);
		}
		$vp_valid = $this->check_vp_system('smi', md5($pin), $vp);
		return ['result'=> $vp_valid, 'status_code'=> $vp_valid ? 200 : 422];
	}
	function check_pin_smi($uid,$pin){
		$p_tmp = array(
			"uid"=>$uid,
			"pin"=>$pin,
		);
		$check = false;
		$status_code = 504;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://hosweb.med.cmu.ac.th/gateway/reg/check_pin_smi',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 5,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>json_encode($p_tmp),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$res = json_decode($response,true);
		if(isset($res["s"])) {
			if($res["s"]) {
				$status_code = 200;
			}else {
				$status_code = 422;
			}
		}
		$result = isset($res["s"])?$res["s"]:false; //ใส่เพื่อกรณี timeout และมีการ return false ออกมา จะไม่มี ตัวแปร s
		return ["result"=>$result,"status_code"=>$status_code];
	}

	function checkLock($p, $lock_config=[]) { //เช็คที่ groups ที่ config กับ token_groups array ของ user และ return ห้องที่ไม่มีสิทธิ์ ออกไป
		$token_data = $p['token_data'];
		$cfl = new Config();
		//โหลดค่า lock ที่ตั้งค่าไว้ในไฟล์ ConfigLock
		if(empty($lock_config)) {
			$lock_config = $cfl->lock_paper;
		}
		if(empty($lock_config)) { //ถ้าไม่ได้ config หรือ อ่าน config ไม้ได้
			 return [];
		}
	
		//หาค่ากลุ่มที่ token ที่ login ว่ามีอยู่ config lock ไหม
		$data = [];
		foreach($lock_config as $k=>$d) { 
			$check = false;
			$arr_temp = array_intersect($token_data['groups'],$d); //token groups array
			//current($arr_temp);
			if(!empty($arr_temp)){
				$check = true;
			}else if(array_search($token_data['group'],$d)!==FALSE){ //เดี่ยว
				$check = true;
			}
			if(!$check) {
				$data[] = $k;
			}
		}
	
		//return room ที่ user ไม่มีสิทธิ เป็น array
		if(!empty($data)) {
			 return $data;
		}else {
			return [];
		}
	}

	function list_paper_location_by_hn($p,$return="string") {
		if(!isset($p['hn'])) {
			return [];
		}
		if($p['hn']=="") {
			return [];
		}
		$hn = $p['hn'];
		$conn=new PDOConnect("ipaper");
		if(isset($p['con'])){
			$con = $p['con'];
		}else{
			$con=$conn->Open();
		}
		$data=[];
		$sql="SELECT paper_location FROM `paper_titles` where hn='{$hn}' and paper_location!='' and status=1 group by paper_location";
		try {
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$rs = $stmt->fetchAll(PDO::FETCH_ASSOC); 
			if(!empty($rs)) {
				foreach($rs as $k=>$d) {
					$data[] = $d['paper_location'];
				} 

				if(isset($p['not_in'])) {
					$not_in_tmp  = !is_array($p['not_in'])?explode(",",$p['not_in']):$p['not_in'];
					$not_in_tmp2 = array_intersect($data,$not_in_tmp);
					if($return=="string") {
						$data = implode(",",$not_in_tmp2);
					}else {
						return $not_in_tmp2;
					}
				}
				
			}
		} catch(PDOException $e) {
			$conn->ret['v']=$e->getMessage();
		}

		if(!isset($p['con'])){
			$con=null;$conn->Close();
		}
		return $data;
	}

	function cut_result($checkvalue,$results_arr,$keyfield="paper_location") {
		$check_arr = !is_array($checkvalue)?explode(",",$checkvalue):$checkvalue;
		$data_ret = [];
		foreach($results_arr as $key=>$value) {
			if(isset($value[$keyfield])) {
				if(!in_array($value[$keyfield],$check_arr)) {
					$data_ret[] = $value;
				}
			}else {
				$data_ret[] = $value;
			}
		}
		return $data_ret;
	}
	
}
?>