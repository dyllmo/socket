#!/bin/sh
ulimit -n 1000000
erl                                                 \
-boot            start_sasl                         \
-pa              ebin                            	\
-s               game start                         \
-name            socket@127.0.0.1                     \
-setcookie       the_cookie                         \
-config          start_config/start_game            \
-env             ERL_MAX_ETS_TABLES 65535           \
-game                                               \
 sid             '1'                                \
 tid             '2'                                \
 cid             '1'                                \
 bid             '0'                                \
 server_port     '8890'                             \
 mysql_host      '"127.0.0.1"'                      \
 mysql_port      '3306'                             \
 mysql_username  '"root"'                           \
 mysql_password  '"mjmjmj"'                         \
 mysql_database  '"game_db"'                        \
 build_code_db	'true'								\
