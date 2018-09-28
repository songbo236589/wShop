<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:70:"/www/wwwroot/mp.zihai029.com/application/admin/view/comment/index.html";i:1535717153;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/head.html";i:1533002192;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/foot.html";i:1503623994;}*/ ?>
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
        <legend>评论<?php echo lang('list'); ?></legend>
    </fieldset>
    <div class="demoTable layui-row">
        <div class="layui-form layui-form-pane" style="float:left;">
			<div class="layui-form-item">
				<label class="layui-form-label">快捷查询</label>
				<div class="layui-input-inline layui-form">
					<input class="layui-input" name="key" id="key" placeholder="请输入微信昵称/商品名称">
				</div>
			</div>
        </div>
		<div class="layui-form layui-form-pane" style="float:left;width:460px">
			<div class="layui-form-item">
				<label class="layui-form-label">评论时间</label>
				<div style="width:72%" class="layui-input-inline layui-form">
					<input style="width:100%" type="text" class="layui-input" id="test10" placeholder="请选择时间">
				</div>
			</div>	
		</div>
		<div class="layui-form layui-form-pane" style="float:left">
			<div class="layui-form-item">
				<label class="layui-form-label">评论级别</label>
				<div class="layui-input-inline layui-form">
					<select id="statuslist">
						<option value=""></option>
						<?php if(is_array($statuslist) || $statuslist instanceof \think\Collection || $statuslist instanceof \think\Paginator): $i = 0; $__LIST__ = $statuslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$statuslist): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo $key; ?>"><?php echo $statuslist; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			</div>		
		</div>
    </div>
	<div class="demoTable layui-row">
		<button class="layui-btn" style="margin-left:30px;margin-top:15px;" id="search" data-type="reload"><?php echo lang('search'); ?></button>
		<a href="<?php echo url('index'); ?>" style="margin-top:15px;" class="layui-btn"><?php echo lang('reset'); ?></a>
		<button type="button" style="margin-top:15px;" class="layui-btn layui-btn-danger" id="delAll"><?php echo lang('batch_remove'); ?></button>
	</div>
    <table class="layui-table" id="list" lay-filter="list"></table>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script type="text/html" id="action">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><?php echo lang('del'); ?></a>
</script>
<script>

	layui.use('laydate', function(){
		var laydate = layui.laydate;
						
		//日期时间范围
		laydate.render({
			elem: '#test10'
			,type: 'datetime'
			,range: true
		});
	})
     layui.use(['table','form'], function() {
        var table = layui.table,form = layui.form,$ = layui.jquery;
        var tableIn = table.render({
            id: 'ad',
            elem: '#list',
            url: '<?php echo url("index"); ?>',
            method: 'post',
            page:true,
            cols: [[
                {checkbox: true, fixed: true},
                {field: 'id',align: 'center', title: '<?php echo lang("id"); ?>', width: 80, fixed: true, sort: true},
				{field: 'nickName', align: 'center',title: '微信昵称', width: 200},
				{field: 'name', align: 'center',title: '商品名称', width: 200},
				{field: 'star', align: 'center',title: '评论级别', width: 200},
				{field: 'content', align: 'center',title: '评论内容', width: 300},
                {field: 'add_time',align: 'center', title: '评论时间',width: 200, sort: true},
                {width: 160, align: 'center',title: '<?php echo lang("action"); ?>', toolbar: '#action'}
            ]],
            limit:''
        });
        //搜索
        $('#search').on('click', function () {
			var star  = $('#statuslist').val();
			var add_time 	= $('#test10').val();
			var key  = $('#key').val();
            if ($.trim(star) === '' && $.trim(add_time) === '' && $.trim(key) === ''){
                layer.msg('<?php echo lang("query_condition"); ?>', {icon: 0});
                return;
            }
				tableIn.reload({
					where: {star:star,add_time:add_time,key:key}
				});
            
        });
        table.on('tool(list)', function(obj) {
            var data = obj.data;
            if(obj.event === 'del'){
				//单个删除
                layer.confirm('确定要删除该日志吗？', function(index){
                    var loading = layer.load(1, {shade: [0.1, '#fff']});
                    $.post("<?php echo url('del'); ?>",{id:data.id},function(res){
                        layer.close(loading);
                        if(res.errorCode == 8888){
                            layer.msg(res.msg,{time:1000,icon:1});
                            tableIn.reload();
                        }else{
                            layer.msg(res.msg,{time:1000,icon:2});
                        }
                    });
                    layer.close(index);
                });
            }
        });
        $('#delAll').click(function(){
			//批量删除
            layer.confirm('确定要删除所选中的全部日志吗？', {icon: 3}, function(index) {
                layer.close(index);
                var checkStatus = table.checkStatus('ad'); //test即为参数id设定的值
                var ids = [];
                $(checkStatus.data).each(function (i, o) {
                    ids.push(o.id);
                });
                var loading = layer.load(1, {shade: [0.1, '#fff']});
                $.post("<?php echo url('delall'); ?>", {'ids': ids}, function (data) {
                    layer.close(loading);
					//console.log(data);
                    if (data.errorCode == 8888) {
                        layer.msg(data.msg, {time: 1000, icon: 1});
                        tableIn.reload();
                    } else {
                        layer.msg(data.msg, {time: 1000, icon: 2});
                    }
                });
            });
        })
    })
</script>