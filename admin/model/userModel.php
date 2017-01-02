<?php
namespace admin\model;

use core\lib\model;

class userModel extends model{
    public $table = 'user';
    
    public function insertUser($datas){
        return $this -> insert($this->table, $datas);
    }
    
    public function getUser( $username ){
        return $this->get($this->table,'*',array('username ' => $username));
    }
    
}