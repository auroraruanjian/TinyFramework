<?php
namespace core\lib\drive\cache;
abstract class base{
    protected $cache_type=''; 
    
    /**
     *初始化并检测是否支持此驱动
     *@throws \Exception  
     */
    public function __construct(){
        if( ! $this -> is_supported() ){
            \core\lib\log::log('不支持此驱动'.$this->$cache_type,array('cache_error','cache') );
            throw new \Exception('不支持此驱动'.$this->$cache_type);
        }
    }
    
    abstract function get( $id );
    
    abstract function save( $id , $value , $ttl = 0 );
    
    abstract function delete( $id );
    
    abstract function increment( $id );
    
    abstract function decrement( $id );
    
    abstract function clean();
    
    abstract function cache_info();
    
    abstract function get_metadata();
    
    abstract function is_supported();
}