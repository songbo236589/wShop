<?php
namespace app\api\service;
use app\common\exception\ParameterException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{

    // 生成令牌
    public static function generateToken()
    {
        $randChar = getRandChar(32);
        $timestamp = time();
        $tokenSalt = config('common.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }
	//获取token令牌对应的用户信息
    public static function getCurrentIdentity()
    {
        $token = Request::instance()
            ->header('token');
        $identities = Cache::get($token);
        if (!$identities)
        {
            throw new ParameterException(['code'=>305,'msg'=>'token不存在']);
        }
        else
        {
            return json_decode($identities,true); 
        }
    }
	//返回user_id
	public static function getUserId(){
		$user = self::getCurrentIdentity();
		return $user['uid'];
	}
	//返回session_key
	public static function sessionKey(){
		$user = self::getCurrentIdentity();
		return $user['session_key'];
	}
	//返回openid
	public static function getOpenId(){
		$user = self::getCurrentIdentity();
		return $user['openid'];
	}
	//判断token是否存在
    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }
}