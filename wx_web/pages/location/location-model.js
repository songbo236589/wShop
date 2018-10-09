import { Base } from '../../utils/base.js';
class Location extends Base {
  constructor() {
    super();
  }
  addLocation(data, callBack) {
    var that = this;
    var params = {
      url: 'addLocation',
      type: 'POST',
      data:data,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  }; 
  locationList(callBack) {
    var that = this;
    var params = {
      url: 'locationList',
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  locationStatus(id, callBack) {
    var that = this;
    var params = {
      url: 'locationStatus',
      type: 'POST',
      data: {id:id},
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  locationDelete(id, callBack) {
    var that = this;
    var params = {
      url: 'locationDelete',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Location }