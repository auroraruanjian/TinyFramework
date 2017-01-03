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
}