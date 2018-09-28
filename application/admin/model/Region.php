<?php
namespace app\admin\model;
class Region extends BaseModel
{
	
	//通过省级id查询出 对应的所有的市和区县
	public function provinceData($data){
		$idshi=$this->where(['pid'=>$data['id']])->field('id')->select();
		if($idshi){
			$idshis=[];
			foreach($idshi as $k=>$v){
				$idshis[]=$v['id'];
			}
			//查询出市对应的县区
			$idxian=$this->where(['pid'=>['in',implode(',',$idshis)]])->field('id')->select();
			$idxians=[];
			foreach($idxian as $k=>$v){
				$idxians[]=$v['id'];
			} 
			$ids=array_merge($idshis,$idxians);
			$ids[]= (int)$data['id'];
		}else{
			$ids = $data['id'];
		}
		return $ids;
	}
	//通过市级列表 查询出对应的省	
	public function cityShengData($list){
		$lists=db('region')->field('id,name')->where(['type'=>1])->select();
		foreach($list['data'] as $k1=>$v1){
			foreach($lists as $k=>$v){
				if($v['id']==$v1['pid']){
					$list['data'][$k1]['pid']=$v['name'];
				}
			}
		}
		return $list;
	}
	//通过市级id    查询出所有的区/县
	public function cityData($data){
		//查询出市对应的县区
		$idxian = $this->where(['pid'=>$data['id']])->field('id')->select();
		
		if($idxian){
			$idxians=[];
			foreach($idxian as $k=>$v){
				$idxians[]=$v['id'];
			}
			$idxians[]=(int)$data['id'];
		}else{
			$idxians = $data['id'];
		}
		return $idxians;
	}
	//整理省市区的关联关系
	public function countyData($list){
		//所有的省	
		$lists = $this->field('id,name,pid')->where(['type'=>1])->select();
		//所有的市
		$listss = $this->field('id,name,pid')->where(['type'=>2])->select();
		//所有的区/县
		foreach($list['data'] as $k1=>$v1){
			//所有的市
			foreach($listss as $k2=>$v2){
				if($v1['pid']==$v2['id']){
					$list['data'][$k1]['pid']=$v2['name'];
					//所有的省
					foreach($lists as $k3=>$v3){
						if($v2['pid']==$v3['id']){
							$list['data'][$k1]['pid1']=$v3['name'];
						}
					}
				}
			}
		}
		return $list;
	}
	
	
	
	
	
	
	
	
}