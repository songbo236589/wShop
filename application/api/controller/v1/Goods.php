<?php
namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
/**
 * Banner资源
 */ 
class Goods extends BaseController
{
    /**
     * 获取商品分类列表信息
     * @url     /getGoodsTypeList/:page
     * @http    get
     * @return  array of getGoodsTypeList item , code 200
     * @throws  MissException
     */
    public function getGoodsTypeList($page)
    { 
		model('goods')->getGoodsTypeList($page);
    }
	
	
	
}