<?php
namespace admin\ctrl;
use admin\model\usermenuModel;

class menuCtrl extends \core\lib\baseCtrl  {
    
    //获取所有菜单
    public function actionAdminmenulist(){
        $menuModel = new usermenuModel();
        
        $menulist = $menuModel -> getMenuList();
        
        $GLOBALS['oViews']->assign('menulist',$menulist);
        
        $GLOBALS['oViews']->assign('pagename','menu/adminmenu.html');
        
        $GLOBALS['oViews']->display('common/page_box.html');
    }
    
    public function actionAdminmenuctl(){
        
    }
    
    //获取某条菜单详情
    public function actionAdminmeunudetail(){
        $menuid = get('menuid',-1,'int');
        
        if( $menuid < 0 ){
            ajaxMsg( '500' , '菜单ID错误' );
        }
        
        $menuModel = new usermenuModel();
        
        $menu = $menuModel -> getOne( $menuid );
        
        ajaxMsg('200','success',$menu);
    }
    
    /**
     * 修改菜单详情
     */
    public function actionChangeMenu(){
        
        $menuid = post('menuid',-1,'int');
        
        if( $menuid < 0 ){
            ajaxMsg( '500' , '菜单ID错误' );
        }
        
        $data = array(
            'title'     => post('title','','string'),
            'descript'   => post('descript','','string'),
            'control'   => post('control','','string'),
            'action'    => post('action','','string'),
            'sort'      => post('sort',0,'int'),
            'is_enable' => post('is_enable',0,'int'),
            'is_show'   => post('is_show',0,'int'),
            'rec_log'   => post('rec_log',0,'int'),
        );
        
        /*
        $data = array_filter( $data , function( $val , $key ){
            return ($val == '')?false:true;
        } );
        */
        
        $menuModel = new usermenuModel();
        
        $return = $menuModel -> updateMenu( $menuid , $data );
        
        if( $return ){
            ajaxMsg(200,'更新成功');
        }else{
            ajaxMsg(500,'没有更新数据');
        }
        
        
    }
}