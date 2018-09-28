<?php
namespace app\admin\validate;
class Admin extends Basevalidate
{
    protected $rule=[
		'group_id'  =>['require','isPositiveInteger'],   //分组id
		'id'        =>['require','isPositiveInteger'],   //管理员id
		'is_open'   =>['require','isStates'],            //管理员状态
        'username'	=>['unique'=>'admin','require'],//管理员账号
		'pwd'		=>['require'],   //管理员密码
		'name'      =>['require'],
		'email'     =>['require'],
		'province_id'           =>['require'],
		'city_id'           	=>['require'],
		'county_id'           	=>['require'],
		'address'           	=>['require']
	];
    protected $message=[
		'group_id'	        	=>'所属组不能为空！',
		'id'	        	    =>'非法操作！',
		'is_open'	        	=>'非法操作状态！',
		'username.require'		=>'管理员账号不能为空！',
        'username.unique'		=>'管理员账号不能重复！',
		'pwd.require'			=>'管理员密码不能为空！',
		'name'			        =>'管理员真实姓名不能为空！',
		'email'			        =>'管理员邮箱不能为空！',
		'province_id'           =>'请选择省！',
		'city_id'           	=>'请选择市！',
		'county_id'           	=>'请选择区县！',
		'address'           	=>'请输入详细地址！'

    ];
    protected $scene=[
        'add'=>['group_id','name','email','username','pwd','province_id','city_id','county_id','address'],
		'edit'=>['id','group_id','name','email','username','province_id','city_id','county_id','address'],
		'pwds'=>['pwd'],
		'state'=>['id','is_open'],
		'del'=>['id']
	];

}                                                                                                                                