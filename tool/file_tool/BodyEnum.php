<?php

	class BodyEnum{

		public $ModHandle;

		function __construct($ModHandle){
			$this->ModHandle = $ModHandle;
		}

		public function init($Sign = ""){
			if($Sign == ""){
				$Sign = Write::get_unit_word();
			}
			if($Sign != "{"){
				exit("not find { \n");
			}
			$Value = Write::get_unit_word();

			while($Value != "}"){
				$this->ModHandle->add_body_enum_list($Value);
				$Value = Write::get_unit_word();
			}
		}

	}


?>