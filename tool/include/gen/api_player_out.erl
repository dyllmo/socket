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
	<<Data_Bin_Size:32/signed, Data_Bin/binary>>.

change_player_name({    Result,    Player_item_list}) ->    
	BinList_Player_item_list = [game_out_class:class_player_player_item_list(Player_item_list_Item) || Player_item_list_Item <- Player_item_list],    
	Player_item_list_Len = length(Player_item_list),    
	Bin_Player_item_list = list_to_binary(BinList_Player_item_list),    
	Data_Bin = <<        	
				101:16/signed,        
				10116:8/signed,        
				Result:8/signed,        
				Player_item_list_Len:32/signed,        
				Bin_Player_item_list/binary    
			>>,    
	Data_Bin_Size = size(Data_Bin),    
	<<Data_Bin_Size:32/signed, Data_Bin/binary>>.