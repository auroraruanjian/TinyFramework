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
        $menuid = get('menuid',0,'int');
        
        $menuModel = new usermenuModel();
        
        $menu = $menuModel -> getOne( $menuid );
        
        ajaxMsg('200','success',$menu);
    }
    
}