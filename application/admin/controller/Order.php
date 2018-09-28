<?php
namespace app\admin\controller;
use think\Loader;
class Order extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if(request()->isPost()){
			$add_time = input('post.add_time');
			$status = input('post.status');
			$key = input('post.key');
			$where=array();	
			if($status != '') $where['o.status']=$status;
			if($add_time) $where['o.add_time'] = timeData($add_time);
			if($key) $where['u.nickName|o.order_number'] = ['like','%'.$key.'%'];
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('common.page_sizes');
            $list = db('order')
				->alias('o')
                ->join('user u','u.id = o.user_id','left')
                ->where($where)
                ->order('o.id desc')
				->field('o.*,u.nickName')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
			$list['data'] = model('order')->orderListInfo($list['data']);	
			return $result = ['code'=>0,'msg'=>config('common.data_success'),'data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
		}
        return $this->fetch('',['statuslist'=>model('order')->orderStatusData()]);
    }
    //调整订单状态
	public function orderStatus(){
		$data = [
			'id'=>input('post.id'),
			'status'=>3	
		];
		return model('order')->commonState($data,'调整状态成功！','调整状态失败，请重试！');
	}
	//导出数据
	public function dataAll()
    {
		$add_time = input('add_time');
		$status = input('status');
		$key = input('key');
		$where=array();	
		if($status) $where['o.status']=$status;
		if($add_time) $where['o.add_time'] = timeData($add_time);
		if($key) $where['u.nickName|o.order_number'] = ['like','%'.$key.'%'];
		$list = db('order')
			->alias('o')
			->where($where)
			->join('user u','u.id = o.user_id','left')
			->order('o.id desc')
			->field('o.*,u.nickName')
			->select();			
        Loader::import('PHPExcel.Classes.PHPExcel');//手动引入PHPExcel.php
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');//引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        $PHPExcel = new \PHPExcel();//实例化
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle("全部订单数据！");
		$arrHeader =  array('编号','微信昵称','订单详情','订单号','付款金额','订单状态','收货人姓名','收货地址','收货人手机号码','下单时间');
		$letter =explode(',',"A,B,C,D,E,F,G,H,I,J");
		$lenth =  count($arrHeader);
		for($i = 0;$i < $lenth;$i++) {
            $PHPSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
		$orderStatusData = model('order')->orderStatusData();
		 //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $PHPSheet->setCellValue('A'.$k,	$v['id']);
			$PHPSheet->setCellValue('B'.$k, $v['nickName']);
			$v['goods'] = json_decode($v['goods'],true);
			$v['goodsRes'] = '';
			foreach($v['goods'] as $k1=>$v1){
				$v['goodsRes'] .= '商品名称：' . $v['goods'][$k1]['name'] . '，购买数量：' . $v['goods'][$k1]['num'] . '，单价：' . $v['goods'][$k1]['shop_price'] . '；';
			}
            $PHPSheet->setCellValue('C'.$k, $v['goodsRes']);
            $PHPSheet->setCellValue('D'.$k, $v['order_number']);
            $PHPSheet->setCellValue('E'.$k, $v['money']);
            $PHPSheet->setCellValue('F'.$k, $orderStatusData[$v['status']]);
            $PHPSheet->setCellValue('G'.$k, $v['userName']);
            $PHPSheet->setCellValue('H'.$k, $v['detailInfo']);
			$PHPSheet->setCellValue('I'.$k,	$v['telNumber']);
            $PHPSheet->setCellValue('J'.$k, date('Y-m-d H:i:s',$v['add_time']));
        }
		$PHPSheet->getColumnDimension('A')->setWidth(5);
        $PHPSheet->getColumnDimension('B')->setWidth(20);
        $PHPSheet->getColumnDimension('C')->setWidth(60);
        $PHPSheet->getColumnDimension('D')->setWidth(20);
        $PHPSheet->getColumnDimension('E')->setWidth(20);
        $PHPSheet->getColumnDimension('F')->setWidth(20);
        $PHPSheet->getColumnDimension('G')->setWidth(20);
        $PHPSheet->getColumnDimension('H')->setWidth(20);
		$PHPSheet->getColumnDimension('I')->setWidth(20);
        $PHPSheet->getColumnDimension('J')->setWidth(20);
		//表格数据
		$PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,"Excel2007");//创建生成的格式
        header('Content-Disposition: attachment;filename="订单数据.xlsx"');//下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output");
    }
}