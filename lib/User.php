<?php 	
namespace Ppeerit\User;

use think\Session;
use think\Config;

class User
{
	// 实例对象
	protected $instance;
	// 默认配置
	protected $_config = [
		'user_session_name'	=>	'member_auth',				//用户session名称
		'user_session_sign'	=>	'member_auth_sign',			//用户session签名名称
		'user_pk'			=>	'uid',						// 用户主键
		'password_key'		=>	'',							//密码加密字符串
	];

	//构造方法
	function __construct()
	{
		// 将应用配置替换默认配置
		if (Config::has('generic')) {
    		$config = Config::get('generic');
    		$this->_config = array_merge( $this->_config, $config );
    	}
	}
	/**
	 * 检查用户是否登陆
	 * @param  array  $config [description]
	 * @return [type]         [description]
	 */
	public static function isLogin()
    {
    	if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        $user = Session::get( self::$instance->_config['user_session_name'] );
        if ( empty($user) ) {
            return 0;
        } else {
            return Session::get( self::$instance->_config['user_session_sign'] ) == self::data_auth_sign( $user ) ? $user[self::$instance->_config['user_pk']] : 0;
        }
    }
    /**
     * 密码加密
     * @param  string $pwd [密码]
     * @return string      [加密后的密码]
     */
    public static function encrypt( $pwd = '' )
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return '' === $pwd ? '' : md5( sha1($pwd) . self::$instance->_config['password_key'] );
    }
	/**
	 * 数据签名
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public static function data_auth_sign( $data )
	{
		//数据类型检测
		if(!is_array($data)){
		    $data = (array)$data;
		}
		ksort($data); //排序
		$code = http_build_query($data); //url编码并生成query字符串
		$sign = sha1($code); //生成签名
		return $sign;
	}
}