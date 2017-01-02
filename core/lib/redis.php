<?php
namespace  core\lib;

class redis {
    /**
     * @var Redis 类型常量
     */
    const REDIS_STRING = 'String';
    const REDIS_SET = 'Set';
    const REDIS_LIST = 'List';
    const REDIS_ZSET = 'Sorted set';
    const REDIS_HASH = 'Hash';
    const REDIS_NOT_FOUND = NULL;
    
    //@var $conn Redis链接
    private  $conn = null;
    
    public function __construct(){
        
    }
    
    /**
     * @todo 建立一个Redis链接
     */
    public function connect( $host , $port=6379 ){
        return $this -> __connect( 'connect' , $host , $port );
    }
    /**
     * @todo 建立一个Redis持久链接
     */
    public function pconnect( $host , $port=6379 ){
        return $this -> __connect( 'pconnect' , $host , $port );
    }
    
    private function __connect( $type='connect' , $host , $port=6379 ){
        $ippatt = '/((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))/';
        
        if(preg_match($ippatt, $host)){
            
            if($type == 'connect'){
                $this -> conn = phpiredis_connect($host,$port);
            }else{
                $this -> conn = phpiredis_pconnect($host,$port);
            }
            
        }else if( strpos($host, '.sock') ){
        
            if($type == 'connect'){
                $this -> conn = phpiredis_connect($host);
            }else{
                $this -> conn = phpiredis_pconnect($host);
            }
        
        }else{
            throw new \Exception('参数错误！');
        }
    }
    
    /**
     * @todo 断开Redis链接
     */
    public function disconnect(){
        phpiredis_disconnect($this->conn);
    }
    
    /**
     * @todo 对象销毁时自动断开Redis链接
     */
    public function __destruct(){
        $this -> disconnect();
    }
    
    /**
     * @todo 密码校验
     * @param String $passwd Redis密码
     * @return boolean 如果正确返回TRUE，否则 返回FALSE
     */
    public function auth($passwd){
        if( phpiredis_command_bs( $this->conn , array( 'AUTH', $passwd ) ) ){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * @todo 打印一个特定的信息 message ，测试时使用
     * @param String $msg 测试信息
     * @return String 同样的信息
     */
    public function _echo ( $msg ){
        return phpiredis_command_bs( $this->conn , array( 'ECHO', $msg ) );
    }
    
    /**
     *@todo 选择 Redis 库 
     *@param Int $iDB 库编码
     *@return void
     */
    public function select($iDB){
        $result = phpiredis_command_bs($this->conn,array(
            'SELECT',$iDB
        ));
        return ($result == 'OK') ? TRUE : FALSE ;
    }
    
    /**
     * @todo 检查Redis链接状态
     * @return boolean 如果链接正常返回TRUE，否则返回FALSE
     */
    public function ping(){
        $result = phpiredis_command_bs($this->conn,array('PING'));
        return ($result == 'PONG') ? TRUE : FALSE ;
    }
    
    
    
    
    /**
     *@todo 获取一个 String值[不限类型]
     *@param String $key 数据库键
     *@return String
     */
    public function getOne($key){
        return phpiredis_command_bs($this->conn,array('GET',$key));
    }
    
    /**
     *@todo 获取一个 List值[不限类型]
     *@param String $key 数据库键
     *@param String $name 需要获取的列
     *@return String
     */
    public function getHone($key,$name){
        
    }
}
