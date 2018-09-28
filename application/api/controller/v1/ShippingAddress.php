<?php
namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
class ShippingAddress extends BaseController
{
	/**
     * 添加收货地址
     * @url     /addLocation
     * @http    post    添加数组信息
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function addLocation()
    {	
		model('shipping_address')->addLocation(input('post.'));
    }
	/**
     * 收货地址列表
     * @url     /locationList
     * @http    get    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function locationList()
    {	
		model('shipping_address')->locationList(0);
    }
	/**
     * 收货地址选择
     * @url     /locationStatus
     * @http    post    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function locationStatus()
    {	
		model('shipping_address')->locationStatus(input('post.id'));
    }
	/**
     * 收货地址删除
     * @url     /locationDelete
     * @http    post    
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function locationDelete()
    {	
		model('shipping_address')->locationDelete(input('post.id'));
    }
	
}







