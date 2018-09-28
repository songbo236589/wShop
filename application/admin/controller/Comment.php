<?php
namespace app\admin\controller;
class Comment extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()) {
			$add_time = input('post.add_time');
			$star = input('post.star');
			$key = input('post.key');
			$where=array();	
			if($star != '') $where['c.star']=$star;
			if($add_time) $where['c.add_time'] = timeData($add_time);
			if($key) $where['u.nickName|g.name'] = ['like','%'.$key.'%'];
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list = db('comment')
				->alias('c')
                ->join('user u','u.id = c.user_id','left')
                ->join('goods g','g.id = c.goods_id','left')
                ->where($where)
                ->order('c.id desc')
				->field('c.content,c.star,c.add_time,c.id,u.nickName,g.name')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('comment')->commentList($list);	
			return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
		}
        return $this->fetch('',['statuslist'=>model('comment')->commentData()]);
    }
	//单个删除
    public function del(){
		$data = input('post.');
		return model('comment')->CommonDel($data,'删除评论成功！','删除评论失败，请重试！');
    }
	//多个删除
    public function delall(){
		$data=input('param.ids/a');
        $map['id'] = array('in',$data);
		return model('comment')->CommonDel($data,'删除评论成功！','删除评论失败，请重试！');
    }
}