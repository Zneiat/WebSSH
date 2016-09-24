<?php
namespace app;
use Exception;

class Db {
    
    public $Table;
    
    /**
     * Class constructor.
     */
    public function __construct(){
        if(!Config('Database')['Connect']) die('Use Db Class Must Open Database Connect.');
    }
    
    /**
     * 数据库快速查询，(⊙v⊙)
     * @param $Table
     * @param array $Where
     * @param $OrderBy
     * @param null $Limit
     * @return array|bool
     */
    public static function Find($Table,$Where = array(),$OrderBy,$Limit = null){
        if (empty($Where)) {
            $Sql = "SELECT * FROM `" . $Table . "`";
        }else{
            $WhereT = 'WHERE ';
            foreach ($Where as $Key => $Val) $WhereT .= '(`'.$Key.'` = \''.$Val.'\') AND';
            $WhereT = trim($WhereT,' AND');
            $Sql = "SELECT * FROM `" . $Table . "` ".$WhereT;
        }
        if (!empty($OrderBy)) {
            $OrderT = 'ORDER BY ';
            foreach ($OrderBy as $Key => $Val){
                switch ($Val){
                    case SORT_ASC:
                        $Val = 'ASC';
                        break;
                    case  SORT_DESC:
                        $Val = 'DESC';
                        break;
                    default:
                        $Val = 'ASC';
                        break;
                }
                $OrderT .= '`'.$Key.'` '.$Val.',';
            }
            $OrderT = trim($OrderT,',');
            $Sql = $Sql.' '.$OrderT;
        }
        if (!empty($Limit)){
            $Sql = $Sql.' LIMIT '.intval($Limit);
        }
        $Query = mysql_query($Sql);
        // AsArray
        $Data = array();
        while($Row = @mysql_fetch_array($Query)) $Data[] = $Row;
        if(mysql_errno()){
            return false;
        }
        return $Data;
    }
    
    /**
     * 转换为数组
     * @param $Result
     * @return array
     */
    public function AsArray($Result){
        $Data = array();
        while($Row = mysql_fetch_array($Result)) $Data[] = $Row;
        return $Data;
    }
}

