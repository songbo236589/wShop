<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"/www/wwwroot/mp.zihai029.com/application/admin/view/pv/index.html";i:1534160024;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/head.html";i:1533002192;s:68:"/www/wwwroot/mp.zihai029.com/application/admin/view/common/foot.html";i:1503623994;}*/ ?>
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
        <legend>访问统计</legend>
    </fieldset>
	<div class="demoTable layui-row" style="margin-bottom:80px">
		<div class="layui-form layui-form-pane" style="float:left">
			<div class="layui-form-item">
				<label class="layui-form-label">图片类型</label>
				<div class="layui-input-inline layui-form">
					<select id="types" style="z-index:9999">
						<option value="0"></option>
						<?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo $key; ?>"><?php echo $type; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			</div>
			<button class="layui-btn" style="margin-left:30px;margin-top:15px;" id="search" data-type="reload"><?php echo lang('search'); ?></button>
			<a href="<?php echo url('index'); ?>" style="margin-top:15px;" class="layui-btn"><?php echo lang('reset'); ?></a>	
		</div>
		
    </div>
	
    
</div>
<div id="main" style="float:left;width: 100%;height:600px;"></div>
<script type="text/javascript" src="__STATIC__/echarts/echarts.js"></script>
<script type="text/javascript" src="__STATIC__/common/js/jquery.2.1.1.min.js"></script>
<script type="text/javascript" src="__STATIC__/plugins/layui/layui.js"></script>


<script type="text/javascript">
	$('#search').click(function(){
		var types = $('#types').val();
		getCategoryPolice(types);
	})
	var myChart = echarts.init(document.getElementById('main'));
	getCategoryPolice(0);
	function getCategoryPolice(type){
		myChart.showLoading();  //数据加载完之前先显示一段简单的loading动画
		$.post("",{type:type}, function (res) {
			
			if(res.length>0){
			var dateList = res.map(function (item) {
				return item[0];
			});
			var valueList = res.map(function (item) {
				return item[1];
			});
			option = {
				//标题配置
				 title: {
					//设置标题
					text: '站点访问统计',
					//标题文字样式
					textStyle:{
						//标题颜色
						color: '#333',
						//主标题文字字体的风格'normal','italic','oblique'
						fontStyle:'normal',
						//主标题文字字体的粗细   100 | 200 | 300 | 400...
						fontWeight:700,
						//主标题文字的字体系列（字体类型）   'sans-serif','serif' , 'monospace', 'Arial', 'Courier New', 'Microsoft YaHei', ...
						fontFamily:'sans-serif',
						//主标题文字的字体大小
						fontSize:32,
						//文字本身的描边颜色。
						//textBorderColor:'#000000',
						//文字本身的描边宽度。
						//textBorderWidth:10,
						//文字本身的阴影颜色。
						//textShadowColor:'#000000',
						//文字本身的阴影长度。
						//textShadowBlur:10,
						//文字本身的阴影 X 偏移。
						//textShadowOffsetX:10,
						//文字本身的阴影 Y 偏移。
						//textShadowOffsetY:10
					},
					//标题内边距，单位px，默认各方向内边距为5，接受数组分别设定上右下左边距。[ 5,  // 上10, // 右5,  // 下10, // 左]
					padding:[ 	0,  // 上
								0, // 右
								0,  // 下
								0, // 左
							],
					left: 'center'
				},
				//工具栏。内置有导出图片，数据视图，动态类型切换，数据区域缩放，重置五个工具。
				toolbox: {
					show: true,
					//工具栏 icon 的大小。
					itemSize:30,
					//工具栏 icon 每项之间的间隔。横向布局时为水平间隔，纵向布局时为纵向间隔。
					itemGap:20,
					//是否在鼠标 hover 的时候显示每个工具 icon 的标题。
					showTitle:true,
					//工具栏组件离容器左侧的距离。left 的值可以是像 20 这样的具体像素值，可以是像 '20%' 这样相对于容器高宽的百分比，也可以是 'left', 'center', 'right'。如果 left 的值为'left', 'center', 'right'，组件会根据相应的位置自动对齐。
					left:1000,
					feature: {
						dataView: {readOnly: false},
						magicType: {type: ['line', 'bar']},
						restore: {},
						saveAsImage: {}
					}
				},
				tooltip: {
					formatter: function (params) {	
						return params.name + '<br/>访问量：' + params.value;
					}
				},
				xAxis:{
					type: 'category',
					data: dateList,
					//坐标轴名称。
					name:'时间轴',
					//坐标轴名称显示位置。'start''middle' 或者 'center''end'
					nameLocation:'end',
					//坐标轴名称的文字样式。
					nameTextStyle:{
						//坐标轴名称的颜色，默认取 axisLine.lineStyle.color。
						color:'#444',
						//坐标轴名称文字字体的风格'normal''italic''oblique'
						fontStyle:'oblique',
						//坐标轴名称文字字体的粗细  100 | 200 | 300 | 400...
						fontWeight:700,
						//坐标轴名称文字的字体系列'sans-serif','serif' , 'monospace', 'Arial', 'Courier New', 'Microsoft YaHei', ...
						fontFamily:'sans-serif',
						//坐标轴名称文字的字体大小
						fontSize:18
					},
					//坐标轴刻度相关设置。
					axisTick: {
						//是否显示坐标轴刻度。
						show:true,
						//类目轴中在 boundaryGap 为 true 的时候有效，可以保证刻度线和标签对齐。如下图：
						alignWithLabel: true,
						//坐标轴刻度的长度。
						length:5,
						//样式设置
						lineStyle:{
							//刻度线的颜色，默认取 axisLine.lineStyle.color。
							color:'red',
							//坐标轴刻度线宽。
							width:3,
							//坐标轴刻度线的类型。'solid''dashed''dotted'
							type:'solid',
						}
					},
					//坐标轴轴线相关设置。
					axisLine:{
						lineStyle:{
							color: '#444',
							//坐标轴线线宽。
							width:3
						}
					},
					//坐标轴刻度标签的相关设置。
					axisLabel:{
						//刻度标签文字的颜色，默认取 axisLine.lineStyle.color。支持回调函数，格式如下
						color:'#444',
						//文字字体的风格'normal''italic''oblique'
						fontStyle:'oblique',
						//文字字体的粗细100 | 200 | 300 | 400...
						fontWeight:700,
						//文字的字体大小
						fontSize:16
					}
				},
				yAxis: {
					type: 'value',
					//坐标轴名称。
					name:'访问量',
					//坐标轴名称显示位置。'start''middle' 或者 'center''end'
					nameLocation:'end',
					//坐标轴名称的文字样式。
					nameTextStyle:{
						//坐标轴名称的颜色，默认取 axisLine.lineStyle.color。
						color:'#444',
						//坐标轴名称文字字体的风格'normal''italic''oblique'
						fontStyle:'oblique',
						//坐标轴名称文字字体的粗细  100 | 200 | 300 | 400...
						fontWeight:700,
						//坐标轴名称文字的字体系列'sans-serif','serif' , 'monospace', 'Arial', 'Courier New', 'Microsoft YaHei', ...
						fontFamily:'sans-serif',
						//坐标轴名称文字的字体大小
						fontSize:18
					},
					//坐标轴刻度相关设置。
					axisTick: {
						//是否显示坐标轴刻度。
						show:true,
						//类目轴中在 boundaryGap 为 true 的时候有效，可以保证刻度线和标签对齐。如下图：
						alignWithLabel: true,
						//坐标轴刻度的长度。
						length:5,
						//样式设置
						lineStyle:{
							//刻度线的颜色，默认取 axisLine.lineStyle.color。
							color:'red',
							//坐标轴刻度线宽。
							width:3,
							//坐标轴刻度线的类型。'solid''dashed''dotted'
							type:'solid',
						}
					},
					//坐标轴轴线相关设置。
					axisLine:{
						lineStyle:{
							color: '#444',
							//坐标轴线线宽。
							width:3
						}
					},
					//坐标轴刻度标签的相关设置。
					axisLabel:{
						//刻度标签文字的颜色，默认取 axisLine.lineStyle.color。支持回调函数，格式如下
						color:'#444',
						//文字字体的风格'normal''italic''oblique'
						fontStyle:'oblique',
						//文字字体的粗细100 | 200 | 300 | 400...
						fontWeight:700,
						//文字的字体大小
						fontSize:16
					}
				},
				series: [{
					data: valueList,
					type: 'line',
					smooth: true,
					//线条样式。
					lineStyle:{
						color:'red',
						//线宽。
						width:3,
					},
					//区域填充样式。
					areaStyle:{
						//填充的颜色。
						color: '#FFFFFF',
					}
					
				}]
			};
			myChart.hideLoading();    //隐藏加载动画
			myChart.setOption(option);
			}else{
				alert('asdds')
			}
		
		});
	}
	
</script>
<script>
     layui.use(['table'], function() {})
</script>