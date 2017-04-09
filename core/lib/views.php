<?php
namespace core\lib;
/**
 * @todo 视图层 
 */
class views{
    
    public $assign;
    protected $baseViewPath = ''; //模版路径
    
    public function __construct(){
        $this -> baseViewPath = APP . DS .'views' . DS . \core\lib\conf::get('viewpath', 'view') . DS ;
    }
    
    public function assign($name,$value){
        $this->assign[$name] = $value;
    }
    
    public function display ($file){
        $rfile=$this->baseViewPath.$file;
        if(is_file($rfile)){
            
            \Twig_Autoloader::register();
            $loader = new \Twig_Loader_Filesystem( $this->baseViewPath );
            $twig = new \Twig_Environment($loader, array(
                    'cache' => NICK.'/log/twig',
                    'debug' => DEBUG,
            ));
            
            //模版函数注册
            $functionName = 'url';
            $twig->registerUndefinedFunctionCallback(function ($functionName) {
            	if (function_exists($functionName)) {
            		return new \Twig_SimpleFunction($functionName, $functionName);
            	}
            	return false;
            });
            
            //模版引擎添加全局 app 支持
            $twig->addGlobal('app', array(
            	'session' => $_SESSION,
                'get'     => $_GET,
                'post'    => $_POST,
                'server'  => $_SERVER
            ));
            
            $template = $twig->loadTemplate($file);
            $template -> display($this->assign?$this->assign:array());
        }
    }
    
}