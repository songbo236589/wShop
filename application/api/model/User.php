<?php
namespace app\api\model;
use app\api\service\Token;
use app\api\service\WXBizDataCrypt;
use app\common\exception\ParameterException;
class User extends BaseModel
{
	//通过openid获取用户id
    public function getByOpenID($openid)
    {
       return $this->where(['openid'=>$openid])->value('id');
        
    }
	//用户首次进入时添加用户信息并返回  主键id
	public function setByOpenID($openid){
		$this->save(['openid'=>$openid,'add_time'=>$_SERVER['REQUEST_TIME']]);
		return $this->id;
	}
	//判断用户信息是否完善
	public function getUser($detail)
    {
		$userArr = Token::getCurrentIdentity();
		$pc = new WXBizDataCrypt(config('wx.app_id'),$userArr['session_key']);
		$errCode = $pc->decryptData($detail['encryptedData'],$detail['iv']);
		$rawData = json_decode($detail['rawData'], true);
		$rawData['id'] = $userArr['uid'];
		if($this->update($rawData) !== false){
			throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>'授权成功！'
			]);
		}else{
			throw new ParameterException([
					'msg'=>'网络错误！'
			]);
		}
    }
	
	/*
	//获取验证码
	public function getUserCode($phone){
		$mscode = mt_rand(100000,999999);	
		$client = new \SoapClient('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl',array('encoding'=>'UTF-8'));
		$message="您本次的验证码为".$mscode."。如非本人操作，请勿理会！【货货通】";
		$param1=array(
                    'account'=>'sdk_shanxiwanyi',
                    'password'=>'123456',
                    'destmobile'=>$phone,
                    'msgText'=> $message
					);
		//接口方法。
        $result = $client->sendBatchMessage($param1);
             //将XML数据转换成数组
       $array=json_decode(json_encode($result),true);
		if($array['sendBatchMessageReturn'] > 0){
			$msg = [
				'phone' => $phone,
				'mscode' => $mscode
			];
			throw new ParameterException([
					'code'=>200,
					'errorCode'=>'8888',
					'msg'=>$msg
			]);
		}else{
			throw new ParameterException(['code'=>400,'msg'=>'发送失败，请重试！']);
		}
	}*/	
	
	
}
