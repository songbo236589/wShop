<?php
namespace app\admin\model;
class GoodsCategory extends BaseModel
{
	public function goodsCategoryList(){
		return $this->where(['status'=>1])->order('sort asc')->field('id,name')->select();
	}
	
		
		
	
	
	
	
	
}
