-module(game_router).

-export([route_request/2]).

route_request(<<Module:16/signed, Action:8/signed, Args/binary>>, State) ->    
	{Time1, _} = statistics(runtime),    
	{Time2, _} = statistics(wall_clock),    
	{M, A, NewState} =    route_request(Module, Action, Args, State),    
	{Time3, _} = statistics(runtime),    
	{Time4, _} = statistics(wall_clock),    
	Sec1 = (Time3 - Time1) / 1000.0,    
	Sec2 = (Time4 - Time2) / 1000.0,    
	game_prof_srv:set_info(M, A, Sec1, Sec2),    
	NewState.

route_request(101, _Action, _Args0, _State) ->     
	case _Action of        
		10101 ->                
			<<    
				Player_nameLen:32/signed, 
				Player_name_str:Player_nameLen/binary    ,   
				 Hash_codeLen:32/signed, 
				 Hash_code_str:Hash_codeLen/binary    ,    
				 Time:32/signed    ,    
				 SourceLen:32/signed, 
				 Source_str:SourceLen/binary    ,    
				 Stage:8/signed    ,    
				 TokenLen:32/signed, 
				 Token_str:TokenLen/binary
			>> = _Args0,            
			Player_name = binary_to_list(Player_name_str),            
			Source = binary_to_list(Source_str),            
			Token = binary_to_list(Token_str),            
			Float_List_Hash_code = binary_to_list(Hash_code_str),            
			Hash_code = my_list_to_float(Float_List_Hash_code),                
			NewState = api_player:login(    Player_name,    Hash_code,    Time,    Source,    Stage,    Token,_State),           
			{player,login,NewState}; 

		10116 ->                
			<<    
				New_nameLen:32/signed, 
				New_name_str:New_nameLen/binary    ,
				Size1:32/signed,Player_specs_Bin/binary
			>> = _Args0,            
			New_name = binary_to_list(New_name_str),            
			{Player_specs,_Args1} = game_router_class:class_player_player_specs(Size1,Player_specs_Bin,[]),                
			NewState = api_player:change_player_name(    New_name,    Player_specs,_State),            
			{player,change_player_name,NewState}    
	end.
			
my_list_to_float (X)->
	try list_to_float(X) of
		R ->
			R
	catch
		_ : _ ->
			list_to_integer(X)
	end.