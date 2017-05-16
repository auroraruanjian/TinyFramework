<?php
namespace core\lib;

use core\lib\conf;

class model extends \medoo{
    public function __construct( $newoption = array() ){
        $option = conf::all('testDB');
        
        $newoption = array_merge($option,$newoption);
        
        parent::__construct($newoption);
        
        /*
        $database = conf::all('database');
        
        try{
            parent::__construct($database['DSN'], $database['USERNAME'], $database['PASSWORD']);
        }catch (\PDOException $e){
            p($e->getMessage());
        }
        */
    }
    
    /**
     * 获取分页数据
     * @param Array $filed  需要查询的字段,[filed1,filed2,filed3]
     * @param Array $where  查询的条件，参考medoo文档
     * @param Int   $p      当前页，默认第一页
     * @param Int   $pn     显示多少行数据，默认20行
     * @param Array $result [ 'row' => Array() , 'param' => Array() ]
     */
    public function getPageResult( $filed , $where=Array()  , $p = 0 , $pn = 20 ){
        
        $count = $this -> count( $this->table , $where );

        $startNum = $p * $pn ;                                                      //开始页
        
        $where = array_merge( $where , [ 'LIMIT' => [ $startNum , $pn ] ] );
        
        $row = $this -> select( $this->table , $filed , $where  );
        
        $url = preg_replace('/&p=[\w]{0,2}/', '', getUrl() ).'&p=??';       //去除旧参数
        
        //返回供分页类使用
        $params = array(
                'total_rows'=> $count,              #(必须)
                'method'    => 'html',              #(必须)
                'parameter' => $url,    #(必须)
                'now_page'  => $p,                  #(必须)
                'list_rows' => $pn,                 #(可选) 默认为15
        );
        $result = [
        	'row'   => $row,
        	'param' => $params
        ];
    
        return $result;
    }

}