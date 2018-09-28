<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/qiniu/index.html";i:1536653506;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/head.html";i:1533002192;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/foot.html";i:1503623994;}*/ ?>
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
<div class="admin-main layui-anim layui-anim-upbit">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>七牛图片上传</legend>
    </fieldset>
    <form class="layui-form layui-form-pane">
		<div class="layui-form-item">
            <label class="layui-form-label">单图</label>
            <input type="hidden" lay-verify="image" name="image" id="image" value="">
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-primary" id="adBtn"><i class="icon icon-upload3"></i>点击上传</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="adPic"/>
                    </div>
                </div>
            </div>
        </div>
		<div class="layui-form-item">
            <label class="layui-form-label">视频</label>
            <input type="hidden" lay-verify="video" name="video" id="video" value="">
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-primary" id="test5"><i class="icon icon-upload3"></i>点击上传</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="adPic"/>
                    </div>
                </div>
            </div>
        </div>
		<div id="videoList"></div>
		<div class="layui-progress layui-progress-big" id="layui-progress-big" style="width:50%;display:none;" lay-showpercent="true" lay-filter="demo">
			<div class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>
		</div>
		
    </form>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script>
    layui.use(['form', 'layer','upload','element'], function () {
		var form = layui.form, $ = layui.jquery, upload = layui.upload,element = layui.element;
		//单图上传
		upload.render({
			elem: '#adBtn',
			url: "<?php echo url('UpFiles/qiNiuPic'); ?>",
			done: function(res){
				if(res.errorCode==8888){			
					$('#adPic').attr('src',res.url);
					$('#image').val(res.url);
					layer.msg(res.msg,{time:1000,icon:1});
				}else{
					$('#adPic').attr('src','');
					layer.msg(res.msg,{time:2000,icon:2});
				}
			}
		});
        upload.render({
			elem: '#test5'
			,url: "<?php echo url('UpFiles/video'); ?>"
			,accept: 'video' //视频
			,before: function(input){
				$('#layui-progress-big').css('display','block');
				var n = 0;
				timer = setInterval(function(){  
					if(n<99){
						n += 1;
					}
					element.progress('demo', n+'%');
				},1000);
			}
			,done: function(res){
				if(res.errorCode==8888){
					clearInterval(timer);
					element.progress('demo', '100%');
					var html = '<video controls preload="auto" width="400px" height="300px">';
					html += '<source src="'+res.url+'" type="video/mp4"/>';
					html += '</video>';
					$('#videoList').html(html);
					$('#video').val(res.url);
					layer.msg(res.msg,{time:1000,icon:1});
				}else{
					element.progress('demo', '0%');
					$('#videoList').html('');
					$('#video').val('');
					layer.msg(res.msg,{time:2000,icon:2});
				}			
			}
		});
		
    })
</script>
</body>
</html>