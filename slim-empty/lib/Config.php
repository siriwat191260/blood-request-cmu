<?php
include_once "ConfigJWT.php";
include_once "ConfigMethod.php";
class Config{
	var $cf;
	var $apps;
	function __construct($mt){
		$this->cf = [
			"base_url"=>"/gateway",
			"apps"=>[
				"VcxS1IxWnFUbGhoTVhCUVdWZHplRmRIV"=>["name"=>"MED CMU", "uid_key"=>"uid"],
			],
			"list"=>[
				"members"=>["rows"=>100]
			],
			"jwt_default"=>"VcxS1IxWnFUbGhoTVhCUVdWZHplRmRIV",
			"jwt"=>[],
			"mt"=>[],
			"gg_api_key"=>"",
			"rsc"=>[
				"0"=>"success"
			],
			"uid_key"=>"uid",
			"debug"=>[
				"controllers"=>[
					"_sms"=>["methods"=>"send_cat"]
				]
			],
			"use_log"=>true,
			"vp"=>[
				"mis"=>[
					"s"=>"fnBJTm1Jc35bcGluXV5e",
					"r"=>"[pin]"
				]
			]
		];
		$this->apps = $this->cf['apps']; /// ใช้บางกรณี
		$this->cf['jwt'] = ConfigJWT();
		$this->cf['mt'] = ConfigMethod($mt);
	}
}