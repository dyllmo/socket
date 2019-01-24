-module(socket_server_sup).

-behaviour(supervisor).

-export([
	start_link/0,
	init/1

]).

start_link () ->
	supervisor:start_link({local, ?MODULE}, ?MODULE, []). %启动督程，4个参数

% 配置督程下面挂的进程
init ([]) ->
	Opts = [
		binary,
		{packet, 0},
		{reuseaddr, true},
		{active, true}
	],
	{ok,ListenSocket} = gen_tcp:listen(2345, Opts), % listen只能一次

	ChildSpecs = lists:foldl(fun(I, Specs)->
		Id = list_to_atom("socket_listen" ++ integer_to_list(I)),
		Spec = {
			Id,
			{socket_server_srv, start_link, [ListenSocket]},
			transient, 
			16#ffffffff, 
			worker, 
			[?MODULE]

		},
		[Spec|Specs]
	end, [], lists:seq(1, 10) 
	),
	% ChildSpecs = [{socket_server_srv,{socket_server_srv, start_link, []},transient, 16#ffffffff, worker, [?MODULE]}],
	{ok, {{one_for_one, 1, 10}, ChildSpecs}}.




