<?php
namespace app\admin\validate;
use think\Validate;
class GoodsCategory extends Basevalidate
{	
    protected $rule=[
		'id'      			    =>['require','isPositiveInteger'],
		'name'                  =>['require'],
		'sort'					=>['require','regex'=>'/^[1-9]{1}[0-9]{0,10}$/'],
		'status'                =>['require','isStates']
	];
    protected $message=[
		'id'              	    =>'非法操作！',
		'name'                  =>'请输入分类名称', 
		'sort.require'			=>'排序不能为空！',
		'sort.regex'			=>'排序是1-11为数字并首数字不能为0！',
		'status'                =>'参数错误！'
    ];
	 protected $scene=[
		'add'	=>['name','sort'],
		'edit'	=>['id','name','sort'],
		'del'	=>['id'],
		'sort'	=>['id','sort'],
		'status'=>['id','status']	
	];
	
	
}                                                                                                                                