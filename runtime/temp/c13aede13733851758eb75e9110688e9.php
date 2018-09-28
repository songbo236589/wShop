<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/goods/index.html";i:1535702741;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/head.html";i:1533002192;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/foot.html";i:1503623994;}*/ ?>
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
        <legend>商品<?php echo lang('list'); ?></legend>
    </fieldset>
    <div class="demoTable layui-row">
        <div class="layui-form layui-form-pane" style="float:left;">
			<div class="layui-form-item">
				<label class="layui-form-label">商品名称</label>
				<div class="layui-input-inline layui-form">
					<input class="layui-input" name="key" id="key" placeholder="请输入商品名称">
				</div>
			</div>
        </div>
		<div class="layui-form layui-form-pane" style="float:left;width:460px">
			<div class="layui-form-item">
				<label class="layui-form-label"><?php echo lang('add_time'); ?></label>
				<div style="width:72%" class="layui-input-inline layui-form">
					<input style="width:100%" type="text" class="layui-input" id="test10" placeholder="请选择时间">
				</div>
			</div>	
		</div>
		<div class="layui-form layui-form-pane" style="float:left">
			<div class="layui-form-item">
				<label class="layui-form-label">商品分类</label>
				<div class="layui-input-inline layui-form">
					<select id="goods_category_id">
						<option value=""></option>
						<?php if(is_array($goods_category) || $goods_category instanceof \think\Collection || $goods_category instanceof \think\Paginator): $i = 0; $__LIST__ = $goods_category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_category): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo $goods_category['id']; ?>"><?php echo $goods_category['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			</div>		
		</div>
		<div class="layui-form layui-form-pane" style="float:left">
			<div class="layui-form-item">
				<label class="layui-form-label">推荐banner</label>
				<div class="layui-input-inline layui-form">
					<select id="openBannere">
						<option value=""></option>
						<?php if(is_array($openBannerData) || $openBannerData instanceof \think\Collection || $openBannerData instanceof \think\Paginator): $i = 0; $__LIST__ = $openBannerData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$openBannerData): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo $key; ?>"><?php echo $openBannerData; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			</div>		
		</div>
		<div class="layui-form layui-form-pane" style="float:left">
			<div class="layui-form-item">
				<label class="layui-form-label"><?php echo lang('status'); ?></label>
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
	<blockquote class="layui-elem-quote">
        <a href="<?php echo url('add'); ?>" class="layui-btn layui-btn-small">添加商品</a>
    </blockquote>
    <table class="layui-table" id="list" lay-filter="list"></table>
</div>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script type="text/html" id="status">
	<input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="上架|下架" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="open_banner">
	<input type="checkbox" name="open_banner" value="{{d.id}}" lay-skin="switch" lay-text="<?php echo lang('yes'); ?>|<?php echo lang('no'); ?>" lay-filter="open_banner" {{ d.open_banner == 1 ? 'checked' : '' }}>
</script>

<script type="text/html" id="order">
    <input name="{{d.id}}" data-id="{{d.id}}" class="list_order layui-input" value="{{d.sort}}" maxlength="11" size="10"/>
</script>
<script type="text/html" id="action">
    <a href="<?php echo url('edit'); ?>?id={{d.id}}" class="layui-btn layui-btn-xs"><?php echo lang('edit'); ?></a>
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
                {field: 'name', align: 'center',title: '商品名称', width: 150},
                {field: 'store_count', align: 'center',title: '库存数量', width: 150, sort: true},
                {field: 'sales_sum', align: 'center',title: '销量', width: 150, sort: true},
                {field: 'shop_price', align: 'center',title: '本店售价', width: 150, sort: true},
                {field: 'market_price', align: 'center',title: '市场价', width: 150, sort: true},
                {field: 'cname', align: 'center',title: '商品分类', width: 150},
                {field: 'add_time',align: 'center', title: '<?php echo lang("add_time"); ?>',width: 150, sort: true},
                {field: 'edit_time',align: 'center', title: '<?php echo lang("edit_time"); ?>',width: 150, sort: true},
				{field: 'status', align: 'center', title: '<?php echo lang("status"); ?>', width: 100, toolbar: '#status'},
				{field: 'open_banner', align: 'center', title: '推荐banner', width: 150, toolbar: '#open_banner'},
                {field: 'sort', align: 'center', title: '<?php echo lang("order"); ?>', width: 120, templet: '#order', sort: true},
                {width: 160, align: 'center',title: '<?php echo lang("action"); ?>', toolbar: '#action'}
            ]],
            limit:''
        });
        //搜索
        $('#search').on('click', function () {
			var goods_category_id 	= $('#goods_category_id').val();
			var time 	= $('#test10').val();
			var status  = $('#statuslist').val();
			var key  = $('#key').val();
			var openBannere  = $('#openBannere').val();
			//alert(addtime);
            if ($.trim(openBannere) === '' && $.trim(key) === '' && $.trim(status) === '' && $.trim(goods_category_id) === '' && $.trim(time) === '' ){
                layer.msg('<?php echo lang("query_condition"); ?>', {icon: 0});
                return;
            }
				tableIn.reload({
					where: {openBannere:openBannere,key:key,goods_category_id:goods_category_id,time:time,status:status}
				});
            
        });
		form.on('switch(open_banner)', function(obj){
            loading =layer.load(1, {shade: [0.1,'#fff']});
            var id = this.value;
            var open_banner = obj.elem.checked===true?1:0;
            $.post('<?php echo url("openBanner"); ?>',{'id':id,'open_banner':open_banner},function (res) {
                layer.close(loading);
                if (res.errorCode == 8888){
						layer.msg(res.msg,{time:1000,icon:1});
						//tableIn.reload();
                    }else{
                        layer.msg(res.msg,{time:1000,icon:2},function(){
							location.href = res.url;
							});
                        return false;
                    }

            })
        });	
		form.on('switch(status)', function(obj){
            loading =layer.load(1, {shade: [0.1,'#fff']});
            var id = this.value;
            var status = obj.elem.checked===true?1:0;
            $.post('<?php echo url("editState"); ?>',{'id':id,'status':status},function (res) {
                layer.close(loading);
                if (res.errorCode == 8888){
						layer.msg(res.msg,{time:1000,icon:1});
						//tableIn.reload();
                    }else{
                        layer.msg(res.msg,{time:1000,icon:2},function(){
							location.href = res.url;
							});
                        return false;
                    }

            })
        });	
        table.on('tool(list)', function(obj) {
            var data = obj.data;
            if(obj.event === 'del'){
				//单个删除
                layer.confirm('确定要删除该商品吗？', function(index){
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
        $('body').on('blur','.list_order',function() {
            var id = $(this).attr('data-id');
            var sort = $(this).val();
            var loading = layer.load(1, {shade: [0.1, '#fff']});
            $.post('<?php echo url("listOrder"); ?>',{'id':id,'sort':sort},function(res){
                layer.close(loading);
                //获取返回值
				if(res.errorCode==8888){
                    layer.msg(res.msg,{time:1000,icon:1});
                }else{
                    layer.msg(res.msg,{time:1000,icon:2},function(){
						location.href = res.url;
					});
                }
            })
        });
        $('#delAll').click(function(){
			//批量删除
            layer.confirm('确定要删除选中的全部商品吗？', {icon: 3}, function(index) {
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