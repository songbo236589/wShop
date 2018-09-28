<?php
namespace app\api\model;
use app\common\exception\ParameterException;
class Goods extends BaseModel
{
	//banner图数据处理
   public function getBanner(){
	  $banner = db('goods')
			->alias('g')
			->join('goods_category c','g.goods_category_id = c.id','left')
			->where(['g.status'=>1,'g.open_banner'=>1,'c.status'=>1])
			->field('g.id,g.images')
			->order('g.sort asc')
			->select();
	   if($banner){
		   throw new ParameterException([
				'code'=>200,
				'errorCode'=>'8888',
				'msg'=>$this->prefixImgUrlOne($banner)
			]);
	   }else{
			throw new ParameterException([
				'msg'=>'没有可用的banner图！'
			]);
	   }	
   }
   //商品列表数据处理
   public function getGoods($type,$page){
		$where['g.status'] = 1;
		$where['c.status'] = 1;
		if($type != 0){
			$where['g.goods_category_id'] = $type;
		}
		$list = db('goods')
				->alias('g')
				->join('goods_category c','g.goods_category_id = c.id','left')
                ->where($where)
				->order('g.sort asc')
				->field('g.id,g.image,g.name,g.sales_sum,g.shop_price,g.market_price')
				->paginate(array('list_rows'=>10,'page'=>$page))
                ->toArray();
		$list['data'] = $this->prefixImgUrl($list['data']);
		throw new ParameterException([
			'code'=>200,
			'errorCode'=>'8888',
			'msg'=>$list
		]);		
	}
	//商品分类数据处理
	public function getGoodsTypeList($page){	
		$goods_category = db('goods_category')->where(['status'=>1])->order('sort asc')->field('id,name')->select();
		$list = db('goods')
                ->where(['status'=>1,'goods_category_id'=>$goods_category[0]['id']])
				->order('sort asc')
				->field('id,image,name,sales_sum,shop_price,market_price')
				->paginate(array('list_rows'=>10,'page'=>$page))
                ->toArray();
		$list['data'] = $this->prefixImgUrl($list['data']);
		throw new ParameterException([
			'code'=>200,
			'errorCode'=>'8888',
			'msg'=>['goods_category'=>$goods_category,'list'=>$list]
		]);	
	} 
	//商品详情数据处理
	public function getGoodsContent($id){
		$goods = $this->where(['id'=>$id])->field('id,name,images,store_count,sales_sum,graphic_details,commodity_parameters,shop_price,market_price')->find();
		$goods['images'] = $this->prefixImgUrlList($goods['images']);
		$goods['graphic_details'] = replacePicUrl($goods['graphic_details']);
		$goods['commodity_parameters'] = replacePicUrl($goods['commodity_parameters']);
		$goods['num'] = model('cart')->cartSum(0);
		$goods['comment'] = db('comment')
				->alias('c')
				->join('user u','u.id = c.user_id','left')
                ->where(['goods_id'=>$id])
				->order('c.id desc')
				->field('c.content,c.star,u.nickName,u.avatarUrl')
				->select();
		throw new ParameterException([
			'code'=>200,
			'errorCode'=>'8888',
			'msg'=>$goods
		]);	
	}
}
