<?php
namespace app\admin\validate;
use app\common\exception\ParameterException;
use think\Request;
use think\Validate;
class Basevalidate extends Validate
{
    //参数1 验证的字段类型      参数2. 验证的数据
    public function goCheck($level,$params){
        //$request = Request::instance();
        //$params = $request->param();//获取传入的所有数据
        //$result = $this->batch()->scene($level)->check($params);   //进行验证
        $result = $this->scene($level)->check($params);
        if(!$result){
            $e = new ParameterException(['msg'=>$this->error,'url'=>url('index')]);
            return $e;
        }else{
            return false;
        }
    }
	//判断是否为正整数
	protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }else{
			return false;
		}
    }
	
	//判断状态是否为0或1
	protected function isStates($value, $rule='', $data='', $field='')
    {
        if ($value == 0 || $value == 1) {
            return true;
        }else{
			return false;
		}
    }
	//长度大于10
	protected function checkname($value, $rule='',$data='',$field='')
    {
        if(mb_strlen($value,'utf8')>10){
            return false;
        }else{
            return true;
        }
    }
	//长度在2-30位之间
	protected function checknamea($value, $rule='',$data='',$field='')
    {
        if(mb_strlen($value,'utf8') < 2 || mb_strlen($value,'utf8') > 20){
            return false;
        }else{
            return true;
        }
    }
	//长度在2-50位之间
	protected function checknameb($value, $rule='',$data='',$field='')
    {
        if(mb_strlen($value,'utf8') < 2 || mb_strlen($value,'utf8') > 50){
            return false;
        }else{
            return true;
        }
    }
	//判断长度为2-10
	protected function checknamec($value, $rule='',$data='',$field='')
    {
        if(mb_strlen($value,'utf8') < 2 || mb_strlen($value,'utf8') > 10){
            return false;
        }else{
            return true;
        }
    }
	//判断长度为2-50
	protected function checknamed($value, $rule='',$data='',$field='')
	{
        if(mb_strlen($value,'utf8') < 2 || mb_strlen($value,'utf8') > 50){
            return false;
        }else{
            return true;
        }
    }
	//判断长度为2-225
	protected function checknamee($value, $rule='',$data='',$field='')
	{
        if(mb_strlen($value,'utf8') < 2 || mb_strlen($value,'utf8') > 225){
            return false;
        }else{
            return true;
        }
    }
}                                                                                                                                