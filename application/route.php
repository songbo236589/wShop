<?php

use think\Route;


//换取Token令牌
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

//判断Token令牌是否过期
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');

//判断用户是否授权登陆
Route::post('api/:version/token/determine', 'api/:version.Token/determineUser');

//访问量
Route::get('api/:version/getPv', 'api/:version.Pv/getPv');

//Banner
Route::get('api/:version/banner', 'api/:version.Index/getBanner');

//商品列表
Route::get('api/:version/getGoods/:type/:page', 'api/:version.Index/getGoods');

//商品详情页
Route::post('api/:version/getGoodsContent', 'api/:version.Index/getGoodsContent');

//商品分类列表
Route::get('api/:version/getGoodsTypeList/:page', 'api/:version.Goods/getGoodsTypeList');

//加入购物车
Route::post('api/:version/postAddCart', 'api/:version.Cart/postAddCart');

//购物车列表
Route::get('api/:version/getCartList', 'api/:version.Cart/getCartList');

//购物车减号
Route::post('api/:version/postCartSubtract', 'api/:version.Cart/postCartSubtract');

//购物车加号
Route::post('api/:version/postCartAdd', 'api/:version.Cart/postCartAdd');

//单个是否选中
Route::post('api/:version/postCartStatus', 'api/:version.Cart/postCartStatus');

//全选全不选
Route::post('api/:version/postCartAll', 'api/:version.Cart/postCartAll');

//立即结算 支付
Route::post('api/:version/PayCart', 'api/:version.Cart/PayCart');

//支付回调接口
Route::post('api/:version/Cart/redirectNotify', 'api/:version.Cart/redirectNotify');

//添加收货地址
Route::post('api/:version/addLocation', 'api/:version.ShippingAddress/addLocation');

//收货地址列表
Route::get('api/:version/locationList', 'api/:version.ShippingAddress/locationList');

//收货地址选择
Route::post('api/:version/locationStatus', 'api/:version.ShippingAddress/locationStatus');

//收货地址删除
Route::post('api/:version/locationDelete', 'api/:version.ShippingAddress/locationDelete');

//订单列表
Route::get('api/:version/orderList/:page/:status', 'api/:version.Order/orderList');

//取消订单
Route::post('api/:version/orderDelete','api/:version.Order/orderDelete');

//订单管理去支付
Route::post('api/:version/orderPay','api/:version.Order/orderPay');

//确定收货
Route::post('api/:version/orderConfirm','api/:version.Order/orderConfirm');

//商品评论
Route::get('api/:version/getOrderItem/:id','api/:version.Order/getOrderItem');


//提交评论
Route::post('api/:version/commentT','api/:version.Order/commentT');

//获取省市区信息
Route::get('api/:version/getRegion','api/:version.Region/getRegion');



















