<?php
namespace app\admin\controller;
use app\common\exception\ParameterException;
use think\Db;
class Database extends Common
{
    protected $db = '', $datadir =  './public/Data/';
    function _initialize(){
        parent::_initialize();
        $db=db('');
		//实例化数据库类
        $this->db = db::connect();
    }
    public function index(){
        if(request()->isPost()){
			//查询出所有的表
            $dbtables = $this->db->query("SHOW TABLE STATUS LIKE '".config('prefix')."%'");
            $total = 0;
			//便利表
            foreach ($dbtables as $k => $v){
                //调用PHP格式化字节大小函数
				$dbtables[$k]['size'] = format_bytes($v['Data_length']);
                //数据库总大小
				$total += $v['Data_length'];
            }
            return $result = ['code'=>0,'msg'=>config('common.acquire_success'),'data'=>$dbtables,'total'=>format_bytes($total),'tableNum'=>count($dbtables),'rel'=>1];
        }
        return view();
    }
    //优化表
    public function optimize(){
        $batchFlag = input('param.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag){
            $table = input('key', array());
        }else {
            $table[] = input('post.tableName');
        }
        if (empty($table)){
            $result['msg'] = config('database.to_be_optimized');
            $result['code'] = 0;
            return $result;
        }
        $strTable = implode(',', $table);
		/*optimize table tableName;如果您已经删除了表的一大部分，或者如果您已经对含有可变长度行的表（含有VARCHAR,BLOB或TEXT列的表）进行了很多更改，则应使用OPTIMIZETABLE。被删除的记录被保持在链接清单中，后续的INSERT操作会重新使用旧的记录位置。您可以使用OPTIMIZE TABLE来重新利用未使用的空间，并整理数据文件的碎片。在多数的设置中，您根本不需要运行OPTIMIZE TABLE。即使您对可变长度的行进行了大量的更新，您也不需要经常运行，每周一次或每月一次即可，只对特定的表运行。OPTIMIZE TABLE只对MyISAM, BDB和InnoDB表起作用。注意，在OPTIMIZE TABLE运行过程中，MySQL会锁定表。*/
        if (!DB::query("OPTIMIZE TABLE {$strTable} ")) {
            $strTable = '';
        }
        return new ParameterException([
            'code'=>'200',
            'msg'=> '数据表优化成功！',
            'errorCode'=>'8888',
            'url'=>url('index')
        ]);
    }
    //修复表
    public function repair() {
        $batchFlag = input('param.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag) {
            $table = input('key', array());
        }else {
            $table[] = input('post.tableName');
        }

        if (empty($table)) {
            $result['msg'] = config('database.table_to_be_fixed');
            $result['code'] = 0;
            return $result;
        }
        $strTable = implode(',', $table);
		/*REPAIR TABLE 用于修复被破坏的表。OPTIMIZE TABLE 用于回收闲置的数据库空间，当表上的数据行被删除时，所占据的磁盘空间并没有立即被回收，使用了OPTIMIZE TABLE命令后这些空间将被回收，并且对磁盘上的数据行进行重排（注意：是磁盘上，而非数据库）。多数时间并不需要运行OPTIMIZE TABLE，只需在批量删除数据行之后，或定期（每周一次或每月一次）进行一次数据表优化操作即可，只对那些特定的表运行。*/
        if (!DB::query("REPAIR TABLE {$strTable} ")) {
            $strTable = '';
        }
        return new ParameterException([
            'code'=>'200',
            'msg'=> '数据表修复成功！',
            'errorCode'=>'8888',
            'url'=>url('index')
        ]);
    }
    //备份
    public function backup(){
        $puttables = input('post.tables/a');
        if(empty($puttables)) {
			//查看表信息
            $dataList = $this->db->query("SHOW TABLE STATUS LIKE '".config('prefix')."%'");
            foreach ($dataList as $row){
                $table[]= $row['Name'];
            }
        }else{
            $table=input('post.tables/a');
        }
		 
		//输出文件头部信息
        $sql = "-- SONGBO SQL Backup\n-- Time:".toDate(time())."\n-- http://www.songbo.com \n\n";
        foreach($table as $key=>$table){
			//表信息
            $sql .= "--\n-- 表的结构 `$table`\n-- \n";
			
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
			//打开表
            $info = $this->db->query("SHOW CREATE TABLE  $table");
            //查出表结构
			$sql .= str_replace(array('USING BTREE','ROW_FORMAT=DYNAMIC'),'',$info[0]['Create Table']).";\n";
            //打开表
			$result = $this->db->query("SELECT * FROM $table");
			
            //判断表中是否有数据
			if($result)$sql .= "\n-- \n-- 导出`$table`表中的数据 `$table`\n--\n";
			//便利数据
            foreach($result as $key=>$val) {
				
                foreach ($val as $k=>$field){
                    if(is_string($field)) {
                        $val[$k] = '\''. $field.'\'';
                    }elseif($field==0){
                        $val[$k] = 0;
                    } elseif(empty($field)){
                        $val[$k] = 'NULL';
                    }
                }
				//将数据存入$sql中
                $sql .= "INSERT INTO `$table` VALUES (".implode(',', $val).");\n";
            }
        }
		//判断文件是否存在    
        $filename = empty($fileName)? date('YmdH').'_'.rand_string(10) : $fileName;
        //打开文件存入数据
		$r= file_put_contents($this->datadir . $filename.'.sql', trim($sql));
		//返回状态
        return new ParameterException([
            'code'=>'200',
            'msg'=> '备份数据库成功！',
            'errorCode'=>'8888'
        ]);
    }
    //备份文件列表
    public function restore(){
        if(request()->isPost()){
			//定义查询文件后缀
            $pattern = "*.sql";
			//glob() 函数返回匹配指定模式的文件名或目录。
			//该函数返回一个包含有匹配文件 / 目录的数组。如果出错返回 false。
            $filelist = glob($this->datadir.$pattern);
            $fileArray = array();
            foreach ($filelist  as $i => $file) {
                //只读取文件
                if (is_file($file)){
					//取得文件大小
                    $_size = filesize($file);
					//函数返回路径中的文件名部分
                    $name = basename($file);
					//截取文件名的下划线前部分  （得到时间）
                    $pre = substr($name, 0, strrpos($name, '_'));
					//把文件名的下划线前和.后替换成空    （得到卷号）
                    $number = str_replace(array($pre. '_', '.sql'), array('', ''), $name);
                    $fileArray[] = array(
                        'name' => $name,
                        'pre' => $pre,
						//filemtime   返回文件上次修改的时间
                        'time' => date('Y-m-d h:i',filemtime($file)),
                        'sortSize' => byte_format($_size),
                        'size' => $_size,
                        'number' => $number,
                    );
                }
            }
			//判断文件是否存在
            if(empty($fileArray)) $fileArray = array();
            return ['code'=>0,'msg'=>config('common.acquire_success'),'data'=>$fileArray,'rel'=>1];
        }
        return view();
    }
    //执行还原数据库操作
    public function restoreData() {
		//定义编码集
        header('Content-Type: text/html; charset=UTF-8');
		//获取文件名
        $filename = input('post.sqlfilepre');
		
		//得到文件所在目录
        $file = $this->datadir.$filename;
		
        //读取数据文件
        $sqldata = file_get_contents($file);
		
		//读表
        $sqlFormat = $this->sql_split($sqldata,config('prefix'));
		
        foreach ((array)$sqlFormat as $sql){
            $sql = trim($sql);
            if (strstr($sql, 'CREATE TABLE')){
                preg_match('/CREATE TABLE `([^ ]*)`/', $sql, $matches);
                $ret = $this->excuteQuery($sql);
            }else{
                $ret =$this->excuteQuery($sql);
            }
        }
        return new ParameterException([
            'code'=>'200',
            'msg'=> '备份数据库成功！',
            'errorCode'=>'8888'
        ]);
    }
	//
    public function excuteQuery($sql='')
    {
		//判断文件是否为空
        if(empty($sql)) {$this->error('空表');}
        $queryType = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|TRUNCATE|REVOKE|LOCK|UNLOCK';
        if (preg_match('/^\s*"?(' . $queryType . ')\s+/i', $sql)) {
            $data['result'] = $this->db->execute($sql);
            $data['type'] = 'execute';
        }else {
            $data['result'] = $this->db->query($sql);
            $data['type'] = 'query';
        }
        $data['dberror'] = $this->db->getError();
        return $data;
    }
	//参数1：文件内容   参数2：表前缀
    function  sql_split($sql,$tablepre) {
		//更新换行符
        $sql = str_replace("\r", "\n", $sql);
		//建立空数组  存放sql文件内容
        $ret = array();
		//默认键为0
        $num = 0;
		//sql结束是添加换行符
        $queriesarray = explode(";\n", trim($sql));
		//销毁数组
        unset($sql);
		//便利数组
        foreach($queriesarray as $query)
        {
			//默认为空
            $ret[$num] = '';
			//添加换行符
            $queries = explode("\n", trim($query));
            //过滤数组
			$queries = array_filter($queries);
            //便利
			foreach($queries as $query)
            {
				//截取第一个字符
                $str1 = substr($query, 0, 1);
                if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }
    //下载
    public function downFile() {
        //文件名称
		$file = $this->request->param('file');
		//文件后缀
        $type = $this->request->param('type');
		//判断文件是否存在
        if (empty($file) || empty($type) || !in_array($type, array("zip", "sql"))) {
            $this->error(config('database.address_does_not_exist'));
        }
		//
        $path = array("zip" => $this->datadir."zipdata/", "sql" => $this->datadir);
        //判断文件是否存在
		$filePath = $path[$type] . $file;
        if (!file_exists($filePath)) {
            $this->error(config('database.address_does_not_exist'));
        }
		//下载文件
        $filename = basename($filePath);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
    }
    //删除sql文件
    public function delSqlFiles() {
        $batchFlag = input('post.batchFlag');
        //批量删除
        if ($batchFlag) {
            $files = input('key', array());
        }else {
            $files[] = input('sqlfilename' , '');
        }
        if (empty($files)) {
            return new ParameterException([
                'msg'=> '删除备份文件失败！',
            ]);
        }
			
        foreach ($files as $file) {
            $a = unlink($this->datadir.'/' . $file);
        }
        if($a){
            return new ParameterException([
                'code'=>'200',
                'msg'=> '删除备份文件成功！',
                'errorCode'=>'8888'
            ]);
        }else{
            return new ParameterException([
                'msg'=> '删除备份文件失败！',
            ]);
        }
    }
}