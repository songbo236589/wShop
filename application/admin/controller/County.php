<?php
namespace app\admin\controller;
use app\admin\validate\Region as RegionV;
class County extends Common
{
   //默认执行的方法
    public function _initialize(){
        parent::_initialize();
    }
	public function index(){
        if(request()->isPost()){
            $key = input('key');
			$pid = input('pid');
			$pid1 = input('pid1');		
			$where=array('type'=>3);
			$status   = input('post.status');	
			if($status != ''){
				$where['status'] = $status;
			}
			if($key){
				$where['name']=['like', "%" . $key . "%"];
			}
			if($pid){
				$pids=db('region')->field('id')->where(['pid'=>$pid])->select();
				foreach($pids as $k=>$v){
					$pidss[]=$v['id'];
				}
				$pidlist=implode(',',$pidss);
				$where['pid']=['in',$pidlist];
				if($pid && $pid1){
					$where['pid']=explode(':',$pid1)[1];
				}
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
			$list = model('region')->countyData($list);
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
			$data['pid']=explode(':',$data['pid'])[1];
			$dataV = (new RegionV())->goCheck('adds',$data);
			if($dataV !== false) return $dataV;
			$data['type']=3;
			return model('region')->commonData($data,'添加区/县成功！','添加区/县失败，请重试！');	
        }else{
			//查询出所属省
			$province = db('Region')->where ( array('pid'=>1) )->select();
            return $this->fetch('',['province'=>$province]);
        }
    }
	 public function getRegion(){
        $map['pid'] = input("pid");
        $list=db("region")->field('id,name')->where($map)->select();
        return $list;
    }
	//编辑
	public function edit(){
		if(request()->isPost()) {
			$data=input('post.');
			$dataV = (new RegionV())->goCheck('edits',$data);
			if($dataV !== false) return $dataV;
			return model('region')->commonData($data,'编辑区/县成功！','编辑区/县失败，请重试！');
        }else{
            $id = input('id');
			//查询出一条信息
            $info =db('region')->field('id,name,pid')->where(['id'=>$id])->find();
			//查询出所有的市
			$city =db('region')->field('pid')->where(['id'=>$info['pid']])->find();
			$citys =db('region')->field('id,name')->where(['pid'=>$city['pid']])->select();
			//查询出所有的省
			$info['pid1']=$city['pid'];
			$province=db('region')->field('id,name,pid')->where(['type'=>1])->select();
            return $this->fetch('',['info'=>$info,'province'=>$province,'citys'=>$citys]);
        }
    }
	//删除
    public function del(){
		$data = input('post.');
		$dataV = (new RegionV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		return model('region')->CommonDel($data,'删除区/县成功！','删除区/县失败，请重试！');
		
    }
	//调整状态
    public function editState(){
		$data = input('post.');
		$dataV = (new RegionV())->goCheck('status',$data);
		if($dataV !== false) return $dataV;
		return model('region')->commonState($data,'调整状态成功！','调整状态失败，请重试！');
    }

    
}