<?php
namespace app\admin\model;
class AuthRule extends BaseModel
{
	//使用递归排序    权限列表
	public function lefts(){
		$cate=$this->order('sort asc')->select();
		
		return $this->menu($cate);
	}
	//使用递归方法  
	//参数1：数据
	//参数2：上下级分割标识
	//参数3：pid默认值
	//参数4：默认级别0
	//参数5：样式调整  最终返回排序结果
	public function menu($cate , $lefthtml = '|— ' , $pid=0 , $lvl=0, $leftpin=0 ){
		static $arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$v['ltitle']=$v['lefthtml'].$v['title'];
				$arr[]=$v;
				$this->menu($cate,$lefthtml,$v['id'], $lvl+1 ,$leftpin+20);
			}
		}
		return $arr;
	}
	//使用递归进行获取当前的删除id的其他子id
	public function dels($id){
		$cate=$this->select();
		return $this->delsort($cate,$id);
	}
	//传入参数
	//参数1：数据
	//参数2：id（当前删除的id）
	public function delsort($cate,$id){
		//创建新数组
		static $arr=array();
		foreach($cate as $v){
			if($v['pid']==$id){
				$arr[]=$v['id'];
				$this->delsort($cate,$v['id']);
			}
		}
		return $arr;
	}
	//权限配置规则
	public function authlist(){
		$admin_rule=$this->where(['authopen'=>1])->field('id,pid,title')->order('sort asc')->select();
        $rules = db('auth_group')->where('id',input('id'))->value('rules');
        return $this->auth($admin_rule,$pid=0,$rules);
	}
	//参数1：权限列表信息
	//参数2：pid =0 顶级权限  
	//参数3：现在已拥有权限列表
	public function auth($cate , $pid=0,$rules){
		//创建数据
        static $arr=array();
		//把已拥有的权限转换为数组
        $rulesArr = explode(',',$rules);
		//便利所有数据
        foreach ($cate as $v){
			//判断顶级目录
            if($v['pid']==$pid){
				//判断该目录是否在拥有列表中存在
                if(in_array($v['id'],$rulesArr)){
					//给它默认选中
                    $v['checked']=true;
                }
				//表示打开状态
                $v['open']=true;
				//放入新数组
                $arr[]=$v;
				//自调用  找出下级
                $this->auth($cate, $v['id'],$rules);
            }
        }
        return $arr;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}