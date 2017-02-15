<?php
namespace core\lib\drive\cache;
class APC extends base{
    public function __construct(){
        parent::__construct();
    }
    
    public function get( $id ){
        
        if( !is_string( $id ) ) return false;
        
        return  apc_fetch( $id );
        
    }
    
    public function save( $id , $value , $ttl = 0 ){
        
        if( !is_string( $id ) && !is_numeric($ttl) ) return false;
        
        if( !is_string( $value ) ){

            $value = unserialize( $value );
        
        }
        
        apc_store($id, $value , $ttl);
    }
    
    public function delete($id){
        
        if( !is_string($id) ) return false;
        
        apc_delete( $id );
        
    }
    
    public function increment($id){
        
    }
    
    public function decrement($id){
        
    }
    
    public function clean(){
        
        apc_clear_cache('user');
        
        return apc_clear_cache();
    
    } 
    
    public function cache_info(){
    
    }
    
    public function get_metadata(){
    
    }
    
    public function is_supported(){
        return (extension_loaded('apc') && ini_get('apc.enabled'));
    }
}