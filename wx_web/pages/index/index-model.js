import { Base } from '../../utils/base.js';
class Index extends Base {
  constructor() {
    super();
  }
  getPv(callBack) {
    var that = this;
    var params = {
      url: 'getPv',
      sCallBack: function (res) {
        callBack && callBack(res);
      }
    };
    that.request(params);
  };
  getBannerData(callBack) {
    var that = this;
    var params = {
      url: 'banner',
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  getGoods( type, page, callBack) {
    var that = this;
    var params = {
      url: 'getGoods/' + type + '/' + page,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  determineUser(detail,callBack) {
    var that = this;
    var params = {
      url: 'token/determine',
      type: 'POST',
      data: detail,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Index }