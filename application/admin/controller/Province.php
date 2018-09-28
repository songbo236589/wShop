<?php
namespace app\admin\controller;
use think\Db;
use app\admin\validate\Region as RegionV;
class Province extends Common
{

   //默认执行的方法
    public function _initialize(){
        parent::_initialize();
    }
    //广告位列表
	public function index(){
        if(request()->isPost()){
            $key = input('key');          
			$where=array('type'=>1);
			$status   = input('post.status');	
			if($status != ''){
				$where['status'] = $status;
			}
			if($key){
				$where['name']=['like', "%" . $key . "%"];
			}
			//默认第一页
			$page =input('page')?input('page'):1;
			//默认每页显示10条
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            //连表   会员等级表
            $list=db('region')
                ->field('id,name,status')
                ->where($where)
                ->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            //返回数据
            return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
		
        return $this->fetch('',['open'=>model('region')->statusCommonData()]);
    }
	//添加
    public function add(){
        if(request()->isPost()){
            $data=input('post.');
			$data['pid']=1;
			$data['type']=1;
			$dataV = (new RegionV())->goCheck('add',$data);
			if($dataV !== false) return $dataV;
			return model('region')->commonData($data,'添加省成功！','添加省失败，请重试！');
        }else{
            return $this->fetch('from',['title'=>'添加省级','info'=>'null']);
        }
    }
	//编辑
	public function edit(){
		if(request()->isPost()) {
            $data=input('post.');
			$dataV = (new RegionV())->goCheck('edit',$data);
			if($dataV !== false) return $dataV;
			return model('region')->commonData($data,'编辑省成功！','编辑省失败，请重试！');
        }else{
            $id = input('id');
            $info =db('region')->field('id,name')->where(['id'=>$id])->find();
            return $this->fetch('from',['info'=>json_encode($info,true),'title'=>'编辑省级']);
        }
    }
	//删除
    public function del(){
		$data = input('post.');
		$dataV = (new RegionV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		//查询出省对应的市
		$ids = model('region')->provinceData($data);
		return model('region')->CommonDel($ids,'删除省成功！','删除省失败，请重试！');
    }
	//调整状态
    public function editState(){
		$data = input('post.');
		$dataV = (new RegionV())->goCheck('status',$data);
		if($dataV !== false) return $dataV;
		return model('region')->commonState($data,'调整状态成功！','调整状态失败，请重试！');
    }

    
}