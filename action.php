<?php
require_once ('core/core.php');
use app\AirOS;
$AirOS = new AirOS;
$Request = $AirOS->GetRequestCommand();
$Path = __DIR__.'/'.Config('CommandsClassPath');
if (!is_dir($Path)) { echo $AirOS->Response('系统错误：未找到命令类库文件夹','Error');die();}
/*mkdir($Path,0777);*/ // 创建文件夹test,并给777的权限（所有权限）
$CommandsIndexes = json_decode(file_get_contents($Path."/CommandsIndexes.json"),true);
// 循环引入所有命令类
$Dir = opendir($Path);
while ($File = readdir($Dir)) {
    if ($File != '.' && $File != '..' && is_file($Path.$File) && preg_match('/\.php$/is', $File)) {
        require_once ($Path.$File);
    }
}
closedir($Dir);
$Command = $Request[0];
if (isset($CommandsIndexes[$Command])) {
    $AirOS->HandleJsonRpc(new $CommandsIndexes[$Command]);
}else{
    $AirOS->ResponseError($Command);
}