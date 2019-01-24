<?php

	include_once("file_tool" . DIRECTORY_SEPARATOR . "Write.php");

	class ErlangHrl{

		public static function build_erlang_hrl($ModClass){
			$Handle = Write::open_erlang_hrl($ModClass->ModName);
			self::create_enum_list($ModClass, $Handle);
			fclose($Handle);
		}

		public static function create_enum_list($ModClass, $Handle){
			$BodyEnumList = $ModClass->BodyEnumList;
			$Num = 0;
			foreach ($BodyEnumList as $BodyEnum) {
				$Enum = strtoupper($BodyEnum);
				Write::line('-define('.$Enum.','.$Num.').',$Handle);
				$Num++;
			}

		}

	}
?>