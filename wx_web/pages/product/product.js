import { Product } from 'product-model.js';
var product = new Product();
Page({
  data: {
    loadingHidden: false,//数据是否加载完成
    dataErr: false, 
    getGoodsContent:[],//商品详情
    num:0,//购物车商品数量
    id:'',//当前产品id
    countsArray: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    productCounts: 1,//当前选择的数量
    isShake:false,//加入购物车时的动画
    tabs: ['图文详情', '商品参数', '商品评论'],//tab数组
    activeIndex: 0,//当前选中tab的下标
    sliderOffset: 0,//tab滑动的幅度
    sliderLeft: 0,//tab滑动到的left值
    starMap: [1,2,3,4,5],
    animation: ''//分享按钮的滑动效果
  },
  onLoad: function (options) {
    var that = this;
    var id = options.id;
    that.setData({
      id: id
    });
    that.animation = wx.createAnimation();
    wx.getSystemInfo({
      success(res) {
        that.setData({
          sliderLeft: (res.windowWidth / 3 - 50) / 2,
        })
      }
    })
    that._loadData();
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, product.requestTime())
  },
  _loadData: function (callback) {
    var that = this;
    var id = that.data.id;
    product.getGoodsContent(id,(res) => {
      if (res.error_code == 8888) {
        res.msg.commodity_parameters = res.msg.commodity_parameters.replace(/\<img/gi, '<img style="max-width:100%;height:auto" ')
        res.msg.graphic_details = res.msg.graphic_details.replace(/\<img/gi, '<img style="max-width:100%;height:auto" ')
        if (res.msg.comment.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          getGoodsContent: res.msg,
          num: res.msg.num
        })
      }
    })
    callback && callback();
  },
  //选择购买数目
  bindPickerChange: function (e) {
    this.setData({
      productCounts: this.data.countsArray[e.detail.value]
    })
  },
  /*添加到购物车*/
  onAddingToCartTap: function () {
    var that = this;
    var num = that.data.productCounts;
    var goods_id = that.data.id;
    console.log(goods_id + '-------------' + num);
    product.postAddCart(goods_id, num, (res) => {
      console.log(res);
      if(res.error_code == 8888) {
        product.tanShowToast('购买成功！','success');
        that.setData({
          isShake: true,
          num:res.msg
        });
        that.setData({
          isShake: false
        });
      }else{
        product.tanShowToast(res.msg,'loading'); 
      }
    })
  },
  //分享
  shares:function(){
    var that = this;
    that.animation.translate3d(0, 0, 0).step();
    that.setData({
      animation: that.animation.export()
    })
  },
  /**
   * 取消分享事件
   */
  cancelShare: function () {
    var that = this;
    that.animation.translate3d(0, 1000, 0).step();
    that.setData({
      animation: that.animation.export()
    })
  },
  
  //点击tab时的切换
  tabClick:function(e) {
    this.setData({
      sliderOffset: e.currentTarget.offsetLeft,
      activeIndex: e.currentTarget.dataset.id
    })
  },
  //跳转购物车
  cart:function(){
    wx.switchTab({
      url: '../cart/cart'
    })
  },
  onPullDownRefresh: function () {
    this._loadData(() => {
      wx.stopPullDownRefresh()
    })
  },
  //分享效果
  onShareAppMessage:function () {
    var that = this;
    var id = that.data.id;
    return {
      title: 'wShop',
      path: 'pages/product/product?id=' + id
    }
  } 
})