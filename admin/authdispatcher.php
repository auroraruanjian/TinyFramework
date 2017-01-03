<?php
namespace admin;

use core\lib\dispatcher;
use \core\lib\route;
use admin\model\usermenuModel;

class authdispatcher extends dispatcher{
    public function __construct(){
        
        parent::__construct();
    }
    
    public  function beforAction(){
    	//如果用户未登陆，跳转到登陆页面
    	
        //以后需要实现，判断用户组是否禁用，菜单是否禁用，等功能
        $result = $this->checkMenuAccess( route::$ctrl , route::$action );
        if( $result == -1 ){
        	redirect('/admin'.url('default','index'));
        }else if($result == 0 ){
			$this -> halt( '您没有权限！' );
        }
        
    }
    public  function afterAction(){
        
    }
    
    /**
     *@todo 检查 菜单访问权限
     */
    public function checkMenuAccess( $control , $action ){
    	$action = lcfirst(str_replace('action', '', $action));

    	$iuserid = !empty( $_SESSION['userid'] ) ? $_SESSION['userid'] : '0' ;
    	
    	//如果已经登录，再次打开 登录页，则跳转到主页
    	if( $control =='default' && in_array($action,array('login')) && $iuserid != 0 ){
    		return -1;
    	}
    	
        $oUsermenu = new usermenuModel();
        
        return $oUsermenu->checkMenuAccess( $iuserid , $control, $action );
    }
    
    public function halt($msg){
    	die( $msg );
    }
}