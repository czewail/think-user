<?php
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2016 陈泽韦 <549226266@qq.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author     陈泽韦 <549226266@qq.com>
 * @copyright  2016 陈泽韦.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    1.0.0
 * @link       http://
 */
namespace Ppeerit\User;

use Ppeerit\User\Exceptions\EncryptInvalidException;
use Ppeerit\User\Exceptions\UserInvalidException;
use Ppeerit\User\Library\Encrypt;
use think\Config;
use think\Session;

/**
 * 用户操作类
 */
class User {
	// 密码加密等级
	const ENCRYPT_LEVEL_1 = '1';
	// 密码加密等级
	const ENCRYPT_LEVEL_2 = '2';
	// 实例对象
	protected $instance;
	// 密码允许等级
	protected $allow_encrypt_level = [
		ENCRYPT_LEVEL_1, ENCRYPT_LEVEL_2,
	];
	// 默认配置
	protected $_config = [
		'user_session_name' => 'member_auth', // 用户session名称
		'user_session_sign' => 'member_auth_sign', // 用户session签名名称
		'user_pk' => 'uid', // 用户主键
		'password_key' => '', // 密码加密字符串
		'encrypt_level' => 2, //
	];

	//构造方法
	function __construct() {
		// 将应用配置替换默认配置
		if (Config::has('user')) {
			$config = Config::get('user');
			$this->_config = array_merge($this->_config, $config);
		}
	}
	/**
	 * 检查用户是否登陆
	 * @param  array  $config [description]
	 * @return [type]   [description]
	 */
	public static function isLogin() {
		if (is_null(self::$instance)) {
			self::$instance = new static();
		}
		$user = Session::get(self::$instance->_config['user_session_name']);
		if (empty($user)) {
			return 0;
		} else {
			return Session::get(self::$instance->_config['user_session_sign']) == self::data_auth_sign($user) ? $user[self::$instance->_config['user_pk']] : 0;
		}
	}
	/**
	 * 用户自动登录
	 * @return [type] [description]
	 */
	public static function autoLogin(array $auth) {
		if (is_null(self::$instance)) {
			self::$instance = new static();
		}
		if (!$auth[self::$instance->_config['user_pk']]) {
			throw new UserInvalidException('user auth is invalid.');
		}
		Session::set(self::$instance->_config['user_session_name'], $auth);
		Session::set(self::$instance->_config['user_session_sign'], self::data_auth_sign($auth));
	}
	/**
	 * 密码加密
	 * @param  string $pwd [密码]
	 * @return string   [加密后的密码]
	 */
	public static function encrypt($pwd = '') {
		if (is_null(self::$instance)) {
			self::$instance = new static();
		}
		$encrypt_level = self::$instance->_config['encrypt_level'];
		if (!in_array($encrypt, self::$instance->allow_encrypt_level)) {
			throw new EncryptInvalidException('encrypt level is invalid.');
		}
		return call_user_func_array(__NAMESPACE__ . 'Encrypt::encrypt_' . $encrypt_level, [$pwd, self::$instance->_config['password_key']]);
	}
	/**
	 * 数据签名
	 * @param  [type] $data [description]
	 * @return [type]  [description]
	 */
	protected static function data_auth_sign($data) {
		//数据类型检测
		if (!is_array($data)) {
			$data = (array) $data;
		}
		ksort($data); //排序
		$code = http_build_query($data); //url编码并生成query字符串
		$sign = sha1($code); //生成签名
		return $sign;
	}

}