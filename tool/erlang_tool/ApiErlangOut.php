<?php


/* 参考
-module(api_player_out).
-export([    login/1,    change_player_name/1]).

login({    Result,    Player_id,    Is_minor_account,    Enable_time}) ->    
	Bin_Result = list_to_binary(Result),    
	Bin_Result_Len = size(Bin_Result),    
	Long_List_Player_id = integer_to_list(Player_id),    
	Bin_Player_id = list_to_binary(Long_List_Player_id),    
	Bin_Player_id_Len = size(Bin_Player_id),    
	Bin_Is_minor_account = list_to_binary(Is_minor_account),    
	Bin_Is_minor_account_Len = size(Bin_Is_minor_account),    
	Data_Bin = <<        
				101:16/signed,        
				10101:8/signed,        
				Bin_Result_Len:32/signed,        
				Bin_Result/binary,        
				Bin_Player_id_Len:32/signed,        
				Bin_Player_id/binary,        
				Bin_Is_minor_account_Len:32/signed,        
				Bin_Is_minor_account/binary,        
				Enable_time:32/signed    
			>>,    
	Data_Bin_Size = size(Data_Bin),    
	<<Data_Bin_Size:32/signed, Data_Bin/binary>>.*/



	class ApiErlangOut{

		public static function build_erlang_api_out($ModClass){
			$ClassList = $ModClass->get_class_list(); // 获得模块主对象的class列表
			$Handle = Write::open_erlang_api_out($ModClass->ModName); // 
			Write::line('-module(api_' . $ModClass->ModName . '_out).', $Handle);
			// 写入函数接口，export
			self::create_export($ModClass, $Handle);
			// 具体写处理每个函数
			self::create_function_list($ModClass, $Handle);
			fclose($Handle);
		}

		// 写入函数接口export
		public static function create_export($ModClass, $Handle){
			$BodyFunctionList = $ModClass->BodyFunctionList();
			Write::line('-export([',$Handle);
			foreach ($BodyFunctionList as $BodyFunction){
				$BodyName 			= $BodyFunction->BodyName;
				Write::line($BodyName.'/1,',$Handle,1);
				
			}
			$FunctionCount = count($BodyFunctionList);
			if($FunctionCount>0){
				Write::handle_move($Handle,-1); // 原版是2
			}
			Write::line('',$Handle);
			Write::line(']).',$Handle);
		}

		// 具体写每个函数
		public static function create_function_list($ModClass, $Handle){
			$BodyFunctionList = $ModClass->BodyFunctionList();
			foreach($BodyFunctionList as $BodyFunction){
				self::create_function($BodyFunction, $ModClass, $Handle);		}
		}

		// 具体的创建逻辑
		public static function create_function($BodyFunction, $ModClass, $Handle){
			$BodyName = $BodyFunction->BodyName;
			$BodyOutValueList = $BodyFunction->BodyOutValueList;
			Write::line($BodyName . '({', $Handle);


			//----接口参数逻辑----
			foreach ($BodyOutValueList as $BodyOutValue) {
				$BodyValueName = $BodyOutValue->body_value_name;
				$Arg = Write::initials($BodyValueName);
				Write::line($Arg . ',', $Handle, 1);
			}
			$BodyOutValueCount = count($BodyOutValueList);
			if($BodyOutValueCount > 0){
				Write::handle_move($Handle, -1); // 原版是2
			}
			Write::line('', $Handle);
			Write::line('}) ->', $Handle);



			//-----函数体内部逻辑----
			// 需要处理的参数数组
			$ArgList = array();
			// 需要经过序列化处理的类
			self::class_to_bin($ArgList, $BodyOutValueList, $Handle); // out属性		



			// 序列化所有数据， 从模块号-接口号到参数
			Write::line('Data_Bin = <<', $Handle, 1);
			$ModNum = $ModClass->ModNum;
			Write::line($ModNum . ':16/signed,', $Handle, 2);
			$BodyNum = $BodyFunction->BodyNum;
			Write::line($BodyNum . ':8/signed,', $Handle, 2);
			self::data_to_bin($ArgList, $Handle);
			Write::handle_move($Handle, -1); // 原版2
			Write::line('', $Handle);
			Write::line('>>,', $Handle, 1);
			Write::line('Data_Bin_Size = size(Data_Bin),', $Handle, 1);
			Write::line('<<Data_Bin_Size:32/signed, Data_Bin/binary>>.', $Handle, 1);
			Write::line('', $Handle);
		}

		//需要经过序列化处理的类, 传入BodyOutValueList列表，将其序列化，储存在ArgList
		public static function class_to_bin(&$ArgList, $ClassValueList, $Handle){
			foreach ($ClassValueList as $ClassValue) {
				$BodyValueName = $ClassValue->body_value_name;
				$BodyValueTypeName = $ClassValue->body_value_type_name;
				$BodyValueType = $ClassValue->body_value_type;
				$Arg = Write::initials($BodyValueName); // 首字母大写
				switch ($BodyValueTypeName) {
					case 'int':
						array_push($ArgList, array($Arg, 32, 'signed'));
						continue;
					
					case 'long':
						$LongListArg = 'Long_List_' . $Arg;
						$NewArg = 'Bin_' . $Arg;
						$NewArgLen = 'Bin_' . $Arg . '_Len';
						Write::line($LongListArg.' = integer_to_list('.$Arg.'),',$Handle,1);
						Write::line($NewArg.' = list_to_binary('.$LongListArg.'),',$Handle,1);
						Write::line($NewArgLen.' = size('.$NewArg.'),',$Handle,1);
						array_push($ArgList,array($NewArgLen,32,'signed'));
						array_push($ArgList,array($NewArg,0,'binary'));
						continue;
					case 'byte':
						array_push($ArgList,array($Arg,8,'signed'));
						continue;
					case 'short':
						array_push($ArgList,array($Arg,16,'signed'));
						continue;
					case 'enum':
						array_push($ArgList,array($Arg,8,'signed'));
						continue;
					case 'string':
						$NewArg 	= 'Bin_'.$Arg;
						$NewArgLen 	= 'Bin_'.$Arg.'_Len';
						Write::line($NewArg.' = list_to_binary('.$Arg.'),',$Handle,1);
						Write::line($NewArgLen.' = size('.$NewArg.'),',$Handle,1);
						array_push($ArgList,array($NewArgLen,32,'signed'));
						array_push($ArgList,array($NewArg,0,'binary'));
						continue;
					case 'float':
						$FloatListArg = 'Float_List_'.$Arg;
						$NewArg 	= 'Bin_'.$Arg;
						$NewArgLen 	= 'Bin_'.$Arg.'_Len';
						Write::line($FloatListArg.' = float_to_list('.$Arg.'),',$Handle,1);
						Write::line($NewArg.' = list_to_binary('.$FloatListArg.'),',$Handle,1);
						Write::line($NewArgLen.' = size('.$NewArg.'),',$Handle,1);
						array_push($ArgList,array($NewArgLen,32,'signed'));
						array_push($ArgList,array($NewArg,0,'binary'));
						continue;
					case 'double':
						$DoubleListArg = 'Double_List_'.$Arg;
						$NewArg 	= 'Bin_'.$Arg;
						$NewArgLen 	= 'Bin_'.$Arg.'_Len';
						Write::line($DoubleListArg.' = float_to_list('.$Arg.'),',$Handle,1);
						Write::line($NewArg.' = list_to_binary('.$DoubleListArg.'),',$Handle,1);
						Write::line($NewArgLen.' = size('.$NewArg.'),',$Handle,1);
						array_push($ArgList,array($NewArgLen,32,'signed'));
						array_push($ArgList,array($NewArg,0,'binary'));
						continue;
					default:
						$ErlangName = $ClassValue->body_class_erlang_name;
						$ErlangName	=Write::toErlangName($ErlangName);
						if($BodyValueType == "typeof"){
							$NewArg 	= 'Bin_'.$Arg;
							Write::line($NewArg.' = game_out_class:'.$ErlangName.'('.$Arg.'),',$Handle,1);
							array_push($ArgList,array($NewArg,0,'binary'));
							continue;
						}else{
							$BinList 	= 'BinList_'.$Arg;
							$NewArg 	= 'Bin_'.$Arg;
							$NewArgLen 	= $Arg.'_Len';
							Write::line($BinList.' = [game_out_class:'.$ErlangName.'('.$Arg.'_Item) || '.$Arg.'_Item <- '.$Arg.'],',$Handle,1);
							Write::line($NewArgLen.' = length('.$Arg.'),',$Handle,1);
							Write::line($NewArg.' = list_to_binary('.$BinList.'),',$Handle,1);
							array_push($ArgList,array($NewArgLen,32,'signed'));
							array_push($ArgList,array($NewArg,0,'binary'));
							continue;
						}
				}
			}
		}


		// 序列化所有数据
		public 	static function data_to_bin($ArgList, $Handle){
			foreach ($ArgList as $ArgInfo) {
				$Value = $ArgInfo[0];
				$Size = $ArgInfo[1];
				$Type = $ArgInfo[2];
				if($Size == 0){
					Write::line($Value . '/' . $Type . ',', $Handle, 2);
				}else{
					Write::line($Value . ':' . $Size . '/' . $Type . ',' , $Handle, 2);
				}
			}
		}


































	}
	
?>