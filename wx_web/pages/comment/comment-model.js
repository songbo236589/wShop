import { Base } from '../../utils/base.js';
class Comment extends Base {
  constructor() {
    super();
  }
  getOrderItem(id, callBack) {
    var that = this;
    var params = {
      url: 'getOrderItem/' + id,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  commentT(data, callBack) {
    var that = this;
    var params = {
      url: 'commentT',
      type: 'POST',
      data: data,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Comment }