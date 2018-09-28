<?php
namespace app\api\validate;
class Cart extends BaseValidate
{
    protected $rule = [
        'goods_id' => ['require','isPositiveInteger'],
        'num'      => ['require','isPositiveInteger']
    ];
	protected $message = [
        'goods_id' => '操作失败！',
        'num'      => '操作失败！'
    ];
}
