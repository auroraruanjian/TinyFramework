<?php
namespace core\lib\drive\log;
class mysql{
    
    /**
     * 记录日志
     * @param String $message 日志内容
     * @param Array $param    参数 array('错误类型','表名')
     */
    public function log(  $message , $param = array('log','') ){
        p($message);
    }
}