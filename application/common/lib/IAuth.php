<?php
namespace app\common\lib;
class IAuth{
	//密码加密方法
	public static function setPassword($data){
		return md5($data.config('common.password'));
	}
}
