<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:69:"/www/wwwroot/mp.zihai029.com/application/admin/view/system/index.html";i:1533001386;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/head.html";i:1533002192;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/foot.html";i:1503623994;}*/ ?>
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
        <legend>系统配置</legend>
    </fieldset>
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">网站名称</label>
            <div class="layui-input-4">
                <input type="text" maxlength="200" name="site_name" value="<?php echo $system['site_name']; ?>" lay-verify="site_name" placeholder="请输入网站名称" class="layui-input">
            </div>
        </div>
		<div class="layui-form-item">
            <label class="layui-form-label">后台标题</label>
            <div class="layui-input-4">
                <input type="text" maxlength="200" name="background_title" value="<?php echo $system['background_title']; ?>" lay-verify="background_title" placeholder="请输入后台标题" class="layui-input">
            </div>
        </div>
        
		<div class="layui-form-item">
            <label class="layui-form-label">登录图</label>
            <input type="hidden" lay-verify="background_image" name="background_image" id="logo2" value="<?php echo $system['background_image']; ?>">
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-primary" id="logoBtn2"><i class="icon icon-upload3"></i>点击上传</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="SLogo2">
                        <p id="demoText2"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="submit" lay-submit="" lay-filter="sys"><?php echo lang('submit'); ?></button>
                
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script>
    layui.use(['form', 'layer','upload'], function () {
        var form = layui.form,layer = layui.layer,upload = layui.upload,$ = layui.jquery;
		if("<?php echo $system['background_image']; ?>"){
            SLogo2.src = "__PUBLIC__"+ "<?php echo $system['background_image']; ?>";
        }
		form.verify({
				
				site_name: function(value){  
						if(value == ''){  
							return '请输入网站名称！';  
						}  
					},
				background_title: function(value){  
						if(value == ''){  
							return '请输入后台标题！';  
						}  
					},
				background_image: function(value){  
						if(value == ''){  
							return '请添加登录图！';  
						}  
					}				
				});
		//普通图片上传    后台登录页背景图片
        var uploadInst2 = upload.render({
			elem: '#logoBtn2'
            ,url: '<?php echo url("UpFiles/upload"); ?>'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#SLogo2').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                    if(res.errorCode==8888){
                        $('#logo2').val(res.url);
						layer.msg(res.msg,{time:1000,icon:1});
                    }else{
						$('#SLogo2').attr('src','');
                        layer.msg(res.msg,{time:2000,icon:2});
                    }
                }
            
        });
        //提交监听
        form.on('submit(sys)', function (data) {
			$("#submit").attr({ disabled: "disabled" });
            loading =layer.load(1, {shade: [0.1,'#fff']});
            $.post("<?php echo url('system/index'); ?>",data.field,function(res){
                layer.close(loading);
                if(res.errorCode == 8888){
                    layer.msg(res.msg,{icon: 1, time: 1000},function(){
                        location.href = res.url;
                    });
                }else{
					$("#submit").removeAttr("disabled");
                    layer.msg(res.msg,{icon: 2, time: 1000});
                }
            });
        })
    })
	

</script>


</body>
</html>