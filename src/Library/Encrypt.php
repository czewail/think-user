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
namespace Ppeerit\User\Library;

use Ppeerit\User\Exceptions\EncryptInvalidException;

/**
 * 密码加密辅助类
 */
class Encrypt {
	// 密码加密等级
	const ENCRYPT_LEVEL_1 = '1';
	// 密码加密等级
	const ENCRYPT_LEVEL_2 = '2';
	// 密码允许等级
	protected static $allow_encrypt_level = [
		ENCRYPT_LEVEL_1, ENCRYPT_LEVEL_2,
	];
	public static function encrypt($pwd = '', $string = '', $level) {
		if (!in_array($level, self::$allow_encrypt_level)) {
			throw new EncryptInvalidException('encrypt level is invalid.');
		}
		return call_user_func_array([self, 'encrypt_level_' . $level], [$pwd, $string]);
	}
	/**
	 * 密码加字符串简单加密
	 * @param  string $pwd    [string]
	 * @param  string $string [string]
	 * @return [type]         [string]
	 */
	protected static function encrypt_level_1($pwd = '', $string = '') {
		return '' === $pwd ? '' : md5($pwd . $string);
	}
	/**
	 * 密码加字符串双重加密
	 * @param  string $pwd    [string]
	 * @param  string $string [string]
	 * @return [type]         [string]
	 */
	protected static function encrypt_level_2($pwd = '', $string = '') {
		return '' === $pwd ? '' : md5(sha1($pwd) . $string);
	}
}