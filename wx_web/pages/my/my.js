import { My } from 'my-model.js';
var my = new My();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadingHidden: false,//数据是否加载完成
    user:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user = JSON.parse(wx.getStorageSync('user'));
    that.setData({
      user:user
    });
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, my.requestTime())
  },
  order:function(){
    wx.navigateTo({
      url: '../order/order'
    })
  },  
  location: function () {
    wx.navigateTo({
      url: '../location/location'
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