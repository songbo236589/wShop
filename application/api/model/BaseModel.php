<?php
namespace app\api\model;
use think\Model;
class BaseModel extends Model
{
	//单个图片格式化
    protected function  prefixImgUrl($data){
		foreach($data as $k=>$v){
		   $data[$k]['image'] =  config('common.img_prefix').$data[$k]['image'];
		} 
        return $data;
    }
	//多个图片格式化取第一个
	protected function  prefixImgUrlOne($data){
		foreach($data as $k=>$v){
		   $data[$k]['images'] =  config('common.img_prefix').explode('|',$data[$k]['images'])[0];
		} 
        return $data;
    }
	//多张图片格式化
	protected function  prefixImgUrlList($images){
		$imagesArr = explode('|',$images);
		for($i=0;$i<count($imagesArr);$i++){
		   $imagesArr[$i] =  config('common.img_prefix').$imagesArr[$i];
		} 
        return $imagesArr;
    }
}