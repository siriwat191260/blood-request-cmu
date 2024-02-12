<?php
class Log
{
    public function saveLogGet($uri)
    {

        $uid = '';
        $m = "";
        $ip = $this->getClientIP();
        $req_m = $_SERVER['REQUEST_METHOD'];

        $conn = new PDOConnect("gateway-log");
        $con = $conn->Open();

        //
        $date = date("Y-m-d");
        $time = date("H:i:s");
        //

        $id = 0;

        $sql = "insert into logs (req_m,m,uri,ip,dtm,uid,date,time) values(:req_m,:m,:uri,:ip,sysdate(),'$uid',:date,:time);"; //

        try {

            $stmt = $con->prepare($sql);
            $stmt->bindParam("req_m", $req_m);
            $stmt->bindParam("m", $m);
            $stmt->bindParam("uri", $uri);
            $stmt->bindParam("ip", $ip);

            //
            $stmt->bindParam("date", $date);
            $stmt->bindParam("time", $time);
            //

            $stmt->execute();
            $id = $con->lastInsertId();
            if ($id > 0) {
                $con = null;
            } else {
                $con = null;
            }
        } catch (PDOException $e) {
            $err = true;
            $msg = $e->getMessage();
        }

        return $id;
    }
    public function saveLog($uri, $p, $h)
    {
        // print_r($p);exit;
        $req_m = $_SERVER['REQUEST_METHOD'];
        $params = $p;
        $p = $this->decode_unicode(json_encode($p));
        $uid = '';
        if (isset($params['token_data']['uid'])) {
            $uid = $params['token_data']['uid'];
        }

        $m = "";
        $ip = $this->getClientIP();

        //
        $date = date("Y-m-d");
        $time = date("H:i:s");
       
        //

        if (isset($params['method'])) {
            $m = $params['method'];
        }

        $conn = new PDOConnect("gateway-log");
        $con = $conn->Open();

        $id = 0;

        $sql = "insert into logs (req_m,h,m,uri,p,ip,dtm,uid,date,time) values(:req_m,:h,:m,:uri,:p,:ip,sysdate(),'$uid',:date,:time);"; //

        try {

            $stmt = $con->prepare($sql);
            $stmt->bindParam("req_m", $req_m);
            $stmt->bindParam("h", $h);
            $stmt->bindParam("m", $m);
            $stmt->bindParam("uri", $uri);
            $stmt->bindParam("p", $p);
            $stmt->bindParam("ip", $ip);

            //
            $stmt->bindParam("date", $date);
            $stmt->bindParam("time", $time);
            //

            $stmt->execute();
            $id = $con->lastInsertId();
            if ($id > 0) {
                $con = null;
            } else {
                $con = null;
            }
        } catch (PDOException $e) {
            $err = true;
            $msg = $e->getMessage();
        }

        return $id;
    }

    //function updateLog($id,$p,$http_code,$total_time,$uid){
    function updateLog($id, $p, $log_detail)
    {

        $http_code = $log_detail['http_code'];
        $total_time = $log_detail['total_time'];
        $uid = $log_detail['uid'];
        $app = $log_detail['app'];

        $p = $this->decode_unicode($p);

        if (strlen($uid) > 13) {
            $uid = substr($uid, 0, 10) . '...';
        }
        //
        $p_tmp = $p;
        $p_obj = json_decode($p_tmp);

        $conn = new PDOConnect("gateway-log");
        $con = $conn->Open();

        //$sql="update logs set res=:p,res_code=:http_code,total_time=:total_time, app=:app, uid=:uid where log_id=:id";
        if (trim($total_time) == '') {
            $sql = "update logs set res=:p,res_code=:http_code,total_time=TIME_TO_SEC(timediff(now(), dtm)), app=:app, uid=:uid where log_id=:id";
        } else {
            $sql = "update logs set res=:p,res_code=:http_code,total_time=:total_time, app=:app, uid=:uid where log_id=:id";
        }
        try {

            $stmt = $con->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("uid", $uid);
            $stmt->bindParam("p", $p);
            $stmt->bindParam("http_code", $http_code);
            if (trim($total_time) != '') {
                $stmt->bindParam("total_time", $total_time);
            }
            $stmt->bindParam("app", $app);

            $stmt->execute();
            $rowCount = $stmt->rowCount();

            $con = null;
        } catch (PDOException $e) {
            $err = true;
            $msg = $e->getMessage();
        }
    }
    function updateLogWithResponse($log_id, $response, $code)
    {

        switch ($code) {
            case '401':
                /*
                $res = $response->withStatus(code)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message":"Unauthorized"}');
                        */
                $res = $response->withStatus($code);
                break;
            default:
                $res = $response->withStatus($code);
        }
        return $this->updateLog($log_id, $res);
    }
    function getClientIP()
    {

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            return  $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER["REMOTE_ADDR"];
        } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER["HTTP_CLIENT_IP"];
        }

        return '';
    }
    function decode_unicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }
}
/*
class Log2{
    public function saveLogGet($uri){

        $uid='';
        $m="";
        $ip=$this->getClientIP(); 
        $req_m = $_SERVER['REQUEST_METHOD'];
        
        $conn=new PDOConnect("gateway-isuandok-log");
        $con=$conn->Open();
        
        $id = 0;

        $sql="insert into logs (req_m,m,uri,ip,dtm,uid) values(:req_m,:m,:uri,:ip,sysdate(),'$uid');";
        
        try {

            $stmt = $con->prepare($sql);
            $stmt->bindParam("req_m", $req_m);
            $stmt->bindParam("m", $m);
            $stmt->bindParam("uri", $uri);
            $stmt->bindParam("ip", $ip);
            
            $stmt->execute();
            $id = $con->lastInsertId();
            if($id>0){
                $con = null;

            }else{
                $con = null;
            }
            
        } catch(PDOException $e) {
            $err=true;
            $msg=$e->getMessage();
        }
        
        return $id;
    }
    public function saveLog($uri, $p, $h){
        // print_r($p);exit;
        $req_m = $_SERVER['REQUEST_METHOD'];
        $params=$p;
        $p=$this->decode_unicode(json_encode($p));
        $uid='';
        if(isset($params['token_data']['uid'])){
            $uid=$params['token_data']['uid'];
        }
        
        $m="";
        $ip=$this->getClientIP(); 

        if(isset($params['method'])){
            $m=$params['method'];	
        }

        
        $conn=new PDOConnect("gateway-isuandok-log");
        $con=$conn->Open();
        
        $id = 0;

        $sql="insert into logs (req_m,h,m,uri,p,ip,dtm,uid) values(:req_m,:h,:m,:uri,:p,:ip,sysdate(),'$uid');";
        
        try {

            $stmt = $con->prepare($sql);
            $stmt->bindParam("req_m", $req_m);
            $stmt->bindParam("h", $h);
            $stmt->bindParam("m", $m);
            $stmt->bindParam("uri", $uri);
            $stmt->bindParam("p", $p);
            $stmt->bindParam("ip", $ip);
            
            $stmt->execute();
            $id = $con->lastInsertId();
            if($id>0){
                $con = null;

            }else{
                $con = null;
            }
            
        } catch(PDOException $e) {
            $err=true;
            $msg=$e->getMessage();
        }
        
        return $id;
    }

    //function updateLog($id,$p,$http_code,$total_time,$uid){
    function updateLog($id, $p, $log_detail){

        $http_code = $log_detail['http_code'];
        $total_time = $log_detail['total_time'];
        $uid = $log_detail['uid'];
        $app = $log_detail['app'];

        $p = $this->decode_unicode($p);
        
        $conn=new PDOConnect("gateway-isuandok-log");
        $con=$conn->Open();
        
        
        $sql="update logs set res=:p,res_code=:http_code,total_time=:total_time, app=:app, uid=:uid where log_id=:id";

        try {

            $stmt = $con->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("uid", $uid);
            $stmt->bindParam("p", $p);
            $stmt->bindParam("http_code", $http_code);
            $stmt->bindParam("total_time", $total_time);
            $stmt->bindParam("app", $app);
            
            $stmt->execute();
            $rowCount = $stmt->rowCount();
                    
            $con = null;
            
            
        } catch(PDOException $e) {
            $err=true;
            $msg=$e->getMessage();
        }

    }
    function updateLogWithResponse($log_id,$response,$code){

        switch($code){
            case '401':
                
                $res = $response->withStatus(code);
                break;
            default: 
                $res = $response->withStatus(code);
        }
        return $this->updateLog($log_id,$res);
    }
    function getClientIP(){
        
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            return  $_SERVER["HTTP_X_FORWARDED_FOR"];  
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) { 
            return $_SERVER["REMOTE_ADDR"]; 
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER["HTTP_CLIENT_IP"]; 
        } 

        return ''; 
    }
    function decode_unicode($str){
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }
}
*/
