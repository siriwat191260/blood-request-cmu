<?php
function ConfigJWT(){
  return [
      "Token"=>[
        "serverName"=>"Token Name",
        "secret"=>"",
        "tokenTime"=>3600*24*365*5,
        "tokenTimeApp"=>3600*24*365,
        "acceptToken"=>[]
      ]
    ];
}