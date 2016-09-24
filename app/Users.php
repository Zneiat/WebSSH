<?php
namespace app;
use Exception;
class Users
{
    // 角色,地位
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;
    
    /**
     * Class constructor.
     */
    public function __construct(){
        if(!Config('Database')['Connect']) die('Use Users Function Must Open Database Connect.');
        if(mysql_num_rows(mysql_query('SHOW TABLES LIKE \'users\''))!=1) die('Users Database Table Not Found.');
        if(!Config('OpenSession')) die('Use Users Function Must Open Session.');
    }
    
    /**
     * 登录
     * @param $Username
     * @param $Password
     * @return array|null
     * @throws Exception
     */
    public function Login($Username,$Password){
        $Username = addslashes(htmlspecialchars(trim($Username)));
        $Password = md5(trim($Password));
        $Query = mysql_query("SELECT * FROM `users` WHERE `username`='$Username' and `password`='$Password' limit 1");
        if($Result = mysql_fetch_array($Query)){
            $_SESSION['userid'] = $Result['id'];
            return $Result;
        }else{
            throw new Exception('用户名或密码错误');
        }
    }
    
    /**
     * 新增
     * @param $Username
     * @param $Password
     * @param int $Role
     * @return resource
     * @throws Exception
     */
    public function Add($Username,$Password,$Role=self::ROLE_USER){
        $Username = addslashes(htmlspecialchars(trim($Username)));
        $Password = md5(trim($Password));
        $Sql = "INSERT INTO `users` (`username`,`password`,`role`) VALUES ('$Username','$Password','$Role')";
        return mysql_query($Sql);
    }
    
    /**
     * 更新
     * @param $UserID
     * @param null $Username
     * @param null $Password
     * @param null $Role
     * @return bool|resource
     * @throws Exception
     */
    public function Update($UserID,$Username=null,$Password=null,$Role=null){
        $UserID = intval($UserID);
        $Username = addslashes(htmlspecialchars(trim($Username)));
        $Password = md5(trim($Password));
        $Query = mysql_query("SELECT * FROM `users` WHERE `id`='$UserID' limit 1");
        if(mysql_fetch_array($Query)){
            if(!is_null($Username)||!is_null($Password)||!is_null($Password)){
                $Set = 'SET';
                if(!is_null($Username)) $Set .= " `username` =  '$Username',";
                if(!is_null($Password)) $Set .= " `password`= '$Password',";
                if(!is_null($Password)) $Set .= " `role`= '$Role',";
                $Sql = "UPDATE `users` ".rtrim($Set,',')." WHERE `id` =1";
                return mysql_query($Sql);
            }else{
                throw new Exception('保存失败');
            }
        }else{
            throw new Exception('未找到该用户');
        }
    }
    
    /**
     * 删除
     * @param $Username
     * @return resource
     * @throws Exception
     */
    public function Del($Username){
        $Username = addslashes(htmlspecialchars(trim($Username)));
        $Sql = "DELETE FROM `users` WHERE `username` = '$Username'";
        return mysql_query($Sql);
    }
    
    /**
     * 是访客？
     * @return bool
     */
    public function IsGuest(){
        return !isset($_SESSION['userid']);
    }
    
    /**
     * 获取当前用户ID
     * @return int|null
     */
    public function GetID(){
        if(isset($_SESSION['userid'])){
            return intval($_SESSION['userid']);
        }else{
            return null;
        }
    }
    
    /**
     * 获取当前用户数据
     * @return array|null
     */
    public function GetData(){
        return $this->GetUserDataByID($this->GetID());
    }
    
    /**
     * 用ID获取用户数据
     * @param $UserID
     * @return array|null
     */
    public function GetUserDataByID($UserID){
        $UserID = intval($UserID);
        $Query = mysql_query("SELECT * FROM `users` WHERE `id`='$UserID' limit 1");
        if($Result = mysql_fetch_array($Query)){
            return $Result;
        }else{
            return null;
        }
    }
    
    /**
     * 用用户名获取用户数据
     * @param $Username
     * @return array|null
     */
    public function GetUserDataByUsername($Username){
        $Username = addslashes(htmlspecialchars(trim($Username)));
        $Query = mysql_query("SELECT * FROM `users` WHERE `username`='$Username' limit 1");
        if($Result = mysql_fetch_array($Query)){
            return $Result;
        }else{
            return null;
        }
    }
    
    /**
     * 注销
     */
    public function Logout(){
        unset($_SESSION['userid']);
    }
    
    /**
     * 强迫症患者患病处理
     * @return resource
     * @throws Exception
     */
    public function UpdateAutoIncrement(){
        $Num = mysql_num_rows(mysql_query("SELECT * FROM `users`"));
        $Num++;
        $Sql = "ALTER TABLE `users` AUTO_INCREMENT = $Num";
        return mysql_query($Sql);
    }
}