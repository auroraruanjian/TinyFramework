<?php
namespace core\lib\drive\log;
use core\lib\conf;
class file{
    public $path; //日志存储位置
    public function __construct(){
        $config = conf::get('option','log');
        $this->path = $config['path'];
    }
    
    public function log($message,$param = array('log','') ){
        $logpath = $this->path.$param[1].DS.date('YmdH').DS;
        
        if(!is_dir($logpath)){
            mkdir($logpath,0777,true);
        }
        $logfilename = $logpath.$param[0].'.log';
        if(!file_exists($logfilename)){
            touch($logfilename);
            chmod($logfilename,0777);
        }
        
//         p($logpath.$file.'.php');
        file_put_contents($logfilename, date('Y-m-d H:i:s') .'   '. $message . PHP_EOL , FILE_APPEND);
    }
}