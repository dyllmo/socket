<?php
	include_once("BodyValue.php");
	class BodyFunction{

		public $ModHandle; // 模块对象
		public $ModNum; // 模块编号
		public $BodyName; // 接口名
		public $BodyNum; // 接口编号
		public $BodyInValueList; // 接口输入属性列表
		public $BodyOutValueList; // 接口输出属性列表
		public $SocketSign; // socket标记

		function __construct($ModNum, $BodyName, $SocketSign, $ModHandle){
			$this->ModHandle = $ModHandle;
			$this->ModNum = $ModNum;
			$this->BodyName = $BodyName;
			$this->SocketSign = $SocketSign;
			$this->BodyInValueList = array();
			$this->BodyOutValueList = array();
		}

		public function init(){
			Write::is_value("="); // 是否为“=”
			$this->BodyNum = Write::get_unit_word(); // 接口编号
			Write::is_value("{");
			$this->in_and_out("in");
			$this->in_and_out("out");
			Write::is_value("}"); // 接口结束
			$this->ModHandle->add_body_function_list($this);// 把自己(接口添加到模块的接口列表中)
		}

		// 处理in 和out
		public function in_and_out($Type){
			if(($Type == "in") || ($Type == "out")){
				if($Type == "in"){
					$isIn = true;
					$isOut = false;
				}else if($Type == "out"){
					$isIn = false;
					$isOut = true;					
				}
				Write::is_value($Type); // 判断是不是
				Write::is_value("{"); // 判断是不是'{'
				// 获取一条数据
				$Value = Write::get_unit_word();
				while($Value != "}"){
					$BodyValueName = $Value; // 属性的名称
					Write::is_value(":"); // 判断是否为“：”
					$BodyValueType = Write::get_unit_word(); // 属性的类型
					$NewBodyValue = new BodyValue($BodyValueName, $BodyValueType, $this->ModHandle, $isIn, $isOut);
					$NewBodyValue->init();
					if($Type=="in"){
						$this->add_body_in_value_list($NewBodyValue);
					}else if ($Type=="out"){
						$this->add_body_out_value_list($NewBodyValue);
					}
					//继续获取一条数据
					$Value = Write::get_unit_word();
				}
			}else{
				exit("not in or out error\n");
			}
		}

		public function add_body_in_value_list($NewBodyValue){
			array_push($this->BodyInValueList, $NewBodyValue);			
		}

		public function add_body_out_value_list($NewBodyValue){
			array_push($this->BodyOutValueList, $NewBodyValue);			
		}

		public function get_body_in_value_list(){
			return $this->BodyInValueList;
		}
	}
?>