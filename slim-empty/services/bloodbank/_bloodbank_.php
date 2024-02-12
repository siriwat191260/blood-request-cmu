<?php
function _bloodbank_(){
    return [
        "cf" => [
          "acceptApps" => [],
          "dbs" => ['217'],
          "endpoint" => "http://xxx",
          "networks" => ['10.20.0.156']
        ],
        "services" => [
          "bloodbank_select" => ["log" => 2, "auth" => false, "groups" => ConfigGroups(['admin','vendor'])  ],
          "bloodbank_insert" => ["log" => 2, "auth" => false, "groups" => ConfigGroups(['admin','vendor'])  ],
        ],
      ];
}
?>