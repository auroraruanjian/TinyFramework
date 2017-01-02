<?php
namespace  core\lib;
abstract class dispatcher{
    
    public function __construct(){
        
    }
    
    abstract public function beforAction();
    
    abstract public function afterAction();
    
}