<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:72:"D:\phpStudy\PHPTutorial\WWW\wshop/application/admin\view\index\main.html";i:1523347198;s:73:"D:\phpStudy\PHPTutorial\WWW\wshop/application/admin\view\common\head.html";i:1533002192;s:73:"D:\phpStudy\PHPTutorial\WWW\wshop/application/admin\view\common\foot.html";i:1503623994;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $site_name; ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="__ADMIN__/css/global.css" media="all">
    <link rel="stylesheet" href="__STATIC__/common/css/font.css" media="all">
</head>
<body class="skin-<?php if(!empty($_COOKIE['skin'])){echo $_COOKIE['skin'];}else{echo '0';setcookie('skin','0');}?>">
<div class="admin-main layui-anim layui-anim-upbit" ng-app="hd" ng-controller="ctrl">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>控制面版</legend>
    </fieldset>
</div>
<div class="admin-main layui-anim layui-anim-upbit">
    <div class="table-responsive">
        <table class="layui-table" lay-even lay-skin="line">
            <colgroup>
                <col width="40%">
                <col>
            </colgroup>
            <tbody>
			<tr>
                <td>当前管理员</td>
                <td><?php echo $session['username']; ?>
					<a href="<?php echo url('index/edit'); ?>" style="margin-left:20px" class="layui-btn layui-btn-warm layui-btn-radius">修改密码</a>
				</td>
            </tr>
            <tr>
                <td>网站域名</td>
                <td><?php echo $config['url']; ?></td>
            </tr>
            <tr>
                <td>网站目录</td>
                <td><?php echo $config['document_root']; ?></td>
            </tr>
            <tr>
                <td>服务器操作系统</td>
                <td><?php echo $config['server_os']; ?></td>
            </tr>
            <tr>
                <td>服务器端口</td>
                <td><?php echo $config['server_port']; ?></td>
            </tr>
            <tr>
                <td>服务器IP</td>
                <td><?php echo $config['server_ip']; ?></td>
            </tr>
            <tr>
                <td>WEB运行环境</td>
                <td><?php echo $config['server_soft']; ?></td>
            </tr>
            <tr>
                <td>MySQL数据库版本</td>
                <td><?php echo $config['mysql_version']; ?></td>
            </tr>
            <tr>
                <td>运行PHP版本</td>
                <td><?php echo $config['php_version']; ?></td>
            </tr>

            <tr>
                <td>最大上传限制</td>
                <td><?php echo $config['max_upload_size']; ?></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script>
    layui.use('table', function() {
        var table = layui.table;
    })
</script>
</body>
</html>