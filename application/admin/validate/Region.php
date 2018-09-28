<?php
namespace app\admin\validate;
class Region extends Basevalidate
{
    protected $rule=[
		'pid'                   =>['require'],
		'id'              		=>['require','isPositiveInteger'],
        'name'					=>['require','checkname'],
		'status'                =>['require','isStates']
	];
    protected $message=[
		'id'			        =>'非法操作！',
		'pid'			        =>'请选择所属地区！',
		'name.require'			=>'名称不能为空！',
		'name.checkname'		=>'名称不能超过10位字符！',
        'status'                =>'参数错误！'
    ];
    protected $scene=[
		'add'=>['name'],
		'edit'=>['id','name'],
		'adds'=>['pid','name'],
		'edits'=>['id','name','pid'],
		'del'=>['id'],
		'status'=>['id','status']	
	];
	
}                                                                                                                                