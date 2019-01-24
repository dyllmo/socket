<?php
	include_once("ModClass.php");
	class Write{

		public static $DocFileHandle; //文件流

		// 打开文件
		public static function init_c($FileName){
			self::$DocFileHandle = fopen($FileName, "r") or exit("open {$FileName} fail!");
			// echo "DocFileHandle:";var_dump(self::$DocFileHandle);
			// echo "open file success!\n";
		}

		// 解析文件流
		public static function run_c(&$ModInfoList){
			 $NewMod = new ModClass();
			 $NewMod->init();
			 fclose(self::$DocFileHandle); // 关闭文件流
			 self::$DocFileHandle == null;
			 array_push($ModInfoList, $NewMod); // 处理好的一个文件流内容push进ModInfoList
		}


		// 获取词组,该文件流指针读完会指向下一个位置
		public static function get_unit_word(){
			$word = "";
			$is_note = false; // 是否注释
			while(!feof(self::$DocFileHandle)){ // 文件流-指针读完会指向下一个位置
				$Len = strlen($word);
				$Char = fgetc(self::$DocFileHandle); // 获得一个字符
				$CharAscii = self::AsciiToInt($Char); // 把字符转成Ascii码
				if(($CharAscii == 13) || ($CharAscii == 10)){ // 换行，普通换行，垂直换行
					//换行如果是注释则返回空
					if ($is_note){
						$word = "";
						//取消注释标记换行了
						$is_note = false;
					}else{
						if($Len > 0){
							return $word;
						}
					}
				}else if(!$is_note){
					if($CharAscii == 32){
						if($Len > 0){
							return $word;
						}
					}else if(self::is_sign($Char)){
						if($Len > 0){
							fseek(self::$DocFileHandle, -1, SEEK_CUR);
							return $word;
						}else if($CharAscii == 47){ // 注释
							$is_note = true;
						}else{
							return $Char;
						}
					}else if(
						(($CharAscii >= 48) && ($CharAscii <= 57)) || // 数字
						(($CharAscii >= 65) && ($CharAscii <= 90)) || // A-Z
						(($CharAscii >= 97) && ($CharAscii <= 122)) || // a-z
						($CharAscii == 46) || // 符号.
						($CharAscii == 95)){ // 下划线
							$word = $word.$Char;

					}
				}
			}
			return $word;
		}

		// 是否是符号
		public static function is_sign($value){
			$sign_list = array("=", "{", "}", "," ,":", "<", ">", "/");
			if(in_array($value, $sign_list)){
				return true;
			}else{
				return false;
			}
		}

		//转换成整数
		public static function AsciiToInt($Char){
			if(strlen($Char) == 1)
				return ord($Char);
			else{
				$num = 0;
				for($i = 0; $i < strlen($Char); $i++){
					$num += ord($Char[$i]);
				}
				return $num;
			}
		}

		public static function is_value($TheValue){
			$Value = self::get_unit_word();
			if($Value != $TheValue){
				exit("value is ".$Value." not is  ".$TheValue. "\n");
			}
		}

		// 往文件中写入文本和空格
		public static function line($Text, $Handle, $SpaceKeyNum = 0){
			$Space = "";
			for($i = 0; $i < $SpaceKeyNum; $i++){
				$Space = $Space . "    ";
			}
			fwrite($Handle, $Space . $Text);
		}

		public static function c_protocol_tool_api($FileName){
			global $CProtocolToolApiPath; // 创建hrl目录路径
			$CProtocolToolApiPath = 'include' . DIRECTORY_SEPARATOR .'gen'. DIRECTORY_SEPARATOR;
			self::CreateDir($CProtocolToolApiPath);

			$FullFileName = $CProtocolToolApiPath . $FileName;
			$TempHandle = fopen($FullFileName, 'w'); // 打开文件，没有就创建
			return $TempHandle;
		}

		public static function c_protocol_tool_out($FileName){
			global $CProtocolToolOutPath;
			$CProtocolToolOutPath = 'include' . DIRECTORY_SEPARATOR .'gen'. DIRECTORY_SEPARATOR;
			self::CreateDir($CProtocolToolOutPath);
			
			$FullFileName = $CProtocolToolOutPath.$FileName;
			$TempHandle = fopen($FullFileName,'w');
			return $TempHandle;
		}

		// 创建目录
		public static function CreateDir($dir){
			return is_dir($dir) or (self::CreateDir(dirname($dir)) and mkdir($dir, 0777));
		}

		public static function keep_a_line($Text, $Handle, $SpaceKeyNum = 0){
			$Space = "";
			for($i = 0; $i<= $SpaceKeyNum; $i++){
				$Space = $Space . "    ";
			}
			fwrite($Handle, $Space . $Text);


		}

		public static function type_to_protocol_string($TypeName, $Type){
			switch ($TypeName){
				case 'int':
					return 'Int32';
				case 'long':
					return 'string';	
				case 'enum':
					return 'Byte';
				case 'byte':
					return 'Byte';
				case 'short':
					return 'Int16';
				case 'string':
					return 'string';
				case 'float':
					return 'float';
				case 'double':
					return 'double';
				default:
					if($Type == "typeof"){
						return $TypeName;
					}else{
						return $TypeName.'[]';
					}
			}	
		}

		public static function handle_move($Handle, $BackNum){
			fseek($Handle, $BackNum, SEEK_CUR);
		}

		public static function type_to_cstype($Type){
			switch ($Type){
				case 'int':
					return 'Int32';
				case 'long':
					return 'long';	
				case 'enum':
					return 'Byte';
				case 'byte':
					return 'Byte';
				case 'short':
					return 'Int16';
				case 'string':
					return 'string';
				case 'float':
					return 'float';
				case 'double':
					return 'double';
				default:
					return $Type;
			}	
		}


		public static function fullname($ModName="",$BodyName){
			$IsDot = self::check_dot($BodyName);
			//是否全名,有“.”符号就是全名
			if ($IsDot){
				return $BodyName;
			}else{
				return $ModName.".".$BodyName;
			}
		}

		public static function check_dot($str){
			$needle = ".";//判断是否包含.这个字符 
			$tmparray = explode($needle,$str); 
			if(count($tmparray)>1){
				return true; 
			}else{
				return false; 
			}
		}




		public static function open_erlang_hrl($FileName){
			global $ErlangApiHrlPath;
			$ErlangApiHrlPath = 'include' . DIRECTORY_SEPARATOR .'gen'. DIRECTORY_SEPARATOR;
			self::CreateDir($ErlangApiHrlPath);
			$FullFileName = $ErlangApiHrlPath.'api_'.$FileName.'.hrl';
			$TempHandle = fopen($FullFileName,'w');
			return $TempHandle;
		}


		public static function open_erlang_api_out($FileName){
			global $ErlangApiOutPath;
			$ErlangApiOutPath = 'include' . DIRECTORY_SEPARATOR .'gen'. DIRECTORY_SEPARATOR;
			self::CreateDir($ErlangApiOutPath);
			$FullFileName = $ErlangApiOutPath.'api_'.$FileName.'_out.erl';
			echo $FullFileName;
			$TempHandle = fopen($FullFileName,'w');
			return $TempHandle;
		}


		public static function initials($str){
			$Len = strlen($str);
			$NewStr = "";
			//是否有大写字母默认没有
			$isUp = false;
			for($i = 0; $i <$Len; $i++){
				if($isUp){
					$NewStr = $NewStr.$str[$i];
				}else{
					$CharAscii 	= self::AsciiToInt($str[$i]);
					$NewChar = $str[$i];
					if(($CharAscii >= 97)&&($CharAscii <= 122)){
						//设定为大写,
						$isUp = true;
						$NewChar = chr($CharAscii-32);
					}else if(($CharAscii >= 65)&&($CharAscii <= 90)){
						$isUp = true;		
					}
					$NewStr = $NewStr.$NewChar;
				}
			}
			return $NewStr;
		}


		//获得erlang 类名
		public static function toErlangName($Name){
			return 'class_'.strtr($Name,".","_dot_");
		}

		public static function open_erlang_game_class($FileName){
			global $ErlangGameClassPath;
			$ErlangGameClassPath = 'include' . DIRECTORY_SEPARATOR .'gen'. DIRECTORY_SEPARATOR;
			self::CreateDir($ErlangGameClassPath);
			$FullFileName = $ErlangGameClassPath.$FileName.'.erl';
			$TempHandle = fopen($FullFileName,'w');
			return $TempHandle;
		}

		public static function open_erlang_file($FileName){
			global $ErlangApiOutPath;
			self::CreateDir($ErlangApiOutPath);
			$FullFileName = $ErlangApiOutPath.$FileName;
			$TempHandle = fopen($FullFileName,'w');
			return $TempHandle;
		}



	}
	
?>