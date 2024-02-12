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
    case 'clinic': {
        return [9, 20, 100, 102, 103, 200, 202, 300, 302];
      }
    case 'health': {
        return [100, 200, 302];
      }
      break;
    case 'emr': {
        return [9, 20, 100, 101, 102, 103, 180, 200, 201, 202, 203, 300, 301, 302, 490, 2300, 4101, 6706]; // Medical Student6
      }
      break;
    case 'emr-view-only': {
        return [490, 6704, 6705]; // Nutritionist, Medical Student45
      }
      break;
    case 'rad': {
        return [421, 422, 2300];
      }
      break;
    case 'reg': {
      return [4101];
    }
      break;
    case 'vendor': {
        return [9, 1];
      }
      break;
    case 'vip-partner': {
        return [2];
      }
      break;
    case 'tester': {
        return [5600];
      }
      break;
    case 'testeriViewer': {
        return [9, 20, 30];
      }
      break;
    case 'helper': {
        return [203, 210];
      }
      break;
    case 'psychiatryDoc': {
        return [2200, 2201];
      }
      break;
    case 'PsychiatryNurse': {
        return [2200, 2201];
      }
      break;
    case 'GyneDoc': {
        return [1800,1801,1802,1803,1804];
      }
      break;
    case 'GyneNurse': {
        return [1809,1811];
      } 
      break;     
    case 'phar': {
        return [300,301];
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
    case 'doctor': {
      return [100, 101, 102, 103];
    }
    case 'intern': {
      return [104];
    }
    case 'dentist': {
      return [170];
    }
    break;
    case 'extern': {
      return [180];
    }
    break;
    case 'student-med': {
      return [180, 181, 182];
    }
    break;
    case 'nurse': {
      return [200, 202];
    }
    break;
    case 'practical-nurse': {
      return [203];
    }
    break;
    case 'helper': {
      return [210];
    }
    break;
    case 'pharmacist': {
      return [300, 302];
    }
    break;
    case 'pharmacist-helper': {
      return [310];
    }
    break;
    case 'med-tech': {
      return [400, 401, 402];
    }
    break;
    case 'lab-technician': {
      return [410];
    }
    break;
    case 'rad-technologist': {
      return [421];
    }
    break;
    case 'rad-technician': {
      return [422];
    }
    break;
    case 'bloodbank': {
      return [430];
    }
    break;
    case 'phy-therapist': {
      return [450];
    }
    break;
    case 'occ-therapist': {
      return [450];
    }
    break;
    case 'psychologist': {
      return [460];
    }
    break;
    case 'nutritionist': {
      return [490];
    }
    break;
    case 'reg': {
      return [700, 702, 711];
    }
    break;
    
  }
  return [];
}
function ConfigMethod($apps)
{
  $cf = [
    "default" => [
      "cf" => [
        "acceptApps" => ['isuandok', ''],
        "networks" => []
      ],
      "service" => [
        "test" => ["auth" => true, "groups" => ConfigGroups(['tester'])],
        "send_line_uid" => ["auth" => false, "log" => true]
      ]
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
  return $cf;
}