<?php
namespace app\admin\validate;
class AuthRule extends Basevalidate
{
    protected $rule=[
		'id'                    =>['require','isPositiveInteger'],
		'pid'  					=>['require'],
		
        'title'					=>['require','checknamea'],
		
		'href'					=>['unique'=>'auth_rule','checknameb'],		
		'menustatus'			=>['require'],
		
		'icon'              	=>['checknamea'],
		
		'authopen'              =>['require','isStates'],
		'menustatus'            =>['require','isStates'],
		
		'sort'		       	 	=>['require','regex'=>'/^[1-9]{1}[0-9]{0,10}$/']
		
		
	];
    protected $message=[
		'id'			        =>'非法操作！',
        'pid'			        =>'权限父级不能为空！',
        
		'authopen'	            =>'非法操作验证权限！',
		'menustatus'	        =>'非法操作菜单状态！',
		
		'title.require'     	=>'权限名称不能为空！',
		
        'title.checknamea'		=>'权限名称必须在2到20位之间！',
		
		
		'href.unique'			=>'控制器/方法不能重复！',
        'href.checknameb'		=>'控制器/方法必须在2到50位之间！',
		
		'menustatus.require'    =>'菜单状态不能为空！',
		
		
		
		
        'icon.checknamea'		=>'图标名称必须在2到20位之间！',
		
		
		'sort.require'     		=>'排序不能为空！',
        'sort.regex'			    =>'排序必须在1到11位之间的数字并且首数字不能为0！'
		
    ];
    protected $scene=[
		'add'=>['pid','title','href','menustatus','sort','icon'],
		'edit'=>['id','title','href','menustatus','sort','icon'],
		'authopen'=>['id','authopen'],
		'menustatus'=>['id','menustatus'],
		'sorts'=>['id','sort'],
		'del'=>['id']
								
	];
}                                                                                                                                