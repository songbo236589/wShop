<?php
namespace app\api\validate;
class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => ['require','isNotEmpty']
    ];
    
    protected $message=[
        'code' => '网络错误！'
    ];
}
