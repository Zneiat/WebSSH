<?php
require_once ('core/core.php');
use app\UserTmpData;
$UserTmpData = new UserTmpData();
try {
    if($CoreUsers -> Login($_POST['username'],$_POST['password'])){
        $UserTmpData->SaveArrayData($_POST['setting']);
        echo json_encode(['success'=>true,'msg'=>'登录成功']);
    }
} catch (Exception $Exception) {
    echo json_encode(['success'=>false,'msg'=>$Exception->getMessage(),'password_error'=>true]);
}