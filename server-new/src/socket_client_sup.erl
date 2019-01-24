-module(socket_client_sup).

-behaviour(supervisor).

-export([
	start_link/0,
	init/1,
	start_child/0
]).

start_link () ->
	supervisor:start_link({local, ?MODULE}, ?MODULE, []).

init ([]) ->
	Id = socket_client_srv,
	StartFunc = {socket_client_srv, start_link, []},
	Restart = transient, 
	Shutdown = 16#ffffffff,
	Type = worker, 
	Module = [?MODULE],
	Specs = [{Id, StartFunc, Restart, Shutdown, Type, Module}],
	{ok, {{simple_one_for_one,10,10}, Specs}}.

start_child () ->
	supervisor:start_child(?MODULE, []).


