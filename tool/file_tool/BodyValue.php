<?php
	include_once("BodyClass.php");
	include_once("BodyEnum.php");
	include_once("ProtocolData.php");
	class BodyValue{

		public $ModHandle; // 模块主对象
		public $body_value_name; // 属性名称
		public $body_value_type; // 属性类型
		public $body_value_type_name;  // 如果类型是自定义，那么就是自定义类型名称
		public $body_class_erlang_name; // 有些类比如item_list: list{player_item:int} 类内部只有1个属性    自定义类接口的模块全名
		public $body_class_single_name; // 属于输入接口in还是属于输出接口out
		public $isIn; // 属性是否属于in
		public $isOut; // 属性是否属于out

		function __construct($body_value_name, $body_value_type, $ModHandle, $isIn, $isOut){
			$this->ModHandle = $ModHandle;
			$this->body_value_name = $body_value_name;
			$this->body_value_type = $body_value_type;
			$this->body_value_type_name = $body_value_type;
			$this->body_class_single_name = null;
			$this->body_class_erlang_name = null;
			$this->isIn = $isIn;
			$this->isOut = $isOut;
		}

		// 属性类型不是自定义结构类型则不作任何操作，返回本对象
		// 属性类型为自定义类型则进行如下操作
		public function init(){
			if($this->body_value_type == "list"){
				$Sign = Write::get_unit_word();
				if($Sign == "<"){ // 先不处理-------------------

				}else if($Sign == "{"){
					$this->body_value_type_name = $this->body_value_name;
					$NewBodyClass = new BodyClass($this->ModHandle, $this->isIn, $this->isOut);
					$NewBodyClass->init($this->body_value_name); // 到时候如果 属性名称 和 类型接口名称相同  那么就是class类，到主对象找具体数据
					$this->body_class_erlang_name = $NewBodyClass->BodyFullName;
					//如果类中只有一个变量把变量类型记下
					$SubList = $NewBodyClass->BodyValueList;

					$SubLen = count($SubList);
					if($SubLen == 1){
						foreach ($SubList as $SubValue){
							$this->body_class_single_name = $SubValue->body_value_type;
						}
					}
				}else{
					exit("body class sign error\n");
				}
				if($this->isIn){
					ProtocolData::GetInstance()->AddInClassName($this->body_class_erlang_name);
				}else{
					ProtocolData::GetInstance()->AddOutClassName($this->body_class_erlang_name);
				}
			}else if($this->body_value_type == "typeof"){ // 先不处理

			}else if($this->body_value_type == "enum"){
				// 生成一个enum对象
				$NewBodyEnum = new BodyEnum($this->ModHandle);
				$NewBodyEnum->init();
			}

		}


	}
	
?>