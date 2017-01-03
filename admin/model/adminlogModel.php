<?php
namespace admin\model;

use core\lib\model;
use core\lib\conf;

class adminlogModel extends model{
    public $table = 'admin_log';
    
    public function __construct(){
        $option = conf::all('logDB');
        
        parent::__construct($option);
    }
    
    /**
     * @todo 写入logo
     */
    public function  insertLog( $title , $content , $control , $action , $typeid = 0){
        
        $datas = array(
        	'typeid'        => intval( $typeid ),
            'adminid'       => isset($_SESSION['userid']) ? $_SESSION['userid'] : 0 ,
            'clientip'      => getRealIP() ,
            'proxyip'       => $_SERVER['REMOTE_ADDR'] ,
            'times'         => date('Y-m-d H:i:s', time() ) ,
            'querystring'   => getUrl() ,                          //完整URL
            'controller'    => $control ,
            'actioner'      => $action ,
            'title'         => $title ,
            'content'       => $content ,
            'requeststring' => serialize($_REQUEST) ,
        );
        
        $result = $this->insert($this->table, $datas) ;
        
    }
    
    /**
     * @todo 根据 userid 获取日志记录
     * @param String $iuserid
     */
    public function getLogbyUserid($iuserid){
    	
    }
}

