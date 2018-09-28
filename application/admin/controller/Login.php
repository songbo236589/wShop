<?php
namespace app\admin\controller;
use app\common\exception\ParameterException;
use think\Controller;
use think\Request;
class Login extends Controller
{
    //空操作
    public function _empty(){
        return $this->error('该页面不存在，返回上次访问页面中...');
    }
	//判断用户是否登录，，如果登录过直接跳转到首页
    public function _initialize(){
        $session = session(config('common.admin_session'),'',config('common.admin_session_z'));
		if($session){
			$this->redirect('index/index');
		}
    }
   
	//管理员登录
    public function index(){
		//判断是否有post数据传入
        if(request()->isPost()){
			//获取数据
            $data = input('post.');
			//判断验证码是否正确
            if(!captcha_check($data['captcha'])){
                return new ParameterException(['msg'=>'验证码错误！']);
            }
			//调用M层方法对数据进行验证
            return model('admin')->login($data);
        }else{
			$config = db('config')->where(['id'=>1])->field('site_name,background_title,background_image')->find();
            return $this->fetch('',['config'=>$config]);
        }
    }
	
}