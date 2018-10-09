import { Category } from 'category-model.js';
var category = new Category();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    dataErr: false,  
    id:'',//商品分类id
    tanslate:'0',//右侧动画浮动大小
    goodsCategoryArr: [],//商品分类
    index:0,//当前的分类下标
    goodsArr:[],//商品列表
    offsetTop: 0,//左侧动画浮动大小
    page:1,//当前的数据页数
    loadingHidden: false//数据是否加载完成
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    that._loadData();
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, category.requestTime())  
  },
  _loadData: function (callback) {
    var that = this;
    category.getGoodsTypeList(1,(res) => {
      if (res.error_code == 8888) {
        if (res.msg.list.data.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          goodsCategoryArr: res.msg.goods_category,
          goodsArr:res.msg.list.data,
          id: res.msg.goods_category[0].id,
          page: 1,
          length: res.msg.list.data.length
        })
      }
    })
    callback && callback();
  },
  changeCategory: function (event){
    var that = this;
    var id = category.getDataSet(event, 'id');
    var index = category.getDataSet(event, 'index');
    category.getGoods(id, 1, (res) => {
      if(res.error_code == 8888) {
        if (res.msg.data.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          goodsArr: res.msg.data,
          index:index,
          id: id,
          tanslate: '-' + index * 100 + 'vh',
          offsetTop: event.currentTarget.offsetTop,
          page: 1,
          length: res.msg.data.length
        })
      }
    })
  },
  product: function (event) {
    var id = category.getDataSet(event, 'id');
    category.navigateTo('../product/product?id=' + id)
  },
  /**
  * 页面上拉触底事件的处理函数
  */
  scroll: function (event) {
    var that = this;
    var page = that.data.page + 1;
    var id = that.data.id;
    category.getGoods(id, page, (res) => {
      if (res.error_code == 8888) {
        if (res.msg.data.length) {
          var goodsArr = that.data.goodsArr;
          for (var i = 0; i < res.msg.data.length; i++) {
            goodsArr.push(res.msg.data[i]);
          }
          that.setData({
            goodsArr: goodsArr,
            page: page,
          });
        }
      }
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