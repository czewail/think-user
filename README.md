# think-user

[![GitHub issues](https://img.shields.io/github/issues/ppeerit/think-user.svg)](https://github.com/ppeerit/think-user/issues)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/ppeerit/think-user/master/LICENSE)
[![Downloads](https://img.shields.io/github/downloads/ppeerit/think-user/latest/total.svg)](https://packagist.org/packages/ppeerit/think-user)

ppeerit\think-user 是基于thinkphp5的简单的用户相关扩展包

##安装
```bash
composer require ppeerit\think-user
```
##配置
扩展配置目录中新增配置文件user.php
```php
return [
	'user_session_name' => 'member_auth', // 用户session名称
	'user_session_sign' => 'member_auth_sign', // 用户session签名名称
	'user_pk' => 'uid', // 用户主键
	'password_key' => 'abcdefg', // 密码加密字符串，自定义
	'encrypt_level' => 2, //加密等级，1：简单加密，2：双重加密
];
```
