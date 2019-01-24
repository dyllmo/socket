-module(socket_client_srv).

-behaviour(gen_server).

-export([
	start_link/0,
	init/1,
	handle_call/3,
	handle_cast/2,
	handle_info/2
]).

-define(SERVER, ?MODULE).

start_link () ->
	gen_server:start_link(?MODULE, [], []). %% gen_server2:start_link({local,?MODULE},?MODULE,[],[]).

init (_Args) ->
	io:format("socket_client_srv It's ok!~n"),
	{ok, [], 0}. % 超时调用handle_info(timeout)
handle_cast ({test_one, Num}, State) ->
	io:format("go handle_cast/2 Num:~p~n", [Num]),
	{noreply, State}. %%{stop, normal, State}.调用terminate关闭进程

handle_call ({test_one, Num}, _From, State) ->
	io:format("go handle_call/3 Num:~p~n", [Num]),
	{reply, {ok, Num}, State}.

%% 创建完玩家进程，处理玩家发来的消息（init启动完进程后阻塞直到Pid绑定到该进程再接收消息----controlling_process----防止出现极端情况）
handle_info (timeout, State) ->
	receive
		{go, Socket} ->
			io:format("go receive Msg! Socket: ~p~n", [Socket]),
			ok
	end,
	{noreply, State};

handle_info ({tcp, Socket, Data}, State) ->
	io:format("socket_client_srv receive binary = ~p Socket: ~p~n ", [Data, Socket]),
	Str = binary_to_term(Data),
	io:format("socket_client_srv receive term = ~p~n", [Str]),
	{noreply, State};

handle_info ({tcp_closed, Socket}, State) ->
	io:format("socket close! Socket: ~p~n", [Socket]),
	{noreply, State}. %%{stop, normal, State}.调用terminate关闭进程

terminate(_Reason, _State) ->
    ok.

code_change(_OldVsn, State, _Extra) ->
    {ok, State}.

