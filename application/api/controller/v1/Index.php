<?php
namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
/**
 * Banner资源
 */ 
class Index extends BaseController
{
    /**
     * 获取Banner信息
     * @url     /banner
     * @http    get
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getBanner()
    { 
		model('goods')->getBanner();
    }
	/**
     * 获取商品列表信息
     * @url     /getGoods/:type/:page
     * @http    get
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getGoods($type,$page)
    {	
		model('goods')->getGoods($type,$page);
    }
	
	/**
     * 获取商品详情页信息
     * @url     /getGoodsContent
     * @http    post     id传入商品id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getGoodsContent()
    {	
		(new IDMustBePositiveInt())->goCheck();
		model('goods')->getGoodsContent(input('post.id'));
    }
}