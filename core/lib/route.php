<?php
namespace core\lib;
class route{
    static public $ctrl;
    static public $action;
    
    public function __construct(){
        
        $type=conf::get('TYPE', 'route');
        $prefix=conf::get('PREFIX', 'route');
        
        if($type ==1 ){
            $this->_setDsRouter();
        }else if ($type == 2){
            $this->_setArgRouter();
        }
        
        if( !empty($prefix) ){
            self::$action = $prefix . ucfirst( self::$action ) ;
        }
    }
    
    /**
     * @todo 设置分割 美化URL 例： http://domain/default/index,调用 default 控制器 index 方法
     */
    public function _setDsRouter(){
        $path = $_SERVER['REQUEST_URI'];
        
        if( isset( $path ) && $path != '/'){
            $patharr= explode( '/' , trim($path,'/') );
            if(isset($patharr[0])){
                self::$ctrl  = $patharr[0];
            }
            unset($patharr[0]);
            if(isset($patharr[1])){
                self::$action = $patharr[1];
                unset($patharr[1]);
            }else{
                self::$action = conf::get('ACTION', 'route');
            }
        
            // 处理 /index/index/id/1 参数URL
            $count = count($patharr) +2 ;
            $i=2;
            while( $i < $count ){
                if(isset($patharr[$i+1])){
                    $_GET[ $patharr[$i] ]= $patharr[$i+1];
                }
                $i += 2;
            }
        
        }else{
            self::$ctrl   = conf::get('CTRL', 'route');
            self::$action = conf::get('ACTION', 'route');
        }
    }
    
    /**
     * @todo 设置参数 URL 例： http://domain/?c=default/index&param=aaa,调用 default 控制器 index 方法
     */
    public function _setArgRouter(){
        $urlslice = conf::get('URLSLICE', 'route');
        $url = explode('/', get($urlslice,'') );
        
        if( !empty($url[0]) && $this->checkUrl( $url[0]) ){
            self::$ctrl = $url[0];
        }else{
            self::$ctrl = conf::get('CTRL', 'route');
        }
        
        if( !empty($url[1]) && $this->checkUrl( $url[1] ) ){
            self::$action = $url[1];
        }else{
            self::$action = conf::get('ACTION', 'route');
        }
        
    }
    
    /**
     * @todo 检查URL是否合法
     * @param String $url url地址
     * @return boolean 合法返回TRUE，不合法返回FALSE 
     */
    public function checkUrl($url){
        if( preg_match ('/^[a-zA-Z\d\-_]{1,20}$/', $url )){
           return true;  
        }
        return false;
    }
    
}