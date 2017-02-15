<?php
namespace core\lib;
class Cache{
    /**
     * @var Array 允许的缓存驱动
     */
    protected $valid_drivers = [
            'APC',
            'Redis',
            'File',
    ];
    
    static private $drivers = array();
    
    /**
     *@param String $drive_name 驱动类型名称
     *@throws \Exception 
     */
    public function __construct( ){ }
    
    /**
     *获取一个缓存
     *@param String $drive_name 驱动名称 
     *@param String $id         缓存的KEY
     */
    public function get( $drive_name , $id ){
        
        if( $this->is_supported($drive_name) ){

            return self::$drivers[$drive_name] -> get( $id );
        
        }
        
        return false;
    
    }
    
    /**
     *保存缓存
     *@param String $drive_name 驱动名称（$valid_drivers存在）
     *@param String $id         缓存的KEY
     *@param String $value      缓存的值
     *@param String $ttl        生存值
     */
    public function save( $drive_name , $id , $value , $ttl = 0 ){
        
        if( $this->is_supported($drive_name) ){

            return self::$drivers[$drive_name] -> save( $id , $value , $ttl );
        
        }

        return false;               
    }
    
    /**
     *删除一个变量
     *@param  String  $id 缓存的KEY
     *@return boolean 
     */
    public function delete( $drive_name ,  $id ){
        
        if( $this->is_supported($drive_name) ){ 
        	
            return self::$drivers[$drive_name] -> delete( $id );
            
        }
        
        return false;
    }
    
    /**
     *函数缓存
     *@param String $drive_name 驱动名称（APC,Redis,File）
     *@param Mixed  $function   需要缓存的函数 或 类和方法名称，格式为：'function_name', 或者 array(&$instace, 'method_name')
     *@param array  $args       函数参数数组
     *@param number $ttl        过期时间，单位：秒
     *@throws Exception
     *@return mixed             返回执行 $function 返回的结果
     */
    public function cachedFunction($drive_name, $function, array $args = array(), $ttl = 0 ){
        
        if( $this->is_supported($drive_name) ){
        	
            $function_key = $function ;
            
            if( is_array( $function ) ){

                if( !empty( $function[0] ) && !empty( $function[1] ) && is_string( $function[1] ) ){

                    $function_key = $function[1] ;
                
                }else{
                	throw new \Exception('格式错误，$function 格式为 array( $class , $method )') ;
                }
                
            }
            
            $serialize_args = serialize( $args );
            
            $cache_key = ( (is_array($function)) ? get_class($function[0]).'_' : '' ). $function_key . md5( $serialize_args );
            
            $result = $this -> get($drive_name, $cache_key );
            
            if( !empty($result) ){
                
                return unserialize( $result );
                
            }
            
                
            $result = call_user_func_array($function, $args);
            
            $this -> save( $drive_name , $cache_key, serialize( $result ) , $ttl );
            
            return $result;
                
        }
    }
    
    /**
     *
     */
    public function increment(){
    
    }
    
    /**
     *
     */
    public function decrement(){
    
    }
    
    /**
     *
     */
    public function clean(){
    
    }
    
    /**
     *
     */
    public function cache_info(){
    
    }
    
    /**
     *
     */
    public function get_metadata(){
    
    }
    
    /**
     *初始化缓存类，并检测是否支持此类型
     *@param String $drive_name 类型名称
     *@throws \Exception 
     */
    public function is_supported( $drive_name ){
        
        if(in_array( $drive_name , $this->valid_drivers ) ){
            
            if(empty( self::$drivers[$drive_name] ) ){

                $drive_class = '\\core\\lib\\drive\\cache\\'.$drive_name;
            
                self::$drivers[$drive_name] = new $drive_class();
            
            }
            
            return true;
        }else{
            
            throw new \Exception('不支持缓存类型'.$drive_name);
        }
        
    }
}