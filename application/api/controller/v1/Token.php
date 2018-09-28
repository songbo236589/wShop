<?php
namespace app\api\controller\v1;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\TokenGet;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
/**
 * 获取令牌，相当于登录
 */
class Token extends BaseController
{
    /**
     * 用户获取令牌（登陆）
     * @url /token/user
     * @POST code
     * @note 虽然查询应该使用get，但为了稍微增强安全性，所以使用POST
     */
    public function getToken($code='')
    {
        (new TokenGet())->goCheck();
        $wx = new UserToken($code);
        $token = $wx->get();
        return [
            'token' => $token
        ];
    }
	/**
     * 用户获取令牌（登陆）
     * @url /token/verify
     * @POST token
     * @note 判断用户的token是否过期
     */
    public function verifyToken($token = '')
    {
        if(!$token){
            throw new ParameterException([
                'token不允许为空！'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }
	/**
     * 用户获取令牌（登陆）
     * @url /token/determine
     * @POST rawData
     * @note 判断用户是否授权登陆
     */
	public function determineUser(){
		$detail = input('post.');	
		if(!$detail){
            throw new ParameterException([
                'msg'=>'网络错误！'
            ]);
        }
		model('user')->getUser($detail);
	}

}