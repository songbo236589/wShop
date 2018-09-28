<?php
namespace app\admin\controller;
use app\admin\validate\Goods as DataV;
class Goods extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()) {
			$goods_category_id = input('post.goods_category_id');
			$time = input('post.time');
			$status = input('post.status');
			$key = input('post.key');
			$open_banner = input('post.openBannere');
			$where=array();	
			if($goods_category_id != '') $where['g.goods_category_id']=$goods_category_id;
			if($status != '') $where['g.status']=$status;
			if($open_banner != '') $where['g.open_banner']=$open_banner;
			if($time) $where['g.add_time'] = timeData($time);
			if($key) $where['g.name'] = ['like','%'.$key.'%'];
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list = db('goods')
				->alias('g')
                ->join('goods_category c','g.goods_category_id = c.id','left')
                ->where($where)
                ->order('g.id desc')
				->field('g.id,g.name,g.store_count,g.sales_sum,g.status,g.sort,g.add_time,g.edit_time,g.open_banner,g.shop_price,g.market_price,c.name as cname')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list = model('goods')->listDateInfo($list);	
			return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
		}
        return $this->fetch('',['goods_category'=>model('goods_category')->goodsCategoryList(),'statuslist'=>model('goods')->statusGoods(),'openBannerData'=>model('goods')->openBannerData()]);
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
			$dataV = (new DataV())->goCheck('add',$data);
			if($dataV !== false) return $dataV;
			$data['images'] = PicCommons($data['images']);
			$data['image'] = PicCommon($data['image']);
			return model('goods')->commonData($data,'添加商品成功！','添加商品失败，请重试！');
        }else{
            return $this->fetch('',['goods_category'=>model('goods_category')->goodsCategoryList()]);
        }
    }
    public function edit(){
        if(request()->isPost()) {
            $data = input('post.');
			$dataV = (new DataV())->goCheck('edit',$data);
			if($dataV !== false) return $dataV;
			$data['images'] = model('goods')->editPicCommons($data['id'],$data['images'],'images');
			$data['image'] = model('goods')->editPicCommon($data['id'],$data['image'],'image'); 
			return model('goods')->commonData($data,'编辑商品成功！','编辑商品失败，请重试！');
        }else{
            $info=db('goods')->where(array('id'=>input('id')))->field('id,goods_category_id,name,image,images,shop_price,market_price,store_count,graphic_details,commodity_parameters,sort')->find();
			$imagesInfo = explode('|',$info['images']);
            return $this->fetch('',['info'=>$info,'goods_category'=>model('goods_category')->goodsCategoryList(),'imagesInfo'=>$imagesInfo]);
        }
    }
    public function listOrder(){
		$data=input('post.');
		$dataV = (new DataV())->goCheck('sort',$data);
        if($dataV !== false) return $dataV;
        return model('goods')->commonOrder($data,'商品排序成功！','商品排序失败，请重试！');
    }
    public function del(){
		$data = input('post.');
		$dataV = (new DataV())->goCheck('del',$data);
		if($dataV !== false) return $dataV;
		model('goods')->delPicCommon($data['id'],'image');
		model('goods')->delPicCommons($data['id'],'images');
		return model('goods')->CommonDel($data,'删除商品成功！','删除商品失败，请重试！');
    }
    public function delall(){
		$data=input('param.ids/a');
        $map['id'] = array('in',$data);
		model('goods')->delallPicCommon($map,'image');
		model('goods')->delallPicCommons($map,'images');
		return model('goods')->CommonDel($data,'删除商品成功！','删除商品失败，请重试！');
    }
	public function openBanner(){
		$data = input('post.');
		$dataV = (new DataV())->goCheck('open_banner',$data);
		if($dataV !== false) return $dataV;
		return model('goods')->commonState($data,'调整商品推荐banner状态成功！','调整商品推荐banner状态失败，请重试！');
    }
    public function editState(){
		$data = input('post.');
		$dataV = (new DataV())->goCheck('status',$data);
		if($dataV !== false) return $dataV;
		return model('goods')->commonState($data,'调整商品状态成功！','调整商品状态失败，请重试！');
    }
}