<?php
namespace common\model;
use core\lib\model;
/**
 * 公共模块，配置信息
 * @author Nick
 *
 */
class configModel extends model{
    
    public $table = 'config';
    
    /**
     * 通过configkey获取一条配置
     * @param String $key  
     */
    public function getOneByKey( $key ){
        return $this->get($this->table,'*', ['configkey ' => $key] );
    }
    
    /**
     * 添加一条新配置
     * @param Array 
     *        $data[
     *          `parentid`,          //父ID
     *          `configkey`,         //配置KEY
     *          `configvalue`,       //配置值
     *          `defaultvalue`,      //默认值
     *          `configvaluetype`,   //配置值类型
     *          `forminputtype`,     //输入类型 INPUT：输入 SELECT：选择 CHECK：选中 SWITCH：开关  
     *          `channelid`,         //频道ID
     *          `title`,             //标题
     *          `description`,       //描述
     *          `isdisabled`         //是否开启
     *        ]
     * @return 返回insertid
     */
    public function insertConfig(Array $data ){
        if( !isset($data['parentid']) ) $data['parentid'] = 0;
        if( !isset($data['channelid']) ) $data['channelid'] = 0;
        
        if( !is_string($data['configkey']) || !is_string($data['configvalue']) || 
            !is_string($data['defaultvalue']) || !is_string($data['configvaluetype']) || 
            !is_string($data['forminputtype']) || !is_string($data['title']) || 
            !is_string($data['description']) ){
        	return false;
        }
        
        $datas = array(
            'parentid'          => intval($data['parentid']),               //父ID
            'configkey'         => $data['configkey'],                      //配置KEY
            'configvalue'       => $data['configvalue'],                    //配置值
            'defaultvalue'      => $data['defaultvalue'],                   //默认值
            'configvaluetype'   => $data['configvaluetype'],                //配置值类型
            'forminputtype'     => $data['forminputtype'],                  //输入类型 INPUT：输入 SELECT：选择 CHECK：选中 SWITCH：开关  
            'channelid'         => $data['channelid'],                      //频道ID
            'title'             => $data['title'],                          //标题
            'description'       => $data['description'],                    //描述
            'isdisabled'        => empty($data['isdisabled']) ? 0 : 1,      //是否开启
        );
    	return $this->insert( $this->table , $datas );
    }
    
    public function delOneByKey( $key ){
    	$this -> delete( $this->table , ['configkey ' => $key] );
    }
    
}