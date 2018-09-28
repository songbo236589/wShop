<?php
namespace app\api\controller\v1;
use app\common\exception\ParameterException;
use app\api\controller\BaseController;
class Pv extends BaseController
{
    public function getPv()
    { 
		$date = date('y-m-d',$_SERVER['REQUEST_TIME']);
		$id = db('pv')->where(['date'=>$date])->value('id');
		
		if($id){
			db('pv')->where(['id'=>$id])->setInc('num',1);	
		}else{
			$data = [
				'num'=>1,
				'date'=>$date
			];	
			db('pv')->insert($data);
		}
        return 1;
    }	
}