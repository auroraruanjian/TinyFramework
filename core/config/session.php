<?php
return array(
    'DRIVE' => 'phpiredis',
    'SESSSION_NAME'  => '_sid',
    'option' => array(
        'save_path' =>'tcp://127.0.0.1:6379?weight=1&timeout=3000.0&database=0&auth=12',
        'match_ip'  => false, 
    ),
);