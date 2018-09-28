//项目名称
var header = 'wShop';
//接口路径
var baseRequestUrl = 'https://mp.zihai029.com/api/v1/';
//公共的ajax请求方法
function request(params,callBack){
	url = baseRequestUrl + params.url;
	if(!params.type){
		params.type='GET';
	}
	vm.$http({
		url: url,
		method: params.type,
		// 请求体重发送的数据
		data: params.data,
		headers:{
			'Content-Type': 'application/x-www-form-urlencoded'
		},
	}).then(function(response){
		var code = response.status.toString();
		var startChar = code.charAt(0);
		if (startChar == '2' || startChar == '4'){
			callBack && callBack(response.data);
		}
	},function(){
		console.log('失败回调');
	})
}