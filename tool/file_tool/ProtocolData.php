<?php

	class ProtocolData {

		private static $_instance; // 保存类实例的静态成员变量
		//private标记的构造方法
		private function __construct(){
			$this->ClassArray = array();
			$this->InClass = array();
			$this->OutClass = array();
		}
		
		//全局类数组
		private $ClassArray;
		
		//全局In类名
		private $InClass;
		
		//全局out类名
		private $OutClass;
		
		//单例方法,用于访问实例的公共的静态方法
		public static function GetInstance(){
			if(!(self::$_instance instanceof self)){
				self::$_instance = new self;
			}
			return self::$_instance;
		}

		// 增加in类信息
		public function AddInClassName($Name){
			if(!in_array($Name, $this->InClass)){
				array_push($this->InClass, $Name);
			}
		}

		// 增加out类信息
		public function AddOutClassName($Name){
			if(!in_array($Name, $this->InClass)){
				array_push($this->OutClass, $Name);
			}
		}
		
		//增加类信息
		public function AddClass($BodyClass){
			$Index = $BodyClass->BodyFullName;
			if (array_key_exists($Index ,$this->ClassArray)){
				$this->IsSameClass($BodyClass,$this->ClassArray[$Index]);
			}else{
				//没有就创建有就覆盖(改变了in 或者 out属性会重新丢进来)
				$this->ClassArray[$Index] = $BodyClass;
			}

		}

		//检查在服务端是否被使用
		public function use_by_server($ClassName){
			if (array_key_exists($ClassName ,$this->ClassArray)){
				//----如果未被使用则赋值成已使用
				if(!$this->ClassArray[$ClassName]->IsServerUse){
					$this->ClassArray[$ClassName]->IsServerUse = true;
				}
			}
		}

		// 获得out类 
		public function get_out_class(){
			$ModClassNameList = array();
			foreach ($this->ClassArray as $BodyClass) {
				$Name = $BodyClass->BodyFullName;
				if(in_array($Name, $this->OutClass)){
					if(!array_key_exists($Name, $ModClassNameList)){
						$ModClassNameList[$Name] = $BodyClass;
					}
					//遍历寻找他的子对象是否包含
					$ClassValueList = $BodyClass->BodyValueList;
					self::input_class($ClassValueList,$ModClassNameList);
				}
			}
			return $ModClassNameList;
		}

		public function input_class($BodyValueList,&$ModClassNameList){
			foreach ($BodyValueList as $ClassValue){
				$ErlangName = $ClassValue->body_class_erlang_name;
				if(!is_null($ErlangName)){
					$BodyClass = $this->ClassArray[$ErlangName];
					$Name = $BodyClass->BodyFullName;
					if (!array_key_exists($Name ,$ModClassNameList)){
						//没有就创建有就覆盖(改变了in 或者 out属性会重新丢进来)
						$ModClassNameList[$Name] = $BodyClass;
					}
					//遍历寻找他的子对象是否包含
					self::input_class($BodyClass->BodyValueList,$ModClassNameList);
				}
			}
		}

		public function get_in_class(){
			$ModClassNameList = array();
			foreach ($this->ClassArray as $BodyClass){
				$Name = $BodyClass->BodyFullName;
				if(in_array($Name, $this->InClass)){
					if (!array_key_exists($Name ,$ModClassNameList)){
						//没有就创建有就覆盖(改变了in 或者 out属性会重新丢进来)
						$ModClassNameList[$Name] = $BodyClass;
					}
					//遍历寻找他的子对象是否包含
					$ClassValueList = $BodyClass->BodyValueList;
					self::input_class($ClassValueList,$ModClassNameList);
				}
			}
			return $ModClassNameList;
		}










	}


?>