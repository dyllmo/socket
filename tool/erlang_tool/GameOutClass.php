<?php

	class GameOutClass{

		public static function build(){

			$Handle = Write::open_erlang_game_class("game_out_class");
			Write::line('-module(game_out_class).',$Handle);
			Write::line('',$Handle);
			Write::line('-compile(export_all).',$Handle);
			Write::line('',$Handle);
			$ClassList = ProtocolData::GetInstance()->get_out_class(); //  获得模块主对象class类列表
			foreach ($ClassList as $Class) {
				self::create_class($Class, $Handle);
			}
			fclose($Handle);

		}


		public static function create_class($Class, $Handle){
			$ErlangName = $Class->ErlangName; // 获得class的erlang全称=>"class_player_player_item_list"   FullName =>"player.player_item_list"
			$ClassValueList = $Class->get_value_list(); // 获取class类种的属性值对象
			Write::line($ErlangName . '({', $Handle);
			foreach ($ClassValueList as $ClassValue) {
				$BodyValueName = $ClassValue->body_value_name;
				 $Arg = Write::initials($BodyValueName); // 首字母大写
				 Write::line($Arg . ',', $Handle, 1);
			}
			$ClassValueCount = count($ClassValueList);
			if($ClassValueCount > 0){
				Write::handle_move($Handle, -1); // 原版2
			}
			Write::line('',$Handle);
			Write::line('})->',$Handle);

			// 整理需要处理的参数, 整成数组
			$ArgList = array();
			self::class_to_bin($ArgList, $ClassValueList, $Handle);
			
			// 序列所有数据， ArgList
			Write::line('<<',$Handle,1);
			self::data_to_bin($ArgList,$Handle);


			$ArgListLen = count($ArgList);
			if($ArgListLen>0){
				Write::handle_move($Handle,-1); // 原版2
			}
			Write::line('',$Handle);
			Write::line('>>.',$Handle,1);
			Write::line('',$Handle);

			
		}

		// 需要序列化的类
		public static function class_to_bin(&$ArgList, $ClassValueList, $Handle){
			foreach ($ClassValueList as $ClassValue) {
				$BodyValueName 		= $ClassValue->body_value_name;
				$BodyValueTypeName 	= $ClassValue->body_value_type_name;
				$BodyValueType		= $ClassValue->body_value_type;
				$Arg = Write::initials($BodyValueName);
				switch ($BodyValueTypeName){
					case 'int':
						array_push($ArgList,array($Arg,32,'signed'));
						continue;
					/*因为lua而改动
					case 'long':
						array_push($ArgList,array($Arg,64,'signed'));
						continue;
					*/
					case 'long':
						$LongListArg = 'Long_List_'.$Arg;
						$NewArg 	= 'Bin_'.$Arg;
						$NewArgLen 	= 'Bin_'.$Arg.'_Len';
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
							Write::line($NewArg.' = '.$ErlangName.'('.$Arg.'),',$Handle,1);
							array_push($ArgList,array($NewArg,0,'binary'));
							continue;
						}else{
							$BinList 	= 'BinList_'.$Arg;
							$NewArg 	= 'Bin_'.$Arg;
							$NewArgLen 	= $Arg.'_Len';
							Write::line($BinList.' = ['.$ErlangName.'('.$Arg.'_Item) || '.$Arg.'_Item <- '.$Arg.'],',$Handle,1);
							Write::line($NewArgLen.' = length('.$Arg.'),',$Handle,1);
							Write::line($NewArg.' = list_to_binary('.$BinList.'),',$Handle,1);
							array_push($ArgList,array($NewArgLen,32,'signed'));
							array_push($ArgList,array($NewArg,0,'binary'));
							continue;
						}
				}
			}
		}



		//序列化所有数据
		public static function data_to_bin($ArgList,$Handle){
			foreach ($ArgList as $ArgInfo){
				$Value 	= $ArgInfo[0];
				$Size 	= $ArgInfo[1];
				$Type 	= $ArgInfo[2];
				if($Size == 0){
					Write::line($Value.'/'.$Type.',',$Handle,2);
				}else{
					Write::line($Value.':'.$Size.'/'.$Type.',',$Handle,2);
				}
			}
		}
























	}


?>