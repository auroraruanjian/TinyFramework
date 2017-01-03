<?php
/** 
 * @todo 输出变量，调试函数
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
 * @todo 截取指定长度字符串，尾后用 特定字符代替
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
 *@todo 获取GET参数 
 */
function get($name,$default=false,$type=false){
    if(isset($_GET[$name])){
        if($type){
            switch($type){
                case 'int':
                    if(is_numeric($_GET[$name])){
                        return $_GET[$name];
                    }else{
                        return $default;
                    }
                    break;
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
                    if(is_numeric($_POST[$name])){
                        return $_POST[$name];
                    }else{
                        return $default;
                    }
                    break;
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
 * @todo 创建URL链接
 * @param
 */
function url( $ctrl , $action , $param = ''){
    $type=core\lib\conf::get('TYPE', 'route');
    if($type == 1){

    }else if($type == 2){
        $urlslice = core\lib\conf::get('URLSLICE', 'route');
        return './?'.$urlslice.'='.$ctrl.'/'.$action.$param;
    }
}

/**
 * @todo ajax返回消息
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
 * @todo 跳转URL
 * @param string $url
 */
function redirect($url){
	header('Location:'.$url);
	exit();
}

/**
 * @todo 获取客户端真实IP
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
 * @todo 获取当前URL的完整地址
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