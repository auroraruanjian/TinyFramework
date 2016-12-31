<?php
namespace core\lib\drive\log;
use core\lib\conf;
class file{
    public $path; //日志存储位置
    public function __construct(){
        $config = conf::get('option','log');
        $this->path = $config['path'];
    }
    
    public function log($message,$file = 'log'){
        $logpath = $this->path.date('YmdH').'/';
        
        if(!is_dir($logpath)){
            mkdir($logpath,0777,true);
        }
        $logfilename = $logpath.$file.'.log';
        if(!file_exists($logfilename)){
            touch($logfilename);
            chmod($logfilename,0777);
        }
        
//         p($logpath.$file.'.php');
        file_put_contents($logfilename, date('Y-m-d H:i:s') .'   '. json_encode($message) . PHP_EOL , FILE_APPEND);
    }
}