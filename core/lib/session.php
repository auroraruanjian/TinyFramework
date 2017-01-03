<?php
/**
 * @todo session 类
 */
namespace core\lib;
class session{
    static $class;
    
    public function __construct(){
        
        $conf = conf::all('session');
        
        ini_set('session.name',$conf['SESSSION_NAME']);
        
        if($conf['DRIVE'] == 'ini_redis'){
            
            if(!extension_loaded('redis')){
                
                throw new \Exception('未开启phpredis扩展！');
                
            }
            
            ini_set("session.save_handler","redis");
            
            ini_set("session.save_path",$conf['option']['save_path']);
            
        }else{
            
            $class = '\core\lib\drive\session\\'.$conf['DRIVE'];
            
            self::$class = new $class($conf['option']);
            
            if(is_php('5.4')){
                session_set_save_handler(self::$class, TRUE);
            }else{
                session_set_save_handler(array(
                    array(self::$class, 'open'),
                    array(self::$class, 'close'),
                    array(self::$class, 'read'),
                    array(self::$class, 'write'),
                    array(self::$class, 'destroy'),
                    array(self::$class, 'gc')
                ), TRUE);
            }
            
        }
        
        session_start();
        
    }
    
}