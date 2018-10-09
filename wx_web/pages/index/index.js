import { Index } from 'index-model.js';
var index = new Index();
Page({
  data: {
    canIUse: wx.canIUse('button.open-type.getUserInfo'),//微信的版本是否支持微信授权登陆
    login: false,//是否成功授权登陆
    loadingHidden:false,//数据是否加载完成
    loading_box:false,//上啦加载更多时的动画
    loading_box_title:'',//提示语
    bannerArr: [],//轮播图数据
    type:0,//商品类型 0表示所有类型
    page:1, //当前页数，默认为第一页
    goodsArr:[]
  },
  // 授权登陆
  login:function(e) {
    var that = this;
    if (e.detail.errMsg == 'getUserInfo:ok'){
      index.determineUser(e.detail,(res) => {
        if (res.error_code == 8888) {
           //将用户微信信息存入缓存
           wx.setStorageSync('user', e.detail.rawData);
           that.setData({
             login:true
           }); 
        }else{
           index.tanShowToast(res.msg,'loading');
        }
      })
    }
  },
  onLoad: function (){
    var that = this;
    wx.getSetting({
      success: function (res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            lang:'zh_CN',
            success: function (res) {
              wx.setStorageSync('user', res.rawData);
              that.setData({
                login: true
              }); 
            }
          })
        }
      }
    })
    that._loadData();
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, index.requestTime())  
  },
  _loadData: function (callback) {
    var that = this;
    index.getPv((res) => {
    });
    index.getBannerData((res) => {
      if (res.error_code == 8888) {
        that.setData({
          bannerArr: res.msg
        }) 
      }
    })
    var type = that.data.type;
    index.getGoods(type,1, (res) => {
      if (res.error_code == 8888) {
        that.setData({
          goodsArr: res.msg.data,
          page: 1
        }) 
      }
    })
    callback && callback();
  },
  onShow:function(){
    var that = this;
    if(wx.getStorageSync('user')){
      that.setData({
        login: true
      });
    }else{
      that.setData({
        login: false
      });
    };
  },
  product: function (event){
    var id = index.getDataSet(event,'id');
    index.navigateTo('../product/product?id='+ id)
  },
  /**
  * 页面上拉触底事件的处理函数
  */
  onReachBottom: function () {
    var that = this;
    that.setData({
      loading_box: true,
      loading_box_title: '数据加载中...'
    })
    var page = that.data.page + 1;
    var type = that.data.type;
    index.getGoods(type, page, (res) => {
      if (res.error_code == 8888) {
        if (res.msg.data.length) {
          var goodsArr = that.data.goodsArr;
          for (var i = 0; i < res.msg.data.length; i++) {
            goodsArr.push(res.msg.data[i]);
          }
          that.setData({
            goodsArr: goodsArr,
            page: page,
            loading_box: false,
            loading_box_title: ''
          });
        } else {
          that.setData({
            loading_box_title: '没有数据了...'
          })
          setTimeout(() => {
            that.setData({
              loading_box: false,
              loading_box_title: ''
            })
          }, index.noDataTime())
        }
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