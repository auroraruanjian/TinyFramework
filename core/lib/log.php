<?php
namespace  core\lib;
class log{
    static $class;
    static public function init(){
        $drive = conf::get('DRIVE','log');
        $class = '\core\lib\drive\log\\'.$drive;
        self::$class = new $class;
    }
    
    /**
     * 记录日志
     * @param String $msg 
     * @param Array $param 参数  mysql: array('错误类型','表名')  file:arrray('文件名','文件路径') array('redis_error','redis'));
     *
     */
    static public function log($msg, $param = array('log','') ){
        if(!is_object(self::$class)){
            self::init();
        }
        self::$class->log($msg,$param);
    }
}