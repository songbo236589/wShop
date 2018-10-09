import { Config } from '../utils/config.js';
import { Token } from 'token.js';
class Base{
  constructor(){
    this.baseRequestUrl = Config.restUrl;
  }
  requestTime(){
    return Config.requestTime;
  }
  noDataTime() {
    return Config.noDataTime;
  }
  
  //http请求
  request(params){
    var that = this,
    url = this.baseRequestUrl + params.url;
    if(!params.type){
      params.type='GET';
    }
    wx.request({
      url: url,
      data:params.data,
      method:params.type,
      header:{
        'content-type':'application/json',
        'token':wx.getStorageSync('token')
      },
      success:function(res){
        // if(params.sCallBack){
        //   params.sCallBack(res);
        // }
       var code = res.statusCode.toString();
       var startChar = code.charAt(0);
       if (startChar == '2' || startChar == '4'){
         params.sCallBack && params.sCallBack(res);
       }
       if (res.statusCode == '305'){
         that._refetch(params);
       }
      },
      fail:function(err){
        console.log(err);
      }
    })
  }
  _refetch(param) {
    var token = new Token();
    token.getTokenFromServer((token) => {
      this.request(param);
    });
  }
  //跳转时获取代用参数
  getDataSet(event,key){
    return event.currentTarget.dataset[key];
  };
  //单一的跳转
  navigateTo(url) {
    wx.navigateTo({
      url: url
    });
    return false;
  }
  //确认和取消弹框  参数1：提示标题，参数2：提示信息  参数3：确认跳转   参数4：取消跳转
  popUp(title,content,successUrl,errorUrl){
    wx.showModal({
      title: title,
      content: content,
      success: function (res) {
        if (res.confirm) {
          wx.navigateTo({
            url: successUrl
          })
        } else {
          wx.switchTab({
            url: errorUrl
          })
        }
      }
    })
  }
  
  //弹框只能确认
  popUpLs(title, content) {
    wx.showModal({
      title: title,
      content: content,
      showCancel:false
    });
    return false;
  }
  //弹框只能确认
  popUpLss(title, content, successUrl) {
    wx.showModal({
      title: title,
      content: content,
      showCancel: false,
      success: function (res) {
      if (res.confirm){
          wx.switchTab({
            url: successUrl
          })
        } 
      }
    });
  }
  //弹框只能确认
  showModalNav(title, content, successUrl) {
    wx.showModal({
      title: title,
      content: content,
      showCancel: false,
      success: function (res) {
        if (res.confirm) {
          wx.navigateTo({
            url: successUrl
          })
        }
      }
    });
  }
  //弹框 参数1：弹框提示语   参数2：图标：success成功  loading失败  参数3：延迟时间  参数4：是否透明  默认false 
  tanShowToast(title, icon, duration=1800, mask=false){
    wx.showToast({
      title: title,
      icon: icon,
      duration: duration,
      mask: mask
    })  
    
  }
  getCode(that) {
    var time = that.data.time;
    if (time == '获取验证码' || time == '重新发送') {
      var interval = setInterval(function () {
        var currentTime = that.data.currentTime;
        currentTime--;
        that.setData({
          time:'等待' + currentTime + '秒',
          currentTime: currentTime
        })
        if (currentTime <= 0) {
          clearInterval(interval);
          that.setData({
            time: '重新发送',
            currentTime: 61
          });

        }

      }, 1000)
    }
  }
  //根据下表删除数组元素
  deleteArrItem(arr,index) {
    var xArr = [];
    for (var i = 0; i < arr.length; i++) {
        if(i != index){
          xArr.push(arr[i])
        }
    }
    return xArr;
  } 
}
export { Base };