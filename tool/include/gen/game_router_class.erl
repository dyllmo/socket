-module(game_router_class).
-compile(export_all).

class_player_player_specs(0, _Args, Result) ->    
	{lists:reverse(Result), _Args};

class_player_player_specs (_Count, _Args0, _Result) ->        
	<<    
		Player_item_id:32/signed    ,    
		Item_id:32/signed    ,    
		NumberLen:32/signed, 
		Number_str:NumberLen/binary,
		_Args1/binary
	>> = _Args0,    
	Number = binary_to_list(Number_str),        
	_NewItem = {    Player_item_id,    Item_id,    Number},    
	class_player_player_specs(_Count-1, _Args1, [_NewItem | _Result]).
			
my_list_to_float (X)->
	try list_to_float(X) of
		R ->
			R
	catch
		_ : _ ->
			list_to_integer(X)
	end.