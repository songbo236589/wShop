<?php
namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
class Order extends BaseController
{
	
	/**
     * 订单列表
     * @url     /orderList/:page/:status
     * @http    get    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function orderList($page,$status)
    {	
		model('order')->orderList($page,$status);
    }
	/**
     * 取消订单
     * @url     /orderDelete
     * @http    post    id:订单id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function orderDelete()
    {	
		(new IDMustBePositiveInt())->goCheck();
		model('order')->locationStatus(input('post.id'));
    }
	/**
     * 订单管理去支付
     * @url     /orderDelete
     * @http    post    id:订单id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function orderPay()
    {	
		(new IDMustBePositiveInt())->goCheck();
		model('order')->orderPay(input('post.id'));
    }
	
	/**
     * 确定收货
     * @url     /orderConfirm
     * @http    post    id:订单id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function orderConfirm()
    {	
		(new IDMustBePositiveInt())->goCheck();
		model('order')->orderConfirm(input('post.id'));
    }
	/**
     * 商品评论
     * @url     /orderConfirm
     * @http    post    id:订单id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getOrderItem($id)
    {	
		(new IDMustBePositiveInt())->goCheck();
		model('order')->getOrderItem($id);
    }
	/**
     * 提交评论
     * @url     /commentT
     * @http    post    data   评论信息
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function commentT()
    {	
		model('order')->commentT(input('post.'));
    }
}







