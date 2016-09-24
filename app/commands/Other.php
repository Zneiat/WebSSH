<?php
use app\Users;
use app\UserTmpData;
class OtherCommands {
    public function cupdate(){
        // 循环引入所有命令类
        $Path = __BasePath__.'/'.Config('CommandsClassPath');
        if (!is_dir($Path)) throw new Exception("系统错误：未找到命令类库文件夹");
        /*mkdir($Path,0777);*/ // 创建文件夹test,并给777的权限（所有权限）
        $Dir = opendir($Path);
        $ClassName = array();
        while ($File = readdir($Dir)) {
            if ($File != '.' && $File != '..' && is_file($Path.$File) && preg_match('/\.php$/is', $File)) {
                require_once ($Path.$File);
                $ClassName[] = basename($File,'.php').'Commands';
            }
        }
        closedir($Dir);
        // 生成命令索引
        $AllClssMethodData = array();
        foreach ($ClassName as $Name) {
            $Class = new $Name;
            $Class = get_class($Class);
            $Methods = get_class_methods($Class);
            foreach ($Methods as $Method) {
                $AllClssMethodData["$Method"] = $Class;
            }
        }
        // JSON格式存入文件
        $JsonContent = json_encode($AllClssMethodData);
        $JsonFile = $Path."/CommandsIndexes.json";
        $Fp = @fopen($JsonFile, 'w');
        fwrite($Fp, $JsonContent);
        fclose($Fp);
        return '索引更新完毕，预览：<br/>'.$JsonContent;
    }
}