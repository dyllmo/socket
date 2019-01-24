<?php

	class GameRouter{
		public static function build($ModClassList,$IsGameProf=false){
			$FileName = 'game_router.erl';
			$Handle = Write::open_erlang_file($FileName);
			Write::line('-module(game_router).',$Handle);
			Write::line('-export([route_request/2]).',$Handle);
			Write::line('',$Handle);
			Write::line('route_request(<<Module:16/signed, Action:8/signed, Args/binary>>, State) ->',$Handle);
			if($IsGameProf){
				Write::line('{Time1, _} = statistics(runtime),',$Handle,1);
				Write::line('{Time2, _} = statistics(wall_clock),',$Handle,1);
				Write::line('{M, A, NewState} =    route_request(Module, Action, Args, State),',$Handle,1);
				Write::line('{Time3, _} = statistics(runtime),',$Handle,1);
				Write::line('{Time4, _} = statistics(wall_clock),',$Handle,1);
				Write::line('Sec1 = (Time3 - Time1) / 1000.0,',$Handle,1);
				Write::line('Sec2 = (Time4 - Time2) / 1000.0,',$Handle,1);
				Write::line('game_prof_srv:set_info(M, A, Sec1, Sec2),',$Handle,1);
			}else{
				Write::line('{_, _, NewState} =    route_request(Module, Action, Args, State),',$Handle,1);
			}
    
			Write::line('NewState.',$Handle,1);
			Write::line('',$Handle);
			//创建对应接口
			foreach ($ModClassList as $ModClass){
				self::resolution_function_list($ModClass,$Handle);
			}
			Write::handle_move($Handle,-1); // 原版2
			Write::line('.' ,$Handle);
			
			//字符串转换浮点数函数
			self::my_list_to_float($Handle);
			fclose($Handle);
		}
		
		public static function resolution_function_list($ModClass,$Handle){
			$ModName = $ModClass->ModName;
			$ModNum = $ModClass->ModNum;
			Write::line('route_request('.$ModNum.', _Action, _Args0, _State) -> ' ,$Handle);
			Write::line('case _Action of' ,$Handle,1);
			
			$BodyFunctionList = $ModClass->BodyFunctionList;
			foreach ($BodyFunctionList as $BodyFunction){
				self::resolution_function($BodyFunction,$ModClass,$Handle);
			}
			if (count($BodyFunctionList)>0){
				Write::handle_move($Handle,-1); // 原版2
			}
			Write::line('' ,$Handle);
			Write::line('end;' ,$Handle,1);
		}
		
		public static function resolution_function($BodyFunction,$ModClass,$Handle,$Pos=3){
			$ModName 		= $BodyFunction->ModHandle->ModName;
			$ApiModName 	= 'api_'.$ModName;
			$BodyNum 		= $BodyFunction->BodyNum;
			$BodyName 		= $BodyFunction->BodyName;
			$BodyValueList 	= $BodyFunction->BodyInValueList;
			Write::line($BodyNum.' ->' ,$Handle,2);
			$EndArgs = self::resolution_arg_list($BodyValueList,$Handle,$Pos);
			//调用对应的api
			Write::keep_a_line('NewState = '.$ApiModName.':'.$BodyName.'(',$Handle,$Pos);
			foreach ($BodyValueList as $BodyValue){
				$BodyValueName 	= $BodyValue->body_value_name;
				$ArgName 		= Write::initials($BodyValueName);
				Write::keep_a_line($ArgName.',',$Handle);
			}
			/*
			if (count($BodyValueList)>0){
				Write::handle_move($Handle,-1);
			}*/
			Write::line("_State),",$Handle);
			Write::line('{'.$ModName.','.$BodyName.',NewState};',$Handle,$Pos);
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
							Write::line('{['.$ArgName.'],'.$EndArgs.'} = game_router_class:'.$ErlangName.'(1,'.$ClassArgBin.',[]),',$Handle,$Pos);
							return $EndArgs;
						}else{
							Write::line($ClassSize.':32/signed,'.$ClassArgBin.'/binary>> = '.$BeginArgs.',',$Handle);
							self::bin_to_str($StringArray,$Handle,$Pos);
							self::bin_to_float($FloatArray,$Handle,$Pos);
							Write::line('{'.$ArgName.','.$EndArgs.'} = game_router_class:'.$ErlangName.'('.$ClassSize.','.$ClassArgBin.',[]),',$Handle,$Pos);
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