import { Base } from '../../utils/base.js';
class AddLocation extends Base {
  constructor() {
    super();
  }
  getRegion(callBack) {
    var that = this;
    var params = {
      url: 'getRegion',
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  //动画事件
  animationEvents(that, moveY, show) {
    that.animation = wx.createAnimation({
      transformOrigin: "50% 50%",
      duration: 400,
      timingFunction: "ease",
      delay: 0
    }
    )
    that.animation.translateY(moveY + 'vh').step()
    that.setData({
      animation: that.animation.export(),
      show: show
    })
  };
  //省市区联动数据处理
  regionArrDataList(that, val) {
    var regionArr = that.data.regionArr;
    var cityArr = [{ "id": "0", "name": "选择市", "pid": "0" }];
    var countyArr = [{ "id": "0", "name": "选择区县", "pid": "0" }];
    var province_id = that.data.province_id;
    var city_id = that.data.city_id;
    var county_id = that.data.county_id;
    var v0 = val[0];
    var v1 = val[1];
    var v2 = val[2];
    if (v0 != 0) {
      var provinceArr_id = that.data.provinceArr[v0].id;
      if (provinceArr_id != province_id) {
        for (var i = 0; i < regionArr.length; i++) {
          if (regionArr[i].pid == provinceArr_id) {
            cityArr.push(regionArr[i]);
          }
        }
        that.setData({
          cityArr: cityArr,
          countyArr: [{ "id": "0", "name": "选择区县", "pid": "0" }],
          province_id: provinceArr_id,
          dqs: that.data.provinceArr[v0].name + ","
        });
      }
    } else {
      that.setData({
        cityArr: [{ "id": "0", "name": "选择市", "pid": "0" }],
        dqs: '地区信息'
      });
    }
    if (v1 != 0) {
      if (v0 != 0) {
        var cityArr_id = that.data.cityArr[v1].id;
        if (cityArr_id != city_id) {
          for (var i = 0; i < regionArr.length; i++) {
            if (regionArr[i].pid == cityArr_id) {
              countyArr.push(regionArr[i]);
            }
          }
          var dqs = that.data.dqs;
          var dqsArr = dqs.split(',');
          dqsArr[1] = that.data.cityArr[v1].name;
          dqs = dqsArr.join(',');
          that.setData({
            city_id: cityArr_id,
            countyArr: countyArr,
            dqs: dqs
          });
        }
      }
    } else {
      that.setData({
        countyArr: [{ "id": "0", "name": "选择区县", "pid": "0" }]
      });
    }
    if (v2 != 0) {
      if (v0 != 0 && v1 != 0) {
        var countyArr_id = that.data.countyArr[v2].id;
        if (countyArr_id != county_id) {
          var dqs = that.data.dqs;
          var dqsArr = dqs.split(',');
          dqsArr[2] = that.data.countyArr[v2].name;
          dqs = dqsArr.join(',');
          that.setData({
            county_id: countyArr_id,
            dqs: dqs
          });
        }
      }
    }
  }
}

export { AddLocation }