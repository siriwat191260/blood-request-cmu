<?php
class CallWebService extends baseController {
  
  public function main($p){
    $config = new Config();
    $cf = $config->cf;
    $url = $cf['serviceURL'];
    $reqMethod = $cf['serviceReqMethod'];
    $headers = $cf['serviceHeaders'];
    
    if(isset($cf['mt'][$p['method']]['url'])){
      $url = $cf['mt'][$p['method']]['url'];
    }
    if(isset($cf['mt'][$p['method']]['reqMethod'])){
      $reqMethod = $cf['mt'][$p['method']]['reqMethod'];
    }
    if(isset($cf['mt'][$p['method']]['headers'])){
      $headers = $cf['mt'][$p['method']]['headers'];
    }
    
    $post = json_encode($p);
    //echo $post;exit;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $reqMethod);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    //echo $result;
    curl_close($ch);
    return $result;
  }
}