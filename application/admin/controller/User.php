<?php
namespace app\admin\controller;
class User extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()){
			$add_time = input('post.add_time');
			$gender = input('post.gender');
			$nickName = input('post.nickName');
			$where=array();	
			if($gender != '') $where['gender']=$gender;
			if($add_time) $where['add_time'] = timeData($add_time);
			if($nickName) $where['nickName'] = ['like','%'.$nickName.'%'];
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list = db('user')
                ->where($where)
                ->order('id desc')
				->field('id,add_time,nickName,gender,language,city,province,country,avatarUrl')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('user')->userListData($list);	
			return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
		}
        return $this->fetch('',['statuslist'=>model('user')->genderData()]);
    }
    
}