<?php
namespace app;
use Exception;

class UserTmpData
{
    private $SaveDataSession = 'UserTmpData';
    
    public function __construct(){
        if(!Config('OpenSession')) die('Use User Setting Function Must Open Session.');
    }
    
    /**
     * 读取或查询Session保存数据
     * @param string null $Key
     * @param string|array null $Val
     * @return bool
     */
    public function Data($Key=null,$Val=null){
        if(!is_null($Key)&&!is_null($Val)){
            return $_SESSION[$this->SaveDataSession][$Key] = $Val;
        }
        if(is_null($Key)&&is_null($Key)) {
            return $_SESSION[$this->SaveDataSession];
        }
        return $_SESSION[$this->SaveDataSession][$Key];
    }
    
    public function IsNull($Key){
        if(!isset($_SESSION[$this->SaveDataSession][$Key])){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 销毁Session
     * @param $Key
     */
    public function DataDel($Key=null){
        if(is_null($Key)){
            unset($_SESSION[$this->SaveDataSession]);
        }else{
            unset($_SESSION[$this->SaveDataSession][$Key]);
        }
        
    }
    
    /**
     * 保存数组到Session
     * @param array $Data
     * @return bool
     * @throws Exception
     */
    public function SaveArrayData($Data){
        if(!is_array($Data)) throw new Exception('必须为数组');
        $_SESSION[$this->SaveDataSession] = $Data;
        return true;
    }
}