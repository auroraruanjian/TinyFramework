<?php
namespace admin\ctrl;
use admin\model\adminuserModel;

class defaultCtrl extends \core\lib\baseCtrl  {
    
    /**
     * @todo 用户登陆方法 url('default','login')  /?c=default/login
     */
    public function actionLogin(){
        
        //显示验证码
        if( isset( $_GET['valide'] ) ){
        	$_vc = \core\A::singleton('\core\lib\ValidateCode');    //实例化
        	$_vc->doimg();  
            $_SESSION['authnum_session'] = $_vc->getCode();         //验证码保存到SESSION中

        }else{
        
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
                $GLOBALS['oViews']->display('login.html');
            }
            
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
        
        /*
         * 分页测试代码
        $usermenu = new \admin\model\usermenuModel();
        $result = $usermenu -> getPageResult( '*' , [], get('p',0,'int') ,1);
       
        $page = new \core\lib\page( $result['param'] );
        $GLOBALS['oViews']->assign('page', $page -> show(1) ); 
        */
        
        $GLOBALS['oViews']->assign('data',$data);
        
        $GLOBALS['oViews']->assign('pagename','content/welcome.html');
        
        $GLOBALS['oViews']->display('common/page_box.html');
    }
    
    
    
}