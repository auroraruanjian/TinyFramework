<?php
namespace admin\ctrl;
use admin\model\adminuserModel;

class defaultCtrl extends \core\lib\baseCtrl  {
    
    /**
     * @todo 用户登陆方法 url('default','login')  /?c=default/login
     */
    public function actionLogin(){
        if( !empty( $_POST ) ){
            $username = post('username');
            $password = post('password');
            
            $userModel = new adminuserModel();
            $user = $userModel -> getUser($username);
            
            if( $user && $user['password'] == md5($password) ){
                $_SESSION['userid']   = $user['userid'];
                $_SESSION['username'] = $user['username'];
                
                ajaxMsg(200,'登录成功',array('url'=> url('default','index') ));
            }else{
                ajaxMsg(500,'登录失败');
            }
        }else{
            $GLOBALS['oViews']->display('notebook/login.html');
        }
    }
    
    public function actionIndex(){
        
        $data = 'hello world ';
        
        //$GLOBALS['oCcahce'] -> save( 'APC' , 'data' , $data , 9000 );
        
        $newdata = $GLOBALS['oCcahce'] -> get( 'APC' , 'data' );
        
        if ( ! $newdata ){
        	
            $userModel = new adminuserModel();
            
            $result = $GLOBALS['oCcahce'] -> cachedFunction( 'APC' , array( &$userModel , 'getUser' ) ,array('admin') ,60 );
            
            p($result);
        }
                
        $GLOBALS['oViews']->assign('data',$data);
        
        $GLOBALS['oViews']->display('notebook/index.html');
    }
    
    
    
}