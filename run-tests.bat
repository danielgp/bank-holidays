@echo off
SETLOCAL ENABLEEXTENSIONS
SET me=%~n0
SET parent=%~dp0
C:\www\App\PHP\7.2.x64\php.exe -c C:\www\Config\PHP\7.2.x64\php.ini %parent%vendor\phpunit\phpunit\phpunit --configuration %parent%phpunit.xml
