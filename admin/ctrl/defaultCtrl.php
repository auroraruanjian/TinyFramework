<?php
namespace admin\ctrl;
use admin\model\userModel;

class defaultCtrl extends \core\lib\baseCtrl  {
    
    
    public function actionLogin(){
        if( !empty( $_POST ) ){
            $username = post('username');
            $password = post('password');
            
            $userModel = new userModel();
            $user = $userModel -> getUser($username);
            
            if( $user && $user['password'] == md5($password) ){
                $_SESSION['userid']   = $user['userid'];
                $_SESSION['username'] = $user['username'];
                
                ajaxMsg(200,'登录成功',array('url'=>'/admin'.url('default','index') ));
            }else{
                ajaxMsg(500,'登录失败');
            }
        }else{
            $GLOBALS['oViews']->assign('submiturl','/admin'.url('default','login'));
        
            $GLOBALS['oViews']->display('notebook/login.html');
        }
    }
    
    public function actionIndex(){
        
        $data = 'hello world ';
        
        $GLOBALS['oViews']->assign('data',$data);
        
        $GLOBALS['oViews']->display('notebook/index.html');
    }
    
    
}