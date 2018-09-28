<?php
namespace app\admin\controller;
use think\Controller;
class Common extends Controller
{
	 //空操作
    public function _empty(){
        return $this->error('该页面不存在，返回上次访问页面中...');
    }
	protected $adminRules;
	//初始化方法
    public function _initialize(){
		$session = session(config('common.admin_session'),'',config('common.admin_session_z'));
		if(!$session && !$session['id']){
			$this->redirect('login/index');
		}
		//获取当前的控制器名
		define('MODULE_NAME',strtolower(request()->controller()));
        //获取当前的方法名
		define('ACTION_NAME',strtolower(request()->action()));
		 //权限管理
        //当前操作权限ID
        if($session['id']!=1){
			//查询出当前的控制器/方法对应的id
            $HrefId = db('auth_rule')->field('id,authopen,title')->where('href',MODULE_NAME.'/'.ACTION_NAME)->find();
            //当前管理员权限
            $map['a.id'] = $session['id'];
			//查询出该管理员所有的权限
            $rules=db('admin')->alias('a')
                ->field('ag.rules')
				->join(config('database.prefix').'auth_group ag','a.group_id = ag.id','left')
                ->where($map)
                ->find();
				//dump($rules);exit;
				//将权限转化为数组
            $this->adminRules = explode(',',$rules['rules']);
			//判断权限
            if($HrefId['authopen']==1){
				//判断当前的权限是否在  权限数组中存在
                if(!in_array($HrefId['id'],$this->adminRules)){
                    $this->error('权限不足！',url('index'));
                }
            }
        }
		$site_name = db('config')->value('site_name');
		$this->assign('site_name',$site_name);
	}
	
	
	
	
	
}