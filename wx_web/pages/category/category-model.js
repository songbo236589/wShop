import { Base } from '../../utils/base.js';
class Category extends Base {
  constructor() {
    super();
  }
  getGoodsTypeList(page,callBack) {
    var that = this;
    var params = {
      url: 'getGoodsTypeList/' + page,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  getGoods(type, page, callBack) {
    var that = this;
    var params = {
      url: 'getGoods/' + type + '/' + page,
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Category }