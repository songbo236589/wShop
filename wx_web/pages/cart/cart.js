import { Cart } from 'cart-model.js';
var cart = new Cart();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    dataErr: false,  
    loadingHidden: false,//数据是否加载完成
    cartArr:[],//当前用户购物车商品数据
    total_num: 0,//当前选中的商品数量
    total_price: 0.00,//当前选中的总价格
    cartAll:0,//是否全选
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {
    var that = this;
    that._loadData();
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, cart.requestTime())  
  },
  _loadData: function (callback) {
    var that = this;
    cart.getCartList( (res) => {
      if (res.error_code == 8888) {
        if (res.msg.list.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          cartArr:res.msg.list,
          total_num:res.msg.total_num,
          total_price:res.msg.total_price,
          cartAll: res.msg.cartAll
        })
      }
    })
    callback && callback();
  },
  add_cart:function(){
    wx.switchTab({
      url: '../index/index'
    })
  },
  //减
  subtract: function (event){
    var that = this;
    var id = cart.getDataSet(event, 'id');
    cart.postCartSubtract(id, (res) => {
      if (res.error_code == 8888) {
        that.setData({
          cartArr: res.msg.list,
          total_num: res.msg.total_num,
          total_price: res.msg.total_price,
          cartAll: res.msg.cartAll
        })
      }else{
        cart.tanShowToast(res.msg, 'loading');
      }
    })
  },
  //加
  add: function (event) {
    var that = this;
    var id = cart.getDataSet(event, 'id');
    cart.postCartAdd(id, (res) => {
      if (res.error_code == 8888) {
        that.setData({
          cartArr: res.msg.list,
          total_num: res.msg.total_num,
          total_price: res.msg.total_price,
          cartAll: res.msg.cartAll
        })
      } else {
        cart.tanShowToast(res.msg, 'loading');
      }
    })
  },
  //单个是否选中
  status: function (event){
    var that = this;
    var id = cart.getDataSet(event, 'id');
    var status = cart.getDataSet(event, 'status');
    var status = status == 1 ? 0 : 1;
    cart.postCartStatus(id, status, (res) => {
      if (res.error_code == 8888) {
        that.setData({
          cartArr: res.msg.list,
          total_num: res.msg.total_num,
          total_price: res.msg.total_price,
          cartAll: res.msg.cartAll
        })
      } else {
        cart.tanShowToast(res.msg, 'loading');
      }
    })
  },
  //全选全不选
  all: function () {
    var that = this;
    var all = that.data.cartAll;
    cart.postCartAll(all, (res) => {
      if (res.error_code == 8888) {
        that.setData({
          cartArr: res.msg.list,
          total_num: res.msg.total_num,
          total_price: res.msg.total_price,
          cartAll: res.msg.cartAll
        })
      } else {
        cart.tanShowToast(res.msg, 'loading');
      }
    })
  },
  //立即结算
  PayCart:function(){
    var that = this;
    cart.PayCart((res) => {
      if (res.error_code == 8888) {
        //进行支付
        wx.requestPayment({
          timeStamp: res.msg.timeStamp.toString(),
          nonceStr: res.msg.nonceStr,
          package: res.msg.package,
          signType: res.msg.signType,
          paySign: res.msg.paySign,
          success: function (succ) {
            cart.popUpLss('提示', '支付成功！', '../order/order');
          },
          fail: function (err) {
            cart.popUpLs('提示', '支付失败，请重试！');
            that._loadData();
          }
        })
      } else if (res.error_code == 9999){
        cart.showModalNav('提示', res.msg,'../location/location');
      } else if (res.error_code == 6666){
        cart.popUpLs('提示', res.msg);
      }
    })
  },
  onPullDownRefresh: function () {
    this._loadData(() => {
      wx.stopPullDownRefresh()
    })
  },
  //分享效果
  onShareAppMessage: function () {
    return {
      title: 'wShop',
      path: 'pages/index/index'
    }
  } 
})