<?php
namespace app\admin\controller;
use app\admin\validate\Admin as AdminV;
class Auth extends Common
{
    //管理员列表
    public function index(){
		//获取数据
        if(request()->isPost()){
			$key = input('post.key');
			$add_time = input('post.add_time');
			$status = input('post.status');
			$county_id = input('post.county_id');
			$city_id = input('post.city_id');
			$province_id = input('post.province_id');
			$where=array();	
			if($key){
				$where['a.name|a.username'] = ['like','%'.$key.'%'];
			}
			if($county_id != ''){
				$where['a.county_id']=$county_id;
			}
			if($city_id != ''){
				$where['a.city_id']=$city_id;
			}
			if($province_id != ''){
				$where['a.province_id']=$province_id;
			}
			if($status != ''){
				$where['a.is_open']=$status;
			}
			if($add_time){
				$where['a.add_time'] = timeData($add_time);
			}
			$page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list=db('admin')
				->alias('a')
				->where($where)
                ->join('auth_group ag','a.group_id = ag.id','left')
				->join('region p','a.province_id = p.id','left')
				->join('region c','a.city_id = c.id','left')
				->join('region co','a.county_id = co.id','left')
                ->field('a.id,a.username,a.ip,a.add_time,a.is_open,a.login_ip,a.edit_time,a.name,a.email,co.name as county,c.name as city,p.name as province,a.address,ag.title')
				->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('admin')->adminDataList($list);	
            return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
       return $this->fetch('',['statuslist'=>model('admin')->statusCommonData(),'province'=>db('region')->where(['type'=>1])->select()]);
    }
	//管理员添加
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
			$dataV = (new AdminV())->goCheck('add',$data);
			if($dataV !== false) return $dataV;
			return model('admin')->adminData($data,'添加管理员成功','添加管理员失败，请重试！');
		}else{
            $auth_group=db('auth_group')->select();
            return $this->fetch('',[
						'auth_group'=>$auth_group,
						'province'=>db('region')->where(['type'=>1])->select()
					]);
        }
    }
	public function getRegion(){
		return db('region')->where(['pid'=>input('pid')])->field('id,name')->select();
	}
	//管理员编辑
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
			$dataV = (new AdminV())->goCheck('edit',$data);
			if($dataV !== false) return $dataV;
			return model('admin')->commonData($data,'编辑管理员成功','编辑管理员失败，请重试！');
        }else{
            $info =db('admin')->where(['id'=>input('id')])->field('id,username,group_id,province_id,city_id,county_id,name,email,address')->find();
			$province = db('region')->where(['type'=>1])->select();
			$city = db('region')->where(['pid'=>$info['province_id']])->select();
			$county = db('region')->where(['pid'=>$info['city_id']])->select();
            $auth_group=db('auth_group')->select();
            return $this->fetch('',[
				'province'=>$province,
				'city'=>$city,
				'county'=>$county,
				'auth_group'=>$auth_group,
				'info'=>$info
			]);
        }
    }
    //删除管理员
    public function del(){
		$data=input('post.');
		$dataV = (new AdminV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		return model('admin')->CommonDel($data,'删除管理员成功！','删除管理员失败，请重试！');
    }
    //修改管理员状态
    public function adminState(){
		$data=input('post.');
		$dataV = (new AdminV())->goCheck('state',$data);
		if($dataV !== false) return $dataV;
		return model('admin')->commonState($data,'调整管理员状态成功！','调整管理员状态失败，请重试！');
    }
    //管理员密码初始化
	public function passwordS(){
		$data = input('post.');
		$dataV = (new AdminV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		return model('admin')->pwds($data,'密码初始化成功','密码初始化失败，请重试！');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   
}