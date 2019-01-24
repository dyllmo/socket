# SmallMouse 

#### 项目介绍

Erlang game server 预设想打造一个简单但是完整的、基于Erlang语言的分布式游戏服务器，从底层开始，0-1完整的过程

#### 设想的服务器分为以下几大块

	1.底层协议：
		协议规则->
			包括api_xxx_out.erl // 将输出result转为OutBin二进制流、
			包括game_router.erl // 服务器路由，将接收的二进制包转为具体参数，调用对应的api_xxx（arg1, arg2...）接口
		协议脚本实现
			首先, 存储所以协议txt文本: 每个协议文件是一个ModClass模块对象、所有的协议文件模块主对象存储在ModInfoList
			其次, 生成对应的协议代码
			最后, 生成服务端代码: hrl文件写入枚举类、api_xxx_out.erl封包、game_router.erl解包
			
	2.socket通讯基础：
		基础的客户端服务器socket通讯
		以监督树supervisor、gen-server为基础的socket通讯（监听池）
	
	3.模块功能开发
	
	4.日志系统
	
	5.数据库系统

