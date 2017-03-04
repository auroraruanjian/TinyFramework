<?php
namespace admin\ctrl;
use admin\model\adminuserModel;
use core\lib\conf;
use common\model\configModel;

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
        /*
        APC缓存测试
        $GLOBALS['oCcahce'] -> save( 'APC' , 'data' , $data , 9000 );
        
        $newdata = $GLOBALS['oCcahce'] -> get( 'APC' , 'data' );
        	
        //APC函数返回值测试
        $userModel = new adminuserModel();
        
        $result = $GLOBALS['oCcahce'] -> cachedFunction( 'APC' , array( &$userModel , 'getUser' ) ,array('admin') ,60 );
        
        p($result);
        */
        
        /*
        //配置插入测试
        $configArr = [
            'parentid'          => 0,
            'configkey'         => 'inserttest', 
            'configvalue'       => '', 
            'defaultvalue'      => '',
            'configvaluetype'   => '',
            'forminputtype'     => '',
            'channelid'         => 0,
            'title'             => '测试', 
            'description'       => '测试', 
            'isdisabled'        => 1, 
        ];
        
        $config = new \common\model\configModel();
        
        $config -> insertConfig($configArr);
        
        $configval = $config->delOneByKey('inserttest');
        p($configval);
        */
        
        $GLOBALS['oViews']->assign('data',$data);
        
        $GLOBALS['oViews']->display('notebook/index.html');
    }
    
    
    
}