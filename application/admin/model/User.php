<?php
namespace app\admin\model;
class User extends BaseModel
{
	public function genderData(){
		return ['1'=>'男','2'=>'女','0'=>'未知'];
	}
	//格式化添加时间和状态
	public function userListData($list){
		$genderData = $this->genderData();
		//格式化时间
		foreach ($list['data'] as $k=>$v){
			$list['data'][$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			$list['data'][$k]['gender'] = $genderData[$list['data'][$k]['gender']];
		}
		return $list;
	}
	
	
	
	
}
