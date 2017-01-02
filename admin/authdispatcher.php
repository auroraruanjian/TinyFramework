<?php
namespace admin;

use core\lib\dispatcher;
use \core\lib\route;

class authdispatcher extends dispatcher{
    public function __construct(){
        
        parent::__construct();
    }
    
    public  function beforAction(){
        
        $this->checkMenuAccess(route::$ctrl,route::$action);
        
    }
    public  function afterAction(){
        
    }
    
    /**
     *@todo 检查 菜单访问权限
     */
    public function checkMenuAccess(){
        
    }
}