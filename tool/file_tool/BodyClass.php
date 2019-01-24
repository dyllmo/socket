<?php
	include_once("ProtocolData.php");
	class BodyClass{

		public $ModHandle; // 模块主对象
		public $ModName; // 模块名

		public $BodyName; // 本接口名
		public $BodyValueList; // 模块内部属性列表
		public $isIn;
		public $isOut;

		public $BodyFullName; // 模块内部类全名称,自定义类型接口模块全名
		public $ErlangName;		//在erlang模块时的名称
		public $BodyParent; // 父类
		public $IsServerUse; // 是否在服务端game_router中使用

		function __construct($ModHandle, $isIn, $isOut){
			$this->ModHandle = $ModHandle;
			$this->ModName = $ModHandle->ModName;
			$this->BodyValueList = array();
			$this->isIn = $isIn;
			$this->isOut = $isOut;	

			$this->IsServerUse = false;
			$this->BodyParent = null;
		}

		public function init($BodyName = ""){

			if($BodyName == ""){
				//获取一条数据
				$this->BodyName = Write::get_unit_word();
				$Sign = Write::get_unit_word();
				//检查是否有父类
				if($Sign == ":"){
					$ParentName = Write::get_unit_word();
					$this->BodyParent = $this->fullname($this->ModHandle->ModName,$ParentName);
					$Sign = Write::get_unit_word();
					//检查是否是分隔号
					if($Sign != "{"){
						exit("body not find { \n");
					}
				}elseif($Sign != "{"){//检查是否是分隔号
					exit("body not find { \n");
				}
			}else{
				$this->BodyName = $BodyName;
			}
		    $this->BodyFullName = $this->fullname($this->ModHandle->ModName, $this->BodyName);
		    $this->toErlangName();
		    
		    //
		    $Value = Write::get_unit_word();
		    while($Value != "}"){
		    	$BodyValueName = $Value;
		    	$Sign = Write::get_unit_word();

		    	if($Sign != ":"){
		    		exit("not find : \n");
		    	}

		    	$BodyValueType = Write::get_unit_word();
		    	$NewBodyValue = new BodyValue($BodyValueName, $BodyValueType, $this->ModHandle, $this->isIn, $this->isOut);
		    	$NewBodyValue->init();
		    	$this->add_body_value_list($NewBodyValue);

		    	$Value = Write::get_unit_word();

		    }

			//把自身加到主对象
			$this->ModHandle->add_body_class_list($this);
			//添加到全局对象
			ProtocolData::GetInstance()->AddClass($this);
		}

		private function add_body_value_list($BodyValue){
			array_push($this->BodyValueList, $BodyValue);
		}

		// 获得属性
		public function get_value_list(){
			return $this->BodyValueList;
		}

		//获得erlang 类名
		private function toErlangName(){
			$Name = $this->BodyFullName;
			$Name = strtr($Name,".","_");
			$this->ErlangName = 'class_'.$Name;
		}

		//编写全名
		private function fullname($ModName="",$BodyName){
			$IsDot = Write::check_dot($BodyName);
			//是否全名,有“.”符号就是全名
			if ($IsDot){
				return $BodyName;
			}else{
				return $ModName.".".$BodyName;
			}
		}

	}

?>