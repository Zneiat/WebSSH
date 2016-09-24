<?php
return [
    // 数据库配置
    'Database' => [
        'Connect' => true, # 是否连接？
        'Host' => '127.0.0.1', # 主机
        'User' => 'root', # 用户
        'Password' => '', # 密码
        'Port' => '3306', # 端口
        'DbName' => 'inos', # 数据库
    ],
    // 开启 Session
    'OpenSession' => true,
    // 命令类存放路径
    'CommandsClassPath' => 'app/commands/',
];