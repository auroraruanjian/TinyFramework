<?php
namespace core\lib\drive\cache;
class APC{
    public function __construct(){
        if( ! is_supported() ){
            \core\lib\log::log('APC','cache_error');
        }
    }
    
    public function get( $id ){
        $value = apc_fetch( $id );
        p($value);
    }
    
    public function save($id,$value,$ttl){
        
    }
    
    public function delete($id){
        
    }
    
    public function increment($id){
        
    }
    
    public function decrement($id){
        
    }
    
    public function clean(){
        
    } 
    
    public function cache_info(){
    
    }
    
    public function get_metadata(){
    
    }
    
    public function is_supported(){
        return (extension_loaded('apc') && ini_get('apc.enabled'));
    }
}