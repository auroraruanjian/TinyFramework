<?php
namespace core;
function_exists('date_default_timezone_set') && date_default_timezone_set('Asia/Shanghai');

class A{
    public static $classMap = array();                      //已经加载的类库
    
    static public function run($config){
        $oConfig = array(
            'dispatcher' => 'core\lib\dispatcher',
        );
        $oConfig = array_merge($oConfig,$config);
        
        //如果存在调度器，先行调用beforAction
        $dispatcher = new $config['dispatcher'];
        if(! is_a($dispatcher, 'core\lib\dispatcher' ) ){
            throw new \Exception('类型错误！' );
        }
        
        //初始化路由类
        $route = new \core\lib\route();

        //在Action运行之前运行，做一些权限校验之类
        $dispatcher -> beforAction();           
        
        //路由调用方法，检查
        $route -> _run();           
        
        //在Action运行之后运行，做一些后续输出控制之类
        $dispatcher -> afterAction();
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