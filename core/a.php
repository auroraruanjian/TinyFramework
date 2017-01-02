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
        
        $route = new \core\lib\route();
        $ctrlClass = $route::$ctrl;
        $action = $route::$action;

        //在Action运行之前运行，做一些权限校验之类
        $dispatcher -> beforAction();           
        
        $ctrlfile=APP.DS.'ctrl'.DS.$ctrlClass.'Ctrl.php';
        $cltrlClass = '\\'.MODULE.'\ctrl\\'.$ctrlClass.'Ctrl';
        if(is_file($ctrlfile)){
            include $ctrlfile;
            if( class_exists($cltrlClass) ){
                $ctrl = new $cltrlClass;
                if(method_exists($ctrl, $action)){
                    $ctrl -> $action();
                    $dispatcher -> afterAction();
                }else{
                    \core\lib\log::log('cannot found '.$action,'router_error');
                    throw new \Exception("找不到控制器 ".$action);
                }
            }else{
                \core\lib\log::log('cannot found '.$ctrlClass,'router_error');
                throw new \Exception("找不到类 ".$ctrlClass);
            }
        }else{
            \core\lib\log::log('cannot found '.$ctrlfile,'router_error');
            throw new \Exception("找不到文件 ".$ctrlfile);
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