<?php
namespace  core\lib;

require_once  CORE.DS.'common'.DS.'public_safe.php';

abstract class dispatcher{
    
    public function __construct(){
        $safeModel = new \public_safe();
    }
    
    abstract public function beforAction();
    
    abstract public function afterAction();
    
}