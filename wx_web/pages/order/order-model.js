import { Base } from '../../utils/base.js';
class Order extends Base {
  constructor() {
    super();
  }
  orderList(page, status, callBack) {
    var that = this;
    var params = {
      url: 'orderList/' + page + '/' + status,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  orderDelete(id, callBack) {
    var that = this;
    var params = {
      url: 'orderDelete',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  orderPay(id, callBack) {
    var that = this;
    var params = {
      url: 'orderPay',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  orderConfirm(id, callBack) {
    var that = this;
    var params = {
      url: 'orderConfirm',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Order }