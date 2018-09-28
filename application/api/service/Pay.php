<?php
namespace app\api\service;
use app\api\model\Order as OrderModel;
use app\common\exception\ParameterException;
use app\api\service\Token;
use think\Loader;
use think\Log;
Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
//支付接口的定义
class Pay
{
	public function pay($pay)
    {  
        return $this->makeWxPreOrder($pay);
    }
    //构建微信支付订单信息
    private function makeWxPreOrder($pay)
    {	
		$openid = Token::getOpenId();
        $wxOrderData = new \WxPayUnifiedOrder();
		$wxOrderData->SetBody($pay['goods']);                 //商品名称 
		$wxOrderData->SetAttach($pay['id']);                  //附加参数,可填可不填,填写的话,里边字符串不能出现空格    回调时原样返回   
		$wxOrderData->SetTime_expire(date("YmdHis", time() + 600));//支付超时  
		$wxOrderData->SetTime_start(date("YmdHis"));       //支付发起时间  
        $wxOrderData->SetOut_trade_no($pay['order_no']);//订单号  
        $wxOrderData->SetTrade_type('JSAPI');         //支付类型  
        $wxOrderData->SetTotal_fee($pay['money'] * 100); //支付金额,单位:分  
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url("https://www.shanxiwanyi.com/api/v1/Cart/redirectNotify");//支付回调验证地址
        throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$this->getPaySignature($wxOrderData,$pay['id'])
			]);
    }
    //向微信请求订单号并生成签名
    private function getPaySignature($wxOrderData,$id)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
       
		// 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recordPreOrder($wxOrder,$id);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder,$id){
        // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的
        (new OrderModel)->where(['id' => $id])
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }
    // 签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }
}