<?php
namespace core\lib;
class Cache{
    /**
     * @var Array 允许的缓存驱动
     */
    private $valid_drivers = [
            'APC',
            'Redis',
            'File',
    ];
    
    static private $drivers = array();
    
    /**
     *
     */
    public function __construct( $drive_name ){
        
        if(in_array( $drive_name , $valid_drivers )){

            $drive_class = '\\core\\lib\\drive\\cache\\'.$drive_name;
            
            self::$drivers[$drive_name] = new $drive_class();
            
            if( !$this -> is_supported($drive_name) ){
                throw new \Exception($message, $code, $previous);
            }
            
        }else{
            
            throw new \Exception('不支持缓存类型'.$drive_name);
            
        }
        
    }
    
    /**
     *@todo 获取一个缓存
     * 
     */
    public function get(){
        
    }
    
    /**
     *
     */
    public function save(){
    
    }
    
    /**
     *
     */
    public function delete(){
    
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
     *@todo 检查是否支持此驱动
     *@param String $type 类型名称
     *@return boolean 支持返回 true ，不支持返回 false
     */
    public function is_supported( $drive ){
        
        return self::$drivers[$drive] -> is_supported() ;
        
    }
}