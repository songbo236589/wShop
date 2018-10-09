import { AddLocation } from 'add_location-model.js';
var add_location = new AddLocation();
import { Location } from '../location/location-model.js';
var location = new Location();
var t = 0;
var show = false;
var moveY = 200;
Page({
  data: {
    loadingHidden: false,//数据是否加载完成
    dq: '地区信息',
    dqs: '',
    province_id: 0,       //省id  
    provinceArr: [{ "id": "0", "name": "选择省", "pid": "0" }],      //省数组
    city_id: 0,          //市id
    cityArr: [{ "id": "0", "name": "选择市", "pid": "0" }],         //市数组
    county_id: 0,         //县id
    countyArr: [{ "id": "0", "name": "选择区县", "pid": "0" }],        //县数组
    regionArr: [],       //全体省市区数据
    userName:'',//收货人
    telNumber: '',//收货人手机号码
    detailInfo: '',//详细收货地址信息

  },
  onLoad: function () {
    var that = this;
    that._loadData();
  },
  _loadData: function () {
    var that = this;
    add_location.getRegion((res) => {
      if(res.error_code == 8888) {
        var regionArr = res.msg;
        if (regionArr.length > 0) {
          var provinceArr = that.data.provinceArr;
          for (var i = 0; i < regionArr.length; i++) {
            if (regionArr[i].pid == 1) {
              provinceArr.push(regionArr[i]);
            }
            if (regionArr[i].type == 2) {
              regionArr[i].name += '市';
            }
          }

          that.setData({
            loadingHidden: true,
            regionArr: regionArr,
            provinceArr: provinceArr
          });
        }
      }
    })  
  },
  //滑动事件
  bindChange: function (e) {
    var that = this;
    var val = e.detail.value;
    add_location.regionArrDataList(this, val);
  },
  //移动按钮点击事件
  translate: function (e) {
    var that = this;
    if (t == 0) {
      moveY = 0;
      show = false;
      t = 1;
    } else {
      moveY = 200;
      show = true;
      t = 0;
    }
    add_location.animationEvents(this, moveY, show);
  },
  //隐藏弹窗浮层
  hiddenFloatView(event) {
    var that = this;
    var data_id = add_location.getDataSet(event, 'id');
    var dqs = that.data.dqs;
    if (data_id == 666) {
      if (dqs != '') {
        that.setData({
          dq: dqs.split(",").join("")
        });
      }
      if (dqs == '地区信息') {
        that.setData({
          province_id: 0,
          city_id: 0,
          county_id: 0
        });
      }
    }
    moveY = 200;
    show = true;
    t = 0;
    add_location.animationEvents(this, moveY, show);
  },
  userName: function (e) {
    var that = this;
    that.setData({
      userName: e.detail.value
    });
  },
  telNumber: function (e) {
    var that = this;
    that.setData({
      telNumber: e.detail.value
    });
  },
  detailInfo: function (e) {
    var that = this;
    that.setData({
      detailInfo: e.detail.value
    });
  },
  add_location:function(){
    var that = this;
    var dqs = that.data.dqs.split(",");
    var userName = that.data.userName;
    var telNumber = that.data.telNumber;
    var detailInfo = that.data.detailInfo;
    if (userName == '') {
      add_location.tanShowToast('请输入收货人', 'loading');
      return;
    }
    if (!(/^1[34578]\d{9}$/.test(telNumber))) {
      add_location.tanShowToast('手机号错误', 'loading');
      return;
    }
    if (detailInfo == '') {
      add_location.tanShowToast('请输入详细地址', 'loading');
      return;
    }
    if(dqs.length != 3){
      add_location.tanShowToast('请选择地区信息','loading');   
      return;
    }else{
      var provinceName = dqs[0];
      var cityName = dqs[1];
      var countyName = dqs[2];
      var data = {
        "cityName": cityName,
        "countyName": countyName,
        "detailInfo": detailInfo,
        "provinceName": provinceName,
        "telNumber": telNumber,
        "userName": userName,
        "wShop":1
      };
      location.addLocation(data, (res) => {
        if (res.error_code == 8888){
          add_location.showModalNav('提示',res.msg,'../location/location');
        } else {
          add_location.tanShowToast(res.msg, 'loading');
        }
      })
    }
  },
  //分享效果
  onShareAppMessage: function () {
    return {
      title: '西安设计家',
      path: 'pages/index/index'
    }
  }
})



