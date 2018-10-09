import { Order } from 'order-model.js';
var order = new Order();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadingHidden: false,
    dataErr: false,    
    loading_box: false,//上啦加载更多时的动画
    loading_box_title: '',//提示语  
    tabs: ['全部', '待付款', '待发货', '待收货', '待评价'],
    status: 0,
    sliderOffset: 0,
    sliderLeft: 0,
    page:1,
    orderArr:[]
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    wx.getSystemInfo({
      success(res) {
        that.setData({
          sliderLeft: (res.windowWidth / 5 - 50) / 2,
        })
      }
    })
    that._loadData();
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, order.requestTime())  
  },
  _loadData: function (callback) {
    var that = this;
    var status = that.data.status;
    order.orderList(1, status, (res) => {
      if (res.error_code == 8888){
        if (res.msg.data.length==0){
          that.setData({
            dataErr:true           
          })
        }else{
          that.setData({
            dataErr: false     
          }) 
        }
        that.setData({
          orderArr: res.msg.data,
          page: 1
        })
      }
    })
    callback && callback();
  },
  //tab栏切换
  tabClick:function(e) {
    var that = this;
    that.setData({
      sliderOffset: e.currentTarget.offsetLeft,
      status: e.currentTarget.dataset.id,
    });
    that._loadData();
  },
  //取消订单
  orderDelete: function (event) {
    var that = this;
    var id = order.getDataSet(event, 'id');
    order.orderDelete(id, (res) => {
      if (res.error_code == 8888) {
        var index = order.getDataSet(event, 'index');
        var orderArr = that.data.orderArr;
        orderArr = order.deleteArrItem(orderArr, index);
        if (orderArr.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          orderArr: orderArr
        })
      }else{
        order.tanShowToast(res.msg,'loading');
      }
    })
  },
  //去支付
  orderPay: function(event) {
    var that = this;
    var id = order.getDataSet(event, 'id');
    order.orderPay(id, (res) => {
      if (res.error_code == 8888) {
        //进行支付
        wx.requestPayment({
          timeStamp: res.msg.timeStamp.toString(),
          nonceStr: res.msg.nonceStr,
          package: res.msg.package,
          signType: res.msg.signType,
          paySign: res.msg.paySign,
          success: function (succ) {
            order.popUpLss('提示', '支付成功！', '../my/my');
          },
          fail: function (err) {
            order.popUpLs('提示', '支付失败，请重试！');
          }
        })
      }else{
        order.popUpLs('提示',res.msg);
      }
    })
  },
  //确定收货
  orderConfirm:function (event) {
    var that = this;
    var id = order.getDataSet(event, 'id');
    order.orderConfirm(id, (res) => {
      if (res.error_code == 8888) {
        var index = order.getDataSet(event, 'index');
        var orderArr = that.data.orderArr;
        orderArr = order.deleteArrItem(orderArr, index);
        if (orderArr.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          orderArr: orderArr
        })
      } else {
        order.tanShowToast(res.msg, 'loading');
      }
    })
  },
  
  comment: function(event) {
    var id = order.getDataSet(event, 'id');
    order.navigateTo('../comment/comment?id=' + id)
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
    var status = that.data.status;
    var page = that.data.page + 1;
    order.orderList(page, status, (res) => {
      if (res.error_code == 8888) {
        if (res.msg.data.length) {
          var orderArr = that.data.orderArr;
          for (var i = 0; i < res.msg.data.length; i++) {
            orderArr.push(res.msg.data[i]);
          }
          that.setData({
            orderArr: orderArr,
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
          }, order.noDataTime())
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