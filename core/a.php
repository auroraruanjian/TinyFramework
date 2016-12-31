<?php
namespace core;

class A{
    public static $classMap = array();                      //已经加载的类库
    
    static public function run(){
        
        $route = new \core\lib\route();
        $ctrlClass = $route -> ctrl;
        $action = $route -> action;
        
        $ctrlfile=APP.DS.'ctrl'.DS.$ctrlClass.'Ctrl.php';
        $cltrlClass = '\\'.MODULE.'\ctrl\\'.$ctrlClass.'Ctrl';
        if(is_file($ctrlfile)){
            include $ctrlfile;
            $ctrl = new $cltrlClass;
            $ctrl -> $action();
        }else{
            \core\lib\log::log('cannot found '.$ctrlClass,'router_error');
            throw new \Exception("找不到控制器 ".$ctrlClass);
        }
    }
    
    static public function load($class){
        //auto load class file
        if( isset( $classMap[$class] ) ){
            return true;
        }else{
            $class = str_replace('\\',DS, $class);
            $file= NICK.DS.$class.'.php' ;
            if( is_file( $file)){
                include $file;
                static::$classMap[$class] = $class;
            }else{
                return false;
            }
        }
    }
    
}