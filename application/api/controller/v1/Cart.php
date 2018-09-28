<?php
namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\Cart as CartV;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
class Cart extends BaseController
{
	/**
     * 加入购物车
     * @url     /getGoodsContent
     * @http    post     传入商品id 和 购买数量num  
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function postAddCart()
    {	
		(new CartV())->goCheck();
		model('cart')->postAddCart(input('post.'));
    }
	/**
     * 购物车列表
     * @url     /getCartList
     * @http    get    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getCartList()
    {	
		model('cart')->getCartList();
    }
	/**
     * 购物车减号
     * @url     /postCartSubtract
     * @http    post  传入购物车id    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
	public function postCartSubtract(){
		$id = input('post.id');
		model('cart')->postCartSubtract($id);
	}
	/**
     * 购物车加号
     * @url     /postCartAdd
     * @http    post  传入购物车id    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
	public function postCartAdd(){
		$id = input('post.id');
		model('cart')->postCartAdd($id);
	}
	/**
     * 购物车调整选中状态
     * @url     /postCartStatus
     * @http    post  传入购物车id    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
	public function postCartStatus(){
		model('cart')->postCartStatus(input('post.'));
	}
	/**
     * 购物车全选全不选
     * @url     /postCartAll
     * @http    post  传入all   表示当前状态
     * @return  array of banner item , code 200
     * @throws  MissException
     */
	public function postCartAll(){
		model('cart')->postCartAll(input('post.all'));
	}
	/**
     * 购物车立即结算  支付
     * @url     /PayCart
     * @http    post  
     * @return  array of banner item , code 200
     * @throws  MissException
     */
	public function PayCart(){
		model('cart')->PayCart();
	}
	//支付回调接口
	public function redirectNotify()
    {
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$data = XmlToArr($xml);
		if (($data['return_code']=='SUCCESS') && ($data['result_code']=='SUCCESS')){
            if(db('order')->where(['id'=>$data['attach']])->update(['status'=>2])){
				$goods = db('order')->where(['id'=>$data['attach']])->value('goods');
				$goods = json_decode($goods);
				foreach($goods as $k=>$v){
					db('goods')->where(['id'=>$goods[$k]['goods_id']])->setInc('sales_sum',$goods[$k]['num']);
					db('goods')->where(['id'=>$goods[$k]['goods_id']])->setDec('store_count',$goods[$k]['num']);
				}
				$result = true;	
			}else{
				$result = false;	
			};
		}else{  
			$result = false;  
		}  
		// 返回状态给微信服务器  
		if($result){  
			$str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'; 
			 	
		}else{  
			$str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';	
		}
		echo exit($str);
    }
}







