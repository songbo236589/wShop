<?php
namespace app\admin\controller;
use app\admin\validate\AuthRule as AuthRuleV;
use think\Db;
class Authrule extends Common
{
	//权限管理
	public function index(){
        if(request()->isPost()){
			//调用M层对权限表进行排序
            $arr = model('auth_rule')->lefts();
			//输出表格
            return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$arr,'rel'=>1];
        }
        return view();
    }
	//添加
    public function add(){
		//判断是否有post提交的数据
        if(request()->isPost()){
			//获取数据
            $data = input('post.');
			$dataV = (new AuthRuleV())->goCheck('add',$data);
            if($dataV !== false) return $dataV;
			return model('auth_rule')->commonData($data,'添加权限成功！','添加权限失败，请重试！');
        }else{
            //调用M层对权限表进行排序
            $arr = model('auth_rule')->lefts();
            return $this->fetch('',['admin_rule'=>$arr]);
        }
    }
	//编辑
	public function edit(){
        if(request()->isPost()){
            $data = input('post.');
			$dataV = (new AuthRuleV())->goCheck('edit',$data);
			if($dataV !== false) return $dataV;
			return model('auth_rule')->commonData($data,'修改权限成功！','修改权限失败，请重试！');
        }else{
            $admin_rule=model('auth_rule')->where(['id'=>input('id')])->field('id,href,title,icon,sort,menustatus')->find();
            return $this->fetch('',['rule'=>$admin_rule]);
        }
    }
	//排序更新操作
    public function ruleOrder(){
		$data=input('post.');
        $dataV = (new AuthRuleV())->goCheck('sorts',$data);
        if($dataV !== false) return $dataV;
        return model('auth_rule')->commonOrder($data,'权限排序成功！','权限排序失败，请重试！');
    }
	//调整是否在左侧显示菜单状态
    public function ruleState(){
		$data=input('post.');
        $dataV = (new AuthRuleV())->goCheck('menustatus',$data);
        if($dataV !== false) return $dataV;
        return model('auth_rule')->commonState($data,'调整验证权限成功！','调整验证权限失败，请重试！');
    }
	//调整是否需要验证权限
    public function ruleTz(){
		$data=input('post.');
		$dataV = (new AuthRuleV())->goCheck('authopen',$data);
		if($dataV !== false) return $dataV;
		return model('auth_rule')->commonState($data,'调整验证权限成功！','调整验证权限失败，请重试！');
    }
	//删除操作
    public function ruleDel(){
		//获取id
		$id=input('post.id');
        $dataV = (new AuthRuleV())->goCheck('del',['id'=>$id]);
        if($dataV !== false) return $dataV;
		//查询子级
		$model=model('auth_rule')->dels($id);
		//放入自身
		$model[]=$id;
        return model('auth_rule')->CommonDel($model,'删除权限成功！','删除权限失败，请重试！');
    }

    
	
	
	
	
	
	
	  
}