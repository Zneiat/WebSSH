<?php
namespace app;
use ReflectionMethod;
use ReflectionParameter;
use Exception;

class AirOS
{
    public function GetRequestCommand()
    {
        $Command = trim(addslashes(@$_POST['Command']));
        $CommandArr = explode(' ', $Command);
        return $CommandArr;
    }
    
    public function HandleJsonRpc($Obj)
    {
        try {
            header('Content-Type: application/json');
            $Class = get_class($Obj);
            $Methods = get_class_methods($Class);
            $ReqData = $this->GetRequestCommand();
            $Command = $ReqData[0];
            $Params = array_splice($ReqData, 1);
            if (!in_array($Command, $Methods) && !in_array("__call", $Methods)) {
                $this->ResponseError($Command);
            } else {
                if (in_array("__call", $Methods) && !in_array($Command, $Methods)) {
                    $Result = call_user_func_array(array($Obj, $Command), $Params);
                    echo $this->Response($Result);
                } else {
                    $MethodObject = new ReflectionMethod($Class, $Command);
                    $DefaultValueNum = 0;
                    $NoDefaultValueNum = 0;
                    $DefaultValueData = array();
                    foreach (json_decode(json_encode($MethodObject->getParameters()), true) as $Num=>$Param) {
                        $ReflectionParame = new ReflectionParameter(array($Class, $Command), $Param['name']);
                        if($ReflectionParame->isDefaultValueAvailable()) $DefaultValueNum++;
                        if(!$ReflectionParame->isDefaultValueAvailable()) $NoDefaultValueNum++;
                        if($ReflectionParame->isDefaultValueAvailable()) $DefaultValueData[] = $ReflectionParame->getDefaultValue();
                    }
                    // echo $DefaultValuePos.' '.json_encode($DefaultValueData).' '.$NoDefaultValuePos;
                    $NumGot = count($Params);
                    $NumExpect = $MethodObject->getNumberOfParameters();
                    if (($NumGot == $NumExpect)) {
                        $result = call_user_func_array(array($Obj, $Command), $Params);
                        echo $this->Response($result);
                    } else if (($NumGot != $NumExpect)&&($DefaultValueNum != 0)&&($NumGot==$NoDefaultValueNum)){
                        $result = call_user_func_array(array($Obj, $Command), array_merge($DefaultValueData, $Params));
                        echo $this->Response($result);
                    } else {
                        $Result = "Wrong Number Of Parameters. Got $NumGot Expect $NumExpect";
                        echo $this->Response($Result, 'Error');
                    }
                }
            }
        } catch (Exception $Exception) {
            // 抓取 throw new Exception 产生的错误
            $Msg = $Exception->getMessage();
            echo $this->Response($Msg, 'Error');
        }
    }
    
    public function Response($Return, $ReturnLevel='Default')
    {
        return json_encode([
            'Result' => $Return,
            'ReturnLevel'=> $ReturnLevel,
            'Username' => (new Users())->GetData()['username'],
            'WorkingDirectory' => basename((new UserTmpData())->Data('Directory')),
        ]);
    }
    
    public function ResponseError($Command){
        $Result = "Command `$Command` Not Found";
        echo $this->Response($Result, 'Error');
    }
}