<?php

	include_once("file_tool" . DIRECTORY_SEPARATOR . "Write.php");
	class ProApiHrl{

		public static function build_api_hrl($ModClass){
			$FileName = self::get_class_name($ModClass->ModName);

			$Handle = Write::c_protocol_tool_api($FileName); // 创建hrl目录及文件，打开hrl文件，返回句柄

			Write::line('//----Do not modify the automatically generated----//',$Handle);
			Write::line('using System;',$Handle);
			Write::line('using System.Collections;',$Handle);	
			Write::line('namespace ProtocolTool{',$Handle);
			Write::line('',$Handle);

			// 构建枚举
			$EnumList = $ModClass->get_enum_list();
			self::write_enum($ModClass->ModName, $EnumList, $Handle);
			
			// 构建类
			self::write_class($ModClass, $Handle);
			Write::line('}', $Handle);
			fclose($Handle);

			return 'gen\\api\\'.$FileName;
		}

		// 获得生成的协议全名
		public static function get_class_name($ModName){
			return 'api_' . $ModName . '_hrl';
		}

		// 写枚举
		public static function write_enum($ModName, $ClassList, $Handle){
			Write::line('public class api_' . $ModName . '_hrl {', $Handle);
			Write::line('public enum ID {', $Handle, 2);
			foreach ($ClassList as $key) {
				Write::line($key . ',', $Handle, 3);
			}
			Write::line('}', $Handle, 2);
			Write::line('}', $Handle, 1);
		}
















		// 写类
		public static function write_class($ModClass, $Handle){
			$ModName = $ModClass->ModName;
			$BodyClassList = $ModClass->BodyClassList;
			foreach ($BodyClassList as $BodyClass) {
				$BodyValueList = $BodyClass->get_value_list();
				$BodyName = $BodyClass->BodyName;
				foreach ($BodyValueList as $BodyValue) {
					$BodyValueName = $BodyValue->body_value_name;
					$BodyValueType = $BodyValue->body_value_type;
					$BodyValueTypeName = $BodyValue->body_value_type_name;
					Write::line('public ' . $BodyValueType . ' '. $BodyValueName . ';', $Handle, 2);

				}
				Write::line('}',$Handle,1);
				Write::line(' ',$Handle);
			}
		}

	}


?>