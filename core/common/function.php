<?php
/** 
 * 输出变量，调试函数
 * dump 依赖第三方类库  symfony/var-dumper
 * @param $var  变量
 * @param $type int 类型 1和2
 */
function p($var,$type=1){
    if($type==1){
        dump($var);
    }else{
        if(is_bool($var)){
            var_dump($var);
        }else if(is_null($var)){
            var_dump(null);
        }else{
            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#f5f5f5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>" . print_r($var, true) . "</pre>";
        }
    }
}

/**
 * 截取指定长度字符串，尾后用 特定字符代替
 * @param $str    需要截取的字符串
 * @param $length 需要截取的长度
 * @param $suff   补充字符
 * @return String 
 */
function count_str($str,$length,$suff='...'){
    if(function_exists('mb_substring')){
        return mb_substr($str, 0,$length).$suff;
    }else{
        return substr($str, 0,$length).$suff;
    }
}

/**
 * 获取GET参数 
 * @param String $name    需要从GET中获取的KEY
 * @param String $default 默认值，如果GET获取不到 $name  
 * @param String $type    [int,string,boolean]
 */
function get($name,$default=false,$type=false){
    if(isset($_GET[$name])){
        if($type){
            switch($type){
                case 'int':
                    return intval($_GET[$name]);
                case 'string':
                    //因为在程序底层已经 转义 处理，这里不再多重转义
                    //return addslashes_deep($_GET[$name]);
                    return $_GET[$name];
                case 'boolean':
                    return $_GET[$name] ? true : false ;
                default:
                    return $default;
            }
        }else{
            return $_GET[$name];
        }
    }else{
        return $default;
    }
}

/**
 *@todo 获取POST参数
 */
function post($name,$default=false,$type=false){
    if(isset($_POST[$name])){
        if($type){
            switch($type){
                case 'int':
                    return intval($_POST[$name]);
                case 'string':
                    //因为在程序底层已经 转义 处理，这里不再多重转义
                    //return addslashes_deep($_GET[$name]);
                    return $_POST[$name];
                case 'boolean':
                    return $_POST[$name] ? true : false ;
                default:
                    return $default;
            }
        }else{
            return $_POST[$name];
        }
    }else{
        return $default;
    }
}

/**
 * decrypt AES 256
 *
 * @param data $edata
 * @param string $password
 * @return decrypted data
 */
function decrypt($edata, $password) {
    $data = base64_decode($edata);
    $salt = substr($data, 0, 16);
    $ct = substr($data, 16);

    $rounds = 3; // depends on key length
    $data00 = $password.$salt;
    $hash = array();
    $hash[0] = hash('sha256', $data00, true);
    $result = $hash[0];
    for ($i = 1; $i < $rounds; $i++) {
        $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
        $result .= $hash[$i];
    }
    $key = substr($result, 0, 32);
    $iv  = substr($result, 32,16);

    return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
}

/**
 * crypt AES 256
 *
 * @param data $data
 * @param string $password
 * @return base64 encrypted data
 */
function encrypt($data, $password) {
    // Set a random salt
    $salt = openssl_random_pseudo_bytes(16);

    $salted = '';
    $dx = '';
    // Salt the key(32) and iv(16) = 48
    while (strlen($salted) < 48) {
        $dx = hash('sha256', $dx.$password.$salt, true);
        $salted .= $dx;
    }

    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);

    $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
    return base64_encode($salt . $encrypted_data);
}

if ( ! function_exists('is_php'))
{
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param	string
     * @return	bool	TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string) $version;

        if ( ! isset($_is_php[$version]))
        {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}



/**
 * 创建URL链接
 * @param String $ctl       控制器
 * @param String $action    行为方法
 * @param Array  $param     参数 
 */
function url( $ctrl , $action , $paramarr = array() ){
    $param = !empty($paramarr)? create_url_param( $paramarr ) : '';
    
    $type=core\lib\conf::get('TYPE', 'route');
    if($type == 1){

    }else if($type == 2){
        $urlslice = core\lib\conf::get('URLSLICE', 'route');
        return './?'.$urlslice.'='.$ctrl.'/'.$action.$param;
    }
}
/**
 * 创建URL参数
 * @param Array $param 参数
 * @return String 返回参数链接
 */
function create_url_param( $param ){
    $link = '';
    foreach( $param as $key => $value ){
    	$link += "&{$key}={$value}";
    }
	return $link;
}


/**
 * ajax返回消息
 * @param Int $code
 * @param string $msg
 * @param array $data
 */
function ajaxMsg( $code , $msg = 'success' , $data = array() ){
    $return_data = array(
        'code' => $code ,
        'msg'  => $msg 
    );
    if(!empty($data)){ $return_data['data'] = $data; }
    
    echo json_encode($return_data);
    exit();
}

/**
 * 跳转URL
 * @param string $url
 */
function redirect($url){
	header('Location:'.$url);
	exit();
}

/**
 * 获取客户端真实IP
 * @return String IP 返回客户端真实IP地址
 */
function getRealIP() {
    static $realip = NULL;
    
    if ($realip !== NULL) {
        return $realip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $arr = explode(',', $_SERVER['HTTP_X_REAL_IP']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return $realip;
}


/**
 * 获取当前URL的完整地址
 * @param boolean $bShowFullUri 是否显示完整URL，默认显示完整
 * @param boolean $bAllowPort   是否显示端口号
 * @return string	完整URL地址
 */
function getUrl($bShowFullUri = TRUE, $bAllowPort = TRUE) {
    $temp_url = '';
    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') {
        $temp_url = 'https://';
    } else {
        $temp_url = 'http://';
    }
    $temp_url .= $_SERVER['SERVER_NAME'];
    if (TRUE == $bAllowPort && intval($_SERVER['SERVER_PORT']) != 80) {
        $temp_url .= ':' . $_SERVER["SERVER_PORT"];
    }
    if ($bShowFullUri == FALSE) {
        return $temp_url;
    }
    $temp_url .= $_SERVER["REQUEST_URI"];
    return $temp_url;
}

/**
 * 转义字符串or数组
 * @param mixed $obj 需要转义的字符串OR数组
 * @param boolean $force 是否强制转义
 * @param boolean $strip 是否需要先去掉之前转义，重新转义
 */
function addslashes_deep( $obj , $force = false , $strip = false ){
    if( !get_magic_quotes_gpc() || $force ){
    	if( is_array( $obj ) ){
    		$obj = array_map( 'addslashes_deep' , $obj );
    	}else if( is_object($obj) ){
    		foreach ( $obj as $key => $val ){
    			$obj -> $key = addslashes_deep( $val );
    		}
    	}else{
    		$obj = addslashes(  $strip? stripslashes($obj) : $obj );
    	}
    }
    return $obj;
}

/**
 * 反转义
 * @param mixed $str 需要反转义的字符串OR数组 
 */
function stripslashes_deep( $obj , $force = false , $strip = false ){
    if( !get_magic_quotes_gpc() || $force ){
        if( is_array( $obj ) ){
            $obj = array_map( 'stripslashes_deep' , $obj );
        }else if( is_object($obj) ){
            foreach ( $obj as $key => $val ){
                $obj -> $key = stripslashes_deep( $val );
            }
        }else{
            $obj = stripslashes( $obj );
        }
    }
    return $obj;
}

//Remove the exploer'bugXSS
function RemoveXSS($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/({0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|({0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}