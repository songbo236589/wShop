<?php
namespace app\admin\validate;
class Goods extends Basevalidate
{	
    protected $rule=[
		'id'      			    =>['require','isPositiveInteger'],
		'goods_category_id'     =>['require','isPositiveInteger'],
		'name'					=>['require'],
		'image'					=>['require'],
		'shop_price'			=>['require'],
		'market_price'			=>['require'],
		'store_count'           =>['require'],
		'sort'					=>['require','regex'=>'/^[1-9]{1}[0-9]{0,10}$/'],
		'status'                =>['require','isStates'],
		'open_banner'           =>['require','isStates']
	];
    protected $message=[
		'id'              	    =>'非法操作！',
		'goods_category_id'     =>'请选择商品类型！',
		'name'       			=>'请输入商品名称！',
		'image'		        	=>'请上传单图！',
		'shop_price'		    =>'请输入本店售价！',
		'market_price'		    =>'请输入市场价！',
		'store_count'		    =>'请输入库存！',
		'sort.require'			=>'排序不能为空！',
		'sort.regex'			=>'排序是1-11为数字并首数字不能为0！',
		'status'                =>'参数错误！',
		'open_banner'           =>'参数错误！'
		
    ];
	 protected $scene=[
		'add'	=>['goods_category_id','name','image','shop_price','market_price','store_count','sort'],
		'edit'	=>['id','goods_category_id','name','image','shop_price','market_price','store_count','sort'],
		'del'	=>['id'],
		'sort'	=>['id','sort'],
		'status'=>['id','status'],
		'open_banner'=>['id','open_banner']	
	];
	
	
}                                                                                                                                