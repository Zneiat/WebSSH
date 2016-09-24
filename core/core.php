<?php
// 引入核心函数库
require_once (__DIR__.'/function.php');
// 引入核心配置文件
$Config = require_once (__DIR__.'/config.php');
define('Config',json_encode($Config)); # 转换配置文件为JSON并设置常量Config
// 连接数据库
$DbConfig = Config('Database');
if($DbConfig['Connect']) ConnectDb($DbConfig['Host'],$DbConfig['User'],$DbConfig['Password'],$DbConfig['Port'],$DbConfig['DbName']);
// 开启 Session
if(Config('OpenSession')) session_start();
// Base路径
define('__BasePath__',preg_replace("/(.*\/).*/", "$1", $_SERVER["SCRIPT_FILENAME"]));
// 使用用户类库
use app\Users;
$CoreUsers = new Users();