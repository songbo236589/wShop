<?php
namespace app\admin\controller;
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use app\common\exception\ParameterException;
class Qiniu extends Common
{
	public function index(){
		return $this->fetch();
	}  
}
