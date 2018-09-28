<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/login/login.html";i:1534851528;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge，chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $config['background_title']; ?></title>
    <link rel="stylesheet" href="__STATIC__/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="__ADMIN__/css/login.css" />
    <link rel="stylesheet" href="__STATIC__/common/css/font.css" />
</head>
<body class="beg-login-bg">
<div class="container login" style="background-image: url('__PUBLIC__<?php echo $config['background_image']; ?>');">
    <div class="content">
        <div id="large-header" class="large-header" style="background-image: url('__PUBLIC__<?php echo $config['background_image']; ?>');">
            <canvas id="demo-canvas"></canvas>
            <div class="main-title">
                <div class="beg-login-box">
                    <header>
                        <h1><?php echo $config['site_name']; ?></h1>
                    </header>
                    <div class="beg-login-main">
                        <form class="layui-form layui-form-pane" method="post">
                            <div class="layui-form-item">
                                <label class="beg-login-icon fs1">
                                    <span class="icon icon-user"></span>
                                </label>
								
                                <input type="text" name="username" lay-verify="required" placeholder="请输入账号" class="layui-input">
                            </div>
                            <div class="layui-form-item">
                                <label class="beg-login-icon fs1">
                                    <i class="icon icon-key"></i>
                                </label>
                                <input type="password"  name="password" lay-verify="required" placeholder="请输入密码" class="layui-input">
                            </div>
                            <div class="layui-form-item">
                                <input type="text" maxlength="4" name="captcha" id="captcha" lay-verify="required" placeholder="请输入验证码" autocomplete="off" class="layui-input">
                                <div class="captcha">
                                    <img src="<?php echo captcha_src(); ?>" alt="captcha" onclick="this.src=this.src+'?'+'id='+Math.random()"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <button type="submit" id="submit" class="layui-btn btn-submit btn-blog" lay-submit lay-filter="login">登录</button>
                            </div>
                        </form>
                    </div>
                    <footer>
                        <p></p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__ADMIN__/js/rAF.js"></script>
<script src="__ADMIN__/js/login.js"></script>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>
<script>
	
	//这里是layui的固定写法
    layui.use('form',function(){
        var form = layui.form,$ = layui.jquery;
        //监听提交    点击lay-filter="login"的时候传入数据data
        form.on('submit(login)', function(data){
		$("#submit").attr({ disabled: "disabled" });
			//这个是提交时的转的那个等待圈
            loading =layer.load(1, {shade: [0.1,'#fff'] });//0.1透明度的白色背景
			//用post的方式进行提交
			//第一个参数为提交的地址  （必填）
			//第二个参数为数据          （必填）
			//第三个参数为回调函数   接收返回值（选填）
            $.post('',data.field,function(res){
				//关闭  提交时的转的那个等待圈
                layer.close(loading);
				//判断返回的结果
                if(res.errorCode == 8888){
					//登录成功后
					//第一个参数为提示信息
					//第二个参数为  1 表示笑脸   执行时间为一秒
					//第三个参数为函数     跳转到指定的页面
					 
                    layer.msg(res.msg, {icon: 1, time: 1000}, function(){
                        location.href = res.url;
                    });
					
                }else{
					//登陆失败时   清空验证码输入框的内容
                    $('#captcha').val('');
					//参数1：输出错误信息
					//参数2：  2表示哭脸     6表示抖动	  1000表示执行时间为一秒	
                    layer.msg(res.msg, {icon: 2, anim: 6, time: 1000});
					//attr() 方法设置或返回被选元素的属性值。
					//在抖动完成后   随机刷新验证码
                    $('.captcha img').attr('src','<?php echo captcha_src(); ?>?id='+Math.random());
					 $("#submit").removeAttr("disabled");
                }
            });
            return false;//返回false表示限制提交
        });
    });
</script>
</body>
</html>