import { Location } from 'location-model.js';
var location = new Location();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    dataErr: false, 
    loadingHidden: false,//数据是否加载完成
    locationArr: [],//收货地址数组
  },
  add_location:function (e) {
    var that = this;
    wx.chooseAddress({
      success: function (datas) {
        if (datas.errMsg == "chooseAddress:ok") {
          var data = {
            "cityName": datas.cityName,
            "countyName": datas.countyName,
            "detailInfo": datas.detailInfo,
            "nationalCode": datas.nationalCode,
            "postalCode": datas.postalCode,
            "provinceName": datas.provinceName,
            "telNumber": datas.telNumber,
            "userName": datas.userName,
          }
          location.addLocation(data, (res) => {
            if (res.error_code == 8888) {
              that.setData({
                locationArr: res.msg,
                dataErr: false
              })
            }else{
              location.tanShowToast(res.msg, 'loading');
            }
            
          })
        } else {
          location.tanShowToast('网路错误！', 'loading');
        }
      }
    })
    wx.getSetting({
      success: function (res) {
        if (!res.authSetting['scope.address']) {
          location.popUpLss('提示', '请进入授权设置进行授权设置', '../my/my') 
        }
      }
    })
  },
  add_location_list(){
    location.navigateTo('../add_location/add_location');
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
    }, location.requestTime())  
  },
  _loadData: function (callback) {
    var that = this;
    location.locationList((res) => {
      if (res.error_code == 8888) {
        if (res.msg.length == 0) {
          that.setData({
            dataErr: true
          })
        } else {
          that.setData({
            dataErr: false
          })
        }
        that.setData({
          locationArr: res.msg
        })
      }
    })
  },
  status: function (event){
    var that = this;
    var id = location.getDataSet(event, 'id');
    location.locationStatus(id,(res) => {
      if (res.error_code == 8888) {
        that.setData({
          locationArr: res.msg
        })
      }
    })
  },
  locationDelete: function (event) {
    var that = this;
    var id = location.getDataSet(event, 'id');
    location.locationDelete(id, (res) => {
      if (res.error_code == 8888) {
        that.setData({
          locationArr: res.msg
        })
        console.log(res.msg)
        if (res.msg.length<1){
          that.setData({
            dataErr: true
          })
        }
      }else{
        location.tanShowToast(res.msg, 'loading');
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