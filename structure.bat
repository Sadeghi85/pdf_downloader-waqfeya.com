@echo off

%~d0
cd "%~dp0"

REM call php.exe -c php.ini -t ./src -S 127.0.0.1:8000
REM call php.exe -c php.ini -f ./src/index1.php
REM call php.exe -c php.ini -f ./src/index2.php
REM call php.exe -c php.ini -f ./src/index3.php
REM call php.exe -c php.ini -f ./src/index4.php
REM call php.exe -c php.ini -f ./src/test.php -- %1
call php.exe -c php.ini -f ./src/structure.php -- %1
REM call php.exe -c php.ini -f ./src/move.php -- %1

echo. && echo.
pause