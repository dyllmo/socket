<?php
	//是否统计erlang效率
	$IsGameProf = true;
	//数据库服务器名称
	$MysqlServerName="127.0.0.1";
	// 连接数据库用户名
    $MysqlUsername="root";
	// 连接数据库密码
    $MysqlPassword="mjmjmj";
	// 数据库的名字
    $MysqlDatabase="gamedb_hk";
	//lua path 文件路径
	$LuaPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'FeiYuMobileGame'.DIRECTORY_SEPARATOR.'download_src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR;
	//lua GameAction文件路径
	//$LuaGameActionPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'FeiYuMobileGame'.DIRECTORY_SEPARATOR.'download_src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR;
	//lua GameAction Mod文件路径
	//$LuaGameActionModPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'FeiYuMobileGame'.DIRECTORY_SEPARATOR.'download_src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'mod'.DIRECTORY_SEPARATOR;
	//lua out 发送信息文件路径
	//$LuaOutPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'FeiYuMobileGame'.DIRECTORY_SEPARATOR.'download_src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'out'.DIRECTORY_SEPARATOR;
	//c++ api_h文件路径
	$CPlusApiHPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'MingJiang'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'api_h'.DIRECTORY_SEPARATOR;
	//c++ api_cpp文件路径
	$CPlusApiCppPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'MingJiang'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR;
	//c++ api_out_h文件路径
	$CPlusApiOutHPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'MingJiang'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'out_h'.DIRECTORY_SEPARATOR;
	//c++ api_out文件路径
	$CPlusApiOutPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'MingJiang'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'out'.DIRECTORY_SEPARATOR;
	//c++ GameAction文件路径
	$CPlusGameActionPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'MingJiang'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'GameAction'.DIRECTORY_SEPARATOR;
	//c++ ApiData文件路径
	$CPlusApiDataPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'MingJiang'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR;

	//c# 协议调试工具路径
	$CProtocolToolPath = '..'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR;
	$CProtocolToolApiPath = '..'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR;
	$CProtocolToolOutPath = '..'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'ProtocolTool'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR.'out'.DIRECTORY_SEPARATOR;
	
	//c# game socket 文件路径
	$CsharpSocketPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'tcp'.DIRECTORY_SEPARATOR;
	//c# api out 文件路径
	$CSharpApiOutPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'tcp'.DIRECTORY_SEPARATOR.'api_out'.DIRECTORY_SEPARATOR;
	//c# api hrl 文件路径
	$CSharpApiHrlPath = '..'.DIRECTORY_SEPARATOR.'client'.DIRECTORY_SEPARATOR.'tcp'.DIRECTORY_SEPARATOR.'api_hrl'.DIRECTORY_SEPARATOR;
	
	
	//erlang api out 文件路径
	$ErlangApiOutPath = '..'.DIRECTORY_SEPARATOR.'server_new'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR;
	//erlang class 文件路径
	$ErlangGameClassPath = $ErlangApiOutPath ;
	//erlang api hrl 文件路径
	$ErlangApiHrlPath = '..'.DIRECTORY_SEPARATOR.'server_new'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'gen'.DIRECTORY_SEPARATOR;
	//erlang gamedb hrl 文件路径
	$ErlangGameDbHrlPath = '..'.DIRECTORY_SEPARATOR.'server_new'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR .'gen'.DIRECTORY_SEPARATOR;
	
	// 日志服务器的表,不在本服写入读取
	$IsGameLog = array(
		"player_name_change_log",
		"player_ingot_log",
        "player_coin_log",
        "player_power_log",
        "player_scroll_log",
        "player_military_log",
        "player_item_log",
        "player_exp_slot_log",
        "player_monster_soul_log",
        "player_spirit_pet_log",
		"player_main_level_log",
		"player_email_log",
		"player_role_grow_log",
		"player_equip_log",
		"player_equip_grow_log",
		"player_spirit_pet_grow_log",
        "player_assault_war_log",
        "player_mission_log",
		"player_login_log",
		"player_war_log",
		"player_guide_log",
        "player_arena_rank_log"
	);
	
	// 只需要写入不需要读取的表,可忽略不用生产到代码里面
	$IsWriteOnly = array(
		"db_version",
		"hero_comments_info",
		"chinese_text",
		"text_type",
		"load_screen",
		"load_map",
		"player_pay_log",
		"load_dialogue",
		"max_online",
        //"name_first",
        //"name_man_double",
        //"name_man_single",
        //"name_woman_double",
        //"name_woman_single",
        "player_war_report",
        "pass_word",
        "error_word",
        "mission_talk"
		/*"mission_section"*/
	);
	
	// 大部分player开头的表要分表,这些表除外
	$CanFrag = array(
        "player_arena_data",
        "player_arena_rank",
		"player_cdkey_gift",
		"player_cdkey_item",
        "player_entrust_data",
        "player_arena_award_time",
        "player_chaos_war_rank"
		/*"mission_section"*/
	);
	
	/* 标识模板生成,注意:
		1.主键必须为自增字段名为id;
		2.必须有标识字段名为sign;
		3.输入标识前缀比如物品表item->'I_' 或者ingot_change_type ->'ICT_';
		4.输入中文的字段比如中文在name_text_id或者在description_text_id
		5.中文描述不引用chinese_text的第四参数要加false,中文描述引用chinese的要加true
		6.没有中文解释就用2个参数即可,要用中文解释得4参数----
	*/
	
	/*
		array(
			'field'			=> "id"  	 默认宏定义的是id字段里面的值,可修改
			'table_name'	=> "item"    ***数据库表名***
			'sign'			=> "sign"	 默认标识字段,可修改
			'prefix_sign'	=> "I"		 ***宏定义标识前缀***
			'text'			=> ""		 解释说明字段名
			'from_chinese'  => true	 	 解释说明字段是否引用chinese_text
		)
	*/
	$SignList = array(
        array(
			'field'=>'id',
			'table_name'=>'hero_type',
			'sign'=>'sign',
			'prefix_sign'=>'HRT_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'rmb_gift_type',
			'sign'=>'sign',
			'prefix_sign'=>'RGT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'rmb_gift',
			'sign'=>'sign',
			'prefix_sign'=>'RG_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'item',
			'sign'=>'sign',
			'prefix_sign'=>'I_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'backpack_type',
			'sign'=>'sign',
			'prefix_sign'=>'BKT_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'item_type',
			'sign'=>'sign',
			'prefix_sign'=>'IT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'station',
			'sign'=>'sign',
			'prefix_sign'=>'STT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'item_color',
			'sign'=>'sign',
			'prefix_sign'=>'IC_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'role_type',
			'sign'=>'sign',
			'prefix_sign'=>'RT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'equip_attribute_type',
			'sign'=>'sign',
			'prefix_sign'=>'EAT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'skill_base',
			'sign'=>'sign',
			'prefix_sign'=>'SB_',
			'text'=>'name_text_id', 
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'skill_type',
			'sign'=>'sign',
			'prefix_sign'=>'ST_',
			'text'=>'description',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'red_state',
			'sign'=>'sign',
			'prefix_sign'=>'RS_',
			'text'=>'discription',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'buff',
			'sign'=>'sign',
			'prefix_sign'=>'BUFF_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'skill_attribute',
			'sign'=>'sign',
			'prefix_sign'=>'SAT_',
			'text'=>'description',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'skill_category',
			'sign'=>'sign',
			'prefix_sign'=>'SCT_',
			'text'=>'description',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'entrust_task_rate',
			'sign'=>'sign',
			'prefix_sign'=>'ETR_',
			'text'=>'description',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'entrust_task_attribute',
			'sign'=>'sign',
			'prefix_sign'=>'ETA_',
			'text'=>'description',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'server_data_type',
			'sign'=>'sign',
			'prefix_sign'=>'SDT_',
			'text'=>'decription',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'rmb_shop',
			'sign'=>'sign',
			'prefix_sign'=>'RMS_',
			'text'=>'discription',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'award_task_sign',
			'sign'=>'sign',
			'prefix_sign'=>'ATS_',
			'text'=>'discription',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'award_seven_day_data',
			'sign'=>'sign',
			'prefix_sign'=>'ASDD_',
			'text'=>'',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'server_value',
			'sign'=>'sign',
			'prefix_sign'=>'SV_',
			'text'=>'decription',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'hero',
			'sign'=>'sign',
			'prefix_sign'=>'H_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'title',
			'sign'=>'sign',
			'prefix_sign'=>'T_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'hero_color',
			'sign'=>'sign',
			'prefix_sign'=>'HC_',
			'text'=>'color_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'level',
			'table_name'=>'role_translate',
			'sign'=>'sign',
			'prefix_sign'=>'RTT_',
			'text'=>'',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'email_title_type',
			'sign'=>'sign',
			'prefix_sign'=>'EMAILT_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		//----------没有中文解释----
		array(
			'field'=>'id',
			'table_name'=>'daily_task',
			'sign'=>'sign',
			'prefix_sign'=>'DT_',
			'text'=>'title_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'role_lottery_prob_type',
			'sign'=>'sign',
			'prefix_sign'=>'RLPT_',
			'text'=>'',
			'from_chinese'=>false
		),
		array(
			'field'=>'up_exp',
			'table_name'=>'vip_level',
			'sign'=>'sign',
			'prefix_sign'=>'VL_',
			'text'=>'',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_job',
			'sign'=>'sign',
			'prefix_sign'=>'AGJ_',
			'text'=>'name_text_id', 
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'equip_attribute',
			'sign'=>'sign',
			'prefix_sign'=>'EA_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'war_attribute',
			'sign'=>'sign',
			'prefix_sign'=>'WA_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'magic_weapon',
			'sign'=>'sign',
			'prefix_sign'=>'ME_',
			'text'=>'',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'game_function',
			'sign'=>'sign',
			'prefix_sign'=>'FUN_',
			'text'=>'name',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'mission_main',
			'sign'=>'sign',
			'prefix_sign'=>'MMAIN_',
			'text'=>'',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'mission',
			'sign'=>'sign',
			'prefix_sign'=>'MISS_',
			'text'=>'',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'arena_rank_section',
			'sign'=>'sign',
			'prefix_sign'=>'ARS_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'elite_mission',
			'sign'=>'sign',
			'prefix_sign'=>'EMS_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'quest_need_type',
			'sign'=>'sign',
			'prefix_sign'=>'QNT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'scroll',
			'sign'=>'sign',
			'prefix_sign'=>'S_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'notify_player_message',
			'sign'=>'sign',
			'prefix_sign'=>'NPM_',
			'text'=>'message_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_event_moment',
			'sign'=>'sign',
			'prefix_sign'=>'AGEM_',
			'text'=>'title_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_achievement',
			'sign'=>'sign',
			'prefix_sign'=>'AGAT_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'game_first',
			'sign'=>'sign',
			'prefix_sign'=>'GF_',
			'text'=>'name',
			'from_chinese'=>false 
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_event_moment_type',
			'sign'=>'sign',
			'prefix_sign'=>'AGEMT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'mystery_shop_sale_type',
			'sign'=>'sign',
			'prefix_sign'=>'MSST_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_shop_sale_type',
			'sign'=>'sign',
			'prefix_sign'=>'AGSST_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_shop_goods_label',
			'sign'=>'sign',
			'prefix_sign'=>'AGSGL_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'spirit_pet_type',
			'sign'=>'sign',
			'prefix_sign'=>'SPT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'spirit_pet',
			'sign'=>'sign',
			'prefix_sign'=>'SP_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'spirit_pet_skill_type',
			'sign'=>'sign',
			'prefix_sign'=>'SPST_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'spirit_pet_lesser_attribute_grade',
			'sign'=>'sign',
			'prefix_sign'=>'SPLAG_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'spirit_pet_skill',
			'sign'=>'sign',
			'prefix_sign'=>'SPST_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'resource_log_type',
			'sign'=>'sign',
			'prefix_sign'=>'RLT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_guess_clue_type',
			'sign'=>'sign',
			'prefix_sign'=>'AGGCT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'sky_war_town_type',
			'sign'=>'sign',
			'prefix_sign'=>'SWTT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'sky_war_task',
			'sign'=>'sign',
			'prefix_sign'=>'SWST_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'sky_war_town',
			'sign'=>'sign',
			'prefix_sign'=>'SWT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'war_type',
			'sign'=>'sign',
			'prefix_sign'=>'WT_',
			'text'=>'name',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'equip_suit',
			'sign'=>'sign',
			'prefix_sign'=>'ES_',
			'text'=>'name_text_id',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'log_type',
			'sign'=>'sign',
			'prefix_sign'=>'GLT_',
			'text'=>'name',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'achievement_type',
			'sign'=>'sign',
			'prefix_sign'=>'ACHT_',
			'text'=>'',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'achievement',
			'sign'=>'sign',
			'prefix_sign'=>'ACH_',
			'text'=>'name',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'beacon_task_type',
			'sign'=>'sign',
			'prefix_sign'=>'BTT_',
			'text'=>'name',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'daily_target',
			'sign'=>'sign',
			'prefix_sign'=>'DTS_',
			'text'=>'`desc`',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'activity_window',
			'sign'=>'sign',
			'prefix_sign'=>'AW_',
			'text'=>'`describe`',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'shop_type',
			'sign'=>'sign',
			'prefix_sign'=>'SST_',
			'text'=>'`name`',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'chaos_war_section',
			'sign'=>'sign',
			'prefix_sign'=>'CWS_',
			'text'=>'`name`',
			'from_chinese'=>false
		),
        array(
			'field'=>'id',
			'table_name'=>'schedule',
			'sign'=>'sign',
			'prefix_sign'=>'SHL_',
			'text'=>'`name`',
			'from_chinese'=>false
		),
		array(
			'field'=>'id',
			'table_name'=>'army_group_boon',
			'sign'=>'sign',
			'prefix_sign'=>'AGB_',
			'text'=>'`name`',
			'from_chinese'=>false
		),
	);
	//强制分表
	$NeedSubTables = array(
		array('player_arena_send_server' => 'time_id'),
		array('player_arena_top_rank' => 'time_id'),
        array('player_assault_statistics' => 'diff'),
		array('player_server_hero_comments' => 'hero_id'),
		array('player_beacon_role' => 'player_beacon_id'),
		array('player_army_group_apply' => 'army_group_id'),
		array('player_army_group_boon' => 'army_group_id'),
		array('player_army_group_bless_record' => 'army_group_id'),
		array('player_army_group_bless_record_times' => 'army_group_id'),
		array('player_army_group_fantasyland' => 'army_group_id'),
		array('player_army_group_fantasyland_challenge_record' => 'army_group_id'),
		array('player_army_group_guess_clue' => 'army_group_id'),
		array('player_army_group_guess_clue_record' => 'army_group_id'),
		array('player_army_group_guess_data' => 'army_group_id'),
		array('player_army_group_guess_get_clue' => 'army_group_id'),
		array('player_army_group_guess_info' => 'army_group_id'),
		array('player_army_group_hero_stage_data' => 'army_group_id'),
		array('player_army_group_hero_stage_info' => 'army_group_id'),
		array('player_army_group_info' => 'army_group_id'),
		array('player_army_group_main_role' => 'army_group_id'),
		array('player_army_group_main_role_record' => 'bb_player_id'),
		array('player_army_group_member' => 'army_group_id'),
		array('player_army_group_member_moment' => 'army_group_id'),
		array('player_army_group_moment_comment' => 'moment_id'),
		array('player_army_group_moment_like' => 'moment_id'),
		array('player_army_group_moment_pic' => 'moment_id'),
		array('player_army_group_response' => 'army_group_id'),
		array('player_army_group_response_answer_mark' => 'army_group_id'),
		array('player_army_group_response_info' => 'army_group_id'),
		array('player_army_group_response_record' => 'army_group_id'),
		array('player_friends' => 'player_id'),
		array('player_vip' => 'userid'),
		array('player_sky_war_c_data' => 'army_group_id'),
		array('player_sky_war_c_score' => 'army_group_id'),
		array('player_sky_war_l_attend' => 'army_group_id'),
		array('player_sky_war_s_aginfo_cache' => 'army_group_id'),
		array('player_sky_war_s_attend' => 'army_group_id'),
		array('player_sky_war_s_data' => 'group_id'),
		array('player_sky_war_s_def_deploy' => 'army_group_id'),
		array('player_sky_war_s_fight_record' => 'army_group_id'),
		array('player_sky_war_s_fire_flag' => 'army_group_id'),
		array('player_sky_war_s_info' => 'group_id'),
		array('player_sky_war_s_group_data' => 'group_id'),
		array('player_sky_war_s_rt_war_info' => 'group_id'),
		array('player_sky_war_s_town' => 'group_id'),
		array('player_sky_war_s_town_record' => 'army_group_id')
	);
	
	$GameConfig =array(
		'hk_test'=>array(
			'app_id' 		=>80015,
			'app_key'		=>"f44307a8a7faeabbe5c8f7f10073b0d4",
			'recharge_key'	=>"4dcf8a16367ef166b4114a8735c2d57d",
		),
		'hk_ios'=>array(
			'app_id' 		=>80017,
			'app_key'		=>"928ee39e17240da6d20d26551edf5b53",
			'recharge_key'	=>"5065279ea5dd08dadd7a2cb70191786e",
		),
		'hk'=>array(
			'app_id' 		=>80015,
			'app_key'		=>"f44307a8a7faeabbe5c8f7f10073b0d4",
			'recharge_key'	=>"4dcf8a16367ef166b4114a8735c2d57d",
		),
		'ios_check'=>array(
			'app_id' 		=>80017,
			'app_key'		=>"928ee39e17240da6d20d26551edf5b53",
			'recharge_key'	=>"5065279ea5dd08dadd7a2cb70191786e",
		),
		'branch'=>array(
			'app_id' 		=>80015,
			'app_key'		=>"f44307a8a7faeabbe5c8f7f10073b0d4",
			'recharge_key'	=>"4dcf8a16367ef166b4114a8735c2d57d",
		)
	);
?>
