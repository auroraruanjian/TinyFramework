<?php
return array(
    'CTRL'   => 'default',
    'ACTION' => 'index',
    'TYPE'   => '2' ,          //1：美化URL 例： /default/index  2:参数url: 例 ： index.php?c=default&m=index
                               // 如果 TYPE 是2 ,则 需要配置 CNAME 例: ?c=default/index
    'CNAME'  => 'c' 
);