@echo off

%~d0
cd "%~dp0"

call php.exe -c php.ini -t ./src -S 127.0.0.1:8000
REM call php.exe -c php.ini -f ./src/index1.php -S 127.0.0.1:8000
REM call php.exe -c php.ini -f ./src/index2.php

echo. && echo.
pause