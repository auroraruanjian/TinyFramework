<?php
namespace core\lib\drive\session;
class redis implements \SessionHandlerInterface{
    
    private $conn;          //redis链接
    private $_config;       //redis配置
    private $sessionid;     //session_id
    private $_key_prefix;
    
    public function __construct($option){
        
        if(!class_exists('redis')){
            throw new \Exception('请开启php-redis扩展！');
        }
        
        $this->_config = $option;
        
        if (empty($option)){
            throw new \Exception('请检查redis配置');
        }elseif (preg_match('#(?:tcp://)?([^:?]+)(?:\:(\d+))?(\?.+)?#', $this->_config['save_path'], $matches)){
			isset($matches[3]) OR $matches[3] = ''; // Just to avoid undefined index notices below
			$this->_config['save_path'] = array(
				'host' => $matches[1],
				'port' => empty($matches[2]) ? NULL : $matches[2],
				'password' => preg_match('#auth=([^\s&]+)#', $matches[3], $match) ? $match[1] : NULL,
				'database' => preg_match('#database=(\d+)#', $matches[3], $match) ? (int) $match[1] : NULL,
				'timeout' => preg_match('#timeout=(\d+\.\d+)#', $matches[3], $match) ? (float) $match[1] : NULL
			);

			preg_match('#prefix=([^\s&]+)#', $matches[3], $match) && $this->_key_prefix = $match[1];
		}
        
		if ($this->_config['match_ip'] === TRUE)
		{
		    $this->_key_prefix .= $_SERVER['REMOTE_ADDR'].':';
		}
		
    }
    
    public function open($savePath, $sessionName)
    {
        $this -> conn = new \redis();
        
        if(! $this -> conn -> connect( $this->_config['save_path']['host'] , $this->_config['save_path']['port'] )  ){
            \core\lib\log::log( date('Y-m-d H:m:s',time()).' redis host'.$this->_config['save_path']['host'].$this->_config['save_path']['port'].' connect faild'.PHP_EOL , 'redis' );
        }
        if( ! $this -> conn -> select( $this->_config['save_path']['database'] )   ){
            \core\lib\log::log( date('Y-m-d H:m:s',time()).' redis select database'.$this->_config['save_path']['database'].' faild'.PHP_EOL , 'redis' );
        }
        
        $this -> sessionid = session_id();
        
        return true;
    }
    
    public function close()
    {
        
        $this->conn->close();
        return true;
    }
    
    public function read($id)
    {
        return  $this -> conn -> get( $this->_key_prefix. $id );//, array( $id ) 
    }
    
    public function write($id, $data)
    {   
        $this -> conn -> set($this->_key_prefix.$id,$data );
        $this -> conn -> setTimeout($this->_key_prefix.$id,$this->_config['save_path']['timeout']);
        
        return true;
    }
    
    public function destroy($id)
    {
        $this -> conn -> hDel( $this->_key_prefix.$id );
        return true;
    }
    
    public function gc($maxlifetime)
    {
        $this -> conn -> persist();
        return true;
    }
    
    /**
     * @todo create session_id
     */
    static public function createSessionId(){
        
//         $sKey = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR'];
        
//         $_SERVER['HTTP_USER_AGENT'].$sKey;
        
    }
    
}