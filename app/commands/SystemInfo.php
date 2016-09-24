<?php
use app\Users;
use app\UserTmpData;
class SystemInfoCommands {
    public function arch(){
        return 'X86_64';
    }
    
    public function uname($Parame=null){
        if (is_null($Parame)) $Parame = '-s';
        $Data['-a'] = 'AirCore AirHost_01 Build0003.x86_64 Fri Aug 26 21:06:56 UTC+8 2016 Aircore';
        $Data['-s'] = 'AirCore';
        $Data['-n'] = 'AirHost_01';
        $Data['-r'] = 'Build0003.x86_64';
        $Data['-v'] = 'Fri Aug 26 21:06:56 UTC+8 2016';
        $Data['-m'] = 'x86_64';
        $Data['-p'] = 'x86_64';
        $Data['-i'] = 'x86_64';
        $Data['-o'] = 'Aircore';
        $Data['--help'] = 'Sorry, i am a lazy boy';
        if(!isset($Data[$Parame])){
            throw new Exception('Try `uname --help\' for more information.');
        }else{
            return $Data[$Parame];
        }
    }
    
    public function shutdown($Parame=null){
        (new Users()) ->Logout();
        (new UserTmpData()) -> DataDel();
        return '关机中...<script>window.location.reload();</script>';
    }
    
    public function logout(){
        (new Users()) ->Logout();
        (new UserTmpData()) -> DataDel();
        return '注销中...<script>window.location.reload();</script>';
    }
    
    public function reboot(){
        return '无法执行此命令';
    }
}