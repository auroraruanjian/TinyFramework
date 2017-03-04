<?php
namespace admin;

use core\lib\dispatcher;
use \core\lib\route;
use admin\model\usermenuModel;

class authdispatcher extends dispatcher{
    public function __construct(){
        
        parent::__construct();
    }
    
    /**
     * Action运行之前运行
     */
    public function beforAction(){
        
    	$iuserid = isset( $_SESSION['userid'] ) ? $_SESSION['userid'] : -1 ;
    	
    	//如果用户未登陆，跳转到登陆页面
        $result = $this->checkMenuAccess( $iuserid , route::$ctrl , route::$action );
        
        if( $result == -1 ){

            redirect( url('default','index') );//'/admin'.
        
        }else if($result == 0 ){

            $this -> halt( "<script>alert('请登陆！');window.location.href='./".url('default','login')."'</script>" );
        
        }
        
        //记录用户访问记录
        if( RECORD_LOG && $result['rec_log']==1 && $iuserid > -1 ){

            $adminlog = \core\A::singleton( "\admin\model\adminlogModel" );
            
            $adminlog -> insertLog( $result['title'] , $result['descript'] , route::$ctrl , route::unFixName( route::$action ) , 1 );
        }
        
    }
    
    /**
     * Action运行之前运行
     */
    public  function afterAction(){
        
    }
    
    /**
     *检查 菜单访问权限
     *@param String $control 控制器名称
     *@param String $action  方法名
     *@return －1:跳转首页 0:重新登陆 返回数据表示正常访问
     */
    public function checkMenuAccess( $iuserid , $control , $action ){
        
    	$action = route::unFixName( $action );
    	
        $oUsermenu = new usermenuModel();
        
        return $oUsermenu->checkMenuAccess( $iuserid , $control, $action );
    }
    
    public function halt($msg){
    	die( $msg );
    }
}