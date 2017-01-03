<?php
return array(
    'CTRL'   => 'default',      //默认控制器
    'ACTION' => 'login',        //默认 方法
    'PREFIX' => 'action',       //默认方法前缀
    'TYPE'   => '2' ,          //1：美化URL 例： /default/index  2:参数url: 例 ： index.php?c=default&m=index
                               // 如果 TYPE 是2 ,则 需要配置 CNAME 例: ?c=default/index
    'URLSLICE'  => 'c'         //默认 参数
);