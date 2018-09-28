<?php
namespace app\admin\controller;
use app\admin\validate\Region as RegionV;
class City extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
	public function index(){
        if(request()->isPost()){
            $key = input('key');
			$pid = input('pid');	
			$where=array('type'=>2);
			$status   = input('post.status');	
			if($status != ''){
				$where['status'] = $status;
			}
			if($key){
				$where['name']=['like', "%" . $key . "%"];
			}
			if($pid != ''){
				$where['pid']=$pid;
			}
			//默认第一页
			$page =input('page')?input('page'):1;
			//默认每页显示10条
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            //连表   会员等级表
            $list=db('region')
                ->field('id,name,pid,status')
                ->where($where)
                ->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('region')->cityShengData($list);
            //返回数据
            return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
		$province=db('region')->field('id,name')->where(['type'=>1])->select();
        return $this->fetch('',['province'=>$province,'open'=>model('region')->statusCommonData()]);
    }
	//添加
    public function add(){
        if(request()->isPost()){
            $data=input('post.');
			$dataV = (new RegionV())->goCheck('adds',$data);
			if($dataV !== false) return $dataV;
			return model('region')->commonData($data,'添加市成功！','添加市失败，请重试！');	
        }else{
			$province=db('region')->field('id,name')->where(['type'=>1])->select();	
            return $this->fetch('',['province'=>$province]);
        }
    }
	//编辑
	public function edit(){
		if(request()->isPost()) {
			$data=input('post.');
			$dataV = (new RegionV())->goCheck('edits',$data);
			if($dataV !== false) return $dataV;
			return model('region')->commonData($data,'编辑市成功！','编辑市失败，请重试！');
        }else{
            $id = input('id');
            $info =db('region')->field('id,name,pid')->where(['id'=>$id])->find();
			$province=db('region')->field('id,name,pid')->where(['type'=>1])->select();
            return $this->fetch('',['info'=>$info,'province'=>$province]);
        }
    }
	//删除
    public function del(){
		$data = input('post.');
		$dataV = (new RegionV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		$idxians = model('region')->cityData($data);
		return model('region')->CommonDel($idxians,'删除市成功！','删除市失败，请重试！');
    }
	//调整状态
    public function editState(){
		$data = input('post.');
		$dataV = (new RegionV())->goCheck('status',$data);
		if($dataV !== false) return $dataV;
		return model('region')->commonState($data,'调整状态成功！','调整状态失败，请重试！');
    }

}