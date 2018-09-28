<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use app\common\exception\ParameterException;
class UpFiles extends Common
{
	//单图上传
    public function upload(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploadss');
        if($info){
			$path=str_replace('\\','/',$info->getSaveName());
			return new ParameterException([
							'code'=>'200',
							'msg'=>'图片上传成功！',
							'errorCode'=>'8888',
							'url' => '/uploadss/'. $path
						]);
        }else{
            return new ParameterException([
							'msg'=>'图片上传失败,请重新上传！',
						]);
        }
    }
	//单图上传并生成缩略图
	public function uploadFile(){
		// 获取上传文件表单字段名
		$fileKey = array_keys(request()->file());
		// 获取表单上传文件
		$file = request()->file($fileKey['0']);
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploadss');
		if($info){
			$path = str_replace('\\','/',$info->getSaveName());
			$date_path = ROOT_PATH . 'public' . DS . 'uploadss';
			$image = \think\Image::open($date_path.'/'. $path);
			$thumb_path = $date_path.'/' .date('Ymd').'/'.'s'.$info->getFilename();
			$image->thumb(182,154)->save($thumb_path);
			return new ParameterException([
					'code'=>'200',
					'msg'=>'图片上传成功！',
					'errorCode'=>'8888',
					'url' => explode('public',$thumb_path)[1]
			]);
		  }else{
			return new ParameterException([
				'msg'=>'图片上传失败,请重新上传！',
			]);	
		  }
	}
	 
	//多图上传并生成缩略图
	public function uploadFiles(){
		$file = request()->file('file');//获取文件信息
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploadss');
		if($info){
			$path = str_replace('\\','/',$info->getSaveName());
			$date_path = ROOT_PATH . 'public' . DS . 'uploadss';
			$image = \think\Image::open($date_path.'/'. $path);
			$thumb_path = $date_path.'/' .date('Ymd').'/'.'s'.$info->getFilename();
			$image->thumb(414,193)->save($thumb_path);
			return new ParameterException([
					'code'=>'200',
					'msg'=>'图片上传成功！',
					'errorCode'=>'8888',
					'url' => explode('public',$thumb_path)[1]
			]);
		  }else{
			return new ParameterException([
				'msg'=>'图片上传失败,请重新上传！',
			]);	
		  }
	}
	//多图上传
	public function uploads(){
		$file = request()->file('file');//获取文件信息
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploadss');
		if($info){
			$path=str_replace('\\','/',$info->getSaveName());
			return new ParameterException([
							'code'=>'200',
							'msg'=>'图片上传成功！',
							'errorCode'=>'8888',
							'url' => '/uploadss/'. $path
						]);
        }else{
            return new ParameterException([
							'msg'=>'图片上传失败,请重新上传！',
						]);
        }
	}
	
	//七牛云单图上传
	public function qiNiuPic(){
		$fileKey = array_keys(request()->file());
		$file = request()->file($fileKey['0']);
        // 要上传图片的本地路径
        $filePath = $file->getRealPath();
        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
        // 上传到七牛后保存的文件名
        $key =substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;
        require_once APP_PATH . '/../vendor/Qiniu/autoload.php';
        $auth = new Auth(config('qiniu.ACCESSKEY'),config('qiniu.SECRETKEY'));
        // 要上传的空间
        $token = $auth->uploadToken(config('qiniu.BUCKET'));
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret,$err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
			return new ParameterException([
							'msg'=>'图片上传失败,请重新上传！',
						]);
          
        } else {
			return new ParameterException([
						'code'=>'200',
						'msg'=>'图片上传成功！',
						'errorCode'=>'8888',
						'url' => config('qiniu.DOMAIN') .'/'. $ret['key']
			]);
        }

    }
	
	//七牛云图片删除
	public function delFile($fileUrl)
    {	
		$fileNameArr = explode('/',$fileUrl);
		$fileName = $fileNameArr[count($fileNameArr)-1];
		require_once APP_PATH . '/../vendor/Qiniu/autoload.php';
        $auth = new Auth(config('qiniu.ACCESSKEY'),config('qiniu.SECRETKEY'));
		$bucketManager = new BucketManager($auth);
		$err = $bucketManager->delete( config('qiniu.BUCKET'),$fileName); 
		if($err){ 
			return new ParameterException([
				'msg'=>'删除失败，请重试！',
			]); 
		}
    }
	
	//上传视频
	public function video(){
		$fileKey = array_keys(request()->file());
		$file = request()->file($fileKey['0']);
        // 要上传图片的本地路径
        $filePath = $file->getRealPath();
        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
        // 上传到七牛后保存的文件名
        $key =substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;
        require_once APP_PATH . '/../vendor/Qiniu/autoload.php';
        $auth = new Auth(config('qiniu.ACCESSKEY'),config('qiniu.SECRETKEY'));
        // 要上传的空间
        $token = $auth->uploadToken(config('qiniu.BUCKET'));
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret,$err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
			return new ParameterException([
							'msg'=>'视频上传失败,请重新上传！',
						]);
          
        } else {
			return new ParameterException([
						'code'=>'200',
						'msg'=>'视频上传成功！',
						'errorCode'=>'8888',
						'url' => config('qiniu.DOMAIN') .'/'. $ret['key']
			]);
        }
	}
    public function file(){
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

        if($info){
            $result['code'] = 0;
            $result['info'] = '文件上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());

            $result['url'] = '/uploads/'. $path;
            $result['ext'] = $info->getExtension();
            $result['size'] = byte_format($info->getSize(),2);
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['info'] = '文件上传失败!';
            $result['url'] = '';
            return $result;
        }
    }
    public function pic(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['url'] = '/uploads/'. $path;
            return json_encode($result,true);
        }else{
            // 上传失败获取错误信息
            $result['code'] =0;
            $result['info'] = '图片上传失败!';
            $result['url'] = '';
            return json_encode($result,true);
        }
    }
    //编辑器图片上传
    public function editUpload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['data']['src'] = __PUBLIC__.'/uploads/'. $path;
            $result['data']['title'] = $path;
            return json_encode($result,true);
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['msg'] = '图片上传失败!';
            $result['data'] = '';
            return json_encode($result,true);
        }
    }
    //多图上传
    public function upImages(){
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result["src"] = '/uploads/'. $path;
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['msg'] = '图片上传失败!';
            return $result;
        }
    }
}