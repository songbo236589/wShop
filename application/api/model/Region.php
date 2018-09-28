<?php
namespace app\api\model;
use app\common\exception\ParameterException;
class Region extends BaseModel
{
   public function getRegion(){
	  $region = $this
			->where(['status'=>1])
			->field('id,pid,name,type')
			->order('id desc')
			->select();
	   if($region){
		   throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$region
			]);
	   }else{
			throw new ParameterException([
				'msg'=>'数据获取错误！'
			]);
	   }	
   }
}
