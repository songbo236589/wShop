<?php
namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
class Region extends BaseController
{
    /**
     * 获取Banner信息
     * @url     /banner
     * @http    get
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getRegion()
    { 
		model('region')->getRegion();
    }
  
}