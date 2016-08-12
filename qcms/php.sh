#!/bin/sh

echo  "开启php 内置web服务  （linux版本）"
nohup php -S '0.0.0.0:8080' &
echo "服务器 进程 ID "
ps -f | grep 'php -S 0.0.0.0:8080'

echo 'http://0.0.0.0:8080'