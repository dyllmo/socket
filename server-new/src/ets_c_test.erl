-module(ets_c_test).

-export([r/1,w/1,rw/1]).

-export([r_loop/3,w_loop/3,rw_loop/3]).

-define(ROW_COUNT,10000).

new (TableName, Options) ->
	case ets:info(TableName) of
		undefined ->
			ets:new(TableName,Options);
		_ ->
			ets:delete_all_objects(TableName)
	end.
	
init () ->
	init_1(),
	init_2().
	
init_1 () ->
	new(ets_1,[public,set,named_table,{keypos,1},{write_concurrency,true}]).
	
init_2 () ->
	new(ets_2,[public,set,named_table,{keypos,1}]).
	
start_wait (Count) -> 
	Self = self(),
	spawn(fun() -> wait(Self,0,Count,0) end).

wait (Self, Max, Max, Sum) -> Self ! {done,Sum};
wait (Self, Count, Max, Sum) ->
	receive
		{done,IT} -> 
			wait(Self,Count + 1,Max,Sum + IT)
	after 5000 ->
			Self ! timeout
	end.
	
end_wait () ->
	end_wait(60 * 1000).
	
end_wait (Timeout) ->
	receive
		{done,Sum} -> 
			Sum
	after Timeout ->
			exit(timeout)
	end.
	
r_loop (Table, I, T) ->
	{Time1, _} = statistics(wall_clock),
	lists:foreach(fun(_) -> ets:lookup(Table,I) end,lists:seq(1,T)),
	{Time2, _} = statistics(wall_clock),
	Time2 - Time1.

w_loop (Table, I, T) ->
	{Time1, _} = statistics(wall_clock),
	lists:foreach(fun(_) -> ets:insert(Table,{I,I}) end,lists:seq(1,T)),
	{Time2, _} = statistics(wall_clock),
	Time2 - Time1.
	
rw_loop (Table, I, T) ->
	{Time1, _} = statistics(wall_clock),
	lists:foreach(
		fun(_) -> 
			ets:insert(Table,{I,I}),
			ets:lookup(Table,I)
		end,
		lists:seq(1,T)
	),
	{Time2, _} = statistics(wall_clock),
	Time2 - Time1.


do (N, Action, Count) ->
	Inteval1 = do(Action,ets_1,N,Count), %% r_loop，ets_1，N, ?ROW_COUNT
	Inteval2 = do(Action,ets_2,N,Count), %% r_loop，ets_2，N, ?ROW_COUNT
	{Inteval1 + Inteval2,Inteval2 / Inteval1}.
	
do (F, Table, N, C) ->
	Pid = start_wait(N),
	lists:foreach(
		fun(I) -> 
			spawn(fun() -> IT = ?MODULE:F(Table,I,C),Pid ! {done,IT} end) 
		end,
		lists:seq(1,N)
	),
	end_wait().
	
r (N) ->
	init(), %% 表初始化
	lists:foreach(fun(I) -> ets:insert(ets_1,{I,I}) end,lists:seq(1,N)), %% 写入数据
	lists:foreach(fun(I) -> ets:insert(ets_2,{I,I}) end,lists:seq(1,N)), %% 写入数据
	do(N,r_loop,?ROW_COUNT).

w (N) ->
	init(),
	do(N,w_loop,?ROW_COUNT).
	
rw (N) ->
	init(),
	do(N,rw_loop,?ROW_COUNT div 2).