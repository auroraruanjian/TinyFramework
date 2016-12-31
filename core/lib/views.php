<?php
namespace core\lib;
/**
 * @todo 视图层 
 */
class views{
    
    public $assign;
    
    public function __construct(){
        
    }
    
    public function assign($name,$value){
        $this->assign[$name] = $value;
    }
    
    public function display ($file){
        $rfile=APP.'/views/'.$file;
        if(is_file($rfile)){
            \Twig_Autoloader::register();
            $loader = new \Twig_Loader_Filesystem(APP.'/views');
            $twig = new \Twig_Environment($loader, array(
                    'cache' => NICK.'/log/twig',
                    'debug' => DEBUG,
            ));
            $template = $twig->loadTemplate($file);
            $template -> display($this->assign?$this->assign:array());
        }
    }
    
}