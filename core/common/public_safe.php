<?php 
/**
 * 公共安全模型，防止sql注入，防止XSS，并对GET、POST、COOKIT、SERVER、REQUEST 参数转义 
 */
class public_safe {
    private $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    private $postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    private $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    /**
     * 构造函数
     */
    public function __construct() {
        //对所有参数进行注入检测
        foreach($_GET as $key=>$value){$this->stopattack($key,$value,$this->getfilter);}
        foreach($_POST as $key=>$value){$this->stopattack($key,$value,$this->postfilter);}
        foreach($_COOKIE as $key=>$value){$this->stopattack($key,$value,$this->cookiefilter);}
        foreach($_REQUEST as $key=>$value){$this->stopattack($key,$value,$this->cookiefilter);}
        foreach($_SERVER as $key=>$value){$this->stopattack($key,$value,$this->cookiefilter);}
        foreach($_FILES as $key=>$value){$this->stopattack($key,$value,$this->cookiefilter);}

        //对所有输入参数过滤
        $_GET       =   addslashes_deep( $_GET , true ) ;
        $_POST      =   addslashes_deep( $_POST , true );
        $_COOKIE    =   addslashes_deep( $_COOKIE , true );
        $_REQUEST   =   addslashes_deep( $_REQUEST , true );
        $_SERVER    =   addslashes_deep( $_SERVER );
        $_FILES     =   addslashes_deep( $_FILES , true );
    }
    /**
     * 参数检查并写日志
     */
    public function stopattack($StrFiltKey, $StrFiltValue, $ArrFiltReq){
        if(is_array($StrFiltValue))$StrFiltValue = implode($StrFiltValue);
        if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue) == 1){  
            $this->writeslog($_SERVER["REMOTE_ADDR"]."    ".strftime("%Y-%m-%d %H:%M:%S")."    ".$_SERVER["PHP_SELF"]."    ".$_SERVER["REQUEST_METHOD"]."    ".$StrFiltKey."    ".$StrFiltValue);
            die('您提交的参数非法,系统已记录您的本次操作！');
        }
    }
    /**
     * SQL注入日志
     */
    public function writeslog($log){
        \core\lib\log::log( $log ,array('safe_warn'));
    }
}

?>