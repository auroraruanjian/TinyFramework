<?php
namespace core\lib;
class route{
    public $ctrl;
    public $action;
    
    public function __construct(){
        $type=conf::get('TYPE', 'route');
        if($type ==1 ){
            $this->_setDsRouter();
        }else if ($type == 2){
            $this->_setArgRouter();
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
                $this->ctrl   = $patharr[0];
            }
            unset($patharr[0]);
            if(isset($patharr[1])){
                $this->action = $patharr[1];
                unset($patharr[1]);
            }else{
                $this->action = conf::get('ACTION', 'route');
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
            $this->ctrl   = conf::get('CTRL', 'route');
            $this->action = conf::get('ACTION', 'route');
        }
    }
    
    public function _setArgRouter(){
        $url = explode('/', get('c','') );
        
        if( isset($url[0]) && $this->checkUrl( $url[0]) ){
            $this->ctrl = $url[0];
        }else{
            $this->ctrl = conf::get('CTRL', 'route');
            
        }
        
        if( isset($url[1]) && $this->checkUrl( $url[1] ) ){
            $this->action = $url[1];
        }else{
            $this->action = conf::get('ACTION', 'route');
        }
        
    }
    
    public function checkUrl($url){
        if( preg_match ('/^[a-zA-Z\d\-_]{0,20}$/', $url )){
           return true;  
        }
        return false;
    }
}