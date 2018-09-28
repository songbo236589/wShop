<?php
namespace app\api\model;
use app\api\service\Token;
use app\api\service\Pay;
use app\common\exception\ParameterException;
class Order extends BaseModel
{
	//订单列表
	public function orderList($page,$status){
		$where['user_id'] = Token::getUserId();
		if($status>0){
			$where['status'] = $status;
		}
		$list = $this
			->where($where)
			->order('id desc')
			->field('id,goods,order_number,userName,detailInfo,telNumber,money,status')
			->paginate(array('list_rows'=>10,'page'=>$page))
			->toArray();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['goods'] = json_decode($list['data'][$k]['goods']);
		}	
		throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$list
			]);	
   }
   //取消订单
   public function locationStatus($id){
		$where['user_id'] = Token::getUserId();
		$where['id'] = $id;
		if($this->where($where)->delete()){
			throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>'删除成功！'
			]);
		}else{
			throw new ParameterException([
				'msg'=>'网络错误！'
			]);
		};				
	}
	
	//订单管理去支付
   public function orderPay($id){
		$where['user_id'] = Token::getUserId();
		$where['id'] = $id;
		$money = $this->where($where)->value('money');
		$order_no = makeOrderNo();
		if($this->where($where)->update(['order_number'=>$order_no])){
			$data = [
				'id'=>$where['id'],
				'order_no'=>$order_no,
				'money'=>$money,
				'goods'=>'wShop商品'
				];
			(new Pay())->pay($data);	
		}else{
			throw new ParameterException([
				'msg'=>'网络错误！'
			]);
		};			
	}
	//确定收货
   public function orderConfirm($id){
		$where['user_id'] = Token::getUserId();
		$where['id'] = $id;
		if($this->where($where)->update(['status'=>4])){
			throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>'收货成功！'
			]);
		}else{
			throw new ParameterException([
				'msg'=>'网络错误！'
			]);
		};			
	}
	//商品评论
   public function getOrderItem($id){
		$where['user_id'] = Token::getUserId();
		$where['id'] = $id;
		$res = $this->where($where)->value('goods');
		if($res){
			throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>json_decode($res)
			]);
		}else{
			throw new ParameterException([
				'msg'=>'网络错误！'
			]);
		};			
	}
	//提交评论
	public function commentT($data){
		$user_id = Token::getUserId();
		foreach($data as $k=>$v){
			unset($data[$k]['name']);
			unset($data[$k]['num']);
			unset($data[$k]['shop_price']);
			unset($data[$k]['store_count']);
			$data[$k]['user_id'] = $user_id;
			$data[$k]['add_time'] = $_SERVER['REQUEST_TIME'];
			db('comment')->insert($data[$k]);
		}
		throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>'评论成功！'
			]);		
	}
}
