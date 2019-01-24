-module(sort).
-compile(export_all).

%%-----------------------------------------------------------------------------------------------
%% @doc
%% bubble_once用于处理依次冒泡排序，两两比较将最大元素移到最后，然后再对N-1个元素使用bubble_once进行冒泡。O(N2)
%% @end
%%-----------------------------------------------------------------------------------------------
bubble_once(Max,[H|T]) ->  
    if 
    	Max > H ->  
            [H] ++ bubble_once(Max,T);  
        true ->  
            [Max] ++ bubble_once(H,T)
    end; 
bubble_once(H,[]) -> [H].

bubble_sort(L) -> 
	bubble_sort(L,length(L)-1).  
bubble_sort([H|T],N) ->   
    Result = bubble_once(H,T),  
    io:format("Result is  ~p~n",[Result]),  
    bubble_sort(Result,N-1);
bubble_sort(L,0) -> 
	L.

%%-----------------------------------------------------------------------------------------------
%% @doc
%% 快速排序O(NlogN)，最差O(N2),顺序或逆序时最差
%% 把第一个数当做基准值，让基准数处在中间
%% 基础数在哪边，哪边的下标不动，另一边下一次++，最后基准数处在中间
%% @end
%%-----------------------------------------------------------------------------------------------
qsort([]) -> [];

qsort([Pivot|T]) ->
	qsort([X || X <- T,X < Pivot]) ++ [Pivot] ++ qsort([X || X <-T,X >= Pivot]).


%%-----------------------------------------------------------------------------------------------
%% @doc 
%% 插入排序，insert_sort(L1,L2)将L2依次插入L1中,L1默认为空。normal函数实现一遍插入排序。O(N2)
%% @end
%%-----------------------------------------------------------------------------------------------
insert_sort(L) -> 
	insert_sort([],L).
insert_sort(L,[]) -> 
	L;
insert_sort(L,[H|T]) ->
    insert_sort(normal(H,L),T).

normal(X,[]) -> [X];
normal(X,[H|T]) ->
    if X > H ->
            [H|normal(X,T)];
        true ->
            [X|[H|T]]
    end.