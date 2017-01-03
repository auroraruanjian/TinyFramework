<?php
namespace admin\model;

use core\lib\model;

class usermenuModel extends model{
    public $table = 'admin_usermenu';
    
    /**
     * @todo 检查用户是否有权限访问菜单
     * @param int $userid 用户ID
     * @param String $control 控制器名
     * @param String $action 方法名
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
    		$result = $this->query("SELECT count(*) as count FROM {$this->table} as um WHERE find_in_set('*',um.allowusergroup)  AND um.control={$control} AND um.action={$action} Limit 1") -> fetch();
    	}else{
    	    //检查 用户 是否有权限查看菜单
    		$userid  = $this->quote( $userid );

    		$sql="SELECT count(*) as count FROM {$this->table} as um INNER JOIN admin_user as au ON find_in_set('*',um.allowusergroup) OR find_in_set(au.groupid,um.allowusergroup) WHERE um.control={$control} AND um.action={$action} AND au.userid={$userid} Limit 1";
    		
    		$result = $this->query($sql) -> fetch();
    	}
    	
    	if($result['count'] >0){
    		return 1;
    	}
    	return 0;
    }
    
    /**
     * @todo 检查 menu 状态
     * @param String $control  控制器名称
     * @param String $action   方法名称
     * @return int 1：存在 ， -1：不存在，-2：菜单关闭
     */
    public function checkMenuExist($control,$action){
        $control = $this->quote( $control );
        $action  = $this->quote( $action );
        
        $sql = "SELECT is_enable FROM {$this->table} WHERE `control` = {$control} AND `action` = {$action} Limit 1";
        
        $result = $this -> query( $sql ) -> fetch();
        
        if($result != false){
            if( $result['is_enable'] == 1 ){
                //菜单正常 返回1
                return 1;
            }else{
                //菜单被禁用，返回-2
                return -2;
            }
        }else{
            //菜单不存在
            return -1;
        }
    }
    
}