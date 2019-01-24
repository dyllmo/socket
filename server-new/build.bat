@echo off
cd ebin
erl -noshell -s make all -s init stop
pause