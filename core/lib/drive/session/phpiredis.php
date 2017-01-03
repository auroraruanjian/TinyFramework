<?php
namespace core\lib\drive\session;
class phpiredis implements \SessionHandlerInterface{
    private $redis;     		    // Redis 链接
    private $config;    			// Redis 配置
    private $_key_prefix='s_';		//前缀
    
    public function __construct($option){
        if(!function_exists('phpiredis_connect')){
            throw new \Exception('请开启php-redis扩展！');
        }
        
        $this->_config = $option;
        
        if (empty($option)){
            throw new \Exception('请检查redis配置');
        }elseif (preg_match('#(?:tcp://)?([^:?]+)(?:\:(\d+))?(\?.+)?#', $this->_config['save_path'], $matches)){
			isset($matches[3]) OR $matches[3] = ''; // Just to avoid undefined index notices below
			$this->_config['save_path'] = array(
				'host' => $matches[1],
				'port' => empty($matches[2]) ? 6379 : $matches[2],
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
    public function open($savePath, $sessionName){
        $this->redis = phpiredis_connect($this->_config['save_path']['host'],$this->_config['save_path']['port']);
        
        if(empty($this->redis) || !is_resource($this->redis) ){
            \core\lib\log::log( ' redis connect database'.$this->_config['save_path']['host'].' faild'.PHP_EOL , 'session_error' );
            throw new \Exception('Redis 连接失败'.$this->_config['save_path']['host'].':'.$this->_config['save_path']['port'].'，请检查!');
        }
        
        //切换 Redis 库
        phpiredis_command_bs($this->redis,array('SELECT','8'));
    }
    public function close(){
        if(empty($this->redis) || !is_resource($this->redis) ){
            \core\lib\log::log( ' close redis resource is faild'.PHP_EOL , 'session_error' );
        }
        phpiredis_disconnect($this->redis);
    }
    public function read($id){
        return phpiredis_command_bs($this->redis,array('GET',$this->_key_prefix.$id));
    }
    public function write($id, $data){
        phpiredis_multi_command_bs($this->redis,array(
            array('SET',$this->_key_prefix.$id,$data),
            array('EXPIRE',$this->_key_prefix.$id,5000)
        ));
    }
    public function destroy($id){
    	phpiredis_command_bs($this->redis,array('DEL',$this->_key_prefix.$id));
    }
    public function gc($maxlifetime){
        //Redis 自动销毁
    }
    
}