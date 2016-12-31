<?php
namespace  core\lib;

class redis {
    private static $conn = null;
    
    public function __construct(){
        $host = conf::get('HOST','redis');
        $port = conf::get('HOST','redis');
        
        if(!empty ( $this -> conn )){
            $this -> conn = phpiredis_connect($host,$port);
        }
    }
    
    public function __destruct(){
        phpiredis_disconnect($this->conn);
    }
    
    
    
    /**
     *@todo 选择 Redis 库 
     *@param $iDB INT 库编码
     *@return void
     */
    public function selectDB($iDB){
        phpiredis_command_bs($this->conn,array(
            'SELECT',$iDB
        ));
    }
    
    /**
     *@todo 获取一个 String值[不限类型]
     *@param $key String 数据库键
     *@return String
     */
    public function getOne($key){
        return phpiredis_command_bs($this->conn,array('GET',$key));
    }
    
    /**
     *@todo 获取一个 List值[不限类型]
     *@param $key String 数据库键
     *@param $name String 需要获取的列
     *@return String
     */
    public function getHone($key,$name){
        
    }
}
