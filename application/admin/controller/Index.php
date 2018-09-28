<?php
namespace app\admin\controller;
use app\admin\validate\Admin as AdminValidate;
use app\common\exception\ParameterException;
use think\Controller;
use think\Db;
class Index extends Common
{
	//自调用  防止报错
    public function _initialize(){
        parent::_initialize();
    }
    public function index()
    {
		//取出session
        $session = session(config('common.admin_session'),'',config('common.admin_session_z'));
        //导航
        // 获取缓存数据
        $authRule = cache('authRule');
		//判断缓存是否存在
        if(!$authRule){
			//如果不存在就把表中的数据存入缓存中    时间为1小时
            $authRule = db('auth_rule')->where('menustatus=1')->order('sort')->select();
            cache('authRule', $authRule, 3600);
       }
        //声明数组
        $menus = array();
		//比例数据
        foreach ($authRule as $key=>$val){
            //组合跳转路径
			$authRule[$key]['href'] = url($val['href']);
            //判断是否为顶级目录
			if($val['pid']==0){
				if($session['id']!=1){
					//给出该管理员的权限
                    if(in_array($val['id'],$this->adminRules)){
                        $menus[] = $val;
                    }
                }else{
					//超级管理员拥有所有权限
                    $menus[] = $val;
                }
            }
        }
		//dump($authRule);exit;
		//便利顶级目录
        foreach ($menus as $k=>$v){
            //便利所有的目录
			foreach ($authRule as $kk=>$vv){
                //找出子级目录
				if($v['id']==$vv['pid']){
                    if($session['id']!=1) {
						//给出应有权限
                        if (in_array($vv['id'], $this->adminRules)) {
                            $menus[$k]['children'][] = $vv;
                        }
                    }else{
						//给出所有权限
                        $menus[$k]['children'][] = $vv;
                    }
                }
            }
        }
		
		//以具有json格式的字符串输出所有数据
        //$this->assign('menus', json_encode($menus,true));
		$background_title = db('config')->value('background_title');
        return $this->fetch('',
		[
		'background_title' =>$background_title,
		'session'=>$session,
		'menus'=>json_encode($menus,true)
		]);
		//return view();
    }
    public function main(){

        $session = session(config('common.admin_session'),'',config('common.admin_session_z'));
        $version = Db::query('SELECT VERSION() AS ver');//获取MySQli的版本
        $config  = [
            'url'             => $_SERVER['HTTP_HOST'],//获取域名
            'document_root'   => $_SERVER['DOCUMENT_ROOT'],//获取网站目录
            'server_os'       => PHP_OS,//服务器操作系统
            'server_port'     => $_SERVER['SERVER_PORT'],//服务器端口
            'server_ip'       => $_SERVER['SERVER_ADDR'],//服务器IP
            'server_soft'     => $_SERVER['SERVER_SOFTWARE'],//WEB运行环境
            'php_version'     => PHP_VERSION,//运行PHP版本
            'mysql_version'   => $version[0]['ver'],//MySQli的版本
            'max_upload_size' => ini_get('upload_max_filesize')//最大上传限制
        ];
		//输出数据
        //$this->assign();
        return $this->fetch('',['config'=>$config,'session'=>$session]);
    }
	//修改密码
	public function edit(){
		//取出$session
        $session = session(config('common.admin_session'),'',config('common.admin_session_z'));
		if(request()->isPost()){
			$data=input('post.');
            //进行验证
            $admin = (new AdminValidate())->goCheck('pwds',$data);
            if($admin !== false) return $admin;
            return model('admin')->passwordS($data);
		}else{
			return $this->fetch('',['session'=>$session]);
		}
	}
	//清除缓存
    public function clear(){
		//获取缓存目录路径
        $R = RUNTIME_PATH;
		//调用清除缓存方法并传入目录路径
        if ($this->_deleteDir($R)) {
			$T =str_replace('runtime','public',RUNTIME_PATH) . 'uploadss' . DS; 
			$this->_deleteDir($T);
			mkdir($T , 0777,true);
            return new ParameterException([
                'code'=>'200',
                'msg'=>'清除缓存成功！',
                'errorCode'=>'8888',
                'url' => url('admin/index/index')]);
        } else {
            return new ParameterException([
                'msg'=>'清除缓存失败，请重试！',
                ]);
        }
    }
	//清除缓存方法
    private function _deleteDir($R)
    {
		//打开文件目录  然后读取其内容
        $handle = opendir($R);
		//便利文件目录  并判断目录是否存在
        while (($item = readdir($handle)) !== false) {
			//判断目录结构
            if ($item != '.' and $item != '..') {
				//判断当前的文件是目录还是文件
                if (is_dir($R . '/' . $item)) {
					//如果是目录再次  调用自己继续便利
                    $this->_deleteDir($R . '/' . $item);
                } else {
					//删除文件
                    if (!unlink($R . '/' . $item))
                        die('error!');
                }
            }
        }
		//closedir() 函数关闭目录
        closedir($handle);
		//rmdir函数删除空的目录。成功返回true
        return rmdir($R);
    }

    //退出登陆
    public function logout(){
        session(null,config('common.admin_session_z'));
		//跳转到登陆页面
        $this->redirect('login/index');
    }
    
}
