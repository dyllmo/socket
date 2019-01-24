<?php
	include_once("Conf.php");

	include_once("file_tool". DIRECTORY_SEPARATOR ."Write.php");
	include_once("c_protocol_tool" . DIRECTORY_SEPARATOR . "ProApiHrl.php");
	include_once("c_protocol_tool" . DIRECTORY_SEPARATOR . "ProApiOut.php");

	include_once("erlang_tool" . DIRECTORY_SEPARATOR . "ErlangHrl.php");
	include_once("erlang_tool" . DIRECTORY_SEPARATOR . "ApiErlangOut.php");
	include_once("erlang_tool" . DIRECTORY_SEPARATOR . "GameOutClass.php");
	include_once("erlang_tool" . DIRECTORY_SEPARATOR . "GameRouterClass.php");
	include_once("erlang_tool" . DIRECTORY_SEPARATOR . "GameRouter.php");

	header("Content-type:text/html;charset=utf-8");



	$FileNameList = read_protocol(); // 目录下所有协议的具体文件路径
	$ModInfoList = array(); // 所有文件
	foreach ($FileNameList as $FileName) { // 循环所有文件路径，每次读取一个文件
		Write::init_c($FileName);	// 读取文件返回文件流存储在静态变量
		Write::run_c($ModInfoList); // 解析文件流，返回值存储在ModInfoList
	}





	$InputChar = "";
	echo "input:\n";
	echo "	1 - xxxxxxxx  \n";
	echo "	2 - create server \n";
	echo "	3 - protocol tool \n";
	echo "	4 - xxxxxxxx  \n";
	echo "	5 - xxxxxxxx \n";
	echo "	x - exit \n";
	fscanf(STDIN, "%c", $InputChar);
	switch ($InputChar) {
		case '1':
			break;
		case '2':
			build_server($SignList,$ModInfoList,$MysqlServerName,$MysqlUsername,$MysqlPassword,$MysqlDatabase,$IsGameProf,$IsGameLog,$NeedSubTables,$IsWriteOnly,$CanFrag);
			break;
		case '3':
			build_protocol_tool($ModInfoList);
			break;
		default:
			break;
	}


	//----生成协议代码----
	function build_protocol_tool($ModInfoList){
		$ProGen = array();
		foreach ($ModInfoList as $ModInfo){ // 取一个文件
			array_push($ProGen, ProApiHrl::build_api_hrl($ModInfo));
			array_push($ProGen, ProApiOut::build_api_out($ModInfo));
		}
		echo 'ProGen:';
		var_dump($ProGen);
	}

	//-----生成服务端代码----
	function build_server($SignList, $ModInfoList, $MysqlServerName, $MysqlUsername, $MysqlPassword, $MysqlDatabase, $IsGameProf, $IsGameLog, $NeedSubTables, $IsWriteOnly, $CanFrag){
		foreach ($ModInfoList as $ModInfo) {
			ErlangHrl::build_erlang_hrl($ModInfo); //xx.hrl文件写入枚举，SUCCESS,0等等


			ApiErlangOut::build_erlang_api_out($ModInfo); //api_xx_out.erl，把所有out结果组成二进制流-------服务端通过调用api_xxx_out.erl把Result转成二进制流
			echo "ok\n";
		}
		GameOutClass::build(); //game_out_class.erl文件写入class自定义类型类的二进制流，out结果组成二进制流-------服务端通过调用api_xxx_out.erl把Result转成二进制流


		GameRouterClass::build(); //game_router_class.erl二进制流进来，解析in自定类型接口如list结构里的所有参数组成列表-----------in参数进来，通过game_router.erl调用对应的api
		GameRouter::build($ModInfoList, $IsGameProf);//game_router.erl二进制流进来，解析所有in参数----------in参数进来，通过game_router.erl调用对应的api
	}










































	// 返回文件夹里所有协议的完整文件相对路径
	function read_protocol(){
		$ProtocolDir = '..' . DIRECTORY_SEPARATOR . 'protocol' . DIRECTORY_SEPARATOR;
		$CurrentDir = opendir($ProtocolDir); // 打开目录
		$ProtocolArray = array();
		while (($file = readdir($CurrentDir)) !== false) { // 循环读目录，每次返回句柄的一个条目
			if(preg_match('/^.*txt$/', $file, $m)){

			}else{
				continue;
			}
			$sub_dir = $ProtocolDir . $file; // 建构文件完整路径，push进数组
			array_push($ProtocolArray, $sub_dir);
		}
		return $ProtocolArray;
	}


	// // 读取xml文件
 //    function modify_protool_csporj($ProGen){
 //        $FileName = '..'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool.csproj';
 //        $Dom = new DOMDocument('1.0', 'UTF-8');
 //        $Dom->load($FileName);  
 //        $ItemGroups = $Dom->getElementsByTagName("ItemGroup");  
 //        $Modify = false;
        
 //        foreach ($ItemGroups as $ItemGroup){  
 //            $Compiles = $ItemGroup->getElementsByTagName("Compile");

 //            if ($Compiles->length > 0){
 //                foreach ($ProGen as $Gen){
 //                    $Exist = false;
                    
 //                    foreach ($Compiles as $Compile){
 //                        $Attr = $Compile->getAttribute('Include');
                            
 //                        if ($Gen == $Attr){
 //                            $Exist = true;
 //                        }
 //                    }
                        
 //                    if (!$Exist){
 //                        $Modify = true;
 //                        $XmlCompile = $Dom->createElement('Compile');
 //                        $XmlCompile->setAttribute("Include", $Gen);
 //                        $ItemGroup->appendChild($XmlCompile);
 //                        $XmlText = $Dom->createTextNode("\n\t");
 //                        $ItemGroup->appendChild($XmlText);
 //                    }
 //                }
 //            }
 //        }
        
 //        if ($Modify){
 //            $Dom->save($FileName);
 //        }
 //    }




?>