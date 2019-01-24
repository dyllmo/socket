<?php
	include_once("file_tool". DIRECTORY_SEPARATOR ."Write.php");
	class GameRouterClass{
		public static function build(){
			$Handle = Write::open_erlang_game_class("game_router_class");
			Write::line('-module(game_router_class).',$Handle);
			Write::line('',$Handle);
			Write::line('-compile(export_all).',$Handle);
			Write::line('',$Handle);
			$ClassList = ProtocolData::GetInstance()->get_in_class();
			//创建类和模板的递归解析
			foreach ($ClassList as $ModClass){
				self::resolution_class($ModClass,$Handle);
			}
			//字符串转换浮点数函数
			self::my_list_to_float($Handle);
			fclose($Handle);
		}
		
		public static function resolution_class($BodyClass,$Handle){
			$ClassName 	= $BodyClass->ErlangName;
			Write::line($ClassName .'(0, _Args, Result) ->' ,$Handle);
			Write::line('{lists:reverse(Result), _Args};' ,$Handle,1);	
			Write::line( $ClassName .' (_Count, _Args0, _Result) ->' ,$Handle);
			$BodyValueList = $BodyClass->get_value_list();
			$EndArgs = self::resolution_arg_list($BodyValueList,$Handle,1,true);
			//写入元组
			Write::keep_a_line("_NewItem = {",$Handle,1);
			foreach ($BodyValueList as $BodyValue){
				$BodyValueName 	= $BodyValue->body_value_name;
				$ArgName 		= Write::initials($BodyValueName);
				Write::keep_a_line($ArgName.',',$Handle);
			}
			if (count($BodyValueList)>0){
				Write::handle_move($Handle,-1);
			}
			Write::line("},",$Handle);
			Write::line('    '.$ClassName .'(_Count-1, '.$EndArgs.', [_NewItem | _Result]).' ,$Handle);
			Write::line("",$Handle);
		}
		
		public static function resolution_arg_list ($BodyValueList,$Handle,$Pos,$IsClass=false){
			$ArrayList = array();
			$Loop = 1;
			$EndArgs = "";
			foreach ($BodyValueList as $BodyValue){
				array_push($ArrayList,$BodyValue);
				$BodyValueType	= $BodyValue->body_value_type;
				//保证类的处理是在最后<<.....size:32/signed, class/binary>>
				if(($BodyValueType == "typeof")||($BodyValueType == "list")){
					$EndArgs = self::resolution_arg($ArrayList,$Handle,$Loop,$Pos,$IsClass);
					$ArrayList = array();
					$Loop++;
				}
			}
			$ArrayLen = count($ArrayList);
			if($ArrayLen > 0){
				$EndArgs = self::resolution_arg($ArrayList,$Handle,$Loop,$Pos,$IsClass);
			}
			return $EndArgs;
		}
		
		public static function resolution_arg($BodyValueList,$Handle,$Loop,$Pos,$IsClass=false){
			$BeginArgs = '_Args'.($Loop-1);
			$EndArgs = $BeginArgs;
			$ClassSize = 'Size'.$Loop;
			Write::keep_a_line("<<",$Handle,$Pos);
			$BodyValueListLen = count($BodyValueList);
			$BodyPoint = 0;
			$StringArray = array();
			$FloatArray = array();
			foreach ($BodyValueList as $BodyValue){
				$BodyValueName 		= $BodyValue->body_value_name;
				$BodyValueTypeName 	= $BodyValue->body_value_type_name;
				$BodyValueType		= $BodyValue->body_value_type;
				$ModName 			= $BodyValue->ModHandle->ModName;
				$ArgName 			= Write::initials($BodyValueName);
				$ClassArgBin 		= $ArgName.'_Bin';
				switch ($BodyValueTypeName){
					case 'int':
						Write::keep_a_line($ArgName.":32/signed",$Handle);
						break;
					/*因为lua而改动
					case 'long':
						Write::keep_a_line($ArgName.":64/signed",$Handle);
						break;
					*/
					case 'long':
						$Len = $ArgName.'Len';
						Write::keep_a_line($Len.':32/signed, '.$ArgName.'_str:'.$Len.'/binary',$Handle);
						array_push($FloatArray,array($ArgName => $ArgName.'_str'));
						break;
					case 'byte':
						Write::keep_a_line($ArgName.":8/signed",$Handle);
						break;
					case 'short':
						Write::keep_a_line($ArgName.":16/signed",$Handle);
						break;
					case 'enum':
						Write::keep_a_line($ArgName.":8/signed",$Handle);
						break;
					case 'string':
						$Len = $ArgName.'Len';
						Write::keep_a_line($Len.':32/signed, '.$ArgName.'_str:'.$Len.'/binary',$Handle);
						array_push($StringArray,array($ArgName => $ArgName.'_str'));
						break;
					case 'float':
						$Len = $ArgName.'Len';
						Write::keep_a_line($Len.':32/signed, '.$ArgName.'_str:'.$Len.'/binary',$Handle);
						array_push($FloatArray,array($ArgName => $ArgName.'_str'));
						break;
					case 'double':
						$Len = $ArgName.'Len';
						Write::keep_a_line($Len.':32/signed, '.$ArgName.'_str:'.$Len.'/binary',$Handle);
						array_push($FloatArray,array($ArgName => $ArgName.'_str'));
						break;
					default:
						$EndArgs = '_Args'.$Loop;
						$ErlangName = $BodyValue->body_class_erlang_name;
						$ErlangName	=Write::toErlangName($ErlangName);
						//typeof单一数据所以数量为1 除了typeof之外就是list数据所以要size
						if($BodyValueType == "typeof"){
							Write::line($ClassArgBin.'/binary>> = '.$BeginArgs.',',$Handle);
							self::bin_to_str($StringArray,$Handle,$Pos);
							self::bin_to_float($FloatArray,$Handle,$Pos);

							Write::line('{['.$ArgName.'],'.$EndArgs.'} = '.$ErlangName.'(1,'.$ClassArgBin.',[]),',$Handle,$Pos);
							return $EndArgs;
						}else{
							Write::line($ClassSize.':32/signed,'.$ClassArgBin.'/binary>> = '.$BeginArgs.',',$Handle);
							self::bin_to_str($StringArray,$Handle,$Pos);
							self::bin_to_float($FloatArray,$Handle,$Pos);
							Write::line('{'.$ArgName.','.$EndArgs.'} = '.$ErlangName.'('.$ClassSize.','.$ClassArgBin.',[]),',$Handle,$Pos);
							return $EndArgs;
						}
				}
				if($BodyPoint == ($BodyValueListLen-1)){
					if($IsClass){
						$EndArgs = '_Args'.$Loop;
						Write::line(','.$EndArgs.'/binary>> = '.$BeginArgs.',',$Handle);
					}else{
						//循环结束
						Write::line('>> = '.$BeginArgs.',',$Handle);
					}
				}else{
					Write::keep_a_line(",",$Handle);
				}
				$BodyPoint++;
			}
			self::bin_to_str($StringArray,$Handle,$Pos);
			self::bin_to_float($FloatArray,$Handle,$Pos);
			return $EndArgs;
		}
		
	
		public static function bin_to_str ($StringArray,$Handle,$Pos){
			foreach($StringArray as $TheString){
				foreach($TheString as $ArgName => $NeedChangeArgName){
					Write::line($ArgName.' = binary_to_list('.$NeedChangeArgName.'),',$Handle,$Pos);
				}
			}
		}
		
		public static function bin_to_float ($StringArray,$Handle,$Pos){
			foreach($StringArray as $TheString){
				foreach($TheString as $ArgName => $NeedChangeArgName){
					$FloatArg = 'Float_List_'.$ArgName;
					Write::line($FloatArg.' = binary_to_list('.$NeedChangeArgName.'),',$Handle,$Pos);
					Write::line($ArgName.' = my_list_to_float('.$FloatArg.'),',$Handle,$Pos);
				}
			}
		}
		
		public static function my_list_to_float ($Handle){
			Write::line('
			
my_list_to_float (X)->
	try list_to_float(X) of
		R ->
			R
	catch
		_ : _ ->
			list_to_integer(X)
	end.',$Handle);

		}
		
	}
?>