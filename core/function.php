<?php
/**
 * Class 自动加载
 * @param $class_name
 */
function __autoload($class_name) {
    require_once("$class_name.php");
}

/**
 * 快速连接数据库
 * @param $Host
 * @param $User
 * @param $Password
 * @param $Port
 * @param $DbName
 * @return resource
 */
function ConnectDb($Host,$User,$Password,$Port,$DbName){
    $Conn = @mysql_connect($Host,$User,$Password,$Port);
    @mysql_select_db($DbName);
    @mysql_query("set NAMES 'utf8'");
    if(mysql_errno()) die('The Database Connection Error,Please Check The Connection Character String.');
    return $Conn;
}

/**
 * 将 mysql_query() 输出为Array数据
 * @param $Query
 * @return array
 */
function MySqlQueryDataToArray($Query){
    $Data = array();
    while($Row = mysql_fetch_array($Query)) $Data[] = $Row;
    return $Data;
}

/**
 * 获取核心配置文件(config.php)内容
 * @param $WhatConfigContent
 * @return mixed
 */
function Config($WhatConfigContent){
    $ConfigArray = json_decode(Config,true);
    return $ConfigArray[$WhatConfigContent];
}

/**
 * 随机生成IP地址
 * @return string
 */
function RandIpAddr(){
    $IpLong = array(
        array('607649792', '608174079'), // 36.56.0.0-36.63.255.255
        array('1038614528', '1039007743'), // 61.232.0.0-61.237.255.255
        array('1783627776', '1784676351'), // 106.80.0.0-106.95.255.255
        array('2035023872', '2035154943'), // 121.76.0.0-121.77.255.255
        array('2078801920', '2079064063'), // 123.232.0.0-123.235.255.255
        array('-1950089216', '-1948778497'), // 139.196.0.0-139.215.255.255
        array('-1425539072', '-1425014785'), // 171.8.0.0-171.15.255.255
        array('-1236271104', '-1235419137'), // 182.80.0.0-182.92.255.255
        array('-770113536', '-768606209'), // 210.25.0.0-210.47.255.255
        array('-569376768', '-564133889'), // 222.16.0.0-222.95.255.255
    );
    $RandKey = mt_rand(0, 9);
    return long2ip(mt_rand($IpLong[$RandKey][0], $IpLong[$RandKey][1]));
}