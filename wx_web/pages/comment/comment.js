import { Comment } from 'comment-model.js';
var comment = new Comment();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadingHidden: false,//数据是否加载完成
    starMap: ['非常差','差','一般','好','非常好'],
    commentArr:[]     //存放商品数据
  },
  //点击星级
  myStarChoose(event) {
    var that = this;
    var commentArr = that.data.commentArr; 
    var star = comment.getDataSet(event, 'star');
    var index = comment.getDataSet(event, 'index');  
    commentArr[index]['star'] = star;
    that.setData({
      commentArr: commentArr
    });
  },
  //输入评论
  content: function (event) {
    var that = this;
    var commentArr = that.data.commentArr; 
    var content = event.detail.value;
    var index = comment.getDataSet(event, 'index');  
    commentArr[index]['content'] = content;
    that.setData({
      commentArr: commentArr
    });
  },
  //信息提交
  commentT:function(){
    var that = this;
    var data = that.data.commentArr;
    comment.commentT(data, (res) => {
      if (res.error_code == 8888) {
        comment.showModalNav('提示',res.msg, '../order/order');
      }else{
        comment.popUpLs('提示', '网络错误！'); 
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var id = options.id;
    var that = this;
    that._loadData(id);
    setTimeout(() => {
      that.setData({
        loadingHidden: true
      });
    }, comment.requestTime())  
  },
  _loadData: function (id) {
    var that = this;
    comment.getOrderItem(id,(res) => {
      if (res.error_code == 8888) {
        for (var i = 0; i < res.msg.length; i++) {
          res.msg[i]['star'] = 5
        }
        that.setData({
          commentArr: res.msg
        })
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