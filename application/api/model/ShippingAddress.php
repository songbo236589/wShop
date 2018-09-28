<?php
namespace app\api\model;
use app\api\service\Token;
use app\common\exception\ParameterException;
class ShippingAddress extends BaseModel
{
	//添加收货地址
	public function addLocation($data){
		$data['user_id'] = Token::getUserId();
		$data['add_time'] = $_SERVER['REQUEST_TIME'];
		if($data['wShop']){
			unset($data['wShop']);
			if($this->save($data)){
				throw new ParameterException([
					'code'=>200,
					'errorCode'=>'8888',
					'msg'=>'添加收货地址成功！'
				]);
			}
		}else{
			if($this->save($data)){
				$this->locationList($data['user_id']);
			}
		}
		throw new ParameterException([
				'msg'=>'网络错误！'
		]);
		
	}
	//收货地址列表
	public function locationList($user_id){
		if($user_id == 0){
			$user_id = Token::getUserId();
		}
		$lsit = $this->where(['user_id'=>$user_id])->order('id desc')->field('userName,telNumber,provinceName,cityName,countyName,detailInfo,status,id')->select();
		throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$lsit
			]);
	}
	//收货地址选择
	public function locationStatus($id){
		$this->where(['id'=>$id])->update(['status'=>1]);
		$this->where(['id'=>['neq',$id]])->update(['status'=>0]);
		$this->locationList(0);
	}
	//收货地址删除
	public function locationDelete($id){
		if($this->where(['id'=>$id])->delete()){
			$this->locationList(0);
		}else{
			throw new ParameterException([
					'msg'=>'网络错误！'
			]);
		};	
	}
}
