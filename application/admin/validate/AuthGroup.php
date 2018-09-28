<?php
namespace app\admin\validate;
use think\Validate;
class AuthGroup extends Basevalidate
{
    protected $rule=[
		'id'                    =>['require','isPositiveInteger'],
		'status'                =>['require','isStates'],
        'title'					 =>['unique'=>'auth_group','require','checkname']
	];
    protected $message=[
		'id'		            =>'非法操作！',
		
		'status'	            =>'非法操作状态！',
		
		'title.require'		=>'用户组名不能为空！',
		'title.checkname'	    =>'用户组名不能超过10位字符！',
        'title.unique'			=>'用户组名不能为重复，请重新填写！',
		
    ];
    protected $scene=[
		'add'=>['title'],
		'edit'=>['id','title'],
		'del'=>['id'],
		'state'=>['id','status']
	];
}                                                                                                                                