<?php
	include_once("BodyFunction.php");
	class ModClass{

		public $ModName; // 模块名
		public $ModNum; // 模块编号
		public $BodyFunctionList; // 接口数组
		public $BodyClassList; // 自定义数据类型数组
		public $BodyEnumList; // 枚举类型数组
		public $TypeList; // 基本类型
		public $SocketSign; // socket标记

		function __construct(){
			$this->BodyFunctionList = array();
			$this->BodyClassList = array();
			$this->BodyEnumList = array();
			$this->TypeList = array('int', 'long', 'enum', 'string', 'byte', 'short', 'float', 'double');
			$this->SocketSign = "game";

		}

		public function init(){
			$this->ModName = Write::get_unit_word(); // 模块名
			$Sign = Write::get_unit_word();
			if($Sign != "="){	// 检查是否是‘=’号
				exit("not find = \n");
			}
			$this->ModNum = Write::get_unit_word(); // 模块编号
			$Sign = Write::get_unit_word();
			if($Sign == "{"){
			}else if ($Sign == "socket") {
				Write::is_value("=");
				$this->SocketSign = Write::get_unit_word();
				Write::is_value("{");
			}else{
				exit("not find { \n");
			}

			// 接下去接口的逻辑处理
			$Value = Write::get_unit_word(); // 第一个接口名字
			while($Value != "}"){
				if($Value == "class"){
					// 生成一个class对象
					$NewBodyClass = new BodyClass($this,false,false);
					$NewBodyClass->init();
				}else{
					// 生成一个接口对象
					$NewBodyFunction = new BodyFunction($this->ModNum, $Value, $this->SocketSign, $this);
					$NewBodyFunction->init();
				}
				$Value = Write::get_unit_word(); // 继续取下一个接口名字
			}

			//---------服务端协议中in用到的类型列表---------
			$in_type_list = array();
			
			foreach($this->BodyFunctionList as $key=>$BodyFunction){
				foreach($BodyFunction->BodyInValueList as $Valuekey=>$BodyInValue){
					//检查数据类型是否带父类
					$IsDot = Write::check_dot($BodyInValue->body_value_type_name);
					//是否全名,有“.”符号就是全名
					if ($IsDot){
						$ClassType = $BodyInValue->body_value_type_name;
					}else{
						$ClassType = $this->ModName.".".$BodyInValue->body_value_type_name;
					}
					if(!in_array($ClassType,$this->TypeList)){
						if(in_array($ClassType,$in_type_list)){
							continue;
						}else{
							array_push($in_type_list,$ClassType);
						}
					}
				}
				//foreach($BodyFunction->BodyInValueList as $Valuekey=>$BodyInValue){
				//	if(!in_array($BodyInValue->body_value_type_name,$this->TypeList)){
				//		if(in_array($BodyInValue->body_value_type_name,$in_type_list)){
				//			continue;
				//		}else{
				//			array_push($in_type_list,$BodyInValue->body_value_type_name);
				//		}
				//	}
				//}
			}
			//echo $this->ModNum."*********\n";
			//---------服务端解析客户端数据需要用到的类-----
			foreach($in_type_list as $key=>$val){
				//给全部类标注是否已经使用
				ProtocolData::GetInstance()->use_by_server($val);
				//self::server_use($val);
			}
			//---所有的类列表-------------------------
			//foreach($this->BodyClassList as $key=>$BodyClass){
			//	if($BodyClass->IsServerUse){
			//		echo $BodyClass->BodyName."\n";
			//	}
			//}


			
		}

		//  添加接口到接口列表
		public function add_body_function_list($BodyFunction){
			array_push($this->BodyFunctionList, $BodyFunction);
		}

		// 添加class自定义类型对象到主对象
		public function add_body_class_list($BodyClass){
			$BodyName = $BodyClass->BodyName;
			foreach ($this->BodyClassList as $OldBodyClass){
				$OldBodyName = $OldBodyClass->BodyName;
				if($OldBodyName == $BodyName){
					return;
				}
			}
			array_push($this->BodyClassList, $BodyClass);		
		}

		// 添加枚举对象到主对象
		public function add_body_enum_list($Enum){
			if(!in_array($Enum, $this->BodyEnumList)){
				array_push($this->BodyEnumList, $Enum);
			}
		}

		// 获得枚举类
		public function get_enum_list(){
			return $this->BodyEnumList;
		}

		// 获得class列表
		public function get_class_list(){
			$class_list = array();
			foreach ($this->BodyClassList as $BodyClass){
				$BodyValueList = array();
				foreach ($BodyClass->BodyValueList as $BodyValue){
					$BodyValueList[$BodyValue->body_value_name] = $BodyValue->body_value_type_name;
				}
				$class_list[$BodyClass->BodyName]=$BodyValueList;
			}
			return $class_list;
		}

		// 获得接口列表
		public function BodyFunctionList(){
			return $this->BodyFunctionList;
		}

	

	}

?>