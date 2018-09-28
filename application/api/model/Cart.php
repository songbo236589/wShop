<?php
namespace app\api\model;
use app\api\service\Token;
use app\api\service\Pay;
use app\common\exception\ParameterException;
class Cart extends BaseModel
{
	//判断库存
	public function storeCount($goods_id,$num){
		$store_count = db('goods')->where(['id'=>$goods_id])->value('store_count');
		if($store_count<$num){
			throw new ParameterException([
				'msg'=>'库存不足！'
			]);
		}
	}
	//添加购物车
	public function postAddCart($data){
		$data['user_id'] = Token::getUserId();
		$this->storeCount($data['goods_id'],$data['num']);
		$id = $this->where(['goods_id'=>$data['goods_id']])->value('id');
		if($id){
			$res = $this->where(['id'=>$id])->setInc('num',$data['num']);
		}else{
			$res = $this->save($data);
		}
		if($res){
			throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$this->cartSum($data['user_id'])
			]);
		}else{
			throw new ParameterException([
					'msg'=>'网络错误！'
			]);
		}
	}
	//统计当前用户的购物车商品总数量
	public function cartSum($user_id){
		if($user_id == 0){
			$user_id = Token::getUserId();
		}
		return $this->where(['user_id'=>$user_id])->sum('num');
	}
	//购物车列表
	public function getCartList(){
		$user_id = Token::getUserId();
		$where['c.user_id'] = $user_id; 
		$where['g.status'] = 1; 
		$list = db('cart')
				->alias('c')
				->join('goods g','g.id = c.goods_id','left')
                ->where($where)
				->order('c.id desc')
				->field('c.id,c.num,c.status,g.name,g.image,g.shop_price')
                ->select();
		$cartArr = array();
		//当前选中的总价格
		$cartArr['total_price'] = 0.00;
		//当前选中的商品数量
		$cartArr['total_num'] = 0;
		foreach($list as $k=>$v){
			$list[$k]['image'] =  config('common.img_prefix').$list[$k]['image'];
			if($list[$k]['status'] == 1){
				$cartArr['total_price'] += $list[$k]['shop_price'] * $list[$k]['num'];
				$cartArr['total_num'] += $list[$k]['num'];
			}
		}
		$cartArr['total_price'] = formatNum($cartArr['total_price']);
		$cartArr['list'] = $list;
		if($this->where(['status'=>0])->count()){
			$cartArr['cartAll'] = 0;
		}else{
			$cartArr['cartAll'] = 1;
		}	
		throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$cartArr
			]);		
	}
	//减
	public function postCartSubtract($id){
		$num = $this->where(['id'=>$id])->value('num');
		if($num >1){
			$data = [
						'id'=>$id,
						'num'=>$num -1,
						'status'=>1	
					];
			$res = $this->update($data);
		}else{
			$res = $this->destroy($id);
		}
		$this->cartCommon($res);
		
	}
	//购物车公共的返回方法
	public function cartCommon($res){
		if($res){
			$this->getCartList();
		}else{
			throw new ParameterException([
					'msg'=>'网络错误！'
			]);
		}
	}
	//加
	public function postCartAdd($id){
		$cart = $this->where(['id'=>$id])->field('num,goods_id')->find();
		$num = $cart['num'] + 1;
		$this->storeCount($cart['goods_id'],$num);
		$data = [
					'id'=>$id,
					'num'=>$num,
					'status'=>1	
				];
		$res = $this->update($data);
		$this->cartCommon($res);
	}
	//调整选中状态
	public function postCartStatus($data){
		$res = $this->update($data);
		$this->cartCommon($res);
	}
	//全选全不选
	public function postCartAll($all){
		$status = $all == 0 ? 1 : 0;
		$res = $this->where(['status'=>$all])->update(['status'=>$status]);
		$this->cartCommon($res);
	}
	//判断是否确定收货地址
	public function noAddL($user_id){
		$shipping_address = db('shipping_address')->where(['user_id'=>$user_id,'status'=>1])->field('userName,postalCode,provinceName,cityName,countyName,detailInfo,nationalCode,telNumber')->find();
		if(!$shipping_address){
			throw new ParameterException([
				'errorCode'=>'9999',
				'msg'=>'请添加收货地址！'
			]);	
		}else{
			return $shipping_address;
		}
	}
	//查找购买的商品
	public function orderData($user_id){
		$list = db('cart')
				->alias('c')
				->join('goods g','g.id = c.goods_id','left')
				->where(['c.user_id'=>$user_id,'c.status'=>1])
				->field('c.id,c.goods_id,c.num,g.name,g.store_count,shop_price')
				->select();
		if(!$list){
			throw new ParameterException([
						'errorCode'=>'6666',
						'msg'=>'请选择商品！'
				]);	
		}else{
			return $list;
		}
	}
	//立即结算
	public function PayCart(){
		$user_id = Token::getUserId();
		$data = $this->noAddL($user_id);
		$list = $this->orderData($user_id);
		$data = $this->listDataC($data,$list,$user_id);
		$id = db('order')->insertGetId($data);
		//支付金额  //订单号  id   //商品标题
		if($id){
			$pay = [
				'id'=>$id,
				'order_no' =>$data['order_number'],
				'money'=>$data['money'],
				'goods'=>'wShop商品'
			];
			(new Pay())->pay($pay);
		}else{
			throw new ParameterException([
						'errorCode'=>'6666',
						'msg'=>'网络错误！'
				]);	
		}
			
	}
	//list数据处理
	public function listDataC($data,$list,$user_id){
		//购物车商品id的数组
		$cart_ids = array();
		//计算商品的总价格
		$money = 0;
		foreach($list as $k=>$v){
			if($list[$k]['store_count']<$list[$k]['num']){
				throw new ParameterException([
						'errorCode'=>'6666',
						'msg'=>'商品'.'“'.$list[$k]['name'].'”'.'库存不足，可减少购买数量！'
				]);	
			}
			$cart_ids[] = $list[$k]['id'];
			unset($list[$k]['id']);
			unset($list[$k]['store_count']);
			$money += $list[$k]['shop_price'] * $list[$k]['num'];	
		}
		if($this->destroy($cart_ids)){
			$data['goods'] = json_encode($list,JSON_UNESCAPED_UNICODE);
			$data['add_time'] = $_SERVER['REQUEST_TIME'];
			$data['order_number'] = makeOrderNo();
			$data['user_id'] = $user_id;
			$data['money'] = formatNum($money);
			return $data;
		}else{
			throw new ParameterException([
						'errorCode'=>'6666',
						'msg'=>'网络错误！'
				]);
		}
		
	}
}
