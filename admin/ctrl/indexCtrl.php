<?php
namespace admin\ctrl;
use \core\lib\model;
class indexCtrl extends \core\lib\baseCtrl  {
    
    public function index(){
        if(isset($_SESSION['user'])){
            //dump($_SESSION,session_id());
        }else{
//             $_SESSION['user']=array(
//                 'username' => 'nick',
//                 'age'      => '20'
//             );
        }
        
        $data = 'hello world ';
        
        $GLOBALS['oViews']->assign('data',$data);
        
        $GLOBALS['oViews']->display('notebook/index.html');
    }
    
    public function test(){
        $data = 'test nihao ,this is test string ';
        
        //$data = preg_replace('/(.){5}$/', '*', $data);
        //$data = substr_replace($data, '*' ,-1 ,5);
        $data = substr_replace($data, '*' ,-1 ,5);
        
        $GLOBALS['oViews']->assign('data',$data);
        
        $GLOBALS['oViews']->display('test.html');
    }
    
    public function del(){
        $model = new \app\model\table2Model();
        
        $rst = $model ->delOne(2);
        
        dump($rst);
    }
    
    public function index4(){
        $model = new \app\model\table2Model();
        
        $rst = $model ->lists();
        
        dump($rst);
    }
    
    public function index3(){
        $model = new model();
        
        //$data = $model->select('table2', '*');
        $data = array(
                'name' => 'java',
                'descript' => 'java data'
        );
        
        $result = $model -> insert('table2', $data);
        
        dump($result);
    }
    
    public function index2(){
        $model = new \core\lib\model();
        
        $ctrl=\core\lib\conf::get('CTRL','route');
        
        $ctrl=\core\lib\conf::get('ACTION','route');
        
        $data= 'Hello World';
        $GLOBALS['oViews']-> assign('title','这是一个视图文件');
        $GLOBALS['oViews']-> assign('data',$data);
        $GLOBALS['oViews']->display("index.html");
    }
    
    public function getTable(){
        $model = new \core\lib\model();
        
        $sql = 'SELECT * FROM table2';
        
        $ret=$model->query($sql);
        
        p($ret->fetchAll());
    }
    
}