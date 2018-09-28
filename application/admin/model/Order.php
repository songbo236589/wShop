<?php
namespace app\admin\model;
class Order extends BaseModel
{
	public function orderStatusData(){
		return ['1'=>'待付款','2'=>'待发货','3'=>'待收货','4'=>'待评价'];
	}
	public function orderListInfo($data){
		foreach ($data as $k=>$v){
			$data[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			$data[$k]['goods'] = json_decode($data[$k]['goods'],true);
			$data[$k]['goodsRes'] = '';
			foreach($data[$k]['goods'] as $k1=>$v1){
				$data[$k]['goodsRes'] .= '商品名称：' . $data[$k]['goods'][$k1]['name'] . '，购买数量：' . $data[$k]['goods'][$k1]['num'] . '，单价：' . $data[$k]['goods'][$k1]['shop_price'] . '；';
			}
		}
		return $data;
	}
}
