<?php
namespace admin\model;

use core\lib\model;

class usermenuModel extends model{
    public $table = 'admin_usermenu';
    
    /**
     * @todo 检查用户是否有权限访问菜单
     * @param int $userid 用户ID
     * @param int $menuid 菜单ID
     * @return int 如果有权限返回1，无权限返回0
     */
    public function checkMenuAccess($userid,$control,$action){
    	/*
    	SELECT * FROM admin_usermenu as um
    	INNER JOIN admin_user as au  ON find_in_set('*',um.allowusergroup) OR find_in_set('1',um.allowusergroup)
    	WHERE um.control='default' AND um.action='index';
    	*/
    	$control = $this->quote($control);
    	$action  = $this->quote($action);

    	//用户未登陆
    	if( 0 == $userid ){
    		//检查菜单是否可任何人查看
    		$result = $this->query("SELECT count(*) as count FROM admin_usermenu as um WHERE find_in_set('*',um.allowusergroup)  AND um.control={$control} AND um.action={$action} Limit 1") -> fetch();
    	}else{
    		$userid  = $this->quote( $userid );

    		$sql="SELECT count(*) as count FROM admin_usermenu as um INNER JOIN admin_user as au ON find_in_set('*',um.allowusergroup) OR find_in_set(au.groupid,um.allowusergroup) WHERE um.control={$control} AND um.action={$action} AND au.userid={$userid} Limit 1";
    		
    		$result = $this->query($sql) -> fetch();
    	}
    	
    	if($result['count'] >0){
    		return 1;
    	}
    	return 0;
    }
    
}