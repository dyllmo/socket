-module(game).

-behaviour(application).

-behaviour(supervisor).

-export([
	start/0,
	init/1,
	start/2,
	stop/1,
	start_child/3
]).

start () ->
	application:start(?MODULE). %%-----启动应用程序，应用控制器会为应用程序 Application 生成一个主应用程序
								%%-----主应用程序通过调用定义在应用描述文件键 mod 里的回调函数 Module:start/2 来启动

start (_Type, _Args) ->
	Result = supervisor:start_link({local, ?MODULE}, ?MODULE, []), % 启动game督程，回调init

	start_child(socket_client_sup, infinity, supervisor),	% 启动game下面的督程
	start_child(socket_server_sup, infinity, supervisor),	% 启动game下面的督程


	Result.

init ([]) ->
	{ok, {{one_for_one, 10, 10}, []}}. % 策略为空?因为start_child使用了策略，策略格式为[Specwork1,Specwork2]

%% 启动game督程下面的子进程
start_child (Module, ShutDown, Type) ->
	Id = Module,
	StartFunc = {Module, start_link, []},
	Restart = permanent,
	Type = supervisor,
	Modules = [Module],
	ChildSpecs = {Id, StartFunc, Restart, ShutDown, Type, Modules},
	SupervisorName = ?MODULE,
	{ok, _} = supervisor:start_child(SupervisorName, ChildSpecs).% 这边的策略格式不用[]，策略不为空则调用这里的，init要为空

stop (_State) ->
	ok.

