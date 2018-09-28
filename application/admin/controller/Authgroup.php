<?php
namespace app\admin\controller;
use app\admin\validate\AuthGroup as AuthGroupV;
use app\common\exception\ParameterException;
use think\Db;
class Authgroup extends Common
{
    //用户组管理
    public function index(){
        if(request()->isPost()){
            $list = db('auth_group')->select();
			foreach($list as $k=>$v){
				$list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
				if($list[$k]['edit_time']){
					$list[$k]['edit_time'] = date('Y-m-d H:i:s',$v['edit_time']);
				}else{
					$list[$k]['edit_time'] = config('common.no_edit');
				}
			}
            return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list,'rel'=>1];
        }
        return view();
    }
   
    //添加组名称
    public function add(){
        if(request()->isPost()){
			$data = input('post.');
			$dataV = (new AuthGroupV())->goCheck('add',$data);
			if($dataV !== false) return $dataV;
			return model('auth_group')->commonData($data,'添加用户组成功！','添加用户组失败，请重试！');
        }else{
            return $this->fetch('');
        }
    }
    //修改分组
    public function edit(){
        if(request()->isPost()) {
            $data=input('post.');
			$dataV = (new AuthGroupV())->goCheck('edit',$data);
			if($dataV !== false) return $dataV;
			return model('auth_group')->commonData($data,'编辑用户组成功！','编辑用户组失败，请重试！');
        }else{
            $info =db('auth_group')->where(['id'=>input('id')])->find();
            return $this->fetch('',['info'=>$info]);
        }
    }
	 //删除管理员分组
    public function del(){
		$data=input('post.');
		$dataV = (new AuthGroupV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		return model('auth_group')->CommonDel($data,'删除用户组成功！','删除用户组失败，请重试！');
    }
	//调整状态
    public function status(){
		$data=input('post.');
		$dataV = (new AuthGroupV())->goCheck('state',$data);
		if($dataV !== false) return $dataV;
		return model('auth_group')->commonState($data,'调整用户组状态成功！','调整用户组状态失败，请重试！');
    }
    //分组配置规则
    public function access(){
		if(request()->isPost()){
			//获取权限
			$data = input('post.');
			//判断权限是否存在
			if(!$data['rules']){
				return new ParameterException(['msg'=>'请选择权限！']);
			}
			$dataV = (new AuthGroupV())->goCheck('del',$data);
			if($dataV !== false) return $dataV;
			//进行更改
			return model('auth_group')->commonData($data,'配置规则成功！','配置规则失败，请重试！');
		}else{
			$arr=model('auth_rule')->authlist();
			$arr[] = array(
            "id"=>0,
            "pid"=>0,
            "title"=>"全部",
            "open"=>true
			);
			return $this->fetch('',['data'=>json_encode($arr,true)]);
		}
        
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   
}