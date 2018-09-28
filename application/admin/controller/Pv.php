<?php
namespace app\admin\controller;
class Pv extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
		
        if(request()->isPost()){
			$type = input('post.type');
			$where = array();
			if($type == 1){
				$kaddtime = date("Y-m-d", strtotime("-1 month"));
				$xaddtime = date("Y-m-d", strtotime("+1 day"));
				$where['date'] = [['>',$kaddtime],['<',$xaddtime]];
			}
			if($type == 2){
				$kaddtime = date("Y-m-d", strtotime("-1 year"));
				$xaddtime = date("Y-m-d", strtotime("+1 day"));
				$where['date'] = [['>',$kaddtime],['<',$xaddtime]];
			}
			$data = db('pv')->where($where)->order('id asc')->field('date,num')->select();
			$datas = array();
			foreach($data as $k=>$v){
				$datas[$k][] = $v['date'];
				$datas[$k][] = $v['num'];
			}
			return $datas;
		}
        return $this->fetch('',[
			'type'=>[
			'1'=>'最近一个月',
			'2'=>'最近一年'
		]]);
    }
}