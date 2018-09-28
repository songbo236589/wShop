<?php
namespace app\admin\model;
use app\common\exception\ParameterException;
use think\Model;
class BaseModel extends Model
{
	//状态
	public function statusCommonData(){
		return ['1'=>lang('enabled'),'0'=>lang('disabled')];
	}
	public function listAddTimeInfo($list){
		foreach ($list['data'] as $k=>$v){
			$list['data'][$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
		}
		return $list;
	}
	public function listDateInfo($list){
		foreach ($list['data'] as $k=>$v){
			$list['data'][$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			if($list['data'][$k]['edit_time']){
				$list['data'][$k]['edit_time'] = date('Y-m-d H:i:s',$v['edit_time']);
			}else{
				$list['data'][$k]['edit_time'] = config('common.no_edit');
			}
		}
		return $list;
	}
	//更改列表的时间和类型
	public function listData($list){
		$statusDataList = $this->statusData();
		//格式化时间
		foreach ($list['data'] as $k=>$v){
			$list['data'][$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			if($list['data'][$k]['edit_time']){
				$list['data'][$k]['edit_time'] = date('Y-m-d H:i:s',$v['edit_time']);
			}else{
				$list['data'][$k]['edit_time'] = config('common.no_edit');
			}
			$list['data'][$k]['type'] = $statusDataList[$list['data'][$k]['type']];
		}
		return $list;
		
	}
	//公告的添加日志方法
	public function commonLog(){
		$controller = request()->controller();
		$action = request()->action();
		$log['title'] = db('auth_rule')->where(['href'=>$controller .'/'.$action])->value('title');
		if(isset($log['title'])){
			$log['ip'] = request()->ip();
			$log['add_time'] = $_SERVER['REQUEST_TIME'];
			$session = session(config('session.admin_session'),'',config('session.admin_session_z'));
			$log['username'] = $session['username'];
			db('log')->insert($log);
		}
	}
	//添加和编辑时的公共方法  参数1：数据，参数2：成功信息，参数3：失败信息
	public function commonData($data,$success,$error){
		if(isset($data['id'])){
			if(array_key_exists('file', $data)) unset($data['file']);
			$data['edit_time'] = $_SERVER['REQUEST_TIME'];	
			if($this->update($data) !== false){
				$this->commonLog();
				return new ParameterException([
					'code'=>'200',
					'msg'=>$success,
					'errorCode'=>'8888',
					'url' => url('index')
				]);
			}else{
				return new ParameterException([
					'msg'=>$error
				]);
			}
		}else{
			$data = unmArr($data);
			$data['add_time'] = $_SERVER['REQUEST_TIME'];	
			if($this->save($data)){
				$this->commonLog();
				return new ParameterException([
					'code'=>'200',
					'msg'=>$success,
					'errorCode'=>'8888',
					'url' => url('index')
				]);
			}else{
				return new ParameterException([
					'msg'=>$error
				]);
			}
		}
	}
	//公共的调整状态  参数1：数据，参数2：成功信息，参数3：失败信息
	public function commonState($data,$success,$error){
		if($this->update($data) !== false){
			$this->commonLog();
			return new ParameterException([
				'code'=>'200',
				'msg'=>$success,
				'errorCode'=>'8888'
			]);
		}else{
			return new ParameterException([
				'msg'=>$error,
				'url' => url('index')
			]);
		}
	}
	//公共的排序操作   参数1：数据，参数2：成功信息，参数3：失败信息
	public function commonOrder($data,$success,$error){
		if($this->update($data) !== false){
			$this->commonLog();
			return new ParameterException([
				'code'=>'200',
				'msg'=>$success,
				'errorCode'=>'8888',
				'url' => url('index')
			]);
		}else{
			return new ParameterException([
				'msg'=>$error,
				'url' => url('index')
			]);
		}
	}
	//公共的删除方法(支持多个删除)  参数1：数据，参数2：成功信息，参数3：失败信息
	public function commonDel($data,$success,$error){
		if($this->destroy($data)){
			$this->commonLog();
			return new ParameterException([
				'code'=>'200',
				'msg'=>$success,
				'errorCode'=>'8888',
				'url' => url('index')
			]);
		}else{
			return new ParameterException([
				'msg'=>$error,
				'url' => url('index')
			]);
		}
	}
	//编辑时  带图片的添加  （处理图片）
	//参数1：图片对应的id   参数2：图片路径   参数3：图片对应的字段名
	public function editPicCommon($id,$url,$fieldName){
		//查询出当前修改的图片路径
		$picUrl = $this->where(['id'=>$id])->value($fieldName);
		//判断是否修改了图片路径
		if($picUrl == $url){
			//没有修改图片路径
			return $url;
		}else{
			//修改了图片路径  注意：这个时候需要删除原来的图片
			$array=explode('/',$picUrl);
			$file=str_replace('runtime','public',RUNTIME_PATH) . $array[1] . DS . $array[2] . DS .$array[3];
			@unlink($file);
			return PicCommon($url);
		}
	}
	//多图编辑  参数1：对应id 参数2：图片数组   参数3：图片对应的字段名
	public function editPicCommons($id,$urlArray,$fieldName){
		$picUrl = $this->where(['id'=>$id])->value($fieldName);
		$picUrlArray = explode('|',$picUrl);
		$url = implode('|',$urlArray);
		if($picUrl == $url){
			return $url;
		}else{
			//获取交集
			$intersectArray = array_intersect($picUrlArray,$urlArray);
			//查询差集  删除不需要的图片
			$diffNo = array_diff($picUrlArray,$intersectArray);
			foreach($diffNo as $k=>$v){
				$diffRemove=explode('/',$diffNo[$k]);
				$file=str_replace('runtime','public',RUNTIME_PATH) . $diffRemove[1] . DS . $diffRemove[2] . DS .$diffRemove[3];
				@unlink($file);
			}
			//需要转移位置的图片
			$diffYes = array_diff($urlArray,$intersectArray);
			static $yesArray = array();
			foreach($intersectArray as $k2=>$v2){
				$yesArray[] = $v2;
			}
			foreach($diffYes as $k1=>$v1){
				$yesArray[] = PicCommon($diffYes[$k1]);
			}
			$returnString = implode('|',$yesArray);
			return $returnString;
		}
	} 
	//单个图片删除方法
	//参数一：id
	//参数二：图片字段名
	public function delPicCommon($id,$fieldName){
		//查询出当前删除图片路径
		$tpmodel=$this->where(['id'=>$id])->value($fieldName);
		$array=explode('/',$tpmodel);
		$file=str_replace('runtime','public',RUNTIME_PATH) . $array[1] . DS . $array[2] . DS .$array[3];
		@unlink($file);
	}
	
	//单个图片删除方法  (多图上传的删除)
	//参数一：id
	//参数二：图片字段名
	public function delPicCommons($id,$fieldName){
		//查询出当前删除图片路径
		$tpmodel=$this->where(['id'=>$id])->value($fieldName);
		$arrayUrl = explode('|',$tpmodel);
		foreach($arrayUrl as $k=>$v){
			$array=explode('/',$v);
			$file=str_replace('runtime','public',RUNTIME_PATH) . $array[1] . DS . $array[2] . DS .$array[3];
			@unlink($file);
		}
	}
	
	//多个个图片删除方法
	//参数一：where条件
	//参数二：图片字段名
	public function delallPicCommon($map,$fieldName){
		//查询出当前删除图片路径
		$tpmodel=$this->where($map)->field($fieldName)->select();
		foreach($tpmodel as $k=>$v){
			$array=explode('/',$v["$fieldName"]);
			$file=str_replace('runtime','public',RUNTIME_PATH) . $array[1] . DS . $array[2] . DS .$array[3];
			@unlink($file);
		}
	}
	
	//多个个图片删除方法   (多图上传的删除)
	//参数一：where条件
	//参数二：图片字段名
	public function delallPicCommons($map,$fieldName){
		//查询出当前删除图片路径
		$tpmodel=$this->where($map)->field($fieldName)->select();
		foreach($tpmodel as $k=>$v){
			$arrayUrlString = explode('|',$v["$fieldName"]);
			foreach($arrayUrlString as $k1=>$v1){
				$array=explode('/',$v1);
				$file=str_replace('runtime','public',RUNTIME_PATH) . $array[1] . DS . $array[2] . DS .$array[3];
				@unlink($file);
			}
		}
	}
	
	
	
	
	
	
	
	
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

