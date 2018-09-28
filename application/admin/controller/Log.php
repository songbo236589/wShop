<?php
namespace app\admin\controller;
class Log extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()) {
			$time = input('post.time');
			$key = input('post.key');
			$where=array();	
			if($key){
				$where['title|username'] = ['like','%'.$key.'%'];
			}
			if($time){
				$where['time'] = timeData($time);
			}
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list = db('log')
                ->where($where)
                ->order('id desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('log')->listAddTimeInfo($list);	
			return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
		}
        return $this->fetch('');
    }
    
	//单个删除
    public function del(){
		$data = input('post.');
		return model('log')->CommonDel($data,'删除日志成功！','删除日志失败，请重试！');
    }
	//多个删除
    public function delall(){
		$data=input('param.ids/a');
        $map['id'] = array('in',$data);
		return model('log')->CommonDel($data,'删除日志成功！','删除日志失败，请重试！');
    }
}