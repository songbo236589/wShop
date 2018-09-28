//api请求地址
var baseRequestUrl = 'https://mp.zihai029.com/api/v1/';
//轮播图切换时间
var swiperTome = 2000;
//下拉刷新执行时间
var setTimeoutTime = 2000;
//初始化下拉刷新显示
$('.s-refresh__layer').show();
//ajax请求方法
function request(params,callBack){
	url = baseRequestUrl + params.url;
	if(!params.type){
		params.type='GET';
	}
	$.ajax({
		type: params.type, //数据的提交方式：get和post
		url: url,//当前页地址。发送请求的地址。
		async:true,   //是否支持异步刷新，默认是true
		dataType:'JSON',   //服务器返回数据的类型，例如xml,String,Json等
		timeout:2000,//  要求为Number类型的参数，设置请求超时时间（毫秒）。此设置将覆盖$.ajaxSetup()方法的全局设置。
		cache:true,//要求为Boolean类型的参数，默认为true（当dataType为script时，默认为false）。设置为false将不会从浏览器缓存中加载请求信息。
		data: params.data,//需要提交的数据
		beforeSend: function(request){ //设置header头信息
			request.setRequestHeader("token","Chenxizhang");
		},
		complete:function(res){
			//if(res.readyState == 4 && res.status == 200){
				
			//}
		},
		success: function(res) { //请求成功后的回调函数
			callBack && callBack(res);
		},
		error:function(err){ //请求失败后的回调函数
			console.log(err);
		}  
	});
};
//获取id参数
function sessionId(){
	var url=window.location.search;
	return url.split('=')[1];
}

//调用轮播图
function swiperPic(){
	setTimeout(function() {
		new Swiper('.swiper-container', {
			loop: true,
			autoplay: swiperTome,
			pagination:'.swiper-pagination'
		})
	}, 500);	
}

//获取滚动条当前的位置 
function getScrollTop() {
	var scrollTop = 0;
	if(document.documentElement && document.documentElement.scrollTop) {
		scrollTop = document.documentElement.scrollTop;
	} else if(document.body) {
		scrollTop = document.body.scrollTop;
	}
	return scrollTop;
}

//获取当前可视范围的高度 
function getClientHeight() {
	var clientHeight = 0;
	if(document.body.clientHeight && document.documentElement.clientHeight) {
		clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight);
	} else {
		clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight);
	}
	return clientHeight;
}

//获取文档完整的高度 
function getScrollHeight() {
	return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
}

//设置cookie
function setCookie(name,value,days){    //封装一个设置cookie的函数
	var oDate=new Date();
	oDate.setDate(oDate.getDate()+days);   //days为保存时间长度
	document.cookie=name+'='+value+';expires='+oDate;
}
//获取cookie
function getCookie(name){
	var arr=document.cookie.split(';');
	for(var i=0;i<arr.length;i++){
		var arr2=arr[i].split('=');
		if(arr2[0]==name){
			return arr2[1];  //找到所需要的信息返回出来
		}
	}
	return '';        //找不到就返回空字符串
}
//删除cookie
function removeCookie(name){
    setCookie(name,1,-1); 
}