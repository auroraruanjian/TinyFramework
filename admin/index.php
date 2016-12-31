<?php
    @header("content-type:text/html; charset=UTF-8");
    define('DS',DIRECTORY_SEPARATOR);           //分隔符
   
    define('NICK', realpath('..'));
    define('CORE',NICK.DS.'core');
    define('APP',NICK.DS.'admin');              //APP 核心目录
    define('MODULE','admin');
    
    define('DEBUG', true);
    
    require_once NICK.DS.'vendor'.DS.'autoload.php';
    
    if(DEBUG){
        //加载第三方异常显示插件
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        
        error_reporting(E_ALL);
        ini_set('display_errors','On');
    }else{
        ini_set('display_errors','Off');
    }
    
    require_once  CORE.DS.'common'.DS.'function.php';
    
    require_once CORE.DS.'a.php';
    
    spl_autoload_register('\core\A::load');
    
    //初始化视图层
    $GLOBALS['oViews'] = new \core\lib\views();
    
    $GLOBALS['oViews']->assign('templatepath',DS.'admin'.DS.'views'.DS.'notebook');     //配置 视图位置
    
    //SESSION初始化
    $GLOBALS['oSession'] = new \core\lib\session();
    
    \core\A::run();
?>