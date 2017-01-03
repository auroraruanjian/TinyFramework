<?php
namespace admin\model;

use core\lib\model;

class adminuserModel extends model{
    public $table = 'admin_user';
    
    public function insertUser($datas){
        return $this -> insert($this->table, $datas);
    }
    
    public function getUser( $username ){
        return $this->get($this->table,'*',array('username ' => $username));
    }
    
    public function getUsergroupId($username){
    	return $this->get($this->table,'groupid',array('username ' => $username));
    }
    
    
}