<?php
	include_once("file_tool". DIRECTORY_SEPARATOR ."Write.php");
	class ProApiOut{

		public static function build_api_out($ModClass){ // 模块主对象
			$ClassList = $ModClass->get_class_list();
			$FileName = self::get_class_name($ModClass->ModName) . '.cs';
			$Handle = Write::c_protocol_tool_out($FileName);
			Write::line('//本代码自动生成请勿修改//',$Handle);
			Write::line('using System;',$Handle);
			Write::line('using System.Collections;',$Handle);
			Write::line('',$Handle);
			Write::line('namespace ProtocolTool{',$Handle);
			Write::line('',$Handle);
			Write::line('public static class api_'.$ModClass->ModName.'_out {',$Handle,1);
			Write::line('',$Handle);
			Write::line('public static GameSocketManager _GSMInstance = null;',$Handle,2);
			Write::line('',$Handle,1);
			//  生成接口
			self::write_function_list($ModClass, $ClassList, $Handle);
			Write::line('}',$Handle,1);
			Write::line('}',$Handle);
			fclose($Handle);
            
            return 'gen\\out\\'.$FileName;

		}

		// 拼接文件名
		public static function get_class_name($ModName){
			return 'api_' . $ModName . '_out'; 
		} 

		//  往文件写入接口
		public static function write_function_list($ModClass, $ClassList, $Handle){
			$BodyFunctionList = $ModClass->BodyFunctionList(); // 得到所有接口列表
			foreach ($BodyFunctionList as $BodyFunction) {
				$BodyName = $BodyFunction->BodyName;
				self::write_in_put($BodyFunction, $ClassList, $Handle, $ModClass);// 接口列表、自定义类型接口列表、文件句柄、模块主对象
				Write::line(' ', $Handle);

			}
		}

		// 生成输入参数，接口列表，class自定义类型接口列表
		public static function write_in_put($BodyFunction, $ClassList, $Handle, $ModClass){
			$ModName = $ModClass->ModName;// 模块名
			$ModNum = $ModClass->ModNum; // 模块编号
			$BodyNum = $BodyFunction->BodyNum; // 接口编号
			$BodyName = $BodyFunction->BodyName; // 接口名
			Write::keep_a_line('public static void ' . $BodyName . '(', $Handle, 2);
			// 循环写入参数
			$BodyInValueList = $BodyFunction->get_body_in_value_list(); // 或者接口里面所有In属性

			foreach ($BodyInValueList as $BodyInValue) {
				$ArgName = $BodyInValue->body_value_name;
				$BodyValueType = $BodyInValue->body_value_type;
				$BodyValueTypeName = $BodyInValue->body_value_type_name;
				$ArgType = Write::type_to_protocol_string($BodyValueTypeName, $BodyValueType); //  写入in参数
				Write::keep_a_line($ArgType . ' ' . $ArgName . ',', $Handle); 
			}
			$Len = count($BodyInValueList);
			if($Len > 0){
				// 去除最后一个逗号
				Write::handle_move($Handle, -1);
			}
			Write::line('){', $Handle);
			// 函数内逻辑 
			self::write_function_body_list($BodyInValueList, $Handle, $ModNum, $BodyNum, $ModClass); // 函数内部
			Write::line('}', $Handle, 2);

		}

		// 生成内部解析逻辑
		public static function write_function_body_list($BodyInValueList, $Handle, $ModNum, $BodyNum, $ModClass){
			Write::line('NetBitStream stream = new NetBitStream();', $Handle, 3);
			Write::line('stream.BeginWrite(' . $ModNum . ',' . $BodyNum . ');', $Handle, 3);
			$LoopValueNameTimes = 0;
			foreach ($BodyInValueList as $BodyInValue) {
				self::write_function_body($ModClass, "", $BodyInValue, $Handle, $LoopValueNameTimes); // 生成每个字段写入逻辑,每对In属性值
			}
			Write::line('stream.EncodeHeader();', $Handle, 3);
			Write::line('_GSMInstance.Send(stream);', $Handle, 3);
		}

		// 生成每个字段写入逻辑, 模块主对象、参数名字、接口属性、句柄、
		public static function write_function_body($ModClass, $ArgNameAdd, $BodyValue, $Handle, &$LoopValueNameTimes = 0, $LoopSpace = 0){
			$ModName = $ModClass->ModName;
			$BodyValueName = $ArgNameAdd . $BodyValue->body_value_name;
			$BodyValueType = $BodyValue->body_value_type;
			$BodyValueTypeName = $BodyValue->body_value_type_name;
			$Type = Write::type_to_cstype($BodyValueTypeName, $ModName); // 
			$SpaceNum = 3 + $LoopSpace;

				switch ($Type){
					case 'Int32':
						Write::line('stream.WriteInt('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					/*
					case 'long':
						Write::line('stream.WriteLong('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					*/
					//lua没有long类型用字符串替代
					case 'long':
						Write::line('stream.WriteString('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					case 'Byte':
						Write::line('stream.WriteByte('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					case 'Int16':
						Write::line('stream.WriteShort('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					case 'string':
						Write::line('stream.WriteString('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					case 'float':
						Write::line('stream.WriteFloat('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					case 'double':
						Write::line('stream.WriteDouble('.$BodyValueName.');',$Handle,$SpaceNum);
						return;
					default:
						exit('write function body type error');
				}

		}













	}	
	



?>