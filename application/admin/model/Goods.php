<?php
namespace app\admin\model;
class Goods extends BaseModel
{
	
	//上架下架
	public function statusGoods(){
		return ['1'=>'上架','0'=>'下架'];
	}
	//推荐banner
	public function openBannerData(){
		return ['1'=>lang('yes'),'0'=>lang('no')];
	}	
		
	
	
	
	
	
}
