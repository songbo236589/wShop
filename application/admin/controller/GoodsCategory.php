<?php
namespace app\admin\controller;
use app\admin\validate\GoodsCategory as DataV;
class GoodsCategory extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()) {
			$time = input('post.time');
			$status = input('post.status');
			$where=array();	
			if($status != '') $where['status']=$status;
			if($time) $where['add_time'] = timeData($time);
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list = db('goods_category')
                ->where($where)
                ->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('goods_category')->listDateInfo($list);	
			return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
		}
        return $this->fetch('',['statuslist'=>model('goods_category')->statusCommonData()]);
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
			$dataV = (new DataV())->goCheck('add',$data);
			if($dataV !== false) return $dataV;
			return model('goods_category')->commonData($data,'添加商品分类成功！','添加商品分类失败，请重试！');
        }else{
            return $this->fetch('');
        }
    }
    public function edit(){
        if(request()->isPost()) {
            $data = input('post.');
			$dataV = (new DataV())->goCheck('edit',$data);
			if($dataV !== false) return $dataV;
			return model('goods_category')->commonData($data,'编辑商品分类成功！','编辑商品分类失败，请重试！');
        }else{
            $info=db('goods_category')->where(array('id'=>input('id')))->field('id,name,sort')->find();
            return $this->fetch('',['info'=>$info]);
        }
    }
    public function listOrder(){
		$data=input('post.');
		$dataV = (new DataV())->goCheck('sort',$data);
        if($dataV !== false) return $dataV;
        return model('goods_category')->commonOrder($data,'商品分类排序成功！','商品分类排序失败，请重试！');
    }
    public function editState(){
		$data = input('post.');
		$dataV = (new DataV())->goCheck('status',$data);
		if($dataV !== false) return $dataV;
		return model('goods_category')->commonState($data,'调整商品分类状态成功！','调整商品分类状态失败，请重试！');
    }
}