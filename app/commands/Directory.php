<?php
use app\UserTmpData;
class DirectoryCommands {
    
    private $WorkingDirectory;
    
    public function __construct() {
        $this->WorkingDirectory = (new UserTmpData())->Data('Directory');
    }
    
    public function cd($Path=null){
        if(is_null($Path)) return '';
        $WD = realpath($this->WorkingDirectory . '/' . $Path);
        /*if($this->WorkingDirectory == realpath(__BasePath__)&&realpath($Path)==realpath(dirname(realpath(__BasePath__)))) $WD = realpath(__BasePath__);*/
        if(!file_exists($WD)) throw new Exception('Cannot Access '.$Path.': No Such File Or Directory');
        (new UserTmpData()) -> Data('Directory',$WD);
        return '';
    }
    
    public function ls($Path=null) {
        if(is_null($Path)) $Path = $this->WorkingDirectory;
        $BPath = realpath($Path);
        if(!is_null($Path)&&!file_exists($BPath)) throw new Exception("Cannot Access $Path: No Such File Or Directory");
        $Dir = opendir($BPath);
        $List = '';
        while ($Name = readdir($Dir)) {
            if ($Name != '.' && $Name != '..') {
                if(!is_file(realpath($BPath.'/'.$Name))){
                    $Color = 'cornflowerblue';
                }else{
                    $Color = 'aqua';
                }
                $List .= '<a style="color: '.$Color.'">'.$Name.'</a>&nbsp;&nbsp;';
            }
        }
        closedir($Dir);
        return $List;
    }
    
    public function pwd(){
        return $this->WorkingDirectory;
    }
    
    public function mkdir($DirName){
        if(file_exists(realpath($this->WorkingDirectory).'/'.$DirName)){
            throw new Exception('mkdir: cannot create directory `'.$DirName.'\': File exists');
        }
        if(mkdir(realpath($this->WorkingDirectory).'/'.$DirName)){
            return '';
        }else{
            throw new Exception('mkdir: error');
        }
    }
    
    public function rmdir($DirName){
        $WD = realpath($this->WorkingDirectory . '/' . $DirName);
        if(!file_exists($WD)) throw new Exception("Cannot Access $DirName: No Such File Or Directory");
        function deldir($Dir) {
            $Dh = opendir($Dir);
            while ($File = readdir($Dh)) {
                if ($File != "." && $File != "..") {
                    $Fullpath = $Dir . "/" . $File;
                    if (!is_dir(realpath($Fullpath))) {
                        @unlink($Fullpath);
                    } else {
                        deldir($Fullpath);
                    }
                }
            }
        }
        if(rmdir($WD)){
            return '';
        }else{
            throw new Exception('rmdir: error');
        }
    }
}