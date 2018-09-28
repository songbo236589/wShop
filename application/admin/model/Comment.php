<?php
namespace app\admin\model;
class Comment extends BaseModel
{
	public function commentData(){
		return ['1'=>'非常差','2'=>'差','3'=>'一般','4'=>'好','5'=>'非常好'];
	}
	//格式化添加时间和状态
	public function commentList($list){
		$commentData = $this->commentData();
		//格式化时间
		foreach ($list['data'] as $k=>$v){
			$list['data'][$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			$list['data'][$k]['star'] = $commentData[$list['data'][$k]['star']];
		}
		return $list;
	}
	
	
}
