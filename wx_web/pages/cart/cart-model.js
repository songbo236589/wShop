import { Base } from '../../utils/base.js';
class Cart extends Base {
  constructor() {
    super();
  }
  getCartList(callBack) {
    var that = this;
    var params = {
      url: 'getCartList',
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  postCartSubtract(id, callBack) {
    var that = this;
    var params = {
      url: 'postCartSubtract',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  postCartAdd(id, callBack) {
    var that = this;
    var params = {
      url: 'postCartAdd',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  postCartStatus(id, status, callBack) {
    var that = this;
    var params = {
      url: 'postCartStatus',
      type: 'POST',
      data: { id: id, status: status},
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  postCartAll(all, callBack) {
    var that = this;
    var params = {
      url: 'postCartAll',
      type: 'POST',
      data: { all: all },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  PayCart(callBack) {
    var that = this;
    var params = {
      url: 'PayCart',
      type: 'POST',
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Cart }