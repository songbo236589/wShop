<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class System extends Common
{
	
	
    //站点设置
    public function index(){
        if(request()->isPost()){
            $data = input('post.');
			$data['id'] = 1;
			$data['background_image'] = model('config')->editPicCommon($data['id'],$data['background_image'],'background_image');
			return model('config')->commonData($data,'系统配置成功!','系统配置失败，请重试!');
        }else{
			//把数据进行   格式转换
            $system = db('config')->where(['id'=>1])->find();
            return $this->fetch('',['system'=>$system]);
        }
    }

}
