<?php
function ConfigGroups($grps)
{
  $rs_grps = [];
  foreach ($grps as $grp) {
    $rs_grps = array_merge($rs_grps, GetGroup($grp));
  }
  return $rs_grps;
}
function ConfigRoles($roles)
{
  $rs_roles = [];
  foreach ($roles as $role) {
    $rs_roles = array_merge($rs_roles, GetGroup($role));
  }
  return $rs_roles;
}
function GetGroup($grp)
{
  switch ($grp) {
    case 'admin': {
        return [9, 20];
      }
      break;
    
    case 'vendor': {
        return [9, 1];
      }
      break;
  }
  return [];
}
function GetRole($role){
  switch ($role) {
    case 'admin': {
      return [9, 20];
    }
    break;
    case 'student-med': {
      return [6704, 6705, 6706];
    }
    break;
  }
  return [];
}
function ConfigMethod($mt)
{
  $cf = [
    "default" => [
      "cf" => [],
      "services" => []
    ],
    "bloodbank" => [
      "cf" => [
        "acceptApps" => [],
        "dbs" => ['217'],
        "endpoint" => "http://xxx",
        "networks" => ['172.17.8.144']
      ],
      "services" => [
        "bloodbank_select" => ["log" => 2, "auth" => false, "groups" => ConfigGroups(['admin','vendor'])  ],
        "bloodbank_insert" => ["log" => 2, "auth" => false, "groups" => ConfigGroups(['admin','vendor'])  ],
      ],
    ]
  ];
  if(isset($cf[$mt])){
    return $cf[$mt];
  }else{
    include_once "services/$mt/_".$mt."_.php";
    return call_user_func("_".$mt."_");
  };
}