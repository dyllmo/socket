-module(game_out_class).
-compile(export_all).
class_player_player_item_list({    Player_item_id,    Item_id,    Number})->    
	<<        
		Player_item_id:32/signed,        
		Item_id:32/signed,       
		Number:32/signed    
	>>.