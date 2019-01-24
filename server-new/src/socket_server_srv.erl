-module(socket_server_srv).

-behaviour(gen_server).

-export([
	start_link/1,
	init/1,
	handle_call/3,
	handle_cast/2,
	handle_info/2
]).

-export([
	acceptor_loop/1
]).

start_link (ListenSocket) ->
	gen_server:start_link(?MODULE, ListenSocket, []). %5个参数，最后一个超时时间

init (ListenSocket) ->	
	io:format("socket_server_srv It's ok!~n"),
	{ok, ListenSocket, 0}.

handle_cast ({test_one, Num}, State) ->
	io:format("go handle_cast/2 Num:~p~n", [Num]),
	{noreply, State}.

handle_call ({test_one, Num}, _From, State) ->
	io:format("go handle_call/3 Num:~p~n", [Num]),
	{reply, {ok, Num}, State}.

handle_info (timeout, ListenSocket) ->
	acceptor_loop(ListenSocket).

acceptor_loop (ListenSocket) ->	
	{ok, Socket} = gen_tcp:accept(ListenSocket), % 阻塞，直到监听到，往下
	handle_connection(Socket),	% 处理完成，往下
	acceptor_loop(ListenSocket).

% 传递给socket_client处理
handle_connection (Socket) ->
	{ok, Pid} = socket_client_sup:start_child(),
	gen_tcp:controlling_process(Socket, Pid),
	Pid ! {go, Socket}.


