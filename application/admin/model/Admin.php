<?php
namespace app\admin\model;
use app\common\exception\ParameterException;
use app\common\lib\IAuth;
use think\Model;
class Admin extends BaseModel
{
	//管理员数据格式化
	public function adminDataList($list){
		foreach ($list['data'] as $k=>$v){
			$list['data'][$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			if($list['data'][$k]['edit_time']){
				$list['data'][$k]['edit_time'] = date('Y-m-d H:i:s',$v['edit_time']);
			}else{
				$list['data'][$k]['edit_time'] = config('common.no_edit');
			}
			if(!$list['data'][$k]['login_ip']){
				$list['data'][$k]['login_ip'] = '从未登陆过';
			}	
		}
		return $list;
	}
	//管理员登录方法
	public function login($data){
		//查看用户名是否存在
		$user=$this->where(['username'=>$data['username']])->field('id,username,pwd,is_open,group_id')->find();
		if($user){
			//查看密码是否正确 
			if($user['pwd'] == IAuth::setPassword($data['password'])){
				$group=db('auth_group')->where(['id'=>$user['group_id']])->field('status')->find();
				if($group['status']==1){
					if($user['is_open']==1){
						//把管理员信息存入session
						session(config('common.admin_session'),$user,config('common.admin_session_z'));
						$dataUpdate['id'] = $user['id'];
						$dataUpdate['login_ip'] = request()->ip();
						$this->update($dataUpdate);
						return new ParameterException([
							'code'=>'200',
							'msg'=>'登录成功！',
							'errorCode'=>'8888',
							'url' => url('index/index')
						]);
					}else{
						return new ParameterException([
							'msg'=>'管理员组或管理员本身状态被关闭！']);
					}
				}else{
					return new ParameterException([
						'msg'=>'管理员组或管理员本身状态被关闭！']);
				}
			}else{
				return new ParameterException([
					'msg'=>'账号或密码错误！']);
			}
		}else{
			return new ParameterException([
				'msg'=>'账号或密码错误！']);
		}
	}
	//管理员  添加
	public function adminData($data,$success,$error){
		//自定义md5加密
		$data['pwd'] =IAuth::setPassword($data['pwd']);
		$data['ip'] = request()->ip();//获取当前ip
		$data = unmArr($data);
		$data['add_time'] = $_SERVER['REQUEST_TIME'];
		$id = $this->insertGetId($data);
		$session = session(config('session.admin_session'),'',config('session.admin_session_z'));	
		if($id){
			$admin_ids = $this->where(['id'=>$session['id']])->value('admin_ids');
			if($admin_ids){
				$admin_ids = $admin_ids .','.$id;
			}else{
				$admin_ids = $id;
			}
			$admin = ['id'=>$session['id'],'admin_ids'=>$admin_ids];
			if($this->update($admin)){
				$this->commonLog();
				return new ParameterException([
					'code'=>'200',
					'msg'=>$success,
					'errorCode'=>'8888',
					'url' => url('index')
				]);
			}
			
		}
		return new ParameterException([
			'msg'=>$error
		]);
		
	}
	//初始化密码  传入参数为数组
	public function pwds($data,$success,$error){
		$data['pwd'] =IAuth::setPassword('123456');
		return $this->commonData($data,$success,$error);
	}
	//修改管理员密码
	public function passwordS($data){
		//自定义md5加密
		$data['pwd'] =IAuth::setPassword($data['pwd']);
		if($this->update($data) !== false){
			return new ParameterException([
				'code'=>'200',
				'msg'=>'密码修改成功！',
				'errorCode'=>'8888',
				'url' => url('main')
			]);
		}else{
			return new ParameterException([
				'msg'=>'修改密码失败，请重试！'
			]);
		};
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
